<?php


namespace App\Controllers;

use MF\Controller\Action;
use MF\Model\Container;

require '../vendor/autoload.php';

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


            $mj = new \Mailjet\Client('887e579677d578c12777f0b91794c80a', 'c88cd3a39d3528411128b68a93d8fe0e', true, ['version' => 'v3.1']);

            $body = [
                'Messages' => [
                    [
                        'From' => [
                            'Email' => "imateos1798@gmail.com",
                            'Name' => $_SESSION['nome']
                        ],
                        'To' => [
                            [
                                'Email' => "smateus2605@gmail.com",
                                'Name' => "Mateus"
                            ]
                        ],
                        'Subject' => $_POST['titulo'],
                        'TextPart' => $_POST['sugestao'],
                        'HTMLPart' => $_POST['sugestao']
                    ]
                ]
            ];

            // All resources are located in the Resources class

            $response = $mj->post(Resources::$Email, ['body' => $body]);

            // Read the response

            if ($response->success()) {
                echo 'E-mail enviado com sucesso!';  // Feedback simples para sucesso
            } else {
                echo 'Erro ao enviar o e-mail: ' . $response->getReasonPhrase();  // Feedback em caso de erro
            }
        } else {
            // Redireciona para página de login em caso de falha de sessão
            header('Location: /?login=erro');
            exit();
        }
    }
}
