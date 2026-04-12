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
 * Classe pour la gestion des reponses du stepper.
 *
 * Cette classe fournit des methodes pour manipuler les reponses du stepper,
 * telles que les messages, les erreurs et les donnees de progression.
 * Elle est utilisee pour communiquer entre le stepper et l'interface utilisateur.
 *
 * @category   PaxSuperi
 */
class StepperResponse
{
    /**
     * @var array Donnees de la reponse.
     *
     * Ce tableau contient les informations sur la reponse du stepper,
     * telles que le statut de succes, les messages, la progression,
     * l'etat de completion et les erreurs.
     */
    private array $data = [
        'success'  => true,
        'message'  => '',
        'progress' => [
            'current_step' => 0,
            'total_steps'  => 0,
        ],
        'completed' => false,
        'error'     => null,
    ];

    /**
     * Concatene une reponse de stepper avec la reponse courante.
     *
     * Cette methode fusionne les donnees d'une autre reponse de stepper
     * avec la reponse courante, en conservant les messages et les erreurs.
     *
     * @param StepperResponse $stepperResponse Reponse de stepper a fusionner.
     */
    public function concatStepperResponse(StepperResponse $stepperResponse): void
    {
        global $pax_logger;
        $pax_logger->debug('Fusion des reponses de stepper');

        $nData = $stepperResponse->getData();
        $this->setMessage($nData['message']);
        if (null !== $nData['error']) {
            $pax_logger->warning('Erreur detectee dans la reponse de stepper: ' . $nData['error']);
            $this->setError($nData['error']);
        }

        $this->data['success'] = $nData['success'];
        $pax_logger->debug('Fusion des reponses de stepper terminee avec succes');
    }

    /**
     * Definit un message dans la reponse.
     *
     * Cette methode permet de definir un message dans la reponse du stepper.
     * Le message peut etre utilise pour informer l'utilisateur de l'etat
     * du traitement.
     *
     * @param string $message Message a definir.
     *
     * @return self Instance courante pour le chainage de methodes.
     */
    public function setMessage(string $message): self
    {
        global $pax_logger;
        $pax_logger->info('Definition du message de reponse: ' . $message);

        $this->data['message'] = $message;

        return $this;
    }

    /**
     * Definit la progression du traitement.
     *
     * Cette methode permet de definir la progression du traitement
     * en specifiant l'etape courante et le nombre total d'etapes.
     * Si l'etape courante est superieure ou egale au nombre total
     * d'etapes, le traitement est marque comme termine.
     *
     * @param int $currentStep Etape courante du traitement.
     * @param int $totalSteps  Nombre total d'etapes du traitement.
     *
     * @return self Instance courante pour le chainage de methodes.
     */
    public function setProgress(int $currentStep, int $totalSteps): self
    {
        global $pax_logger;
        $pax_logger->info('Definition de la progression: etape ' . $currentStep . ' sur ' . $totalSteps);

        $this->data['progress'] = [
            'current_step' => $currentStep,
            'total_steps'  => $totalSteps,
        ];

        if ($currentStep >= $totalSteps && $totalSteps > 0) {
            $pax_logger->info('Traitement marque comme termine');
            $this->setCompleted();
        }

        return $this;
    }

    /**
     * Marque le traitement comme termine.
     *
     * Cette methode privee permet de marquer le traitement comme termine.
     * Elle est appelee automatiquement lorsque la progression atteint
     * le nombre total d'etapes.
     *
     * @param bool $completed Etat de completion (true par defaut).
     *
     * @return self Instance courante pour le chainage de methodes.
     */
    private function setCompleted(bool $completed = true): self
    {
        global $pax_logger;
        $pax_logger->info('Traitement marque comme termine avec etat: ' . ($completed ? 'true' : 'false'));

        $this->data['completed'] = $completed;

        return $this;
    }

    /**
     * Definit une erreur dans la reponse.
     *
     * Cette methode permet de definir une erreur dans la reponse du stepper.
     * Elle marque egalement la reponse comme echouee en definissant
     * le statut de succes a false.
     *
     * @param string $error Message d'error a definir.
     *
     * @return self Instance courante pour le chainage de methodes.
     */
    public function setError(string $error): self
    {
        global $pax_logger;
        $pax_logger->error('Definition d\'une erreur dans la reponse: ' . $error);

        $this->data['success'] = false;
        $this->data['error']   = $error;

        return $this;
    }

    /**
     * Fusionne des donnees supplementaires dans la reponse.
     *
     * Cette methode permet de fusionner des donnees supplementaires
     * dans la reponse du stepper. Les donnees existantes sont conservees
     * et les nouvelles donnees sont ajoutees ou ecrasent les donnees existantes.
     *
     * @param array $data Donnees supplementaires a fusionner.
     *
     * @return self Instance courante pour le chainage de methodes.
     */
    public function setData(array $data): self
    {
        global $pax_logger;
        $pax_logger->debug('Fusion de donnees supplementaires dans la reponse');

        $this->data = array_merge($this->data, $data);

        return $this;
    }

    /**
     * Envoie la reponse au format JSON.
     *
     * Cette methode envoie la reponse du stepper au format JSON
     * et termine l'execution du script. Elle est utilisee pour
     * communiquer avec l'interface utilisateur via AJAX.
     */
    public function send(): void
    {
        global $pax_logger;
        $pax_logger->info('Envoi de la reponse JSON au client');
        $pax_logger->debug('Donnees de reponse: ' . json_encode($this->data));

        if (! headers_sent()) {
            header('Content-Type: application/json');
        }
        echo json_encode($this->data);

        exit;
    }

    /**
     * Recupere les donnees de la reponse.
     *
     * Cette methode permet de recuperer les donnees de la reponse
     * du stepper sous forme de tableau associatif. Elle est utilisee
     * pour acceder aux informations de la reponse sans envoyer
     * immediatement la reponse au client.
     *
     * @return array Donnees de la reponse.
     */
    public function getData(): array
    {
        global $pax_logger;
        $pax_logger->debug('Recuperation des donnees de la reponse');

        return $this->data;
    }
}
