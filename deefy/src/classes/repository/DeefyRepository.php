<?php

namespace iutnc\deefy\repository;

use iutnc\deefy\audio\lists\Playlist;
use iutnc\deefy\audio\tracks\AlbumTrack;
use iutnc\deefy\audio\tracks\PodcastTrack;

class DeefyRepository
{
    private \PDO $pdo;
    private static ?DeefyRepository $instance = null;
    private static array $config = [];

    private function __construct(array $conf)
    {
        $this->pdo = new \PDO(
            $conf['dsn'],
            $conf['user'],
            $conf['pass'],
            [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_EMULATE_PREPARES => false,
                \PDO::ATTR_STRINGIFY_FETCHES => false,
            ]
        );
        $this->pdo->prepare("SET NAMES 'UTF8'")->execute();
    }

    public static function setConfig(string $file): void
    {
        $conf = parse_ini_file($file);
        if ($conf === false) {
            throw new \Exception('Erreur de lecture du fichier de configuration');
        }

        self::$config = [
            'dsn' => "{$conf['driver']}:host={$conf['host']};dbname={$conf['database']};charset=utf8",
            'user' => $conf['username'],
            'pass' => $conf['password'],
        ];
    }

    public static function getInstance(): DeefyRepository
    {
        if (is_null(self::$instance)) {
            self::$instance = new DeefyRepository(self::$config);
        }

        return self::$instance;
    }

    public function saveEmptyPlaylist(Playlist $pl): Playlist
    {
        $stmt = $this->pdo->prepare('INSERT INTO playlist (nom) VALUES (:nom)');
        $stmt->execute(['nom' => $pl->nom]);
        $pl->setId((int) $this->pdo->lastInsertId());

        return $pl;
    }

    public function findAllPlaylists(): array
    {
        $stmt = $this->pdo->query('SELECT id, nom FROM playlist ORDER BY nom');
        $playlists = [];

        while ($row = $stmt->fetch()) {
            $pl = new Playlist($row['nom']);
            $pl->setId((int) $row['id']);
            $playlists[] = $pl;
        }

        return $playlists;
    }

    public function findPlaylistById(int $id): ?Playlist
    {
        $stmt = $this->pdo->prepare('SELECT id, nom FROM playlist WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        if ($row === false) {
            return null;
        }

        $stmt = $this->pdo->prepare(
            'SELECT t.* FROM track t
             INNER JOIN playlist2track p2t ON p2t.id_track = t.id
             WHERE p2t.id_pl = :id
             ORDER BY p2t.no_piste_dans_liste'
        );
        $stmt->execute(['id' => $id]);

        $pistes = [];
        while ($t = $stmt->fetch()) {
            if ($t['type'] === 'A') {
                $piste = new AlbumTrack(
                    $t['titre'],
                    (int) $t['duree'],
                    $t['titre_album'] ?? '',
                    (int) ($t['numero_album'] ?? 0),
                    $t['filename'] ?? ''
                );
            } else {
                $piste = new PodcastTrack(
                    $t['titre'],
                    (int) $t['duree'],
                    $t['auteur_podcast'] ?? '',
                    $t['filename'] ?? ''
                );
            }
            $piste->setId((int) $t['id']);
            $pistes[] = $piste;
        }

        $pl = new Playlist($row['nom'], $pistes);
        $pl->setId((int) $row['id']);

        return $pl;
    }

    public function savePodcastTrack(PodcastTrack $track): PodcastTrack
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO track (titre, duree, filename, type, auteur_podcast, date_podcast)
             VALUES (:titre, :duree, :filename, 'P', :auteur, CURDATE())"
        );
        $stmt->execute([
            'titre' => $track->titre,
            'duree' => $track->duree,
            'filename' => $track->fichier,
            'auteur' => $track->animateur,
        ]);
        $track->setId((int) $this->pdo->lastInsertId());

        return $track;
    }

    public function addTrackToPlaylist(int $trackId, int $playlistId): void
    {
        $stmt = $this->pdo->prepare(
            'SELECT COALESCE(MAX(no_piste_dans_liste), 0) + 1 AS numero
             FROM playlist2track
             WHERE id_pl = :pl'
        );
        $stmt->execute(['pl' => $playlistId]);
        $numero = (int) $stmt->fetch()['numero'];

        $stmt = $this->pdo->prepare(
            'INSERT INTO playlist2track (id_pl, id_track, no_piste_dans_liste)
             VALUES (:pl, :track, :no)'
        );
        $stmt->execute([
            'pl' => $playlistId,
            'track' => $trackId,
            'no' => $numero,
        ]);
    }
}