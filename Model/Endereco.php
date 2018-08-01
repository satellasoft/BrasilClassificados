<?php

require_once("Usuario.php");

class Endereco {

    private $cod;
    private $rua;
    private $numero;
    private $bairro;
    private $cidade;
    private $estado;
    private $complemento;
    private $cep;
    private $usuario;

    public function __construct() {
        $this->usuario = new Usuario();
    }

    function getCod() {
        return $this->cod;
    }

    function getRua() {
        return $this->rua;
    }

    function getNumero() {
        return $this->numero;
    }

    function getBairro() {
        return $this->bairro;
    }

    function getCidade() {
        return $this->cidade;
    }

    function getEstado() {
        return $this->estado;
    }

    function getComplemento() {
        return $this->complemento;
    }

    function getCep() {
        return $this->cep;
    }

    function getUsuario() {
        return $this->usuario;
    }

    function setCod($cod) {
        $this->cod = $cod;
    }

    function setRua($rua) {
        $this->rua = $rua;
    }

    function setNumero($numero) {
        $this->numero = $numero;
    }

    function setBairro($bairro) {
        $this->bairro = $bairro;
    }

    function setCidade($cidade) {
        $this->cidade = $cidade;
    }

    function setEstado($estado) {
        $this->estado = $estado;
    }

    function setComplemento($complemento) {
        $this->complemento = $complemento;
    }

    function setCep($cep) {
        $this->cep = $cep;
    }

    function setUsuario($usuario) {
        $this->usuario = $usuario;
    }

}

?>