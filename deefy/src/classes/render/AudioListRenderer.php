<?php

namespace iutnc\deefy\render;

use iutnc\deefy\audio\lists\AudioList;

class AudioListRenderer implements Renderer
{
    public function __construct(protected readonly AudioList $list)
    {
    }

    public function render(): string
    {
        $html = "<section class=\"audio-list\">\n";
        $html .= "<h2>{$this->list->nom}</h2>\n";
        if ($this->list->nbPistes === 0) {
            $html .= "<p>Aucune piste.</p>\n";
        } else {
            $html .= "<ol>\n";
            foreach ($this->list->pistes as $piste) {
                $html .= "<li>{$piste->afficherCompact()}";
                if ($piste->fichier !== '') {
                    $html .= "<br><audio controls src=\"{$piste->fichier}\"></audio>";
                }
                $html .= "</li>\n";
            }
            $html .= "</ol>\n";
        }
        $html .= "<p>{$this->list->nbPistes} piste(s) - {$this->list->dureeTotale}s</p>\n";
        $html .= "</section>\n";
        return $html;
    }
}