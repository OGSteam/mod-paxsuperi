<?php

/**
 * OGSPY - Mod PAx Superi
 *
 * @package [Mod] Pax Superi
 * @author Machine
 * @copyright Copyright &copy; 2016, https://ogsteam.eu/
 * @license https://opensource.org/licenses/gpl-license.php GNU Public License
 */

use Ogsteam\Ogspy\Abstracts\Model_Abstract;

if (! defined('IN_SPYOGAME')) {
    exit('Hacking attempt');
}

/**
 * Classe abstraite pour la gestion des modeles de donnees.
 *
 * Cette classe abstraite fournit des methodes de base pour interagir
 * avec la base de donnees, notamment pour l'enregistrement et la mise
 * a jour des donnees. Elle est heritee par les modeles specifiques.
 *
 * @category   PaxSuperi
 */
abstract class Pax_Model_Abstract extends Model_Abstract
{
    /**
     * @var string Nom de la table dans la base de donnees.
     */
    protected string $table;

    /**
     * @var array Liste des champs autorises pour la table.
     *
     * Les champs autorises sont ceux qui peuvent etre utilises
     * pour les operations d'enregistrement et de mise a jour.
     */
    protected array $allowedFields;

    /**
     * @var array Liste des champs requis pour la table.
     *
     * Les champs requis sont ceux qui doivent etre presents
     * pour les operations d'enregistrement et de mise a jour.
     */
    protected array $requiredFields;

    /**
     * Enregistre une seule entite dans la base de donnees.
     *
     * Cette methode enregistre une seule entite dans la base de donnees.
     * Elle verifie que les champs obligatoires sont presents et que
     * seuls les champs autorises sont utilises.
     *
     * @param array $data Tableau associatif contenant les donnees a enregistrer.
     *
     * @return bool True si l'enregistrement a reussi, false sinon.
     *
     * @throws Exception Si les champs obligatoires sont manquants ou si des champs non autorises sont presents.
     */
    public function save(array $data): bool
    {
        global $pax_logger;
        $pax_logger->info('Tentative d\'enregistrement d\'une entite dans la table ' . $this->table);

        if (empty($data)) {
            $pax_logger->warning('Donnees vides pour l\'enregistrement');

            return false;
        }

        // champs obligatoire ?
        if (! $this->isRequiredField($data)) {
            $pax_logger->error('Champs obligatoires manquants pour l\'enregistrement');

            throw new Exception('Champ obligatoire non présent.');
        }

        // champs authorisé ?
        if (! $this->isOnlyAllowedFields($data)) {
            $pax_logger->error('Champs non autorises presents dans les donnees');

            throw new Exception('Champn on autorisé present.');
        }

        $key = array_keys($data);

        // creation requete SQL
        $sql    = $this->prepareQuery([$data], $key);
        $result = $this->db->sql_query($sql);

        if ($result) {
            $pax_logger->info('Enregistrement reussi dans la table ' . $this->table);
        } else {
            $pax_logger->error('Echec de l\'enregistrement dans la table ' . $this->table);
        }

        return $result;
    }

    /**
     * Enregistre plusieurs entites dans la base de donnees.
     *
     * Cette methode enregistre plusieurs entites dans la base de donnees
     * en une seule requete. Elle verifie que les champs obligatoires sont
     * presents et que seuls les champs autorises sont utilises pour chaque entite.
     *
     * @param array $datas Tableau de tableaux associatifs contenant les donnees a enregistrer.
     *
     * @return bool True si l'enregistrement a reussi, false sinon.
     *
     * @throws Exception Si les champs obligatoires sont manquants ou si des champs non autorises sont presents.
     */
    public function saveMultiple(array $datas): bool
    {
        global $pax_logger;
        $pax_logger->info('Tentative d\'enregistrement de ' . count($datas) . ' entites dans la table ' . $this->table);

        // Si pas de data à traiter, retourne false
        if (empty($datas)) {
            $pax_logger->warning('Aucune donnee a enregistrer');

            return false;
        }

        foreach ($datas as $data) {
            // champs obligatoire ?
            if (! $this->isRequiredField($data)) {
                $pax_logger->error('Champs obligatoires manquants pour une entite');

                throw new Exception('Champ obligatoire non présent.');
            }

            // champs authorisé ?
            if (! $this->isOnlyAllowedFields($data)) {
                $pax_logger->error('Champs non autorises presents dans une entite');

                throw new Exception('Champs on autorisé present.');
            }
        }

        // on prends les champs de la premiere ligne
        $key = array_keys($datas[0]);

        // creation requete SQL
        $sql    = $this->prepareQuery($datas, $key);
        $result = $this->db->sql_query($sql);

        if ($result) {
            $pax_logger->info('Enregistrement multiple reussi dans la table ' . $this->table);
        } else {
            $pax_logger->error('Echec de l\'enregistrement multiple dans la table ' . $this->table);
        }

        return $result;
    }

    /**
     * Verifie si tous les champs requis sont presents dans les donnees.
     *
     * Cette methode verifie que tous les champs definis comme requis
     * dans la propriete $requiredFields sont presents dans le tableau de donnees.
     *
     * @param array $data Tableau associatif contenant les donnees a verifier.
     *
     * @return bool True si tous les champs requis sont presents, false sinon.
     */
    private function isRequiredField(array $data): bool
    {
        foreach ($this->requiredFields as $field) {
            if (! array_key_exists($field, $data)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Verifie que seuls les champs autorises sont presents dans les donnees.
     *
     * Cette methode verifie que tous les champs presents dans le tableau de donnees
     * font partie des champs autorises definis dans la propriete $allowedFields.
     *
     * @param array $data Tableau associatif contenant les donnees a verifier.
     *
     * @return bool True si seuls les champs autorises sont presents, false sinon.
     */
    private function isOnlyAllowedFields(array $data): bool
    {
        $keys = array_keys($data);

        foreach ($keys as $key) {
            if (! in_array($key, $this->allowedFields, true)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Prepare une requete SQL pour l'enregistrement des donnees.
     *
     * Cette methode prepare une requete SQL INSERT avec mise a jour
     * en cas de doublon (ON DUPLICATE KEY UPDATE) pour enregistrer
     * les donnees dans la base de donnees.
     *
     * @param array $datas Tableau de tableaux associatifs contenant les donnees a enregistrer.
     * @param array $key   Tableau contenant les noms des champs a utiliser.
     *
     * @return string Requete SQL preparee.
     */
    private function prepareQuery(array $datas, array $key): string
    {
        global $pax_logger;
        $pax_logger->info('Traitement  de ' . count($datas) . ' entites dans la table ' . $this->table);
        $pax_logger->debug('Preparation de la requete SQL pour l\'enregistrement des donnees');

        // Si pas de data à traiter, retourne false
        if (empty($datas)) {
            $pax_logger->warning('Aucune donnee a enregistrer');

            return false;
        }

        $values               = [];
        $currentAllowedFields = array_intersect($key, $this->allowedFields); // le meilleur des deux mondes currentAllowedFields
        $pax_logger->debug('Champs autorises pour cette operation: ' . implode(', ', $currentAllowedFields));

        foreach ($datas as $data) {
            $dataValues = [];

            foreach ($currentAllowedFields as $field) {
                if (array_key_exists($field, $data)) {
                    $currentValue = $data[$field];

                    // on caste au besoin sinon escape
                    if (is_int($currentValue)) {
                        $dataValues[] = (int) $currentValue;
                    } else {
                        $dataValues[] = "'" . $this->db->sql_escape_string($currentValue) . "'";
                    }
                }
            }
            $values[] = '(' . implode(', ', $dataValues) . ')';
        }

        // Requete
        $query = "INSERT INTO {$this->table} (" . implode(', ', $currentAllowedFields) . ') VALUES ' . implode(', ', $values);
        // si jamais c une mise a jour
        $updates = [];

        foreach ($currentAllowedFields as $field) {
            // ne concerne pas les id qui demeurent
            if ($field !== 'id') {
                $updates[] = $field . ' = VALUES(' . $field . ')';
            }
        }
        $query .= ' ON DUPLICATE KEY UPDATE ' . implode(', ', $updates);

        $pax_logger->debug('Requete SQL preparee: ' . substr($query, 0, 200) . '...');

        return $query;
    }
}
