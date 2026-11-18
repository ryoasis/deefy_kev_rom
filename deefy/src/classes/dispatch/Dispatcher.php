<?php

namespace iutnc\deefy\dispatch;

use iutnc\deefy\action\DefaultAction;

class Dispatcher
{
    private string $action;
    private array $routes = [];

    public function __construct()
    {
        $this->action = $_GET['action'] ?? 'default';
    }

    public function addRoute(string $action, string $class): self
    {
        $this->routes[$action] = $class;
        return $this;
    }

    public function run(): void
    {
        $class = $this->routes[$this->action] ?? DefaultAction::class;
        try {
            $html = (new $class())->execute();
        } catch (\PDOException $e) {
            $html = "<p>Erreur d'accès à la base de données.</p>";
        } catch (\Exception $e) {
            $html = "<p>Une erreur est survenue.</p>";
        }
        $this->renderPage($html);
    }

    private function renderPage(string $html): void
    {
        $page = <<<HTML
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>Deefy</title>
        </head>
        <body>
            <header>
                <h1><a href="?action=default">Deefy</a></h1>
                <nav>
                    <ul>
                        <li><a href="?action=default">Accueil</a></li>
                        <li><a href="?action=my-playlists">Mes playlists</a></li>
                        <li><a href="?action=add-playlist">Créer une playlist</a></li>
                        <li><a href="?action=playlist">Playlist courante</a></li>
                    </ul>
                </nav>
            </header>
            <main>
        $html
            </main>
        </body>
        </html>
        HTML;
        echo $page;
    }
}