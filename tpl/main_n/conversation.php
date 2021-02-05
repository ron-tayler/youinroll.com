<?php the_sidebar(); ?>

<style>
.chat-top-panel {
  display: flex;
  width: 100%;
  height: 80px;
  padding: 10px;
}

.chat-top-panel > img {
  border-radius: 100%;
  align-self: start;
}

.chat-top-panel > h3 {
  margin-left: 10px;
}

.chat-top-panel > i {
  flex: end;
}

.chat-search {
  display: flex;
  justify-content: space-between;
  height: 75px;
  padding-right: 10px;
  padding-left: 10px;
  padding-top: 20px;
  background-color: #bfbfbf;
  position: relative;
}

.chat-search-append {
  position: absolute;
  top: 26%;
  right: 4%;
  /* border: 2px solid gray; */
  background-color: white;
  text-align: center;
  vertical-align: middle;
  font-size: 10px;
  border-radius: 70px;
  padding: 5px;
  height: 35px;
}

.app-message-list .list-group .list-group-item {
  padding: 10px;
}
</style>

<!-- Message Sidebar -->
<div class="page-aside">
  <div class="page-aside-switch">
    <i class="icon icon-chevron-left"></i>
    <i class="icon icon-chevron-right"></i>
  </div>
  <div class="chat-search">
    <input type="text" id='channelTag' class="form-control input-lg empty" name="channelTag" value="" placeholder="Поиск по каналам">                
    <div class="chat-search-append">
      <i class="material-icons">&#xe8b6;</i>
    </div>
  </div>
  <div class="page-aside-inner">
      <div class="app-message-list">
      <div data-role="container">
        <div data-role="content">
          <ul id='conversationsList' class="list-group">
          
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- End Message Sidebar -->

<div class="page-main" id='chatMainBlock' data-active='<?=$cid?>'>

  <div id="messageBox">

    <!-- Start Toppanel -->
    <div class='chat-top-panel'>
        <img width='50' height='50' src='' title=''>
        <h3>
          
        </h3>
        <i id='chatSettings' class='material-settings-icon'></i>
    </div>
    <!-- End Toppanel -->

    <!-- Chat Box -->
    <div class="app-message-chats">	  
      <div class="chats">
        
      </div>
    </div>
    <!-- End Chat Box -->

    <!-- Message Input-->
    <form class="app-message-input" id='chatForm'>
      <textarea id="insertChat" class="form-control" name='text' rows="1"></textarea>
      <div class="message-input-actions btn-group">
        <div id="showEmoji"></div>
        <button id="sendChat" class="message-input-btn btn btn-primary" type="button"><?= _lang("SEND");?></button>
      </div>
    </form>
    <!-- End Message Input-->
  </div>

  <div id="emptyDialog">
    
    <div class="row text-center" style="margin-top:11%;">
      <?=_lang("Start a conversation by clicking 'Message' on that channel")?>
    </div>
    <div class="row text-center mtop20">
      <a class="btn btn-primary" href="<?=site_url().members?>">
        <?=_lang("Browse channels")?>
      </a>
    </div>

  </div>

</div>

<script>
$(document).on('ready', function() {

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

        $('.chat-top-panel > h3').text(chatName);
        $('.chat-top-panel > img').prop('src', chatImage);

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

        $('.chat-top-panel > h3').text(chatName);
        $('.chat-top-panel > img').prop('src', chatImage);
        
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

      $('.chat-top-panel > h3').text(chatName);
      $('.chat-top-panel > img').prop('src', chatImage);

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

            $('.chat-top-panel > h3').text(chatName);
            $('.chat-top-panel > img').prop('src', chatImage);

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

            $('.chat-top-panel > h3').text(chatName);
            $('.chat-top-panel > img').prop('src', chatImage);
            
          }
        }
      });
    }
  })

})
</script>