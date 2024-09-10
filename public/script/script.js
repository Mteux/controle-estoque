function filtrar() {
  let modFiltro = document.getElementById("form-filtro");
  modFiltro.style.display =
    modFiltro.style.display === "none" ? "block" : "none";
  let buttonPesquisar = document.getElementById("pesquisar");
  pesquisar(buttonPesquisar);
}

function pesquisar(botao) {
  botao.onclick = function () {
    let produto = document.getElementById("filtro-produto").value.toLowerCase();
    let categoria = document
      .getElementById("filtro-categoria")
      .value.toLowerCase();
    let vlrMaximo =
      parseFloat(document.getElementById("vlrMaximo").value) || Infinity;
    let vlrMinimo = parseFloat(document.getElementById("vlrMinimo").value) || 0;
    let quantidade =
      parseInt(document.getElementById("quantidade").value) || null;

    let tabela = document.getElementById("tabela");

    let linhas = Array.from(tabela.querySelectorAll("tbody tr"));

    linhas.forEach(function (linha) {
      let prodTable = linha.querySelector(".produto").innerText.toLowerCase();
      let valorTable = parseFloat(
        linha
          .querySelector(".valor")
          .innerText.replace("R$", "")
          .replace(",", ".")
      );
      let quantTable = parseInt(linha.querySelector(".quantidade").innerText);
      let cateTable = linha
        .querySelector(".quantidade")
        .innerText.toLowerCase();

      let exibir = true;

      if (produto && !prodTable.includes(produto)) {
        exibir = false;
      }
      if (categoria && cateTable !== categoria) {
        exibir = false;
      }
      if (valorTable < vlrMinimo || valorTable > vlrMaximo) {
        exibir = false;
      }
      if (quantidade && quantTable !== quantidade) {
        exibir = false;
      }

      // Mostrar ou esconder a linha com base nos filtros
      linha.style.display = exibir ? "" : "none";
    });
  };
}
