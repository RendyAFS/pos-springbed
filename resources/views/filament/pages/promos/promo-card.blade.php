@php
    $promo = $getRecord();

    $color = match ($promo->type) {
        'flash_sale' => ['bg' => '#FEF0EF', 'border' => '#F7C1C1', 'text' => '#E24B4A'],
        'voucher' => ['bg' => '#EEF2FB', 'border' => '#B5D4F4', 'text' => '#378ADD'],
        default => ['bg' => '#EAF3DE', 'border' => '#C0DD97', 'text' => '#3B6D11'],
    };

    $icon = match ($promo->type) {
        'flash_sale' => '%',
        'voucher' => '🏷',
        'free_shipping' => '🚚',
        default => '🎁',
    };

    $usagePct = $promo->usage_limit > 0 ? ($promo->usage_count / $promo->usage_limit) * 100 : 0;
@endphp

<div class="rounded-xl border p-4 transition hover:shadow-md"
    style="background:{{ $color['bg'] }};border-color:{{ $color['border'] }};">

    {{-- HEADER --}}
    <div class="flex justify-between items-start mb-3">

        <div class="flex gap-2 items-center">

            <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-white" style="color:{{ $color['text'] }};">
                {{ $icon }}
            </div>

            <div>
                <p class="font-semibold text-sm" style="color:{{ $color['text'] }}">
                    {{ $promo->name }}
                </p>

                <p class="text-xs text-gray-500">
                    {{ $promo->type->getLabel() }}
                </p>
            </div>

        </div>
        {{ $this->getTableRecordAction('edit')?->record($promo) }}
    </div>


    {{-- DISCOUNT --}}
    <div class="text-center my-3">

        @if ($promo->discount_type === 'percentage')
            <p class="text-3xl font-bold" style="color:{{ $color['text'] }}">
                {{ $promo->discount_value }}%
            </p>
        @else
            <p class="text-2xl font-bold" style="color:{{ $color['text'] }}">
                Rp {{ number_format($promo->discount_value, 0, ',', '.') }}
            </p>
        @endif

        <p class="text-xs text-gray-500">Discount</p>

    </div>


    {{-- INFO --}}
    <div class="text-sm border-t pt-2 mt-3" style="border-color:{{ $color['border'] }}">

        <div class="flex justify-between py-1">
            <span class="text-gray-500">Min Purchase</span>

            <span style="color:{{ $color['text'] }}">
                Rp {{ number_format($promo->min_purchase, 0, ',', '.') }}
            </span>
        </div>

        <div class="flex justify-between py-1">
            <span class="text-gray-500">Valid</span>

            <span style="color:{{ $color['text'] }}">
                {{ $promo->start_date?->format('Y-m-d') }}
                -
                {{ $promo->end_date?->format('Y-m-d') }}
            </span>
        </div>

        @if ($promo->usage_limit)
            <div class="flex justify-between py-1">
                <span class="text-gray-500">Usage</span>

                <span>
                    {{ $promo->usage_count }} / {{ $promo->usage_limit }}
                </span>
            </div>

            <div class="h-2 bg-gray-200 rounded mt-1">

                <div class="h-2 rounded" style="width:{{ $usagePct }}%;background:{{ $color['text'] }}"></div>

            </div>
        @endif

    </div>


    {{-- FOOTER --}}
    <div class="flex justify-between items-center mt-3">

        <span class="text-xs font-medium px-2 py-1 rounded"
            style="background:{{ $promo->is_active ? '#DCFCE7' : '#FEE2E2' }}">
            {{ $promo->is_active ? 'Active' : 'Inactive' }}
        </span>

    </div>

</div>
