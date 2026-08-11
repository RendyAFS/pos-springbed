<!DOCTYPE html>
<html>

<head>
    <style>
        @page {
            size: A5 portrait;
            margin: 15mm;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            margin: 0;
            padding: 0;
            color: #222;
        }

        .wrapper {
            width: 100%;
            text-align: center;
        }

        .card {
            border: 1px solid #999;
            border-radius: 8px;
            padding: 18px;
        }

        .company {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .subtitle {
            font-size: 12px;
            color: #777;
            margin-bottom: 20px;
        }

        .qr {
            width: 240px;
            height: 240px;
            display: block;
            margin: 0 auto 20px;
        }

        .sku {
            font-size: 20px;
            font-family: monospace;
            font-weight: bold;
            margin-bottom: 12px;
        }

        .name {
            font-size: 18px;
            font-weight: bold;
            line-height: 1.4;
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            margin-top: 10px;
            border-collapse: collapse;
            font-size: 13px;
        }

        td {
            padding: 4px 0;
        }

        td:first-child {
            width: 90px;
            color: #666;
            text-align: left;
        }

        td:last-child {
            text-align: left;
            font-weight: bold;
        }

        .footer {
            margin-top: 25px;
            font-size: 11px;
            color: #888;
        }
    </style>
</head>

<body>

    <div class="wrapper">

        <div class="card">

            <div class="company">
                {{ config('app.name') }}
            </div>

            <div class="subtitle">
                Product QR Code
            </div>

            <img class="qr" src="{{ $dataUri }}">

            <div class="sku">
                {{ $product->sku ?: '-' }}
            </div>

            <div class="name">
                {{ $product->name }}
            </div>

            <table>

                <tr>
                    <td>Brand</td>
                    <td>{{ $product->brand?->name ?? '-' }}</td>
                </tr>

                <tr>
                    <td>Type</td>
                    <td>{{ $product->type?->name ?? '-' }}</td>
                </tr>

                <tr>
                    <td>Size</td>
                    <td>{{ $product->size?->name ?? '-' }}</td>
                </tr>

            </table>

            <div class="footer">
                Scan QR Code untuk identifikasi produk
            </div>

        </div>

    </div>

</body>

</html>
