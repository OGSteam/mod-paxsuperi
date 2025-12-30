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

class ClassUniverse extends AbstractClass
{
    protected  $astroModel;



    public function __construct(string $endpoint)
    {
        parent::__construct($endpoint);
        $this->astroModel = new Pax_Astro_Object_Model();
    }


    public function traitement(): StepperResponse
    {
        if ($this->preTraitement() !== true) {
            return $this->response;
        }

        $pays = $this->setting->pays;
        $uni = (int)$this->setting->uni;

        $xmlManager = new XmlManager($pays, $uni);
        $universeXmlString = $xmlManager->getLocalXml($this->endpoint);
        $universeXml = simplexml_load_string($universeXmlString);

        // Récupération du timestamp
        $datadate = (int)$universeXml->attributes()->timestamp;

        $dataAstros = array();

        foreach ($universeXml->planet as $astroObjecttXml) {
            $t_coordonnee = explode(':', $astroObjecttXml[0]['coords']); // récuperation coord en cours
            $galaxy = (int)$t_coordonnee[0];
            $system = (int)$t_coordonnee[1];
            $row = (int) $t_coordonnee[2];
            $player_id  = (int) $astroObjecttXml[0]['player'];

            $dataAstro = array();

            // Planete
            $dataAstro = array();
            //commun
            $dataAstro['galaxy'] =    $galaxy;
            $dataAstro['system'] = $system;
            $dataAstro['row'] = $row;
            $dataAstro['last_update'] = $datadate;
            $dataAstro['player_id'] =  $player_id;
            //specifique
            $dataAstro['id'] = (int)$astroObjecttXml[0]['id'];
            $dataAstro['type']  = TYPE_PLANET; // 0=> planete / 1 => lune
            $dataAstro['name'] = strval($astroObjecttXml[0]['name']);


            $dataAstros[] = $dataAstro;

            //lune
            if (isset($astroObjecttXml->moon)) {
                $dataAstro = array();
                //commun
                $dataAstro['galaxy'] =    $galaxy;
                $dataAstro['system'] = $system;
                $dataAstro['row'] = $row;
                $dataAstro['last_update'] = $datadate;
                $dataAstro['player_id'] =  $player_id;
                //specifique
                $dataAstro['id'] = (int)$astroObjecttXml[0]->moon['id'];
                $dataAstro['type']  = TYPE_MOON; // 0=> planete / 1 => lune
                $dataAstro['name'] = strval($astroObjecttXml[0]->moon['name']);

                $dataAstros[] = $dataAstro;
            }
        }
        if ($this->astroModel->saveMultiple($dataAstros)) {
            $this->response->setMessage('Enregistrement effectué  ' . $this->endpoint);
            return $this->response;
        }



        // Si erreur ou aucun objet à enregistrer
        $this->response->setError('Aucun objet astronomique à enregistrer ou erreur lors de l\'enregistrement ' . $this->endpoint);
        $this->response->setMessage('Aucun objet astronomique à enregistrer ou erreur lors de l\'enregistrement ' . $this->endpoint);

        return $this->response;
    }
}
