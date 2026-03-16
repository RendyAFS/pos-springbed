<div class="rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="bg-gray-50 dark:bg-gray-800 px-4 py-3 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">
            Stock Overview per Store
        </h3>
        @if(collect($pending)->isNotEmpty())
            <span class="inline-flex items-center gap-1 text-xs font-medium text-amber-700 bg-amber-100 px-2 py-1 rounded-full">
                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                </svg>
                Unsaved changes
            </span>
        @endif
    </div>

    @if($stocks->isEmpty())
        <div class="px-4 py-8 text-center">
            <svg class="w-8 h-8 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
            </svg>
            <p class="text-sm text-gray-400">No stock data available yet.</p>
        </div>
    @else
        <div class="divide-y divide-gray-100 dark:divide-gray-700">
            @foreach($stocks as $stock)
                @php
                    $qty = $stock->quantity;
                    $color = match(true) {
                        $qty <= 5  => ['text' => 'text-red-800',    'badge' => 'bg-red-700',    'pill' => 'bg-red-100 text-red-600'],
                        $qty <= 10 => ['text' => 'text-yellow-800', 'badge' => 'bg-yellow-700', 'pill' => 'bg-yellow-100 text-yellow-600'],
                        default    => ['text' => 'text-green-800',  'badge' => 'bg-green-700',  'pill' => 'bg-green-100 text-green-600'],
                    };
                @endphp
                <div class="flex items-center justify-between px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                    <div class="flex items-center gap-3">
                        <span class="inline-block w-2 h-2 rounded-full {{ $color['badge'] }}"></span>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-200">
                            {{ $stock->storeSetting?->store_name ?? 'Unknown Store' }}
                        </span>
                        @if($stock->has_pending)
                            <span class="text-xs text-gray-400 line-through">
                                {{ $stock->qty_before }} pcs
                            </span>
                            <svg class="w-3 h-3 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                        @endif
                    </div>
                    <div class="flex items-center gap-2">
                        @if($stock->has_pending)
                            <span class="text-xs text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full font-medium border border-amber-200">
                                pending
                            </span>
                        @endif
                        <span class="text-sm font-bold px-3 py-1 rounded-full {{ $color['pill'] }}">
                            {{ $qty }} pcs
        	            </span>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
