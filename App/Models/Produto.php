<?php


namespace App\Models;

use MF\Model\Model;

class Produto extends Model {
    private $id;
    private $id_usuario;
    private $produto;
    private $valor;
    private $categoria;
    private $quantidade;
    private $descricao;

    public function __get($atributo)
    {
        return $this->$atributo;
    }
    public function __set($atributo, $valor)
    {
        $this->$atributo = $valor;
    }


    //salvar
    public function salvar() {
        $query = "insert into produtos(id_usuario, produto, valor, quantidade, categoria, descricao)values(:id_usuario, :produto, :valor, :quantidade, :categoria, :descricao)";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
        $stmt->bindValue(':produto', $this->__get('produto'));
        $stmt->bindValue(':valor', $this->__get('valor'));
        $stmt->bindValue(':quantidade', $this->__get('quantidade'));
        $stmt->bindValue(':categoria', $this->__get('categoria'));
        $stmt->bindValue(':descricao', $this->__get('descricao'));

        $stmt->execute();

        return $this;
    }

    //recuperar

    public function getAll() {

        $query = "
            select 
                p.id, p.id_usuario, u.nome, p.produto, p.valor, p.quantidade, p.categoria, p.descricao 
            from 
                produtos as p
                left join usuarios as u on (p.id_usuario = u.id)
            where 
                p.id_usuario = :id_usuario
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);

    }
}



?>