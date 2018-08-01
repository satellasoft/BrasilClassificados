<?php

class ClassificadoConsulta {

    private $cod;
    private $nome;
    private $descricao;
    private $imagem;
    
    function getCod() {
        return $this->cod;
    }

    function setCod($cod) {
        $this->cod = $cod;
    }

    
    function getNome() {
        return $this->nome;
    }

    function getDescricao() {
        return $this->descricao;
    }

    function getImagem() {
        return $this->imagem;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }

    function setDescricao($descricao) {
        $this->descricao = $descricao;
    }

    function setImagem($imagem) {
        $this->imagem = $imagem;
    }
}
?>