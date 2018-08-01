<?php

require_once("Banco.php");

class EnderecoDAO {

    private $pdo;
    private $debug;

    public function __construct() {
        $this->pdo = new Banco();
        $this->debug = true;
    }

    public function Cadastrar(Endereco $endereco) {
        try {
            $sql = "INSERT INTO endereco (rua, numero, bairro, cidade, estado, complemento, cep, usuario_cod) VALUES (:rua, :numero, :bairro, :cidade, :estado, :complemento, :cep, :usuario)";
            $param = array(
                ":rua" => $endereco->getRua(),
                ":numero" => $endereco->getNumero(),
                ":bairro" => $endereco->getBairro(),
                ":cidade" => $endereco->getCidade(),
                ":estado" => $endereco->getEstado(),
                ":complemento" => $endereco->getComplemento(),
                ":cep" => $endereco->getCep(),
                ":usuario" => $endereco->getUsuario()->getCod()
            );
            return $this->pdo->ExecuteNonQuery($sql, $param);
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return false;
        }
    }

    public function Alterar(Endereco $endereco) {
        try {
            $sql = "UPDATE endereco SET rua = :rua, numero = :numero, bairro = :bairro, cidade = :cidade, estado = :estado, complemento = :complemento, cep = :cep WHERE cod = :cod";
            $param = array(
                ":rua" => $endereco->getRua(),
                ":numero" => $endereco->getNumero(),
                ":bairro" => $endereco->getBairro(),
                ":cidade" => $endereco->getCidade(),
                ":estado" => $endereco->getEstado(),
                ":complemento" => $endereco->getComplemento(),
                ":cep" => $endereco->getCep(),
                ":cod" => $endereco->getCod()
            );
            return $this->pdo->ExecuteNonQuery($sql, $param);
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return false;
        }
    }

    public function RetornarTodosUsuarioCod(int $usuarioCod) {
        try {
            $sql = "SELECT e.cod, e.rua, e.numero, e.bairro, e.cidade, e.estado, e.complemento, e.cep, u.nome FROM endereco e INNER JOIN usuario u ON u.cod = e.usuario_cod WHERE e.usuario_cod = :usuariocod ORDER by e.cod DESC";
            $param = array(":usuariocod" => $usuarioCod);

            $dt = $this->pdo->ExecuteQuery($sql, $param);
            $listaEndereco = [];

            foreach ($dt as $r) {
                $endereco = new Endereco();

                $endereco->setCod($r["cod"]);
                $endereco->setRua($r["rua"]);
                $endereco->setBairro($r["bairro"]);
                $endereco->setCep($r["cep"]);
                $endereco->setCidade($r["cidade"]);
                $endereco->setComplemento($r["complemento"]);
                $endereco->setEstado($r["estado"]);
                $endereco->setNumero($r["numero"]);
                $endereco->getUsuario()->setNome($r["nome"]);

                $listaEndereco[] = $endereco;
            }

            return $listaEndereco;
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return null;
        }
    }

    public function RetornarCod(int $enderecoCod) {
        try {
            $sql = "SELECT e.rua, e.numero, e.bairro, e.cidade, e.estado, e.complemento, e.cep FROM endereco e WHERE e.cod = :cod";
            $param = array(":cod" => $enderecoCod);

            $dt = $this->pdo->ExecuteQueryOneRow($sql, $param);

            $endereco = new Endereco();


            $endereco->setRua($dt["rua"]);
            $endereco->setBairro($dt["bairro"]);
            $endereco->setCep($dt["cep"]);
            $endereco->setCidade($dt["cidade"]);
            $endereco->setComplemento($dt["complemento"]);
            $endereco->setEstado($dt["estado"]);
            $endereco->setNumero($dt["numero"]);


            return $endereco;
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return null;
        }
    }

    public function Deletar(int $enderecoCod) {
        try {
            $sql = "DELETE FROM endereco WHERE cod = :cod";
            $param = array(
                ":cod" => $enderecoCod
            );

            return $this->pdo->ExecuteNonQuery($sql, $param);
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return false;
        }
    }

}

?>