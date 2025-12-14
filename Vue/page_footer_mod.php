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

global $db;
// Récupére le numéro de version du mod
$request   = 'SELECT `version` from `' . TABLE_MOD . '` WHERE root=\'paxsuperi\'';
$result    = $db->sql_query($request);
[$version] = $db->sql_fetch_row($result);

echo '<div class="ogspy-mod-footer">';
echo '<p>Pax superi (v.' . $version . ') créé par <i>Machine</i></p>';
echo '</div>';
