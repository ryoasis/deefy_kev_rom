<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use iutnc\deefy\action\AddPlaylistAction;
use iutnc\deefy\action\DefaultAction;
use iutnc\deefy\action\DisplayPlaylistAction;
use iutnc\deefy\action\MyPlaylistsAction;
use iutnc\deefy\dispatch\Dispatcher;
use iutnc\deefy\repository\DeefyRepository;

session_start();

DeefyRepository::setConfig(__DIR__ . '/config/db.config.ini');

$dispatcher = new Dispatcher();
$dispatcher->addRoute('default', DefaultAction::class);
$dispatcher->addRoute('add-playlist', AddPlaylistAction::class);
$dispatcher->addRoute('playlist', DisplayPlaylistAction::class);
$dispatcher->addRoute('my-playlists', MyPlaylistsAction::class);
$dispatcher->run();