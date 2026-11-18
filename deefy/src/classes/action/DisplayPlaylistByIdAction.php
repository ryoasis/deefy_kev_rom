<?php

namespace iutnc\deefy\action;

use iutnc\deefy\render\AudioListRenderer;
use iutnc\deefy\repository\DeefyRepository;

class DisplayPlaylistByIdAction extends Action
{
    protected function executeGet(): string
    {
        $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
        if ($id === false || $id === null) {
            return '<p>Identifiant de playlist manquant ou invalide.</p>';
        }

        $playlist = DeefyRepository::getInstance()->findPlaylistById($id);
        if ($playlist === null) {
            return '<p>Playlist introuvable.</p>';
        }

        $_SESSION['playlist'] = serialize($playlist);

        $html = (new AudioListRenderer($playlist))->render();
        return $html . '<p><a href="?action=add-track">Ajouter une piste</a></p>';
    }
}