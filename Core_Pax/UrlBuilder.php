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
 * Classe pour la construction des URLs.
 *
 * Cette classe fournit des methodes pour construire les URLs
 * utilisees pour acceder a l'API d'OGame.
 *
 * @category   PaxSuperi
 */
class UrlBuilder
{
    /**
     * Construit une URL pour l'API d'OGame.
     *
     * Cette methode construit une URL pour l'API d'OGame en utilisant
     * les constantes definies dans la classe Constant et en remplacant
     * les placeholders par les valeurs specifiques.
     *
     * @param string $constantName Nom de la constante definie dans la classe Constant.
     * @param string $pays         Pays pour lequel l'URL doit etre construite.
     * @param string $uni          Univers pour lequel l'URL doit etre construite.
     *
     * @return string|null URL construite ou null si la constante n'est pas valide.
     */
    public static function build(string $constantName, string $pays, string $uni): ?string
    {
        $validEndpoints = Constant::getEndpoint();
        if (! in_array($constantName, $validEndpoints, true)) {
            return null;
        }

        // Récupère les données de la constante
        $constants = Constant::getCst();
        $config    = $constants[$constantName];

        $url = $config['url'];

        return str_replace(['{uni}', '{pays}'], [$uni, $pays], $url);
    }
}
