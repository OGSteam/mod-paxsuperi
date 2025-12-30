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
 * Modele pour la gestion des objets astronomiques.
 * 
 * Ce modele herite de Pax_Model_Abstract et fournit des methodes pour
 * interagir avec la table des objets astronomiques dans la base de donnees.
 *
 * @category   PaxSuperi
 * @package    Model
 * @subpackage Astro_Object
 *
 * @example
 * // Creer une instance du modele
 * $astroObjectModel = new Pax_Astro_Object_Model();
 * 
 * // Enregistrer un objet astronomique
 * $astroObjectData = ['id' => 1, 'type' => 'planet', 'galaxy' => 1, 'system' => 1, 'row' => 1, 'player_id' => 1, 'last_update' => time(), 'name' => 'Planet 1'];
 * $astroObjectModel->save($astroObjectData);
 */
class Pax_Astro_Object_Model extends Pax_Model_Abstract
{
    /**
     * @var string Nom de la table des objets astronomiques dans la base de donnees.
     */
    protected string  $table = TABLE_USER_BUILDING;
    
    /**
     * @var array Liste des champs autorises pour les objets astronomiques.
     * 
     * Les champs autorises sont : id, type, galaxy, system, row, player_id, last_update, name.
     */
    protected array $allowedFields = ['id', 'type', 'galaxy', 'system', 'row', 'player_id', 'last_update', 'name'
    ];
    
    /**
     * @var array Liste des champs requis pour les objets astronomiques.
     * 
     * Les champs requis sont : id, type, galaxy, system, row, player_id, last_update, name.
     * Ces champs ne peuvent pas etre null.
     */
    protected array $requiredFields = ['id', 'type', 'galaxy', 'system', 'row', 'player_id', 'last_update', 'name'];




}
