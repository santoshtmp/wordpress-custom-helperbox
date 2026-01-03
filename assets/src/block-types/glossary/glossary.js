jQuery(($) => {

  let searchValue = '';

  const searchParams = new URLSearchParams(window.location.search);
  if (searchParams.has("search")) {
    searchValue = searchParams.get("search");
  }

  const tableQuery = {
    pageLength: 25,
    lengthMenu: [
      [10, 25, 50, 100, -1],
      [10, 25, 50, 100, "All"],
    ],
    dom: "lrtip",
    ordering: false,
    fnDrawCallback() {
      const totalPages = this.api().page.info().pages;
      if (1 === totalPages) {
        $(".dataTables_paginate").hide();
      } else {
        $(".dataTables_paginate").show();
      }
    },
  };

  const table = $("#glossary-table").DataTable(tableQuery);

  $(".glossary-search input").on("keyup", function () {
    table.search(this.value).draw();
  });

  if (searchValue) {
    table.search(searchValue).draw();
  }
});
