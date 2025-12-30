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
 * Classe pour la gestion des alliances.
 * 
 * Cette classe herite de AbstractClass et fournit des methodes pour traiter
 * les donnees des alliances depuis l'API XML d'OGame.
 *
 * @category   PaxSuperi
 * @package    Class
 * @subpackage Alliances
 */
class ClassAlliances extends AbstractClass
{
    /**
     * Traite les donnees des alliances.
     * 
     * Cette methode recupere les donnees des alliances depuis l'API XML,
     * les transforme et les enregistre dans la base de donnees.
     *
     * @return StepperResponse Reponse du traitement.
     *
     * @throws Exception Si une erreur survient lors du traitement.
     */
    
    public function traitement(): StepperResponse
    {
        global $pax_logger;
        $pax_logger->info('Début du traitement des alliances');
        
        if ($this->preTraitement() !== true) {
            $pax_logger->warning('Pré-traitement échoué pour les alliances');
            return $this->response;
        }
        $pays          =  $this->setting->pays;
        $uni           = (int)$this->setting->uni; 
        $pax_logger->debug('Récupération des données pour le pays: ' . $pays . ' et l\'univers: ' . $uni);

        $xmlManager = new XmlManager($pays, $uni);
        $alliancesXmlString = $xmlManager->getLocalXml($this->endpoint);
        $alliancesXml = simplexml_load_string($alliancesXmlString);
        $pax_logger->info('Données XML des alliances chargées avec succès');
        // date
        $datadate = (int)$alliancesXml->attributes()->timestamp;
        // recup info alliance en bdd ogspy => Xml data 
        // OK = id => id
        // OK = name 	=> name
        // OK = tag => tag	
        // KO = class 	
        // OK = datadate 			
        	
        // ex :<alliance id="500001" name="Alliance PULSAR" tag="PULSAR" founder="101735" foundDate="1688375350" homepage="https://discord.gg/hmjTtCswdr" logo="https://cdn.discordapp.com/attachments/789188905798336526/1124714043568042045/pulsar.png" open="1">
        $dataAlliances = array();
        foreach ($alliancesXml as $allianceXml) {
            $dataAlliance = array();
            $dataAlliance['id'] = (int)$allianceXml[0]['id'];
            $dataAlliance['name'] = (string)$allianceXml[0]['name'];
            $dataAlliance['tag'] = (string)$allianceXml[0]['tag'];
            $dataAlliance['datadate'] = $datadate;

            $dataAlliances[] = $dataAlliance;
        }


        $model = new Pax_Alliance_Model();
        if ($model->saveMultiple($dataAlliances)) {
            $pax_logger->info('Enregistrement des alliances effectué avec succès');
            $this->response->setMessage('Enregistrement effectué  ' . $this->endpoint);
            return $this->response;
        }


        // si erreur 
        $pax_logger->error('Une erreur est survenue lors de l\'enregistrement des alliances');
        $this->response->setError('Une erreur est survenue ' . $this->endpoint);
        $this->response->setMessage('Une erreur est survenue ' . $this->endpoint);

        return $this->response;
    }
    
}
