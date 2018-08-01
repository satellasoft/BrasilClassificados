<?php

require_once("Classificado.php");

class Imagem {

    private $cod;
    private $imagem;
    private $classificado;

    public function __construct() {
        $this->classificado = new Classificado();
    }

    function getCod() {
        return $this->cod;
    }

    function getImagem() {
        return $this->imagem;
    }

    function getClassificado() {
        return $this->classificado;
    }

    function setCod($cod) {
        $this->cod = $cod;
    }

    function setImagem($imagem) {
        $this->imagem = $imagem;
    }

    function setClassificado($classificado) {
        $this->classificado = $classificado;
    }

}

?>