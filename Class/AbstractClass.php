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
 * Classe abstraite pour la gestion des donnees.
 * 
 * Cette classe abstraite fournit des methodes de base pour telecharger,
 * traiter et gerer les donnees depuis l'API d'OGame.
 * Elle est heritee par les classes specifiques pour chaque type de donnee
 * (joueurs, alliances, univers, etc.).
 *
 * @category   PaxSuperi
 * @package    Class
 * @subpackage AbstractClass
 *
 * @example
 * // Creer une classe specifique pour un type de donnee
 * class ClassPlayers extends AbstractClass
 * {
 *     public function traitement(): StepperResponse
 *     {
 *         // Implementation specifique pour le traitement des joueurs
 *     }
 * }
 * 
 * // Utiliser la classe specifique
 * $players = new ClassPlayers('CST_PLAYERS');
 * $players->download();
 * $players->traitement();
 */
class AbstractClass
{
    /**
     * @var Setting Instance des parametres de configuration.
     */
    protected Setting $setting ;
    
    /**
     * @var StepperResponse Reponse du stepper.
     */
    protected StepperResponse $response;
    
    /**
     * @var string Point de terminaison de l'API pour recuperer les donnees.
     */
    protected string $endpoint;

    /**
     * Constructeur de la classe.
     *
     * Ce constructeur initialise les parametres de configuration,
     * la reponse du stepper et le point de terminaison de l'API.
     *
     * @param string $endpoint Point de terminaison de l'API pour recuperer les donnees.
     */
    public function __construct(string $endpoint)
    {
        $this->setting = Setting::getInstance();
        $this->response = new StepperResponse();
        $this->endpoint = $endpoint;
    }

    /**
     * Telecharge les donnees depuis l'API d'OGame.
     *
     * Cette methode telecharge les donnees depuis l'API d'OGame
     * et les stocke localement dans un fichier XML.
     *
     * @return StepperResponse Reponse du stepper.
     *
     * @throws Exception Si une erreur survient lors du telechargement.
     */
    public function download(): StepperResponse
    {
        // telechargement du fichier xml correspondant au endpoint
        // Initialisation
        $pays          =  $this->setting->pays;
        $uni           = (int)$this->setting->uni; 
        $temporisation = (int) $this->setting->temporisation;  

        $xmlManager = new XmlManager($pays, $uni);
        $endpoints  = Constant::getEndpoint();

        if (! $this->isValidRequestEndpoint()) {
            return $this->response;
        }
        if (! $xmlManager->isUpToDate($this->endpoint)) {
            $playersXml = $xmlManager->downloadXml($this->endpoint);
            $this->response->setMessage('Telechargement de endpoints ' . $this->endpoint . ' termine.');
            sleep($temporisation);
        } else {
            $this->response->setMessage('Le fichier ' . $this->endpoint . '.xml est deja a jour.');
        }

        return $this->response;
        // 1. Telecharger et stocker la liste des joueurs
        // foreach ($endpoints as $endpoint) {
        //    var_dump($xmlManager->isUpToDate($endpoint));
        //    echo '<br />';
        // echo 'Pour  ' . $endpoint . ' : <br>';
        //    if (! $xmlManager->isUpToDate($endpoint)) {
        //        $playersXml = $xmlManager->downloadXml($endpoint);
        //        echo 'Telechargement de endpoints ' . $endpoint . ' termine.<br>';
        //        sleep($temporisation);
        //    } else {
        //        echo 'Le fichier ' . $endpoint . '.xml est deja a jour.<br>';
        //    }
    }

        /**
     * Effectue le pre-traitement des donnees.
     *
     * Cette methode verifie si le fichier XML est a jour avant de
     * procéder au traitement des donnees.
     *
     * @return StepperResponse|bool Reponse du stepper ou true si le pre-traitement reussi.
     *
     * @throws Exception Si une erreur survient lors du pre-traitement.
     */
    protected function preTraitement(): StepperResponse|bool
    {
        $pays          =  $this->setting->pays;
        $uni           = (int)$this->setting->uni; 


        $xmlManager = new XmlManager($pays, $uni);
        $endpoints  = Constant::getEndpoint();

        if (! $this->isValidRequestEndpoint()) {
            return $this->response;
        }
        if (! $xmlManager->isUpToDate($this->endpoint)) {
            $this->response->setError('Le fichier XML ' . $this->endpoint . '  n\'est pas a jour.');
            $this->response->setMessage('Le fichier XML ' . $this->endpoint . '  n\'est pas a jour.');

            return $this->response;
        }
        return true;
    }




    /**
     * Traite les donnees.
     *
     * Cette methode est appelee pour traiter les donnees telechargees.
     * Elle doit etre implementee par les classes specifiques.
     *
     * @return StepperResponse Reponse du stepper.
     *
     * @throws Exception Si une erreur survient lors du traitement.
     */
    public function traitement()
    {
        if ($this->isValidRequestEndpoint()) {
        }
        $this->response->setError('La fonction traitement de ' . $this->endpoint . ' n\'existe pas.');
        $this->response->setMessage('Lafonction traitement de   ' . $this->endpoint . ' n\'existe pas.');

        return $this->response;
    }

    /**
     * Verifie si le point de terminaison est valide.
     *
     * Cette methode verifie si le point de terminaison specifie
     * est valide et existe dans les constantes definies.
     *
     * @return bool True si le point de terminaison est valide, false sinon.
     *
     * @throws Exception Si le point de terminaison n'est pas valide.
     */
    protected function isValidRequestEndpoint(): bool
    {
        Constant::getCst()[$this->endpoint];
        if (! isset(Constant::getCst()[$this->endpoint])) {
            $this->response->setError('La demande ' . $this->endpoint . ' n\'existe pas.');
            $this->response->setMessage('La demande  ' . $this->endpoint . ' n\'existe pas.');

            return false;
        }

        return true;
    }
}
