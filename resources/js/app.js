/**
 * Motion engine — lightweight, dependency-free.
 *
 * Responsibilities:
 *  - Add `.motion-ready` to <html> so scroll-reveal styles only apply with JS.
 *  - Stagger direct children of any `[data-stagger]` container.
 *  - Reveal `[data-animate]` elements on scroll via IntersectionObserver.
 *  - Auto-dismiss session toasts (`[data-toast]`).
 *
 * Everything degrades gracefully: without JS the CSS keyframe animations and
 * nth-child stagger still play, and content is never hidden.
 */

const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

function staggerContainers() {
    document.querySelectorAll('[data-stagger]').forEach((container) => {
        const step = parseInt(container.dataset.staggerStep || '70', 10);
        Array.from(container.children).forEach((child, index) => {
            child.style.animationDelay = `${index * step}ms`;
        });
    });
}

function revealOnScroll() {
    const targets = document.querySelectorAll('[data-animate]');
    if (prefersReduced || !('IntersectionObserver' in window)) {
        targets.forEach((el) => el.classList.add('is-visible'));
        return;
    }

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.12, rootMargin: '0px 0px -8% 0px' },
    );

    targets.forEach((el) => observer.observe(el));
}

function initSubmitLoading() {
    document.querySelectorAll('form[data-loading-on-submit]').forEach((form) => {
        form.addEventListener('submit', () => {
            if (typeof form.checkValidity === 'function' && !form.checkValidity()) {
                return;
            }
            const button = form.querySelector('button[type="submit"]');
            if (button) {
                button.classList.add('btn-loading');
                button.disabled = true;
            }
        });
    });
}

function initToasts() {
    document.querySelectorAll('[data-toast]').forEach((toast, index) => {
        if (prefersReduced) return;

        const ttl = parseInt(toast.dataset.toastTtl || '4500', 10);
        const delay = 80 + index * 90;

        window.setTimeout(() => {
            toast.classList.add('is-leaving');
            window.setTimeout(() => toast.remove(), 340);
        }, ttl + delay);
    });
}

function init() {
    staggerContainers();
    revealOnScroll();
    initToasts();
    initSubmitLoading();
    document.documentElement.classList.add('motion-ready');
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
} else {
    init();
}
