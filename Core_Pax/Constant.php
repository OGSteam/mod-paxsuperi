<?php

/**
 * OGSPY - Mod PAx Superi
 *
 * @package [Mod] Pax Superi
 * @author Machine
 * @copyright Copyright &copy; 2016, https://ogsteam.eu/
 * @license https://opensource.org/licenses/gpl-license.php GNU Public License
 */

if (! defined('IN_SPYOGAME')) {
    exit('Hacking attempt');
}

/**
 * Classe pour la gestion des constantes.
 *
 * Cette classe fournit des methodes pour recuperer les constantes
 * utilisees dans le module PaxSuperi, telles que les URLs de l'API
 * d'OGame et les parametres de validite.
 *
 * @category   PaxSuperi
 */
class Constant
{
    /**
     * Recupere les constantes du module.
     *
     * Cette methode retourne un tableau associatif contenant les constantes
     * utilisees dans le module, telles que les URLs de l'API d'OGame et les
     * parametres de validite.
     *
     * @return array Tableau associatif des constantes.
     */
    public static function getCst(): array
    {
        return [
            // ---Players---
            'CST_PLAYERS' => ['url' => 'https://s{uni}-{pays}.ogame.gameforge.com/api/players.xml', 'validity' => 24, 'isRank' => false],
            // ---Alliances---
            'CST_ALLIANCES' => ['url' => 'https://s{uni}-{pays}.ogame.gameforge.com/api/alliances.xml', 'validity' => 24, 'isRank' => false],
            // ---Universe---
            'CST_UNIVERSE' => ['url' => 'https://s{uni}-{pays}.ogame.gameforge.com/api/universe.xml', 'validity' => 168, 'isRank' => false],
            // ---Rank Players---
            'CST_PLAYERS_RANK_POINTS'             => ['url' => 'https://s{uni}-{pays}.ogame.gameforge.com/api/highscore.xml?category=1&type=0', 'validity' => 8, 'isRank' => true],
            'CST_PLAYERS_RANK_ECO'                => ['url' => 'https://s{uni}-{pays}.ogame.gameforge.com/api/highscore.xml?category=1&type=1', 'validity' => 8, 'isRank' => true],
            'CST_PLAYERS_RANK_TECHNOLOGY'         => ['url' => 'https://s{uni}-{pays}.ogame.gameforge.com/api/highscore.xml?category=1&type=2', 'validity' => 8, 'isRank' => true],
            'CST_PLAYERS_RANK_MILITARY'           => ['url' => 'https://s{uni}-{pays}.ogame.gameforge.com/api/highscore.xml?category=1&type=3', 'validity' => 8, 'isRank' => true],
            'CST_PLAYERS_RANK_MILITARY_BUILT'     => ['url' => 'https://s{uni}-{pays}.ogame.gameforge.com/api/highscore.xml?category=1&type=4', 'validity' => 8, 'isRank' => true],
            'CST_PLAYERS_RANK_MILITARY_DESTROYED' => ['url' => 'https://s{uni}-{pays}.ogame.gameforge.com/api/highscore.xml?category=1&type=5', 'validity' => 8, 'isRank' => true],
            'CST_PLAYERS_RANK_MILITARY_LOST'      => ['url' => 'https://s{uni}-{pays}.ogame.gameforge.com/api/highscore.xml?category=1&type=6', 'validity' => 8, 'isRank' => true],
            'CST_PLAYERS_RANK_MILITARY_HONNOR'    => ['url' => 'https://s{uni}-{pays}.ogame.gameforge.com/api/highscore.xml?category=1&type=7', 'validity' => 8, 'isRank' => true],
            // ---Rank Alliances---
            'CST_ALLIANCES_RANK_POINTS'             => ['url' => 'https://s{uni}-{pays}.ogame.gameforge.com/api/highscore.xml?category=2&type=0', 'validity' => 8, 'isRank' => true],
            'CST_ALLIANCES_RANK_ECO'                => ['url' => 'https://s{uni}-{pays}.ogame.gameforge.com/api/highscore.xml?category=2&type=1', 'validity' => 8, 'isRank' => true],
            'CST_ALLIANCES_RANK_TECHNOLOGY'         => ['url' => 'https://s{uni}-{pays}.ogame.gameforge.com/api/highscore.xml?category=2&type=2', 'validity' => 8, 'isRank' => true],
            'CST_ALLIANCES_RANK_MILITARY'           => ['url' => 'https://s{uni}-{pays}.ogame.gameforge.com/api/highscore.xml?category=2&type=3', 'validity' => 8, 'isRank' => true],
            'CST_ALLIANCES_RANK_MILITARY_BUILT'     => ['url' => 'https://s{uni}-{pays}.ogame.gameforge.com/api/highscore.xml?category=2&type=4', 'validity' => 8, 'isRank' => true],
            'CST_ALLIANCES_RANK_MILITARY_DESTROYED' => ['url' => 'https://s{uni}-{pays}.ogame.gameforge.com/api/highscore.xml?category=2&type=5', 'validity' => 8, 'isRank' => true],
            'CST_ALLIANCES_RANK_MILITARY_LOST'      => ['url' => 'https://s{uni}-{pays}.ogame.gameforge.com/api/highscore.xml?category=2&type=6', 'validity' => 8, 'isRank' => true],
            'CST_ALLIANCES_RANK_MILITARY_HONNOR'    => ['url' => 'https://s{uni}-{pays}.ogame.gameforge.com/api/highscore.xml?category=2&type=7', 'validity' => 8, 'isRank' => true],
            // ---Autres---
            'CST_SERVERDATA' => ['url' => 'https://s{uni}-{pays}.ogame.gameforge.com/api/serverData.xml', 'validity' => 99999, 'isRank' => false],
            'CST_SERVERS'    => ['url' => 'https://lobby.ogame.gameforge.com/api/servers', 'validity' => 99999, 'isRank' => false],
        ];
    }

    /**
     * Recupere la liste des endpoints actifs pour le stepper.
     *
     * @return array Liste des noms de constantes.
     */
    public static function getEndpoint(): array
    {
        return [
            'CST_PLAYERS',
            'CST_ALLIANCES',
            'CST_UNIVERSE',
            'CST_PLAYERS_RANK_POINTS',
            'CST_PLAYERS_RANK_ECO',
            'CST_PLAYERS_RANK_TECHNOLOGY',
            'CST_PLAYERS_RANK_MILITARY',
            'CST_PLAYERS_RANK_MILITARY_BUILT',
            'CST_PLAYERS_RANK_MILITARY_DESTROYED',
            'CST_PLAYERS_RANK_MILITARY_LOST',
            'CST_PLAYERS_RANK_MILITARY_HONNOR',
            'CST_ALLIANCES_RANK_POINTS',
            'CST_ALLIANCES_RANK_ECO',
            'CST_ALLIANCES_RANK_TECHNOLOGY',
            'CST_ALLIANCES_RANK_MILITARY',
            'CST_ALLIANCES_RANK_MILITARY_BUILT',
            'CST_ALLIANCES_RANK_MILITARY_DESTROYED',
            'CST_ALLIANCES_RANK_MILITARY_LOST',
            'CST_ALLIANCES_RANK_MILITARY_HONNOR',
        ];
    }
}
