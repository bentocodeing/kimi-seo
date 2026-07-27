import { initReveal } from './reveal';
import { initBackToTop, initCopyCommand, initStickyHeader } from './ui';

document.addEventListener('DOMContentLoaded', () => {
    initReveal();
    initCopyCommand();
    initStickyHeader();
    initBackToTop();
});
