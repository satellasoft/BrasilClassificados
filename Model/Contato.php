<?php

class Contato {

    private $cod;
    private $nome;
    private $email;
    private $assunto;
    private $mensagem;
    private $data;
    private $ip;

    function getCod() {
        return $this->cod;
    }

    function getNome() {
        return $this->nome;
    }

    function getEmail() {
        return $this->email;
    }

    function getAssunto() {
        return $this->assunto;
    }

    function getMensagem() {
        return $this->mensagem;
    }

    function getData() {
        return $this->data;
    }

    function getIp() {
        return $this->ip;
    }

    function setCod($cod) {
        $this->cod = $cod;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    function setAssunto($assunto) {
        $this->assunto = $assunto;
    }

    function setMensagem($mensagem) {
        $this->mensagem = $mensagem;
    }

    function setData($data) {
        $this->data = $data;
    }

    function setIp($ip) {
        $this->ip = $ip;
    }

}

?>