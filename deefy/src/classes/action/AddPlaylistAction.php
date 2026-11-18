<?php

namespace iutnc\deefy\action;

use iutnc\deefy\audio\lists\Playlist;
use iutnc\deefy\render\AudioListRenderer;
use iutnc\deefy\repository\DeefyRepository;

class AddPlaylistAction extends Action
{
    protected function executeGet(): string
    {
        return <<<HTML
        <h2>Créer une playlist</h2>
        <form method="post" action="?action=add-playlist">
            <label for="nom">Nom de la playlist</label>
            <input type="text" id="nom" name="nom" maxlength="100" required>
            <button type="submit">Créer</button>
        </form>
        HTML;
    }

    protected function executePost(): string
    {
        $nom = trim($_POST['nom'] ?? '');
        if ($nom === '') {
            return '<p>Le nom de la playlist est obligatoire.</p>' . $this->executeGet();
        }

        $playlist = DeefyRepository::getInstance()->saveEmptyPlaylist(new Playlist($nom));
        $_SESSION['playlist'] = serialize($playlist);

        $html = (new AudioListRenderer($playlist))->render();
        return $html . '<p><a href="?action=add-track">Ajouter une piste</a></p>';
    }
}