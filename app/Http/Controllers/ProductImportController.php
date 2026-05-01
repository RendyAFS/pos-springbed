<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ProductImportController extends Controller
{
    public function downloadTemplate(): BinaryFileResponse
    {
        $templatePath = public_path('assets/templates/product_import_template.xlsx');

        if (!file_exists($templatePath)) {
            abort(404, 'Template file not found.');
        }

        return response()->download($templatePath, 'product_import_template.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
