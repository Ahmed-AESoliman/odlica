<div class="flex gap-2">
    <flux:sidebar.toggle
        class="text-white! dark:text-gray-300! lg:hidden cursor-pointer rounded-full! hover:bg-zinc-500 dark:hover:bg-zinc-900 duration-150
             bg-zinc-400 dark:bg-zinc-900 fixed! left-0 top-1/3"
        icon="chevron-right" />

    <flux:sidebar sticky stashable class="pt-0 max-lg:bg-zinc-300 dark:max-lg:bg-zinc-950">
        <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />
        <flux:heading size="xl">Filters Selection</flux:heading>
        <div>
            <flux:field class="mb-4">
                <flux:label size="sm">Sort by</flux:label>
                <flux:select size="sm" placeholder="Sort by..." wire:model.live="sortField">
                    <flux:select.option value="name">Name</flux:select.option>
                    <flux:select.option value="price">Price</flux:select.option>
                    <flux:select.option value="created_at">Newest</flux:select.option>
                </flux:select>
            </flux:field>
            <flux:separator text="Categories" />
            <flux:field class="my-4">
                <flux:radio.group class="flex-col card">
                    <flux:radio value="0" label="all" wire:click="setCategory(0)" />
                    @foreach ($categories as $category)
                        <flux:radio value="{{ $category->id }}" label="{{ $category->name }}"
                            wire:click="setCategory({{ $category->id }})" />
                    @endforeach
                </flux:radio.group>
            </flux:field>
            <flux:separator text="Brands" />
            <flux:field class="my-4">
                <ul class="grid w-full gap-1">
                    @foreach ($brands as $brand)
                        <li>
                            <input type="checkbox" id="brand-{{ $brand->id }}" wire:model.live="filters.brands"
                                value="{{ $brand->id }}" class="hidden peer">
                            <label for="brand-{{ $brand->id }}"
                                class="inline-flex items-center justify-between w-full p-2 text-gray-500 bg-white border-1 border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 peer-checked:border-blue-600 dark:peer-checked:border-blue-600 hover:text-gray-600 dark:peer-checked:text-gray-300 peer-checked:text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700">
                                <div class="flex gap-4 items-center">
                                    <img src="{{ asset('storage/' . $brand->logo) }}" class="w-10 h-10" />
                                    <div class="w-full text-lg font-semibold">{{ $brand->name }}</div>
                                </div>
                            </label>
                        </li>
                    @endforeach
                </ul>
            </flux:field>
            <flux:separator text="Variant Selection" />
            <div class="my-3">
                <flux:legend class="mb-2">Colors</flux:legend>
                <div class="grid grid-cols-11 gap-2 ">
                    @foreach ($colors as $color)
                        <div>
                            <input type="checkbox" id="color-{{ $color->id }}" value="{{ $color->id }}"
                                wire:model.live="filters.colors" class="hidden peer">
                            <label for="color-{{ $color->id }}"
                                class="inline-flex items-center justify-between w-full p-2 text-gray-500 border-1 hover:opacity-100 opacity-25 border-gray-200 rounded-full cursor-pointer peer-checked:border-blue-600 dark:peer-checked:border-blue-600 peer-checked:opacity-100 duration-200"
                                style="background-color: {{ $color->value }};">
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="my-3">
                <flux:legend class="mb-2">Size</flux:legend>
                <div class="grid grid-cols-5 gap-2 ">
                    @foreach ($sizes as $size)
                        <div>
                            <input type="checkbox" id="size-{{ $size->id }}" value="{{ $size->id }}"
                                wire:model.live="filters.sizes" class="hidden peer">
                            <label for="size-{{ $size->id }}"
                                class="inline-flex items-center justify-center h-10 w-10 p-2 text-sm font-semibold bg-zinc-900  text-zinc-50 border-1 hover:opacity-100 opacity-25 border-gray-200 rounded-full cursor-pointer peer-checked:border-blue-600 dark:peer-checked:border-blue-600 peer-checked:opacity-100 duration-200">
                                {{ $size->value }}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Price Range -->
            <div class="my-3">
                <flux:legend class="mb-2">Price Range</flux:legend>
                <div class="flex items-center gap-2">
                    <flux:input type="number" wire:model.live.debounce.500ms="filters.price_min" min="0"
                        placeholder="Min" />
                    <span>to</span>
                    <flux:input type="number" wire:model.live.debounce.500ms="filters.price_max" min="0"
                        placeholder="Max" />
                </div>
            </div>

            <div class="my-3">
                <flux:checkbox wire:model.live="filters.on_sale" label="On Sale" />
                <flux:checkbox wire:model.live="filters.in_stock" label="In Stock" />
            </div>
        </div>
    </flux:sidebar>


    <div class="grid md:grid-cols-3  xl:grid-cols-5 gap-2 flex-1 overflow-y-auto">
        @foreach ($products as $product)
            <x-product-card :product="$product" />
        @endforeach

        <div class="col-span-full mt-4">
            {{ $products->links() }}
        </div>
    </div>
    <livewire:cart.cart-overview />
</div>
