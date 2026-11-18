<?php

namespace iutnc\deefy\action;

use iutnc\deefy\repository\DeefyRepository;

class MyPlaylistsAction extends Action
{
    protected function executeGet(): string
    {
        $playlists = DeefyRepository::getInstance()->findAllPlaylists();
        if (count($playlists) === 0) {
            return "<h2>Mes playlists</h2>\n<p>Aucune playlist. <a href=\"?action=add-playlist\">Créer une playlist</a></p>";
        }
        $html = "<h2>Mes playlists</h2>\n<ul>\n";
        foreach ($playlists as $pl) {
            $html .= "<li><a href=\"?action=display-playlist&amp;id={$pl->id}\">{$pl->nom}</a></li>\n";
        }
        return $html . "</ul>";
    }
}