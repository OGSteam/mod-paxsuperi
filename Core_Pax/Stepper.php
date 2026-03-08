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

/**
 * @var Container $container Container DI global.
 */
$container = $GLOBALS['pax_container'];

/**
 * @var SettingInterface $setting Instance des parametres de configuration.
 */
$setting = $container->make(SettingInterface::class);

/**
 * @var LoggerInterface $logger Instance du logger.
 */
$logger = $container->make(LoggerInterface::class);

/**
 * @var array $state Etat actuel du stepper.
 *
 * Ce tableau contient les informations sur l'etat actuel du stepper,
 * telles que l'etat d'execution, l'etape courante, etc.
 */
$state = loadStepperState();

/**
 * @var StepperResponse $stepperResponse Reponse du stepper.
 *
 * Cette variable stocke la reponse du stepper, qui est utilisee pour
 * envoyer des messages et des erreurs a l'utilisateur.
 */
$stepperResponse = new StepperResponse();

// Réinitialiser le stepper si demandé
if (isset($pub_action) && $pub_action === 'reset_stepper') {
    resetStepper();
    echo json_encode(['success' => true]);
    exit();
}

// peut on commencer
if (! stepperCanStart($state)) {
    return $stepperResponse->setError('Ne peut demarrer.')->send();
}

// lancement stepper
if ($state['stepperRunning'] === 0) {
    startStepper();
}

// hors limit
if ($state['currentStep'] > $state['total']) {
    resetStepper();

    return $stepperResponse
        ->setMessage('Le travail semble termine')
        ->setError('Ne peut poursuivre 1 .')
        ->send();
}

// Lancement etape courante
runCurrentStep($state, $stepperResponse);

/**
 * Charge l'etat actuel du stepper.
 *
 * Cette fonction charge l'etat actuel du stepper depuis les parametres
 * de configuration et retourne un tableau contenant les informations
 * sur l'etat d'execution, l'etape courante, etc.
 *
 * @return array Etat actuel du stepper.
 */
function loadStepperState(): array
{
    global $setting;

    return [
        'stepperRunning'      => $setting->stepperRunning,
        'lastRunning'         => $setting->lastRunning,
        'lastRunningSecurity' => $setting->lastRunningSecurity,
        'currentStep'         => $setting->currentStep,

        'total' => count(Constant::getEndpoint()) * 2 - 1, // download + traitement
    ];
}

/**
 * Verifie si le stepper peut demarrer.
 *
 * Cette fonction verifie si le stepper peut demarrer en fonction
 * de la derniere execution et de l'etat actuel du stepper.
 *
 * @param array $state Etat actuel du stepper.
 *
 * @return bool True si le stepper peut demarrer, false sinon.
 */
function stepperCanStart(array $state): bool
{
    $recent = (time() - $state['lastRunning']) < $state['lastRunningSecurity'];

    // Si derniere execution < securite ET stepper arrete => refus
    return ! ($recent && $state['lastRunning'] === 0);
}

/**
 * Demarre le stepper.
 *
 * Cette fonction demarre le stepper en initialisant les parametres
 * de configuration et en definissant l'etat d'execution du stepper.
 */
function startStepper(): void
{
    global $setting;

    $setting->lastRunning = time();
    $state['lastRunning'] = time();

    $setting->currentStep = 1;
    $state['currentStep'] = 1;

    $setting->stepperRunning = 1;
    $state['stepperRunning'] = 1;

    $state['total'] = count(Constant::getEndpoint()) * 2 - 1;
}

/**
 * Reinitialise le stepper.
 *
 * Cette fonction reinitialise le stepper en remettant a zero les parametres
 * de configuration et en arretant l'execution du stepper.
 */
function resetStepper(): void
{
    global $setting;
    $setting->currentStep    = 0;
    $state['currentStep']    = 0;
    $setting->stepperRunning = 0;
    $state['stepperRunning'] = 0;
}

/**
 * Construit la liste des etapes du stepper.
 *
 * Cette fonction construit la liste des etapes du stepper en utilisant
 * les constantes definies dans la classe Constant et en creant des etapes
 * pour le telechargement et le traitement des donnees.
 *
 * @return array Liste des etapes du stepper.
 */
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

/**
 * Convertit un endpoint en nom de classe.
 *
 * Cette fonction convertit un nom d'endpoint en nom de classe
 * en supprimant le prefixe 'CST_' et en mettant en majuscule la premiere
 * lettre du nom.
 *
 * @param string $endpoint Nom de l'endpoint.
 *
 * @return string Nom de la classe correspondante.
 */
function endpointToClass(string $endpoint): string
{
    $name = str_replace('CST_', '', $endpoint);

    return 'Class' . ucfirst(strtolower($name));
}

/**
 * Execute l'etape courante du stepper.
 *
 * Cette fonction execute l'etape courante du stepper en utilisant
 * la liste des etapes et en appelant la methode correspondante
 * de la classe associee.
 *
 * @param array           $state Etat actuel du stepper.
 * @param StepperResponse $resp  Reponse du stepper.
 */
function runCurrentStep(array $state, StepperResponse $resp): void
{
    global $setting;

    $steps   = buildStepList();
    $current = $state['currentStep'];

    if (! isset($steps[$current])) {
        $resp->setError("Etape inconnue : {$current}")->send();

        exit();
    }

    $resp->setProgress($current, $state['total']);

    $setting->currentStep = $current + 1;
    // todo indique que la fin est arrivee di total atteint step et enregistre dans bdd

    $step = $steps[$current];

    if (! class_exists($step['class'])) {
        $resp
            ->setMessage('La classe n\'existe pas : ' . $step['class'])
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
