<div x-data="{
    notifications: [],
    add(message, type = 'success', timeout = 3000) {
        // Handle object format
        if (typeof message === 'object') {
            type = message[0].type || type;
            message = message[0].message || 'Notification';
        }

        const id = Date.now();
        this.notifications.push({
            id,
            message,
            type,
            show: false
        });

        // Trigger enter animation in next tick
        setTimeout(() => {
            const notification = this.notifications.find(n => n.id === id);
            if (notification) notification.show = true;
        }, 50);

        // Auto-remove after timeout
        setTimeout(() => {
            this.remove(id);
        }, timeout);
    },
    remove(id) {
        const index = this.notifications.findIndex(n => n.id === id);
        if (index >= 0) {
            this.notifications[index].show = false;

            setTimeout(() => {
                this.notifications = this.notifications.filter(n => n.id !== id);
            }, 300);
        }
    }
}" @notify.window="add($event.detail)" class="fixed top-4 right-4 z-50 space-y-3 w-72">
    <template x-for="notification in notifications" :key="notification.id">
        <div x-show="notification.show" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-x-8"
            x-transition:enter-end="opacity-100 transform translate-x-0"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 transform translate-x-0"
            x-transition:leave-end="opacity-0 transform translate-x-8"
            :class="{
                'bg-green-500': notification.type === 'success',
                'bg-red-500': notification.type === 'error',
                'bg-blue-500': notification.type === 'info',
                'bg-yellow-500': notification.type === 'warning'
            }"
            class="rounded-lg p-4 text-white shadow-lg flex items-start justify-between">
            <div class="flex items-center">
                <div x-show="notification.type === 'success'" class="mr-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div x-show="notification.type === 'error'" class="mr-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </div>
                <div x-show="notification.type === 'info'" class="mr-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div x-show="notification.type === 'warning'" class="mr-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                        </path>
                    </svg>
                </div>
                <span x-text="notification.message" class="text-sm"></span>
            </div>
            <button @click="remove(notification.id)" class="ml-4 text-white hover:text-gray-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>
    </template>
</div>
