<?php
$abs_path = $_SERVER["DOCUMENT_ROOT"]."/TecWeb2021/php/";


class Film {

    public $id = -666;
    public $titolo;
    public $lingua_titolo;
    public $anno;
    public $paese;
    public $durata;
    public $trama;
    public $locandina = -666;
    public $piattaforme;

    public function __construct($array) {
        $this->id = $array["ID"];
        $this->titolo = $array["Titolo"];
        $this->lingua_titolo = $array["Lingua_titolo"];
        $this->anno = $array["Anno"];
        $this->paese = $array["Paese"];
        $this->durata = $array["Durata"];
        $this->trama = $array["Trama"];
        $this->locandina = $array["Locandina"];
        $this->piattaforme = $array["Piattaforme"];
    }


//    public static function getInstance() {
//        if (!isset($_SESSION['proposal'])) {
//            $_SESSION['proposal'] = new Film();
//        }
//
//        return $_SESSION['proposal'];
//    }
}