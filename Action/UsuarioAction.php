<?php

session_start();

require_once ("../Controller/UsuarioController.php");
require_once ("../Model/Usuario.php");

$usuarioController = new UsuarioController();

$req = filter_input(INPUT_GET, "req", FILTER_SANITIZE_NUMBER_INT);

/*
 * 1 - Verifica se o usuário existe.
 * 2 - Verifica se o e-mail existe
 * 3 - Verifica se o CPF existe
 * 4 - Autenticar usuário
 */
switch ($req) {
    case 1:
        $usuario = filter_input(INPUT_POST, "txtUsuario", FILTER_SANITIZE_STRING);
        echo $usuarioController->VerificaUsuarioExiste($usuario);
        break;
    case 2:
        $email = filter_input(INPUT_POST, "txtEmail", FILTER_SANITIZE_STRING);
        echo $usuarioController->VerificaEmailExiste($email);
        break;
    case 3:
        $cpf = filter_input(INPUT_POST, "txtCPF", FILTER_SANITIZE_STRING);
        echo $usuarioController->VerificaCPFExiste($cpf);
        break;
    case 4:
        $usu = filter_input(INPUT_POST, "txtUsuario", FILTER_SANITIZE_STRING);
        $senha = filter_input(INPUT_POST, "txtSenha", FILTER_SANITIZE_STRING);
        $permissao = 2;


        $usuario = $usuarioController->AutenticarUsuario($usu, $senha, $permissao);
        if (!empty($usuario)) {
            $_SESSION["cod"] = $usuario->getCod();

            $ex = explode(" ", $usuario->getNome());
            $_SESSION["nome"] = $ex[0];
            echo "ok";
        } else {
            echo "invalid";
        }
        break;
}
?>
