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

    public function LoadFile($path, $type, $file, $renameFile = true) {
        $fl = $file['type'];
        $validFormat = false;

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

        if ($validFormat) {
            $finalName = "";
            if ($renameFile) {
                $explode = explode(".", $file['name']);
                $finalName = md5(time()) . "." . $explode[(count($explode) - 1)];
            } else {
                $finalName = $file['name'];
            }

            if (move_uploaded_file($file['tmp_name'], $path . "/" . $finalName)) {
                return $finalName;
            } else {
                return "";
            }
        } else {
            return "invalid";
        }
    }

}

?>