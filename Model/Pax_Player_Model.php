<?php

/**
 * OGSPY - Mod PAx Superi
 *
 * @package [Mod] Pax Superi
 * @author Machine
 * @copyright Copyright &copy; 2016, https://ogsteam.eu/
 * @license https://opensource.org/licenses/gpl-license.php GNU Public License
 */

class Pax_Player_Model extends Pax_Model_Abstract
{
    protected string  $table = TABLE_GAME_PLAYER;
    protected array $allowedFields = ['id', 'name', 'status', 'class', 'ally_id', 'datadate', 'off_commandant', 'off_amiral', 'off_ingenieur', 'off_geologue', 'off_technocrate'];
    protected array $requiredFields = ['id', 'ally_id']; // pas d autoincrmente et ally_id ne peut etre null

}
