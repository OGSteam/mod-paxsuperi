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

abstract class ClassPlayers_rank extends AbstractClass
{
    protected  $model;
    protected bool $isShips ;

    public function __construct(string $endpoint)
    {
        parent::__construct($endpoint);
        $this->model = new Pax_Rankings_Player_Model();
           $this->isShips = false;
    }

    public function traitement(): StepperResponse
    {
        if ($this->preTraitement() !== true) {
            return $this->response;
        }
        $pays          = pax_mod_get_option('pays');
        $uni           = pax_mod_get_option('uni');

        $xmlManager = new XmlManager($pays, $uni);
        $playersRankString = $xmlManager->getLocalXml($this->endpoint);
        $playersRankXml = simplexml_load_string($playersRankString);
        // date
        $datadate = (int)$playersRankXml->attributes()->timestamp;

        //<player position="5" id="101706" score="1414116972"/>

        $dataPlayersRank = array();
        foreach ($playersRankXml as $playerRankXml) {

            $dataPlayerRank = array();
            $dataPlayerRank['rank'] = (int)$playerRankXml[0]['position'];
            $dataPlayerRank['player_id'] = (int)$playerRankXml[0]['id'];

            $dataPlayerRank['points'] = (string)$playerRankXml[0]['score']; /// strint car possible perte d info bigint

            if ($this->isShips) {
                $dataPlayerRank['nb_spacecraft'] = (int)$playerRankXml[0]['ships'] ?? 0; /// strint car possible perte d info bigint
            }

            $dataPlayerRank['datadate'] = $datadate;

            //todo sender_id
            $dataPlayersRank[] = $dataPlayerRank;
        }
        // instance dans le constructeur
        //$this->model = new Pax_Rankings_Player_Model();

        if ($this->model->saveMultiple($dataPlayersRank)) {
            $this->response->setMessage('Enregistrement effectué  ' . $this->endpoint);
            return $this->response;
        }


        // si erreur 
        $this->response->setError('Une erreur est survenue ' . $this->endpoint);
        $this->response->setMessage('Une erreur est survenue ' . $this->endpoint);

        return $this->response;
    }
}


class ClassPlayers_rank_points extends ClassPlayers_rank
{
    public function   traitement(): StepperResponse
    {


        $this->model->setTable(TABLE_RANK_PLAYER_POINTS);
        return parent::traitement();
    }
}
class ClassPlayers_rank_eco extends ClassPlayers_rank
{
    public function   traitement(): StepperResponse
    {
        $this->model->setTable(TABLE_RANK_PLAYER_ECO);
        return parent::traitement();
    }
}
class ClassPlayers_rank_technology extends ClassPlayers_rank
{
    public function   traitement(): StepperResponse
    {


        $this->model->setTable(TABLE_RANK_PLAYER_TECHNOLOGY);
        return parent::traitement();
    }
}
class ClassPlayers_rank_military extends ClassPlayers_rank
{
        public function   traitement(): StepperResponse
    {
$this->isShips = true;
        $this->model->setTable(TABLE_RANK_PLAYER_MILITARY);
        return parent::traitement();
    }
}
class ClassPlayers_rank_military_built extends ClassPlayers_rank
{
    public function   traitement(): StepperResponse
    {

        $this->model->setTable(TABLE_RANK_PLAYER_MILITARY_BUILT);
        return parent::traitement();
    }
}
class ClassPlayers_rank_military_destroyed extends ClassPlayers_rank
{
    public function   traitement(): StepperResponse
    {

        $this->model->setTable(TABLE_RANK_PLAYER_MILITARY_DESTRUCT);
        return parent::traitement();
    }
}
class ClassPlayers_rank_military_lost extends ClassPlayers_rank
{
    public function   traitement(): StepperResponse
    {

        $this->model->setTable(TABLE_RANK_PLAYER_MILITARY_LOOSE);
        return parent::traitement();
    }
}
class ClassPlayers_rank_military_honnor extends ClassPlayers_rank
{
    public function   traitement(): StepperResponse
    {
        $this->model->setTable(TABLE_RANK_PLAYER_HONOR);
        return parent::traitement();
    }
}
