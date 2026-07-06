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

        $downPaymentBadgeSz = $isA4 ? '8px' : '7px';
    @endphp

    @include('prints.transaction-invoice-styles')
</head>

<body>
    @include('prints.transaction-invoice-content')
</body>

</html>
