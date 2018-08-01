<?php

class Categoria {

    private $cod;
    private $nome;
    private $thumb;
    private $descricao;
    private $link;
    private $subcategoria;

    function getSubcategoria() {
        return $this->subcategoria;
    }

    function setSubcategoria($subcategoria) {
        $this->subcategoria = $subcategoria;
    }

    function getCod() {
        return $this->cod;
    }

    function getNome() {
        return $this->nome;
    }

    function getThumb() {
        return $this->thumb;
    }

    function getDescricao() {
        return $this->descricao;
    }

    function getLink() {
        return $this->link;
    }

    function setCod($cod) {
        $this->cod = $cod;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }

    function setThumb($thumb) {
        $this->thumb = $thumb;
    }

    function setDescricao($descricao) {
        $this->descricao = $descricao;
    }

    function setLink($link) {
        $this->link = $link;
    }

}

?>