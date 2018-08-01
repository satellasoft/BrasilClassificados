<?php

require_once("Banco.php");

class ImagemDAO {

    private $pdo;
    private $debug;

    public function __construct() {
        $this->pdo = new Banco();
    }

    public function CadastrarImagens(array $imagem) {
        //http://stackoverflow.com/questions/6523062/php-function-arguments-array-of-objects-of-a-specific-class
        try {
            $erros = 0;

            $this->pdo->BeginTransaction();

            foreach ($imagem as $i) {
                $sql = "INSERT INTO imagens (imagem, classificado_cod) VALUES (:imagem, :classificadocod)";
                $param = array(
                    ":imagem" => $i->getImagem(),
                    ":classificadocod" => $i->getClassificado()->getCod()
                );
                if (!$this->pdo->ExecuteNonQuery($sql, $param)) {
                    $erros++;
                }
            }

            if ($erros == 0) {
                $this->pdo->Commit();
                return true;
            } else {
                $this->pdo->Rollback();
                return false;
            }
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            $this->pdo->Rollback();
            return false;
        }
    }

    public function CarregarImagensClassificado(int $classificadoCod) {
        try {
            $sql = "SELECT cod, imagem FROM imagens WHERE classificado_cod = :classificadocod";
            $param = array(
                ":classificadocod" => $classificadoCod
            );

            $dt = $this->pdo->ExecuteQuery($sql, $param);
            $listaImagens = [];

            foreach ($dt as $dr) {
                $imagem = new Imagem();
                $imagem->setCod($dr["cod"]);
                $imagem->setImagem($dr["imagem"]);

                $listaImagens[] = $imagem;
            }

            return $listaImagens;
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return null;
        }
    }

    public function VerificarArquivoExiste(int $classificadoCod, int $imagemCod) {
        try {
            $sql = "SELECT imagem FROM imagens WHERE classificado_cod  = :classificadocod AND cod = :cod";
            $param = array(
                ":classificadocod" => $classificadoCod,
                ":cod" => $imagemCod
            );

            $dr = $this->pdo->ExecuteQueryOneRow($sql, $param);

            return $dr["imagem"];
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return null;
        }
    }

    public function RemoverImagem(int $classificadoCod, int $imagemCod) {
        try {
            $sql = "DELETE FROM imagens WHERE classificado_cod  = :classificadocod AND cod = :cod";
            $param = array(
                ":classificadocod" => $classificadoCod,
                ":cod" => $imagemCod
            );
            return $this->pdo->ExecuteNonQuery($sql, $param);
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return false;
        }
    }

    public function RetornarImagensClassificado(int $classificado, int $usuario) {
        try {
            $sql = "SELECT i.imagem, c.nome FROM imagens i INNER JOIN classificado c ON c.cod = i.classificado_cod WHERE c.cod = :classificadocod AND c.usuario_cod = :usuariocod";
            $param = array(
                ":classificadocod" => $classificado,
                ":usuariocod" => $usuario
            );

            $dt = $this->pdo->ExecuteQuery($sql, $param);
            $listaImagem = array();

            foreach ($dt as $dr) {
                //  $imagem = new Imagem();
                $array = array($dr["imagem"], $dr["nome"]);
//                $imagem->setImagem($dr["imagem"]);
//                $imagem->getClassificado()->setNome($dr["nome"]);

                $listaImagem[] = $array;
            }

            return $listaImagem;
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return null;
        }
    }

    public function RetornarImagensClassificadoResumida(int $classificado) {
        try {
            $sql = "SELECT imagem FROM imagens WHERE classificado_cod = :classificadocod";
            $param = array(
                ":classificadocod" => $classificado
            );

            $dt = $this->pdo->ExecuteQuery($sql, $param);
            $listaImagem = array();

            foreach ($dt as $dr) {
                $array = $dr["imagem"];

                $listaImagem[] = $array;
            }

            return $listaImagem;
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return null;
        }
    }

}

?>