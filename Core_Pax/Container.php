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
 * Container d'Injection de Dépendances simple pour PaxSuperi.
 *
 * Ce container permet de gérer les dépendances et leurs instances
 * sans utiliser de bibliothèques externes.
 */
class Container
{
    /**
     * @var array<string, callable> Définitions des services.
     */
    private array $services = [];

    /**
     * @var array<string, object> Instances des services singleton.
     */
    private array $instances = [];

    /**
     * Enregistre un service dans le container.
     *
     * @param string $abstract Nom abstrait du service (interface ou classe)
     * @param callable $concrete Fonction de création du service
     */
    public function bind(string $abstract, callable $concrete): void
    {
        $this->services[$abstract] = $concrete;
    }

    /**
     * Enregistre un service singleton dans le container.
     *
     * @param string $abstract Nom abstrait du service
     * @param callable $concrete Fonction de création du service
     */
    public function singleton(string $abstract, callable $concrete): void
    {
        $this->services[$abstract] = $concrete;
        $this->instances[$abstract] = null;
    }

    /**
     * Résout et retourne une instance du service.
     *
     * @param string $abstract Nom du service à résoudre
     * @param array $parameters Paramètres optionnels pour la création
     * @return object Instance du service
     * @throws \RuntimeException Si le service n'est pas trouvé
     */
    public function make(string $abstract, array $parameters = []): object
    {
        if (!isset($this->services[$abstract])) {
            throw new \RuntimeException("Service {$abstract} not found in container.");
        }

        // Retourne l'instance singleton si elle existe
        if (isset($this->instances[$abstract]) && $this->instances[$abstract] !== null) {
            return $this->instances[$abstract];
        }

        // Crée une nouvelle instance
        $instance = $this->services[$abstract]($this, $parameters);

        // Stocke l'instance si c'est un singleton
        if (isset($this->instances[$abstract])) {
            $this->instances[$abstract] = $instance;
        }

        return $instance;
    }

    /**
     * Vérifie si un service est enregistré.
     *
     * @param string $abstract Nom du service
     * @return bool True si le service existe
     */
    public function has(string $abstract): bool
    {
        return isset($this->services[$abstract]);
    }

    /**
     * Réinitialise le container (utile pour les tests).
     */
    public function reset(): void
    {
        $this->services = [];
        $this->instances = [];
    }
}