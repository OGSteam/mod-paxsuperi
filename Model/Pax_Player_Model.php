<?php

/**
 * OGSPY - Mod PAx Superi
 *
 * @package [Mod] Pax Superi
 * @author Machine
 * @copyright Copyright &copy; 2016, https://ogsteam.eu/
 * @license https://opensource.org/licenses/gpl-license.php GNU Public License
 */

/**
 * Modele pour la gestion des joueurs.
 * 
 * Ce modele herite de Pax_Model_Abstract et fournit des methodes pour
 * interagir avec la table des joueurs dans la base de donnees.
 *
 * @category   PaxSuperi
 * @package    Model
 * @subpackage Player
 */
class Pax_Player_Model extends Pax_Model_Abstract
{
    /**
     * @var string Nom de la table des joueurs dans la base de donnees.
     */
    protected string  $table = TABLE_GAME_PLAYER;
    
    /**
     * @var array Liste des champs autorises pour les joueurs.
     * 
     * Les champs autorises sont : id, name, status, class, ally_id, datadate,
     * off_commandant, off_amiral, off_ingenieur, off_geologue, off_technocrate.
     */
    protected array $allowedFields = ['id', 'name', 'status', 'class', 'ally_id', 'datadate', 'off_commandant', 'off_amiral', 'off_ingenieur', 'off_geologue', 'off_technocrate'];
    
    /**
     * @var array Liste des champs requis pour les joueurs.
     * 
     * Les champs requis sont : id, ally_id, datadate.
     * Ces champs ne peuvent pas etre null et n'ont pas d'auto-increment.
     */
    protected array $requiredFields = ['id', 'ally_id', 'datadate']; // pas d autoincrmente et ally_id ne peut etre null



    public function get_player_count_by_ally()
    {
        global $pax_logger;
        $pax_logger->info('Récupération du nombre de joueurs par alliance');
        
        $request = "SELECT `ally_id`,COUNT(*) AS nb_joueurs " .
            " FROM " . $this->table;
        $request .= " GROUP BY `ally_id`";
        $result = $this->db->sql_query($request);
        
        if (!$result) {
            $pax_logger->error('Erreur lors de l\'exécution de la requête pour récupérer le nombre de joueurs par alliance');
            return false;
        }

        $raw_get_player_count_by_ally = array();
        while ($countData = $this->db->sql_fetch_assoc($result)) {
            $raw_get_player_count_by_ally[$countData['ally_id']] = $countData['nb_joueurs'];
        }

        if (empty($raw_get_player_count_by_ally)) {
            $pax_logger->warning('Aucun joueur trouvé pour les alliances');
            return false;
        }
        
        $pax_logger->info('Nombre de joueurs par alliance récupéré avec succès');
        return $raw_get_player_count_by_ally;
    }
}
