function filtrar() {
  let modFiltro = document.getElementById("form-filtro");
  if (modFiltro.style.display == "none") {
    modFiltro.style.display = "block";
  } else {
    modFiltro.style.display = "none";
  }
  let buttonPesquisar = document.getElementById("pesquisar");
  pesquisar(buttonPesquisar);
}

function pesquisar(botao) {
  botao.onclick = function () {
    let produto = document.getElementById("filtro-produto").value;
    let categoria = document.getElementById("filtro-categoria").value;
    let vlrMaximo = document.getElementById("vlrMaximo").value;
    let vlrMinimo = document.getElementById("vlrMinimo").value;
    let quantidade = document.getElementById("quantidade").value;

    let tabela = document.getElementById("tabela");

    let linhas = Array.from(tabela.querySelectorAll("tbody tr"));

    linhas.forEach(function (linha) {
      let celulas = Array.from(linha.querySelectorAll("td"));
      let prodTable = celulas[0].innerText;
      let valorTable = celulas[1].innerText;
      let quantTable = celulas[2].innerText;
      let cateTable = celulas[3].innerText;

      console.log(
        `celulas: ${celulas[1].innerText} Produto : ${prodTable} , valor : ${valorTable}, quantidade: ${quantTable}, categoria: ${cateTable}`
      );
    });
  };
}
