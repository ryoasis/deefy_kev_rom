DROP TABLE IF EXISTS `playlist2track`;
DROP TABLE IF EXISTS `track`;
DROP TABLE IF EXISTS `playlist`;

CREATE TABLE `playlist` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `nom` varchar(100) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

CREATE TABLE `track` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `titre` varchar(100) NOT NULL,
    `genre` varchar(30) DEFAULT NULL,
    `duree` int(5) NOT NULL DEFAULT 0,
    `filename` varchar(256) DEFAULT NULL,
    `type` varchar(1) NOT NULL,
    `artiste_album` varchar(100) DEFAULT NULL,
    `titre_album` varchar(100) DEFAULT NULL,
    `annee_album` int(4) DEFAULT NULL,
    `numero_album` int(11) DEFAULT NULL,
    `auteur_podcast` varchar(100) DEFAULT NULL,
    `date_podcast` date DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

CREATE TABLE `playlist2track` (
    `id_pl` int(11) NOT NULL,
    `id_track` int(11) NOT NULL,
    `no_piste_dans_liste` int(3) NOT NULL,
    PRIMARY KEY (`id_pl`, `id_track`),
    FOREIGN KEY (`id_pl`) REFERENCES `playlist` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`id_track`) REFERENCES `track` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

INSERT INTO `playlist` (`id`, `nom`) VALUES (1, 'Best of B.B. King'), (2, 'Podcasts du matin');

INSERT INTO `track`
    (`id`, `titre`, `genre`, `duree`, `filename`, `type`, `artiste_album`, `titre_album`, `annee_album`, `numero_album`, `auteur_podcast`, `date_podcast`) VALUES
    (1, 'I''m with You', 'blues', 151, 'assets/audio/01-Im_with_you_BB-King-Lucille.mp3', 'A', 'B.B. King', 'Lucille', 1968, 1, NULL, NULL),
    (2, 'I Need Your Love', 'blues', 143, 'assets/audio/02-I_Need_Your_Love-BB_King-Lucille.mp3', 'A', 'B.B. King', 'Lucille', 1968, 2, NULL, NULL),
    (3, 'Country Girl', 'blues', 264, 'assets/audio/03-Country_Girl-BB_King-Lucille.mp3', 'A', 'B.B. King', 'Lucille', 1968, 3, NULL, NULL),
    (4, 'Le journal de la tech', NULL, 600, NULL, 'P', NULL, NULL, NULL, NULL, 'Jean Dupont', '2026-11-01');

INSERT INTO `playlist2track`
    (`id_pl`, `id_track`, `no_piste_dans_liste`) VALUES
    (1, 1, 1),
    (1, 2, 2),
    (1, 3, 3),
    (2, 4, 1);
