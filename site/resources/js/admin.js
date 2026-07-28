/**
 * Admin ads index: drag-and-drop row ordering (HTML5 drag events, handle-only).
 * On drag end the new ordered id list is POSTed to the reorder endpoint.
 */
export function initAdReorder() {
    const list = document.querySelector('[data-reorder-list]');
    if (!list) return;

    const status = document.querySelector('[data-reorder-status]');
    let dragged = null;

    list.addEventListener('dragstart', (event) => {
        const row = event.target.closest('tr[data-ad-id]');
        if (!row) return;
        dragged = row;
        row.classList.add('is-dragging');
        event.dataTransfer.effectAllowed = 'move';
        event.dataTransfer.setData('text/plain', row.dataset.adId);
    });

    list.addEventListener('dragover', (event) => {
        if (!dragged) return;
        event.preventDefault();
        const row = event.target.closest('tr[data-ad-id]');
        if (!row || row === dragged) return;
        const rect = row.getBoundingClientRect();
        const before = event.clientY - rect.top < rect.height / 2;
        list.insertBefore(dragged, before ? row : row.nextSibling);
    });

    list.addEventListener('drop', (event) => event.preventDefault());

    list.addEventListener('dragend', () => {
        if (dragged) dragged.classList.remove('is-dragging');
        dragged = null;
        persist();
    });

    async function persist() {
        const ids = Array.from(list.querySelectorAll('tr[data-ad-id]'))
            .map((row) => Number(row.dataset.adId));
        try {
            const response = await fetch(list.dataset.reorderUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    Accept: 'application/json',
                },
                body: JSON.stringify({ ids }),
            });
            if (!response.ok) throw new Error(`HTTP ${response.status}`);
            if (status) {
                status.textContent = 'Order saved.';
                setTimeout(() => { status.textContent = ''; }, 2000);
            }
        } catch {
            window.location.reload();
        }
    }
}
