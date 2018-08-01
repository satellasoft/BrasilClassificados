<?php

require_once("../DAL/TelefoneDAO.php");

class TelefoneController {

    private $telefoneDAO;

    public function __construct() {
        $this->telefoneDAO = new TelefoneDAO();
    }

    public function Cadastrar(Telefone $telefone) {
        if (strlen($telefone->getNumero()) > 5 && $telefone->getTipo() > 0 && $telefone->getTipo() <= 3 && $telefone->getUsuario()->getCod() > 0) {
            return $this->telefoneDAO->Cadastrar($telefone);
        } else {
            return false;
        }
    }

    public function Deletar(int $cod) {
        if ($cod > 0) {
            return $this->telefoneDAO->Deletar($cod);
        } else {
            return false;
        }
    }

    public function Alterar(Telefone $telefone) {
        if (strlen($telefone->getNumero()) > 5 && $telefone->getTipo() > 0 && $telefone->getTipo() <= 3 && $telefone->getCod() > 0) {
            return $this->telefoneDAO->Alterar($telefone);
        } else {
            return false;
        }
    }

    public function RetornarTodosUsuario(int $usuarioCod) {
        if ($usuarioCod > 0) {
            return $this->telefoneDAO->RetornarTodosUsuario($usuarioCod);
        } else {
            return null;
        }
    }

    public function RetornarCod(int $telefoneCod) {
        if ($telefoneCod > 0) {
            return $this->telefoneDAO->RetornarCod($telefoneCod);
        } else {
            return null;
        }
    }

}
