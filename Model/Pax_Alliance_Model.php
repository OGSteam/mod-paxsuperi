<?php

/**
 * OGSPY - Mod PAx Superi
 *
 * @package [Mod] Pax Superi
 * @author Machine
 * @copyright Copyright &copy; 2016, https://ogsteam.eu/
 * @license https://opensource.org/licenses/gpl-license.php GNU Public License
 */

class Pax_Alliance_Model extends Pax_Model_Abstract
{
    protected string  $table = TABLE_GAME_ALLY;
    protected array $allowedFields = ['id', 'name', 'tag', 'class', 'datadate'];
    protected array $requiredFields = ['id']; // pas d autoincrmente et ally_id ne peut etre null

}
