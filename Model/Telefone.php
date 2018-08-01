<?php

require_once("Usuario.php");

class Telefone {

    private $cod;
    private $tipo;
    private $numero;
    private $usuario;
    
       public function __construct() {
        $this->usuario = new Usuario();
    }


    function getCod() {
        return $this->cod;
    }

    function getTipo() {
        return $this->tipo;
    }

    function getNumero() {
        return $this->numero;
    }

    function getUsuario() {
        return $this->usuario;
    }

    function setCod($cod) {
        $this->cod = $cod;
    }

    function setTipo($tipo) {
        $this->tipo = $tipo;
    }

    function setNumero($numero) {
        $this->numero = $numero;
    }

    function setUsuario($usuario) {
        $this->usuario = $usuario;
    }
}

?>