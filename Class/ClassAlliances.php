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

class ClassAlliances extends AbstractClass
{
    
    public function traitement(): StepperResponse
    {
        if ($this->preTraitement() !== true) {
            return $this->response;
        }
        $pays          = pax_mod_get_option('pays');
        $uni           = pax_mod_get_option('uni');


        $xmlManager = new XmlManager($pays, $uni);
        $alliancesXmlString = $xmlManager->getLocalXml($this->endpoint);
        $alliancesXml = simplexml_load_string($alliancesXmlString);
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
            $this->response->setMessage('Enregistrement effectué  ' . $this->endpoint);
            return $this->response;
        }


        // si erreur 
        $this->response->setError('Une erreur est survenue ' . $this->endpoint);
        $this->response->setMessage('Une erreur est survenue ' . $this->endpoint);

        return $this->response;
    }
    
}
