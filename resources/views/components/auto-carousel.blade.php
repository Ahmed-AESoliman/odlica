<!-- resources/views/components/auto-carousel.blade.php -->
<div x-data="{
    activeSlide: 0,
    slides: [],
    interval: {{ $interval ?? 5000 }},
    autoplayEnabled: {{ $autoplay ?? 'true' }},
    timer: null,

    init() {
        this.slides = Array.from(this.$el.querySelectorAll('.carousel-slide'));
        this.setActiveSlide(0);

        if (this.autoplayEnabled) {
            this.startAutoplay();
        }

        // Add keyboard navigation
        this.$watch('activeSlide', () => this.showSlide(this.activeSlide));
    },

    startAutoplay() {
        this.timer = setInterval(() => {
            this.next();
        }, this.interval);
    },

    stopAutoplay() {
        clearInterval(this.timer);
    },

    setActiveSlide(index) {
        this.activeSlide = index;
        this.slides.forEach((slide, i) => {
            if (i === index) {
                slide.classList.remove('hidden');
                slide.classList.add('block');
            } else {
                slide.classList.remove('block');
                slide.classList.add('hidden');
            }
        });
    },

    next() {
        this.setActiveSlide((this.activeSlide + 1) % this.slides.length);
    },

    prev() {
        this.setActiveSlide((this.activeSlide - 1 + this.slides.length) % this.slides.length);
    },

    showSlide(index) {
        if (this.autoplayEnabled) {
            this.stopAutoplay();
            this.startAutoplay();
        }
        this.setActiveSlide(index);
    },

    pauseAutoplay() {
        if (this.autoplayEnabled) {
            this.stopAutoplay();
        }
    },

    resumeAutoplay() {
        if (this.autoplayEnabled) {
            this.startAutoplay();
        }
    }
}" class="relative w-full overflow-hidden rounded-lg {{ $class ?? '' }}"
    @mouseenter="pauseAutoplay()" @mouseleave="resumeAutoplay()" @keydown.arrow-right.prevent="next()"
    @keydown.arrow-left.prevent="prev()">
    <!-- Carousel slides -->
    <div class="carousel-container relative">
        {{ $slot }}
    </div>

    <!-- Navigation arrows -->
    <button @click.prevent="prev()"
        class="absolute left-2 top-1/2 -translate-y-1/2 p-2 bg-black/30 hover:bg-black/50 text-white rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-all duration-300"
        aria-label="Previous slide">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
    </button>

    <button @click.prevent="next()"
        class="absolute right-2 top-1/2 -translate-y-1/2 p-2 bg-black/30 hover:bg-black/50 text-white rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-all duration-300"
        aria-label="Next slide">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
    </button>

    <!-- Slide indicators -->
    <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2">
        <template x-for="(slide, index) in slides" :key="index">
            <button class="w-3 h-3 rounded-full transition-all duration-300 focus:outline-none"
                :class="{ 'bg-white': activeSlide === index, 'bg-white/50 hover:bg-white/80': activeSlide !== index }"
                @click="showSlide(index)" :aria-label="`Go to slide ${index + 1}`"
                :aria-current="activeSlide === index ? 'true' : 'false'"></button>
        </template>
    </div>
</div>
