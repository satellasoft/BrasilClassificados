<?php

class Upload {

    private $arrFormatImages;
    private $arrFormatFiles;

    public function __construct() {
        $this->arrFormatImages = array(
            "image/jpeg",
            "image/png"
        );

        $this->arrFormatFiles = array(
            "application/x-compressed",
            "application/x-zip-compressed",
            "application/zip",
            "multipart/x-zip",
            "application/x-compressed",
            "application/x-gzip",
            "text/html",
            "application/octet-stream",
            "application/x-gzip",
            "multipart/x-gzip",
            "application/msword"
        );
    }

    /*
     * Types: 
     * img  - Is image files, all array formats 'arrFormatImages' images are accepted
     * file - Is compressed files, all array formats 'arrFormatFiles' are accepted.
     */

    public function ValidaImagens($file, $type, $maxSize, $maxItem) {
        $invalidFormat = 0;

        if (count($file["name"]) > $maxItem) { //Valida a quantidade de arquivos enviado.
            return false;
        }

        for ($i = 0; $i < count($file["name"]); $i++) {
            $validFormat = false;
            $fl = $file['type'][$i];

            if ($type == "img") {
                foreach ($this->arrFormatImages as $result) {
                    if ($fl == $result) {
                        $validFormat = true;
                    }
                }
            }

            if ($type == "file") {
                foreach ($this->arrFormatFiles as $result) {
                    if ($fl == $result) {
                        $validFormat = true;
                    }
                }
            }

            if (!$validFormat) {
                $invalidFormat++;
            } else {
                if ($file['size'][$i] > (($maxSize * 1024) * 1024)) { //Validamos o valor do arquivo
                    $invalidFormat++;
                }
            }
        }

        if ($invalidFormat === 0) {
            return true;
        } else {
            return false;
        }
    }

    public function LoadFile($path, $file, $renameFile = true) {

        $arrayNames = [];
        $finalName = "";

        for ($i = 0; $i < count($file["name"]); $i++) {
            if ($renameFile) {
                $explode = explode(".", $file['name'][$i]);
                $finalName = md5(time()) . "{$i}." . $explode[(count($explode) - 1)];
            } else {
                $finalName = $file['name'][$i];
            }


            if (move_uploaded_file($file['tmp_name'][$i], $path . "/" . $finalName)) {
                $arrayNames[] = $finalName;
            }
        }
        return $arrayNames;
    }

}

?>