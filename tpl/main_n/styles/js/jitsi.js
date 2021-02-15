let oDbg, con, oArgs;

class JitsiChat {

    // <![CDATA[
    handleIQ(oIQ) {
        console.log(oIQ.xml().htmlEnc());
        con.send(oIQ.errorReply(ERR_FEATURE_NOT_IMPLEMENTED));
    }

    handleMessage(oJSJaCPacket) {

        let messageBody = oJSJaCPacket.getBody().htmlEnc().replace(/\!\-\-\-\-\-.*/, '')

        if (window.location.pathname.split('/')[1] === 'conversation') {

            chatClass.runMethod({
                method: 'recieveMessages',
                params: {
                    block: '#messageBox .chats',
                    message: {
                        from: oJSJaCPacket.getFrom().replace('@smartfooded.com/jsjac_simpleclient', '').replace('-dmnsymb-', '@'),
                        text: messageBody
                    }
                }
            });

        } else {

            let currentEmail = JSON.parse(localStorage.getItem('jitsiArguments')).username.replace('-dmnSymb-', '@');

            let isMine = (oJSJaCPacket.getFrom().replace('@smartfooded.com/jsjac_simpleclient', '').replace('-dmnsymb-', '@') === currentEmail) ?
                true : false;

            if (!isMine) {
                $.notify(messageBody);
            }

        }
    }

    
    startListener(roomHash) {



        /* let roomName = 'global-room';

        let bc = new BroadcastChannel('profile');
        let ws = new WebSocket('wss://youinrolltinod.com:15674/ws');

        let client = Stomp.over(ws);
        let choosenToken = '';
        let ownerId = '';

        bc.onmessage = function (messageData) {
            let currentId = (bc.name === 'user') ? userId : profileId;
            socketListener(messageData.data, currentId);
        }

        client.debug = true;

        let query_prefix = 'dev';

        
        let on_connect = function() {

            client.subscribe(`/queue/${queue_prefix}-${roomHash}`, function(data) {
                
            });
        };

        let on_error =  function() {
            
        };
        
        client.connect('xatikont', 'tester322', on_connect, on_error, '/'); */
    }

    sendMsg(oForm, roomName) {
        if (oForm.text.value == '' || roomName == '')
            return false;

        if (roomName.indexOf('@') == -1)
            roomName += '@' + con.domain;

        try {

            let rWord = Math.random().toString(36).substring(7);

            oForm.text.value += '!----- ' + rWord;

            let oMsg = new JSJaCMessage();
            oMsg.setTo(new JSJaCJID(roomName));
            oMsg.setBody(oForm.text.value);
            con.send(oMsg);

            return false;
        } catch (e) {
            console.log(e);
            return false;
        }
    }

    runConf(block, streamId, onComplete)
    {
        const domain = 'smartfooded.com';

        $.get('https://youinroll.com/lib/ajax/jitsi/getStreamInfo.php',
            {
                streamId: streamId     
            }, function (data) {

                data = JSON.parse(data);

                if(data.user.isAuthor) {

                } else
                {
                    $(block).unbind('mouseenter').unbind('mouseleave').unbind('click');
                }

                if(parseInt(data.author.onAir) !== 0)
                {
                    let allowedButtons = (data.user.isAuthor) ? ['hangup', 'microphone', 'camera', 'settings'] : [];
                    const options = {
                        roomName: data.stream.name,
                        parentNode: document.querySelector(block),
                        width: 'auto',
                        userInfo: {
                            email: data.user.email,
                            displayName: data.user.name
                        },
                        configOverwrite:{
                            doNotStoreRoom: false,
                            startVideoMuted: 0,
                            startWithVideoMuted: true,
                            startWithAudioMuted: true,
                            enableWelcomePage: false,
                            prejoinPageEnabled: false
                        },
                        interfaceConfigOverwrite: {
                            TOOLBAR_BUTTONS: allowedButtons,
                            DISPLAY_WELCOME_PAGE_CONTENT: false,
                            TILE_VIEW_MAX_COLUMNS: 0,
                            DISABLE_JOIN_LEAVE_NOTIFICATIONS: true,
                            IS_STREAM: true 
                        },
                        onload: function(){
                            /* new JitsiChat().joinToChat(data,stream.name); */
                        }
                    };

                    window.JitsiApi = new JitsiMeetExternalAPI(domain, options);

                    JitsiApi.addListener('readyToClose', function() {
                            window.location.href = 'https://youinroll.com/dashboard/';
                            JitsiApi.dispose();

                            let participants = JitsiApi.getParticipantsInfo();

                            for (const participant in participants) {
                                JitsiApi.executeCommand('kickParticipant',
                                    participant.participantId
                                )
                            }
                        });
            
                    JitsiApi.executeCommand('avatarUrl', data.user.avatar);

                } else
                {
                    $(block).append(
                        `<div class="vprocessing" style="background-size: contain; background-repeat: no-repeat; background-color: #f3f3f3">
                            <div class="blured-block">
                                <div class="vpre">Stream is offline</div>
                            </div>
                        </div>`
                    );
                }

                onComplete(data);

            });
    }

    joinToChat(roomName) {

        let httpbase = 'https://smartfooded.com/http-bind?room=' + roomName;

        oDbg = new JSJaCConsoleLogger(3);

        // set up the connection
        con = new JSJaCHttpBindingConnection({
            oDbg: oDbg,
            httpbase: httpbase,
            timerval: 500
        });

        this.setupCon(con);

        let savedArgs = JSON.parse(localStorage.getItem('jitsiArguments'));

        savedArgs.register = false;

        con.connect(savedArgs);
    }

    quit() {
        let p = new JSJaCPresence();
        p.setType("unavailable");
        con.send(p);
        con.disconnect();
    }


    onunload = function() {
        if (typeof con != 'undefined' && con && con.connected()) {
            // save backend type
            if (con._hold) // must be binding
                (new JSJaCCookie('btype', 'binding')).write();
            else
                (new JSJaCCookie('btype', 'polling')).write();
            if (con.suspend) {
                con.suspend();
            }
        }
    };
}