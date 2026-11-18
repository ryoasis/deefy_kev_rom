<?php

namespace iutnc\deefy\audio\lists;

use iutnc\deefy\exception\InvalidPropertyNameException;

class AudioList
{
    protected ?int $id = null;
    protected int $nbPistes;
    protected int $dureeTotale;

    public function __construct(
        protected readonly string $nom,
        protected array $pistes = []
    ) {
        $this->nbPistes = count($pistes);
        $total = 0;
        foreach ($pistes as $piste) {
            $total += $piste->duree;
        }
        $this->dureeTotale = $total;
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
}