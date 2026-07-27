/**
 * Scroll reveal: elements with [data-reveal] get the .reveal hiding class
 * (JS-only, so content is never hidden without JS) and .is-visible when
 * they enter the viewport. Runs once per element.
 */
export function initReveal() {
    if (!('IntersectionObserver' in window)) return;
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    const observer = new IntersectionObserver(
        (entries) => {
            for (const entry of entries) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            }
        },
        { rootMargin: '0px 0px -10% 0px' },
    );

    document.querySelectorAll('[data-reveal]').forEach((el) => {
        el.classList.add('reveal');
        if (el.dataset.revealDelay) {
            el.classList.add(`reveal-delay-${el.dataset.revealDelay}`);
        }
        observer.observe(el);
    });
}
