<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <style>
        @page {
            size: A5 portrait;
            margin: 10mm 10mm 10mm 10mm;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            width: 148mm;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #000;
        }

        .paper {
            width: 148mm;
        }

        /* ===== HEADER ===== */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 6px;
            border-bottom: 2px solid #000;
            padding-bottom: 6px;
        }

        .header-left {
            display: flex;
            align-items: flex-start;
            gap: 8px;
        }

        .header-logo {
            width: 55px;
            height: 55px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
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

        .header-right {
            text-align: left;
            font-size: 11px;
            min-width: 90px;
        }

        .header-right table {
            border-collapse: collapse;
        }

        .header-right td {
            padding: 1px 3px;
            vertical-align: top;
        }

        .header-right .label {
            font-weight: bold;
            white-space: nowrap;
        }

        .header-right .value {
            border-bottom: 1px solid #000;
            min-width: 70px;
        }

        /* ===== NOTA NO ===== */
        .nota-no {
            font-size: 11px;
            font-weight: bold;
            margin-bottom: 4px;
            border-bottom: 1px solid #000;
            padding-bottom: 2px;
        }

        /* ===== TABLE ITEMS ===== */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 4px;
        }

        .items-table th {
            border: 1px solid #000;
            padding: 3px 4px;
            text-align: center;
            font-size: 10px;
            background: #fff;
        }

        .items-table td {
            border: 1px solid #000;
            padding: 3px 4px;
            font-size: 10px;
            vertical-align: top;
        }

        .items-table .col-qty {
            width: 14mm;
            text-align: center;
        }

        .items-table .col-name {
            width: auto;
        }

        .items-table .col-price {
            width: 22mm;
            text-align: right;
        }

        .items-table .col-total {
            width: 22mm;
            text-align: right;
        }

        /* Empty rows to fill space */
        .items-table tr.empty-row td {
            height: 8mm;
        }

        /* ===== FOOTER ===== */
        .footer {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-top: 4px;
            gap: 8px;
        }

        .footer-left {
            flex: 1;
        }

        .footer-perhatian {
            border: 1px solid #000;
            padding: 4px 6px;
            font-size: 9px;
            line-height: 1.4;
            margin-bottom: 8px;
        }

        .footer-perhatian .perhatian-title {
            font-weight: bold;
            font-size: 9px;
            text-decoration: underline;
            margin-bottom: 2px;
        }

        .footer-tanda-terima {
            font-size: 10px;
            font-weight: bold;
            margin-top: 4px;
        }

        .footer-tanda-terima .sign-line {
            margin-top: 18mm;
            border-top: 1px solid #000;
            width: 40mm;
        }

        .footer-right {
            min-width: 55mm;
            text-align: right;
        }

        .footer-right .jumlah-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: bold;
            font-size: 11px;
            border-bottom: 2px solid #000;
            padding-bottom: 2px;
            margin-bottom: 4px;
        }

        .footer-right .jumlah-box {
            border: 1px solid #000;
            min-width: 30mm;
            height: 7mm;
            padding: 2px 4px;
            text-align: right;
            font-weight: bold;
        }

        .footer-right .summary-row {
            display: flex;
            justify-content: space-between;
            font-size: 10px;
            padding: 1px 0;
        }

        .footer-right .summary-row .val {
            min-width: 30mm;
            text-align: right;
            border-bottom: 1px solid #000;
        }

        .footer-right .hormat-kami {
            font-size: 10px;
            font-weight: bold;
            margin-top: 4px;
            text-align: center;
        }

        .footer-right .hormat-sign {
            margin-top: 14mm;
            border-top: 1px solid #000;
            text-align: center;
            font-size: 9px;
        }

        @media print {

            html,
            body {
                width: 148mm;
            }
        }
    </style>
</head>

<body onload="window.print()">

    @php
        $storeName = $transaction->storeSetting->store_name ?? '-';
        $storeAddress = $transaction->storeSetting->address ?? '-';
        $storePhone = $transaction->storeSetting->phone ?? '';

        $tglFormatted = $transaction->transaction_date
            ? \Carbon\Carbon::parse($transaction->transaction_date)->format('d-m-y')
            : '-';

        $customerName = $transaction->customer->name ?? '-';

        // Count items for empty row padding
        $itemCount = $transaction->transactionItems->count();
        $minRows = 10; // minimum visible rows in table
        $emptyRows = max(0, $minRows - $itemCount);
    @endphp

    <div class="paper">

        {{-- HEADER --}}
        <div class="header">
            <div class="header-left">
                <div class="header-logo">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="Logo"
                        style="width: 80px; height: 80px; object-fit: contain;">
                </div>
                <div>
                    <div class="header-store-name">{{ strtoupper($storeName) }}</div>
                    @if ($storeAddress)
                        <div class="header-store-address">{{ $storeAddress }}</div>
                    @endif
                    @if ($storePhone)
                        <div class="header-store-address">Telp. {{ $storePhone }}</div>
                    @endif
                </div>
            </div>
            <div class="header-right">
                <table>
                    <tr>
                        <td class="label">Tgl.</td>
                        <td class="value">{{ $tglFormatted }}</td>
                    </tr>
                    <tr>
                        <td class="label" style="vertical-align: middle; padding: 2px 3px;">
                            <div
                                style="display: flex; flex-direction: column; align-items: flex-start; line-height: 1.5;">
                                <span style="border-bottom: 1px solid #000; min-width: 10x;">Tuan</span>
                                <span>Toko</span>
                            </div>
                        </td>
                        <td class="value" style="vertical-align: middle;">
                            <div
                                style="
                                min-width: 70px;
                                height: 100%;
                                display: flex;
                                align-items: center;
                                padding: 2px 3px;
                                min-height: 28px;
                            ">
                                {{ $customerName }}
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- NOTA NO --}}
        <div class="nota-no">
            NOTA No. {{ $transaction->transaction_code }}
        </div>

        {{-- ITEMS TABLE --}}
        <table class="items-table">
            <thead>
                <tr>
                    <th class="col-qty">Banyak-<br>nya</th>
                    <th class="col-name">NAMA BARANG</th>
                    <th class="col-price">Harga Sat.</th>
                    <th class="col-total">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transaction->transactionItems as $item)
                    @php
                        $isBundle = !is_null($item->bundle_id);
                        $name = $item->product->name ?? ($item->bundle->name ?? '-');
                    @endphp

                    {{-- Main item row --}}
                    <tr>
                        <td class="col-qty">{{ $item->qty }}</td>
                        <td class="col-name">{{ $name }}</td>
                        <td class="col-price">{{ number_format($item->selling_price, 0, ',', '.') }}</td>
                        <td class="col-total">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>

                    {{-- Bundle children --}}
                    @if ($isBundle)
                        @foreach ($item->bundle->bundleItems as $bundleItem)
                            @php
                                $bundleProduct = $bundleItem->product->name ?? '-';
                                $qty = $bundleItem->qty * $item->qty;
                                $price = $bundleItem->price;
                                $subtotal = $price * $qty;
                            @endphp
                            <tr>
                                <td class="col-qty" style="text-align:center;font-size:9px;">{{ $qty }}</td>
                                <td class="col-name" style="font-size:9px;padding-left:8px;">↳ {{ $bundleProduct }}
                                </td>
                                <td class="col-price" style="font-size:9px;">{{ number_format($price, 0, ',', '.') }}
                                </td>
                                <td class="col-total" style="font-size:9px;">
                                    {{ number_format($subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    @endif
                @endforeach

                {{-- Empty padding rows --}}
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
        <div style="margin-top: 4px;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 8px;">
                <div style="flex: 1;">
                    <div class="footer-perhatian">
                        <div class="perhatian-title">PERHATIAN</div>
                        Barang-barang yang sudah dibeli<br>
                        tidak dapat dikembalikan / ditukar
                    </div>
                </div>
                <div class="footer-right">
                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span class="val">{{ number_format($transaction->subtotal, 0, ',', '.') }}</span>
                    </div>
                    @if ($transaction->discount_total > 0)
                        <div class="summary-row">
                            <span>Diskon</span>
                            <span class="val">{{ number_format($transaction->discount_total, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    @if ($transaction->shiping_cost > 0)
                        <div class="summary-row">
                            <span>Ongkir</span>
                            <span class="val">{{ number_format($transaction->shiping_cost, 0, ',', '.') }}</span>
                        </div>
                    @endif

                    <div class="jumlah-row" style="margin-top:4px;">
                        <span>Jumlah Rp.</span>
                        <div class="jumlah-box">
                            {{ number_format($transaction->grand_total, 0, ',', '.') }}
                        </div>
                    </div>
                    <div class="summary-row" style="font-size:9px; color:#555;">
                        <span>{{ $transaction->transactionPayment?->method?->getLabel() }}</span>
                        <span>{{ $transaction->transactionPayment?->status?->getLabel() }}</span>
                    </div>
                </div>

            </div>
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-top: 4px;">

                <div style="font-size: 10px; font-weight: bold; text-align: center;">
                    Tanda Terima,
                    <div style="margin-top: 18mm; border-top: 1px solid #000; width: 40mm;"></div>
                </div>

                <div style="font-size: 10px; font-weight: bold; text-align: center;">
                    Hormat kami,
                    <div style="margin-top: 18mm; border-top: 1px solid #000; width: 40mm;"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.onafterprint = function() {
            window.close();
        };
    </script>

</body>

</html>
