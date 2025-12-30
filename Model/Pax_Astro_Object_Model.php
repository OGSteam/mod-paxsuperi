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
    protected string $table = TABLE_USER_BUILDING;

    /**
     * @var array Liste des champs autorises pour les objets astronomiques.
     *
     * Les champs autorises sont : id, type, galaxy, system, row, player_id, last_update, name.
     */
    protected array $allowedFields = [
        'id',
        'type',
        'galaxy',
        'system',
        'row',
        'player_id',
        'last_update',
        'name',
    ];

    /**
     * @var array Liste des champs requis pour les objets astronomiques.
     *
     * Les champs requis sont : id, type, galaxy, system, row, player_id, last_update, name.
     * Ces champs ne peuvent pas etre null.
     */
    protected array $requiredFields = ['id', 'type', 'galaxy', 'system', 'row', 'player_id', 'last_update', 'name'];

    /**
     * Enregistre plusieurs entites dans la base de donnees avec gestion des suppressions obsolètes.
     *
     * Cette methode surcharge la méthode parente pour ajouter la logique de suppression
     * des objets astronomiques obsolètes apres l'insertion des nouveaux.
     *
     * @param array $datas Tableau de tableaux associatifs contenant les donnees a enregistrer.
     *
     * @return bool True si l'enregistrement a reussi, false sinon.
     *
     * @throws Exception Si les champs obligatoires sont manquants ou si des champs non autorises sont presents.
     */
    public function saveMultiple(array $datas): bool
    {
        $this->db->sql_transaction('begin');

        $savemultiple = parent::saveMultiple($datas);

        $timestamp      = $datas[0]['last_update'];
        $deleteObsolete = $this->deleteObsoleteObjects($timestamp);

        $this->db->sql_transaction('commit');

        return $savemultiple && $deleteObsolete;
    }

    /**
     * Supprime tous les objets astronomiques antérieurs à un timestamp donné.
     *
     * Cette méthode supprime les objets dont la dernière mise à jour est antérieure
     * au timestamp spécifié, ce qui permet de nettoyer les objets disparus.
     *
     * @param int $timestamp Timestamp de référence pour la suppression
     *
     * @return bool True si la suppression a réussi, false sinon
     */
    public function deleteObsoleteObjects(int $timestamp): bool
    {
        global $pax_logger;
        $pax_logger->info('Suppression des objets astronomiques obsolètes (antérieurs à ' . date('Y-m-d H:i:s', $timestamp) . ')');

        $sql    = "DELETE FROM {$this->table} WHERE last_update < " . (int) $timestamp;
        $result = $this->db->sql_query($sql);

        if ($result) {
            $affectedRows = $this->db->sql_affectedrows();
            $pax_logger->info('Suppression de ' . $affectedRows . ' objets obsolètes réussie');

            return true;
        }
        $pax_logger->error('Échec de la suppression des objets obsolètes');

        return false;
    }
}
