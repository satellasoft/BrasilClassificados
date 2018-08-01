<?php

if (file_exists("../DAL/ClassificadoDAO.php")) {
    require_once("../DAL/ClassificadoDAO.php");
} elseif (file_exists("DAL/ClassificadoDAO.php")) {
    require_once("DAL/ClassificadoDAO.php");
}

class ClassificadoController {

    private $classificadoDAO;

    function __construct() {
        $this->classificadoDAO = new classificadoDAO();
    }

    public function Cadastrar(Classificado $classificado) {

        $valor = floatval(str_replace(',', '.', str_replace('.', '', $classificado->getValor())));

        $classificado->setValor($valor);

        if (trim(strlen($classificado->getNome())) > 0 && $classificado->getStatus() > 0 && $classificado->getPerfil() > 0 && $classificado->getTipo() > 0 && trim(strlen($classificado->getDescricao())) >= 10 && $classificado->getCategoria()->getCod() > 0 && $classificado->getUsuario()->getCod() > 0) {
            return $this->classificadoDAO->Cadastrar($classificado);
        } else {
            return false;
        }
    }

    public function Alterar(Classificado $classificado) {
        if (trim(strlen($classificado->getNome())) > 0 && $classificado->getValor() > 0 && $classificado->getStatus() > 0 && $classificado->getPerfil() > 0 && $classificado->getTipo() > 0 && trim(strlen($classificado->getDescricao())) >= 10 && $classificado->getCategoria()->getCod() > 0 && $classificado->getCod() > 0) {
            return $this->classificadoDAO->Alterar($classificado);
        } else {
            return false;
        }
    }

    public function AlterarResumido(Classificado $classificado) {

        $valor = floatval(str_replace(',', '.', str_replace('.', '', $classificado->getValor())));

        $classificado->setValor($valor);

        if (trim(strlen($classificado->getDescricao())) >= 10 && $classificado->getCod() > 0 && $classificado->getUsuario()->getCod() > 0) {
            return $this->classificadoDAO->AlterarResumido($classificado);
        } else {
            return false;
        }
    }

    public function RetornarTodosFiltro(string $termo, int $tipo, int $status, int $perfil, int $categoriacod) {

        if (strlen($termo) > 0 && $tipo > 0 && $status > 0 && $perfil > 0 && $categoriacod > 0) {
            return $this->classificadoDAO->RetornarTodosFiltro($termo, $tipo, $status, $perfil, $categoriacod);
        } else {
            return null;
        }
    }

    public function RetornarCod(int $cod) {
        if ($cod > 0) {
            return $this->classificadoDAO->RetornarCod($cod);
        } else {
            return null;
        }
    }

    public function RetornarCompletoCod($cod) {
        if ($cod > 0) {
            return $this->classificadoDAO->RetornarCompletoCod($cod);
        } else {
            return null;
        }
    }

    public function RetornarQuantidadeRegistros(int $categoriaCod, string $termo) {
        if (strlen($termo) >= 3 && $categoriaCod > 0) {
            return $this->classificadoDAO->RetornarQuantidadeRegistros($categoriaCod, $termo);
        } else {
            return 0;
        }
    }

    public function RetornarPesquisa(int $categoriaCod, string $termo, int $inicio, int $fim) {
        if (strlen($termo) >= 3 && $categoriaCod > 0) {
            return $this->classificadoDAO->RetornarPesquisa($categoriaCod, $termo, $inicio, $fim);
        } else {
            return null;
        }
    }

    public function RetornarAnuncioClassificadoCod(int $cod) {
        if ($cod > 0) {
            return $this->classificadoDAO->RetornarAnuncioClassificadoCod($cod);
        } else {
            return null;
        }
    }

    public function RetornarUsuarioCod(int $usuarioCod) {
        if ($usuarioCod > 0) {
            return $this->classificadoDAO->RetornarUsuarioCod($usuarioCod);
        } else {
            return null;
        }
    }

    public function RetornarUltimosClassificados(int $qnt) {
        if ($qnt > 0) {
            return $this->classificadoDAO->RetornarUltimosClassificados($qnt);
        } else {
            return null;
        }
    }

}

?>