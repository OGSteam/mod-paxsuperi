<?php

/**
 * Classe de gestion des logs pour PaxSuperi.
 * 
 * Cette classe utilise la variable globale $log pour les logs et fournit
 * des methodes pour logger des messages a differents niveaux (info, warning,
 * error, debug, critical).
 * 
 * @category   PaxSuperi
 * @package    Core_Pax
 * @subpackage Logger
 *
 * @example
 * // Initialisation du logger
 * $logger = new Pax_Logger(true);
 * 
 * // Log un message d'information
 * $logger->info('Message d\'information');
 * 
 * // Log un message d'erreur
 * $logger->error('Message d\'erreur');
 */
class Pax_Logger
{
    /**
     * @var bool Indique si les logs sont activés.
     * 
     * Si cette variable est a true, les logs seront envoyes.
     * Si elle est a false, les logs ne seront pas envoyes.
     */
    private $debug = false;

    /**
     * Constructeur.
     *
     * @param bool $debug Active ou désactive les logs.
     */
    public function __construct($debug = false)
    {
        $this->debug = $debug;
    }

    /**
     * Active les logs.
     */
    public function enableDebug()
    {
        $this->debug = true;
    }

    /**
     * Désactive les logs.
     */
    public function disableDebug()
    {
        $this->debug = false;
    }

    /**
     * Vérifie si les logs sont activés.
     *
     * @return bool
     */
    public function isDebugEnabled()
    {
        return $this->debug;
    }

    /**
     * Ajoute le préfixe [paxsuperi] à un message.
     *
     * @param string $message Le message à préfixer.
     * @return string Le message préfixé.
     */
    private function addPrefix($message)
    {
        return '[paxsuperi] ' . $message;
    }

    /**
     * Log un message d'information.
     *
     * @param string $message Le message à logger.
     * @param array $context Contexte supplémentaire pour le log.
     */
    public function info($message, array $context = array())
    {
        if ($this->debug && isset($GLOBALS['log'])) {
            $GLOBALS['log']->info($this->addPrefix($message), $context);
        }
    }

    /**
     * Log un message d'avertissement.
     *
     * @param string $message Le message à logger.
     * @param array $context Contexte supplémentaire pour le log.
     */
    public function warning($message, array $context = array())
    {
        if ($this->debug && isset($GLOBALS['log'])) {
            $GLOBALS['log']->warning($this->addPrefix($message), $context);
        }
    }

    /**
     * Log un message d'erreur.
     *
     * @param string $message Le message à logger.
     * @param array $context Contexte supplémentaire pour le log.
     */
    public function error($message, array $context = array())
    {
        if ($this->debug && isset($GLOBALS['log'])) {
            $GLOBALS['log']->error($this->addPrefix($message), $context);
        }
    }

    /**
     * Log un message de debug.
     *
     * @param string $message Le message à logger.
     * @param array $context Contexte supplémentaire pour le log.
     */
    public function debug($message, array $context = array())
    {
        if ($this->debug && isset($GLOBALS['log'])) {
            $GLOBALS['log']->debug($this->addPrefix($message), $context);
        }
    }

    /**
     * Log un message critique.
     *
     * @param string $message Le message à logger.
     * @param array $context Contexte supplémentaire pour le log.
     */
    public function critical($message, array $context = array())
    {
        if ($this->debug && isset($GLOBALS['log'])) {
            $GLOBALS['log']->critical($this->addPrefix($message), $context);
        }
    }

    /**
     * Log une exception.
     *
     * @param Exception $exception L'exception à logger.
     * @param array $context Contexte supplémentaire pour le log.
     */
    public function logException(Exception $exception, array $context = array())
    {
        if ($this->debug && isset($GLOBALS['log'])) {
            $message = sprintf(
                '[paxsuperi] Exception: %s in %s on line %d',
                $exception->getMessage(),
                $exception->getFile(),
                $exception->getLine()
            );
            $GLOBALS['log']->error($message, array_merge($context, ['exception' => $exception]));
        }
    }
}
