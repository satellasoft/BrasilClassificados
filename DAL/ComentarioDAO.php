<?php

require_once("Banco.php");

class ComentarioDAO {

    private $pdo;
    private $debug;

    public function __construct() {
        $this->pdo = new Banco();
        $this->debug = true;
    }

    public function Cadastrar(Comentario $comentario) {
        try {
            $sql = "INSERT INTO comentario (mensagem, data, status, classificado_cod, usuario_cod, comentario_cod) VALUES (:mensagem, :data, :status, :classificadocod, :usuariocod, :comentariocod)";

            $param = array(
                ":mensagem" => $comentario->getMensagem(),
                ":data" => date("Y-m-d H-i-s"),
                ":status" => 1,
                ":classificadocod" => $comentario->getClassificado()->getCod(),
                ":usuariocod" => $comentario->getUsuario()->getCod(),
                ":comentariocod" => $comentario->getSubComentario()
            );

            return $this->pdo->ExecuteNonQuery($sql, $param);
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return false;
        }
    }

    public function Remover(int $comentariocod) {
        try {
            $sql = "UPDATE comentario SET status = :status WHERE cod = :comentariocod";

            $param = array(
                ":status" => 2,
                ":comentariocod" => $comentariocod
            );

            return $this->pdo->ExecuteNonQuery($sql, $param);
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return false;
        }
    }

    public function RetornarUltmosClassificado(int $classificadocod) {
        try {
            $sql = "SELECT c.cod, c.mensagem, c.data, c.comentario_cod, u.nome FROM comentario c INNER JOIN usuario u ON u.cod = c.usuario_cod WHERE c.classificado_cod = :clacod AND c.status = 1 ORDER BY c.data DESC LIMIT 15";
            $param = array(
                ":clacod" => $classificadocod
            );

            $dt = $this->pdo->ExecuteQuery($sql, $param);
            $listaComentario = array();

            foreach ($dt as $dr) {
                $comentario = new Comentario();
                $comentario->setCod($dr["cod"]);
                $comentario->setMensagem($dr["mensagem"]);
                $comentario->setData($dr["data"]);
                $comentario->getUsuario()->setNome($dr["nome"]);
                $comentario->setSubComentario($dr["comentario_cod"]);

                $listaComentario[] = $comentario;
            }

            return $listaComentario;
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return null;
        }
    }

    public function RetornarTodosClassificadoCod(int $classificadocod) {
        try {
            $sql = "SELECT c.cod, c.status, c.mensagem, c.data, c.comentario_cod, u.nome, u.email FROM comentario c INNER JOIN usuario u ON u.cod = c.usuario_cod WHERE c.classificado_cod = :clacod ORDER BY c.data DESC";
            $param = array(
                ":clacod" => $classificadocod
            );

            $dt = $this->pdo->ExecuteQuery($sql, $param);
            $listaComentario = array();

            foreach ($dt as $dr) {
                $comentario = new Comentario();
                $comentario->setCod($dr["cod"]);
                $comentario->setStatus($dr["status"]);
                $comentario->setMensagem($dr["mensagem"]);
                $comentario->setData($dr["data"]);
                $comentario->setSubComentario($dr["comentario_cod"]);
                $comentario->getUsuario()->setNome($dr["nome"]);
                $comentario->getUsuario()->setEmail($dr["email"]);

                $listaComentario[] = $comentario;
            }

            return $listaComentario;
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return null;
        }
    }

}
