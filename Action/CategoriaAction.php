<?php

require_once ("../Controller/CategoriaController.php");
require_once ("../Model/Categoria.php");
$categoriaController = new CategoriaController();

$req = filter_input(INPUT_GET, "req", FILTER_SANITIZE_NUMBER_INT);

/*
 * 1- Retornar todas as categorias em JSON. 
 */
switch ($req) {
    case 1:
        echo $categoriaController->RetornarTodosJSON();
        break;
}
?>