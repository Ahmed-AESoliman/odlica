<?php

namespace App\Livewire\Cart;

use App\Stores\CartStore;
use Livewire\Component;

class CartCounter extends Component
{
    public $count = 0;

    protected $listeners = ['cart-updated' => 'updateCount'];

    public function mount()
    {
        $this->updateCount();
    }

    public function updateCount()
    {
        $cartStore = app(CartStore::class);
        $this->count = $cartStore->getCartCount();;
    }

    public function render()
    {
        return <<<'BLADE'
        <flux:navbar.item icon="shopping-bag" badge="{{ $count }}" badge-color="red" class="cursor-pointer"
            x-on:click="
                $flux.modal('cart').show()
            ">
        </flux:navbar.item>
        BLADE;
    }
}
