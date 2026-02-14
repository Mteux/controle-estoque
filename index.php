<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "../vendor/autoload.php";

// Inicia a sessão
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Pega a URL requisitada
$request = $_SERVER['REQUEST_URI'];

// Remove a barra inicial
$url = ltrim($request, '/');

// Se estiver vazio, vai para home
if (empty($url)) {
    $url = 'index';
}

echo "<!-- URL requisitada: $url -->";

// Roteamento manual
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
        $controller->exportar();
        break;
        
    case 'deletar-produto':
        $controller = new App\Controllers\AppController();
        $controller->deletarProduto();
        break;
        
    case 'sair':
        $controller = new App\Controllers\AuthController();
        $controller->sair();
        break;
        
    default:
        echo "<h1>Página não encontrada</h1>";
        echo "<p>URL: " . htmlspecialchars($url) . "</p>";
        break;
}
?>