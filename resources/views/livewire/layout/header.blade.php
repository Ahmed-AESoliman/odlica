    <flux:header class="bg-zinc-50 dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-700 mb-8">

        <flux:brand href="#" logo="{{ asset('blue-logo.svg') }}" class="dark:hidden h-auto! w-[10rem]" />
        <flux:brand href="#" logo="{{ asset('white-logo.svg') }}" class="hidden dark:flex h-auto! w-[10rem]" />
        <flux:navbar class="mr-4 ml-auto">
            <flux:navbar.item icon="shopping-bag" badge="12" badge-color="red" class="cursor-pointer"
                x-on:click="$flux.modal('cart').show()">
            </flux:navbar.item>
            <flux:separator vertical />
            <flux:navbar.item x-data x-on:click="$flux.dark = ! $flux.dark" icon="moon" variant="subtle"
                class="cursor-pointer">
            </flux:navbar.item>
        </flux:navbar>

    </flux:header>
