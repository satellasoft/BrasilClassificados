<?php

require_once("../DAL/EnderecoDAO.php");

class EnderecoController {

    private $enderecoDAO;

    public function __construct() {
        $this->enderecoDAO = new EnderecoDAO();
    }

    function Cadastrar(Endereco $endereco) {
        if (strlen($endereco->getRua()) > 0 &&
                strlen($endereco->getNumero()) > 0 &&
                strlen($endereco->getCidade()) > 0 &&
                strlen($endereco->getComplemento()) > 0 &&
                strlen($endereco->getCep()) == 9 &&
                $endereco->getUsuario()->getCod() > 0 &&
                $this->ValidarEstado($endereco->getEstado())) {

            return $this->enderecoDAO->Cadastrar($endereco);
        } else {
            return false;
        }
    }

    public function Alterar(Endereco $endereco) {
        if (strlen($endereco->getRua()) > 0 &&
                strlen($endereco->getNumero()) > 0 &&
                strlen($endereco->getCidade()) > 0 &&
                strlen($endereco->getComplemento()) > 0 &&
                strlen($endereco->getCep()) == 9 &&
                $endereco->getCod() > 0 &&
                $this->ValidarEstado($endereco->getEstado())) {

            return $this->enderecoDAO->Alterar($endereco);
        } else {
            return false;
        }
    }

    public function RetornarTodosUsuarioCod(int $usuarioCod) {
        if ($usuarioCod > 0) {
            return $this->enderecoDAO->RetornarTodosUsuarioCod($usuarioCod);
        } else {
            return null;
        }
    }

    public function RetornarCod(int $enderecoCod) {
        if ($enderecoCod > 0) {
            return $this->enderecoDAO->RetornarCod($enderecoCod);
        } else {
            return null;
        }
    }

    public function Deletar(int $enderecoCod) {
        if ($enderecoCod > 0) {
            return $this->enderecoDAO->Deletar($enderecoCod);
        } else {
            return false;
        }
    }

    private function ValidarEstado(string $es) {
        $achou = false;
        $arrayEstados = array("ac", "al", "am", "ap", "ba", "ce", "df", "es", "go", "ma", "mt", "ms", "mg", "pa", "pb", "pr", "pe", "pi", "rj", "rn", "ro", "rs", "rr", "sc", "se", "sp", "to");
        for ($i = 0; $i < count($arrayEstados); $i++) {
            if ($arrayEstados[$i] == $es) {
                $achou = true;
            }
        }

        return $achou;
    }

}

?>