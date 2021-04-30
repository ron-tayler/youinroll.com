let topPanelText = '.chat-area-title';
let topPanelImg = '.chat-area-profile';

document.addEventListener('DOMContentLoaded', function() {

    window.rendered;

    let chatClass = new YRChat();

    let userID = parseInt($('#chatMainBlock').data('active'));

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

    chatClass.runMethod({
        method: 'getConversations',
        params: {
            block: '.conversation-area > .search-bar',
            userId: userID,
            chatActiveCallback: function() {

                let chatName = $('.msg-active').find('.msg-username').text();
                let chatImage = $('.msg-active').find('.msg-profile').prop('src');

                $(topPanelText).text(chatName);
                $(topPanelImg).prop('src', chatImage);

                $('.chat-area-main').empty();

                /*chatClass.runMethod({
                  method: 'getMessages',
                  params: {
                    block: '.chat-area-main',
                    blockContact: '.chat-area-group',
                    chatId: parseInt($('.msg-active').data('id'))
                  }
                }); */

            },
            afterCreate: function() {
                let chatName = $('.msg-active').find('.msg-username').text();
                let chatImage = $('.msg-active').find('.msg-profile').prop('src');

                $(topPanelText).text(chatName);
                $(topPanelImg).prop('src', chatImage);
            }
        }
    });

    $('body').on('click', '.msg:not(.search_result)', function(e) {

        rendered = false;

        $('#chatMainBlock').data('page', '1');

        //$('.detail-area').css('display','flex');
        $('.chat-area-footer').css('display', 'flex');

        $('#emptyDialog').hide();
        $('.msg.msg-active').removeClass('msg-active');

        $(this).addClass('msg-active');

        let chatName = $('.msg-active').find('.msg-username').text();
        let chatImage = $('.msg-active').find('.msg-profile').prop('src');

        $(topPanelText).text(chatName);
        $(topPanelImg).prop('src', chatImage);

        $('.detail-title').text(chatName);

        $('.chat-area-main').empty();

        chatClass.runMethod({
            method: 'getMessages',
            params: {
                block: '.chat-area-main',
                blockContact: '.chat-area-group',
                chatId: $(this).data('id'),
                page: $('#chatMainBlock').data('page')
            }
        });

        setTimeout(() => {
            document.querySelector(".chat-area").scrollTo(0, document.querySelector(".chat-area").scrollHeight);
        }, 500);
    });

    $(".chat-area").scroll(function() {
        let current_page = parseInt($('#chatMainBlock').data('page'));

        if ($(".chat-area").scrollTop() === 0 && rendered === true) {

            $('#chatMainBlock').data('page', (current_page + 1).toString());

            chatClass.runMethod({
                method: 'getMessages',
                params: {
                    block: '.chat-area-main',
                    blockContact: '.chat-area-group',
                    chatId: $('.msg.msg-active').data('id'),
                    page: $('#chatMainBlock').data('page')
                }
            });
        }
    });

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

    document.addEventListener('keyup', function(e) {

        if (e.code === 'Enter') {
            $('.feather-send').click()
        }

    });

    $('.feather-send').on('click', function() {

        if ($('#messageInput').val() === '' && document.getElementById("messageFile").value === '') {
            return null;
        }

        if (document.getElementById("messageFile").value !== null && document.getElementById("messageFile").value !== "") {
            chatClass.runMethod({
                method: 'sendMessage',
                params: {
                    text: $('#messageInput').val(),
                    file: $("#messageFile")[0].files[0],
                    toUser: $('.msg-active').data('user'),
                    chatId: $('.msg-active').data('id'),
                    afterComplete: function() {

                        $('#messageInput').val(' ');
                        $("#messageFile").val(' ');
                        document.getElementById("messageFile").value = null;
                    }
                }
            });
        } else {
            chatClass.runMethod({
                method: 'sendMessage',
                params: {
                    text: $('#messageInput').val(),
                    toUser: $('.msg-active').data('user'),
                    chatId: $('.msg-active').data('id'),
                    afterComplete: function() {

                        $('#messageInput').val(' ');
                        $("#messageFile").val(' ');
                        document.getElementById("messageFile").value = null;
                    }
                }
            });
        }

        document.getElementById("messageFile").value = null;
        $('#messageInput').val('');
        $('#openfile').find('.badge').remove();

    });

    $('.conversation-area').on('click', '.search_result', function() {

        $('.search_result').removeClass('active');
        $(this).addClass('active');

        $('.detail-area').css('display', 'flex');
        $('.detail-area').css('margin-left', 'unset');
        $('.chat-area').hide();

        let chatImage = $(this).find('.msg-profile').prop('src');
        let chatName = $(this).find('.msg-username').text();

        $('.detail-area').find('.chat-area-profile').prop('src', chatImage);
        $('.detail-area').find('.detail-title').text(chatName);

        $('#writeTo').attr('href', `/conversation/${$(this).data('user')}/`)

    })

    $('#addToConv').on('click', function() {

        $.get('https://youinroll.com/lib/ajax/chat/getConversations.php', function(data) {

            let conversations = JSON.parse(data);

            $('#confBlock').empty();

            for (const key in conversations) {

                let chat = conversations[key];

                let $variant = `<li data-id='${chat.conf_id}' class='variant dropdown-item'>${chat.title}</li>`;

                $('#confBlock').append($variant);
            }

            $('#confBlock').dropdown();
        });
    })

    $('#confBlock').on('click', '.variant', function() {
        let chatId = $(this).data('id');
        let userId = $('.search_result.active').data('user');

        $.post('https://youinroll.com/lib/ajax/chat/addToChat.php', {
            chatId: chatId,
            userId: userId
        }, function(data) {
            alert('Добавлен')
        });
    });

    $('.conversation-area').on('change', '.search-bar > input', function() {

        let value = $(this).val();

        if (value.length > 3) {
            $('.conversation-area').find('.msg').remove();

            chatClass.runMethod({
                method: 'getChannels',
                params: {
                    block: '.conversation-area > .search-bar',
                    value: value
                }
            });
        }

        if (value.length === 0) {
            $('.conversation-area').find('.msg').remove();

            $('.detail-area').css('display', 'none');
            $('.detail-area').css('margin-left', 'auto');
            $('.chat-area').show();

            $('#chatMainBlock').data('page', '1');

            chatClass.runMethod({
                method: 'getConversations',
                params: {
                    block: '.conversation-area > .search-bar',
                    userId: parseInt($('#chatMainBlock').data('active')),
                    chatActiveCallback: function() {

                        let chatName = $('.msg-active').find('.msg-username').text();
                        let chatImage = $('.msg-active').find('.msg-profile').prop('src');

                        $(topPanelText).text(chatName);
                        $(topPanelImg).prop('src', chatImage);

                        chatClass.runMethod({
                            method: 'getMessages',
                            params: {
                                block: '.chat-area-main',
                                chatId: $('.list-group-item.active').data('id'),
                                blockContact: '.chat-area-group',
                                page: $('#chatMainBlock').data('page')
                            }
                        });

                    },
                    afterCreate: function() {
                        let chatName = $('.msg-active').find('.msg-username').text();
                        let chatImage = $('.msg-active').find('.msg-profile').prop('src');

                        $(topPanelText).text(chatName);
                        $(topPanelImg).prop('src', chatImage);

                    }
                }
            });
        }

    });

}, false);