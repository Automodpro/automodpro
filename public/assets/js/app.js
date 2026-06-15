// AutoMod Pro — App JavaScript

document.addEventListener('DOMContentLoaded', function() {

    // --- Initialize DataTables ---
    document.querySelectorAll('.datatable').forEach(function(el) {
        if (typeof $ !== 'undefined' && $.fn.DataTable) {
            $(el).DataTable({
                language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json' },
                pageLength: 10,
                responsive: true,
                order: [[0, 'desc']],
                dom: "<'table-header'<'length'l><'filter'f>>" +
                     "<'table-scroll'tr>" +
                     "<'table-footer'<'info'i><'pagination'p>>"
            });
        }
    });

    // --- User Dropdown Toggle ---
    document.querySelectorAll('.user-dropdown-toggle').forEach(function(el) {
        el.addEventListener('click', function(e) {
            e.stopPropagation();
            var menu = this.nextElementSibling;
            if (menu && menu.classList.contains('dropdown-menu')) {
                menu.classList.toggle('show');
            }
        });
    });

    document.addEventListener('click', function() {
        document.querySelectorAll('.dropdown-menu.show').forEach(function(m) {
            m.classList.remove('show');
        });
    });

    // --- Auto-dismiss alerts ---
    document.querySelectorAll('.alert .btn-close').forEach(function(btn) {
        btn.addEventListener('click', function() {
            this.parentElement.remove();
        });
    });

    setTimeout(function() {
        document.querySelectorAll('.alert').forEach(function(el) {
            el.style.transition = 'opacity 400ms ease, transform 400ms ease';
            el.style.opacity = '0';
            el.style.transform = 'translateY(-8px)';
            setTimeout(function() { el.remove(); }, 400);
        });
    }, 6000);

    // --- Sidebar mobile toggle ---
    var toggleBtn = document.getElementById('sidebarToggle');
    if (toggleBtn) {
        toggleBtn.addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('open');
        });
    }

    // --- Confirm dialogs ---
    document.querySelectorAll('[data-confirm]').forEach(function(el) {
        el.addEventListener('click', function(e) {
            if (!confirm(this.dataset.confirm || '¿Está seguro?')) {
                e.preventDefault();
            }
        });
    });

    // --- Input mask for uppercase license plates ---
    document.querySelectorAll('.input-placa').forEach(function(el) {
        el.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });
    });

});
