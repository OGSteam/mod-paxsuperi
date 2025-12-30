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
 * Modele pour la gestion des classements des alliances.
 * 
 * Ce modele herite de Pax_Model_Abstract et fournit des methodes pour
 * interagir avec les tables des classements des alliances dans la base de donnees.
 *
 * @category   PaxSuperi
 * @package    Model
 * @subpackage Rankings_Ally
 *
 * @example
 * // Creer une instance du modele
 * $rankingsAllyModel = new Pax_Rankings_Ally_Model();
 * 
 * // Changer la table pour les classements economiques
 * $rankingsAllyModel->setTable(TABLE_RANK_ALLY_ECO);
 * 
 * // Enregistrer un classement
 * $rankingData = ['rank' => 1, 'ally_id' => 1, 'points' => 1000, 'datadate' => time()];
 * $rankingsAllyModel->save($rankingData);
 */
class Pax_Rankings_Ally_Model extends Pax_Model_Abstract
{
    /**
     * @var string Nom de la table des classements des alliances dans la base de donnees.
     * 
     * Par defaut, la table est TABLE_RANK_ALLY_POINTS.
     */
    protected string  $table = TABLE_RANK_ALLY_POINTS;
    
    /**
     * @var array Liste des champs autorises pour les classements des alliances.
     * 
     * Les champs autorises sont : rank, ally_id, points, nb_spacecraft, datadate, number_member, sender_id.
     */
    protected array $allowedFields = ['rank', 'ally_id', 'points','nb_spacecraft', 'datadate', 'number_member', "sender_id"];
    
    /**
     * @var array Liste des champs requis pour les classements des alliances.
     * 
     * Les champs requis sont : ally_id, datadate.
     * Ces champs ne peuvent pas etre null.
     */
    protected array $requiredFields = ['ally_id', 'datadate'];

    /**
     * Definit la table a utiliser pour les classements des alliances.
     *
     * Cette methode permet de changer la table utilisee pour les classements
     * des alliances en fonction du type de classement (points, economie, technologie, etc.).
     *
     * @param string $table Nom de la table a utiliser.
     *
     * @throws Exception Si la table specifiee n'est pas autorisee.
     */
    public function setTable(string $table): void
    {
     $allowedTable = [TABLE_RANK_ALLY_POINTS, TABLE_RANK_ALLY_ECO, TABLE_RANK_ALLY_TECHNOLOGY, TABLE_RANK_ALLY_MILITARY, TABLE_RANK_ALLY_MILITARY_BUILT, TABLE_RANK_ALLY_MILITARY_LOOSE, TABLE_RANK_ALLY_MILITARY_DESTRUCT, TABLE_RANK_ALLY_HONOR];

        if (in_array($table, $allowedTable)) {
            $this->table = $table;
        }
    }


}
