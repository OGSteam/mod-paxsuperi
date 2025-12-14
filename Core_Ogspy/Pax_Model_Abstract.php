<?php

/**
 * OGSPY - Mod PAx Superi
 *
 * @package [Mod] Pax Superi
 * @author Machine
 * @copyright Copyright &copy; 2016, https://ogsteam.eu/
 * @license https://opensource.org/licenses/gpl-license.php GNU Public License
 */

use Ogsteam\Ogspy\Abstracts\Model_Abstract;

if (! defined('IN_SPYOGAME')) {
    exit('Hacking attempt');
}

abstract class Pax_Model_Abstract extends Model_Abstract
{
    public function __construct()
    {
        global $db_host, $db_user, $db_password, $db_database;
        parent::__construct();
        $this->db = Pax_sql_db::getInstance($db_host, $db_user, $db_password, $db_database);
    }
}
