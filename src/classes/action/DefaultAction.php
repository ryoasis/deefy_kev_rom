<?php

namespace iutnc\deefy\action;

class DefaultAction extends Action
{
    protected function executeGet(): string
    {
        return "<h2>Bienvenue !</h2>\n<p>Deefy, votre gestionnaire de playlists.</p>";
    }
}