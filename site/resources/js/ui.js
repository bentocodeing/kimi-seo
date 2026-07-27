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
