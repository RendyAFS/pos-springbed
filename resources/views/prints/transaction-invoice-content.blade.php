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

    // Menambahkan variabel untuk Tanggal Kirim
    $shippingDate = $transaction->transactionShipment?->created_at
        ? \Carbon\Carbon::parse($transaction->transactionShipment->created_at)->format('d/m/Y')
        : '';

    $itemCount = $transaction->transactionItems->count();
    $minRows = $isA4 ? 18 : 12;
    $emptyRows = max(0, $minRows - $itemCount);
    $rowNum = 0;

    $totalDownPayment =
        $transaction->transactionDownPayments->sum('amount') + (float) ($transaction->transactionPayment?->amount ?? 0);
    $grandTotal = (float) $transaction->grand_total;
    $isLunas = $totalDownPayment >= $grandTotal;
    $sisa = $grandTotal - $totalDownPayment;
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
                    @if ($shippingDate)
                        <div class="meta-item">
                            <div class="meta-label">Tanggal Kirim</div>
                            <div class="meta-value">{{ $shippingDate }}</div>
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
                <div class="info-box-title">Alamat Tujuan</div>
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
                    @if ($transaction->is_down_payment)
                        <tr>
                            <td colspan="2" style="padding-top:5px;">
                                <span class="badge-down-payment">Down Payment</span>
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
