<?php
use function Livewire\Volt\{state, mount};

state([
    'minPrice' => 0,
    'maxPrice' => 1000,
    'selectedMin' => null,
    'selectedMax' => null,
]);

mount(function () {
    $this->selectedMin = 0;
    $this->selectedMax = 1000;
});

$updateValues = function ($min, $max) {
    $this->selectedMin = $min;
    $this->selectedMax = $max;
    $this->dispatch('price-filter-updated', [
        'min' => $min,
        'max' => $max,
    ]);
};
?>

<div>
    <h3 wire:ignore>Price Range: $<span x-text="{{ $selectedMin }}"></span> : $<span x-text="{{ $selectedMax }}"></span>
    </h3>

    <x-dual-range-slider :min="$minPrice" :max="$maxPrice" :min-value="$selectedMin" :max-value="$selectedMax" />

    <script>
        document.addEventListener('alpine:init', () => {
            let updateDebounceTimer;

            // Process min change events with debouncing
            window.addEventListener('min-changed', (event) => {
                clearTimeout(updateDebounceTimer);
                updateDebounceTimer = setTimeout(() => {
                    @this.updateValues(event.detail, @this.selectedMax);
                }, 100);
            });

            // Process max change events with debouncing
            window.addEventListener('max-changed', (event) => {
                clearTimeout(updateDebounceTimer);
                updateDebounceTimer = setTimeout(() => {
                    @this.updateValues(@this.selectedMin, event.detail);
                }, 100);
            });
        });
    </script>
</div>
