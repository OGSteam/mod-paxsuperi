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
    exit('Hacking Attempt!');
}
global $db;

$mod_folder = 'paxsuperi';
$mod_name   = 'paxsuperi';

update_mod($mod_folder, $mod_name);
generate_all_cache();
