<?php

require_once("Usuario.php");
require_once("Categoria.php");

class Classificado {

    private $cod;
    private $nome;
    private $descricao;
    private $tipo;
    private $valor;
    private $status;
    private $perfil;
    private $categoria;
    private $usuario;

    public function __construct() {
        $this->usuario = new Usuario();
        $this->categoria = new Categoria();
    }

    function getCod() {
        return $this->cod;
    }

    function getNome() {
        return $this->nome;
    }

    function getDescricao() {
        return $this->descricao;
    }

    function getTipo() {
        return $this->tipo;
    }

    function getValor() {
        return $this->valor;
    }

    function getStatus() {
        return $this->status;
    }

    function getPerfil() {
        return $this->perfil;
    }

    function getCategoria() {
        return $this->categoria;
    }

    function getUsuario() {
        return $this->usuario;
    }

    function setCod($cod) {
        $this->cod = $cod;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }

    function setDescricao($descricao) {
        $this->descricao = $descricao;
    }

    function setTipo($tipo) {
        $this->tipo = $tipo;
    }

    function setValor($valor) {
        $this->valor = $valor;
    }

    function setStatus($status) {
        $this->status = $status;
    }

    function setPerfil($perfil) {
        $this->perfil = $perfil;
    }

    function setCategoria($categoria) {
        $this->categoria = $categoria;
    }

    function setUsuario($usuario) {
        $this->usuario = $usuario;
    }

}

?>