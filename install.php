<?php

/**
 * OGSPY - Mod PAx Superi
 *
 * @package [Mod] Pax Superi
 * @author Machine
 * @copyright Copyright &copy; 2016, https://ogsteam.eu/
 * @license https://opensource.org/licenses/gpl-license.php GNU Public License
 */

global $db;

if (! defined('IN_SPYOGAME')) {
    exit('Hacking Attempt!');
}

global $table_prefix;

$install_ogspy = false;
$is_ok         = false;
$mod_folder    = 'paxsuperi';
$root          = 'paxsuperi';
$is_ok         = install_mod($mod_folder);

if ($is_ok) {
    // si besoin de créer des tables, a faire ici
    // Options par défaut.
    mod_set_option('pays', 'fr');
    mod_set_option('uni', '198');
    mod_set_option('temporisation', '3');
    mod_set_option('stepperRunning', '0');
    mod_set_option('currentStep', '0');
    mod_set_option('lastRunning', '0');
    mod_set_option('lastRunningSecurity', '60');
}
