<?php

require_once("DAL/ComentarioDAO.php");

class ComentarioController {

    private $comentarioDAO;

    function __construct() {
        $this->comentarioDAO = new ComentarioDAO();
    }

    public function Cadastrar(Comentario $comentario) {
        if (strlen($comentario->getMensagem()) <= 500 && $comentario->getClassificado()->getCod() > 0 && $comentario->getUsuario()->getCod() > 0) {
            return $this->comentarioDAO->Cadastrar($comentario);
        } else {
            return false;
        }
    }

    public function Remover(int $comentariocod) {
        if ($comentariocod > 0) {
            return $this->comentarioDAO->Remover($comentariocod);
        } else {
            return false;
        }
    }

    public function RetornarUltmosClassificado(int $classificadocod) {
        if ($classificadocod > 0) {
            return $this->comentarioDAO->RetornarUltmosClassificado($classificadocod);
        } else {
            return null;
        }
    }

    public function RetornarTodosClassificadoCod(int $classificadocod) {
        if ($classificadocod > 0) {
            return $this->comentarioDAO->RetornarTodosClassificadoCod($classificadocod);
        } else {
            return null;
        }
    }

}

?>