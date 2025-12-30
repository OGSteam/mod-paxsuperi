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
 * Modele pour la gestion des alliances.
 * 
 * Ce modele herite de Pax_Model_Abstract et fournit des methodes pour
 * interagir avec la table des alliances dans la base de donnees.
 *
 * @category   PaxSuperi
 * @package    Model
 * @subpackage Alliance
 *
 * @example
 * // Creer une instance du modele
 * $allianceModel = new Pax_Alliance_Model();
 * 
 * // Enregistrer une alliance
 * $allianceData = ['id' => 1, 'name' => 'Alliance 1', 'tag' => 'A1', 'datadate' => time()];
 * $allianceModel->save($allianceData);
 */
class Pax_Alliance_Model extends Pax_Model_Abstract
{
    /**
     * @var string Nom de la table des alliances dans la base de donnees.
     */
    protected string  $table = TABLE_GAME_ALLY;
    
    /**
     * @var array Liste des champs autorises pour les alliances.
     * 
     * Les champs autorises sont : id, name, tag, class, datadate.
     */
    protected array $allowedFields = ['id', 'name', 'tag', 'class', 'datadate'];
    
    /**
     * @var array Liste des champs requis pour les alliances.
     * 
     * Les champs requis sont : id, datadate.
     * Ces champs ne peuvent pas etre null.
     */
    protected array $requiredFields = ['id', 'datadate'];

}
