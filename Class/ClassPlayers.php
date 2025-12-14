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
        $pays          = pax_mod_get_option('pays');
        $uni           = pax_mod_get_option('uni');
        $temporisation = (int) pax_mod_get_option('temporisation');

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

        // Sauvegarde d'un seul joueur
        $data = [
            'id' => 123,
            'name' => 'Joueur1',
            'ally_id' => 456,
            'datadate' => time(),
        ];
        $model = new Pax_Player_Model();
        $model->save($data);



        // Sauvegarde de plusieurs joueurs
        $datas = [
            [
                'id' => 123,
                'name' => 'Joueur1',
                'ally_id' => 456,
                'datadate' => time(),
            ],
            [
                'id' => 789,
                'name' => 'Joueur2',
                'ally_id' => 101,
                'datadate' => time(),
            ],
        ];
        $model->saveMultiple( $dataPlayers);

        //TODO
        //  $playersXml = $xmlManager->getLocalXml($this->endpoint);
        //  echo '<pre>';
        //   var_dump($playersXml);

        // exit();
        $this->response->setMessage('TODO  ' . $this->endpoint);
        return $this->response;
    }
}
