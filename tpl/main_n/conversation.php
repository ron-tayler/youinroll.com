<? $cid = $globalTemplateVariable;?>

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
        <div>
          <img width='50' height='50' src='' title=''>
          <h3></h3>
        </div>
        <i id='chatSettings' class='material-icons'>&#xe8b8;</i>
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
