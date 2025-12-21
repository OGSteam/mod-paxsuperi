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

class ClassPlayers extends AbstractClass
{


    public function traitement(): StepperResponse
    {
        if ($this->preTraitement() !== true) {
            return $this->response;
        }
        $pays          =  $this->setting->pays;
        $uni           = (int)$this->setting->uni; 

        $xmlManager = new XmlManager($pays, $uni);
        $playersXmlString = $xmlManager->getLocalXml($this->endpoint);
        $playersXml = simplexml_load_string($playersXmlString);
        // date
        $datadate = (int)$playersXml->attributes()->timestamp;
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
        $dataPlayers = array();
        foreach ($playersXml as $playerXml) {
            $dataPlayer = array();
            $dataPlayer['id'] = (int)$playerXml[0]['id'];
            $dataPlayer['name'] = (string)$playerXml[0]['name'];
            $dataPlayer['status'] = (string)$playerXml[0]['status'];
            $dataPlayer['ally_id'] = (string)(int)$playerXml[0]['alliance'];
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
