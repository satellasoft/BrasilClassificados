<?php

if (file_exists("../DAL/UsuarioDAO.php")) {
    require_once("../DAL/UsuarioDAO.php");
} else {
    require_once("DAL/UsuarioDAO.php");
}

class UsuarioController {

    private $usuarioDAO;

    public function __construct() {
        $this->usuarioDAO = new UsuarioDAO();
    }

    public function Cadastrar(Usuario $usuario) {
        if (strlen($usuario->getNome()) >= 5 && strlen($usuario->getUsuario()) >= 7 && strlen($usuario->getSenha()) >= 7 && strpos($usuario->getEmail(), "@") && strpos($usuario->getEmail(), ".") &&
                strlen($usuario->getCpf()) == 14 && $usuario->getSexo() != "" && $usuario->getPermissao() >= 1 && $usuario->getPermissao() <= 2 && $usuario->getStatus() >= 1 && $usuario->getStatus() <= 2) {

            return $this->usuarioDAO->Cadastrar($usuario);
        } else {
            return false;
        }
    }

    public function Alterar(Usuario $usuario) {
        if (strlen($usuario->getNome()) >= 5 && strlen($usuario->getUsuario()) >= 7 && strpos($usuario->getEmail(), "@") && strpos($usuario->getEmail(), ".") &&
                strlen($usuario->getCpf()) == 14 && $usuario->getSexo() != "" && $usuario->getPermissao() >= 1 && $usuario->getPermissao() <= 2 && $usuario->getStatus() >= 1 && $usuario->getStatus() <= 2) {

            return $this->usuarioDAO->Alterar($usuario);
        } else {
            return false;
        }
    }

    public function RetornarUsuarios(string $termo, int $tipo) {
        if ($termo != "" && $tipo >= 1 && $tipo <= 4) {
            return $this->usuarioDAO->RetornarUsuarios($termo, $tipo);
        } else {
            return null;
        }
    }

    public function RetornaCod(int $usuarioCod) {
        if ($usuarioCod > 0) {
            return $this->usuarioDAO->RetornaCod($usuarioCod);
        } else {
            return null;
        }
    }

    public function AutenticarUsuario(string $usu, string $senha, int $permissao) {

        if (strlen($usu) >= 7 && strlen($senha) >= 7 && $permissao > 0) {
            $senha = md5($senha);
            return $this->usuarioDAO->AutenticarUsuario($usu, $senha, $permissao);
        } else {
            return null;
        }
    }

    public function AlterarSenha(string $senha, int $cod) {
        if (strlen($senha) >= 7 && $cod > 0) {
            return $this->usuarioDAO->AlterarSenha($senha, $cod);
        } else {
            return false;
        }
    }

    public function VerificaUsuarioExiste(string $user) {
        if (strlen($user) >= 3) {
            return $this->usuarioDAO->VerificaUsuarioExiste($user);
        } else {
            -10;
        }
    }

    public function VerificaEmailExiste(string $email) {
        if (strpos($email, "@") > 0 && strpos($email, ".") > 0) {
            return $this->usuarioDAO->VerificaEmailExiste($email);
        } else {
            -10;
        }
    }

    public function VerificaCPFExiste(string $cpf) {
        if (strlen($cpf) == 14) {
            return $this->usuarioDAO->VerificaCPFExiste($cpf);
        } else {
            -10;
        }
    }

}
