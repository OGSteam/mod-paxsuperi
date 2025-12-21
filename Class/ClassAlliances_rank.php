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

abstract class ClassAlliances_rank extends AbstractClass
{
    protected  $model;
    public function __construct(string $endpoint)
    {
        parent::__construct($endpoint);
        $this->model = new Pax_Rankings_Ally_Model();
    }

    public function traitement(): StepperResponse
    {
        if ($this->preTraitement() !== true) {
            return $this->response;
        }
        $pays          =  $this->setting->pays;
        $uni           = (int)$this->setting->uni; 


        $xmlManager = new XmlManager($pays, $uni);
        $allyRankString = $xmlManager->getLocalXml($this->endpoint);
        $allysRankXml = simplexml_load_string($allyRankString);
        // date
          $datadate = formatage_timestamp_for_rank((int)$allysRankXml->attributes()->timestamp);


        //<player position="5" id="101706" score="1414116972"/>

        $dataAllysRank = array();
        foreach ($allysRankXml as $allyRankXml) {

            $dataAllyRank = array();
            $dataAllyRank['rank'] = (int)$allyRankXml[0]['position'];
            $dataAllyRank['ally_id'] = (int)$allyRankXml[0]['id'];

            //--------------doesn't have a default value--------------------
            $dataAllyRank['ally'] = '?'; //Field 'ally' doesn't have a default value
            $dataAllyRank['number_member'] = '0'; //number_member 'ally' doesn't have a default value
            $dataAllyRank['points_per_member'] = '0'; //points_per_member 'ally' doesn't have a default value
            //---------------------------------------------------------------

            $dataAllyRank['points'] = (string)$allyRankXml[0]['score']; /// strint car possible perte d info bigint
            $dataAllyRank['datadate'] = $datadate;


            //todo sender_id
            $dataAllysRank[] = $dataAllyRank;
        }
        // instance dans le constructeur
        //$this->model = new Pax_Rankings_Player_Model();

        if ($this->model->saveMultiple($dataAllysRank)) {
            $this->response->setMessage('Enregistrement effectué  ' . $this->endpoint);
            return $this->response;
        }


        // si erreur 
        $this->response->setError('Une erreur est survenue ' . $this->endpoint);
        $this->response->setMessage('Une erreur est survenue ' . $this->endpoint);

        return $this->response;
    }
}

class ClassAlliances_rank_points extends ClassAlliances_rank
{
    public function   traitement(): StepperResponse
    {
        $this->model->setTable(TABLE_RANK_ALLY_POINTS);
        return parent::traitement();
    }
}


class ClassAlliances_rank_eco extends ClassAlliances_rank
{
    public function   traitement(): StepperResponse
    {
        $this->model->setTable(TABLE_RANK_ALLY_ECO);
        return parent::traitement();
    }
}


class ClassAlliances_rank_technology extends ClassAlliances_rank
{

    public function   traitement(): StepperResponse
    {
        $this->model->setTable(TABLE_RANK_ALLY_TECHNOLOGY);
        return parent::traitement();
    }
}


class ClassAlliances_rank_military extends ClassAlliances_rank
{
    public function   traitement(): StepperResponse
    {
        $this->model->setTable(TABLE_RANK_ALLY_MILITARY);
        return parent::traitement();
    }
}


class ClassAlliances_rank_military_built extends ClassAlliances_rank
{
    public function   traitement(): StepperResponse
    {
        $this->model->setTable(TABLE_RANK_ALLY_MILITARY_BUILT);
        return parent::traitement();
    }
}


class ClassAlliances_rank_military_destroyed extends ClassAlliances_rank
{
    public function   traitement(): StepperResponse
    {
        $this->model->setTable(TABLE_RANK_ALLY_MILITARY_DESTRUCT);
        return parent::traitement();
    }
}


class ClassAlliances_rank_military_lost extends ClassAlliances_rank
{
    public function   traitement(): StepperResponse
    {
        $this->model->setTable(TABLE_RANK_ALLY_MILITARY_LOOSE);
        return parent::traitement();
    }
}


class ClassAlliances_rank_military_honnor extends ClassAlliances_rank
{
    public function   traitement(): StepperResponse
    {
        $this->model->setTable(TABLE_RANK_ALLY_HONOR);
        return parent::traitement();
    }
}
