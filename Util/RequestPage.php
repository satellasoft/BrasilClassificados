<?php

$pagina = filter_input(INPUT_GET, "pagina", FILTER_SANITIZE_STRING);

//echo $pagina;


$arrayPaginas = array(
    "home" => "View/home.php", //Página inicial
    "contato" => "View/ContatoView/ContatoView.php",
    "usuario" => "View/UsuarioView/UsuarioView.php",
    "endereco" => "View/UsuarioView/EnderecoView.php",
    "telefone" => "View/UsuarioView/TelefoneView.php",
    "alterarsenha" => "View/UsuarioView/AlterarSenhaView.php",
    "visualizarusuario" => "View/UsuarioView/VisualizarView.php",
    "classificado" => "View/ClassificadoView/ClassificadoView.php",
    "categoria" => "View/CategoriaView/CategoriaView.php",
    "categoriaimagem" => "View/CategoriaView/AlterarImagem.php",
    "gerenciarimagemclassificado" => "View/ClassificadoView/ImagensClassificadoView.php",
    "visualizarclassificado" => "View/ClassificadoView/VisualizarClassificado.php",
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