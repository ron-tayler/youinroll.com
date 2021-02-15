$(document).on('ready', function() {

    let topPanelImg = '.chat-top-panel > div > img';
    let topPanelText = '.chat-top-panel > div > h3';


    if($('#chatMainBlock').data('active') === 0) {
      $('#messageBox').hide();
      $('#emptyDialog').show();
    }
    
    let chatClass = new YRChat();
  
    chatClass.runMethod({
      method: 'getConversations',
      params: {
        block: '#conversationsList',
        userId: parseInt($('#chatMainBlock').data('active')),
        chatActiveCallback: function() {
  
          let chatName = $('.list-group-item.active').find('.media-heading').text();
          let chatImage = $('.list-group-item.active').find('.img-responsive').prop('src');
  
          $(topPanelText).text(chatName);
          $(topPanelImg).prop('src', chatImage);
  
          chatClass.runMethod({
            method: 'getMessages',
            params: {
              block: '#messageBox .chats',
              chatId: $('.list-group-item.active').data('id')
            }
          });
  
        },
        afterCreate: function() {
          let chatName = $('.list-group-item.active').find('.media-heading').text();
          let chatImage = $('.list-group-item.active').find('.img-responsive').prop('src');
  
          $(topPanelText).text(chatName);
          $(topPanelImg).prop('src', chatImage);
          
        }
      }
    });
  
    $('#sendChat').on('click', function(){
      chatClass.runMethod({
        method: 'sendMessage',
        params: {
          form: '#insertChat',
          text: $('#insertChat').val(),
          toUser: $('.list-group-item.active').data('user'),
          chatId: $('.list-group-item.active').data('id'),
          afterComplete: function(data){
            let jitsiArguments = JSON.parse(localStorage.getItem('jitsiArguments'));
  
            jitsiClass.sendMsg(document.getElementById('chatForm'), jitsiArguments.username);
  
            data.participants.forEach(participant => {
              console.log(jitsiClass.sendMsg(document.getElementById('chatForm'), `${participant.jitsiLogin}`));
            });
  
            $('#insertChat').val('');
            $('.emoj-editor').text('');
          }
        }
      });
  
  
    })
  
    $('body').on('click', '#conversationsList > li', function(e){
  
        $('#messageBox').show();
        $('#emptyDialog').hide();
        $('.list-group-item.active').removeClass('active');
        $(this).addClass('active');
  
        let chatName = $(this).find('.media-heading').text();
        let chatImage = $(this).find('.img-responsive').prop('src');
  
        $(topPanelText).text(chatName);
        $(topPanelImg).prop('src', chatImage);
  
        $('#messageBox .chats').empty();
  
        chatClass.runMethod({
          method: 'getMessages',
          params: {
            block: '#messageBox .chats',
            chatId: $(this).data('id')
          }
        });
    })
  
    $('#channelTag').on('change', function(){
      
      let value = $(this).val();
  
      if(value.length > 3)
      {
        $('#conversationsList').empty();
        chatClass.runMethod({
          method: 'getChannels',
          params: {
            block: '#conversationsList',
            value: value
          }
        });
      }
  
      if(value.length === 0)
      {
        chatClass.runMethod({
          method: 'getConversations',
          params: {
            block: '#conversationsList',
            userId: parseInt($('#chatMainBlock').data('active')),
            chatActiveCallback: function() {
  
              let chatName = $('.list-group-item.active').find('.media-heading').text();
              let chatImage = $('.list-group-item.active').find('.img-responsive').prop('src');
  
              $(topPanelText).text(chatName);
              $(topPanelImg).prop('src', chatImage);
  
              chatClass.runMethod({
                method: 'getMessages',
                params: {
                  block: '#messageBox .chats',
                  chatId: $('.list-group-item.active').data('id')
                }
              });
  
            },
            afterCreate: function() {
              let chatName = $('.list-group-item.active').find('.media-heading').text();
              let chatImage = $('.list-group-item.active').find('.img-responsive').prop('src');
  
              $(topPanelText).text(chatName);
              $(topPanelImg).prop('src', chatImage);
              
            }
          }
        });
      }
    })
  
  })