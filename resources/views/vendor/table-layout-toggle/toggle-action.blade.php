@php
$icon = $this->isGridLayout() ? $listIcon : $gridIcon;
$color = 'gray';
@endphp

<div>
  <x-filament::icon-bphp artisan vendor:publish --tag="table-layout-toggle-config"utton
    :icon="$icon"
    :color="$color"
    size="md"
    class="h-9 w-9"
    wire:click="changeLayoutView"
  />
</div>
