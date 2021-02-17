class YRChat {
    
    constructor(options) {

        this.actions = {
            'getConversations': this._getConversations,
            'getMessages': this._getMessages,
            'sendMessage': this._sendMessage,
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

        switch ($('body').data('route')) {
            case 'conversation':
                if(message.type === 'message')
                {
                    let typeClass = (parseInt(message.user_id) === CurrentUser)? 'owner' : '';

                    if(parseInt(message.chatId) === parseInt(ActiveChat))
                    {
                        let $message = `<div class="chat-msg ${typeClass}">
                            <div class="chat-msg-profile">
                                <a href='${message.avatarLink}'>
                                    <img class="chat-msg-img"
                                        src="${message.avatar}"
                                        alt="${message.author}" />
                                </a>
                                <div class="chat-msg-date">1.22pm</div>
                            </div>
                            <div class="chat-msg-content">
                                <div class="chat-msg-text">${message.text}</div>
                            </div>
                        </div>`;

                        $('.msg.msg-active').find('.msg-message').text(message.text);
                        $('.msg.msg-active').find('.msg-date').text('Сейчас');

                        $('.chat-area-main').append($message);

                        if(typeClass === 'owner')
                        {
                            document.querySelector(".chat-area").scrollTo(0,document.querySelector(".chat-area").scrollHeight);
                        }
                    }
                }
                break;
        
            default:
                $.notify(message.author + ': ' + message.text);
                let prevVal = $('#myInbox').find('.badge').text();

                $('#myInbox').find('.badge').text(parseInt(prevVal) + 1);
                break;
        }
    }

    startListener() {

        window.ActiveChat = 0;

        $.get('https://youinroll.com/lib/ajax/chat/getUserRoom.php', function (data){    
            
            let bc = new BroadcastChannel('private');
            let ws = new WebSocket('wss://youinrolltinod.com:15673/ws');

            window.client = Stomp.over(ws);
            let choosenToken = '';
            let ownerId = '';

            bc.onmessage = function (messageData) {
                console.log(messageData);
            }

            client.debug = true;

            let queuePrefix = 'private';
            let roomHash = data;
            
            let on_connect = function() {

                client.subscribe(`/queue/${queuePrefix}-${roomHash}`, function(data) {
                    new YRChat().recieveMessages(data)                   
                });
            };

            let on_error =  function() {
                
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

        if (params.userId !== undefined && params.userId !== 0 && !isNaN(params.userId) ) {

            $.get("lib/ajax/chat/getConversation.php", {
                    userId: params.userId
                },
                function(data) {

                    let chat = JSON.parse(data);

                    let $chat = `<div class="msg msg-active online" data-id="${(chat.conf_id !== undefined) ? chat.conf_id : 0}" data-user="${chat.userId}">
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

                    if (chat.conf_id !== undefined) {

                        params.chatActiveCallback();
                        /* YRChat.prototype._getMessages.call({
                            chatId: chat.conf_id,
                            block: params.block
                        }); */
                    }

                }).then(() => {
                params.afterCreate();
            });

            return null;
        }

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
                chatId: params.chatId
            },
            function(data) {

                let messages = JSON.parse(data);

                ActiveChat = params.chatId;

                for (const key in messages.messages) {

                    let message = messages.messages[key];

                    let typeClass = message.isMine ? 'owner' : '';

                    let $message = `<div class="chat-msg ${typeClass}">
                        <div class="chat-msg-profile">
                            <a href='${message.avatarLink}'>
                                <img class="chat-msg-img"
                                    src="${message.avatar}"
                                    alt="${message.author}" />
                            </a>
                            <div class="chat-msg-date">1.22pm</div>
                        </div>
                        <div class="chat-msg-content">
                            <div class="chat-msg-text">${message.text}</div>
                        </div>
                    </div>`;

                    $(params.block).append($message);

                }

                $(params.blockContact).empty();

                for(const key in messages.chatInfo[0])
                {
                    if(key < 5)
                    {
                        let user = messages.chatInfo[0][key];

                        $(params.blockContact).prepend(`<img class="chat-area-profile" data-id="${user.user_id}" src="${user.authorImage}" alt="" />`);
                    }   
                }

                if( messages.chatInfo[0].length > 3 )
                {
                    let participantsLeft = messages.chatInfo[0].length - 3;

                    $(params.blockContact).append(`<span>${participantsLeft}</span>`);
                }

                $(params.blockContact).append(`<span data-toggle="modal" data-target="#addParticipantModal" id='addParticipant'>+</span>`);

                document.querySelector(".chat-area").scrollTo(0,document.querySelector(".chat-area").scrollHeight);

            });
    }

    _sendMessage(params) {

        let postData = {
            chatId: params.chatId,
            text: params.text
        };

        if (params.toUser !== undefined && params.toUser !== 0) {
            postData = {
                chatId: params.chatId,
                userId: params.toUser,
                text: params.text
            };
        }

        $.post("https://youinroll.com/lib/ajax/chat/sendMessage.php",
            postData,
            function(data) {

                data = JSON.parse(data);

                if (data !== null) {
                    for (const user in data.users) {
                        client.send(`/queue/private-${data.users[user].chatRoom}`, {}, JSON.stringify(data.message));
                    }
                }

            });
    }
}