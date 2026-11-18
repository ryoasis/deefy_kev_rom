<?php

namespace iutnc\deefy\action;

use iutnc\deefy\audio\tracks\PodcastTrack;
use iutnc\deefy\render\AudioListRenderer;
use iutnc\deefy\repository\DeefyRepository;

class AddPodcastTrackAction extends Action
{
    protected function executeGet(): string
    {
        if (!isset($_SESSION['playlist'])) {
            return '<p>Aucune playlist courante. <a href="?action=my-playlists">Choisir une playlist</a></p>';
        }

        return <<<HTML
        <h2>Ajouter une piste</h2>
        <form method="post" action="?action=add-track">
            <label for="titre">Titre</label>
            <input type="text" id="titre" name="titre" maxlength="100" required>
            <label for="auteur">Auteur</label>
            <input type="text" id="auteur" name="auteur" maxlength="100" required>
            <label for="duree">Durée (secondes)</label>
            <input type="number" id="duree" name="duree" min="0" required>
            <button type="submit">Ajouter</button>
        </form>
        HTML;
    }

    protected function executePost(): string
    {
        if (!isset($_SESSION['playlist'])) {
            return $this->executeGet();
        }

        $titre = trim($_POST['titre'] ?? '');
        $auteur = trim($_POST['auteur'] ?? '');
        $duree = filter_var($_POST['duree'] ?? '', FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]);

        if ($titre === '' || $auteur === '' || $duree === false) {
            return '<p>Données invalides.</p>' . $this->executeGet();
        }

        $repo = DeefyRepository::getInstance();
        $playlistId = unserialize($_SESSION['playlist'])->id;

        $track = $repo->savePodcastTrack(new PodcastTrack($titre, $duree, $auteur));
        $repo->addTrackToPlaylist($track->id, $playlistId);

        $playlist = $repo->findPlaylistById($playlistId);
        $_SESSION['playlist'] = serialize($playlist);

        $html = (new AudioListRenderer($playlist))->render();
        return $html . '<p><a href="?action=add-track">Ajouter encore une piste</a></p>';
    }
}