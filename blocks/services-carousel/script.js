class ServicesCarousel {
    constructor(carouselElement) {
        this.carouselElement = carouselElement;
        this.carouselId = carouselElement.getAttribute('data-carousel-id');
        
        this.currentIndex = 0;
        this.itemsPerView = this.calculateItemsPerView();
        this.itemWidth = this.calculateItemWidth();
        
        // Get actual number of services from DOM
        this.totalItems = this.carouselElement.querySelectorAll('.service-card').length;
        this.maxIndex = Math.max(0, this.totalItems - this.itemsPerView);

        // Use unique selectors for this carousel instance
        this.track = document.getElementById(`${this.carouselId}-track`);
        this.prevBtn = document.getElementById(`${this.carouselId}-prev`);
        this.nextBtn = document.getElementById(`${this.carouselId}-next`);

        this.touchStartX = 0;
        this.touchStartY = 0;
        this.isDragging = false;
        this.startTransform = 0;

        this.init();
    }

    init() {
        this.bindEvents();
        this.updateButtons();
        this.handleResize();
        this.updateCarousel();
    }

    calculateItemsPerView() {
        const screenWidth = window.innerWidth;
        if (screenWidth < 480) return 2;
        if (screenWidth < 768) return 3;
        if (screenWidth < 1024) return 5;
        if (screenWidth < 1280) return 6;
        if (screenWidth < 1440) return 7;
        return 8;
    }

    calculateItemWidth() {
        const screenWidth = window.innerWidth;
        if (screenWidth < 768) return 136; // 120px + 16px gap
        return 163; // 139px + 24px gap
    }

    bindEvents() {
        // Button events - scoped to this carousel instance
        this.prevBtn.addEventListener('click', () => this.goToPrevious());
        this.nextBtn.addEventListener('click', () => this.goToNext());

        // Keyboard events - only when focused on this carousel
        this.carouselElement.addEventListener('keydown', (e) => this.handleKeyboard(e));

        // Touch events - scoped to this carousel's track
        this.track.addEventListener('touchstart', (e) => this.handleTouchStart(e), {
            passive: true
        });
        this.track.addEventListener('touchmove', (e) => this.handleTouchMove(e), {
            passive: false
        });
        this.track.addEventListener('touchend', (e) => this.handleTouchEnd(e), {
            passive: true
        });

        // Mouse events for desktop dragging - scoped to this carousel's track
        this.track.addEventListener('mousedown', (e) => this.handleMouseDown(e));
        
        // Use bound methods to ensure proper cleanup
        this.boundMouseMove = (e) => this.handleMouseMove(e);
        this.boundMouseUp = (e) => this.handleMouseUp(e);
        
        document.addEventListener('mousemove', this.boundMouseMove);
        document.addEventListener('mouseup', this.boundMouseUp);

        // Resize event - throttled per instance
        this.boundResize = () => this.handleResize();
        window.addEventListener('resize', this.boundResize);

        // Prevent context menu on long press
        this.track.addEventListener('contextmenu', (e) => {
            if (this.isDragging) {
                e.preventDefault();
            }
        });
    }

    handleKeyboard(e) {
        if (e.key === 'ArrowLeft') {
            e.preventDefault();
            this.goToPrevious();
        } else if (e.key === 'ArrowRight') {
            e.preventDefault();
            this.goToNext();
        }
    }

    handleTouchStart(e) {
        this.touchStartX = e.touches[0].clientX;
        this.touchStartY = e.touches[0].clientY;
        this.isDragging = false;
        this.startTransform = this.currentIndex * this.itemWidth;
        this.track.style.transition = 'none';
    }

    handleTouchMove(e) {
        if (!this.touchStartX) return;

        const touchCurrentX = e.touches[0].clientX;
        const touchCurrentY = e.touches[0].clientY;

        const diffX = this.touchStartX - touchCurrentX;
        const diffY = this.touchStartY - touchCurrentY;

        if (Math.abs(diffX) > Math.abs(diffY) && Math.abs(diffX) > 10) {
            e.preventDefault();
            this.isDragging = true;

            const newTransform = this.startTransform + diffX;
            const minTransform = 0;
            const maxTransform = this.maxIndex * this.itemWidth;

            let boundedTransform = Math.max(minTransform, Math.min(maxTransform, newTransform));

            if (newTransform < minTransform) {
                boundedTransform = minTransform - (minTransform - newTransform) * 0.3;
            } else if (newTransform > maxTransform) {
                boundedTransform = maxTransform + (newTransform - maxTransform) * 0.3;
            }

            this.track.style.transform = `translateX(-${boundedTransform}px)`;
        }
    }

    handleTouchEnd(e) {
        if (!this.touchStartX || !this.isDragging) {
            this.track.style.transition = '';
            return;
        }

        const touchEndX = e.changedTouches[0].clientX;
        const diffX = this.touchStartX - touchEndX;

        this.track.style.transition = '';

        const swipeThreshold = 50;
        const velocityThreshold = 30;

        if (Math.abs(diffX) > swipeThreshold || Math.abs(diffX) > velocityThreshold) {
            if (diffX > 0) {
                this.goToNext();
            } else {
                this.goToPrevious();
            }
        } else {
            this.updateCarousel();
        }

        this.touchStartX = 0;
        this.touchStartY = 0;
        this.isDragging = false;
    }

    handleMouseDown(e) {
        e.preventDefault();
        this.touchStartX = e.clientX;
        this.isDragging = false;
        this.startTransform = this.currentIndex * this.itemWidth;
        this.track.style.transition = 'none';
        this.track.style.cursor = 'grabbing';
    }

    handleMouseMove(e) {
        if (!this.touchStartX) return;

        const diffX = this.touchStartX - e.clientX;

        if (Math.abs(diffX) > 5) {
            this.isDragging = true;

            const newTransform = this.startTransform + diffX;
            const minTransform = 0;
            const maxTransform = this.maxIndex * this.itemWidth;

            let boundedTransform = Math.max(minTransform, Math.min(maxTransform, newTransform));

            if (newTransform < minTransform) {
                boundedTransform = minTransform - (minTransform - newTransform) * 0.3;
            } else if (newTransform > maxTransform) {
                boundedTransform = maxTransform + (newTransform - maxTransform) * 0.3;
            }

            this.track.style.transform = `translateX(-${boundedTransform}px)`;
        }
    }

    handleMouseUp(e) {
        if (!this.touchStartX) return;

        const diffX = this.touchStartX - e.clientX;

        this.track.style.transition = '';
        this.track.style.cursor = '';

        if (this.isDragging) {
            const swipeThreshold = 50;

            if (Math.abs(diffX) > swipeThreshold) {
                if (diffX > 0) {
                    this.goToNext();
                } else {
                    this.goToPrevious();
                }
            } else {
                this.updateCarousel();
            }
        }

        this.touchStartX = 0;
        this.isDragging = false;
    }

    handleResize() {
        clearTimeout(this.resizeTimeout);
        this.resizeTimeout = setTimeout(() => {
            const newItemsPerView = this.calculateItemsPerView();
            const newItemWidth = this.calculateItemWidth();

            if (newItemsPerView !== this.itemsPerView || newItemWidth !== this.itemWidth) {
                this.itemsPerView = newItemsPerView;
                this.itemWidth = newItemWidth;
                this.maxIndex = Math.max(0, this.totalItems - this.itemsPerView);

                if (this.currentIndex > this.maxIndex) {
                    this.currentIndex = this.maxIndex;
                }

                this.updateCarousel();
                this.updateButtons();
            }
        }, 150);
    }

    goToPrevious() {
        if (this.currentIndex > 0) {
            this.currentIndex--;
            this.updateCarousel();
            this.updateButtons();
            this.announceChange('Previous services');
        }
    }

    goToNext() {
        if (this.currentIndex < this.maxIndex) {
            this.currentIndex++;
            this.updateCarousel();
            this.updateButtons();
            this.announceChange('Next services');
        }
    }

    updateCarousel() {
        const translateX = -this.currentIndex * this.itemWidth;
        this.track.style.transform = `translateX(${translateX}px)`;
    }

    updateButtons() {
        this.prevBtn.disabled = this.currentIndex === 0;
        this.nextBtn.disabled = this.currentIndex >= this.maxIndex;

        this.prevBtn.setAttribute('aria-disabled', this.currentIndex === 0);
        this.nextBtn.setAttribute('aria-disabled', this.currentIndex >= this.maxIndex);
    }

    announceChange(message) {
        let announcement = document.getElementById(`${this.carouselId}-announcement`);
        if (!announcement) {
            announcement = document.createElement('div');
            announcement.id = `${this.carouselId}-announcement`;
            announcement.setAttribute('aria-live', 'polite');
            announcement.setAttribute('aria-atomic', 'true');
            announcement.style.position = 'absolute';
            announcement.style.left = '-10000px';
            announcement.style.width = '1px';
            announcement.style.height = '1px';
            announcement.style.overflow = 'hidden';
            this.carouselElement.appendChild(announcement);
        }

        announcement.textContent = message;
    }

    // Cleanup method
    destroy() {
        // Remove event listeners
        window.removeEventListener('resize', this.boundResize);
        document.removeEventListener('mousemove', this.boundMouseMove);
        document.removeEventListener('mouseup', this.boundMouseUp);
        
        // Clear timeouts
        if (this.resizeTimeout) {
            clearTimeout(this.resizeTimeout);
        }
        if (this.autoPlayInterval) {
            clearInterval(this.autoPlayInterval);
        }
    }

    // Auto-play functionality (optional)
    startAutoPlay(interval = 5000) {
        this.stopAutoPlay();
        this.autoPlayInterval = setInterval(() => {
            if (this.currentIndex >= this.maxIndex) {
                this.currentIndex = 0;
            } else {
                this.currentIndex++;
            }
            this.updateCarousel();
            this.updateButtons();
        }, interval);
    }

    stopAutoPlay() {
        if (this.autoPlayInterval) {
            clearInterval(this.autoPlayInterval);
            this.autoPlayInterval = null;
        }
    }
}

// Carousel Manager to handle multiple instances
class CarouselManager {
    constructor() {
        this.carousels = new Map();
        this.init();
    }

    init() {
        // Initialize all service carousels on the page
        const carouselElements = document.querySelectorAll('.services-carousel[data-carousel-id]');
        
        carouselElements.forEach(element => {
            const carouselId = element.getAttribute('data-carousel-id');
            if (!this.carousels.has(carouselId)) {
                const carousel = new ServicesCarousel(element);
                this.carousels.set(carouselId, carousel);
            }
        });
    }

    destroyAll() {
        this.carousels.forEach(carousel => carousel.destroy());
        this.carousels.clear();
    }

    getCarousel(id) {
        return this.carousels.get(id);
    }
}

// Initialize carousel manager when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    // Create global carousel manager
    window.servicesCarouselManager = new CarouselManager();
});

// Add intersection observer for performance optimization
if ('IntersectionObserver' in window) {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('carousel-visible');
            }
        });
    }, {
        threshold: 0.1
    });

    document.addEventListener('DOMContentLoaded', () => {
        const carouselElements = document.querySelectorAll('.services-carousel');
        carouselElements.forEach(element => {
            observer.observe(element);
        });
    });
}

// Handle page unload cleanup
window.addEventListener('beforeunload', () => {
    if (window.servicesCarouselManager) {
        window.servicesCarouselManager.destroyAll();
    }
});