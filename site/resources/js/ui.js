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
 * collapses extra slides to 1 on mobile). Each visible position rotates
 * independently on its own 5s countdown (mirrored in an sr-only live
 * region): hovering a card pauses only that card's timer. Fully static
 * under reduced motion. Hiding is JS-additive: without JS all ads stay
 * stacked.
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

        // Per-lane rotation: each visible position keeps its own countdown
        // and advances on its own clock, so hovering a card pauses only
        // that card's timer.
        const timerLabels = Array.from(root.querySelectorAll('[data-ad-timer]'));
        if (timerLabels.length) root.classList.add('has-timer');
        const srTimer = root.querySelector('[data-ad-sr-timer]');

        const INTERVAL = 5000;
        const current = [];   // slide index shown per lane
        const remaining = []; // ms left per lane
        const paused = [];
        for (let lane = 0; lane < visible; lane += 1) {
            current.push(lane);
            remaining.push(INTERVAL);
            paused.push(false);
        }

        const showLanes = () => {
            slides.forEach((slide) => slide.classList.remove('is-active', 'is-active-extra'));
            current.forEach((slideIndex, lane) => {
                const slide = slides[slideIndex];
                slide.classList.add('is-active');
                if (lane > 0) slide.classList.add('is-active-extra');
            });
        };

        const renderCountdown = () => {
            current.forEach((slideIndex, lane) => {
                const label = slides[slideIndex].querySelector('[data-ad-timer]');
                if (label) label.textContent = `${Math.ceil(remaining[lane] / 1000)}s`;
            });
            if (srTimer) {
                const running = remaining.filter((_, lane) => !paused[lane]);
                srTimer.textContent = running.length
                    ? `Next ad in ${Math.ceil(Math.min(...running) / 1000)} seconds`
                    : 'Ad rotation paused';
            }
        };

        // A lane takes the first free slide after the newest one on display,
        // so two lanes never show the same ad.
        const advance = (lane) => {
            const newest = Math.max(...current);
            for (let step = 1; step <= slides.length; step += 1) {
                const candidate = (newest + step) % slides.length;
                if (!current.includes(candidate)) {
                    current[lane] = candidate;
                    return;
                }
            }
        };

        const tick = () => {
            let changed = false;
            for (let lane = 0; lane < visible; lane += 1) {
                if (paused[lane]) continue;
                remaining[lane] -= 1000;
                if (remaining[lane] <= 0) {
                    advance(lane);
                    remaining[lane] = INTERVAL;
                    changed = true;
                }
            }
            if (changed) showLanes();
            renderCountdown();
        };

        let timer = null;
        const start = () => {
            if (!timer) {
                renderCountdown();
                timer = setInterval(tick, 1000);
            }
        };

        slides.forEach((slide, slideIndex) => {
            slide.addEventListener('mouseenter', () => {
                const lane = current.indexOf(slideIndex);
                if (lane !== -1) paused[lane] = true;
            });
            slide.addEventListener('mouseleave', () => {
                const lane = current.indexOf(slideIndex);
                if (lane !== -1) paused[lane] = false;
            });
        });

        start();
    });
}

/**
 * Docs right-rail TOC scroll-spy: highlights the link of the section
 * currently being read. The active heading is the last one whose top has
 * passed just below the sticky header. Purely additive — without JS all
 * links stay neutral.
 */
export function initDocsToc() {
    const nav = document.querySelector('nav[aria-label="On this page"]');
    const article = document.querySelector('article.prose');
    if (!nav || !article) return;

    const links = new Map();
    nav.querySelectorAll('a[href^="#"]').forEach((link) => {
        links.set(decodeURIComponent(link.getAttribute('href').slice(1)), link);
    });
    const headings = Array.from(article.querySelectorAll('h2[id], h3[id]'))
        .filter((h) => links.has(h.id));
    if (headings.length === 0) return;

    const OFFSET = 120; // sticky header + breathing room
    const setActive = () => {
        let current = headings[0];
        headings.forEach((h) => {
            if (h.getBoundingClientRect().top <= OFFSET) current = h;
        });
        links.forEach((link) => link.classList.remove('toc-link-active'));
        links.get(current.id).classList.add('toc-link-active');
    };

    let raf = null;
    window.addEventListener('scroll', () => {
        if (raf) return;
        raf = requestAnimationFrame(() => { raf = null; setActive(); });
    }, { passive: true });
    setActive();
}
