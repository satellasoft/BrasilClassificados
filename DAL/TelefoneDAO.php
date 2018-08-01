<?php

require_once("Banco.php");

class TelefoneDAO {

    private $pdo;
    private $debug;

    public function __construct() {
        $this->pdo = new Banco();
    }

    public function Cadastrar(Telefone $telefone) {
        try {
            $sql = "INSERT INTO telefone (tipo, numero, usuario_cod) VALUES (:tipo, :numero, :usuario)";
            $param = array(
                ":tipo" => $telefone->getTipo(),
                ":numero" => $telefone->getNumero(),
                ":usuario" => $telefone->getUsuario()->getCod()
            );

            return $this->pdo->ExecuteNonQuery($sql, $param);
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return false;
        }
    }

    public function Alterar(Telefone $telefone) {
        try {
            $sql = "UPDATE telefone SET tipo = :tipo, numero = :numero WHERE cod = :cod";
            $param = array(
                ":tipo" => $telefone->getTipo(),
                ":numero" => $telefone->getNumero(),
                ":cod" => $telefone->getCod()
            );

            return $this->pdo->ExecuteNonQuery($sql, $param);
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return false;
        }
    }
    
    public function Deletar(int $cod) {
        try {
            $sql = "DELETE FROM telefone WHERE cod = :cod";
            $param = array(
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

    //Consultar Todos
    public function RetornarTodosUsuario(int $usuarioCod) {
        try {
            $sql = "SELECT t.cod, t.tipo, t.numero, u.nome FROM telefone t INNER JOIN usuario u ON u.cod = t.usuario_cod WHERE t.usuario_cod = :usuarioCod ORDER BY t.tipo ASC";
            $param = array(
                ":usuarioCod" => $usuarioCod
            );

            $dt = $this->pdo->ExecuteQuery($sql, $param);
            $listaTelefone = [];

            foreach ($dt as $r) {
                $telefone = new Telefone();

                $telefone->setCod($r["cod"]);
                $telefone->setTipo($r["tipo"]);
                $telefone->setNumero($r["numero"]);
                $telefone->getUsuario()->setNome($r["nome"]);

                $listaTelefone[] = $telefone;
            }

            return $listaTelefone;
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return null;
        }
    }

    public function RetornarCod(int $telefoneCod) {
        try {
            $sql = "SELECT tipo, numero FROM telefone WHERE cod = :cod";
            $param = array(
                ":cod" => $telefoneCod
            );

            $dt = $this->pdo->ExecuteQueryOneRow($sql, $param);

            $telefone = new Telefone();

            $telefone->setTipo($dt["tipo"]);
            $telefone->setNumero($dt["numero"]);
            return $telefone;
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return null;
        }
    }

}

?>