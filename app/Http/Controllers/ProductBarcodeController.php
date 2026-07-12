<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Writer\PngWriter;

class ProductBarcodeController extends Controller
{
    public function print(Product $product)
    {
        $product->load([
            'brand',
            'type',
            'size',
        ]);

        $qr = (new Builder(
            writer: new PngWriter(),
            data: (string) $product->id,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 320,
            margin: 10,
        ))->build();

        return Pdf::loadView('pdf.product-barcode', [
            'product' => $product,
            'dataUri' => $qr->getDataUri(),
        ])
            ->setPaper('a5', 'portrait')
            ->stream("barcode-{$product->sku}.pdf");
    }
}
