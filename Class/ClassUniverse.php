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
 * Classe pour la gestion de l'univers.
 *
 * Cette classe herite de AbstractClass et fournit des methodes pour traiter
 * les donnees de l'univers depuis l'API XML d'OGame.
 *
 * @category   PaxSuperi
 */
class ClassUniverse extends AbstractClass
{
    /**
     * @var Pax_Astro_Object_Model Modele pour la gestion des objets astronomiques.
     */
    protected $astroModel;

    /**
     * Constructeur de la classe.
     *
     * @param string $endpoint Point de terminaison de l'API pour recuperer les donnees.
     */
    public function __construct(string $endpoint)
    {
        parent::__construct($endpoint);
        $this->astroModel = new Pax_Astro_Object_Model();
    }

    /**
     * Traite les donnees de l'univers.
     *
     * Cette methode recupere les donnees de l'univers depuis l'API XML,
     * les transforme et les enregistre dans la base de donnees.
     *
     * @return StepperResponse Reponse du traitement.
     *
     * @throws Exception Si une erreur survient lors du traitement.
     */
    public function traitement(): StepperResponse
    {
        global $pax_logger;
        $pax_logger->info('Debut du traitement de l\'univers');

        if ($this->preTraitement() !== true) {
            $pax_logger->warning('Pre-traitement echoue pour l\'univers');

            return $this->response;
        }

        $pays = $this->setting->pays;
        $uni  = (int) $this->setting->uni;
        $pax_logger->debug('Recuperation des donnees pour le pays: ' . $pays . ' et l\'univers: ' . $uni);

        $xmlManager        = new XmlManager($pays, $uni);
        $universeXmlString = $xmlManager->getLocalXml($this->endpoint);
        $universeXml       = simplexml_load_string($universeXmlString);
        $pax_logger->info('Donnees XML de l\'univers chargees avec succes');

        // Recuperation du timestamp
        $datadate = (int) $universeXml->attributes()->timestamp;

        $dataAstros = [];

        foreach ($universeXml->planet as $astroObjecttXml) {
            $t_coordonnee = explode(':', $astroObjecttXml[0]['coords']); // recuperation coord en cours
            $galaxy       = (int) $t_coordonnee[0];
            $system       = (int) $t_coordonnee[1];
            $row          = (int) $t_coordonnee[2];
            $player_id    = (int) $astroObjecttXml[0]['player'];

            $dataAstro = [];

            // Planete
            $dataAstro = [];
            // commun
            $dataAstro['galaxy']      = $galaxy;
            $dataAstro['system']      = $system;
            $dataAstro['row']         = $row;
            $dataAstro['last_update'] = $datadate;
            $dataAstro['player_id']   = $player_id;
            // specifique
            $dataAstro['id']   = (int) $astroObjecttXml[0]['id'];
            $dataAstro['type'] = TYPE_PLANET; // 0=> planete / 1 => lune
            $dataAstro['name'] = (string) ($astroObjecttXml[0]['name']);

            $dataAstros[] = $dataAstro;

            // lune
            if (isset($astroObjecttXml->moon)) {
                $dataAstro = [];
                // commun
                $dataAstro['galaxy']      = $galaxy;
                $dataAstro['system']      = $system;
                $dataAstro['row']         = $row;
                $dataAstro['last_update'] = $datadate;
                $dataAstro['player_id']   = $player_id;
                // specifique
                $dataAstro['id']   = (int) $astroObjecttXml[0]->moon['id'];
                $dataAstro['type'] = TYPE_MOON; // 0=> planete / 1 => lune
                $dataAstro['name'] = (string) ($astroObjecttXml[0]->moon['name']);

                $dataAstros[] = $dataAstro;
            }
        }
        if ($this->astroModel->saveMultiple($dataAstros)) {
            $pax_logger->info('Enregistrement des objets astronomiques effectue avec succes');
            $this->response->setMessage('Enregistrement effectue  ' . $this->endpoint);

            return $this->response;
        }

        // Si erreur ou aucun objet a enregistrer
        $pax_logger->error('Aucun objet astronomique a enregistrer ou erreur lors de l\'enregistrement');
        $this->response->setError('Aucun objet astronomique a enregistrer ou erreur lors de l\'enregistrement ' . $this->endpoint);
        $this->response->setMessage('Aucun objet astronomique a enregistrer ou erreur lors de l\'enregistrement ' . $this->endpoint);

        return $this->response;
    }
}
