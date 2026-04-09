<!DOCTYPE html>
<html>

<head>
    <style>
        @page {
            size: 58mm auto;
            margin: 2mm 2mm 2mm 2mm;
        }

        html,
        body {
            width: 58mm;
            margin: 0;
            padding: 0;
            font-family: monospace;
            font-size: 12px;
        }

        .paper {
            width: 58mm;
            max-width: 58mm;
            font-family: monospace;
            font-size: 12px;
        }

        body {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        pre {
            margin: 0;
            white-space: pre;
            line-height: 1.2;
        }

        @media print {

            html,
            body {
                width: 58mm;
            }
        }
    </style>
</head>

<body onload="window.print()">

    @php
        function line($left = '', $right = '', $width = 32)
        {
            $left = (string) $left;
            $right = (string) $right;

            $space = $width - strlen($left) - strlen($right);
            if ($space < 1) {
                $space = 1;
            }

            return $left . str_repeat(' ', $space) . $right;
        }

        function center($text, $width = 32)
        {
            $padding = floor(($width - strlen($text)) / 2);
            return str_repeat(' ', max(0, $padding)) . $text;
        }

        function separator($char = '-', $width = 32)
        {
            return str_repeat($char, $width);
        }
    @endphp

    <div class="paper">
    <pre>
{{ center(strtoupper($transaction->storeSetting->name ?? 'TOKO')) }}
{{ center($transaction->storeSetting->address ?? '-') }}

{{ separator() }}

{{ line('Kode', $transaction->transaction_code) }}
{{ line('Tanggal', $transaction->transaction_date) }}
{{ line('Kasir', $transaction->creator->name ?? '-') }}

{{ separator() }}

@foreach ($transaction->transactionItems as $item)
@php
    $name = $item->product->name ?? $item->bundle->name;
@endphp
{{ substr($name, 0, 32) }}
{{ line($item->qty . ' x ' . number_format($item->selling_price, 0, ',', '.'), number_format($item->subtotal, 0, ',', '.')) }}
@endforeach

{{ separator() }}

{{ line('Subtotal', number_format($transaction->subtotal, 0, ',', '.')) }}
{{ line('Diskon', number_format($transaction->discount_total, 0, ',', '.')) }}
{{ line('Ongkir', number_format($transaction->shiping_cost, 0, ',', '.')) }}

{{ separator() }}

{{ line('TOTAL', number_format($transaction->grand_total, 0, ',', '.')) }}

{{ separator() }}

{{ line('Bayar', $transaction->transactionPayment?->method?->getLabel()) }}
{{ line('Status', $transaction->transactionPayment?->status?->getLabel()) }}

{{ separator() }}

{{ center('Terima kasih 🙏') }}
{{ center('Selamat datang kembali') }}
</pre>
</div>

    <script>
        window.onafterprint = function() {
            window.close(); // auto close tab setelah print
        };
    </script>

</body>

</html>
