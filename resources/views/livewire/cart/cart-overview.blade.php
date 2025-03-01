<flux:modal name="cart" variant="flyout" class="space-y-6 pl-2!w max-md:w-2xl">
    @if (count($cartItems) > 0)
        <div class="flex flex-col gap-2 mt-10">
            @foreach ($cartItems as $item)
                <div class="flex items-center gap-4 h-20 w-full">
                    <div class="h-full w-1/3">
                        <img src="{{ asset('storage/' . $item['image']) }}" class="w-full h-full object-cover"
                            alt="{{ $item['name'] }}">
                    </div>
                    <div class="h-full w-1/2">
                        <flux:heading size="lg" class="font-bold! truncate">{{ $item['name'] }}</flux:heading>
                        <flux:subheading>{{ $item['quantity'] }} x ${{ number_format($item['price'], 2) }}
                        </flux:subheading>
                        @if (!empty($item['variant_details']))
                            <div class="text-xs text-gray-500">
                                @if (!empty($item['variant_details']['color']))
                                    <span class="inline-block mr-1">
                                        Color: {{ $item['variant_details']['color']['name'] }}
                                    </span>
                                @endif

                                @if (!empty($item['variant_details']['size']))
                                    <span class="inline-block">
                                        Size: {{ $item['variant_details']['size']['name'] }}
                                    </span>
                                @endif
                            </div>
                        @endif
                    </div>
                    <flux:button icon="x-mark" variant="subtle"
                        wire:click="removeItem({itemId: {{ $item['id'] }}})" />
                </div>
                <flux:separator />
            @endforeach
        </div>
        <div class="flex justify-between items-center">
            <flux:heading size="xl" class="capitalize">total</flux:heading>
            <flux:heading size="xl">${{ number_format($cartTotal, 2) }}</flux:heading>
        </div>
        <flux:separator />

        <div class="flex justify-between items-center gap-2">
            <flux:button icon-trailing="shopping-cart" class="bg-red-600! hover:bg-red-400! border-0! flex-1">
                view cart
            </flux:button>
            <flux:button icon-trailing="banknotes" class="bg-emerald-600! hover:bg-emerald-400! border-0! flex-1">
                check out
            </flux:button>
        </div>
    @else
        <div class="w-full text-center py-8">
            <flux:icon name="shopping-cart" class="h-12 w-12 mx-auto text-gray-400 mb-4" />
            <flux:heading size="lg">Your cart is empty</flux:heading>
            <flux:subheading class="mt-2">Add items to your cart to see them here</flux:subheading>
            <flux:button class="mt-6" href="{{ route('products.index') }}" wire:navigate>
                Continue Shopping
            </flux:button>
        </div>
    @endif
</flux:modal>
