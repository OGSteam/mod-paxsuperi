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
 * @global string $table_prefix Prefixe des tables de la base de donnees.
 */
global $table_prefix;

/**
 * @var string Nom du module.
 */
define('MOD_NAME', 'paxsuperi');

// paths
define('MOD_ROOT', 'mod/' . MOD_NAME . '/');
define('MOD_ROOT_MODEL', MOD_ROOT . 'Model/');
define('MOD_ROOT_VUE', MOD_ROOT . 'Vue/');
define('MOD_ROOT_XML', MOD_ROOT . 'Xml/');
define('MOD_ROOT_CORE_PAX', MOD_ROOT . 'Core_Pax/');
define('MOD_ROOT_CORE_OGSPY', MOD_ROOT . 'Core_Ogspy/');
define('MOD_ROOT_CORE_CLASS', MOD_ROOT . 'Class/');

define('TYPE_PLANET', 'planet');
define('TYPE_MOON', 'moon');

// ?toJson=1

// include core pax
include MOD_ROOT_CORE_PAX . 'Container.php';
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
include MOD_ROOT_MODEL . 'Pax_Astro_Object_Model.php';

// Initialisation du container DI
if (! isset($GLOBALS['pax_container'])) {
    $GLOBALS['pax_container'] = new Container();
    
    // Enregistrement des services de base avec leurs interfaces
    $GLOBALS['pax_container']->singleton(SettingInterface::class, fn() => Setting::getInstance());
    $GLOBALS['pax_container']->singleton(Setting::class, fn($c) => $c->make(SettingInterface::class));
}

// include core pax logger
include MOD_ROOT_CORE_PAX . 'Pax_Logger.php';

// Initialisation du logger global
if (! isset($GLOBALS['pax_logger'])) {
    $setting               = $GLOBALS['pax_container']->make(SettingInterface::class);
    $debug                 = isset($setting->debug) ? (bool) $setting->debug : true; // Utilise la valeur de debug depuis la configuration, true par défaut
    $GLOBALS['pax_logger'] = new Pax_Logger($debug);
    
    // Enregistrement du logger dans le container
    $GLOBALS['pax_container']->singleton(LoggerInterface::class, fn() => $GLOBALS['pax_logger']);
    $GLOBALS['pax_container']->singleton(Pax_Logger::class, fn() => $GLOBALS['pax_logger']);
    
    $GLOBALS['pax_container']->bind(XmlManagerInterface::class, fn($c) => new XmlManager(
        $c->make(SettingInterface::class)->pays,
        $c->make(SettingInterface::class)->uni,
        $c
    ));
}

// include core ogspy ( si OK a integrer dans code source )
// include MOD_ROOT_CORE_OGSPY . 'Pax_mysql.php';

// fn util bypass ogspy

/**
 * formatage_timestamp_for_rank
 *
 * change l'horaire du classement avec un horaire compatible pour un affichage dans ogspy
 *
 * @param int $time timestamp
 *
 * @return int
 */
function formatage_timestamp_for_rank($time)
{
    // / il faut garder le format ogspy ( toutes les 8 heeures ... ) )
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

    return mktime($temp['hours'], $temp['minutes'], $temp['seconds'], $temp['mon'], $temp['mday'], $temp['year']);
}
