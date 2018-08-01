<?php

require_once("Banco.php");

class ClassificadoDAO {

    private $pdo;
    private $debug;

    public function __construct() {
        $this->pdo = new Banco();
        $this->debug = true;
    }

    public function Cadastrar(Classificado $classificado) {
        try {
            $sql = "INSERT classificado (nome, descricao, tipo, valor, status, perfil, usuario_cod, categoria_cod) VALUES (:nome, :descricao, :tipo, :valor, :status, :perfil, :usuariocod, :categoriacod)";

            $param = array(
                ":nome" => $classificado->getNome(),
                ":descricao" => $classificado->getDescricao(),
                ":tipo" => $classificado->getTipo(),
                ":valor" => $classificado->getValor(),
                ":status" => $classificado->getStatus(),
                ":perfil" => $classificado->getPerfil(),
                ":usuariocod" => $classificado->getUsuario()->getCod(),
                ":categoriacod" => $classificado->getCategoria()->getCod()
            );

            return $this->pdo->ExecuteNonQuery($sql, $param);
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return false;
        }
    }

    public function Alterar(Classificado $classificado) {
        try {
            $sql = "UPDATE classificado SET nome = :nome, descricao = :descricao, tipo = :tipo, valor = :valor, status = :status, perfil = :perfil, categoria_cod = :categoriacod WHERE cod = :cod";

            $param = array(
                ":nome" => $classificado->getNome(),
                ":descricao" => $classificado->getDescricao(),
                ":tipo" => $classificado->getTipo(),
                ":valor" => $classificado->getValor(),
                ":status" => $classificado->getStatus(),
                ":perfil" => $classificado->getPerfil(),
                ":categoriacod" => $classificado->getCategoria()->getCod(),
                ":cod" => $classificado->getCod()
            );

            return $this->pdo->ExecuteNonQuery($sql, $param);
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return false;
        }
    }

    public function AlterarResumido(Classificado $classificado) {
        try {
            $sql = "UPDATE classificado SET descricao = :descricao, valor = :valor WHERE cod = :cod AND usuario_cod = :usuariocod";

            $param = array(
                ":descricao" => $classificado->getDescricao(),
                ":valor" => $classificado->getValor(),
                ":cod" => $classificado->getCod(),
                ":usuariocod" => $classificado->getUsuario()->getCod()
            );

            return $this->pdo->ExecuteNonQuery($sql, $param);
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return false;
        }
    }

    public function RetornarTodosFiltro(string $termo, int $tipo, int $status, int $perfil, int $categoriacod) {
        try {
            $sql = "SELECT cod, nome, status FROM classificado WHERE nome LIKE :termo AND tipo = :tipo AND status = :status AND perfil = :perfil AND  categoria_cod = :categoriacod ORDER BY nome ASC";
            $param = array(
                ":termo" => "%{$termo}%",
                ":tipo" => $tipo,
                ":status" => $status,
                ":perfil" => $perfil,
                ":categoriacod" => $categoriacod
            );

            $dt = $this->pdo->ExecuteQuery($sql, $param);
            $listaClassificado = [];

            foreach ($dt as $dr) {
                $classificado = new Classificado();

                $classificado->setCod($dr["cod"]);
                $classificado->setNome($dr["nome"]);
                $classificado->setStatus($dr["status"]);

                $listaClassificado[] = $classificado;
            }

            return $listaClassificado;
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return null;
        }
    }

    public function RetornarCod(int $cod) {
        try {
            $sql = "SELECT nome, descricao, tipo, valor, status, perfil, categoria_cod FROM classificado WHERE cod = :cod";
            $param = array(":cod" => $cod);
            //Data Table
            $dt = $this->pdo->ExecuteQueryOneRow($sql, $param);
            $classificado = new Classificado();

            $classificado->setNome($dt["nome"]);
            $classificado->setDescricao($dt["descricao"]);
            $classificado->setTipo($dt["tipo"]);
            $classificado->setStatus($dt["status"]);
            $classificado->setPerfil($dt["perfil"]);
            $classificado->getCategoria()->setCod($dt["categoria_cod"]);
            $classificado->setValor($dt["valor"]);

            return $classificado;
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return null;
        }
    }

    public function RetornarCompletoCod($cod) {
        try {
            $sql = "SELECT c.nome as clanome, c.descricao, c.tipo, c.valor, c.status, c.perfil, ca.nome as catnome, u.nome as usnome FROM classificado c " .
                    "INNER JOIN categoria ca ON ca.cod = c.categoria_cod " .
                    "INNER JOIN usuario u ON u.cod = c.usuario_cod " .
                    "WHERE c.cod = :cod";
            $param = array(
                ":cod" => $cod
            );

            $dr = $this->pdo->ExecuteQueryOneRow($sql, $param);

            //dt = Data Table
            //dr = Data Row

            $classificado = new Classificado();

            $classificado->setNome($dr["clanome"]);
            $classificado->setDescricao($dr["descricao"]);
            $classificado->setTipo($dr["tipo"]);
            $classificado->setValor($dr["valor"]);
            $classificado->setStatus($dr["status"]);
            $classificado->setPerfil($dr["perfil"]);
            $classificado->getCategoria()->setNome($dr["catnome"]);
            $classificado->getUsuario()->setNome($dr["usnome"]);

            return $classificado;
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return null;
        }
    }

    public function RetornarQuantidadeRegistros(int $categoriaCod, string $termo) {
        try {
            $sql = "SELECT count(cla.cod) as total FROM classificado cla WHERE cla.categoria_cod = :categoriacod AND cla.nome LIKE :termo AND cla.status = 1";

            $param = array(
                ":categoriacod" => $categoriaCod,
                ":termo" => "%{$termo}%"
            );

            $dr = $this->pdo->ExecuteQueryOneRow($sql, $param);

            if ($dr["total"] != null) {
                return $dr["total"];
            } else {
                return 0;
            }
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return null;
        }
    }

    public function RetornarPesquisa(int $categoriaCod, string $termo, int $inicio, int $fim) {
        try {
            $sql = "SELECT cla.cod, cla.nome, cla.descricao, (SELECT imagem FROM imagens WHERE classificado_cod = cla.cod ORDER BY cod ASC LIMIT 1) as img FROM classificado cla WHERE cla.categoria_cod = :categoriacod AND cla.nome LIKE :termo AND cla.status = 1 LIMIT :inicio, :fim";

            $param = array(
                ":categoriacod" => $categoriaCod,
                ":termo" => "%{$termo}%",
                ":inicio" => $inicio,
                ":fim" => $fim
            );

            $dt = $this->pdo->ExecuteQuery($sql, $param);
            $listaClassificado = [];
            foreach ($dt as $dr) {
                $classificadoConsulta = new ClassificadoConsulta();
                $classificadoConsulta->setCod($dr["cod"]);
                $classificadoConsulta->setNome($dr["nome"]);
                $classificadoConsulta->setDescricao($dr["descricao"]);
                $classificadoConsulta->setImagem($dr["img"]);

                $listaClassificado[] = $classificadoConsulta;
            }

            return $listaClassificado;
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return null;
        }
    }

    public function RetornarAnuncioClassificadoCod(int $cod) {
        try {
            $sql = "SELECT cla.cod, cla.nome, cla.descricao, cla.tipo, cla.valor, cat.nome as catnome, us.nome as usnome, us.email usemail FROM classificado cla INNER JOIN categoria cat ON cat.cod = cla.categoria_cod INNER JOIN usuario us ON us.cod = cla.usuario_cod WHERE cla.cod = :cod AND cla.status = 1";
            $param = array(
                ":cod" => $cod
            );
            $dr = $this->pdo->ExecuteQueryOneRow($sql, $param);

            $classificado = new Classificado();
            $classificado->setCod($dr["cod"]);
            $classificado->setNome($dr["nome"]);
            $classificado->setDescricao($dr["descricao"]);
            $classificado->setTipo($dr["tipo"]);
            $classificado->setValor($dr["valor"]);
            //Classificado
            $classificado->getCategoria()->setNome($dr["catnome"]);
            //Usuário
            $classificado->getUsuario()->setNome($dr["usnome"]);
            $classificado->getUsuario()->setEmail($dr["usemail"]);

            return $classificado;
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return null;
        }
    }

    public function RetornarUsuarioCod(int $usuarioCod) {
        try {
            $sql = "SELECT cla.cod, cla.nome as clanome, cla.valor, cat.nome as catnome FROM classificado cla INNER JOIN categoria cat ON cat.cod = cla.categoria_cod WHERE cla.usuario_cod = :usuariocod ORDER BY cla.cod DESC";
            $param = array(
                ":usuariocod" => $usuarioCod
            );

            $dt = $this->pdo->ExecuteQuery($sql, $param);
            $listaClassificado = array();

            foreach ($dt as $dr) {
                $classificado = new Classificado();

                $classificado->setCod($dr["cod"]);
                $classificado->setNome($dr["clanome"]);
                $classificado->setValor($dr["valor"]);

                $classificado->getCategoria()->setNome($dr["catnome"]);

                $listaClassificado[] = $classificado;
            }

            return $listaClassificado;
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return null;
        }
    }

    public function RetornarUltimosClassificados(int $qnt) {
        try {
            $sql = "SELECT c.cod, c.nome, i.imagem FROM classificado c INNER JOIN imagens i ON i.classificado_cod = c.cod WHERE c.status = 1 GROUP BY c.cod ORDER BY c.cod DESC LIMIT :limit";
            $param = array(
                ":limit" => $qnt
            );

            $dt = $this->pdo->ExecuteQuery($sql, $param);
            $listaClassificado = array();

            foreach ($dt as $dr) {
                $classificadoConsulta = new ClassificadoConsulta();
                $classificadoConsulta->setCod($dr["cod"]);
                $classificadoConsulta->setNome($dr["nome"]);
                $classificadoConsulta->setImagem($dr["imagem"]);

                $listaClassificado[] = $classificadoConsulta;
            }
            
            return $listaClassificado;
        } catch (PDOException $ex) {
            if ($this->debug) {
                echo "ERRO: {$ex->getMessage()} LINE: {$ex->getLine()}";
            }
            return null;
        }
    }

}

?>