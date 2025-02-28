    <flux:modal name="cart" variant="flyout" class="space-y-6 pl-2!w max-md:w-2xl">
        <div class="flex flex-col gap-2 mt-10">
            <div class="flex items-center gap-4 h-20  w-full">
                <div class="h-full w-1/3"><img src="https://placehold.co/600x400@2x.png" class="w-full h-full"></div>
                <div class="h-full w-1/2">
                    <flux:heading size="lg" class="font-bold!">title</flux:heading>
                    <flux:subheading>1 x 150$</flux:subheading>
                </div>
                <flux:button icon="x-mark" variant="subtle" />
            </div>
            <flux:separator />
        </div>
        <div class="flex justify-between items-center">
            <flux:heading size="xl" class="capitalize">total</flux:heading>
            <flux:heading size="xl">$7,532.16</flux:heading>
        </div>
        <flux:separator />

        <div class="flex justify-between items-center gap-2">
            <flux:button icon-trailing="shopping-cart" class="bg-red-600! hover:bg-red-400! border-0! flex-1">view
                cart
            </flux:button>
            <flux:button icon-trailing="banknotes" class="bg-emerald-600! hover:bg-emerald-400! border-0! flex-1">check
                out
            </flux:button>

        </div>
        {{-- <div class="w-full text-center">
            <flux:subheading>empty cart</flux:subheading>
        </div> --}}
    </flux:modal>
