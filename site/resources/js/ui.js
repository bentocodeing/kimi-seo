/**
 * Small UI behaviours for the public site:
 * - copy install command button (command read from the DOM, single source)
 * - sticky header shadow once scrolled
 * - back-to-top floating button
 */
export function initCopyCommand() {
    const button = document.querySelector('[data-copy-command]');
    const source = document.querySelector('.terminal-typing');
    if (!button || !source) return;

    button.addEventListener('click', async () => {
        const command = source.textContent.trim();
        try {
            if (!navigator.clipboard || !navigator.clipboard.writeText) {
                throw new Error('Clipboard API unavailable');
            }
            await navigator.clipboard.writeText(command);
        } catch {
            // Fallback: select the command text so the user can copy it.
            const selection = window.getSelection();
            const range = document.createRange();
            range.selectNodeContents(source);
            selection.removeAllRanges();
            selection.addRange(range);
        }
        button.classList.add('copied');
        setTimeout(() => button.classList.remove('copied'), 1500);
    });
}

export function initStickyHeader() {
    const header = document.querySelector('[data-sticky-header]');
    if (!header) return;

    const onScroll = () => header.classList.toggle('is-scrolled', window.scrollY > 8);
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
}

export function initBackToTop() {
    const button = document.querySelector('[data-back-to-top]');
    if (!button) return;

    const onScroll = () => button.classList.toggle('is-visible', window.scrollY > 600);
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();

    button.addEventListener('click', () => {
        const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        window.scrollTo({ top: 0, behavior: reduced ? 'auto' : 'smooth' });
    });
}

/**
 * Public ad slot carousel: shows `data-visible` slides at a time (the CSS
 * collapses extra slides to 1 on mobile), rotates the window every 5s with
 * a per-card countdown (mirrored in an sr-only live region), pauses on
 * hover, and stays fully static under reduced motion. Hiding is
 * JS-additive: without JS all ads stay stacked.
 */
export function initAdCarousels() {
    document.querySelectorAll('[data-ad-carousel]').forEach((root) => {
        const slides = Array.from(root.querySelectorAll('.ad-slide'));
        if (slides.length === 0) return;

        const visible = Math.min(parseInt(root.dataset.visible || '1', 10), slides.length);
        root.classList.add('carousel-on');

        let index = 0;
        const show = () => {
            slides.forEach((slide) => slide.classList.remove('is-active', 'is-active-extra'));
            for (let i = 0; i < visible; i += 1) {
                const slide = slides[(index + i) % slides.length];
                slide.classList.add('is-active');
                if (i > 0) slide.classList.add('is-active-extra');
            }
        };
        show();

        // Static when every ad fits in the window, or under reduced motion.
        if (slides.length <= visible) return;
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

        // Per-card countdown labels, kept in sync with the rotation, plus
        // an sr-only mirror for screen readers.
        const timerLabels = Array.from(root.querySelectorAll('[data-ad-timer]'));
        if (timerLabels.length) root.classList.add('has-timer');
        const srTimer = root.querySelector('[data-ad-sr-timer]');

        const INTERVAL = 5000;
        let remaining = INTERVAL;
        let timer = null;

        const renderCountdown = () => {
            const seconds = Math.ceil(remaining / 1000);
            timerLabels.forEach((el) => { el.textContent = `${seconds}s`; });
            if (srTimer) srTimer.textContent = `Next ad in ${seconds} seconds`;
        };

        const tick = () => {
            remaining -= 1000;
            if (remaining <= 0) {
                index = (index + 1) % slides.length;
                show();
                remaining = INTERVAL;
            }
            renderCountdown();
        };
        const start = () => {
            if (!timer) {
                renderCountdown();
                timer = setInterval(tick, 1000);
            }
        };
        const stop = () => {
            clearInterval(timer);
            timer = null;
        };

        root.addEventListener('mouseenter', stop);
        root.addEventListener('mouseleave', start);
        start();
    });
}
