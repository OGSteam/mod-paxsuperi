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
    private $table = TABLE_GAME_PLAYER;
    private $allowedFields = ['id', 'name', 'status', 'class', 'ally_id', 'datadate', 'off_commandant', 'off_amiral', 'off_ingenieur', 'off_geologue', 'off_technocrate'];
    private $requiredFields = ['id', 'ally_id']; // pas d autoincrmente et ally_id ne peut etre null


    public function save(array $data): bool
    {
        if (empty($data)) {
            return false;
        }

        //champs obligatoire ?
        if (!$this->isRequiredField($data)) {
            throw new Exception("Champ obligatoire non présent.");
        }

        //champs authorisé ?
        if (!$this->isOnlyAllowedFields($data)) {
            throw new Exception("Champn on autorisé present.");
        }

        $key = array_keys($data);


        // creation requete SQL
        $sql = $this->prepareQuery([$data], $key);
        return $this->db->sql_query($sql);
    }


    public function saveMultiple(array $datas): bool
    {
        // Si aucun joueur à traiter, retourne false
        if (empty($datas)) {
            return false;
        }

        foreach ($datas as $data) {
            //champs obligatoire ?
            if (!$this->isRequiredField($data)) {
                throw new Exception("Champ obligatoire non présent.");
            }

            //champs authorisé ?
            if (!$this->isOnlyAllowedFields($data)) {
                throw new Exception("Champs on autorisé present.");
            }
        }

        // on prends les champs de la premiere ligne 
        $key = array_keys($datas[0]);

        // creation requete SQL
        $sql = $this->prepareQuery($datas, $key);
        return $this->db->sql_query($sql);
    }

    private function isRequiredField(array $data): bool
    {
        foreach ($this->requiredFields as $field) {
            if (!array_key_exists($field, $data)) {
                return false;
            }
        }
        return true;
    }


    private function isOnlyAllowedFields(array $data): bool
    {

        $keys = array_keys($data);
        foreach ($keys as $key) {
            if (!in_array($key, $this->allowedFields)) {
                return false;
            }
        }
        return true;
    }


    private function prepareQuery(array $datas, array $key): string
    {

        $values = [];
        $currentAllowedFields = $key;
        foreach ($datas as $data) {
            $dataValues = [];
            foreach ($this->allowedFields as $field) {
                if (array_key_exists($field, $data)) {

                    
                    $currentValue = $data[$field];

                    // on caste au besoin sinon escape
                    if (is_int($currentValue)) {
                        $dataValues[] = (int)$currentValue;
                    } else {
                        $dataValues[] = "'" . $this->db->sql_escape_string($currentValue) . "'";
                    }
                }
            }
            $values[] = "(" . implode(', ', $dataValues) . ")";
        }

        // Requete 
        $query = "INSERT INTO {$this->table} (" . implode(', ', $currentAllowedFields) . ") VALUES " . implode(', ', $values);
        // si jamais c une mise a jour 
        $updates = [];
        foreach ($currentAllowedFields as $field) {
            // ne concerne pas les id qui demeurent 
            if ($field !== 'id') {
                $updates[] = $field . " = VALUES(" . $field . ")";
            }
        }
        $query .= " ON DUPLICATE KEY UPDATE " . implode(', ', $updates);

        return $query;
    }
}
