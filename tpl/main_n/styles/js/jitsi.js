let oDbg, con, oArgs;

class JitsiChat {

    runConf(block, streamId, onComplete) {
        const domain = 'smartfooded.com';

	// stream ID doesnt work for the first time when user creates lesson 

	     console.log("Before stream ID");
	    console.log(streamId);
	    console.log("After stream ID");

        $.get('https://youinroll.com/lib/ajax/jitsi/getStreamInfo.php', {
            streamId: streamId
        }, function(data) {
		// Ошибка  Unexpected end of JSON input 
		// errrorrr toDo
	    

            data = JSON.parse(data);


            if (data.user.isAuthor) {

            } else {
                $(block).unbind('mouseenter').unbind('mouseleave').unbind('click');
            }

            if (parseInt(data.stream.on_air) !== 0) {
                $('.streamerStatus').addClass('online');

                onComplete();

                $('#i-like-it').find('span').text(data.stream.likes ? data.stream.likes : 0);
                $('.pv_tip.views').find('span').text(data.stream.views ? data.stream.likes : 0);

                if (data.stream.type === 'stream') {
			// https://stackoverflow.com/questions/19521667/disable-fullscreen-iphone-video - play video in browser for iphone - add playsinline
                    let $videoplayer = `<video playsinline class="video-js vjs-default-skin vjs-big-play-centered vjs-fluid tvideo-dimensions vjs-big-play-centered  vjs-controls-enabled vjs-workinghover vjs-v6 vjs-brand vjs-has-started vjs-paused vjs-user-inactive" id="videoPlayer">
                    <source
                        src="https://smartfooded.com:8443/hls/${data.stream.token}.m3u8"
                        type="application/x-mpegURL"></source>
                    </video>`;


document.getElementById("openfile").remove();

                    $("#stream").append($videoplayer);

                    let options = {
                        autoplay: 'any',
                        width: 100,
			    
			    controls: true,
			    preload: "auto",
			    language: "ru-ru",
			    liveTracker: true,
                        autoSetup: true,
                        textTrackSettings: false,
			//    ErrorDisplay: "Возникла ошибка, перезагрузите страницу",
                        notSupportedMessage: "Трансляция ещё не началась",
                        liveui: true,
                        controlBar: {
                            children: [
                                'playToggle',
                                'volumePanel',
                                'fullscreenToggle'
                            ],
                        }
                    };

                    let player = videojs('#videoPlayer', options);
	

                    /*  player.src({
                         src: `https://smartfooded.com:8443/hls/${data.stream.token}.m3u8`,
                         type: "application/x-mpegURL"
                     }); */

                    return null;
                }

                let allowedButtons = (data.user.isAuthor) ? ['desktop', 'fullscreen', 'security', 'sharedvideo', 'tileview', 'microphone', 'camera', 'settings', 'recording', 'livestreaming'] : [
                    'desktop', 'microphone', 'camera', 'raisehand', 'fullscreen', 'tileview'
                ];
                const options = {
                    roomName: data.stream.transName,
                    parentNode: document.querySelector(block),
                    width: 'auto',
                    userInfo: {
                        email: data.user.email,
                        displayName: data.user.name
                    },
                    configOverwrite: {
                        doNotStoreRoom: false,
                        enableWelcomePage: false,
                        prejoinPageEnabled: false
                    },
                    interfaceConfigOverwrite: {
                        TOOLBAR_BUTTONS: allowedButtons,
                        DISPLAY_WELCOME_PAGE_CONTENT: false,
                        DISABLE_JOIN_LEAVE_NOTIFICATIONS: true,
                        DEFAULT_BACKGROUND_IMAGE: data.stream.cover,
                        IS_STREAM: true
                    },
                    onload: function() {
                        /* $('#hangout-all').on('click', function(){
                                let participants = JitsiApi.getParticipantsInfo();
        
                                for (const participant in participants) {
                                    JitsiApi.executeCommand('kickParticipant',
                                        participants[participant].participantId
                                    )
                                }
                            }); */
                    }
                };

                window.JitsiApi = new JitsiMeetExternalAPI(domain, options);

                JitsiApi.getLivestreamUrl().then(livestreamData => {
                    console.warn('///////');
                    console.log(livestreamData);
                    console.warn('///////');
                });

                setTimeout(() => {

                    JitsiApi.executeCommand('subject', ' ');

                    if (data.user.isAuthor) {
                        JitsiApi.addListener('readyToClose', function() {

                            let participants = JitsiApi.getParticipantsInfo();

                            let pinnedParticipant = participant.id;

                            pinnedParticipant = participants[0].participantId

                            JitsiApi.pinParticipant(pinnedParticipant);
                            JitsiApi.executeCommand('setLargeVideoParticipant', pinnedParticipant);

                            /* let participants = JitsiApi.getParticipantsInfo();

                            for (const participant in participants) {
                                JitsiApi.executeCommand('kickParticipant',
                                    participants[participant].participantId
                                )
                            }

                            JitsiApi.dispose(); */

                            //window.location.href = 'https://youinroll.com/dashboard';
                        });
                    }

                    JitsiApi.addListener('participantLeft', function(participant) {

                        let participants = JitsiApi.getParticipantsInfo();

                        let trigger = false;

                        for (const i in participants) {
                            if (participants[i].displayName === data.author.name) {
                                trigger = true;
                            }
                        }

                        if (!trigger) {
                            //window.location.href = 'https://youinroll.com/dashboard';
                        }
                    });

                    JitsiApi.addListener('participantJoined', function(participant) {
                        let participants = JitsiApi.getParticipantsInfo();

                        let pinnedParticipant = participant.id;

                        pinnedParticipant = participants[0].participantId

                        JitsiApi.pinParticipant(pinnedParticipant);
                        JitsiApi.executeCommand('setLargeVideoParticipant', pinnedParticipant);
                    })

                    JitsiApi.executeCommand('avatarUrl', 'https://youinroll.com/' + data.user.avatar);

                }, 10000);

            } else {
                $(block).append(
                    `<div class="vprocessing" style="background-size: contain; background-repeat: no-repeat; background-color: #f3f3f3">
                            <div class="blured-block">
                                <div class="vpre">Stream is offline</div>
                            </div>
                        </div>`
                );
            }

        });
    }
}
