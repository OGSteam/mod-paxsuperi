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

include_once 'mod/paxsuperi/common.php';

$install_ogspy = false;
$is_ok         = false;
$mod_folder    = 'paxsuperi';
$root          = 'paxsuperi';
$is_ok         = install_mod($mod_folder);

if ($is_ok) {
    $setting =Setting::getInstance();

    $setting->resetCurrentUse();
    $setting->pays          = 'fr';
    $setting->uni           = '198';
    $setting->temporisation = '1';
    $setting->debug         = '0';
}
