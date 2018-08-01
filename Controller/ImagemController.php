<?php

if (file_exists("../DAL/ImagemDAO.php")) {
    require_once("../DAL/ImagemDAO.php");
} else {
    require_once("DAL/ImagemDAO.php");
}

class ImagemController {

    private $imagemDAO;

    function __construct() {
        $this->imagemDAO = new ImagemDAO();
    }

    public function CadastrarImagens(array $imagem) {
        if ($imagem != null) {
            return $this->imagemDAO->CadastrarImagens($imagem);
        } else {
            return false;
        }
    }

    public function CarregarImagensClassificado(int $classificadoCod) {
        if ($classificadoCod > 0) {
            return $this->imagemDAO->CarregarImagensClassificado($classificadoCod);
        } else {
            return null;
        }
    }

    public function VerificarArquivoExiste(int $classificadoCod, int $imagemCod) {
        if ($classificadoCod > 0 && $imagemCod > 0) {
            return $this->imagemDAO->VerificarArquivoExiste($classificadoCod, $imagemCod);
        } else {
            return null;
        }
    }

    public function RemoverImagem(int $classificadoCod, int $imagemCod) {
        if ($classificadoCod > 0 && $imagemCod > 0) {
            return $this->imagemDAO->RemoverImagem($classificadoCod, $imagemCod);
        } else {
            return false;
        }
    }

    public function RetornarImagensClassificado(int $classificado, int $usuario) {
        if ($classificado > 0 && $usuario > 0) {
            return $this->imagemDAO->RetornarImagensClassificado($classificado, $usuario);
        } else {
            return null;
        }
    }

    public function RetornarImagensClassificadoResumida(int $classificado) {
        if ($classificado > 0) {
            return $this->imagemDAO->RetornarImagensClassificadoResumida($classificado);
        } else {
            return null;
        }
    }

}
