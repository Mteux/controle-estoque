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

    public function exportarRelatorioExcel()
    {
        $this->initSession();

        if (empty($_SESSION['id'])) {
            header('Location: /?login=erro');
            return;
        }
        
        $id_usuario = $_SESSION['id'];
        $produto = Container::getModel('Produto');
        $produto->__set('id_usuario', $id_usuario);
        
        $produtos = $produto->getAll();
        
        $filename = 'relatorio_estoque_' . date('Y-m-d') . '.xls';
        
        header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        // BOM para UTF-8
        echo "\xEF\xBB\xBF";
        
        echo '<html>';
        echo '<head><meta charset="UTF-8"></head>';
        echo '<body>';
        echo '<table border="1">';
        echo '<tr><th colspan="6" style="font-size:16px;">RELATÓRIO DE ESTOQUE</th></tr>';
        echo '<tr><td colspan="6">Data: ' . date('d/m/Y H:i:s') . '</td></tr>';
        echo '<tr><th>Produto</th><th>Categoria</th><th>Quantidade</th><th>Valor Unit.</th><th>Valor Total</th><th>Status</th></tr>';
        
        $valorTotalGeral = 0;
        $totalQuantidade = 0;
        
        foreach ($produtos as $p) {
            $valorTotal = $p['valor'] * $p['quantidade'];
            $valorTotalGeral += $valorTotal;
            $totalQuantidade += $p['quantidade'];
            $status = $p['quantidade'] < 10 ? 'ESTOQUE BAIXO' : 'OK';
            
            echo '<tr>';
            echo '<td>' . htmlspecialchars($p['produto']) . '</td>';
            echo '<td>' . $p['categoria'] . '</td>';
            echo '<td align="center">' . $p['quantidade'] . '</td>';
            echo '<td align="right">R$ ' . number_format($p['valor'], 2, ',', '.') . '</td>';
            echo '<td align="right">R$ ' . number_format($valorTotal, 2, ',', '.') . '</td>';
            echo '<td>' . $status . '</td>';
            echo '</tr>';
        }
        
        echo '<tr style="font-weight:bold; background-color:#ecf0f1;">';
        echo '<td colspan="2">TOTAL GERAL:</td>';
        echo '<td align="center">' . $totalQuantidade . '</td>';
        echo '<td></td>';
        echo '<td align="right">R$ ' . number_format($valorTotalGeral, 2, ',', '.') . '</td>';
        echo '<td></td>';
        echo '</tr>';
        
        echo '</table>';
        echo '</body></html>';
        exit;
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

    /**
         * Exibe a página de relatórios
         */
        /**
 * Exibe a página de relatórios com estatísticas do estoque
 */
    public function relatorios()
    {
        $this->initSession();

        if (empty($_SESSION['id'])) {
            header('Location: /?login=erro');
            return;
        }
        
        $id_usuario = $_SESSION['id'];
        $produto = Container::getModel('Produto');
        $produto->__set('id_usuario', $id_usuario);
        
        // Busca todos os produtos do usuário
        $this->view->produtos = $produto->getAll();
        
        // Inicializa variáveis de estatísticas
        $totalProdutos = count($this->view->produtos);
        $valorTotal = 0;
        $totalQuantidade = 0;
        $estoqueBaixo = 0;
        $categorias = [];
        $valores = [];
        
        // Calcula estatísticas
        foreach ($this->view->produtos as $p) {
            $valorTotal += $p['valor'] * $p['quantidade'];
            $totalQuantidade += $p['quantidade'];
            $valores[] = $p['valor'];
            
            // Conta produtos com estoque baixo (< 10 unidades)
            if ($p['quantidade'] < 10) {
                $estoqueBaixo++;
            }
            
            // Agrupa por categoria
            $cat = $p['categoria'];
            if (!isset($categorias[$cat])) {
                $categorias[$cat] = 0;
            }
            $categorias[$cat]++;
        }
        
        // Define valores padrão se não houver produtos
        $produtoMaisCaro = !empty($valores) ? max($valores) : 0;
        $produtoMaisBarato = !empty($valores) ? min($valores) : 0;
        
        // Prepara array com todas as estatísticas
        $this->view->stats = [
            'total_produtos' => $totalProdutos,
            'total_quantidade' => $totalQuantidade,
            'valor_total' => $valorTotal,
            'estoque_baixo' => $estoqueBaixo,
            'categorias' => $categorias,
            'produto_mais_caro' => $produtoMaisCaro,
            'produto_mais_barato' => $produtoMaisBarato
        ];
        
        // Renderiza a view de relatórios
        $this->render('relatorios');
    }

    /**
     * Exporta relatório em formato CSV
     */
    public function exportarRelatorioCSV()
    {
        $this->initSession();

        if (empty($_SESSION['id'])) {
            header('Location: /?login=erro');
            return;
        }
        
        $id_usuario = $_SESSION['id'];
        $produto = Container::getModel('Produto');
        $produto->__set('id_usuario', $id_usuario);
        
        // Busca todos os produtos
        $produtos = $produto->getAll();
        
        // Calcula estatísticas
        $valorTotal = 0;
        $totalQuantidade = 0;
        $estoqueBaixo = 0;
        
        foreach ($produtos as $p) {
            $valorTotal += $p['valor'] * $p['quantidade'];
            $totalQuantidade += $p['quantidade'];
            if ($p['quantidade'] < 10) {
                $estoqueBaixo++;
            }
        }
        
        // Nome do arquivo com data
        $filename = 'relatorio_estoque_' . date('Y-m-d_H-i-s') . '.csv';
        
        // Headers para download
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        // Cria o arquivo CSV
        $output = fopen('php://output', 'w');
        
        // BOM para UTF-8 (resolve acentos no Excel)
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // ===== CABEÇALHO DO RELATÓRIO =====
        fputcsv($output, ['RELATÓRIO DE ESTOQUE', date('d/m/Y H:i:s')]);
        fputcsv($output, []); // Linha em branco
        
        // Estatísticas Gerais
        fputcsv($output, ['ESTATÍSTICAS GERAIS']);
        fputcsv($output, ['Total de Produtos:', count($produtos)]);
        fputcsv($output, ['Total de Unidades:', $totalQuantidade]);
        fputcsv($output, ['Valor Total em Estoque:', 'R$ ' . number_format($valorTotal, 2, ',', '.')]);
        fputcsv($output, ['Produtos com Estoque Baixo:', $estoqueBaixo]);
        fputcsv($output, []); // Linha em branco
        
        // Cabeçalho da tabela de produtos
        fputcsv($output, ['LISTA DE PRODUTOS']);
        fputcsv($output, [
            'ID',
            'Produto',
            'Categoria',
            'Quantidade',
            'Valor Unitário',
            'Valor Total',
            'Status'
        ]);
        
        // Dados dos produtos
        foreach ($produtos as $p) {
            $valorUnitario = 'R$ ' . number_format($p['valor'], 2, ',', '.');
            $valorTotalProduto = 'R$ ' . number_format($p['valor'] * $p['quantidade'], 2, ',', '.');
            $status = $p['quantidade'] < 10 ? 'ESTOQUE BAIXO' : 'OK';
            
            fputcsv($output, [
                $p['id'],
                $p['produto'],
                $p['categoria'],
                $p['quantidade'],
                $valorUnitario,
                $valorTotalProduto,
                $status
            ]);
        }
        
        // Linha de total
        fputcsv($output, []); // Linha em branco
        fputcsv($output, [
            'TOTAL GERAL:',
            '',
            '',
            $totalQuantidade,
            '',
            'R$ ' . number_format($valorTotal, 2, ',', '.'),
            ''
        ]);
        
        fclose($output);
        exit;
    }

    /**
     * Exporta relatório em formato PDF
     */
    public function exportarRelatorioPDF()
    {
        $this->initSession();

        if (empty($_SESSION['id'])) {
            header('Location: /?login=erro');
            return;
        }
        
        $id_usuario = $_SESSION['id'];
        $produto = Container::getModel('Produto');
        $produto->__set('id_usuario', $id_usuario);
        
        // Busca todos os produtos
        $produtos = $produto->getAll();
        
        // Calcula estatísticas
        $valorTotal = 0;
        $totalQuantidade = 0;
        $estoqueBaixo = 0;
        
        foreach ($produtos as $p) {
            $valorTotal += $p['valor'] * $p['quantidade'];
            $totalQuantidade += $p['quantidade'];
            if ($p['quantidade'] < 10) {
                $estoqueBaixo++;
            }
        }
        
        // HTML do relatório
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Relatório de Estoque</title>
            <style>
                body { font-family: Arial, sans-serif; }
                h1 { color: #2c3e50; text-align: center; }
                h2 { color: #34495e; border-bottom: 2px solid #3498db; padding-bottom: 5px; }
                table { width: 100%; border-collapse: collapse; margin: 20px 0; }
                th { background-color: #2c3e50; color: white; padding: 10px; }
                td { border: 1px solid #ddd; padding: 8px; }
                .total { background-color: #ecf0f1; font-weight: bold; }
                .baixo { color: #e74c3c; font-weight: bold; }
            </style>
        </head>
        <body>
            <h1>RELATÓRIO DE ESTOQUE</h1>
            <p>Data: ' . date('d/m/Y H:i:s') . '</p>
            
            <h2>ESTATÍSTICAS GERAIS</h2>
            <table>
                <tr><td><strong>Total de Produtos:</strong></td><td>' . count($produtos) . '</td></tr>
                <tr><td><strong>Total de Unidades:</strong></td><td>' . $totalQuantidade . '</td></tr>
                <tr><td><strong>Valor Total em Estoque:</strong></td><td>R$ ' . number_format($valorTotal, 2, ',', '.') . '</td></tr>
                <tr><td><strong>Produtos com Estoque Baixo:</strong></td><td>' . $estoqueBaixo . '</td></tr>
            </table>
            
            <h2>LISTA DE PRODUTOS</h2>
            <table>
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Categoria</th>
                        <th>Quantidade</th>
                        <th>Valor Unit.</th>
                        <th>Valor Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>';
        
        foreach ($produtos as $p) {
            $valorUnitario = 'R$ ' . number_format($p['valor'], 2, ',', '.');
            $valorTotalProduto = 'R$ ' . number_format($p['valor'] * $p['quantidade'], 2, ',', '.');
            $status = $p['quantidade'] < 10 ? '<span class="baixo">ESTOQUE BAIXO</span>' : 'OK';
            
            $html .= '
                <tr>
                    <td>' . htmlspecialchars($p['produto']) . '</td>
                    <td>' . $p['categoria'] . '</td>
                    <td align="center">' . $p['quantidade'] . '</td>
                    <td align="right">' . $valorUnitario . '</td>
                    <td align="right">' . $valorTotalProduto . '</td>
                    <td>' . $status . '</td>
                </tr>';
        }
        
        $html .= '
                <tr class="total">
                    <td colspan="2"><strong>TOTAL GERAL:</strong></td>
                    <td align="center">' . $totalQuantidade . '</td>
                    <td></td>
                    <td align="right">R$ ' . number_format($valorTotal, 2, ',', '.') . '</td>
                    <td></td>
                </tr>
                </tbody>
            </table>
        </body>
        </html>';
        
        // Gerar PDF
        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        // Download
        $dompdf->stream("relatorio_estoque_" . date('Y-m-d') . ".pdf", array("Attachment" => true));
        exit;
    }
}