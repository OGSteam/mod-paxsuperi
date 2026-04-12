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
 * Classe abstraite pour la gestion des classements des alliances.
 *
 * Cette classe herite de AbstractClass et fournit des methodes pour traiter
 * les donnees des classements des alliances depuis l'API XML d'OGame.
 *
 * @category   PaxSuperi
 */
abstract class ClassAlliances_rank extends AbstractClass
{
    /**
     * @var Pax_Rankings_Ally_Model Modele pour la gestion des classements des alliances.
     */
    protected $model;

    /**
     * @var Pax_Player_Model Modele pour la gestion des joueurs.
     */
    protected $modelPlayer;

    /**
     * Constructeur de la classe.
     *
     * @param string $endpoint Point de terminaison de l'API pour recuperer les donnees.
     */
    public function __construct(string $endpoint)
    {
        parent::__construct($endpoint);
        $this->model       = new Pax_Rankings_Ally_Model();
        $this->modelPlayer = new Pax_Player_Model();
    }

    /**
     * Traite les donnees des classements des alliances.
     *
     * Cette methode recupere les donnees des classements des alliances depuis l'API XML,
     * les transforme et les enregistre dans la base de donnees.
     *
     * @return StepperResponse Reponse du traitement.
     *
     * @throws Exception Si une erreur survient lors du traitement.
     */
    public function traitement(): StepperResponse
    {
        global $pax_logger;
        $pax_logger->info('Debut du traitement des classements des alliances pour ' . $this->endpoint);

        if ($this->preTraitement() !== true) {
            $pax_logger->warning('Pre-traitement echoue pour les classements des alliances');

            return $this->response;
        }
        $pays = $this->setting->pays;
        $uni  = (int) $this->setting->uni;
        $pax_logger->debug('Recuperation des donnees pour le pays: ' . $pays . ' et l\'univers: ' . $uni);

        $allyPlayerCount = $this->modelPlayer->get_player_count_by_ally();
        $pax_logger->info('Recuperation du nombre de joueurs par alliance');

        $xmlManager     = new XmlManager($pays, $uni, $GLOBALS['pax_container']);
        $allyRankString = $xmlManager->getLocalXml($this->endpoint);
        $allysRankXml   = simplexml_load_string($allyRankString);
        $pax_logger->info('Donnees XML des classements des alliances chargees avec succes');
        // date
        $datadate = Pax_Helper::formatageTimestampForRank((int) $allysRankXml->attributes()->timestamp);
        $pax_logger->debug('Timestamp formate pour les classements: ' . $datadate);

        // tableau Ally / count(player)
        $allyPlayerCount = $this->modelPlayer->get_player_count_by_ally();

        $dataAllysRank = [];

        foreach ($allysRankXml as $allyRankXml) {
            $dataAllyRank                  = [];
            $dataAllyRank['rank']          = (int) $allyRankXml[0]['position'];
            $dataAllyRank['ally_id']       = (int) $allyRankXml[0]['id'];
            $dataAllyRank['number_member'] = $allyPlayerCount[$dataAllyRank['ally_id']] ?? 0; // / si 0 => bug si pas dde joueur pas d alliance

            // --------------doesn't have a default value--------------------
            // $dataAllyRank['ally'] = '?'; //Field 'ally' doesn't have a default value => sera supp de la prochaine verion
            // $dataAllyRank['points_per_member'] = '0'; //points_per_member 'ally' doesn't have a default value  => sera supp de la prochaine verion
            // ---------------------------------------------------------------

            $dataAllyRank['points']   = (string) $allyRankXml[0]['score']; // / strint car possible perte d info bigint
            $dataAllyRank['datadate'] = $datadate;

            // todo sender_id
            $dataAllysRank[] = $dataAllyRank;
        }
        // instance dans le constructeur
        // $this->model = new Pax_Rankings_Player_Model();

        if ($this->model->saveMultiple($dataAllysRank)) {
            $pax_logger->info('Enregistrement des classements des alliances effectue avec succes');
            $this->response->setMessage('Enregistrement effectué  ' . $this->endpoint);

            return $this->response;
        }

        // si erreur
        $pax_logger->error('Erreur lors de l\'enregistrement des classements des alliances');
        $this->response->setError('Une erreur est survenue ' . $this->endpoint);
        $this->response->setMessage('Une erreur est survenue ' . $this->endpoint);

        return $this->response;
    }
}

class ClassAlliances_rank_points extends ClassAlliances_rank
{
    public function traitement(): StepperResponse
    {
        $this->model->setTable(TABLE_RANK_ALLY_POINTS);

        return parent::traitement();
    }
}

class ClassAlliances_rank_eco extends ClassAlliances_rank
{
    public function traitement(): StepperResponse
    {
        $this->model->setTable(TABLE_RANK_ALLY_ECO);

        return parent::traitement();
    }
}

class ClassAlliances_rank_technology extends ClassAlliances_rank
{
    public function traitement(): StepperResponse
    {
        $this->model->setTable(TABLE_RANK_ALLY_TECHNOLOGY);

        return parent::traitement();
    }
}

class ClassAlliances_rank_military extends ClassAlliances_rank
{
    public function traitement(): StepperResponse
    {
        $this->model->setTable(TABLE_RANK_ALLY_MILITARY);

        return parent::traitement();
    }
}

class ClassAlliances_rank_military_built extends ClassAlliances_rank
{
    public function traitement(): StepperResponse
    {
        $this->model->setTable(TABLE_RANK_ALLY_MILITARY_BUILT);

        return parent::traitement();
    }
}

class ClassAlliances_rank_military_destroyed extends ClassAlliances_rank
{
    public function traitement(): StepperResponse
    {
        $this->model->setTable(TABLE_RANK_ALLY_MILITARY_DESTRUCT);

        return parent::traitement();
    }
}

class ClassAlliances_rank_military_lost extends ClassAlliances_rank
{
    public function traitement(): StepperResponse
    {
        $this->model->setTable(TABLE_RANK_ALLY_MILITARY_LOOSE);

        return parent::traitement();
    }
}

class ClassAlliances_rank_military_honnor extends ClassAlliances_rank
{
    public function traitement(): StepperResponse
    {
        $this->model->setTable(TABLE_RANK_ALLY_HONOR);

        return parent::traitement();
    }
}
