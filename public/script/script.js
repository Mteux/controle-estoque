document.addEventListener("DOMContentLoaded", function () {
  // Esconde o filtro inicialmente (opcional)
  document.getElementById("form-filtro").style.display = "none";

  // Adiciona evento ao botão pesquisar
  document.getElementById("pesquisar").addEventListener("click", function () {
    let produto = document.getElementById("filtro-produto").value.toLowerCase();
    let categoria = document
      .getElementById("filtro-categoria")
      .value.toLowerCase();
    let vlrMaximo =
      parseFloat(document.getElementById("vlrMaximo").value) || Infinity;
    let vlrMinimo = parseFloat(document.getElementById("vlrMinimo").value) || 0;
    let quantidade =
      parseInt(document.getElementById("quantidade").value) || null;

    let linhas = Array.from(document.querySelectorAll("#tabela tbody tr"));

    linhas.forEach(function (linha) {
      let prodTable = linha.querySelector(".produto").innerText.toLowerCase();
      let valorTable = parseFloat(
        linha
          .querySelector(".valor")
          .innerText.replace("R$", "")
          .replace(",", ".")
      );
      let quantTable = parseInt(linha.querySelector(".quantidade").innerText);
      let cateTable = linha.querySelector(".categoria").innerText.toLowerCase();

      let exibir = true;

      if (produto && !prodTable.includes(produto)) exibir = false;
      if (categoria && !cateTable.includes(categoria)) exibir = false;
      if (valorTable < vlrMinimo || valorTable > vlrMaximo) exibir = false;
      if (quantidade !== null && quantTable !== quantidade) exibir = false;

      linha.style.display = exibir ? "" : "none";
    });
  });
});

// Mostrar/esconder o formulário
function filtrar() {
  let modFiltro = document.getElementById("form-filtro");
  modFiltro.style.display =
    modFiltro.style.display === "none" ? "block" : "none";
}

document.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll(".deletar-produto").forEach((btn) => {
    btn.addEventListener("click", function () {
      const row = btn.closest("tr");
      const id = row.getAttribute("data-id");

      if (!id) {
        alert("ID do produto não encontrado.");
        return;
      }

      if (confirm("Deseja realmente excluir este produto?")) {
        fetch(`/deletar-produto?id=${id}`)
          .then((response) => response.text())
          .then((data) => {
            if (data.trim() === "success") {
              row.remove();
            } else {
              alert("Erro ao excluir produto.");
            }
          })
          .catch((error) => {
            console.error("Erro na requisição:", error);
            alert("Erro ao excluir produto.");
          });
      }
    });
  });
});
