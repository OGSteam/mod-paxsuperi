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

class AbstractClass
{
    protected StepperResponse $response;
    protected string $endpoint;

    public function __construct(string $endpoint)
    {
        $this->response = new StepperResponse();
        $this->endpoint = $endpoint;
    }

    public function download(): StepperResponse
    {
        // téléchargement du fichier xml correspondant au endpoint
        // Initialisation
        $pays          = pax_mod_get_option('pays');
        $uni           = pax_mod_get_option('uni');
        $temporisation = (int) pax_mod_get_option('temporisation');

        $xmlManager = new XmlManager($pays, $uni);
        $endpoints  = Constant::getEndpoint();

        if (! $this->isValidRequestEndpoint()) {
            return $this->response;
        }
        if (! $xmlManager->isUpToDate($this->endpoint)) {
            $playersXml = $xmlManager->downloadXml($this->endpoint);
            $this->response->setMessage('Téléchargement de endpoints ' . $this->endpoint . ' terminé.');
            sleep($temporisation);
        } else {
            $this->response->setMessage('Le fichier ' . $this->endpoint . '.xml est déjà à jour.');
        }

        return $this->response;
        // 1. Télécharger et stocker la liste des joueurs
        // foreach ($endpoints as $endpoint) {
        //    var_dump($xmlManager->isUpToDate($endpoint));
        //    echo '<br />';
        // echo 'Pour  ' . $endpoint . ' : <br>';
        //    if (! $xmlManager->isUpToDate($endpoint)) {
        //        $playersXml = $xmlManager->downloadXml($endpoint);
        //        echo 'Téléchargement de endpoints ' . $endpoint . ' terminé.<br>';
        //        sleep($temporisation);
        //    } else {
        //        echo 'Le fichier ' . $endpoint . '.xml est déjà à jour.<br>';
        //    }
    }

        protected function preTraitement(): StepperResponse|bool
    {
        $pays          = pax_mod_get_option('pays');
        $uni           = pax_mod_get_option('uni');

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




    public function traitement()
    {
        if ($this->isValidRequestEndpoint()) {
        }
        $this->response->setError('La fonction traitement de ' . $this->endpoint . ' n\'existe pas.');
        $this->response->setMessage('Lafonction traitement de   ' . $this->endpoint . ' n\'existe pas.');

        return $this->response;
    }

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
