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
global $table_prefix;

define('MOD_NAME', 'paxsuperi');

// paths
define('MOD_ROOT', 'mod/' . MOD_NAME . '/');
define('MOD_ROOT_MODEL', MOD_ROOT . 'Model/');
define('MOD_ROOT_VUE', MOD_ROOT . 'Vue/');
define('MOD_ROOT_XML', MOD_ROOT . 'Xml/');
define('MOD_ROOT_CORE_PAX', MOD_ROOT . 'Core_Pax/');
define('MOD_ROOT_CORE_OGSPY', MOD_ROOT . 'Core_Ogspy/');
define('MOD_ROOT_CORE_CLASS', MOD_ROOT . 'Class/');

// ?toJson=1

// include core pax
include MOD_ROOT_CORE_PAX . 'Constant.php';
include MOD_ROOT_CORE_PAX . 'UrlBuilder.php';
include MOD_ROOT_CORE_PAX . 'XmlManager.php';
include MOD_ROOT_CORE_PAX . 'StepperResponse.php';
include MOD_ROOT_CORE_PAX . 'Setting.php';

include MOD_ROOT_CORE_CLASS . 'AbstractClass.php';
include MOD_ROOT_CORE_CLASS . 'ClassPlayers.php';
include MOD_ROOT_CORE_CLASS . 'ClassAlliances.php';
include MOD_ROOT_CORE_CLASS . 'ClassAlliances_rank.php';
include MOD_ROOT_CORE_CLASS . 'ClassPlayers_rank.php';
include MOD_ROOT_CORE_CLASS . 'ClassUniverse.php';

include MOD_ROOT_CORE_OGSPY . 'Pax_sql_db.php';
include MOD_ROOT_CORE_OGSPY . 'Pax_Model_Abstract.php';
include MOD_ROOT_MODEL . 'Pax_Player_Model.php';
include MOD_ROOT_MODEL . 'Pax_Alliance_Model.php';
include MOD_ROOT_MODEL . 'Pax_Rankings_Player_Model.php';
include MOD_ROOT_MODEL . 'Pax_Rankings_Ally_Model.php';





// include core ogspy ( si OK a integrer dans code source )
// include MOD_ROOT_CORE_OGSPY . 'Pax_mysql.php';

// fn util bypass ogspy


/**
 * formatage_timestamp_for_rank
 *
 * change l'horaire du classement avec un horaire compatible pour un affichage dans ogspy
 *
 * @param int $time timestamp
 * @return int
 */
function formatage_timestamp_for_rank($time)
{
    /// il faut garder le format ogspy ( toutes les 8 heeures ... ) )
    $temp = getdate($time);

    // on format la date
    $temp['seconds'] = 0;
    $temp['minutes'] = 0;
    if ($temp['hours'] >= 0 && $temp['hours'] < 8) {

        $temp['hours'] = 0;
    }
    if ($temp['hours'] >= 8 && $temp['hours'] < 16) {
        $temp['hours'] = 8;
    }
    if ($temp['hours'] >= 16 && $temp['hours'] < 24) {
        $temp['hours'] = 16;
    }

    $time = mktime($temp['hours'], $temp['minutes'], $temp['seconds'], $temp['mon'], $temp['mday'], $temp['year']);
    return $time;
}
