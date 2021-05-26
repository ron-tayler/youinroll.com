<?php include_once('../../load.php'); do_action('pre-video');?>

<script>
// When the user clicks on <div>, open the popup
function isHidden(el) {
    return (el.offsetParent === null)
};

function myFunction() {
	var popup = document.getElementById("report-author");
	if ((window.getComputedStyle(popup).visibility === "hidden")){

  document.getElementById("report-author").style.visibility = "inherit";
  document.getElementById("stream-content").style.display = "none";
	}else{

  document.getElementById("report-author").style.visibility = "hidden";
  document.getElementById("stream-content").style.display = "";
	}
}
</script>




<style>

@media only screen and (min-width: 601px) {
.inter-icons {
   display: none;
}
}


.sidebar-mobile .bottom-nav {
    display: none;
}


#report-author {
 z-index: 99999;
visibility: hidden;
}

@media only screen and (max-width: 600px) {

.stream-header {
	display: none;
}

.phone-stream {
    position: sticky;
   top: 0px;
   z-index: 2040;
   background-color: white;
}

.chat-area-main {
	padding-bottom: 50px;
}

.chat-area-footer {
	position: fixed;
}

.notifyjs-corner {
	display: none;
}

}

</style>


<?
$streamId = token_id();

$streamInfo = $globalTemplateVariable;

if($streamInfo !== null)
{
    $streamInfo->categoryName = $db->get_col('SELECT cat_name FROM '.DB_PREFIX.'channels WHERE cat_id = '.toDb($streamInfo->category).' LIMIT 0,1');
    
    $userInfo = $db->get_row("SELECT id,avatar,name FROM ".DB_PREFIX."users where id = ".toDb((int)$streamInfo->moderator_id)." limit  0,1");
    $userInfo->isAuthor = ((int)$streamInfo->moderator_id === user_id());

    if($userInfo->isAuthor)
    {
        $db->query('UPDATE '.DB_PREFIX.'conferences SET on_air = true WHERE id = '.toDb($streamId));
    }
}

?>

<div class="stream-holder row">
    <div class="stream-row" id="#stremrowwindow">
        <div id="renderPlaylist">
            <div class="stream-under col-xs-12">
                <div class="mtop10" style="padding-left:20px;">
                    <div class="row vibe-interactions">
                        <div class="streamer-row">
                            <div class='streamerStatus'>
                                <img id="streamerImage" src="<?=$userInfo->avatar?>" width="40" height="40" />
                            </div>
                            <div class='streamerData'>
                                <h1 id='streamerName' style="margin:0px;"><?=$userInfo->name?></h1>
                                <p style="font-weight:500; color:#333">
                                    <span style="font-weight:bold;color:black;font-size:18px"><?= $streamInfo->name?></span>                                     
                                        <span style="color:red; font-size:20px">&#8226;</span>

                                        <span class="badge badge-secondary" style="color:#9147ff;">                                         
                                        
                                    <?= $streamInfo->categoryName[0]?></span> <a
                                        href="<?php echo channel_url($streamInfo->category,$streamInfo->channel_name);?>"
                                        title="<?php echo _html($streamInfo->channel_name);?>">
                                        <?php echo _html($streamInfo->channel_name);?>
                                    </a>
                                </p>
                            </div>
			    <div>
				<h1>
					<button onclick="myFunction()"  class="btn btn-secondary2 legitRipple">
						<i class="icon-threedot"></i>
					</button>

						<div id="report-author">
						    <?php if (is_user()) { ?>
						    <?php if ((int)user_id() === (int)$streamInfo->moderator_id) { ?>
								    <div class="likes-bar">
							<div class="aaa">
							     <a role="button" class="btn btn-danger" style="padding: 10px" data-toggle="modal" id="closeStream">
								 <span style="text-align:center;"><?php echo _lang("Завершить урок");?></span>
							     </a>
							 </div>
						       </div>
						    <?php } else { ?>
							<div class="aaa">
							    <a class="tipS" title=" <?php echo _lang('Report');?>" data-target="#report-it"
								data-toggle="modal" href="javascript:void(0)"
								title=" <?php echo _lang('Report media');?>">
								<i class="material-icons">&#xE153;</i>
							    </a>
							</div>
						    <?php } ?>
						    <?php } ?>
						</div>
				</h1>
                            </div>
                        </div>
                    </div>
                   
                </div>

                <? if( (int)user_id() === (int)$streamInfo->moderator_id ) {?>
                    <?include(TPL.'/modals/streamsettings.php');?>
                <? } ?>

                <?include(TPL.'/modals/sharemodal.php');?>
                
            </div>


            <div class="col-xs-12">
                <div id="stream-content" class="col-xs-12 phone-stream">
                    <div class='stream-main'>
<?if( true /* !in_array(user_group(),[4,5,3,8],true)*/ && $streamInfo->on_air === '1' )
                    {?>
                        <div class="video-player pull-left" id="stream" data-stream="<?=$streamId?>">                    
                        </div>
                    <?} else {?>
                        <?if($streamInfo->on_air !== '1') {?>
                        <div class="video-player pull-left">
                            <div class="vprocessing" style="background-image: url('<?=$streamInfo->cover?>'); background-size: contain; background-repeat: no-repeat;">
                                <div class="blured-block">
                                    <div class="vpre">Урок завершён</div> 
                                </div>
                            </div>
                        </div>
                        <?} else {?>
                        <div class="video-player pull-left">
                            <div class="vprocessing" style="background-image: url('<?=$streamInfo->cover?>'); background-size: contain; background-repeat: no-repeat;">
                                <div class="blured-block">
                                    <div class="vpre">Вы не являетесь учеником или преподователем</div> 
                                </div>
                            </div>
                        </div>
                        <?}?>
                    <?}?>
                        <div id="stream-header" class="stream-header">
                            
                            <div class="user-media-actions">

                                <div class="interaction-icons">

                                    <div class="likes-bar">
                                        <div class="aaa">
                                            <a class="pv_tip views">
                                                <img src="/tpl/main/images/eye.svg" class="eye" />
                                                <span><?=number_format($stream->views)?></span>
                                            </a>
                                        </div>
                                    </div>

                                    <div class="likes-bar">
                                        <div class="aaa">
                                            <a id="i-like-it" data-id="<?=$streamInfo->id?>" class="pv_tip likes"
                                                title=" <?php echo _lang('Like');?>">
                                                <!-- <i class="material-icons">&#xE8DC;</i> -->
                                                <img src="/tpl/main/images/like.svg" class="like" />
                                                <span><?php echo number_format($streamInfo->liked);?></span>
                                            </a>
                                        </div>
                                    </div>

                                    <div class="likes-bar">
    				       <div class="aaa">
                                            <a role="button" data-toggle="modal" href="#ShareModal"
                                                title=" <?php echo _lang('Share or Embed');?>">
                                                <!-- <i class="material-icons ico-flipped">&#xE15E;</i> -->
                                                <img src="/tpl/main/images/share.svg" class="share" />
                                                <span class="hidden-xs">
                                                    <?php 
                                                        //echo _lang('Share');
                                                        echo 'Поделиться';
                                                        ?>
                                                </span>
                                            </a>
		                       </div>
                                    </div>

                                </div>






                            </div>
                        </div>

                    </div>
                    <?
                        $chatTitle = $streamInfo->name;
                        $chatImage = $streamInfo->cover;
                    ?>
                    <!-- user_group() !== 4 && user_group() !== 5 && user_group() !== 3 && user_group() !== 8 -->
                    <?if(true)
                    {
                        include(TPL.'/widgets/microchat.php');
                    }
                    ?>
                    
                </div>
                <div class="mobile-chat" style="width:100%;">
                </div>
            </div>
        </div>
    </div>

    
</div>
</div>
<?php do_action('post-video'); ?>
<!-- Start Report Sidebar -->

<?$entity = $streamInfo; $entity->type = 'stream';?>
<? include(TPL.'/modals/report.php'); ?>
</div>
</div>




			<script>
		window.addEventListener("beforeunload", function(event) {
  event.returnValue = "Write something clever here..";
});
var promise = document.querySelector('vjs-tech').play();

if (promise !== undefined) {
    promise.catch(error => {
        console.log("error")
        // Auto-play was prevented
        // Show a UI element to let the user manually start playback
    }).then(() => {
     console.log("started") 
    });
}




			</script>
