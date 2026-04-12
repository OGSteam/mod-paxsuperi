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
 * Interface pour la gestion des paramètres de configuration.
 */
interface SettingInterface
{
    /**
     * Récupère un paramètre de configuration.
     *
     * @param string $name Nom du paramètre
     *
     * @return mixed Valeur du paramètre
     */
    public function __get(string $name): mixed;

    /**
     * Définit un paramètre de configuration.
     *
     * @param string $name  Nom du paramètre
     * @param mixed  $value Valeur du paramètre
     *
     * @throws Exception Si le paramètre n'est pas autorisé
     */
    public function __set(string $name, mixed $value): void;

    /**
     * Réinitialise les paramètres de configuration non essentiels.
     */
    public function resetCurrentUse(): void;
}
