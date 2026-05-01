<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <style>
        @page {
            size: A5 portrait;
            margin: 5mm;
        }

        * {
            box-sizing: border-box;
        }

        .paper {
            width: 100%;
        }

        /* HEADER */
        .header-table {
            width: 100%;
            border-bottom: 2px solid #000;
            padding-bottom: 6px;
            margin-bottom: 6px;
        }

        .header-store-name {
            font-size: 18px;
            font-weight: bold;
            color: #c0392b;
            line-height: 1.1;
        }

        .header-store-sub {
            font-size: 11px;
            font-weight: bold;
            color: #c0392b;
        }

        .header-store-address {
            font-size: 9px;
            color: #333;
            margin-top: 2px;
            line-height: 1.4;
        }

        .header-right-table {
            border-collapse: collapse;
        }

        .header-right-table td {
            padding: 1px 3px;
            vertical-align: top;
        }

        .label {
            font-weight: bold;
            white-space: nowrap;
        }

        /* NOTA NO */
        .nota-no {
            font-size: 11px;
            font-weight: bold;
            margin-bottom: 4px;
            border-bottom: 1px solid #000;
            padding-bottom: 2px;
        }

        /* ITEMS TABLE */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 4px;
        }

        .items-table th {
            border: 1px solid #000;
            padding: 3px 4px;
            text-align: center;
            font-size: 12px;
        }

        .items-table td {
            border: 1px solid #000;
            padding: 3px 4px;
            font-size: 12px;
            vertical-align: top;
        }

        .col-qty {
            width: 14mm;
            text-align: center;
        }

        .col-name {
            width: auto;
        }

        .col-price {
            width: 22mm;
            text-align: right;
        }

        .col-total {
            width: 22mm;
            text-align: right;
        }

        .empty-row td {
            height: 8mm;
        }

        /* FOOTER */
        .footer-perhatian {
            border: 1px solid #000;
            padding: 4px 6px;
            font-size: 12px;
            line-height: 1.4;
            margin-bottom: 8px;
        }

        .perhatian-title {
            font-weight: bold;
            font-size: 12px;
            text-decoration: underline;
            margin-bottom: 2px;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }

        .summary-table td {
            padding: 1px 0;
            font-size: 12px;
        }

        .summary-table .val {
            text-align: right;
            border-bottom: 1px solid #000;
            min-width: 30mm;
        }

        .jumlah-row td {
            font-weight: bold;
            font-size: 11px;
            border-bottom: 2px solid #000;
            padding-bottom: 2px;
        }

        .jumlah-box {
            border: 1px solid #000;
            min-width: 30mm;
            height: 7mm;
            padding: 2px 4px;
            text-align: right;
            font-weight: bold;
        }

        .sign-table {
            width: 100%;
        }

        .sign-cell {
            font-size: 12px;
            font-weight: bold;
            text-align: center;
            vertical-align: top;
            width: 50%;
        }

        .sign-line {
            margin: 18mm auto 0 auto;
            border-top: 1px solid #000;
            width: 40mm;
        }
    </style>
</head>

<body>

    @php
        $storeName = $transaction->storeSetting->store_name ?? '-';
        $storeAddress = $transaction->storeSetting->address ?? '-';
        $storePhone = $transaction->storeSetting->phone ?? '';

        $tglFormatted = $transaction->transaction_date
            ? \Carbon\Carbon::parse($transaction->transaction_date)->format('d-m-y')
            : '-';

        $customerName = $transaction->customer->name ?? '-';

        $itemCount = $transaction->transactionItems->count();
        $minRows = 10;
        $emptyRows = max(0, $minRows - $itemCount);
    @endphp

    <div class="paper">

        {{-- HEADER --}}
        <table class="header-table" cellpadding="0" cellspacing="0">
            <tr>
                <td style="vertical-align: top; width: 65%;">
                    <table cellpadding="0" cellspacing="0">
                        <tr>
                            <td style="vertical-align: top; padding-right: 8px;">
                                <img src="{{ public_path('assets/images/logo.png') }}" alt="Logo"
                                    style="width: 55px; height: 55px; object-fit: contain;">
                            </td>
                            <td style="vertical-align: top;">
                                <div class="header-store-name">{{ strtoupper($storeName) }}</div>
                                @if ($storeAddress)
                                    <div class="header-store-address">{{ $storeAddress }}</div>
                                @endif
                                @if ($storePhone)
                                    <div class="header-store-address">Telp. {{ $storePhone }}</div>
                                @endif
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="vertical-align: top; text-align: right;  width: 35%;">
                    <table class="header-right-table" cellpadding="0" cellspacing="0">
                        <tr>
                            <td class="label">Tgl.</td>
                            <td style="text-decoration: underline;">{{ $tglFormatted }}</td>
                        </tr>
                        <tr>
                            <td class="label" style="vertical-align: middle; padding: 2px 3px; ">
                                <span style="border-bottom: 1px solid #000; display: block;">Tuan</span>
                                <span>Toko</span>
                            </td>
                            <td style="min-height: 28px; padding: 2px 3px; text-decoration: underline;">
                                {{ $customerName }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        {{-- NOTA NO --}}
        <div class="nota-no">
            NOTA No. {{ $transaction->transaction_code }}
        </div>

        {{-- ITEMS TABLE --}}
        <table class="items-table">
            <thead>
                <tr>
                    <th class="col-qty" style="font-size:14px;">Banyak-<br>nya</th>
                    <th class="col-name" style="font-size:14px;">NAMA BARANG</th>
                    <th class="col-price" style="font-size:14px;">Harga Sat.</th>
                    <th class="col-total" style="font-size:14px;">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transaction->transactionItems as $item)
                    @php
                        $isBundle = !is_null($item->bundle_id);
                        $name = $item->product->name ?? ($item->bundle->name ?? '-');
                    @endphp

                    <tr>
                        <td class="col-qty" style="font-size:14px;">{{ $item->qty }}</td>
                        <td class="col-name" style="font-weight: bold;font-size:14px;">{{ $name }}</td>
                        <td class="col-price" style="font-size:14px;">
                            {{ number_format($item->selling_price, 0, ',', '.') }}</td>
                        <td class="col-total" style="font-size:14px;">{{ number_format($item->subtotal, 0, ',', '.') }}
                        </td>
                    </tr>

                    @if ($isBundle)
                        @foreach ($item->bundle->bundleItems as $bundleItem)
                            @php
                                $bundleProduct = $bundleItem->product->name ?? '-';
                                $qty = $bundleItem->qty * $item->qty;
                                $price = $bundleItem->price;
                                $subtotal = $price * $qty;
                            @endphp
                            <tr>
                                <td class="col-qty" style="text-align:center;font-size:14px;">{{ $qty }}</td>
                                <td class="col-name" style="font-size:14px;padding-left:8px;"> - {{ $bundleProduct }}
                                </td>
                                <td class="col-price" style="font-size:14px;">{{ number_format($price, 0, ',', '.') }}
                                </td>
                                <td class="col-total" style="font-size:14px;">
                                    {{ number_format($subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    @endif
                @endforeach

                @for ($i = 0; $i < $emptyRows; $i++)
                    <tr class="empty-row">
                        <td class="col-qty"></td>
                        <td class="col-name"></td>
                        <td class="col-price"></td>
                        <td class="col-total"></td>
                    </tr>
                @endfor
            </tbody>
        </table>

        {{-- FOOTER --}}
        <table style="width: 100%; margin-top: 4px;" cellpadding="0" cellspacing="4">
            <tr>
                <td style="vertical-align: top; width: 55%;">
                    <div class="footer-perhatian">
                        <div class="perhatian-title">PERHATIAN</div>
                        Barang-barang yang sudah dibeli<br>
                        tidak dapat dikembalikan / ditukar
                    </div>
                </td>
                <td style="vertical-align: top; width: 45%;">
                    <table class="summary-table" cellpadding="0" cellspacing="0">
                        <tr>
                            <td>Subtotal</td>
                            <td class="val">{{ number_format($transaction->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @if ($transaction->discount_total > 0)
                            <tr>
                                <td>Diskon</td>
                                <td class="val">{{ number_format($transaction->discount_total, 0, ',', '.') }}</td>
                            </tr>
                        @endif
                        @if ($transaction->shiping_cost > 0)
                            <tr>
                                <td>Ongkir</td>
                                <td class="val">{{ number_format($transaction->shiping_cost, 0, ',', '.') }}</td>
                            </tr>
                        @endif
                        <tr class="jumlah-row" style="margin-top: 4px;">
                            <td>Jumlah Rp.</td>
                            <td class="jumlah-box">{{ number_format($transaction->grand_total, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td style="font-size:11px; color:#555;">
                                {{ $transaction->transactionPayment?->method?->getLabel() }}
                            </td>
                            <td style="font-size:11px; color:#555; text-align:right;">
                                {{ $transaction->transactionPayment?->status?->getLabel() }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        {{-- TANDA TANGAN --}}
        <table class="sign-table" style="margin-top: 4px;" cellpadding="0" cellspacing="0">
            <tr>
                <td class="sign-cell">
                    Tanda Terima,
                    <div class="sign-line"></div>
                </td>
                <td class="sign-cell">
                    Hormat kami,
                    <div class="sign-line"></div>
                </td>
            </tr>
        </table>

    </div>

</body>

</html>
