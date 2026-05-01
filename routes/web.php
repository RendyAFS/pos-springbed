<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('filament.admin.auth.login');
});

Route::get('/transactions/{transaction}/print', [App\Http\Controllers\TransactionPrintController::class, 'print'])->name('transactions.print');
Route::get('/products/import/template', [App\Http\Controllers\ProductImportController::class, 'downloadTemplate'])->name('products.import.template');
