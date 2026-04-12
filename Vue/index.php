<?php

if (! defined('IN_SPYOGAME')) {
    exit('Hacking attempt');
}

global $pax_logger;
$pax_logger->info('Chargement de la vue principale');
$setting = Setting::getInstance();
?>

<!-- Inclusion CSS/JS spécifique au module -->
<link rel="stylesheet" href="mod/paxsuperi/css/paxsuperi.css">
<script src="mod/paxsuperi/js/paxsuperi.js"></script>

<div class="og-msg">
    <h3 class="og-title">Mod Pax Superi</h3>
    <p class="og-content">Pax Superi est un mod OGSpy permettant de mettre à jour les données de l'univers depuis l'API XML d'OGame.</p>
</div>

<div class="og-msg">
    <div class="pax-actions pax-button-group">
        <button class="og-button" id="pax-start-btn">Démarrer le traitement</button>
        <button class="og-button og-button-warning" id="pax-stop-btn" disabled>Arrêter</button>
    </div>
</div>

<div class="og-msg">
    <div class="pax-progress-container">
        <div class="pax-progress-bar">
            <div id="pax-progress-fill" class="pax-progress-fill"></div>
        </div>
    </div>

    <div id="pax-log" class="pax-log-container">
        <div class="pax-log-entry">Prêt pour le traitement. Cliquez sur "Démarrer".</div>
    </div>
</div>

<script>
    $(document).ready(function() {
        PaxStepper.init(<?php echo (int) $setting->temporisation; ?>);
    });
</script>
