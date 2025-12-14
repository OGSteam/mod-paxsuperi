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
include_once 'mod/paxsuperi/common.php';

require_once 'views/page_header.php';
include MOD_ROOT_VUE . 'page_header_mod.php';

switch ($pub_subaction ?? null) {
    case 'xxx':
        include MOD_ROOT_VUE . 'xxxx.php';
        break;

    default:
        include MOD_ROOT_VUE . 'index.php';
        break;
}

include MOD_ROOT_VUE . 'page_footer_mod.php';
require_once 'views/page_tail.php';
