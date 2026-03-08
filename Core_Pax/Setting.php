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

require_once __DIR__ . '/Interfaces/SettingInterface.php';

/**
 * Classe pour la gestion des parametres de configuration.
 *
 * Cette classe fournit des methodes pour recuperer et gerer
 * les parametres de configuration du module PaxSuperi.
 * Elle implemente le pattern singleton pour s'assurer qu'une seule
 * instance de la classe est creee.
 *
 * @category   PaxSuperi
 *
 * @example
 * // Recuperer l'instance de la classe Setting
 * $setting = Setting::getInstance();
 *
 * // Recuperer un parametre de configuration
 * $pays = $setting->pays;
 *
 * // Modifier un parametre de configuration
 * $setting->pays = 'fr';
 */
class Setting implements SettingInterface
{
    /**
     * @var Setting|null Instance unique de la classe (singleton).
     */
    private static $instance;

    /**
     * @var array Donnees de configuration.
     *
     * Ce tableau stocke les valeurs des parametres de configuration
     * recuperes depuis la base de donnees.
     */
    private array $datas;

    /**
     * @var string Nom du module.
     *
     * Ce nom est utilise pour identifier le module dans la base de donnees.
     */
    private string $modName = 'paxsuperi';

    /**
     * @var array Liste des parametres de configuration autorises.
     *
     * Les parametres autorises sont :
     * - pays : Code du pays (ex: 'fr').
     * - uni : Numero de l'univers (ex: '123').
     * - temporisation : Delai entre les requetes (en secondes).
     * - stepperRunning : Indique si le stepper est en cours d'execution.
     * - currentStep : Etape courante du stepper.
     * - lastRunning : Derniere execution du stepper.
     * - lastRunningSecurity : Derniere execution du stepper pour la securite.
     * - debug : Active ou desactive les logs.
     */
    private array $allowedConf = [
        'pays',
        'uni',
        'temporisation',
        'stepperRunning',
        'currentStep',
        'lastRunning',
        'lastRunningSecurity',
        'debug',
    ];

    /**
     * @var array Liste des parametres de configuration modifiables.
     *
     * Les parametres modifiables sont :
     * - pays : Code du pays (ex: 'fr').
     * - uni : Numero de l'univers (ex: '123').
     * - temporisation : Delai entre les requetes (en secondes).
     * - debug : Active ou desactive les logs.
     */
    private array $allowedConfMod = [
        'pays',
        'uni',
        'temporisation',
        'debug',
    ];

    /**
     * Constructeur prive pour implementer le pattern singleton.
     *
     * Ce constructeur initialise les donnees de configuration en recuperant
     * les valeurs des parametres autorises depuis la base de donnees.
     *
     * @throws Exception Si un parametre de configuration n'est pas autorise.
     */
    private function __construct()
    {
        $this->datas = [];

        // recuperation des tous les items
        foreach ($this->allowedConf as $item) {
            $this->datas[$item] = $this->pax_mod_get_option($item);
        }
    }

    /**
     * Recupere l'instance unique de la classe (singleton).
     *
     * Cette methode implemente le pattern singleton pour s'assurer
     * qu'une seule instance de la classe est creee.
     *
     * @return self Instance unique de la classe.
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Reinitialise les parametres de configuration non essentiels.
     *
     * Cette methode reinitialise les parametres de configuration
     * qui ne sont pas essentiels au fonctionnement du module.
     */
    public function resetCurrentUse(): void
    {
        $tabAllowedConf = array_diff($this->allowedConf, $this->allowedConfMod);

        // remise a 0 des elements non essentielles au focntionnement du mod
        foreach ($tabAllowedConf as $item) {
            $this->{$item} = 0;
        }
    }

    /**
     * Recupere un parametre de configuration.
     *
     * Cette methode permet de recuperer un parametre de configuration
     * en utilisant la syntaxe $setting->parametre.
     *
     * @param string $name Nom du parametre de configuration.
     *
     * @return mixed|null Valeur du parametre de configuration ou null si le parametre n'existe pas.
     */
    public function __get(string $name): mixed
    {
        return $this->datas[$name] ?? null;
    }

    /**
     * Definit un parametre de configuration.
     *
     * Cette methode permet de definir un parametre de configuration
     * en utilisant la syntaxe $setting->parametre = valeur.
     *
     * @param string $name  Nom du parametre de configuration.
     * @param mixed  $value Valeur du parametre de configuration.
     *
     * @throws Exception Si le parametre de configuration n'est pas autorise.
     */
    public function __set(string $name, mixed $value): void
    {
        if (! in_array($name, $this->allowedConf, true)) {
            throw new Exception("setting non autorisé  : {$name}");
        }

        $this->pax_mod_set_option($name, $value);
        $this->datas[$name] = $value;
    }

    /**
     * Recupere un parametre de configuration depuis la base de donnees.
     *
     * Cette methode est utilisee en interne pour recuperer les parametres
     * de configuration depuis la base de donnees.
     *
     * @param string $param Nom du parametre de configuration.
     *
     * @return mixed Valeur du parametre de configuration.
     */
    private function pax_mod_get_option($param)
    {
        return mod_get_option($param, $this->modName);
    }

    /**
     * Definit un parametre de configuration dans la base de donnees.
     *
     * Cette methode est utilisee en interne pour definir les parametres
     * de configuration dans la base de donnees.
     *
     * @param string $param Nom du parametre de configuration.
     * @param mixed  $value Valeur du parametre de configuration.
     *
     * @return mixed Resultat de l'operation.
     */
    private function pax_mod_set_option($param, $value)
    {
        return mod_set_option($param, $value, $this->modName);
    }
}
