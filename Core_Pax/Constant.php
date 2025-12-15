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

class Constant
{
    public static function getCst(): array
    {
        $tCst = [];
        // ---Players---
        $tCst['CST_PLAYERS'] = ['url' => 'https://s{uni}-{pays}.ogame.gameforge.com/api/players.xml', 'validity' => 24, 'isRank' => false];
        // $tCst['CST_PLAYER_DATA'] = ["url" => "https://s{uni}-{pays}.ogame.gameforge.com/api/playerData.xml?id={id}", "validity" => 0] =>/Non utilisable dans ce contexte
        // ---Alliances---
        $tCst['CST_ALLIANCES'] = ['url' => 'https://s{uni}-{pays}.ogame.gameforge.com/api/alliances.xml', 'validity' => 24, 'isRank' => false];
        // ---Universe---
        $tCst['CST_UNIVERSE'] = ['url' => 'https://s{uni}-{pays}.ogame.gameforge.com/api/universe.xml', 'validity' => 168, 'isRank' => false];
        // ---Rank Players---
        $tCst['CST_PLAYERS_RANK_POINTS']             = ['url' => 'https://s{uni}-{pays}.ogame.gameforge.com/api/highscore.xml?category=1&type=0', 'validity' => 8, 'isRank' => true];
        $tCst['CST_PLAYERS_RANK_ECO']                = ['url' => 'https://s{uni}-{pays}.ogame.gameforge.com/api/highscore.xml?category=1&type=1', 'validity' => 8, 'isRank' => true];
        $tCst['CST_PLAYERS_RANK_TECHNOLOGY']         = ['url' => 'https://s{uni}-{pays}.ogame.gameforge.com/api/highscore.xml?category=1&type=2', 'validity' => 8, 'isRank' => true];
        $tCst['CST_PLAYERS_RANK_MILITARY']           = ['url' => 'https://s{uni}-{pays}.ogame.gameforge.com/api/highscore.xml?category=1&type=3', 'validity' => 8, 'isRank' => true];
        $tCst['CST_PLAYERS_RANK_MILITARY_BUILT']     = ['url' => 'https://s{uni}-{pays}.ogame.gameforge.com/api/highscore.xml?category=1&type=4', 'validity' => 8, 'isRank' => true];
        $tCst['CST_PLAYERS_RANK_MILITARY_DESTROYED'] = ['url' => 'https://s{uni}-{pays}.ogame.gameforge.com/api/highscore.xml?category=1&type=5', 'validity' => 8, 'isRank' => true];
        $tCst['CST_PLAYERS_RANK_MILITARY_LOST']      = ['url' => 'https://s{uni}-{pays}.ogame.gameforge.com/api/highscore.xml?category=1&type=6', 'validity' => 8, 'isRank' => true];
        $tCst['CST_PLAYERS_RANK_MILITARY_HONNOR']    = ['url' => 'https://s{uni}-{pays}.ogame.gameforge.com/api/highscore.xml?category=1&type=7', 'validity' => 8, 'isRank' => true];
        // ---Rank Alliances---
        $tCst['CST_ALLIANCES_RANK_POINTS']             = ['url' => 'https://s{uni}-{pays}.ogame.gameforge.com/api/highscore.xml?category=2&type=0', 'validity' => 8, 'isRank' => true];
        $tCst['CST_ALLIANCES_RANK_ECO']                = ['url' => 'https://s{uni}-{pays}.ogame.gameforge.com/api/highscore.xml?category=2&type=1', 'validity' => 8, 'isRank' => true];
        $tCst['CST_ALLIANCES_RANK_TECHNOLOGY']         = ['url' => 'https://s{uni}-{pays}.ogame.gameforge.com/api/highscore.xml?category=2&type=2', 'validity' => 8, 'isRank' => true];
        $tCst['CST_ALLIANCES_RANK_MILITARY']           = ['url' => 'https://s{uni}-{pays}.ogame.gameforge.com/api/highscore.xml?category=2&type=3', 'validity' => 8, 'isRank' => true];
        $tCst['CST_ALLIANCES_RANK_MILITARY_BUILT']     = ['url' => 'https://s{uni}-{pays}.ogame.gameforge.com/api/highscore.xml?category=2&type=4', 'validity' => 8, 'isRank' => true];
        $tCst['CST_ALLIANCES_RANK_MILITARY_DESTROYED'] = ['url' => 'https://s{uni}-{pays}.ogame.gameforge.com/api/highscore.xml?category=2&type=5', 'validity' => 8, 'isRank' => true];
        $tCst['CST_ALLIANCES_RANK_MILITARY_LOST']      = ['url' => 'https://s{uni}-{pays}.ogame.gameforge.com/api/highscore.xml?category=2&type=6', 'validity' => 8, 'isRank' => true];
        $tCst['CST_ALLIANCES_RANK_MILITARY_HONNOR']    = ['url' => 'https://s{uni}-{pays}.ogame.gameforge.com/api/highscore.xml?category=2&type=7', 'validity' => 8, 'isRank' => true];
        // ---Autres---
        // les validités sont subjectifs
        $tCst['CST_SERVERDATA'] = ['url' => 'https://s{uni}-{pays}.ogame.gameforge.com/api/serverData.xml', 'validity' => 99999, 'isRank' => false];
        $tCst['CST_SERVERS']    = ['url' => 'https://lobby.ogame.gameforge.com/api/servers', 'validity' => 99999, 'isRank' => false];

        return $tCst;
    }



    public static function getEndpoint(): array
    {
        $tEndpoint   = [];
        $tEndpoint[] = 'CST_PLAYERS';
        $tEndpoint[] = 'CST_ALLIANCES';
     //   $tEndpoint[] = 'CST_UNIVERSE';
     //   $tEndpoint[] = 'CST_PLAYERS_RANK_POINTS';
     //   $tEndpoint[] = 'CST_PLAYERS_RANK_ECO';
     //   $tEndpoint[] = 'CST_PLAYERS_RANK_TECHNOLOGY';
     //   $tEndpoint[] = 'CST_PLAYERS_RANK_MILITARY';
     //   $tEndpoint[] = 'CST_PLAYERS_RANK_MILITARY_BUILT';
     //   $tEndpoint[] = 'CST_PLAYERS_RANK_MILITARY_DESTROYED';
     //   $tEndpoint[] = 'CST_PLAYERS_RANK_MILITARY_LOST';
     //   $tEndpoint[] = 'CST_PLAYERS_RANK_MILITARY_HONNOR';
     //   $tEndpoint[] = 'CST_ALLIANCES_RANK_POINTS';
     //   $tEndpoint[] = 'CST_ALLIANCES_RANK_ECO';
     //   $tEndpoint[] = 'CST_ALLIANCES_RANK_TECHNOLOGY';
     //   $tEndpoint[] = 'CST_ALLIANCES_RANK_MILITARY';
     //   $tEndpoint[] = 'CST_ALLIANCES_RANK_MILITARY_BUILT';
     //   $tEndpoint[] = 'CST_ALLIANCES_RANK_MILITARY_DESTROYED';
     //   $tEndpoint[] = 'CST_ALLIANCES_RANK_MILITARY_LOST';
     //   $tEndpoint[] = 'CST_ALLIANCES_RANK_MILITARY_HONNOR';
        // $tEndpoint[] = 'CST_SERVERDATA';
        // $tEndpoint[] = 'CST_SERVERS';

        return $tEndpoint;
    }
}
