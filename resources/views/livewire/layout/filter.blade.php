<div class="">

    <flux:sidebar.toggle
        class="text-white! dark:text-gray-300! lg:hidden cursor-pointer rounded-full! hover:bg-zinc-500  dark:hover:bg-zinc-900 duration-150
         bg-zinc-400 dark:bg-zinc-900  fixed! left-0  top-1/3 "
        icon="chevron-right" />

    <flux:sidebar sticky stashable class="pt-0 max-lg:bg-zinc-300 dark:max-lg:bg-zinc-950">
        <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />
        <flux:heading size="xl">Filters Selection</flux:heading>
        <div>

            <flux:field class="mb-4">
                <flux:label size="sm">Sort by</flux:label>
                <flux:select size="sm" placeholder="Sort by...">
                    <flux:select.option>price</flux:select.option>
                </flux:select>
            </flux:field>
            <flux:separator text="Categories" />
            <flux:field class="my-4">
                <flux:radio.group class="flex-col card">
                    <flux:radio value="" label="all (100)" checked />
                    <flux:radio value="" label="test (20)" />

                </flux:radio.group>
            </flux:field>
            <flux:separator text="Brands" />
            <flux:field class="my-4">
                <ul class="grid w-full gap-1">
                    <li>
                        <input type="checkbox" id="react-option" value="" class="hidden peer">
                        <label for="react-option"
                            class="inline-flex items-center justify-between w-full p-2 text-gray-500 bg-white border-1 border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 peer-checked:border-blue-600 dark:peer-checked:border-blue-600 hover:text-gray-600 dark:peer-checked:text-gray-300 peer-checked:text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700">
                            <div class="flex gap-4 items-center">

                                <img src="https://placehold.co/600x400@2x.png" class="w-10 h-10" />
                                <div class="w-full text-lg font-semibold">Nike</div>
                            </div>
                        </label>
                    </li>

                </ul>
            </flux:field>
            <flux:separator text="Variant Selection" />
            <div class="my-3">
                <flux:legend class="mb-2">Colors</flux:legend>
                <div class="grid grid-cols-11 gap-2 ">
                    <div>
                        <input type="checkbox" id="color-1" value="" class="hidden peer">
                        <label for="color-1"
                            class="inline-flex items-center justify-between w-full p-2 text-gray-500 border-1 hover:opacity-100 opacity-25 border-gray-200 rounded-full cursor-pointer peer-checked:border-blue-600 dark:peer-checked:border-blue-600 peer-checked:opacity-100 duration-200"
                            style="background-color: rgb(77, 51, 230)">
                        </label>
                    </div>
                    <div>
                        <input type="checkbox" id="color-2" value="" class="hidden peer">
                        <label for="color-2"
                            class="inline-flex items-center justify-between w-full p-2 text-gray-500 border-1 hover:opacity-100 opacity-25 border-gray-200 rounded-full cursor-pointer peer-checked:border-blue-600 dark:peer-checked:border-blue-600 peer-checked:opacity-100 duration-200"
                            style="background-color: rgb(15, 136, 43)">
                        </label>
                    </div>
                    <div>
                        <input type="checkbox" id="color-3" value="" class="hidden peer">
                        <label for="color-3"
                            class="inline-flex items-center justify-between w-full p-2 text-gray-500 border-1 hover:opacity-100 opacity-25 border-gray-200 rounded-full cursor-pointer peer-checked:border-blue-600 dark:peer-checked:border-blue-600 peer-checked:opacity-100 duration-200"
                            style="background-color: rgb(25, 207, 216)">
                        </label>
                    </div>
                </div>
            </div>
            <div class="my-3">
                <flux:legend class="mb-2">Size</flux:legend>
                <div class="grid grid-cols-5 gap-2 ">
                    <div>
                        <input type="checkbox" id="size-1" value="" class="hidden peer">
                        <label for="size-1"
                            class="inline-flex items-center justify-between h-10 w-10 p-2 text-sm font-semibold bg-zinc-900  text-zinc-50 border-1 hover:opacity-100 opacity-25 border-gray-200 rounded-full cursor-pointer peer-checked:border-blue-600 dark:peer-checked:border-blue-600 peer-checked:opacity-100 duration-200">
                            sm
                        </label>
                    </div>
                </div>
            </div>

            <livewire:components.pricefilter />

        </div>
    </flux:sidebar>
</div>
