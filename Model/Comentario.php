<?php

require_once("Classificado.php");
require_once("Usuario.php");

class Comentario {

    private $cod;
    private $mensagem;
    private $data;
    private $status;
    private $classificado;
    private $usuario;
    private $subComentario;

    public function __construct() {
        $this->classificado = new Classificado();
        $this->usuario = new Usuario();
    }

    function getCod() {
        return $this->cod;
    }

    function getMensagem() {
        return $this->mensagem;
    }

    function getData() {
        return $this->data;
    }

    function getStatus() {
        return $this->status;
    }

    function getClassificado() {
        return $this->classificado;
    }

    function getUsuario() {
        return $this->usuario;
    }

    function setCod($cod) {
        $this->cod = $cod;
    }

    function setMensagem($mensagem) {
        $this->mensagem = $mensagem;
    }

    function setData($data) {
        $this->data = $data;
    }

    function setStatus($status) {
        $this->status = $status;
    }

    function setClassificado($classificado) {
        $this->classificado = $classificado;
    }

    function setUsuario($usuario) {
        $this->usuario = $usuario;
    }

    function getSubComentario() {
        return $this->subComentario;
    }

    function setSubComentario($subComentario) {
        $this->subComentario = $subComentario;
    }

}

?>