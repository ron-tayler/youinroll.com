class YRChat {
    constructor(options) {

        this.actions = {
            'getConversations': this._getConversations,
            'getMessages': this._getMessages,
            'recieveMessages': this._recieveMessages,
            'sendMessage': this._sendMessage,
            'getChannels': this._getChannels
        }
    }

    runMethod(options) {
        this.actions[options.method](options.params);
    }

    /* Private actions */

    /*
     * Method for getting all conversations of user
     * @param {Object} params.userId Id of chat, whose opened on page
     * @param {void} params.afterCreate function after completed
     */
    _getConversations(params) {

        if (params.userId !== undefined && params.userId !== 0) {

            $.get("lib/ajax/chat/getConversation.php", {
                    userId: params.userId
                },
                function(data) {

                    let chat = JSON.parse(data);

                    let $chat = `<li class="list-group-item active" data-id="${(chat.conf_id !== undefined) ? chat.conf_id : 0}" data-user="${chat.userId}">
                        <div class="media">
                        <div class="media-left">
                            <a class="avatar" href="${chat.profileUrl}">
                            <img class="img-responsive" src="${chat.avatar}" alt="${chat.title}"><i></i>
                            </a>
                        </div>
                        <div class="media-body">
                            <h4 class="media-heading">
                                <p>
                                ${chat.title}
                                </p>
                            </h4>
                            <span class="media-text">${chat.lastMessage}</span>
                            <span class="media-time">${chat.lastUpdate}</span>
                        </div>
                        <div class="media-right">
                            <!-- <span class="badge badge-danger">${chat.unreadCount}</span> -->
                        </div>
                        </div>
                    </li>`;

                    $(params.block).append($chat);

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
                        isCurrent = (params.chatId === chat.id) ? "active" : "";
                    }

                    let $chat = `<li class="list-group-item ${isCurrent}" data-id="${chat.conf_id}">
                        <div class="media">
                        <div class="media-left">
                            <a class="avatar" href="${chat.profileUrl}">
                            <img class="img-responsive" src="${chat.avatar}" alt="${chat.title}"><i></i>
                            </a>
                        </div>
                        <div class="media-body">
                            <h4 class="media-heading">
                                <p>
                                ${chat.title}
                                </p>
                            </h4>
                            <span class="media-text">${chat.lastMessage}</span>
                            <span class="media-time">${chat.lastUpdate}</span>
                        </div>
                        <div class="media-right">
                            ${( parseInt(chat.unreadCount) !== 0) ? '<span class="badge badge-danger">'+chat.unreadCount+'</span>' : ''}
                        </div>
                        </div>
                    </li>`;

                    $(params.block).append($chat);
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

                let $chat = `<li class="list-group-item active" data-id="0" data-user="${chat.id}">
                    <div class="media">
                    <div class="media-left">
                        <a class="avatar" href="${chat.profileUrl}">
                        <img class="img-responsive" src="${chat.avatar}" alt="${chat.name}"><i></i>
                        </a>
                    </div>
                    <div class="media-body">
                        <h4 class="media-heading">
                            <p>
                            ${chat.name}
                            </p>
                        </h4>
                        <span class="media-text">${chat.lastMessage}</span>
                        <span class="media-time"></span>
                    </div>
                    <div class="media-right">
                        <!-- <span class="badge badge-danger">${chat.unreadCount}</span> -->
                    </div>
                    </div>
                </li>`;

                $(params.block).append($chat);
                
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


                for (const key in messages) {

                    let isCurrent = "";

                    let message = messages[key];

                    let typeClass = message.isMine ? 'chat-right' : 'chat-left';

                    let $message = `<div class="chat dummy-chat ${typeClass}">
                      <div class="chat-avatar">
                        <a target="_blank" class="avatar" href="${message.avatarLink}" title="${message.author}">
                          <img src="${message.avatar}" alt="${message.author}">
                        </a>
                      </div>
                      <div class="chat-body">
                        <div class="chat-content">
                          ${message.text}
                        </div>
                      </div>
                    </div>`;

                    $(params.block).append($message);
                }

            });
    }

    /*
     * Method for recieving messages of selected chat
     * @param {Object} params.message.from Message from
     * @param {Object} params.message.text Text of message
     */
    _recieveMessages(params) {

        let message = params.message;

        let currentEmail = JSON.parse(localStorage.getItem('jitsiArguments')).username.replace('-dmnSymb-', '@');

        let isMine = (message.from.replace('-dmnSymb-', '@') === currentEmail) ?
            true : false;

        let typeClass = isMine ? 'chat-right' : 'chat-left';

        $.get("https://youinroll.com/lib/ajax/chat/getAvatarByEmail.php", {
                email: message.from.replace('-dmnSymb-', '@')
            },
            function(data) {
                let userInfo = JSON.parse(data);

                let $message = `<div class="chat dummy-chat ${typeClass}">
                    <div class="chat-avatar">  
                        <a target="_blank" class="avatar" href="${userInfo.avatarLink}" title="${userInfo.name}">
                            <img src="${userInfo.avatar}" alt="${userInfo.name}">
                        </a>
                    </div>
                    <div class="chat-body">
                        <div class="chat-content">
                            ${message.text}
                        </div>
                    </div>
                </div>`;

                $(params.block).append($message);
            });
    }

    _sendMessage(params) {

        let postData = {
            chatId: params.chatId,
            text: params.text
        };

        if (params.toUser !== undefined || params.toUser !== 0) {
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

                if (data.result === true && data.result !== undefined) {
                    params.afterComplete(data);
                }

            });
    }
}