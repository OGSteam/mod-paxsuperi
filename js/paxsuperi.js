/**
 * Pax Superi - Stepper JS
 */

const PaxStepper = {
    isRunning: false,
    stopRequested: false,
    tempo: 1000,

    init: function(tempo) {
        this.tempo = (parseInt(tempo) || 1) * 1000;
        this.bindEvents();
    },

    bindEvents: function() {
        $('#pax-start-btn').on('click', () => this.start());
        $('#pax-stop-btn').on('click', () => this.stop());
    },

    log: function(message, type = '') {
        const $log = $('#pax-log');
        const $entry = $('<div class="pax-log-entry"></div>').text(message);
        
        if (type === 'error') {
            $entry.addClass('pax-error');
        } else if (type === 'success') {
            $entry.addClass('pax-success');
        }
        
        $log.append($entry);
        $log.scrollTop($log[0].scrollHeight);
    },

    updateProgress: function(current, total) {
        const percentage = (current / total) * 100;
        $('#pax-progress-fill').css('width', percentage + '%');
    },

    start: function() {
        $('#pax-log').html('');
        this.isRunning = true;
        this.stopRequested = false;
        
        $('#pax-stop-btn').prop('disabled', false);
        $('#pax-start-btn').prop('disabled', true);
        
        $('#pax-progress-fill').css('background-color', '#1ae021ff');
        this.log("Démarrage du traitement...");
        
        this.resetAndStart();
    },

    stop: function() {
        this.stopRequested = true;
        this.isRunning = false;
        this.log("Arrêt demandé par l'utilisateur.", 'error');
        $('#pax-start-btn').prop('disabled', false);
        $('#pax-stop-btn').prop('disabled', true);
    },

    resetAndStart: function() {
        $.post('mod/paxsuperi/Core_Pax/Stepper.php', {
            action: 'reset_stepper'
        }, (data) => {
            if (data && data.success) {
                this.processStep();
            } else {
                this.log("Erreur lors de la réinitialisation du stepper.", 'error');
                this.stop();
            }
        }, 'json').fail((xhr, status, error) => {
            this.log("Erreur réseau: " + error, 'error');
            this.stop();
        });
    },

    processStep: function() {
        if (this.stopRequested) return;

        $.post('mod/paxsuperi/Core_Pax/Stepper.php', {
            action: 'process_step'
        }, (data) => {
            if (this.stopRequested) return;

            if (!data || !data.message || !data.progress) {
                this.log("Erreur: Format de réponse invalide.", 'error');
                $('#pax-progress-fill').css('background-color', '#f44336');
                this.stop();
                return;
            }

            this.log(data.message);
            this.updateProgress(data.progress.current_step, data.progress.total_steps);

            if (!data.completed) {
                setTimeout(() => this.processStep(), this.tempo);
            } else {
                this.log("Traitement terminé avec succès !", 'success');
                this.isRunning = false;
                $('#pax-start-btn').prop('disabled', false);
                $('#pax-stop-btn').prop('disabled', true);
            }
        }, 'json').fail((xhr, status, error) => {
            this.log("Erreur réseau: " + error, 'error');
            $('#pax-progress-fill').css('background-color', '#f44336');
            this.stop();
        });
    }
};
