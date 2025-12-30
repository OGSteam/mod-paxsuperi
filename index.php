<?php

/**
 * OGSPY - Mod PAx Superi
 *
 * @package [Mod] Pax Superi
 * @author Machine
 * @copyright Copyright &copy; 2016, https://ogsteam.eu/
 * @license https://opensource.org/licenses/gpl-license.php GNU Public License
 *
 * @category   PaxSuperi
 * @package    Main
 *
 * @description
 * Point d'entree principal du module PaxSuperi.
 * Ce fichier gere les requetes utilisateur et charge les vues appropriees.
 */

if (! defined('IN_SPYOGAME')) {
    exit('Hacking Attempt!');
}

/**
 * Inclusion du fichier commun qui contient les definitions et les inclusions
 * necessaires pour le fonctionnement du module.
 */
include_once 'mod/paxsuperi/common.php';

global $pax_logger;
$pax_logger->info('Chargement de la page principale de PaxSuperi');

require_once 'views/page_header.php';
include MOD_ROOT_VUE . 'page_header_mod.php';
include MOD_ROOT_VUE . 'page_menu_mod.php';

$setting = Setting::getInstance();
$pax_logger->debug('Paramètres chargés: uni=' . $setting->uni . ', pays=' . $setting->pays);

/// formulaire admin
if (isset($pub_admin) && $pub_admin == "1") {
    //univers
    if (isset($pub_uni)) {
        $setting->uni = (int) ($pub_uni);
    }
    //univers
    if (isset($pub_pays) && strlen($pub_pays) < 4) {
        $setting->pays = $pub_pays;
    }
    if (isset($pub_temporisation)) {
        $pub_temporisation = (int)$pub_temporisation > 3 ? 3 : (int)$pub_temporisation; // inf a 3 s
        $pub_temporisation = (int)$pub_temporisation < 1 ? 1 : (int)$pub_temporisation; // sup a 1 s
        $setting->temporisation = (int)$pub_temporisation;
    }
}



switch ($pub_subaction ?? null) {
    case 'admin':
        include MOD_ROOT_VUE . 'admin.php';
        break;
    case 'state':
        include MOD_ROOT_VUE . 'state.php';
        break;
    case 'paxsuperi':
        include MOD_ROOT_VUE . 'index.php';
        break;

    default:
        include MOD_ROOT_VUE . 'index.php';
        break;
}

include MOD_ROOT_VUE . 'page_footer_mod.php';
require_once 'views/page_tail.php';
