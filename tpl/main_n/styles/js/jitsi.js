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
                        from: oJSJaCPacket.getFrom().replace('@test.xatikont.beget.tech/jsjac_simpleclient', '').replace('-dmnsymb-', '@'),
                        text: messageBody
                    }
                }
            });

        } else {

            let currentEmail = JSON.parse(localStorage.getItem('jitsiArguments')).username.replace('-dmnSymb-', '@');

            let isMine = (oJSJaCPacket.getFrom().replace('@test.xatikont.beget.tech/jsjac_simpleclient', '').replace('-dmnsymb-', '@') === currentEmail) ?
                true : false;

            if (!isMine) {
                $.notify(messageBody);
            }

        }
    }

    handlePresence(oJSJaCPacket) {
        console.log(oJSJaCPacket);
    }

    handleError(e) {

        console.log(e);

        if (con.connected())
            con.disconnect();
    }

    handleStatusChanged(status) {
        oDbg.log("status changed: " + status);
    }

    handleConnected() {

        con.send(new JSJaCPresence());
    }

    handleDisconnected() {

    }

    handleIqVersion(iq) {
        con.send(iq.reply([iq.buildNode('name', 'jsjac simpleclient'), iq.buildNode('version', JSJaC.Version), iq.buildNode('os', navigator.userAgent)]));
        return true;
    }

    handleIqTime(iq) {
        let now = new Date();
        con.send(iq.reply([iq.buildNode('display', now.toLocaleString()), iq.buildNode('utc', now.jabberDate()), iq.buildNode('tz', now.toLocaleString().substring(now.toLocaleString().lastIndexOf(' ') + 1))]));
        return true;
    }

    startListener() {

        let roomName = 'global-room';

        let httpbase = 'https://test.xatikont.beget.tech/http-bind?room=' + roomName + '-chat-room';

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

    doLogin(oForm, roomName, registered) {

        registered = (registered === 1) ? true : false;

        roomName = 'global-room'

        let username = oForm.email.value.replace('@', '-dmnSymb-');

        oArgs = new Object();

        oDbg = new JSJaCConsoleLogger(3);

        try {

            let httpbase = 'https://test.xatikont.beget.tech/http-bind?room=' + roomName + '-chat-room';

            // set up the connection
            con = new JSJaCHttpBindingConnection({
                oDbg: oDbg,
                httpbase: httpbase,
                timerval: 500
            });

            this.setupCon(con);

            // setup args for connect method
            oArgs.domain = 'test.xatikont.beget.tech';
            oArgs.username = `${username}`;
            oArgs.resource = 'jsjac_simpleclient';
            oArgs.pass = oForm.password.value;
            oArgs.register = !registered;

            localStorage.setItem('jitsiArguments', JSON.stringify(oArgs));

            con.connect(oArgs);

        } catch (e) {
            console.log(e);
        } finally {
            return false;
        }
    }

    setupCon(oCon) {
        oCon.registerHandler('message', this.handleMessage);
        oCon.registerHandler('presence', this.handlePresence);
        oCon.registerHandler('iq', this.handleIQ);
        oCon.registerHandler('onconnect', this.handleConnected);
        oCon.registerHandler('onerror', this.handleError);
        oCon.registerHandler('status_changed', this.handleStatusChanged);
        oCon.registerHandler('ondisconnect', this.handleDisconnected);

        oCon.registerIQGet('query', NS_VERSION, this.handleIqVersion);
        oCon.registerIQGet('query', NS_TIME, this.handleIqTime);
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
                            TILE_VIEW_MAX_COLUMNS: 1,
                            DISABLE_JOIN_LEAVE_NOTIFICATIONS: true,    
                        },
                        onload: function(){
                            new JitsiChat().joinToChat(data,stream.name);
                        }
                    };

                    let JitsiApi = new JitsiMeetExternalAPI(domain, options);

                    JitsiApi.addEventListeners({
                        readyToClose: function (data) {
                            console.log(data);
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

        let httpbase = 'https://test.xatikont.beget.tech/http-bind?room=' + roomName;

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