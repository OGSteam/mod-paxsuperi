<?php

/**
 * OGSPY - Mod PAx Superi
 *
 * @package [Mod] Pax Superi
 * @author Machine
 * @copyright Copyright &copy; 2016, https://ogsteam.eu/
 * @license https://opensource.org/licenses/gpl-license.php GNU Public License
 */

define('IN_SPYOGAME', true);

if (preg_match('#mod#', getcwd())) {
    chdir('../../../');
}
// $_SERVER['SCRIPT_FILENAME'] = str_replace(basename(__FILE__), 'index.php', preg_replace('#\/mod\/(.*)\/#', '/', $_SERVER['SCRIPT_FILENAME']));
// inclusions

include 'common.php';
include_once 'mod/paxsuperi/common.php';

$state           = loadStepperState();
$stepperResponse = new StepperResponse();

// peut on commencer
if (! stepperCanStart($state)) {
    return $stepperResponse->setError('Ne peut démarrer.')->send();
}

// lancement stepper
if ($state['stepperRunning'] === 0) {
    startStepper();
}

// hors limit
if ($state['currentStep'] > $state['total']) {
    resetStepper();

    return $stepperResponse
        ->setMessage('Le travail semble terminé')
        ->setError('Ne peut poursuivre 1 .')
        ->send();
}

// Lancement etape courante
runCurrentStep($state, $stepperResponse);

function loadStepperState(): array
{
    return [
        'stepperRunning'      => (int) pax_mod_get_option('stepperRunning'),
        'currentStep'         => (int) pax_mod_get_option('currentStep'),
        'lastRunning'         => (int) pax_mod_get_option('lastRunning'),
        'lastRunningSecurity' => (int) pax_mod_get_option('lastRunningSecurity'),
        'total'               => count(Constant::getEndpoint()) * 2 - 1, // download + traitement
    ];
}

function stepperCanStart(array $state): bool
{
    $recent = (time() - $state['lastRunning']) < $state['lastRunningSecurity'];

    // Si dernière exécution < sécurité ET stepper arrêté => refus
    return ! ($recent && $state['lastRunning'] === 0);
}

function startStepper(): void
{
    pax_mod_set_option('lastRunning', time());
    $state['lastRunning'] = time();

    pax_mod_set_option('currentStep', 1);
    $state['currentStep'] = 1;

    pax_mod_set_option('stepperRunning', 1);
    $state['stepperRunning'] = 1;

    $state['total'] = count(Constant::getEndpoint()) * 2 - 1;
}

function resetStepper(): void
{
    pax_mod_set_option('currentStep', 0);
    $state['currentStep'] = 0;
    pax_mod_set_option('stepperRunning', 0);
    $state['stepperRunning'] = 0;
}

function buildStepList(): array
{
    $steps = [];
    $i     = 0;

    foreach (Constant::getEndpoint() as $endpoint) {
        $class = endpointToClass($endpoint);

        $steps[$i++] = ['class' => $class, 'method' => 'download', 'endpoint' => $endpoint];
        $steps[$i++] = ['class' => $class, 'method' => 'traitement', 'endpoint' => $endpoint];
    }

    return $steps;
}

function endpointToClass(string $endpoint): string
{
    $name = str_replace('CST_', '', $endpoint);

    return 'Class' . ucfirst(strtolower($name));
}

function runCurrentStep(array $state, StepperResponse $resp): void
{
    $steps   = buildStepList();
    $current = $state['currentStep'];

    if (! isset($steps[$current])) {
        $resp->setError("Étape inconnue : {$current}")->send();

        exit();
    }

    $resp->setProgress($current, $state['total']);

    pax_mod_set_option('currentStep', $current + 1);
    // todo indiqué que la fin est arrivé  di total atteint step et enregistré dans bdd

    $step = $steps[$current];

    if (! class_exists($step['class'])) {
        $resp
            ->setMessage('La classe n’existe pas : ' . $step['class'])
            ->setError('Ne peut poursuivre 2.')
            ->send();

        exit();
    }

    $instance = new $step['class']($step['endpoint']);
    $fn       = (string) $step['method'];

    $response = $instance->{$fn}();
    $resp->concatStepperResponse($response);
    $resp->send();

    exit();
}
