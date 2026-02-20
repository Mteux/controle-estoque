// ===== FUNÇÕES DE FILTRO =====

// Mostrar/esconder formulário de filtro
function filtrar() {
  let formFiltro = document.getElementById("form-filtro");
  if (formFiltro) {
    if (
      formFiltro.style.display === "none" ||
      formFiltro.style.display === ""
    ) {
      formFiltro.style.display = "block";
    } else {
      formFiltro.style.display = "none";
    }
  }
}

// Limpar todos os filtros
function limparFiltros() {
  // Limpar campos
  document.getElementById("filtro-produto").value = "";
  document.getElementById("filtro-categoria").value = "";
  document.getElementById("vlrMaximo").value = "";
  document.getElementById("vlrMinimo").value = "";
  document.getElementById("quantidade").value = "";

  // Mostrar todas as linhas
  let linhas = document.querySelectorAll("#tabela tbody tr");
  linhas.forEach((linha) => {
    linha.style.display = "";
  });

  // Mostrar mensagem
  alert("Filtros limpos!");
}

// Aplicar filtros
function aplicarFiltro() {
  // Pegar valores dos filtros
  let produto = document.getElementById("filtro-produto").value.toLowerCase();
  let categoria = document
    .getElementById("filtro-categoria")
    .value.toLowerCase();
  let vlrMaximo = document.getElementById("vlrMaximo").value;
  let vlrMinimo = document.getElementById("vlrMinimo").value;
  let quantidade = document.getElementById("quantidade").value;

  // Converter valores para número
  vlrMaximo = vlrMaximo ? parseFloat(vlrMaximo) : Infinity;
  vlrMinimo = vlrMinimo ? parseFloat(vlrMinimo) : 0;
  quantidade = quantidade ? parseInt(quantidade) : null;

  // Pegar todas as linhas da tabela
  let linhas = document.querySelectorAll("#tabela tbody tr");
  let encontrados = 0;

  linhas.forEach((linha) => {
    // Pegar valores da linha
    let prodTable =
      linha.querySelector(".produto")?.innerText.toLowerCase() || "";
    let valorText = linha.querySelector(".valor")?.innerText || "R$ 0,00";
    let quantTable = linha.querySelector(".quantidade")?.innerText || "0";
    let cateTable =
      linha.querySelector(".categoria")?.innerText.toLowerCase() || "";

    // Limpar o valor para comparação (remover R$, pontos, vírgulas)
    let valorTable =
      parseFloat(
        valorText.replace("R$", "").replace(/\./g, "").replace(",", "."),
      ) || 0;
    quantTable = parseInt(quantTable) || 0;

    // Aplicar filtros
    let mostrar = true;

    if (produto && !prodTable.includes(produto)) mostrar = false;
    if (categoria && !cateTable.includes(categoria)) mostrar = false;
    if (valorTable < vlrMinimo || valorTable > vlrMaximo) mostrar = false;
    if (quantidade !== null && quantTable !== quantidade) mostrar = false;

    // Mostrar ou esconder a linha
    if (mostrar) {
      linha.style.display = "";
      encontrados++;
    } else {
      linha.style.display = "none";
    }
  });
}

// ===== FUNÇÕES DE EXCLUSÃO =====
function initDeleteButtons() {
  document.querySelectorAll(".deletar-produto").forEach((btn) => {
    btn.addEventListener("click", function (e) {
      e.preventDefault();
      e.stopPropagation();

      const row = this.closest("tr");
      const id = row?.getAttribute("data-id");
      const produto = row?.querySelector(".produto")?.innerText || "Produto";

      if (!id) {
        alert("ID do produto não encontrado.");
        return;
      }

      if (confirm(`Deseja realmente excluir o produto "${produto}"?`)) {
        // Aqui você pode adicionar a lógica de exclusão via AJAX
        fetch(`/deletar-produto?id=${id}`)
          .then((response) => response.text())
          .then((data) => {
            if (data.trim() === "success") {
              row.remove();
              alert("Produto excluído com sucesso!");
            } else {
              alert("Erro ao excluir produto.");
            }
          })
          .catch((error) => {
            console.error("Erro:", error);
            alert("Erro ao excluir produto.");
          });
      }
    });
  });
}

// ===== FUNÇÕES DE EXCLUSÃO =====
function initDeleteButtons() {
  let botoes = document.querySelectorAll(".deletar-produto");

  botoes.forEach((botao) => {
    botao.onclick = function (e) {
      e.preventDefault();

      let linha = this.closest("tr");
      let id = linha.getAttribute("data-id");
      let produto = linha.querySelector(".produto")?.innerText || "Produto";

      if (!id) {
        alert("ID do produto não encontrado!");
        return;
      }

      if (confirm(`Tem certeza que deseja excluir o produto "${produto}"?`)) {
        // Redireciona para a URL de exclusão
        window.location.href = `/deletar-produto?id=${id}`;
      }
    };
  });
}
// ===== INICIALIZAÇÃO =====
document.addEventListener("DOMContentLoaded", function () {
  console.log("DOM carregado - inicializando sistema");

  // Esconder filtro inicialmente
  let formFiltro = document.getElementById("form-filtro");
  if (formFiltro) {
    formFiltro.style.display = "none";
  }

  // Adicionar evento ao botão pesquisar
  let btnPesquisar = document.getElementById("pesquisar");
  if (btnPesquisar) {
    btnPesquisar.addEventListener("click", aplicarFiltro);
  }

  // Inicializar botões de exclusão
  initDeleteButtons();

  // Inicializar botões de check
  initCheckButtons();

  console.log("Sistema inicializado com sucesso!");
});
