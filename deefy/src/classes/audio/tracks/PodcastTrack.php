<?php

namespace iutnc\deefy\audio\tracks;

class PodcastTrack extends AudioTrack
{
    public function __construct(
        string $titre,
        int $duree,
        protected readonly string $animateur,
        string $fichier = ''
    ) {
        parent::__construct($titre, $duree, $fichier);
    }

    public function afficherCompact(): string
    {
        return "{$this->titre} avec {$this->animateur} ({$this->duree}s)";
    }
}