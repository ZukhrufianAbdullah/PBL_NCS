document.addEventListener('DOMContentLoaded', () => {
    const focusTarget = document.querySelector('[data-autofocus="true"]');
    if (focusTarget) {
        focusTarget.focus();
    }
});

window.searchTable = function searchTable(inputId, tableId) {
    const input = document.getElementById(inputId);
    const table = document.getElementById(tableId);

    if (!input || !table || !table.tBodies.length) {
        return;
    }

    const filter = input.value.toLowerCase();
    const rows = Array.from(table.tBodies[0].rows);

    rows.forEach((row) => {
        const rowText = row.textContent.toLowerCase();
        row.style.display = rowText.includes(filter) ? '' : 'none';
    });
};

window.confirmDelete = function confirmDelete(itemName) {
    const label = itemName || 'data ini';
    return window.confirm(`Apakah Anda yakin ingin menghapus ${label}? Tindakan ini tidak dapat dibatalkan.`);
};

