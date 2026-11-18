<?php

namespace iutnc\deefy\audio\tracks;

use iutnc\deefy\exception\InvalidPropertyNameException;
use iutnc\deefy\exception\InvalidPropertyValueException;

class AudioTrack
{
    protected ?int $id = null;

    public function __construct(
        protected readonly string $titre,
        protected readonly int $duree,
        protected readonly string $fichier = ''
    ) {
        if ($duree < 0) {
            throw new InvalidPropertyValueException('invalid property : duree');
        }
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function __get(string $name): mixed
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        }
        throw new InvalidPropertyNameException("invalid property : $name");
    }

    public function afficherCompact(): string
    {
        return "{$this->titre} ({$this->duree}s)";
    }
}