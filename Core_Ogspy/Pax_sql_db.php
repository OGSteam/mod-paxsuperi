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
 * OGSpy MySQL Database Class
 */
class Pax_sql_db extends sql_db
{
    private $transactionInProgress = false;

    /**
     * Une transaction est elle possible
     *
     * @return bool True si aucune transaction n'est en cours, false sinon.
     */
    public function isTransactionReady(): bool
    {
        return ! $this->transactionInProgress;
    }

    /**
     * Start MySQL Transaction
     *
     * @param string $mode Transaction mode ('begin', 'start', 'commit', 'rollback')
     *
     * @return bool Success or failure
     *
     * @throws ErrorException Si une transaction est déjà en cours et qu'on essaie d'en démarrer une nouvelle.
     */
    public function sql_transaction($mode = 'begin')
    {
        global $pax_logger;
        $pax_logger->info('Tentative de transaction MySQL avec le mode: ' . $mode);
        
        switch (strtolower($mode)) {
            case 'begin':
            case 'start':
                if ($this->transactionInProgress) {
                    $pax_logger->error('Tentative de demarrage d\'une nouvelle transaction alors qu\'une transaction est deja en cours');
                    throw new ErrorException("Une transaction est déjà en cours. Veuillez la finaliser avant d'en démarrer une nouvelle.");
                }
                $result = mysqli_autocommit($this->db_connect_id, false)
                    && mysqli_query($this->db_connect_id, 'START TRANSACTION');
                if ($result) {
                    $this->transactionInProgress = true;
                    $pax_logger->info('Transaction MySQL demarree avec succes');
                } else {
                    $pax_logger->error('Echec du demarrage de la transaction MySQL');
                }

                return $result;

            case 'commit':
                $result = mysqli_commit($this->db_connect_id);
                mysqli_autocommit($this->db_connect_id, true);
                $this->transactionInProgress = false;
                
                if ($result) {
                    $pax_logger->info('Transaction MySQL validee avec succes');
                } else {
                    $pax_logger->error('Echec de la validation de la transaction MySQL');
                }

                return $result;

            case 'rollback':
                $result = mysqli_rollback($this->db_connect_id);
                mysqli_autocommit($this->db_connect_id, true);
                $this->transactionInProgress = false;
                
                if ($result) {
                    $pax_logger->info('Transaction MySQL annulee avec succes');
                } else {
                    $pax_logger->error('Echec de l\'annulation de la transaction MySQL');
                }

                return $result;

            default:
                return false;
        }
    }

    /**
     * Destructeur de la classe.
     * 
     * Ce destructeur verifie si une transaction est en cours et l'annule
     * si necessaire pour eviter les transactions orphelines.
     */
    public function __destruct()
    {
        global $pax_logger;
        // si oubli avec transaction en cours
        if ($this->transactionInProgress) {
            $pax_logger->warning('Transaction MySQL orpheline detectee et annulee automatiquement');
            mysqli_rollback($this->db_connect_id);
            mysqli_autocommit($this->db_connect_id, true);
            $this->transactionInProgress = false;
        }
    }
}
