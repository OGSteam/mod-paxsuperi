<?php

/**
 * OGSPY - Mod PAx Superi
 *
 * @package [Mod] Pax Superi
 * @author Mistral Vibe
 * @copyright Copyright &copy; 2024, https://ogsteam.eu/
 * @license https://opensource.org/licenses/gpl-license.php GNU Public License
 */

if (! defined('IN_SPYOGAME')) {
    exit('Hacking attempt');
}

/**
 * Interface pour la gestion des fichiers XML.
 */
interface XmlManagerInterface
{
    /**
     * Télécharge un fichier XML depuis l'API OGame.
     *
     * @param string $constantName Nom de la constante
     * @return string Contenu XML
     */
    public function downloadXml(string $constantName): string;

    /**
     * Récupère le fichier XML local.
     *
     * @param string $constantName Nom de la constante
     * @return string|null Contenu XML ou null si le fichier n'existe pas
     */
    public function getLocalXml(string $constantName): ?string;

    /**
     * Vérifie si le fichier XML est à jour.
     *
     * @param string $constantName Nom de la constante
     * @return bool True si le fichier est à jour
     */
    public function isUpToDate(string $constantName): bool;

    /**
     * Récupère le chemin du fichier XML.
     *
     * @param string $constantName Nom de la constante
     * @return string Chemin du fichier
     */
    public function getFilePath(string $constantName): string;
}