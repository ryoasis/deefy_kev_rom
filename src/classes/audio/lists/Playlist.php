<?php

namespace iutnc\deefy\audio\lists;

use iutnc\deefy\audio\tracks\AudioTrack;

class Playlist extends AudioList
{
    public function ajouterPiste(AudioTrack $piste): void
    {
        $this->pistes[] = $piste;
        $this->nbPistes++;
        $this->dureeTotale += $piste->duree;
    }

    public function supprimerPiste(int $indice): void
    {
        if (!isset($this->pistes[$indice])) {
            return;
        }
        $this->dureeTotale -= $this->pistes[$indice]->duree;
        unset($this->pistes[$indice]);
        $this->nbPistes--;
    }

    public function ajouterListe(array $pistes): void
    {
        foreach ($pistes as $piste) {
            $dejaPresente = false;
            foreach ($this->pistes as $existante) {
                if ($existante === $piste) {
                    $dejaPresente = true;
                    break;
                }
            }
            if (!$dejaPresente) {
                $this->ajouterPiste($piste);
            }
        }
    }
}