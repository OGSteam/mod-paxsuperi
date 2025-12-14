<?php

if (! defined('IN_SPYOGAME')) {
    exit('Hacking attempt');
}
?>
  <style>
        #progress-container { margin: 20px 0; }
        #progress-bar { height: 20px; background: #f0f0f0; }
        #progress { height: 100%; background: #1ae021ff; width: 0%; }
        #log { height: 200px; overflow-y: scroll; border: 1px solid #ddd; padding: 10px; margin-top: 10px; }
        .error { color: red; }
        button { margin-right: 10px; padding: 8px 16px; }
    </style>



<div class="og-msg ">
    <h3 class="og-title">Mod Superapix</h3>
    <p class="og-content">Superapix est un mod Ogspy permettant de mettre à jour depuis l'api XML d'ogame</p>
    <p class="og-content">Celui ci est maintenant profilé pour automatiquement se mettre à jour sans actions utilisateurs </p>
</div>



<div class="og-msg">

    <button class="og-button" id="start-btn" value = "Démarrer">Démarrer</button>
    <button class="og-button og-button-warning" id="stop-btn"  value = "Arrêter" disabled>Arrêter</button>
</div>




<div class="og-msg">


<div id="progress-container">
    <div id="progress-bar">
        <div id="progress"></div>
    </div>
</div>

<div id="log"></div>

</div>



<script>
    let isRunning = false;
    let stopRequested = false;

    // Démarrer le traitement
    $('#start-btn').click(function() {
         $('#log').html('');
        isRunning = true;
        stopRequested = false;
        $('#stop-btn').prop('disabled', false);
        $('#start-btn').prop('disabled', true);
        $('#progress').css('background-color', '#1ae021ff'); // Vert par defaut / tout va bien se passer
        $('#log').append('<div>Démarrage du traitement...</div>');
        processStep();
    });

    // Arrêter le traitement
    $('#stop-btn').click(function() {
        stopRequested = true;
        isRunning = false;
        $('#log').append('<div>Arret demandé par l utilisateur.</div>');
    });

    // Traiter une étape
    function processStep() {
        if (stopRequested) {
            $('#start-btn').prop('disabled', false);
            return;
        }

        $.post('mod/paxsuperi/Core_pax/Stepper.php', {
            action: 'process_step'
        }, function(data) {
            if (stopRequested) {
                $('#start-btn').prop('disabled', false);
                return;
            }

            // Vérification des retours -
            // on stop  en cas d'erreur
            if (!data) {
                logMessage("Erreur: Réponse vide du serveur.", 'error');
                $('#progress').css('background-color', '#f44336');
                stopRequested = true;
                $('#start-btn').prop('disabled', false);
                return;
            }

            if (!data.message || !data.progress) {
                logMessage("Erreur: Format de réponse invalide.", 'error');
                $('#progress').css('background-color', '#f44336');
                stopRequested = true;
                $('#start-btn').prop('disabled', false);
                return;
            }

            // On ajoute le message reçu
            logMessage(data.message);

            // Actualisation de la progression
            const progress = (data.progress.current_step / data.progress.total_steps) * 100;
            $('#progress').css('width', progress + '%');

            // gestion du prochain tour
            if (!data.completed) {
                setTimeout(processStep, <?php echo (int) mod_get_option('temporisation'); ?>000);
            } else {
                  stopRequested = true;
                $('#stop-btn').prop('disabled', true);
                logMessage("Terminé !", 'success');
                $('#start-btn').prop('disabled', false);
            }
        }, 'json').fail(function(jqXHR, textStatus, errorThrown) {
            logMessage("Erreur réseau: " + textStatus + " - " + errorThrown, 'error');
            $('#progress').css('background-color', '#f44336'); // Rouge en cas d'erreur
            stopRequested = true;
            $('#start-btn').prop('disabled', false);
        });
    }

    // Fonction pour ajouter un message au log
    function logMessage(message, type = '') {
        const $message = $('<div>').text(message);
        if (type === 'error') {
            $message.addClass('og-alert');
        } else if (type === 'success') {
            $message.addClass('og-success');
        }
        $('#log').append($message);

    }
</script>

<!--
<table class="og-table og-small-table">
    <thead>
        <tr>
            <th colspan="2"><?php echo help('liste des dernieres mises à jour'); ?>Dernieres mise à jour</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($tab ?? [] as $key => $value) : ?>
            <tr>
                <td class="tdstat">
                    <?php echo lang($value); ?>
                </td>
                <td class="tdvalue">
                    <?php echo date('d M Y H:i', ((int) find_config('last_' . $value))); ?>
                </td>
            </tr>
        <?php endforeach; ?>


    </tbody>
</table>
        -->
<?php

// Initialisation
// $pays       = mod_get_option("pays");
// $uni        =   mod_get_option("uni");
// $temporisation = (int)mod_get_option("temporisation");

// $xmlManager = new XmlManager($pays, $uni);
// $endpoints  = Constant::getEndpoint();
// echo '<pre>';
// var_dump($endpoints);
// echo '</pre>';
// 1. Télécharger et stocker la liste des joueurs
// foreach ($endpoints as $endpoint) {
//    var_dump($xmlManager->isUpToDate($endpoint));
//    echo '<br />';
// echo 'Pour  ' . $endpoint . ' : <br>';
//    if (! $xmlManager->isUpToDate($endpoint)) {
//        $playersXml = $xmlManager->downloadXml($endpoint);
//        echo 'Téléchargement de endpoints ' . $endpoint . ' terminé.<br>';
//        sleep($temporisation);
//    } else {
//        echo 'Le fichier ' . $endpoint . '.xml est déjà à jour.<br>';
//    }

// Récupérer le contenu depuis le stockage local
// $localPlayersXml = $xmlManager->getLocalXml($endpoint);
// if ($localPlayersXml) {
//    echo 'Contenu récupéré depuis le stockage local :<br>';
//    echo substr($localPlayersXml, 0, 200) . '...<br>'; // Affiche les 200 premiers caractères
// }

// }
