<?php

class Usuario {

    private $cod;
    private $nome;
    private $email;
    private $cpf;
    private $usuario;
    private $senha;
    private $nascimento;
    private $sexo;
    private $status;
    private $permissao;
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

    function getCpf() {
        return $this->cpf;
    }

    function getUsuario() {
        return $this->usuario;
    }

    function getSenha() {
        return $this->senha;
    }

    function getNascimento() {
        return $this->nascimento;
    }

    function getSexo() {
        return $this->sexo;
    }

    function getStatus() {
        return $this->status;
    }

    function getPermissao() {
        return $this->permissao;
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

    function setCpf($cpf) {
        $this->cpf = $cpf;
    }

    function setUsuario($usuario) {
        $this->usuario = strtolower($usuario);
    }

    function setSenha($senha) {
        $this->senha = md5($senha);
    }

    function setNascimento($nascimento) {
        $date = str_replace('/', '-', $nascimento);
        $this->nascimento = date('Y-m-d', strtotime($date));
    }

    function setSexo($sexo) {
        $this->sexo = $sexo;
    }

    function setStatus($status) {
        $this->status = $status;
    }

    function setPermissao($permissao) {
        $this->permissao = $permissao;
    }

    function setIp($ip) {
        $this->ip = $ip;
    }

}

?>