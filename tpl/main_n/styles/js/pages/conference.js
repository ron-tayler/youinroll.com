$(document).on('ready', function() {

    jitsiClass.runConf('#stream', $('#stream').data('stream'), function(data){

    });

    window.addEventListener("beforeunload", function(evt) {

        evt.preventDefault();

        $.get('https://youinroll.com/lib/ajax/jitsi/removeOnAir.php', {
            streamId: $('#stream').data('stream')
        });

        return null;
    });

    window.addEventListener("onbeforeunload", function(evt) {

        evt.preventDefault();

        $.get('https://youinroll.com/lib/ajax/jitsi/removeOnAir.php', {
            streamId: $('#stream').data('stream')
        });

        return null;
    });
});