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
 * Interface pour le logger.
 */
interface LoggerInterface
{
    /**
     * Log un message d'information.
     *
     * @param string $message Message à logger
     * @param array  $context Contexte supplémentaire
     */
    public function info(string $message, array $context = []): void;

    /**
     * Log un message de debug.
     *
     * @param string $message Message à logger
     * @param array  $context Contexte supplémentaire
     */
    public function debug(string $message, array $context = []): void;

    /**
     * Log un message d'avertissement.
     *
     * @param string $message Message à logger
     * @param array  $context Contexte supplémentaire
     */
    public function warning(string $message, array $context = []): void;

    /**
     * Log un message d'erreur.
     *
     * @param string $message Message à logger
     * @param array  $context Contexte supplémentaire
     */
    public function error(string $message, array $context = []): void;
}
