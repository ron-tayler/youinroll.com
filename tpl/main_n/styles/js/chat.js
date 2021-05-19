class YRChat {

    constructor(options) {

        this.actions = {
            'getConversations': this._getConversations,
            'getMessages': this._getMessages,
            'getStreamMessages': this._getStreamMessages,
            'sendMessage': this._sendMessage,
            'sendMessageStream': this._sendMessageStream,
            'getChannels': this._getChannels
        }
    }

    runMethod(options) {
        this.actions[options.method](options.params);
    }

    /*
     * Method for recieving messages of socket
     */
    recieveMessages(messageData) {

        let message = JSON.parse(messageData.body);

        let queue = (message.private === true);

        this.sendPing(message.chatId);

        if (message.type === 'ping') {

            $('.chat-msg-content > i').text('done_all');
            return null;
        }

        switch (queue) {
            case true:
                if (message.type === 'message') {
                    let typeClass = (parseInt(message.user_id) === CurrentUser) ? 'owner' : '';

                    let $filePreview = '';

                    if (message.file_id !== undefined && message.file_id !== null) {
                        $filePreview = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-paperclip">
                            <path
                                d="M21.44 11.05l-9.19 9.19a6 6 0 01-8.49-8.49l9.19-9.19a4 4 0 015.66 5.66l-9.2 9.19a2 2 0 01-2.83-2.83l8.49-8.48" />
                        </svg>`;

                        message.file.name = message.file.name.substr(0, 10).substr(0, message.file.name.lastIndexOf(".")) + '.' + message.file.name.substring(message.file.name.lastIndexOf(".") + 1)

                        if (message.file.type.match(/^image/)) {
                            $filePreview = `<img src="${message.file.path.replace('/download.php?path=','')}" width="20" height="40">`;
                            message.file.name = '';
                        }

                        $filePreview = `
                        <a target="blank" href="${message.file.path}">
                            ${$filePreview}
                            ${message.file.name}
                        <a><br>`;
                    }

                    if (parseInt(message.chatId) === parseInt(ActiveChat)) {

                        let icon = '';

                        if (parseInt(message.user_id) === CurrentUser) {
                            icon = `<i class="material-icons">done</i>`;
                        }

                        let exp = /(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/i;
                        message.text = message.text.replace(exp, "<a target='blank' href='$1'>$1</a>");

                        let $message = `<div class="chat-msg ${typeClass}">
                            <div class="chat-msg-profile">
                                <a href='${message.avatarLink}'>
                                    <img class="chat-msg-img"
                                        src="${message.avatar}"
                                        alt="${message.author}" />
                                </a>
                                <div class="chat-msg-date">${message.author} ${moment(message.created_at, 'YYYY-MM-DD hh:mm:ss').lang('ru').fromNow()}</div>
                            </div>
                            <div class="chat-msg-content">
                                <div class="chat-msg-text">${$filePreview}${message.text}</div>
                                ${icon}
                            </div>
                            <hr />
                        </div>`;

                        $('.msg.msg-active').find('.msg-message').text(message.text);
                        $('.msg.msg-active').find('.msg-date').text('Сейчас');

                        $('.chat-area-main').append($message);

                        document.querySelector(".chat-area").scrollTo(0, document.querySelector(".chat-area").scrollHeight);
                    } else {
                        $(`.msg[data-id="${message.chatId}"]`).find('.msg-message').text(message.text);
                        $(`.msg[data-id="${message.chatId}"]`).find('.msg-date').text('Сейчас');

                        let prevVal = parseInt($('#chatNotifyBadge').text());
                        prevVal += 1;
                        $('#chatNotifyBadge').text(prevVal);
                        if(prevVal>0){
                            $('#chatNotifyBadge').css('display','inherit')
                        }

                        if ($(`.msg[data-id="${message.chatId}"]`).find('.badge').length) {
                            let messagesCount = parseInt($(`.msg[data-id="${message.chatId}"]`).find('.badge').text()) + 1;

                            $(`.msg[data-id="${message.chatId}"]`).find('.badge').text(messagesCount.toString());

                        } else {
                            $(`.msg[data-id="${message.chatId}"]`).append(`
                                <span class="badge badge-light">1</span>
                            `)
                        }
                    }

                    if (!$(`.msg[data-id="${message.chatId}"]`).length) {

                        $('.conversation-area > .msg').remove();

                        chatClass.runMethod({
                            method: 'getConversations',
                            params: {
                                block: '.conversation-area > .search-bar',
                                userId: parseInt($('#chatMainBlock').data('active')),
                                chatActiveCallback: function() {
                                    let chatName = $('.msg-active').find('.msg-username').text();
                                    let chatImage = $('.msg-active').find('.msg-profile').prop('src');

                                    $('.chat-area-title').text(chatName);
                                    $('.chat-area-profile').prop('src', chatImage);

                                    $('.chat-area-main').empty();
                                },
                                afterCreate: function() {
                                    let chatName = $('.msg-active').find('.msg-username').text();
                                    let chatImage = $('.msg-active').find('.msg-profile').prop('src');

                                    $('.chat-area-title').text(chatName);
                                    $('.chat-area-profile').prop('src', chatImage);
                                }
                            }
                        });

                        $('.chat-area-main').empty();

                        new YRChat().runMethod({
                            method: 'getMessages',
                            params: {
                                block: '.chat-area-main',
                                blockContact: '.chat-area-group',
                                chatId: parseInt($('.msg-active').data('id'))
                            }
                        });
                    }

                    let $chatBlock = $(`.msg[data-id="${message.chatId}"]`);

                    $(`.msg[data-id="${message.chatId}"]`).remove();

                    $('.conversation-area > .search-bar').after($chatBlock);
                }
                break;

            default:
                if ((message.private !== true && $('body').data('route') !== 'private')) {
                    let typeClass = (parseInt(message.user_id) === CurrentUser) ? 'owner' : '';

                    let $filePreview = '';

                    if (message.file_id !== undefined && message.file_id !== null) {
                        $filePreview = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-paperclip">
                            <path
                                d="M21.44 11.05l-9.19 9.19a6 6 0 01-8.49-8.49l9.19-9.19a4 4 0 015.66 5.66l-9.2 9.19a2 2 0 01-2.83-2.83l8.49-8.48" />
                        </svg>`;

                        message.file.name = message.file.name.substr(0, 10).substr(0, message.file.name.lastIndexOf(".")) + '.' + message.file.name.substring(message.file.name.lastIndexOf(".") + 1)

                        if (message.file.type.match(/^image/)) {
                            $filePreview = `<img src="${message.file.path.replace('/download.php?path=','')}" width="20" height="40">`;
                            message.file.name = '';
                        }

                        $filePreview = `
                        <a target="blank" href="${message.file.path}">
                            ${$filePreview}
                            ${message.file.name}
                        <a><br>`;
                    }

                    let exp = /(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/i;
                    message.text = message.text.replace(exp, "<a target='blank' href='$1'>$1</a>");

                    let $message = `<div class="chat-msg ${typeClass}">
                        <div class="chat-msg-profile">
                            <a href='${message.avatarLink}'>
                                <img class="chat-msg-img"
                                    title="${message.author}"
                                    src="${message.avatar}"
                                    alt="${message.author}" />
                            </a>
                            <div class="chat-msg-date">${message.author}</div>
                        </div>
                        <div class="chat-msg-content">
                            <div class="chat-msg-text">${$filePreview}${message.text}</div>
                        </div>
                    </div>`;

                    $('.chat-area-main').append($message);

                    document.querySelector(".chat-area").scrollTo(0, document.querySelector(".chat-area").scrollHeight);

                }

                break;
        }

        $.notify(message.author + ': ' + message.text);

        let ua = navigator.userAgent.toLowerCase();
        if (ua.indexOf('safari') != -1) {
            if (ua.indexOf('chrome') > -1) {
                let audio = new Audio('/tpl/main_n/sounds/incomingMessage.wav');
                audio.play();
            } else {

            }
        }
    }

    startStreamChat(streamId) {
        $.get('https://youinroll.com/lib/ajax/jitsi/getChat.php', {
            streamId: streamId
        }, function(data) {

            data = JSON.parse(data);

            let queuePrefix = 'stream';
            let roomHash = data.room;

            let ua = navigator.userAgent.toLowerCase();
            if (ua.indexOf('safari') != -1) {
                if (ua.indexOf('chrome') > -1) {
                    window.bc = new BroadcastChannel('stream');
                    bc.onmessage = function(messageData){
                        messageData = {
                            body: messageData.data
                        }
                        new YRChat().recieveMessages(messageData)
                    }
                } else {

                }
            }


            let ws = new WebSocket('wss://youinrolltinod.com:15673/ws');

            window.client2 = Stomp.over(ws);
            let choosenToken = '';
            let ownerId = '';

            client2.debug = true;

            let on_connect = function() {

                client2.subscribe(`/queue/${queuePrefix}-${roomHash}-${data.viewerId}`, function(data) {

                    if (ua.indexOf('safari') != -1) {
                        if (ua.indexOf('chrome') > -1) {
                            bc.postMessage(data.body);
                        }
                    }

                    new YRChat().recieveMessages(data)
                });
            };

            let on_error = function() {

            };

            client2.connect('xatikont', 'tester322', on_connect, on_error, '/')

        });
    }

    startListener() {

        window.ActiveChat = 0;

        $.get('https://youinroll.com/lib/ajax/chat/getUserRoom.php', function(data) {
            let ws = new WebSocket('wss://youinrolltinod.com:15673/ws');

            let ua = navigator.userAgent.toLowerCase();
            if (ua.indexOf('safari') != -1) {
                if (ua.indexOf('chrome') > -1) {
                    window.bc = new BroadcastChannel('private');
                    bc.onmessage = function(messageData) {
                        messageData = {
                            body: messageData.data
                        }

                        new YRChat().recieveMessages(messageData)
                    }
                } else {

                }
            }

            window.client = Stomp.over(ws);
            let choosenToken = '';
            let ownerId = '';

            client.debug = true;

            let queuePrefix = 'private';
            let roomHash = data;

            let on_connect = function() {

                client.subscribe(`/queue/${queuePrefix}-${roomHash}`, function(data) {
                    if (ua.indexOf('safari') != -1) {
                        if (ua.indexOf('chrome') > -1) {
                            bc.postMessage(data.body);
                        }
                    }
                    new YRChat().recieveMessages(data);
                });
            };

            let on_error = function() {

            };

            client.connect('xatikont', 'tester322', on_connect, on_error, '/')
        })
    }


    /* Private actions */

    /*
     * Method for getting all conversations of user
     * @param {Object} params.userId Id of chat, whose opened on page
     * @param {void} params.afterCreate function after completed
     */
    _getConversations(params) {

        $.get("lib/ajax/chat/getConversations.php",
            function(data) {

                let conversations = JSON.parse(data);

                for (const key in conversations) {

                    let isCurrent = "";

                    let chat = conversations[key];

                    if (params.chatId !== undefined || params.chatId !== 0) {
                        isCurrent = (params.chatId === chat.id) ? "msg-active" : "";
                    }
                    let $chat = `<div class="msg ${isCurrent}" data-id="${(chat.conf_id !== undefined) ? chat.conf_id : 0}">
                        <img class="msg-profile" src="${chat.avatar}"
                            alt="${chat.title}" />
                        <div class="msg-detail">
                            <div class="msg-username">${chat.title}</div>
                            <div class="msg-content">
                                <span class="msg-message">${chat.lastMessage}</span>
                                <span class="msg-date">${chat.lastUpdate}</span>
                            </div>
                        </div>
                    </div>`;

                    $(params.block).after($chat);
                }

                if (params.userId !== undefined && params.userId !== 0 && !isNaN(params.userId)) {

                    $.get("lib/ajax/chat/getConversation.php", {
                            userId: params.userId
                        },
                        function(data) {

                            let chat = JSON.parse(data);

                            let $chat = `<div class="msg msg-active" data-id="${(chat.conf_id !== undefined) ? chat.conf_id : 0}" data-user="${chat.userId}">
                                <img class="msg-profile" src="${chat.avatar}"
                                    alt="${chat.title}" />
                                <div class="msg-detail">
                                    <div class="msg-username">${chat.title}</div>
                                    <div class="msg-content">
                                        <span class="msg-message">${chat.lastMessage ? chat.lastMessage : ''}</span>
                                        <span class="msg-date">${chat.lastUpdate}</span>
                                    </div>
                                </div>
                            </div>`;

                            if ($(`.msg[data-id='${(chat.conf_id !== undefined) ? chat.conf_id : 0}']`).length === 0) {
                                $(params.block).after($chat);
                            }

                            if (params.userId !== undefined) {
                                $(`.msg[data-id='${(chat.conf_id !== undefined) ? chat.conf_id : 0}']`).click();
                            }

                            if (chat.conf_id !== undefined) {
                                params.chatActiveCallback();
                            }

                        }).then(() => {
                        params.afterCreate();
                    });
                }

            });
    }

    /* 
     * Method for searching channels by name
     */
    _getChannels(params) {

        $.get("lib/ajax/chat/searchChannels.php", {
                value: params.value
            },
            function(data) {

                let chats = JSON.parse(data);

                chats.forEach(chat => {

                    let $chat = `<div class="msg search_result" data-id="0" data-user="${chat.id}">
                    <img class="msg-profile" src="${chat.avatar}"
                        alt="${chat.name}" />
                    <div class="msg-detail">
                        <div class="msg-username">${chat.name}</div>
                    </div>
                </div>`;

                    $(params.block).after($chat);

                });

            });
    }

    /*
     * Method for getting messages of selected chat
     * @param {Object} params.chatId Id of selected chat
     */
    _getMessages(params) {

        $.get("https://youinroll.com/lib/ajax/chat/getMessages.php", {
                chatId: params.chatId,
                page: params.page
            },
            function(data) {

                let messages = JSON.parse(data);

                ActiveChat = params.chatId;

                // TODO Это временное решение, стоит сделать через API
                // START
                let prevVal = parseInt($('#chatNotifyBadge').text());
                let prevvVal = parseInt($(`.msg[data-id="${params.chatId}"]`).find('.badge').text());
                if((prevVal>0) && (prevvVal>0)) {
                    prevVal -= prevvVal;
                    if(prevVal<0) {
                        prevVal = 0;
                    }
                    $('#chatNotifyBadge').text(prevVal);
                    if(prevVal==0){
                        $('#chatNotifyBadge').css('display','none');
                    }
                }
                // END

                $(`.msg[data-id="${params.chatId}"]`).find('.badge').remove();

                for (const key in messages.messages) {

                    let message = messages.messages[key];

                    let typeClass = message.isMine ? 'owner' : '';

                    let $filePreview = '';

                    if (message.file_id !== undefined && message.file_id !== null) {

                        $filePreview = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-paperclip">
                            <path
                                d="M21.44 11.05l-9.19 9.19a6 6 0 01-8.49-8.49l9.19-9.19a4 4 0 015.66 5.66l-9.2 9.19a2 2 0 01-2.83-2.83l8.49-8.48" />
                        </svg>`;

                        message.file.name = message.file.name.substr(0, 10).substr(0, message.file.name.lastIndexOf(".")) + '.' + message.file.name.substring(message.file.name.lastIndexOf(".") + 1)

                        if (message.file.type.match(/^image/)) {
                            $filePreview = `<img src="${message.file.path.replace('/download.php?path=','')}" width="20" height="40">`;
                            message.file.name = '';
                        }

                        $filePreview = `
                        <a target="blank" href="${message.file.path}">
                            ${$filePreview}
                            ${message.file.name}
                        <a><br>`;
                    }

                    let icon = '';

                    if (message.isMine) {
                        icon = `<i class="material-icons">${message.readed === '1' ? 'done_all' : 'done'}</i>`;
                    }

                    let exp = /(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/i;
                    message.text = message.text.replace(exp, "<a target='blank' href='$1'>$1</a>");

                    let $message = `
                    <div class="chat-msg ${typeClass}">
                        <div class="chat-msg-profile">
                            <a href='${message.avatarLink}'>
                                <img class="chat-msg-img"
                                    src="${message.avatar}"
                                    alt="${message.author}" />
                            </a>
                            <div class="chat-msg-date">${message.author} ${moment(message.created_at, 'YYYY-MM-DD hh:mm:ss').lang('ru').fromNow()}</div>
                        </div>
                        <div class="chat-msg-content">
                            <div class="chat-msg-text">${$filePreview}${message.text}</div>
                            ${icon}
                        </div>
                    </div>`;

                    if (parseInt(params.page) > 1) {
                        $(params.block).prepend($message);
                    } else {
                        $(params.block).append($message);
                    }

                }

                $(params.blockContact).empty();

                rendered = true;

                for (const key in messages.chatInfo[0]) {
                    if (key < 5) {
                        let user = messages.chatInfo[0][key];

                        $(params.blockContact).prepend(`<img class="chat-area-profile" data-id="${user.user_id}" src="${user.authorImage}" alt="" />`);
                    }
                }

                if (messages.chatInfo.length !== 0 && messages.chatInfo[0].length > 3) {
                    let participantsLeft = messages.chatInfo[0].length - 3;

                    $(params.blockContact).append(`<span>${participantsLeft}</span>`);
                }

                $(params.blockContact).append(`<span data-toggle="modal" data-target="#addParticipantModal" id='addParticipant'>+</span>`);

                document.querySelector(".chat-area").scrollTo(0, document.querySelector(".chat-area").scrollHeight);

            })
    }

    sendPing(chatId) {

        $.post("https://youinroll.com/lib/ajax/chat/sendPing.php", {
                'chatId': chatId
            },
            function(data) {

                data = JSON.parse(data);

                for (let i = 0; i < data.length; i++) {
                    const chat = data[i];

                    client.send(`/queue/private-${chat.chatRoom}`, {}, JSON.stringify(chat));
                }
            });
    }

    _sendMessage(params) {

        let postData;

        if (params.file !== undefined || params.file !== null) {

            postData = new FormData();

            postData.append("chatId", params.chatId);
            postData.append("text", params.text);

            if (params.toUser !== undefined && params.toUser !== 0) {

                postData.append("chatId", params.chatId);
                postData.append("userId", params.toUser);
                postData.append("private", true);
                postData.append("text", params.text);
            }

            postData.append("chatfile", params.file);

            $.ajax({
                type: "POST",
                contentType: "multipart/form-data",
                url: "https://youinroll.com/lib/ajax/chat/sendMessage.php",
                data: postData,
                processData: false,
                contentType: false,
                success: function(data) {

                    data = JSON.parse(data);

                    ActiveChat = parseInt(data.message.chatId);

                    $('.msg-active').data('id', data.message.chatId);

                    if (data !== null) {
                        for (const user in data.users) {

                            if (params.file !== undefined || params.file !== null) {
                                data.file_id = data.message.file_id;
                            }

                            data.message.private = true;

                            client.send(`/queue/private-${data.users[user].chatRoom}`, {}, JSON.stringify(data.message));
                        }
                    }

                }
            });

        } else {
            postData = {
                chatId: params.chatId,
                text: params.text
            };

            if (params.toUser !== undefined && params.toUser !== 0) {
                postData = {
                    chatId: params.chatId,
                    userId: params.toUser,
                    text: params.text,
                    private: true
                };
            }

            $.post("https://youinroll.com/lib/ajax/chat/sendMessage.php",
                postData,
                function(data) {

                    data = JSON.parse(data);

                    ActiveChat = parseInt(data.message.chatId);

                    $('.msg-active').data('id', data.message.chatId);

                    if (data !== null) {
                        for (const user in data.users) {
                            client.send(`/queue/private-${data.users[user].chatRoom}`, {}, JSON.stringify(data.message));
                        }
                    }

                });
        }
    }

    _getStreamMessages(params) {

        $.get("https://youinroll.com/lib/ajax/jitsi/getMessages.php", {
                streamId: params.streamId
            },
            function(data) {

                let messages = JSON.parse(data);

                for (const key in messages) {

                    let message = messages[key];

                    let typeClass = message.isMine ? 'owner' : '';
                    let $filePreview = '';

                    if (message.file_id !== undefined && message.file_id !== null) {
                        $filePreview = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-paperclip">
                            <path
                                d="M21.44 11.05l-9.19 9.19a6 6 0 01-8.49-8.49l9.19-9.19a4 4 0 015.66 5.66l-9.2 9.19a2 2 0 01-2.83-2.83l8.49-8.48" />
                        </svg>`;

                        message.file.name = message.file.name.substr(0, 10).substr(0, message.file.name.lastIndexOf(".")) + '.' + message.file.name.substring(message.file.name.lastIndexOf(".") + 1)

                        if (message.file.type.match(/^image/)) {
                            $filePreview = `<img src="${message.file.path.replace('/download.php?path=','')}" width="20" height="40">`;
                            message.file.name = '';
                        }

                        $filePreview = `
                        <a target="blank" href="${message.file.path}">
                            ${$filePreview}
                            ${message.file.name}
                        <a><br>`;
                    }

                    let exp = /(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/i;
                    message.text = message.text.replace(exp, "<a target='blank' href='$1'>$1</a>");

                    let $message = `<div class="chat-msg ${typeClass}">
                        <div class="chat-msg-profile">
                            <a href='${message.avatarLink}'>
                                <img class="chat-msg-img"
                                    src="${message.avatar}"
                                    alt="${message.author}" />
                            </a>
                            <div class="chat-msg-date">${message.author}</div>
                        </div>
                        <div class="chat-msg-content">
                            <div class="chat-msg-text">${$filePreview}${message.text}</div>
                        </div>
                    </div>`;

                    $(params.block).append($message);

                }

                document.querySelector(".chat-area").scrollTo(0, document.querySelector(".chat-area").scrollHeight);

            });
    }

    _sendMessageStream(params) {

        if (params.file !== undefined || params.file !== null) {

            let postData = new FormData();

            postData.append("streamId", params.streamId);
            postData.append("text", params.text);

            postData.append("chatfile", params.file);

            $.ajax({
                type: "POST",
                contentType: "multipart/form-data",
                url: "https://youinroll.com/lib/ajax/jitsi/sendMessage.php",
                data: postData,
                processData: false,
                contentType: false,
                success: function(data) {

                    data = JSON.parse(data);

                    if (data !== null) {

                        for (let index = 0; index < data.queues.length; index++) {

                            let viewerId = data.queues[index].chatRoom;

                            client2.send(`/queue/stream-${data.chatRoom}-${viewerId}`, {}, JSON.stringify(data.message));

                        }

                    }

                }
            });
        } else {

            let postData = {
                streamId: params.streamId,
                text: params.text
            };

            $.post("https://youinroll.com/lib/ajax/jitsi/sendMessage.php",
                postData,
                function(data) {

                    data = JSON.parse(data);

                    if (data !== null) {

                        for (let index = 0; index <= data.views; index++) {

                            let viewerId = index;

                            client2.send(`/queue/stream-${data.chatRoom}-${viewerId}`, {}, JSON.stringify(data.message));

                        }

                    }

                });
        }
    }

}