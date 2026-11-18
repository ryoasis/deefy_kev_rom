<?php

declare(strict_types=1);

require_once __DIR__ . '/src/loader/ClassLoader.php';
$loader = new \loader\ClassLoader('iutnc\\deefy', __DIR__ . '/src/classes');
$loader->register();

use iutnc\deefy\action\AddPlaylistAction;
use iutnc\deefy\action\DefaultAction;
use iutnc\deefy\dispatch\Dispatcher;
use iutnc\deefy\repository\DeefyRepository;

session_start();

DeefyRepository::setConfig(__DIR__ . '/config/db.ini');

$dispatcher = new Dispatcher();
$dispatcher->addRoute('default', DefaultAction::class);
$dispatcher->addRoute('add-playlist', AddPlaylistAction::class);
$dispatcher->run();