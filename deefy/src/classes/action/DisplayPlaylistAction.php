<?php

namespace iutnc\deefy\action;

use iutnc\deefy\render\AudioListRenderer;

class DisplayPlaylistAction extends Action
{
    protected function executeGet(): string
    {
        if (!isset($_SESSION['playlist'])) {
            return "<p>Aucune playlist courante. <a href=\"?action=my-playlists\">Choisir une playlist</a></p>";
        }
        $playlist = unserialize($_SESSION['playlist']);
        $html = (new AudioListRenderer($playlist))->render();
        return $html . '<p><a href="?action=add-track">Ajouter une piste</a></p>';
    }
}