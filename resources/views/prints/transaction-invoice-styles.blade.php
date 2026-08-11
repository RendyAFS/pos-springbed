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

    /* ── DOWN PAYMENT BADGES ── */
    .badge-down-payment {
        display: inline-block;
        font-size: {{ $downPaymentBadgeSz }};
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
        font-size: {{ $downPaymentBadgeSz }};
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
        font-size: {{ $downPaymentBadgeSz }};
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
        font-size: {{ $downPaymentBadgeSz }};
        color: #c0392b;
        margin-top: 3px;
        font-weight: 600;
    }
</style>
