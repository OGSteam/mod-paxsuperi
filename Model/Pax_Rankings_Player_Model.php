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
 * Modele pour la gestion des classements des joueurs.
 * 
 * Ce modele herite de Pax_Model_Abstract et fournit des methodes pour
 * interagir avec les tables des classements des joueurs dans la base de donnees.
 *
 * @category   PaxSuperi
 * @package    Model
 * @subpackage Rankings_Player
 *
 * @example
 * // Creer une instance du modele
 * $rankingsPlayerModel = new Pax_Rankings_Player_Model();
 * 
 * // Changer la table pour les classements economiques
 * $rankingsPlayerModel->setTable(TABLE_RANK_PLAYER_ECO);
 * 
 * // Enregistrer un classement
 * $rankingData = ['rank' => 1, 'player_id' => 1, 'points' => 1000, 'datadate' => time()];
 * $rankingsPlayerModel->save($rankingData);
 */
class Pax_Rankings_Player_Model extends Pax_Model_Abstract
{
    /**
     * @var string Nom de la table des classements des joueurs dans la base de donnees.
     * 
     * Par defaut, la table est TABLE_RANK_PLAYER_POINTS.
     */
    protected string  $table = TABLE_RANK_PLAYER_POINTS;
    
    /**
     * @var array Liste des champs autorises pour les classements des joueurs.
     * 
     * Les champs autorises sont : rank, player_id, points, nb_spacecraft, datadate, sender_id.
     */
    protected array $allowedFields = ['rank', 'player_id', 'points','nb_spacecraft', 'datadate',  "sender_id"];
    
    /**
     * @var array Liste des champs requis pour les classements des joueurs.
     * 
     * Les champs requis sont : player_id, datadate.
     * Ces champs ne peuvent pas etre null.
     */
    protected array $requiredFields = ['player_id', 'datadate'];

    /**
     * Definit la table a utiliser pour les classements des joueurs.
     *
     * Cette methode permet de changer la table utilisee pour les classements
     * des joueurs en fonction du type de classement (points, economie, technologie, etc.).
     *
     * @param string $table Nom de la table a utiliser.
     *
     * @throws Exception Si la table specifiee n'est pas autorisee.
     */
    public function setTable(string $table): void
    {
          $allowedTable = [TABLE_RANK_PLAYER_POINTS, TABLE_RANK_PLAYER_ECO, TABLE_RANK_PLAYER_TECHNOLOGY, TABLE_RANK_PLAYER_MILITARY, TABLE_RANK_PLAYER_MILITARY_BUILT, TABLE_RANK_PLAYER_MILITARY_LOOSE, TABLE_RANK_PLAYER_MILITARY_DESTRUCT, TABLE_RANK_PLAYER_HONOR];

        if (in_array($table, $allowedTable)) {
            $this->table = $table;
        }
    }


}
