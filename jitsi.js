/* function runConf(block, name, email, username, avatar, role)
{
    const domain = 'smartfooded.com';
    const options = {
        roomName: name,
        parentNode: document.querySelector(block),
        userInfo: {
            email: email,
            displayName: username
        },
        configOverwrite:{
            doNotStoreRoom: false,
            startVideoMuted: 0,
            startWithVideoMuted: true,
            startWithAudioMuted: true,
            enableWelcomePage: false,
            prejoinPageEnabled: false,
        },
        interfaceConfigOverwrite: {
            DISPLAY_WELCOME_PAGE_CONTENT: false            
        },
        onload: function(){

            
        }
    };

    window.JitsiApi = new JitsiMeetExternalAPI(domain, options);

    localStorage.removeItem('activeConference');
    localStorage.removeItem('userAvatar');
    localStorage.removeItem('userRole');

   

    JitsiApi.executeCommand('avatarUrl', avatar);

    localStorage.setItem('activeConference', JSON.stringify(options) );
    localStorage.setItem('userAvatar', avatar);
    localStorage.setItem('userRole', role);

    return options;
}

function bindEvents(block, role)
{
    let participants = [];

    JitsiApi.addEventListeners({

        participantRoleChanged: function (){
            
        },
        readyToClose: function () {

            for (const participant in participants) {
               JitsiApi.executeCommand('kickParticipant', participant.id);
            }

            $(block).remove();
            localStorage.removeItem('activeConference');
            localStorage.removeItem('userAvatar');
            localStorage.removeItem('userRole');
        },
        audioMuteStatusChanged: function (data) {
            
        },
        videoMuteStatusChanged: function (data) {
            
        },
        tileViewChanged: function (data) {
            
        },
        screenSharingStatusChanged: function (data) {
            
        },
        participantJoined: function(data){
            
        },
        participantLeft: function(data){
            //participants.removeItem(data);
        }
    });
}

$(document).on('ready', function(){

    let url = window.location.href;
    let regexp = url.match('/conference\/.*?/');

    if(regexp !== null)
    {
        
    }

    if(localStorage.getItem('activeConference') !== null && regexp === null)
    {
        let div = document.createElement('div');
        div.className = "minimized";
        div.id = "meet";
    
        document.body.append(div);
        
        var options = JSON.parse(localStorage.getItem('activeConference'));

        options.parentNode = document.querySelector('#meet');

        #runConf('#meet', options.roomName, options.userInfo.email, options.userInfo.displayName, localStorage.getItem('userAvatar'), localStorage.getItem('userRole'));
    
        #JitsiApi.executeCommand('toggleMinimized', true);
    }
});
 */