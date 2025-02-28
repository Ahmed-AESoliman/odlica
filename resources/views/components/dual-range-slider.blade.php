<div x-data="{
    min: {{ $min ?? 0 }},
    max: {{ $max ?? 100 }},
    minValue: {{ $minValue ?? ($min ?? 0) }},
    maxValue: {{ $maxValue ?? ($max ?? 100) }},
    minThumb: null,
    maxThumb: null,
    isDragging: false,
    currentThumb: null,

    init() {
        this.minThumb = this.$refs.minThumb;
        this.maxThumb = this.$refs.maxThumb;

        const onMouseDown = (e, isMin) => {
            e.preventDefault();
            this.isDragging = true;
            this.currentThumb = isMin ? 'min' : 'max';
            document.addEventListener('mousemove', onMouseMove);
            document.addEventListener('mouseup', onMouseUp);
        };

        const onMouseMove = (e) => {
            if (!this.isDragging) return;

            const trackRect = this.$refs.track.getBoundingClientRect();
            const trackWidth = trackRect.width;
            let percentage = Math.min(Math.max(0, (e.clientX - trackRect.left) / trackWidth), 1);

            const newValue = Math.round(this.min + percentage * (this.max - this.min));

            // For min thumb
            if (this.currentThumb === 'min') {
                // If values are equal, ensure min can always move left
                if (this.minValue === this.maxValue) {
                    if (newValue < this.maxValue) {
                        this.minValue = newValue;
                    } else {
                        // If trying to move right, keep them together by updating both
                        const oldMin = this.minValue;
                        this.minValue = Math.min(newValue, this.max);
                        // If min was updated, update max to match
                        if (this.minValue > oldMin) {
                            this.maxValue = this.minValue;
                        }
                    }
                } else {
                    this.minValue = Math.min(newValue, this.maxValue);
                }
            }
            // For max thumb
            else {
                // If values are equal, ensure max can always move right
                if (this.minValue === this.maxValue) {
                    if (newValue > this.minValue) {
                        this.maxValue = newValue;
                    } else {
                        // If trying to move left, keep them together by updating both
                        const oldMax = this.maxValue;
                        this.maxValue = Math.max(newValue, this.min);
                        // If max was updated, update min to match
                        if (this.maxValue < oldMax) {
                            this.minValue = this.maxValue;
                        }
                    }
                } else {
                    this.maxValue = Math.max(newValue, this.minValue);
                }
            }
        };

        const onMouseUp = () => {
            this.isDragging = false;
            document.removeEventListener('mousemove', onMouseMove);
            document.removeEventListener('mouseup', onMouseUp);

            // Only dispatch events when dragging ends
            this.$dispatch('min-changed', this.minValue);
            this.$dispatch('max-changed', this.maxValue);
        };

        this.minThumb.addEventListener('mousedown', (e) => onMouseDown(e, true));
        this.maxThumb.addEventListener('mousedown', (e) => onMouseDown(e, false));
    },

    minThumbPosition() {
        return ((this.minValue - this.min) / (this.max - this.min)) * 100;
    },

    maxThumbPosition() {
        return ((this.maxValue - this.min) / (this.max - this.min)) * 100;
    },

    progressBarStyle() {
        return {
            left: `${this.minThumbPosition()}%`,
            width: `${this.maxThumbPosition() - this.minThumbPosition()}%`
        };
    }
}" class="relative w-full py-4">
    <div class="h-2 bg-gray-200 rounded-full" x-ref="track">
        <!-- Progress bar between two thumbs -->
        <div class="absolute h-2 bg-blue-500 rounded-full"
            :style="`left: ${minThumbPosition()}%; right: ${100 - maxThumbPosition()}%;`"></div>

        <!-- Min value thumb -->
        <div x-ref="minThumb"
            class="absolute w-6 h-6 -mt-2 -ml-3 bg-white border-2 border-blue-500 rounded-full shadow cursor-grab active:cursor-grabbing focus:outline-none"
            :style="`left: ${minThumbPosition()}%`" tabindex="0" role="slider" :aria-valuenow="minValue"
            :aria-valuemin="min" :aria-valuemax="max" aria-label="Minimum value">
        </div>

        <!-- Max value thumb -->
        <div x-ref="maxThumb"
            class="absolute w-6 h-6 -mt-2 -ml-3 bg-white border-2 border-blue-500 rounded-full shadow cursor-grab active:cursor-grabbing focus:outline-none"
            :style="`left: ${maxThumbPosition()}%`" tabindex="0" role="slider" :aria-valuenow="maxValue"
            :aria-valuemin="min" :aria-valuemax="max" aria-label="Maximum value">
        </div>
    </div>
    <div class="flex justify-between mt-2">
        <span class="text-sm text-gray-600" x-text="minValue"></span>
        <span class="text-sm text-gray-600" x-text="maxValue"></span>
    </div>
</div>
