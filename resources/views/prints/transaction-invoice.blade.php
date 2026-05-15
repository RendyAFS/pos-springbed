<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    @php
        $isA4 = ($paperSize ?? 'a5') === 'a4';
        $pageSize = $isA4 ? 'A4' : 'A5';
        $pageHeight = $isA4 ? '297mm' : '210mm';
        $baseFontSz = $isA4 ? '11px' : '9px';

        $bodyPad = $isA4 ? '16px 28px' : '10px 18px';
        $bodyPadBottom = $isA4 ? '48px' : '36px';

        $headerPad = $isA4 ? '18px 28px 14px' : '12px 18px 10px';
        $metaPad = $isA4 ? '8px 28px' : '6px 18px';
        $metaItemMr = $isA4 ? '18px' : '12px';
        $metaLabelSz = $isA4 ? '8px' : '7px';
        $metaValueSz = $isA4 ? '10px' : '8.5px';

        $logoPr = $isA4 ? '12px' : '8px';
        $logoSize = $isA4 ? '52px' : '38px';
        $storeNameSz = $isA4 ? '20px' : '15px';
        $taglineSz = $isA4 ? '9px' : '7.5px';
        $invoiceLabelSz = $isA4 ? '28px' : '20px';

        $infoRowMb = $isA4 ? '14px' : '10px';
        $infoColPr = $isA4 ? '16px' : '10px';
        $infoTitleSz = $isA4 ? '8px' : '7px';
        $infoTitleMb = $isA4 ? '5px' : '3px';
        $infoContentSz = $isA4 ? '10px' : '8.5px';
        $infoStrongSz = $isA4 ? '11px' : '9px';

        $thPad = $isA4 ? '6px 8px' : '4px 6px';
        $thSz = $isA4 ? '9px' : '7.5px';
        $tdPad = $isA4 ? '5px 8px' : '3px 6px';
        $tdSz = $isA4 ? '10px' : '8.5px';
        $colNo = $isA4 ? '20px' : '16px';
        $colQty = $isA4 ? '40px' : '28px';
        $colPrice = $isA4 ? '95px' : '80px';
        $bundleSubPl = $isA4 ? '12px' : '8px';
        $emptyRowH = $isA4 ? '14px' : '11px';
        $itemsMb = $isA4 ? '12px' : '8px';

        $footerWrapMt = $isA4 ? '4px' : '3px';
        $footerLeftPr = $isA4 ? '14px' : '10px';
        $termsPad = $isA4 ? '8px 10px' : '5px 7px';
        $termsMb = $isA4 ? '10px' : '7px';
        $termsTitleSz = $isA4 ? '8px' : '7px';
        $termsListSz = $isA4 ? '9px' : '7.5px';

        $summTdPad = $isA4 ? '3px 6px' : '2px 4px';
        $summTdSz = $isA4 ? '10px' : '8.5px';
        $grandSz = $isA4 ? '12px' : '10px';
        $grandPad = $isA4 ? '6px 8px' : '4px 6px';
        $payRowSz = $isA4 ? '9px' : '7.5px';
        $payRowPt = $isA4 ? '3px' : '2px';

        $signMt = $isA4 ? '16px' : '10px';
        $signCellSz = $isA4 ? '9px' : '7.5px';
        $signLineMt = $isA4 ? '22mm' : '16mm';
        $signLineW = $isA4 ? '45mm' : '32mm';

        $footerBandPad = $isA4 ? '6px 28px' : '4px 18px';
        $footerBandSz = $isA4 ? '8px' : '7px';

        $depositBadgeSz = $isA4 ? '8px' : '7px';
    @endphp
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

        @page {
            size: {{ $pageSize }} portrait;
            margin: 0;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', Arial, sans-serif;
            background: #fff;
            color: #1a1a2e;
            font-size: {{ $baseFontSz }};
            margin: 0;
            padding: 0;
        }

        html,
        body {
            height: 100%;
        }

        .page {
            width: 100%;
            height: {{ $pageHeight }};
            position: relative;
        }

        .body-stretch {
            display: block;
            padding: {{ $bodyPad }};
            padding-bottom: {{ $bodyPadBottom }};
        }

        /* ── HEADER BAND ── */
        .header-band {
            background: #1565c0;
            padding: {{ $headerPad }};
            position: relative;
            overflow: hidden;
        }

        .header-band::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 40%;
            height: 100%;
            background: #1976d2;
            clip-path: polygon(12% 0, 100% 0, 100% 100%, 0% 100%);
        }

        .header-inner {
            position: relative;
            z-index: 1;
            display: table;
            width: 100%;
        }

        .header-left {
            display: table-cell;
            vertical-align: middle;
            width: 50%;
        }

        .header-right {
            display: table-cell;
            vertical-align: middle;
            text-align: right;
            width: 50%;
        }

        .store-logo-wrap {
            display: table;
        }

        .store-logo-img {
            display: table-cell;
            vertical-align: middle;
            padding-right: {{ $logoPr }};
        }

        .store-logo-img img {
            width: {{ $logoSize }};
            height: {{ $logoSize }};
            object-fit: contain;
            background: #fff;
            border-radius: 6px;
            padding: 2px;
        }

        .store-logo-text {
            display: table-cell;
            vertical-align: middle;
        }

        .store-name {
            font-size: {{ $storeNameSz }};
            font-weight: 800;
            color: #fff;
            letter-spacing: -0.5px;
            line-height: 1.1;
        }

        .store-tagline {
            font-size: {{ $taglineSz }};
            color: rgba(255, 255, 255, 0.75);
            margin-top: 2px;
        }

        .invoice-label {
            font-size: {{ $invoiceLabelSz }};
            font-weight: 800;
            color: #fff;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        /* ── META BAND ── */
        .meta-band {
            background: #e3f0fb;
            padding: {{ $metaPad }};
            display: table;
            width: 100%;
            border-bottom: 2px solid #1565c0;
        }

        .meta-left,
        .meta-right {
            display: table-cell;
            vertical-align: middle;
        }

        .meta-right {
            text-align: right;
        }

        .meta-item {
            display: inline-block;
            margin-right: {{ $metaItemMr }};
        }

        .meta-item:last-child {
            margin-right: 0;
        }

        .meta-label {
            font-size: {{ $metaLabelSz }};
            color: #1565c0;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .meta-value {
            font-size: {{ $metaValueSz }};
            font-weight: 700;
            color: #1a1a2e;
        }

        /* ── INFO ROW ── */
        .info-row {
            display: table;
            width: 100%;
            margin-bottom: {{ $infoRowMb }};
        }

        .info-col {
            display: table-cell;
            vertical-align: top;
            width: 50%;
            padding-right: {{ $infoColPr }};
        }

        .info-col:last-child {
            padding-right: 0;
            text-align: right;
        }

        .info-box-title {
            font-size: {{ $infoTitleSz }};
            font-weight: 700;
            color: #1565c0;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            border-bottom: 1px solid #1565c0;
            padding-bottom: 2px;
            margin-bottom: {{ $infoTitleMb }};
        }

        .info-box-content {
            font-size: {{ $infoContentSz }};
            color: #333;
            line-height: 1.5;
        }

        .info-box-content strong {
            color: #1a1a2e;
            font-weight: 700;
            display: block;
            font-size: {{ $infoStrongSz }};
        }

        /* ── ITEMS TABLE ── */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: {{ $itemsMb }};
        }

        .items-table thead tr {
            background: #1565c0;
            color: #fff;
        }

        .items-table th {
            padding: {{ $thPad }};
            font-size: {{ $thSz }};
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .items-table tbody tr {
            border-bottom: 1px solid #e8eef4;
        }

        .items-table tbody tr:nth-child(even) {
            background: #f5f9fd;
        }

        .items-table td {
            padding: {{ $tdPad }};
            font-size: {{ $tdSz }};
            vertical-align: top;
        }

        .col-no {
            width: {{ $colNo }};
            text-align: center;
        }

        .col-qty {
            width: {{ $colQty }};
            text-align: center;
        }

        .col-name {
            width: auto;
        }

        .col-price {
            width: {{ $colPrice }};
            text-align: right;
        }

        .col-total {
            width: {{ $colPrice }};
            text-align: right;
        }

        .product-name {
            font-weight: 600;
            color: #1a1a2e;
        }

        .bundle-sub {
            padding-left: {{ $bundleSubPl }};
            color: #555;
        }

        .empty-row td {
            height: {{ $emptyRowH }};
            border-bottom: 1px solid #e8eef4;
        }

        /* ── FOOTER WRAP ── */
        .footer-wrap {
            display: table;
            width: 100%;
            margin-top: {{ $footerWrapMt }};
        }

        .footer-left {
            display: table-cell;
            vertical-align: top;
            width: 55%;
            padding-right: {{ $footerLeftPr }};
        }

        .footer-right {
            display: table-cell;
            vertical-align: top;
            width: 45%;
        }

        .terms-box {
            border: 1px solid #c8d8e8;
            border-radius: 4px;
            padding: {{ $termsPad }};
            background: #f9fbfd;
            margin-bottom: {{ $termsMb }};
        }

        .terms-title {
            font-size: {{ $termsTitleSz }};
            font-weight: 700;
            color: #1565c0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 3px;
        }

        .terms-list {
            font-size: {{ $termsListSz }};
            color: #555;
            line-height: 1.5;
            list-style: none;
            padding: 0;
        }

        .terms-list li::before {
            content: '• ';
            color: #1565c0;
        }

        /* ── SUMMARY TABLE ── */
        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }

        .summary-table td {
            padding: {{ $summTdPad }};
            font-size: {{ $summTdSz }};
        }

        .summary-table .s-label {
            color: #555;
        }

        .summary-table .s-val {
            text-align: right;
            color: #1a1a2e;
            font-weight: 500;
        }

        .summary-divider td {
            border-top: 1px solid #d0dde8;
        }

        .grand-row {
            background: #1565c0;
            border-radius: 3px;
        }

        .grand-row td {
            color: #fff !important;
            font-weight: 800 !important;
            font-size: {{ $grandSz }} !important;
            padding: {{ $grandPad }} !important;
        }

        .payment-status-row td {
            font-size: {{ $payRowSz }};
            color: #888;
            padding-top: {{ $payRowPt }};
        }

        /* ── SIGNATURE ── */
        .sign-section {
            margin-top: {{ $signMt }};
            display: table;
            width: 100%;
        }

        .sign-cell {
            display: table-cell;
            width: 50%;
            text-align: center;
            font-size: {{ $signCellSz }};
            color: #555;
            vertical-align: top;
        }

        .sign-line {
            margin: {{ $signLineMt }} auto 0;
            border-top: 1px solid #888;
            width: {{ $signLineW }};
        }

        /* ── FOOTER BAND ── */
        .footer-band {
            background: #1565c0;
            padding: {{ $footerBandPad }};
            text-align: center;
            color: rgba(255, 255, 255, 0.7);
            font-size: {{ $footerBandSz }};
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
        }

        /* ── DEPOSIT BADGES ── */
        .badge-deposit {
            display: inline-block;
            font-size: {{ $depositBadgeSz }};
            font-weight: 700;
            padding: 2px 7px;
            border: 1px solid #e67e22;
            color: #e67e22;
            border-radius: 3px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .badge-lunas {
            display: inline-block;
            font-size: {{ $depositBadgeSz }};
            font-weight: 700;
            padding: 2px 7px;
            border: 1px solid #27ae60;
            color: #27ae60;
            border-radius: 3px;
            margin-left: 3px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .badge-belum-lunas {
            display: inline-block;
            font-size: {{ $depositBadgeSz }};
            font-weight: 700;
            padding: 2px 7px;
            border: 1px solid #c0392b;
            color: #c0392b;
            border-radius: 3px;
            margin-left: 3px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .sisa-text {
            font-size: {{ $depositBadgeSz }};
            color: #c0392b;
            margin-top: 3px;
            font-weight: 600;
        }
    </style>
</head>

<body>

    @php
        $storeName = $transaction->storeSetting->store_name ?? '-';
        $storeAddress = $transaction->storeSetting->address ?? '-';
        $storePhone = $transaction->storeSetting->phone ?? '';
        $storeEmail = $transaction->storeSetting->email ?? '';

        $tglFormatted = $transaction->transaction_date
            ? \Carbon\Carbon::parse($transaction->transaction_date)->format('d/m/Y')
            : '-';

        $customerName = $transaction->customer->name ?? '-';
        $customerPhone = $transaction->customer->phone ?? '';
        $customerAddress = $transaction->customer->address ?? '';

        $courierName = $transaction->transactionShipment?->courier?->name ?? '';
        $trackingNo = $transaction->transactionShipment?->tracking_number ?? '';

        $itemCount = $transaction->transactionItems->count();
        $minRows = $isA4 ? 18 : 12;
        $emptyRows = max(0, $minRows - $itemCount);
        $rowNum = 0;

        $totalDeposit =
            $transaction->transactionDeposits->sum('amount') + (float) ($transaction->transactionPayment?->amount ?? 0);
        $grandTotal = (float) $transaction->grand_total;
        $isLunas = $totalDeposit >= $grandTotal;
        $sisa = $grandTotal - $totalDeposit;
    @endphp

    <div class="page">

        {{-- ── HEADER ── --}}
        <div class="header-band">
            <div class="header-inner">
                <div class="header-left">
                    <table class="store-logo-wrap" cellpadding="0" cellspacing="0">
                        <tr>
                            <td class="store-logo-img">
                                <img src="{{ public_path('assets/images/logo.png') }}" alt="Logo">
                            </td>
                            <td class="store-logo-text">
                                <div class="store-name">{{ strtoupper($storeName) }}</div>
                                @if ($storeAddress)
                                    <div class="store-tagline">{{ $storeAddress }}</div>
                                @endif
                                @if ($storePhone)
                                    <div class="store-tagline">Telp. {{ $storePhone }}</div>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="header-right">
                    <div class="invoice-label">INVOICE</div>
                </div>
            </div>
        </div>

        {{-- ── META BAND ── --}}
        <div class="meta-band">
            <table cellpadding="0" cellspacing="0" style="width:100%;">
                <tr>
                    <td style="vertical-align:middle;">
                        @if ($courierName)
                            <div class="meta-item">
                                <div class="meta-label">Metode Pengiriman</div>
                                <div class="meta-value">{{ $courierName }}</div>
                            </div>
                        @endif
                        @if ($trackingNo)
                            <div class="meta-item">
                                <div class="meta-label">No. Resi</div>
                                <div class="meta-value">{{ $trackingNo }}</div>
                            </div>
                        @endif
                    </td>
                    <td style="text-align:right; vertical-align:middle;">
                        <div class="meta-item">
                            <div class="meta-label">Order ID</div>
                            <div class="meta-value">{{ $transaction->transaction_code }}</div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-label">Tanggal</div>
                            <div class="meta-value">{{ $tglFormatted }}</div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        {{-- ── BODY ── --}}
        <div class="body-stretch">

            {{-- Customer & Order Info --}}
            <div class="info-row">
                <div class="info-col">
                    <div class="info-box-title">Shipping Address</div>
                    <div class="info-box-content">
                        <strong>{{ $customerName }}</strong>
                        @if ($customerPhone)
                            {{ $customerPhone }}<br>
                        @endif
                        @if ($customerAddress)
                            {{ $customerAddress }}
                        @endif
                    </div>
                </div>
                <div class="info-col" style="text-align:right; padding-right:0; padding-left:16px;">
                    <div class="info-box-title" style="text-align:right;">Order Details</div>
                    <div class="info-box-content" style="text-align:right;">
                        <strong>{{ $storeName }}</strong>
                        @if ($storePhone)
                            Telp. {{ $storePhone }}<br>
                        @endif
                        @if ($storeEmail)
                            {{ $storeEmail }}
                        @endif
                    </div>
                </div>
            </div>

            {{-- Items Table --}}
            <table class="items-table" cellpadding="0" cellspacing="0">
                <thead>
                    <tr>
                        <th class="col-no" style="text-align:center;">No</th>
                        <th class="col-name" style="text-align:left;">Nama Produk</th>
                        <th class="col-qty">Qty</th>
                        <th class="col-price">Harga Satuan</th>
                        <th class="col-total">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transaction->transactionItems as $item)
                        @php
                            $rowNum++;
                            $isBundle = !is_null($item->bundle_id);
                            $name = $item->product->name ?? ($item->bundle->name ?? '-');
                        @endphp
                        <tr>
                            <td class="col-no">{{ $rowNum }}</td>
                            <td class="col-name product-name">{{ $name }}</td>
                            <td class="col-qty">{{ $item->qty }}</td>
                            <td class="col-price">Rp. {{ number_format($item->selling_price, 0, ',', '.') }}</td>
                            <td class="col-total">Rp. {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @if ($isBundle)
                            @foreach ($item->bundle->bundleItems as $bundleItem)
                                @php
                                    $bundleProduct = $bundleItem->product->name ?? '-';
                                    $qty = $bundleItem->qty * $item->qty;
                                    $price = $bundleItem->price;
                                    $subtotal = $price * $qty;
                                @endphp
                                <tr class="bundle-sub">
                                    <td class="col-no"></td>
                                    <td class="col-name" style="color:#555; padding-left:14px;">{{ $bundleProduct }}
                                    </td>
                                    <td class="col-qty" style="color:#555;">{{ $qty }}</td>
                                    <td class="col-price" style="color:#555;">Rp.
                                        {{ number_format($price, 0, ',', '.') }}</td>
                                    <td class="col-total" style="color:#555;">Rp.
                                        {{ number_format($subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        @endif
                    @endforeach

                    @for ($i = 0; $i < $emptyRows; $i++)
                        <tr class="empty-row">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    @endfor
                </tbody>
            </table>

            {{-- Terms + Signature + Summary --}}
            <div class="footer-wrap">

                <div class="footer-left">
                    <div class="terms-box">
                        <div class="terms-title">Syarat dan Ketentuan Berlaku</div>
                        <ul class="terms-list">
                            <li>Barang yang sudah dibeli tidak bisa dikembalikan / ditukar</li>
                            <li>Print head barang teknik dan tidak ada garansi dalam bentuk apapun</li>
                        </ul>
                    </div>

                    <div class="sign-section" style="margin-top:0;">
                        <div class="sign-cell" style="text-align:left; display:inline-block; width:48%;">
                            Tanda Terima,
                            <div class="sign-line" style="margin-left:0;"></div>
                        </div>
                        <div class="sign-cell" style="text-align:right; display:inline-block; width:48%;">
                            Hormat kami,
                            <div class="sign-line" style="margin-right:0;"></div>
                        </div>
                    </div>
                </div>

                <div class="footer-right">
                    <table class="summary-table" cellpadding="0" cellspacing="0">
                        <tr>
                            <td class="s-label">Sub Total</td>
                            <td class="s-val">Rp. {{ number_format($transaction->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @if ($transaction->discount_total > 0)
                            <tr>
                                <td class="s-label">Diskon</td>
                                <td class="s-val">- Rp. {{ number_format($transaction->discount_total, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endif
                        @if ($transaction->shiping_cost > 0)
                            <tr>
                                <td class="s-label">Ongkir</td>
                                <td class="s-val">Rp. {{ number_format($transaction->shiping_cost, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endif
                        <tr class="summary-divider">
                            <td colspan="2" style="padding:0; height:2px;"></td>
                        </tr>
                        <tr class="grand-row">
                            <td class="s-label">Grand Total</td>
                            <td class="s-val">Rp. {{ number_format($transaction->grand_total, 0, ',', '.') }}</td>
                        </tr>
                        <tr class="payment-status-row">
                            <td class="s-label">{{ $transaction->transactionPayment?->method?->getLabel() }}</td>
                            <td class="s-val" style="text-align:right; color:#888;">
                                {{ $transaction->transactionPayment?->status?->getLabel() }}
                            </td>
                        </tr>
                        @if ($transaction->is_deposit)
                            <tr>
                                <td colspan="2" style="padding-top:5px;">
                                    <span class="badge-deposit">Deposit</span>
                                    @if ($isLunas)
                                        <span class="badge-lunas">Lunas</span>
                                    @else
                                        <span class="badge-belum-lunas">Belum Lunas</span>
                                        <div class="sisa-text">Sisa: Rp {{ number_format($sisa, 0, ',', '.') }}</div>
                                    @endif
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>

            </div>
            {{-- ── END footer-wrap ── --}}

        </div>
        {{-- ── END body-stretch ── --}}

        {{-- ── FOOTER BAND ── --}}
        <div class="footer-band">
            {{ $storeName }} &bull; {{ $storeAddress }}
            @if ($storePhone)
                &bull; Telp. {{ $storePhone }}
            @endif
        </div>

    </div>
    {{-- ── END .page ── --}}

</body>

</html>
