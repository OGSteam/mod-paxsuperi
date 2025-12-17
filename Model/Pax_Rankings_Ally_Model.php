<?php

/**
 * OGSPY - Mod PAx Superi
 *
 * @package [Mod] Pax Superi
 * @author Machine
 * @copyright Copyright &copy; 2016, https://ogsteam.eu/
 * @license https://opensource.org/licenses/gpl-license.php GNU Public License
 */

class Pax_Rankings_Ally_Model extends Pax_Model_Abstract
{
    protected string  $table = TABLE_RANK_ALLY_POINTS;
    protected array $allowedFields = ['rank', 'ally_id', 'points','nb_spacecraft', 'datadate','points_per_member', 'number_member','ally', "sender_id"];
    protected array $requiredFields = ['ally_id', 'datadate'];

    public function setTable(string $table): void
    {
     $allowedTable = [TABLE_RANK_ALLY_POINTS, TABLE_RANK_ALLY_ECO, TABLE_RANK_ALLY_TECHNOLOGY, TABLE_RANK_ALLY_MILITARY, TABLE_RANK_ALLY_MILITARY_BUILT, TABLE_RANK_ALLY_MILITARY_LOOSE, TABLE_RANK_ALLY_MILITARY_DESTRUCT, TABLE_RANK_ALLY_HONOR];

        if (in_array($table, $allowedTable)) {
            $this->table = $table;
        }
    }




}
