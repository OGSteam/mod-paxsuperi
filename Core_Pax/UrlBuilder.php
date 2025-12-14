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

class UrlBuilder
{
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
