<?php

require_once("Banco.php");

if (file_exists("../Util/ClassSerialization.php")) {
    require_once("../Util/ClassSerialization.php");
} elseif (file_exists("Util/ClassSerialization.php")) {
    require_once("Util/ClassSerialization.php");
}

class CategoriaDAO {

    private $pdo;
    private $debug;
    private $serialization;

    public function __construct() {
        $this->pdo = new Banco();
        $this->debug = true;
        $this->serialization = new ClassSerialization();
    }

    public function Cadastrar(Categoria $categoria) {
        try {
            $sql = "INSERT INTO categoria (nome, thumb, descricao, link, categoria_cod) VALUES (:nome, :thumb, :descricao, :link, :categoriacod)";
            $param = array(
                ":nome" => $categoria->getNome(),
                "thumb" => $categoria->getThumb(),
                ":descricao" => $categoria->getDescricao(),
                ":link" => $categoria->getLink(),
                ":categoriacod" => $categoria->getSubcategoria()
            );

            return $this->pdo->ExecuteNonQuery($sql, $param);
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return false;
        }
    }

    public function Alterar(Categoria $categoria) {
        try {
            $sql = "UPDATE categoria SET nome = :nome, descricao = :descricao, link = :link, categoria_cod = :categoriacod WHERE cod = :cod";
            $param = array(
                ":nome" => $categoria->getNome(),
                ":descricao" => $categoria->getDescricao(),
                ":link" => $categoria->getLink(),
                ":categoriacod" => $categoria->getSubcategoria(),
                ":cod" => $categoria->getCod()
            );

            return $this->pdo->ExecuteNonQuery($sql, $param);
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return false;
        }
    }

    public function AlterarImagem(string $thumb, int $cod) {
        try {
            $sql = "UPDATE categoria SET thumb = :thumb WHERE cod = :cod";
            $param = array(
                ":thumb" => $thumb,
                ":cod" => $cod
            );

            return $this->pdo->ExecuteNonQuery($sql, $param);
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return false;
        }
    }

    public function RetornarCategoriasResumido() {
        try {
            //$sql = "SELECT cod, nome FROM categoria WHERE categoria_cod IS NULL ORDER BY nome ASC"; //Categorias PAI
            $sql = "SELECT cod, nome, categoria_cod FROM categoria ORDER BY categoria_cod, nome ASC"; //Categorias PAI e FILHO

            $dt = $this->pdo->ExecuteQuery($sql);
            $listaCategoria = [];

            foreach ($dt as $cat) {
                $categoria = new Categoria();
                $categoria->setCod($cat["cod"]);
                $categoria->setNome($cat["nome"]);
                $categoria->setSubcategoria($cat["categoria_cod"]);
                $listaCategoria[] = $categoria;
            }

            return $listaCategoria;
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return null;
        }
    }

    public function RetornarTodos() {
        try {
            $sql = "SELECT cod, nome, categoria_cod, thumb, descricao, link FROM categoria ORDER BY nome ASC"; //Categorias PAI e FILHO

            $dt = $this->pdo->ExecuteQuery($sql);
            $listaCategoria = [];

            foreach ($dt as $cat) {
                $categoria = new Categoria();
                $categoria->setCod($cat["cod"]);
                $categoria->setNome($cat["nome"]);
                $categoria->setDescricao($cat["descricao"]);
                $categoria->setLink($cat["link"]);
                $categoria->setThumb($cat["thumb"]);
                $categoria->setSubcategoria($cat["categoria_cod"]);
                $listaCategoria[] = $categoria;
            }

            return $listaCategoria;
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return null;
        }
    }

    public function RetornarTodosJSON() {
        try {
            $sql = "SELECT cod, nome, categoria_cod, link FROM categoria ORDER BY nome ASC"; //Categorias PAI e FILHO

            $dt = $this->pdo->ExecuteQuery($sql);
            $listaCategoria = [];

            foreach ($dt as $cat) {
                $categoria = new Categoria();
                $categoria->setCod($cat["cod"]);
                $categoria->setNome($cat["nome"]);
                $categoria->setLink($cat["link"]);
                $categoria->setSubcategoria($cat["categoria_cod"]);
                $listaCategoria[] = $categoria;
            }

            return $this->serialization->serialize($listaCategoria);
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return null;
        }
    }

    public function RetornarCod(int $cod) {
        try {
            $sql = "SELECT nome, descricao, link, categoria_cod FROM categoria WHERE cod = :cod";
            $param = array(":cod" => $cod);

            $dt = $this->pdo->ExecuteQueryOneRow($sql, $param);

            $categoria = new Categoria();

            $categoria->setNome($dt["nome"]);
            $categoria->setDescricao($dt["descricao"]);
            $categoria->setLink($dt["link"]);
            $categoria->setSubcategoria($dt["categoria_cod"]);

            return $categoria;
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return null;
        }
    }

}

?>