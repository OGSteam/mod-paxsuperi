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

class StepperResponse
{
    private $data = [
        'success'  => true,
        'message'  => '',
        'progress' => [
            'current_step' => 0,
            'total_steps'  => 0,
        ],
        'completed' => false,
        'error'     => null,
    ];

    public function concatStepperResponse(StepperResponse $stepperResponse): void
    {
        $nData = $stepperResponse->getData();
        $this->setMessage($nData['message']);
        if (null !== $nData['error']) {
            $this->setError($nData['error']);
        }

        $this->data['success'] = $nData['success'];
    }

    /**
     * Définir un message
     */
    public function setMessage(string $message): self
    {
        $this->data['message'] = $message;

        return $this;
    }

    /**
     * Définir la progression
     */
    public function setProgress(int $currentStep, int $totalSteps): self
    {
        $this->data['progress'] = [
            'current_step' => $currentStep,
            'total_steps'  => $totalSteps,
        ];

        if ((int) $currentStep >= (int) $totalSteps) {
            $this->setCompleted();
        }

        return $this;
    }

    /**
     * terminé
     */
    private function setCompleted(bool $completed = true): self
    {
        $this->data['completed'] = $completed;

        return $this;
    }

    /**
     * si  erreur
     */
    public function setError(string $error): self
    {
        $this->data['success'] = false;
        $this->data['error']   = $error;

        return $this;
    }

    /**
     * INFO supp
     */
    public function setData(array $data): self
    {
        $this->data = array_merge($this->data, $data);

        return $this;
    }

    /**
     * afficher json
     */
    public function send(): void
    {
        header('Content-Type: application/json');
        echo json_encode($this->data);

        exit;
    }

    /**
     * retourne les données
     */
    public function getData(): array
    {
        return $this->data;
    }
}
