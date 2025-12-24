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
    protected array $requiredFields = ['id', 'ally_id', 'datadate']; // pas d autoincrmente et ally_id ne peut etre null



    public function get_player_count_by_ally()
    {
        $request = "SELECT `ally_id`,COUNT(*) AS nb_joueurs " .
            " FROM " . $this->table;
        $request .= " GROUP BY `ally_id`";
        $result = $this->db->sql_query($request);


        $raw_get_player_count_by_ally = array();
        while ($countData = $this->db->sql_fetch_assoc($result)) {
            $raw_get_player_count_by_ally[$countData['ally_id']] = $countData['nb_joueurs'];
        }

        if (empty($raw_get_player_count_by_ally)) {
            return false;
        }

        return $raw_get_player_count_by_ally;
    }
}
