<?php

$pagina = filter_input(INPUT_GET, "pagina", FILTER_SANITIZE_STRING);

$arrayPaginas = array(
    "home" => "View/home.php", //Página inicial
    "contato" => "View/contato.php",
    "classificado" => "View/classificado.php",
    "categoria" => "View/categoria.php",
    "quemsomos" => "View/quemsomos.php",
    "termodeuso" => "View/termosuso.php",
    "cadastro" => "View/cadastro.php",
    "entrar" => "View/entrar.php",
    //painel
    "painel" => "View/painel.php",
     "anunciar" => "View/painel/anunciar.php",
    "anexarimagem" => "View/painel/gerenciarimagem.php",
    "visualizarclassificado" => "View/painel/visualizarclassificado.php"
);

if ($pagina) {
    $encontrou = false;

    foreach ($arrayPaginas as $page => $key) {
        if ($pagina == $page) {
            $encontrou = true;
            require_once($key);
        }
    }

    if (!$encontrou) {
        require_once("View/home.php");
    }
} else {
    require_once("View/home.php");
}
?>