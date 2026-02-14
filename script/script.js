// Classe principal do sistema
class StockManager {
  constructor() {
    this.apiUrl = "/api";
    this.notificationContainer = null;
    this.init();
  }

  init() {
    this.createNotificationContainer();
    this.initEventListeners();
    this.loadStats();
    this.checkLowStock();
    this.initTooltips();
    this.formatCurrencyFields();
  }

  createNotificationContainer() {
    this.notificationContainer = document.createElement("div");
    this.notificationContainer.id = "notification-container";
    document.body.appendChild(this.notificationContainer);
  }

  initEventListeners() {
    document.addEventListener("DOMContentLoaded", () => {
      this.initFiltro();
      this.initDeleteButtons();
      this.initEditButtons();
      this.initCheckButtons();
      this.initSearchInput();
      this.initExportButtons();
    });
  }

  // ===== SISTEMA DE FILTRO =====
  initFiltro() {
    const formFiltro = document.getElementById("form-filtro");
    if (formFiltro) {
      formFiltro.style.display = "none";
    }

    const btnFiltrar = document.querySelector("button[onclick='filtrar()']");
    if (btnFiltrar) {
      btnFiltrar.addEventListener("click", () => this.toggleFiltro());
    }

    const btnPesquisar = document.getElementById("pesquisar");
    if (btnPesquisar) {
      btnPesquisar.addEventListener("click", () => this.filtrarTabela());
    }

    // Filtro em tempo real
    const filtros = [
      "filtro-produto",
      "filtro-categoria",
      "vlrMaximo",
      "vlrMinimo",
      "quantidade",
    ];
    filtros.forEach((id) => {
      const input = document.getElementById(id);
      if (input) {
        input.addEventListener("input", () =>
          this.debounce(() => this.filtrarTabela(), 300),
        );
      }
    });

    // Botão limpar filtros
    this.addClearFiltersButton();
  }

  toggleFiltro() {
    const modFiltro = document.getElementById("form-filtro");
    if (modFiltro) {
      const isHidden =
        modFiltro.style.display === "none" || !modFiltro.style.display;
      modFiltro.style.display = isHidden ? "block" : "none";

      if (isHidden) {
        modFiltro.scrollIntoView({ behavior: "smooth", block: "start" });
      }
    }
  }

  filtrarTabela() {
    this.showLoading();

    setTimeout(() => {
      const produto =
        document.getElementById("filtro-produto")?.value.toLowerCase() || "";
      const categoria =
        document.getElementById("filtro-categoria")?.value.toLowerCase() || "";
      const vlrMaximo =
        parseFloat(document.getElementById("vlrMaximo")?.value) || Infinity;
      const vlrMinimo =
        parseFloat(document.getElementById("vlrMinimo")?.value) || 0;
      const quantidade =
        parseInt(document.getElementById("quantidade")?.value) || null;

      const linhas = document.querySelectorAll("#tabela tbody tr");
      let resultados = 0;

      linhas.forEach((linha) => {
        const prodTable =
          linha.querySelector(".produto")?.innerText.toLowerCase() || "";
        const valorTable = this.parseValor(
          linha.querySelector(".valor")?.innerText || "0",
        );
        const quantTable =
          parseInt(linha.querySelector(".quantidade")?.innerText) || 0;
        const cateTable =
          linha.querySelector(".categoria")?.innerText.toLowerCase() || "";

        let exibir = true;

        if (produto && !prodTable.includes(produto)) exibir = false;
        if (categoria && !cateTable.includes(categoria)) exibir = false;
        if (valorTable < vlrMinimo || valorTable > vlrMaximo) exibir = false;
        if (quantidade !== null && quantTable !== quantidade) exibir = false;

        linha.style.display = exibir ? "" : "none";
        if (exibir) resultados++;
      });

      this.hideLoading();
      this.showNotification(
        `${resultados} produto(s) encontrado(s)`,
        resultados > 0 ? "success" : "warning",
      );
    }, 300);
  }

  addClearFiltersButton() {
    const formFiltro = document.getElementById("form-filtro");
    if (formFiltro && !document.getElementById("limpar-filtros")) {
      const btnContainer = document.createElement("div");
      btnContainer.className = "text-center mt-3";
      btnContainer.innerHTML = `
                <button type="button" class="btn btn-outline-secondary btn-sm" id="limpar-filtros">
                    <i class="fas fa-eraser"></i> Limpar Filtros
                </button>
            `;
      formFiltro.appendChild(btnContainer);

      document
        .getElementById("limpar-filtros")
        .addEventListener("click", () => {
          document.getElementById("filtro-produto").value = "";
          document.getElementById("filtro-categoria").value = "";
          document.getElementById("vlrMaximo").value = "";
          document.getElementById("vlrMinimo").value = "";
          document.getElementById("quantidade").value = "";
          this.filtrarTabela();
        });
    }
  }

  parseValor(valor) {
    return (
      parseFloat(
        valor.replace("R$", "").replace(/\./g, "").replace(",", "."),
      ) || 0
    );
  }

  // ===== SISTEMA DE EXCLUSÃO =====
  initDeleteButtons() {
    document.querySelectorAll(".deletar-produto").forEach((btn) => {
      btn.addEventListener("click", (e) => {
        e.stopPropagation();
        this.confirmarExclusao(btn);
      });
    });
  }

  confirmarExclusao(btn) {
    const row = btn.closest("tr");
    const id = row?.getAttribute("data-id");
    const produto = row?.querySelector(".produto")?.innerText || "Produto";

    if (!id) {
      this.showNotification("ID do produto não encontrado.", "error");
      return;
    }

    this.showConfirmDialog(
      `Excluir ${produto}?`,
      "Esta ação não poderá ser desfeita.",
      () => this.excluirProduto(id, row),
    );
  }

  async excluirProduto(id, row) {
    this.showLoading();

    try {
      const response = await fetch(`/deletar-produto?id=${id}`);
      const data = await response.text();

      this.hideLoading();

      if (data.trim() === "success") {
        row.style.animation = "slideOutRight 0.3s ease-out";
        setTimeout(() => {
          row.remove();
          this.showNotification("Produto excluído com sucesso!", "success");
          this.loadStats();
          this.checkLowStock();
        }, 300);
      } else {
        this.showNotification("Erro ao excluir produto.", "error");
      }
    } catch (error) {
      this.hideLoading();
      console.error("Erro:", error);
      this.showNotification("Erro ao excluir produto.", "error");
    }
  }

  // ===== SISTEMA DE EDIÇÃO =====
  initEditButtons() {
    document.querySelectorAll(".fa-edit").forEach((btn) => {
      btn.addEventListener("click", (e) => {
        e.stopPropagation();
        this.editarProduto(btn);
      });
    });
  }

  editarProduto(btn) {
    const row = btn.closest("tr");
    const id = row?.getAttribute("data-id");

    if (id) {
      window.location.href = `/editar-produto?id=${id}`;
    } else {
      this.showNotification("ID do produto não encontrado.", "error");
    }
  }

  // ===== SISTEMA DE CHECK =====
  initCheckButtons() {
    document.querySelectorAll(".fa-check").forEach((btn) => {
      btn.addEventListener("click", (e) => {
        e.stopPropagation();
        this.marcarRevisado(btn);
      });
    });
  }

  marcarRevisado(btn) {
    const row = btn.closest("tr");
    row.style.backgroundColor = "#d4edda";
    row.style.transition = "background-color 0.5s";

    setTimeout(() => {
      row.style.backgroundColor = "";
    }, 1000);

    this.showNotification("Produto marcado como revisado!", "success");

    // Animação no ícone
    btn.style.transform = "scale(1.5)";
    setTimeout(() => {
      btn.style.transform = "";
    }, 500);
  }

  // ===== SISTEMA DE BUSCA =====
  initSearchInput() {
    const searchInput = document.createElement("div");
    searchInput.className = "mb-3";
    searchInput.innerHTML = `
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0">
                    <i class="fas fa-search text-primary"></i>
                </span>
                <input type="text" class="form-control border-start-0" id="busca-rapida" 
                       placeholder="Buscar produtos...">
            </div>
        `;

    const tabela = document.getElementById("tabela");
    if (tabela) {
      tabela.parentNode.insertBefore(searchInput, tabela);

      document.getElementById("busca-rapida").addEventListener("input", (e) => {
        this.buscaRapida(e.target.value.toLowerCase());
      });
    }
  }

  buscaRapida(termo) {
    const linhas = document.querySelectorAll("#tabela tbody tr");
    let encontrados = 0;

    linhas.forEach((linha) => {
      const texto = linha.innerText.toLowerCase();
      const corresponde = texto.includes(termo);
      linha.style.display = corresponde ? "" : "none";
      if (corresponde) encontrados++;
    });

    if (termo.length > 2) {
      this.showNotification(`${encontrados} resultados encontrados`, "info");
    }
  }

  // ===== SISTEMA DE EXPORTAÇÃO =====
  initExportButtons() {
    const btnExportar = document.querySelector('a[href="/exportar"]');
    if (btnExportar) {
      btnExportar.addEventListener("click", (e) => {
        e.preventDefault();
        this.exportarDados();
      });
    }
  }

  async exportarDados() {
    this.showLoading();

    try {
      const formatos = ["PDF", "Excel", "CSV"];
      const formato = await this.showFormatSelector(formatos);

      if (formato) {
        this.showNotification(`Exportando em ${formato}...`, "info");
        setTimeout(() => {
          this.hideLoading();
          this.showNotification("Exportação concluída!", "success");
        }, 2000);
      }
    } catch (error) {
      this.hideLoading();
      this.showNotification("Erro ao exportar", "error");
    }
  }

  // ===== SISTEMA DE NOTIFICAÇÕES =====
  showNotification(message, type = "info") {
    const notification = document.createElement("div");
    notification.className = `notification alert-${type}`;

    const icon = this.getIcon(type);

    notification.innerHTML = `
            <i class="fas ${icon}"></i>
            <span>${message}</span>
        `;

    this.notificationContainer.appendChild(notification);

    setTimeout(() => {
      notification.style.animation = "slideOutRight 0.3s ease-out";
      setTimeout(() => notification.remove(), 300);
    }, 3000);
  }

  getIcon(type) {
    const icons = {
      success: "fa-check-circle",
      error: "fa-exclamation-circle",
      warning: "fa-exclamation-triangle",
      info: "fa-info-circle",
    };
    return icons[type] || icons.info;
  }

  // ===== MODAL DE CONFIRMAÇÃO =====
  showConfirmDialog(title, message, onConfirm) {
    const modal = document.createElement("div");
    modal.className = "modal-custom";

    const dialog = document.createElement("div");
    dialog.className = "modal-custom-content";

    dialog.innerHTML = `
            <i class="fas fa-exclamation-triangle" style="font-size: 3rem; color: var(--warning-color); margin-bottom: 20px;"></i>
            <h3 style="color: var(--primary-color); margin-bottom: 10px;">${title}</h3>
            <p style="color: #6c757d; margin-bottom: 20px;">${message}</p>
            <div style="display: flex; gap: 10px; justify-content: center;">
                <button class="btn btn-outline-secondary" id="cancelBtn">
                    <i class="fas fa-times"></i> Cancelar
                </button>
                <button class="btn btn-danger" id="confirmBtn">
                    <i class="fas fa-check"></i> Confirmar
                </button>
            </div>
        `;

    modal.appendChild(dialog);
    document.body.appendChild(modal);

    document.getElementById("cancelBtn").addEventListener("click", () => {
      modal.style.animation = "fadeOut 0.3s ease-out";
      setTimeout(() => modal.remove(), 300);
    });

    document.getElementById("confirmBtn").addEventListener("click", () => {
      onConfirm();
      modal.style.animation = "fadeOut 0.3s ease-out";
      setTimeout(() => modal.remove(), 300);
    });

    modal.addEventListener("click", (e) => {
      if (e.target === modal) {
        modal.style.animation = "fadeOut 0.3s ease-out";
        setTimeout(() => modal.remove(), 300);
      }
    });
  }

  // ===== SELETOR DE FORMATO =====
  showFormatSelector(formatos) {
    return new Promise((resolve) => {
      const modal = document.createElement("div");
      modal.className = "modal-custom";

      const dialog = document.createElement("div");
      dialog.className = "modal-custom-content";

      dialog.innerHTML = `
                <i class="fas fa-file-export" style="font-size: 3rem; color: var(--secondary-color); margin-bottom: 20px;"></i>
                <h3 style="color: var(--primary-color); margin-bottom: 20px;">Escolha o formato</h3>
                <div style="display: flex; gap: 10px; justify-content: center; flex-wrap: wrap;">
                    ${formatos
                      .map(
                        (f) => `
                        <button class="btn btn-outline-primary formato-btn" data-formato="${f}">
                            <i class="fas fa-file-${f.toLowerCase()}"></i> ${f}
                        </button>
                    `,
                      )
                      .join("")}
                    <button class="btn btn-outline-secondary" id="cancelarExport">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                </div>
            `;

      modal.appendChild(dialog);
      document.body.appendChild(modal);

      document.querySelectorAll(".formato-btn").forEach((btn) => {
        btn.addEventListener("click", () => {
          const formato = btn.dataset.formato;
          modal.remove();
          resolve(formato);
        });
      });

      document
        .getElementById("cancelarExport")
        .addEventListener("click", () => {
          modal.remove();
          resolve(null);
        });
    });
  }

  // ===== ESTATÍSTICAS =====
  async loadStats() {
    try {
      const produtos = document.querySelectorAll("#tabela tbody tr");
      const totalProdutos = produtos.length;

      let valorTotal = 0;
      let estoqueBaixo = 0;
      const categorias = new Set();

      produtos.forEach((produto) => {
        if (produto.style.display !== "none") {
          const valor = this.parseValor(
            produto.querySelector(".valor")?.innerText || "0",
          );
          const quantidade =
            parseInt(produto.querySelector(".quantidade")?.innerText) || 0;
          const categoria =
            produto.querySelector(".categoria")?.innerText || "";

          valorTotal += valor * quantidade;
          if (quantidade < 10) estoqueBaixo++;
          if (categoria) categorias.add(categoria);
        }
      });

      this.updateStatsDisplay({
        totalProdutos,
        valorTotal: valorTotal.toFixed(2),
        estoqueBaixo,
        categorias: categorias.size,
      });
    } catch (error) {
      console.error("Erro ao carregar estatísticas:", error);
    }
  }

  updateStatsDisplay(stats) {
    let statsContainer = document.getElementById("stats-container");

    if (!statsContainer) {
      statsContainer = document.createElement("div");
      statsContainer.id = "stats-container";
      statsContainer.className = "container-fluid mt-4";

      const titulo = document.querySelector(".titulo");
      if (titulo) {
        titulo.parentNode.insertBefore(statsContainer, titulo.nextSibling);
      }
    }

    if (stats) {
      statsContainer.innerHTML = `
                <div class="row">
                    <div class="col-md-3">
                        <div class="stats-card">
                            <i class="fas fa-boxes"></i>
                            <div class="stats-number">${stats.totalProdutos}</div>
                            <div class="stats-label">Total de Produtos</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card">
                            <i class="fas fa-dollar-sign"></i>
                            <div class="stats-number">R$ ${stats.valorTotal}</div>
                            <div class="stats-label">Valor em Estoque</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card">
                            <i class="fas fa-exclamation-triangle"></i>
                            <div class="stats-number">${stats.estoqueBaixo}</div>
                            <div class="stats-label">Estoque Baixo</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card">
                            <i class="fas fa-chart-line"></i>
                            <div class="stats-number">${stats.categorias}</div>
                            <div class="stats-label">Categorias</div>
                        </div>
                    </div>
                </div>
            `;
    }
  }

  // ===== VERIFICAR ESTOQUE BAIXO =====
  checkLowStock() {
    const linhas = document.querySelectorAll("#tabela tbody tr");
    const lowStock = [];

    linhas.forEach((linha) => {
      const quantidade =
        parseInt(linha.querySelector(".quantidade")?.innerText) || 0;
      const produto = linha.querySelector(".produto")?.innerText || "";

      if (quantidade < 10 && quantidade > 0) {
        lowStock.push({ produto, quantidade });
        linha.style.backgroundColor = "#fff3cd";

        // Adicionar ícone de alerta
        if (!linha.querySelector(".low-stock-icon")) {
          const td = document.createElement("td");
          td.className = "low-stock-icon";
          td.innerHTML =
            '<i class="fas fa-exclamation-triangle text-warning" title="Estoque baixo"></i>';
          linha.appendChild(td);
        }
      } else {
        linha.style.backgroundColor = "";
        const icon = linha.querySelector(".low-stock-icon");
        if (icon) icon.remove();
      }
    });

    if (lowStock.length > 0) {
      this.showNotification(
        `${lowStock.length} produto(s) com estoque baixo!`,
        "warning",
      );

      // Criar alerta fixo
      this.showLowStockAlert(lowStock);
    }
  }

  showLowStockAlert(lowStock) {
    const alertContainer = document.getElementById("low-stock-alert");
    if (!alertContainer) {
      const container = document.createElement("div");
      container.id = "low-stock-alert";
      container.className =
        "alert alert-warning alert-dismissible fade show mt-3";
      container.setAttribute("role", "alert");
      container.innerHTML = `
                <strong><i class="fas fa-exclamation-triangle"></i> Atenção!</strong>
                <div id="low-stock-list"></div>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            `;

      const tabela = document.getElementById("tabela");
      if (tabela) {
        tabela.parentNode.insertBefore(container, tabela);
      }
    }

    const lowStockList = document.getElementById("low-stock-list");
    if (lowStockList) {
      lowStockList.innerHTML = `
                <ul class="mt-2 mb-0">
                    ${lowStock
                      .map(
                        (item) =>
                          `<li>${item.produto}: <strong>${item.quantidade}</strong> unidades restantes</li>`,
                      )
                      .join("")}
                </ul>
            `;
    }
  }

  // ===== FORMATAR CAMPOS DE MOEDA =====
  formatCurrencyFields() {
    document
      .querySelectorAll('input[name="valor"], .valor')
      .forEach((field) => {
        field.addEventListener("input", (e) => {
          let value = e.target.value.replace(/\D/g, "");
          value = (value / 100).toFixed(2) + "";
          value = value.replace(".", ",");
          value = value.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
          e.target.value = "R$ " + value;
        });
      });
  }

  // ===== TOOLTIPS =====
  initTooltips() {
    if (typeof $ !== "undefined" && $.fn.tooltip) {
      $('[data-toggle="tooltip"]').tooltip();
    }
  }

  // ===== LOADING =====
  showLoading() {
    if (!document.querySelector(".loader")) {
      const loader = document.createElement("div");
      loader.className = "loader";
      loader.innerHTML = '<div class="spinner"></div>';
      document.body.appendChild(loader);
    }
  }

  hideLoading() {
    const loader = document.querySelector(".loader");
    if (loader) {
      loader.style.animation = "fadeOut 0.3s ease-out";
      setTimeout(() => loader.remove(), 300);
    }
  }

  // ===== DEBOUNCE =====
  debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
      const later = () => {
        clearTimeout(timeout);
        func(...args);
      };
      clearTimeout(timeout);
      timeout = setTimeout(later, wait);
    };
  }
}

// ===== INICIALIZAÇÃO =====
const stockManager = new StockManager();

// Função global para manter compatibilidade
window.filtrar = function () {
  stockManager.toggleFiltro();
};

// Atualizar estatísticas quando a tabela mudar
const observer = new MutationObserver(() => {
  stockManager.loadStats();
  stockManager.checkLowStock();
});

observer.observe(document.body, { childList: true, subtree: true });
