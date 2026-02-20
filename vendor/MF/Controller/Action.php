<?php

namespace MF\Controller;

abstract class Action {

	protected $view;

	public function __construct() {
		$this->view = new \stdClass();
	}

	protected function initSession() {
		if (session_status() == PHP_SESSION_NONE) {
			session_start();
		}
	}

	protected function render($view, $layout = 'layout') {
		$this->view->page = $view;

		// CAMINHO ABSOLUTO CORRETO
		$basePath = dirname(dirname(dirname(__DIR__))); // C:\xampp\htdocs\controle-estoque
		
		// Verificar se o layout existe
		$layoutPath = $basePath . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR . $layout . '.phtml';
		
		if(file_exists($layoutPath)) {
			require_once $layoutPath;
		} else {
			$this->content();
		}
	}

	protected function content() {
		$classAtual = get_class($this);

		$classAtual = str_replace('App\\Controllers\\', '', $classAtual);

		$classAtual = strtolower(str_replace('Controller', '', $classAtual));

		// CAMINHO ABSOLUTO CORRETO
		$basePath = dirname(dirname(dirname(__DIR__))); // C:\xampp\htdocs\controle-estoque
		
		// Mapeamento de controllers para pastas
		$folderMap = [
			'index' => 'index',
			'app' => 'app',
			'auth' => 'index'
		];
		
		$folder = isset($folderMap[$classAtual]) ? $folderMap[$classAtual] : $classAtual;
		
		// Montar o caminho completo da view
		$viewPath = $basePath . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR . $folder . DIRECTORY_SEPARATOR . $this->view->page . '.phtml';
		
		if (file_exists($viewPath)) {
			require_once $viewPath;
		} else {
			echo "View não encontrada: " . $viewPath;
		}
	}
}
?>