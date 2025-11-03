/**
 * Global DataTables Configuration
 */
const dataTablesConfig = {
    language: {
        search: "Cari:",
        lengthMenu: "Tampilkan _MENU_ data per halaman",
        info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
        infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
        infoFiltered: "(disaring dari _MAX_ total data)",
        zeroRecords: "Tidak ada data yang ditemukan",
        paginate: {
            first: "Pertama",
            last: "Terakhir",
            next: "›",
            previous: "‹"
        }
    },
    responsive: true,
    pageLength: 10,
    lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Semua"]],
    autoWidth: false
};

/**
 * Initialize DataTable with custom config
 * @param {string} selector - Table selector
 * @param {object} customConfig - Additional configuration
 */
function initDataTable(selector, customConfig = {}) {
    return $(selector).DataTable({
        ...dataTablesConfig,
        ...customConfig
    });
}