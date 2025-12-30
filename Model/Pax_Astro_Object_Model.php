<?php

/**
 * OGSPY - Mod PAx Superi
 *
 * @package [Mod] Pax Superi
 * @author Machine
 * @copyright Copyright &copy; 2016, https://ogsteam.eu/
 * @license https://opensource.org/licenses/gpl-license.php GNU Public License
 */

class Pax_Astro_Object_Model extends Pax_Model_Abstract
{
    protected string  $table = TABLE_USER_BUILDING;
    protected array $allowedFields = ['id', 'type', 'galaxy', 'system', 'row', 'player_id', 'last_update', 'name'
    ];
    protected array $requiredFields = ['id', 'type', 'galaxy', 'system', 'row', 'player_id', 'last_update', 'name'];

   

    
}