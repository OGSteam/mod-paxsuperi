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
 * Classe abstraite pour la gestion des classements des joueurs.
 *
 * Cette classe herite de AbstractClass et fournit des methodes pour traiter
 * les donnees des classements des joueurs depuis l'API XML d'OGame.
 *
 * @category   PaxSuperi
 */
abstract class ClassPlayers_rank extends AbstractClass
{
    /**
     * @var Pax_Rankings_Player_Model Modele pour la gestion des classements des joueurs.
     */
    protected $model;

    /**
     * @var bool Indique si les donnees concernent des vaisseaux.
     */
    protected bool $isShips;

    /**
     * Constructeur de la classe.
     *
     * @param string $endpoint Point de terminaison de l'API pour recuperer les donnees.
     */
    public function __construct(string $endpoint)
    {
        parent::__construct($endpoint);
        $this->model   = new Pax_Rankings_Player_Model();
        $this->isShips = false;
    }

    /**
     * Traite les donnees des classements des joueurs.
     *
     * Cette methode recupere les donnees des classements des joueurs depuis l'API XML,
     * les transforme et les enregistre dans la base de donnees.
     *
     * @return StepperResponse Reponse du traitement.
     *
     * @throws Exception Si une erreur survient lors du traitement.
     */
    public function traitement(): StepperResponse
    {
        global $pax_logger;
        $pax_logger->info('Debut du traitement des classements des joueurs pour ' . $this->endpoint);

        if ($this->preTraitement() !== true) {
            $pax_logger->warning('Pre-traitement echoue pour les classements des joueurs');

            return $this->response;
        }
        $pays = $this->setting->pays;
        $uni  = (int) $this->setting->uni;
        $pax_logger->debug('Recuperation des donnees pour le pays: ' . $pays . ' et l\'univers: ' . $uni);

        $xmlManager        = new XmlManager($pays, $uni, $GLOBALS['pax_container']);
        $playersRankString = $xmlManager->getLocalXml($this->endpoint);
        $playersRankXml    = simplexml_load_string($playersRankString);
        $pax_logger->info('Donnees XML des classements des joueurs chargees avec succes');
        // date
        $datadate = Pax_Helper::formatageTimestampForRank((int) $playersRankXml->attributes()->timestamp);
        $pax_logger->debug('Timestamp formate pour les classements: ' . $datadate);

        // <player position="5" id="101706" score="1414116972"/>

        $dataPlayersRank = [];

        foreach ($playersRankXml as $playerRankXml) {
            $dataPlayerRank              = [];
            $dataPlayerRank['rank']      = (int) $playerRankXml[0]['position'];
            $dataPlayerRank['player_id'] = (int) $playerRankXml[0]['id'];

            $dataPlayerRank['points'] = (string) $playerRankXml[0]['score']; // / strint car possible perte d info bigint

            if ($this->isShips) {
                $dataPlayerRank['nb_spacecraft'] = (int) $playerRankXml[0]['ships'] ?? 0; // / strint car possible perte d info bigint
            }

            $dataPlayerRank['datadate'] = $datadate;

            // todo sender_id
            $dataPlayersRank[] = $dataPlayerRank;
        }
        // instance dans le constructeur
        // $this->model = new Pax_Rankings_Player_Model();

        if ($this->model->saveMultiple($dataPlayersRank)) {
            $pax_logger->info('Enregistrement des classements des joueurs effectue avec succes');
            $this->response->setMessage('Enregistrement effectué  ' . $this->endpoint);

            return $this->response;
        }

        // si erreur
        $pax_logger->error('Erreur lors de l\'enregistrement des classements des joueurs');
        $this->response->setError('Une erreur est survenue ' . $this->endpoint);
        $this->response->setMessage('Une erreur est survenue ' . $this->endpoint);

        return $this->response;
    }
}

class ClassPlayers_rank_points extends ClassPlayers_rank
{
    public function traitement(): StepperResponse
    {
        $this->model->setTable(TABLE_RANK_PLAYER_POINTS);

        return parent::traitement();
    }
}
class ClassPlayers_rank_eco extends ClassPlayers_rank
{
    public function traitement(): StepperResponse
    {
        $this->model->setTable(TABLE_RANK_PLAYER_ECO);

        return parent::traitement();
    }
}
class ClassPlayers_rank_technology extends ClassPlayers_rank
{
    public function traitement(): StepperResponse
    {
        $this->model->setTable(TABLE_RANK_PLAYER_TECHNOLOGY);

        return parent::traitement();
    }
}
class ClassPlayers_rank_military extends ClassPlayers_rank
{
    public function traitement(): StepperResponse
    {
        $this->isShips = true;
        $this->model->setTable(TABLE_RANK_PLAYER_MILITARY);

        return parent::traitement();
    }
}
class ClassPlayers_rank_military_built extends ClassPlayers_rank
{
    public function traitement(): StepperResponse
    {
        $this->model->setTable(TABLE_RANK_PLAYER_MILITARY_BUILT);

        return parent::traitement();
    }
}
class ClassPlayers_rank_military_destroyed extends ClassPlayers_rank
{
    public function traitement(): StepperResponse
    {
        $this->model->setTable(TABLE_RANK_PLAYER_MILITARY_DESTRUCT);

        return parent::traitement();
    }
}
class ClassPlayers_rank_military_lost extends ClassPlayers_rank
{
    public function traitement(): StepperResponse
    {
        $this->model->setTable(TABLE_RANK_PLAYER_MILITARY_LOOSE);

        return parent::traitement();
    }
}
class ClassPlayers_rank_military_honnor extends ClassPlayers_rank
{
    public function traitement(): StepperResponse
    {
        $this->model->setTable(TABLE_RANK_PLAYER_HONOR);

        return parent::traitement();
    }
}
