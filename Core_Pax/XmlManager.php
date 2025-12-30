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
 * Classe pour la gestion des fichiers XML.
 *
 * Cette classe fournit des methodes pour telecharger, stocker et recuperer
 * les fichiers XML depuis l'API d'OGame.
 *
 * @category   PaxSuperi
 */
class XmlManager
{
    /**
     * @var string Chemin où stocker les fichiers XML.
     */
    private string $folderSavePath;

    /**
     * @var string Code du pays.
     */
    private string $pays;

    /**
     * @var string Numéro de l'univers.
     */
    private string $uni;

    /**
     * Constructeur de la classe.
     *
     * @param string      $pays           Code du pays (ex: 'fr').
     * @param string      $uni            Numéro de l'univers (ex: '123').
     * @param string|null $folderSavePath Chemin où stocker les fichiers XML (ex: 'storage/').
     *                                    Si null, utilise MOD_ROOT_XML.
     */
    public function __construct(string $pays, string $uni, ?string $folderSavePath = null)
    {
        if ($folderSavePath === null) {
            $folderSavePath = MOD_ROOT_XML;
        }
        $this->pays = $pays;
        $this->uni  = $uni;

        $this->folderSavePath = $folderSavePath;
        $this->FolderExist();
    }

    /**
     * Telecharge un fichier XML depuis l'API OGame et le stocke localement.
     *
     * Cette methode telecharge un fichier XML depuis l'API OGame en utilisant
     * le nom de la constante pour construire l'URL, puis stocke le fichier
     * localement dans le dossier specifie.
     *
     * @param string $constantName Nom de la constante (ex: 'CST_PLAYERS').
     * @param string $pays         Code du pays (ex: 'fr').
     * @param string $uni          Numéro de l'univers (ex: '123').
     *
     * @return string Contenu du fichier XML.
     */
    public function downloadXml(string $constantName): string
    {
        $url = UrlBuilder::build($constantName, $this->pays, $this->uni);
        $xml = $this->fetchUrl($url);

        // Sauvegarde le fichier localement
        $filename = $this->getFilename($constantName);
        $this->saveToFile($filename, $xml);

        return $xml;
    }

    /**
     * Récupère un fichier XML depuis le stockage local.
     *
     * @param string $constantName Nom de la constante
     * @param string $pays         Code du pays
     * @param string $uni          Numéro de l'univers
     *
     * @return string|null Contenu du fichier XML ou null s'il n'existe pas
     */
    public function getLocalXml(string $constantName): ?string
    {
        $filename = $this->getFilename($constantName);
        $filepath = $this->folderSavePath . $filename;

        return file_exists($filepath) ? file_get_contents($filepath) : null;
    }

    /**
     * Vérifie si le fichier XML local est à jour (selon la durée de validité définie dans Constant).
     *
     * @param string $constantName Nom de la constante
     * @param string $pays         Code du pays
     * @param string $uni          Numéro de l'univers
     *
     * @return bool True si le fichier est à jour, false sinon
     */
    public function isUpToDate(string $constantName): bool
    {
        $filename = $this->getFilename($constantName);
        $filepath = $this->folderSavePath . $filename;

        // si pas de fichier
        if (! file_exists($filepath)) {
            return false;
        }

        // récuperation de la date
        $partialXml = substr($this->getLocalXml($constantName), 0, 300);
        preg_match('/.*timestamp.\"([0-9]{10}).*/', $partialXml, $matches);
        $timestamp = $matches[1] ?? 0;

        if (Constant::getCst()[$constantName]['isRank']) {
            $timestamp = $this->formatage_timestamp_for_rank($timestamp);
        }

        $validity = Constant::getCst()[$constantName]['validity'] * 3600; // Convertit les heures en secondes

        // var_dump(time() - $timestamp);echo' <br />';
        return (time() - $timestamp) < $validity;
    }

    /**
     * Télécharge le contenu d'une URL.
     *
     * @param string $url URL à télécharger
     *
     * @return string Contenu téléchargé
     *
     * @throws RuntimeException En cas d'erreur
     */
    private function fetchUrl(string $url): string
    {
        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => "User-Agent: OGSPY - Pax Superi!\r\n",
            ],
        ]);

        $content = @file_get_contents($url, false, $context);
        if ($content === false) {
            $error = error_get_last();

            throw new RuntimeException('Erreur lors du téléchargement : ' . $error['message']);
        }

        return $content;
    }

    /**
     * Sauvegarde le contenu dans un fichier.
     *
     * @param string $filename Nom du fichier
     * @param string $content  Contenu à sauvegarder
     */
    private function saveToFile(string $filename, string $content): void
    {
        file_put_contents($this->folderSavePath . $filename, $content);
    }

    /**
     * Génère un nom de fichier unique.
     *
     * @param string $constantName Nom de la constante
     * @param string $pays         Code du pays
     * @param string $uni          Numéro de l'univers
     *
     * @return string Nom du fichier
     */
    private function getFilename(string $constantName): string
    {
        return "{$constantName}_{$this->pays}_{$this->uni}.xml";
    }

    /**
     * Crée le dossier de stockage s'il n'existe pas.
     */
    private function FolderExist(): void
    {
        if (! file_exists($this->folderSavePath)) {
            mkdir($this->folderSavePath, 0755, true);
        }
    }

    private function formatage_timestamp_for_rank($time)
    {
        // / il faut garder le format ogspy ( toutes les 8 heeures ... ) )
        $temp = getdate($time);

        // on format la date
        $temp['seconds'] = 0;
        $temp['minutes'] = 0;
        if ($temp['hours'] >= 0 && $temp['hours'] < 8) {
            $temp['hours'] = 0;
        }
        if ($temp['hours'] >= 8 && $temp['hours'] < 16) {
            $temp['hours'] = 8;
        }
        if ($temp['hours'] >= 16 && $temp['hours'] < 24) {
            $temp['hours'] = 16;
        }

        return mktime($temp['hours'], $temp['minutes'], $temp['seconds'], $temp['mon'], $temp['mday'], $temp['year']);
    }
}
