<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use App\Imports\ProductImport;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('importProducts')
                ->label('Import Excel')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('success')
                ->modalHeading('Import Products from Excel')
                ->modalDescription('Upload an Excel file (.xlsx) to bulk import products. Download the template below before uploading.')
                ->modalWidth('lg')
                ->modalSubmitActionLabel('Import')
                ->modalFooterActions(fn(Action $action) => [
                    Action::make('downloadTemplate')
                        ->label('Download Template')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->color('gray')
                        ->url(route('products.import.template'))
                        ->openUrlInNewTab()
                        ->cancelParentActions(false),
                    $action->getModalSubmitAction(),
                    $action->getModalCancelAction(),
                ])
                ->schema([
                    FileUpload::make('import_file')
                        ->label('Excel File (.xlsx)')
                        ->required()
                        ->disk('local')
                        ->directory('imports/products')
                        ->acceptedFileTypes([
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'application/vnd.ms-excel',
                        ])
                        ->maxSize(5120)
                        ->helperText('Only .xlsx files are accepted. Max file size: 5MB.')
                ])
                ->action(function (array $data): void {
                    $filePath = $data['import_file'];

                    if (!$filePath) {
                        Notification::make()
                            ->title('No file uploaded')
                            ->danger()
                            ->send();
                        return;
                    }

                    $fullPath = Storage::disk('local')->path($filePath);

                    $import = new ProductImport();

                    try {
                        Excel::import($import, $fullPath);
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Import failed')
                            ->body('Error reading file: ' . $e->getMessage())
                            ->danger()
                            ->duration(10000)
                            ->send();

                        // Cleanup uploaded file
                        Storage::disk('local')->delete($filePath);
                        return;
                    }

                    // Cleanup uploaded file
                    Storage::disk('local')->delete($filePath);

                    $importedCount = $import->importedCount;
                    $errors        = $import->getErrors();

                    if ($importedCount > 0 && empty($errors)) {
                        Notification::make()
                            ->title('Import successful!')
                            ->body("Successfully imported {$importedCount} product(s).")
                            ->success()
                            ->duration(6000)
                            ->send();
                    } elseif ($importedCount > 0 && !empty($errors)) {
                        $errorSummary = collect($errors)
                            ->take(5)
                            ->map(fn($e) => "Row {$e['row']} ({$e['name']}): " . implode(', ', $e['errors']))
                            ->join("\n");

                        $extraCount = count($errors) > 5 ? ' (+' . (count($errors) - 5) . ' more errors)' : '';

                        Notification::make()
                            ->title("Imported {$importedCount} product(s) with " . count($errors) . ' error(s)')
                            ->body($errorSummary . $extraCount)
                            ->warning()
                            ->duration(12000)
                            ->send();
                    } else {
                        $errorSummary = collect($errors)
                            ->take(5)
                            ->map(fn($e) => "Row {$e['row']} ({$e['name']}): " . implode(', ', $e['errors']))
                            ->join("\n");

                        Notification::make()
                            ->title('Import failed — no products were imported')
                            ->body($errorSummary)
                            ->danger()
                            ->duration(12000)
                            ->send();
                    }
                }),

            CreateAction::make(),
        ];
    }
}
