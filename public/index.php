<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "../vendor/autoload.php";

// Inicia a sessão
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Pega a URL requisitada COMPLETA
$request = $_SERVER['REQUEST_URI'];
$base = '/';

// Remove o caminho base se necessário
if (strpos($request, '/controle-estoque/public/') === 0) {
    $url = substr($request, strlen('/controle-estoque/public/'));
} else {
    $url = ltrim($request, '/');
}

// Separa a URL dos parâmetros GET
$partes = explode('?', $url);
$url = $partes[0]; // Pega só a parte antes do ?
$url = trim($url, '/');

// Se estiver vazio, vai para home
if (empty($url)) {
    $url = 'index';
}

// Roteamento - AGORA COM A ROTA CORRETA
switch ($url) {
    case 'index':
        $controller = new App\Controllers\IndexController();
        $controller->index();
        break;
        
    case 'inscreverse':
        $controller = new App\Controllers\IndexController();
        $controller->inscreverse();
        break;
        
    case 'registrar':
        $controller = new App\Controllers\IndexController();
        $controller->registrar();
        break;
        
    case 'autenticar':
        $controller = new App\Controllers\AuthController();
        $controller->autenticar();
        break;
        
    case 'dashboard':
        $controller = new App\Controllers\AppController();
        $controller->dashboard();
        break;
        
    case 'adicionar':
        $controller = new App\Controllers\AppController();
        $controller->adicionar();
        break;
        
    case 'addProduto':
        $controller = new App\Controllers\AppController();
        $controller->addProduto();
        break;
        
    case 'feedback':
        $controller = new App\Controllers\AppController();
        $controller->feedback();
        break;
        
    case 'enviar':
        $controller = new App\Controllers\AppController();
        $controller->enviar();
        break;
        
    case 'exportar':
        $controller = new App\Controllers\AppController();
        $controller->exportarRelatorioExcel();
        break;
        
    // CORREÇÃO AQUI - ROTA DELETAR PRODUTO
    case 'deletar-produto':
        $controller = new App\Controllers\AppController();
        $controller->deletarProduto();
        break;
        
    case 'sair':
        $controller = new App\Controllers\AuthController();
        $controller->sair();
        break;

    case 'editar-produto':
        $controller = new App\Controllers\AppController();
        $controller->editarProduto();
        break;
            
    case 'atualizar-produto':
        $controller = new App\Controllers\AppController();
        $controller->atualizarProduto();
        break;

    case 'relatorios':
        $controller = new App\Controllers\AppController();
        $controller->relatorios();
        break;

        case 'exportar-relatorio-csv':
            $controller = new App\Controllers\AppController();
            $controller->exportarRelatorioCSV();
            break;
            
        case 'exportar-relatorio-excel':
            $controller = new App\Controllers\AppController();
            $controller->exportarRelatorioExcel();
            break;
            
        case 'exportar-relatorio-pdf':
            $controller = new App\Controllers\AppController();
            $controller->exportarRelatorioPDF();
            break;
        
    default:
        header("HTTP/1.0 404 Not Found");
        echo "<h1>Página não encontrada</h1>";
        echo "<p>URL: " . htmlspecialchars($url) . "</p>";
        echo "<p>URL completa: " . htmlspecialchars($_SERVER['REQUEST_URI']) . "</p>";
        break;
}
?>