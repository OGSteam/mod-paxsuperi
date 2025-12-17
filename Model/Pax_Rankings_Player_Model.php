<?php

/**
 * OGSPY - Mod PAx Superi
 *
 * @package [Mod] Pax Superi
 * @author Machine
 * @copyright Copyright &copy; 2016, https://ogsteam.eu/
 * @license https://opensource.org/licenses/gpl-license.php GNU Public License
 */

class Pax_Rankings_Player_Model extends Pax_Model_Abstract
{
    protected string  $table = TABLE_RANK_PLAYER_POINTS;
    protected array $allowedFields = ['rank', 'player_id', 'points','nb_spacecraft', 'datadate',  "sender_id"];
    protected array $requiredFields = ['player_id', 'datadate'];

    public function setTable(string $table): void
    {
        $allowedTable = [TABLE_RANK_PLAYER_POINTS, TABLE_RANK_PLAYER_ECO, TABLE_RANK_PLAYER_TECHNOLOGY, TABLE_RANK_PLAYER_MILITARY, TABLE_RANK_PLAYER_MILITARY_BUILT, TABLE_RANK_PLAYER_MILITARY_LOOSE, TABLE_RANK_PLAYER_MILITARY_DESTRUCT, TABLE_RANK_PLAYER_HONOR];

        if (in_array($table, $allowedTable)) {
            $this->table = $table;
        }
    }




}
