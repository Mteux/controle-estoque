<?php

namespace App\Controllers;

use MF\Controller\Action;
use MF\Model\Container;

class AppController extends Action
{

    public function dashboard()
    {
        session_start();

        if ($_SESSION['id'] != '' && $_SESSION['nome'] != '') {

            //recuperar os produtos

            $produto = Container::getModel('Produto');

            $produto->__set('id_usuario', $_SESSION['id']);

            $produtos = $produto->getAll();



            $this->view->produtos = $produtos;

            $this->render('dashboard');
        } else {
            header('Location: /?login=erro');
        }
    }

    public function adicionar()
    {

        session_start();

        if ($_SESSION['id'] != '' && $_SESSION['nome'] != '') {

            $this->render('adicionar');
        } else {
            header('Location: /?login=erro');
        }
    }

    public function addProduto()
    {
        session_start();

        if ($_SESSION['id'] != '' && $_SESSION['nome'] != '') {

            $produto = Container::getModel('Produto');

            $produto->__set('id_usuario', $_SESSION['id']);
            $produto->__set('produto', $_POST['produto']);
            $produto->__set('valor', $_POST['valor']);
            $produto->__set('categoria', $_POST['categoria']);
            $produto->__set('quantidade', $_POST['quantidade']);
            $produto->__set('descricao', $_POST['descricao']);

            $produto->salvar();
            header('Location: /adicionar');
        } else {
            header('Location: /?login=erro');
        }
    }
}
