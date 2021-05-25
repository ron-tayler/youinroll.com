$(document).on('ready', function() {




    if ($(window).width() < 768) {
        let $chat = $('.chat-wrapper');
        let $closeStream = $('#closeStream').parent();

        $closeStream.css('display', 'grid');

        $('.stream-under').append($closeStream);
        $('.mobile-chat').append($chat);
    }

    $('#streamSettings').on('submit', function(e) {

        e.preventDefault();

        $.post(site_url + 'lib/ajax/jitsi/editConference.php',
            $('#streamSettings').serialize(),
            function(data) {
                location.reload();
            });

    });

    $('#openfile').click(function() {
        $("#messageFile").click();
    });

    $('#messageFile').change(function(e) {

        if (document.getElementById("messageFile").value !== null || document.getElementById("messageFile").value !== '') {
            $('#openfile').append(`
                <span class="badge badge-light">1</span>
            `);
        } else {
            $('#openfile').find('.badge').remove();
        }
    })

    $('#i-like-it').on('click', function() {

        let streamId = parseInt($(this).data('id'));

        let likesNr = $('#i-like-it > span').text();

        likesNr = parseInt(likesNr);

        $('#i-like-it').addClass('done-like');
        $('#i-like-it').addClass('isLiked');

        $.post(
            site_url + 'lib/ajax/likeStream.php', {
                stream_id: streamId,
                type: 1
            },
            function(data) {
                let a = JSON.parse(data);

                if (parseInt(a.added) === 0) {
                    $('#i-like-it').removeClass('done-like');
                    $('#i-like-it').removeClass('isLiked');
                    $('#i-like-it > span').html(likesNr - 1);

                } else {

                    $('#i-like-it').addClass('done-like');
                    $('#i-like-it').addClass('isLiked');
                    $('#i-like-it > span').html(likesNr + 1);
                }

                $.notify(a.title + ' ' + a.text);
            });
    });
	console.log("Stream ID" + $('#stream'));

    jitsiClass.runConf('#stream', $('#stream').data('stream'), function() {
        chatClass.startStreamChat($('#stream').data('stream'));
    });

    $('.chat-area-main').empty();

    chatClass.runMethod({
        method: 'getStreamMessages',
        params: {
            streamId: parseInt($('#stream').data('stream')),
            block: '.chat-area-main'
        }
    });

    /* $('#editConf').on('click', function(){
        $.post('https://youinroll.com/lib/ajax/jitsi/editConference.php', {

        }, function(){
            window.location.reload();
        })
    }) */

    document.addEventListener('keyup', function(e) {

        if (e.code === 'Enter') {
            $('.feather-send').click()
        }

    });

    $('#closeStream').on('click', function() {
	    console.log("Remove on air stream started");
	    console.log("Stream ID");
        $.get('https://youinroll.com/lib/ajax/jitsi/removeOnAir.php', {
            streamId: $('#stream').data('stream')
        }, function(result) {
            location.href = '/dashboard';
        });
    });

    $('.feather-send').on('click', function() {

        if ($('#messageInput').val() === '' && document.getElementById("messageFile").value === '') {
            return null;
        }

        if (document.getElementById("messageFile").value !== null && document.getElementById("messageFile").value !== "") {

            chatClass.runMethod({
                method: 'sendMessageStream',
                params: {
                    streamId: parseInt($('#stream').data('stream')),
                    file: $('#messageFile')[0].files[0],
                    text: $('#messageInput').val(),
                    afterComplete: function(data) {
                        $('#messageInput').val(' ');
                        $("#messageFile").val(' ');
                        document.getElementById("messageFile").value = null;

                    }
                }
            });

        } else {
            chatClass.runMethod({
                method: 'sendMessageStream',
                params: {
                    streamId: parseInt($('#stream').data('stream')),
                    text: $('#messageInput').val(),
                    afterComplete: function(data) {
                        $('#messageInput').val(' ');
                        $("#messageFile").val(' ');
                        document.getElementById("messageFile").value = null;

                    }
                }
            });
        }

        $('#messageInput').val(' ');
        $("#openfile").find('.badge').remove();
        document.getElementById("messageFile").value = null;
    });

    window.onbeforeunload = function() {
        $.get('https://youinroll.com/lib/ajax/jitsi/removeOnStream.php', {
            streamId: $('#stream').data('stream')
        });
    };

    $('body').on('click', '#smiles', function() {
        $('.intercom-composer-emoji-popover').toggleClass("active");
        $('body').prepend('<div id="modalKostil"></div>');
    });


    $('#wrapper').on('click', function() {
        if ($('#modalKostil').length) {
            $('.intercom-composer-emoji-popover').toggleClass("active");
            $('#modalKostil').remove();
        }
    });

    $('.intercom-emoji-picker-emoji').on('click', function() {
        $('#messageInput').val($('#messageInput').val() + $(this).text());
    });
});
