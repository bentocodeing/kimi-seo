import { initReveal } from './reveal';
import { initAdCarousels, initBackToTop, initCopyCommand, initStickyHeader } from './ui';
import { initAdReorder } from './admin';

document.addEventListener('DOMContentLoaded', () => {
    initReveal();
    initCopyCommand();
    initStickyHeader();
    initBackToTop();
    initAdCarousels();
    initAdReorder();
});
