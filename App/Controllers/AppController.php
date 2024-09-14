<?php


namespace App\Controllers;

use MF\Controller\Action;
use MF\Model\Container;

require 'vendor/autoload.php';

use \Mailjet\Resources;


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

    public function feedback()
    {
        session_start();
        if ($_SESSION['id'] != '' && $_SESSION['nome'] != '') {



            $this->render('feedback');
        } else {
            header('Location: /?login=erro');
        }
    }

    public function enviar()
    {
        session_start();
        if ($_SESSION['id'] != '' && $_SESSION['nome'] != '') {


            $mj = new \Mailjet\Client(getenv('50b5bb65b705a2b1c3bb3a9bef3bf1ff'), getenv('2b76ab3cd1fd4519700c3d4d9146d514'), true, ['version' => 'v3.1']);

            $body = [
                'Messages' => [
                    [
                        'From' => [
                            'Email' => "imateos1798@gmail.com",
                            'Name' => "this.usuario['name']"
                        ],
                        'To' => [
                            [
                                'Email' => "smateus2605@gmail.com",
                                'Name' => "Mateus"
                            ]
                        ],
                        'Subject' => "My first Mailjet Email!",
                        'TextPart' => "Greetings from Mailjet!",
                        'HTMLPart' => "<h3>Dear passenger 1, welcome to <a href=\"https://www.mailjet.com/\">Mailjet</a>!</h3>
                        <br />May the delivery force be with you!"
                    ]
                ]
            ];
        } else {
            header('Location: /?login=erro');
        }
    }
}
