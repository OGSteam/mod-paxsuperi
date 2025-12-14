<?php

/**
 * OGSPY - Mod PAx Superi
 *
 * @package [Mod] Pax Superi
 * @author Machine
 * @copyright Copyright &copy; 2016, https://ogsteam.eu/
 * @license https://opensource.org/licenses/gpl-license.php GNU Public License
 */

global $db, $root;

if (! defined('IN_SPYOGAME')) {
    exit('Hacking Attempt!');
}

global $de,$table_prefix;
$mod_uninstall_name = 'paxsuperi';

uninstall_mod($mod_uninstall_name);

generate_all_cache();
