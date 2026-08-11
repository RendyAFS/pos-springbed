<div class="flex flex-col items-center gap-6 p-2" x-data>

    <div id="barcode-card" class="w-full max-w-sm rounded-xl border border-gray-200 bg-white shadow-sm">

        <div class="border-b px-5 py-3 text-center">
            <h3 class="text-base font-bold tracking-wide">
                {{ config('app.name') }}
            </h3>

            <p class="text-xs text-gray-500">
                Product Barcode
            </p>
        </div>

        <div class="flex justify-center p-5">
            <img src="{{ $dataUri }}" alt="Barcode {{ $record->sku }}" class="h-64 w-64">
        </div>

        <div class="space-y-2 border-t px-5 py-4 text-center">

            @if ($record->sku)
                <div>
                    <div class="font-mono text-lg font-bold tracking-widest">
                        {{ $record->sku }}
                    </div>

                    <div class="text-xs text-gray-500">
                        SKU
                    </div>
                </div>
            @endif

            <div>
                <div class="text-base font-semibold">
                    {{ $record->name }}
                </div>
            </div>

            <div class="flex justify-center gap-2 flex-wrap text-xs">

                @if ($record->type)
                    <span class="rounded-full bg-gray-100 px-3 py-1">
                        {{ $record->type->name }}
                    </span>
                @endif

                @if ($record->size)
                    <span class="rounded-full bg-gray-100 px-3 py-1">
                        {{ $record->size->name }}
                    </span>
                @endif

                @if ($record->brand)
                    <span class="rounded-full bg-gray-100 px-3 py-1">
                        {{ $record->brand->name }}
                    </span>
                @endif

            </div>

        </div>

    </div>

    <div class="flex gap-2">

        <a href="{{ $dataUri }}" download="barcode-{{ $record->sku ?? $record->id }}.png"
            class="fi-color fi-color-primary fi-bg-color-400 hover:fi-bg-color-300 dark:fi-bg-color-600 dark:hover:fi-bg-color-700 fi-text-color-950 hover:fi-text-color-800 dark:fi-text-color-0 dark:hover:fi-text-color-0 fi-btn fi-size-md">
            Download PNG
        </a>

        <a href="{{ route('products.barcode.print', $record) }}" target="_blank" class="fi-btn fi-btn-size-md border">
            Print PDF
        </a>

    </div>

</div>
