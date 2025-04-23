const paginatedTables = document.querySelectorAll(".paginated-table");

paginatedTables.forEach((paginatedTable) => {
  paginatedTable.addEventListener("click", async (e) => {
    if (e.target.closest(".pagination-link")) {
      e.preventDefault();
      const url = e.target.closest(".pagination-link").href;
      const loading = `
        <div class="is-fetching p-4">
          ${createLoadingAnimation()}
        </div>
      `;
      const tableContent = paginatedTable.querySelector(".table-content");
      tableContent.innerHTML = loading;

      const response = await fetchRequest(url, "GET");
      if (response.ok) {
        const json = await response.json();
        paginatedTable.innerHTML = json.table;
      } else {
        handleError(response, tableContent);
      }
    }
  });
});
