<?php

namespace iutnc\deefy\audio\tracks;

class AlbumTrack extends AudioTrack
{
    public function __construct(
        string $titre,
        int $duree,
        protected readonly string $album,
        protected readonly int $numero,
        string $fichier = ''
    ) {
        parent::__construct($titre, $duree, $fichier);
    }

    public function afficherCompact(): string
    {
        return "{$this->numero}. {$this->titre} - {$this->album} ({$this->duree}s)";
    }
}