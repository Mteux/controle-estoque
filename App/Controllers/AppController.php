<?php

namespace App\Controllers;

use MF\Controller\Action;
use MF\Model\Container;
use Mailjet\Resources;

class AppController extends Action
{
    public function dashboard()
    {
        $this->initSession();

        if (!empty($_SESSION['id']) && !empty($_SESSION['nome'])) {
            // Recuperar os produtos
            $produto = Container::getModel('Produto');
            $produto->__set('id_usuario', $_SESSION['id']);
            $this->view->produtos = $produto->getAll();

            $this->render('dashboard');
        } else {
            header('Location: /?login=erro');
        }
    }

    public function adicionar()
    {
        $this->initSession();

        if (!empty($_SESSION['id']) && !empty($_SESSION['nome'])) {
            $this->render('adicionar');
        } else {
            header('Location: /?login=erro');
        }
    }

    public function addProduto()
    {
        $this->initSession();

        if (!empty($_SESSION['id']) && !empty($_SESSION['nome'])) {

            // Tratamento do valor em uma linha só
            $valor = $_POST['valor'];
            $valor = str_replace(['R$', ' ', '.'], '', $valor); // Remove R$, espaços e pontos
            $valor = str_replace(',', '.', $valor); // Troca vírgula por ponto
            $valor = (float) $valor; // Converte para float
            $produto = Container::getModel('Produto');

            $produto->__set('id_usuario', $_SESSION['id']);
            $produto->__set('produto', $_POST['produto']);
            $produto->__set('valor', $valor);
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
        $this->initSession();

        if (!empty($_SESSION['id']) && !empty($_SESSION['nome'])) {
            $this->render('feedback');
        } else {
            header('Location: /?login=erro');
        }
    }

    public function enviar()
    {
        $this->initSession();

        if (!empty($_SESSION['id']) && !empty($_SESSION['nome'])) {
            // Configuração do Mailjet
            $mj = new \Mailjet\Client(
                '887e579677d578c12777f0b91794c80a',
                'c88cd3a39d3528411128b68a93d8fe0e',
                true,
                ['version' => 'v3.1']
            );

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
                        'HTMLPart' => nl2br($_POST['sugestao'])
                    ]
                ]
            ];

            $response = $mj->post(Resources::$Email, ['body' => $body]);

            if ($response->success()) {
                header('Location: /feedback?sucesso=1');
            } else {
                echo 'Erro ao enviar o e-mail: ' . $response->getReasonPhrase();
            }
        } else {
            header('Location: /?login=erro');
            exit();
        }
    }

    public function exportar()
    {
        $this->initSession();

        if (!empty($_SESSION['id']) && !empty($_SESSION['nome'])) {
            $produto = Container::getModel('Produto');
            $produto->__set('id_usuario', $_SESSION['id']);
            $produtos = $produto->getAll();

            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="produtos.xls"');
            header('Cache-Control: max-age=0');

            echo "<table border='1'>";
            echo "<tr>
                    <th>Produto</th>
                    <th>Valor</th>
                    <th>Quantidade</th>
                    <th>Categoria</th>
                    <th>Descrição</th>
                </tr>";

            foreach ($produtos as $produto) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($produto['produto']) . "</td>";
                echo "<td>R$ " . number_format($produto['valor'], 2, ',', '.') . "</td>";
                echo "<td>" . $produto['quantidade'] . "</td>";
                echo "<td>" . htmlspecialchars($produto['categoria']) . "</td>";
                echo "<td>" . htmlspecialchars($produto['descricao']) . "</td>";
                echo "</tr>";
            }

            echo "</table>";
            exit;
        } else {
            header('Location: /?login=erro');
            exit();
        }
    }

    
    public function deletarProduto()
    {
        $this->initSession();

        // Verifica se está logado
        if (empty($_SESSION['id'])) {
            header('Location: /?login=erro');
            return;
        }
        
        // Verifica se tem ID na URL
        if (empty($_GET['id'])) {
            header('Location: /dashboard');
            return;
        }
        
        $id = $_GET['id'];
        $id_usuario = $_SESSION['id'];
        
        try {
            $produto = Container::getModel('Produto');
            $produto->__set('id', $id);
            $produto->__set('id_usuario', $id_usuario);
            
            if ($produto->deletar()) {
                // Sucesso
                header('Location: /dashboard?excluido=1');
            } else {
                // Erro
                header('Location: /dashboard?erro=1');
            }
        } catch (Exception $e) {
            header('Location: /dashboard?erro=1');
        }
    }

    public function editarProduto()
    {
        $this->initSession();

        // Verifica se está logado
        if (empty($_SESSION['id'])) {
            header('Location: /?login=erro');
            return;
        }
        
        // Verifica se tem ID na URL
        if (empty($_GET['id'])) {
            header('Location: /dashboard');
            return;
        }
        
        $id = $_GET['id'];
        $id_usuario = $_SESSION['id'];
        
        // Busca o produto no banco
        $produto = Container::getModel('Produto');
        $produto->__set('id', $id);
        $produto->__set('id_usuario', $id_usuario);
        
        // Você precisa criar este método no Model
        $dados = $produto->getPorId();
        
        if ($dados) {
            $this->view->produto = $dados;
            $this->render('editar', 'layout'); // Vamos criar a view 'editar.phtml'
        } else {
            header('Location: /dashboard?erro=1');
        }
    }

    /**
     * Processa o formulário de edição e atualiza o produto
     */
    public function atualizarProduto()
    {
        $this->initSession();

        // Verifica se está logado
        if (empty($_SESSION['id'])) {
            header('Location: /?login=erro');
            return;
        }
        
        // Verifica se os dados foram enviados
        if (empty($_POST['id'])) {
            header('Location: /dashboard');
            return;
        }
        
        // Trata o valor (igual ao addProduto)
        $valor = $_POST['valor'];
        $valor = str_replace(['R$', ' ', '.'], '', $valor);
        $valor = str_replace(',', '.', $valor);
        $valor = (float) $valor;
        
        // Atualiza o produto
        $produto = Container::getModel('Produto');
        $produto->__set('id', $_POST['id']);
        $produto->__set('id_usuario', $_SESSION['id']);
        $produto->__set('produto', $_POST['produto']);
        $produto->__set('valor', $valor);
        $produto->__set('categoria', $_POST['categoria']);
        $produto->__set('quantidade', $_POST['quantidade']);
        $produto->__set('descricao', $_POST['descricao']);
        
        if ($produto->atualizar()) {
            header('Location: /dashboard?atualizado=1');
        } else {
            header('Location: /editar-produto?id=' . $_POST['id'] . '&erro=1');
        }
    }
}