jQuery(function ($) {
    const searchParams = new URLSearchParams(window.location.search);
    if (searchParams.has('search')) {
        var search_value = searchParams.get('search');
    }

    const table_query = {
        "pageLength": 25,
        "lengthMenu": [
            [10, 25, 50, 100, -1],
            [10, 25, 50, 100, "All"]
        ],
        "dom": "lrtip",
        "ordering": false,
        fnDrawCallback: function () {
            var totalPages = this.api().page.info().pages;
            if (totalPages == 1) {
                $('.dataTables_paginate').hide();
            }
            else {
                $('.dataTables_paginate').show();
            }
        }
    };

    var table = $('#glossary-table').DataTable(table_query);

    $('.glossary-search input').on('keyup', function () {
        table.search(this.value).draw();
    });

    if (search_value) {
        table.search(search_value).draw();
    }

});