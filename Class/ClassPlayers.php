<?php

/**
 * OGSPY - Mod PAx Superi
 *
 * @package [Mod] Pax Superi
 * @author Machine
 * @copyright Copyright &copy; 2016, https://ogsteam.eu/
 * @license https://opensource.org/licenses/gpl-license.php GNU Public License
 */

if (! defined('IN_SPYOGAME')) {
    exit('Hacking attempt');
}

/**
 * Classe pour la gestion des joueurs.
 *
 * Cette classe herite de AbstractClass et fournit des methodes pour traiter
 * les donnees des joueurs depuis l'API XML d'OGame.
 *
 * @category   PaxSuperi
 */
class ClassPlayers extends AbstractClass
{
    /**
     * Traite les donnees des joueurs.
     *
     * Cette methode recupere les donnees des joueurs depuis l'API XML,
     * les transforme et les enregistre dans la base de donnees.
     *
     * @return StepperResponse Reponse du traitement.
     *
     * @throws Exception Si une erreur survient lors du traitement.
     */
    public function traitement(): StepperResponse
    {
        global $pax_logger;
        $pax_logger->info('Début du traitement des joueurs');

        if ($this->preTraitement() !== true) {
            $pax_logger->warning('Pré-traitement échoué pour les joueurs');

            return $this->response;
        }
        $pays = $this->setting->pays;
        $uni  = (int) $this->setting->uni;
        $pax_logger->debug('Récupération des données pour le pays: ' . $pays . ' et l\'univers: ' . $uni);

        $xmlManager       = new XmlManager($pays, $uni, $GLOBALS['pax_container']);
        $playersXmlString = $xmlManager->getLocalXml($this->endpoint);
        $playersXml       = simplexml_load_string($playersXmlString);
        $pax_logger->info('Données XML des joueurs chargées avec succès');
        // date
        $datadate = (int) $playersXml->attributes()->timestamp;
        // recup info player en bdd ogspy => Xml data
        // OK = id => id
        // OK = name 	=> name
        // OK = status => status
        // KO = class
        // OK = ally_id =>alliance
        // KO = off_commandant
        // KO = off_amiral
        // KO = off_ingenieur
        // KO = off_geologue
        // KO = off_technocrate
        // OK = 	datadate =>  $datadate /!\ deja récuperé

        // ex : ["@attributes"]=> array(4) { ["id"]=> string(6) "100053" ["name"]=> string(7) "glavius" ["status"]=> string(2) "vI" ["alliance"]=> string(6) "500000" }
        $dataPlayers = [];

        foreach ($playersXml as $playerXml) {
            $dataPlayer             = [];
            $dataPlayer['id']       = (int) $playerXml[0]['id'];
            $dataPlayer['name']     = (string) $playerXml[0]['name'];
            $dataPlayer['status']   = (string) $playerXml[0]['status'];
            $dataPlayer['ally_id']  = (string) (int) $playerXml[0]['alliance'];
            $dataPlayer['datadate'] = $datadate;

            $dataPlayers[] = $dataPlayer;
        }

        $model = new Pax_Player_Model();
        if ($model->saveMultiple($dataPlayers)) {
            $this->response->setMessage('Enregistrement effectué  ' . $this->endpoint);

            return $this->response;
        }

        // si erreur
        $this->response->setError('Une erreur est survenue ' . $this->endpoint);
        $this->response->setMessage('Une erreur est survenue ' . $this->endpoint);

        return $this->response;
    }
}
