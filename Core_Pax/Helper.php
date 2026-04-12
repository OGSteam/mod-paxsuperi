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
 * Classe utilitaire pour le module PaxSuperi.
 *
 * @category   PaxSuperi
 */
class Helper
{
    /**
     * Formate un timestamp pour être compatible avec l'affichage des classements OGSpy (toutes les 8 heures).
     *
     * @param int $time Le timestamp à formater.
     *
     * @return int Le timestamp formaté.
     */
    public static function formatageTimestampForRank(int $time): int
    {
        $temp = getdate($time);

        // On formate la date pour correspondre aux créneaux OGSpy (0h, 8h, 16h)
        $temp['seconds'] = 0;
        $temp['minutes'] = 0;

        if ($temp['hours'] >= 0 && $temp['hours'] < 8) {
            $temp['hours'] = 0;
        } elseif ($temp['hours'] >= 8 && $temp['hours'] < 16) {
            $temp['hours'] = 8;
        } else {
            $temp['hours'] = 16;
        }

        return mktime($temp['hours'], $temp['minutes'], $temp['seconds'], $temp['mon'], $temp['mday'], $temp['year']);
    }
}
