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
        switch (strtolower($mode)) {
            case 'begin':
            case 'start':
                if ($this->transactionInProgress) {
                    throw new ErrorException("Une transaction est déjà en cours. Veuillez la finaliser avant d'en démarrer une nouvelle.");
                }
                $result = mysqli_autocommit($this->db_connect_id, false)
                    && mysqli_query($this->db_connect_id, 'START TRANSACTION');
                if ($result) {
                    $this->transactionInProgress = true;
                }

                return $result;

            case 'commit':
                $result = mysqli_commit($this->db_connect_id);
                mysqli_autocommit($this->db_connect_id, true);
                $this->transactionInProgress = false;

                return $result;

            case 'rollback':
                $result = mysqli_rollback($this->db_connect_id);
                mysqli_autocommit($this->db_connect_id, true);
                $this->transactionInProgress = false;

                return $result;

            default:
                return false;
        }
    }

    public function __destruct()
    {
        // si oubli avec transaction en cours
        if ($this->transactionInProgress) {
            // TODO Journaliser le probleme
            mysqli_rollback($this->db_connect_id);
            mysqli_autocommit($this->db_connect_id, true);
            $this->transactionInProgress = false;
        }
    }
}
