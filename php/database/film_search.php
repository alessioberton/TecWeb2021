<?php


class Film_search {

    public $id = -666;
    public $titolo;
    public $lingua_titolo;
    public $anno;
    public $paese;
    public $durata;
    public $trama;
    public $locandina = -666;
    public $voto = 0;
    public $piattaforma = [];
    public $genere = [];
    public $cc;
    public $sdh;
    public $ad;
    public $livello;
    public $eta;
    public $mood;
    public $riconoscimenti;
    public $costo_aggiuntivo;
    public $giorno_entrata;
    public $giorno_uscita;

    public function __construct($array) {
        if ($array) {
            $this->id = $array["ID"];
            $this->titolo = $array["Titolo"];
            $this->lingua_titolo = $array["Lingua_titolo"];
            $this->anno = $array["Anno"];
            $this->paese = $array["Paese"];
            $this->durata = $array["Durata"];
            $this->trama = $array["Trama"];
            $this->locandina = $array["Locandina"];
            $this->voto = $array["Voto"];
            $this->piattaforma = $array["Piattaforma"];
            $this->genere = $array["Genere"];
            $this->cc = $array["CC"];
            $this->sdh = $array["SDH"];
            $this->ad = $array["AD"];
            $this->livello = $array["Livello"];
            $this->eta = $array["Eta"];
            $this->mood = $array["Mood"];
            $this->riconoscimenti = $array["Riconoscimenti"];
            $this->costo_aggiuntivo = $array["costo_aggiuntivo"];
            $this->giorno_entrata = $array["giorno_entrata"];
            $this->giorno_uscita = $array["giorno_uscita"];

        }
    }

}