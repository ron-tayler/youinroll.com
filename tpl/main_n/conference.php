<?php include_once('../../load.php'); the_sidebar(); do_action('pre-video');?>
<div class="video-holder row">
<?php if(has_list()){  ?>
<div id="renderPlaylist">
<?php } ?>
    <?php
    if(is_user() && $video->name !== null)
    {
        $userModel = $cachedb->get_results("SELECT id,group_id,name,email,avatar,type,pass from ".DB_PREFIX."users where id = '".user_id()."'");
                    
        $userEmail = $userModel[0]->email;
        $userName = translit($userModel[0]->name);
        $userAvatar = thumb_fix($userModel[0]->avatar, true, 250, 250);
        $userRole = 'member';

        switch($userModel[0]->group_id) {
            case 1:
                $userRole = 'admin';
                break;
            case 4:
                $userRole = 'member';
                break;
            case 6:
                $userRole = 'premium';
                break;
            case 8:
                $userRole = 'parent';
                break;
            case 10:
                $userRole = 'student';
                break;
            case 11:
                $userRole = 'school';
                break;
            case 12:
                $userRole = 'teacher';
                break;
            default:
                $userRole = 'student';
                break;
        }

        if($userRole === 'student')
        {  
            $raw = "SELECT * FROM ".DB_PREFIX."conference_participants WHERE conference_id = '$video->id' AND user_id = '".$userModel[0]->id."'";

            $allowedMember = $db->get_results($raw);

            if($allowedMember === null)
            {
                layout('404');
                die();
            }
        }
    ?>
    <div class="<?php if(!has_list()){ echo "col-md-8 col-xs-12";} else {echo "row block player-in-list";}?> ">
        <div id="video-content" class="<?php if(has_list()){ echo "col-md-8 col-xs-12";} else {echo "row block";}?>">
            <div class="video-player pull-left" id="meet" data-umail="<?=$userEmail?>">
                <?php         
                    echo("
                        <script>
                            window.options = runConf('#meet', '$video->name', '$userEmail', '$userName', '$userAvatar', '$userRole');
                        </script>
                    ");
                ?>
                <div class="clearfix"></div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <!-- <script src="https://tinode.video-library-service.com/umd/tinode.prod.js"></script>
    <script src="test.js"></script> -->
    <?php }?>

    <?php if(has_list()){  ?>
    <div id="LH" class="row nomargin">
        <div class="playlistvibe">
            <div class="cute">
                <h1>
				<?php $listest = decList(_get('list'));
				/* Test if list can be sorted */
                if(intval($listest) > 0) { 
				$thesort = not_empty(_get('sorter')) ? _get('sorter') : 'no' ;
				$thesorts = array(
				'no' => _lang("Default order"),
				'vd' => _lang("Added descending"),
				'va' => _lang("Added ascending"),
				'td' => _lang("Title descending"),
				'ta' => _lang("Title ascending"),
				'wd' => _lang("Views descending"),
				'wa' => _lang("Views ascending"),				
				);
				if (isset($thesorts[$thesort])) {
				?>
				<div class="playlist-order"> 
				<a class="dropdown-toggle tipS" title="<?php echo $thesorts[$thesort];  ?>" data-toggle="dropdown" href="#" aria-expanded="false" data-animation="scale-up" role="button">
				<i class="icon icon-calendar"></i>			
				</a>
				<ul class="dropdown-menu dropdown-left bullet" role="menu">
				<?php 
				foreach ($thesorts as $key => $value) {
				echo '<li><a href="'.video_url($video->id , $video->title, hashList($listest)).'&sorter='.$key.'">';
                if($key !== $thesort) {
				echo '<i class="icon material-icons">&#xE836;</i> ';
				} else {
				echo '<i class="icon material-icons">&#xE837;</i> ';	
				}
				echo $value.'</a></li>';	
					
				}
				echo '</ul>';
				} ?>
				</div>
				<?php } ?>				
				
                    <?php  echo _html('Now playing:'); ?>
                    <span>
                        <?php  echo _html(_cut(list_title(_get('list')),260));?>
                    </span>
                </h1>			
				
		                <div class="cute-line"></div>
            </div>
            <div class="next-an list-next">
                <a class="fullit tipS" href="javascript:void(0)" title="<?php  echo _html('Resize player');?>">
                    <i id="flT" class="material-icons">&#xE85B;</i>
                </a>
                <a id="ComingNext" href="" class="tipS" title="">
                   <i class="material-icons">&#xE5C8;</i>
                </a>
                <a class="tipS" title="<?php  echo _html('Stop playlist');?>" href="<?php  echo $canonical;?>">
                <i class="material-icons">&#xE047;</i></a>
            </div>
        </div>
    </div>
	</div>
    <?php } ?>
    <div class="rur video-under-right oboxed <?php if(has_list()){ echo "mtop10";}?> pull-right col-md-4 col-xs-12">
        <?php do_action('before-related');  echo _ad('0','related-videos-top');?>
            <div class="related video-related top10 related-with-list">
                
                    <?php 
					if(get_option("ajaxyRel" , 1) == 1) {
                    echo '<div class="ajaxreqRelated" data-url="relatedvids?videoowner&videoid='.$video->id.'&videomedia='.$video->media.'&videocategory='.$video->category.'">
					 <div class="cp-spinner cp-flip"></div>  
					  </div>
					';
					} else {	
					echo '<ul>';
					layout('layouts/related'); 
					echo '</ul>';
					}
?>
                
            </div>
            <?php do_action('after-related'); ?>
        </div>
        <div class="video-under col-md-8 col-xs-12">
            <div class="oboxed odet mtop10">
                <div class="row vibe-interactions">
                    <?php do_action('before-video-title'); ?>
                    <h1>
                        <?php echo _html($video->title);?>
                    </h1>
                    <?php do_action('after-video-title'); ?>
                    <div class="user-media-actions">
					<div class="interaction-icons">
                        <div class="likes-bar">                          
                            <?php if($is_liked) { ?>
                            <div class="aaa">
                                <a href="javascript:RemoveLike(<?php echo $video->id;?>)" id="i-like-it" class="isLiked pv_tip likes" title=" <?php echo _lang('Remove from liked');?>">
                                    <!-- <i class="material-icons">&#xE8DC;</i> -->
                                    <img src="/tpl/main/images/like.svg" class="like" />
                                    <span><?php echo number_format($video->liked);?></span>
                                </a>
                            </div>
                            <?php } else { ?>
                            <div class="aaa">
                                <a href="javascript:iLikeThis(<?php echo $video->id;?>)" id="i-like-it" class="pv_tip likes" title=" <?php echo _lang('Like');?>">
                                    <!-- <i class="material-icons">&#xE8DC;</i> -->
                                    <img src="/tpl/main/images/like.svg" class="like" />									
                                    <span><?php echo number_format($video->liked);?></span>
                                </a>
                            </div>
                            <?php } ?>
							  <div class="aaa ">
                                <a href="javascript:iHateThis(<?php echo $video->id;?>)" id="i-dislike-it" class="pv_tip dislikes <?php if($is_disliked) { echo 'isLiked'; }?>" data-toggle="tooltip" data-placement="top" title=" <?php echo _lang('Dislike');?>">
                                    <!-- <i class="material-icons">&#xE8DB;</i> -->
                                    <img src="/tpl/main/images/dislike.svg" class="dislike" />
                                   <span> <?php echo number_format($video->disliked); ?></span>
                                </a>
                            </div>
                            <div class="like-box">
                                
                                <div class="like-progress">
                                    <div class="likes-success" style="width: 
                                        <?php echo $likes_percent;?>%;">
                                    </div>
                                    <div class="likes-danger second" style="width: 
                                        <?php echo $dislikes_percent;?>%;">
                                    </div>
                                </div>
                            </div>
						   </div>               						  
                            
                            <div class="aaa">
                                <a id="social-share" href="javascript:void(0)"  title=" <?php echo _lang('Share or Embed');?>">
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
                            <?php if (is_user()) { ?>
                                <?php if ((int)user_id() === (int)$video->moderator_id) { ?>
                                <div class="aaa">
                                    <a href="<?=site_url().lessonsettings.'/'.$video->id?>">
                                        <span><?php echo _lang("Настройки урока");?></span>
                                    </a>
                                </div>
                                <?php } ?>
                            <?php } ?>
                            <div class="aaa">
                                <a class="tipS" title=" <?php echo _lang('Report');?>" data-target="#report-it" data-toggle="modal" href="javascript:void(0)"  title=" <?php echo _lang('Report media');?>">
                                   <!-- <i class="material-icons">&#xE153;</i> -->
                                </a>
                            </div>
							</div>

                            <div class="like-views">
                            <?php 
                            echo '<img src="/tpl/main/images/eye.svg" class="eye" /> '.number_format($video->views);
                            //echo number_format($video->views);
                            //$views_correct_text = str_replace('число ', '', _lang('views')); echo $views_correct_text; 
                            ?>
                            </div>
							</div>
                        
                    </div>
                    <div class="clearfix"></div>
                </div>
           
                <div class="user-container full top20">
                    <div class="pull-left user-box" style="">
                        <?php echo '
                        <a class="userav" href="'.profile_url($video->moderator_id,$video->owner).'" title="'.addslashes($video->owner).'">
                            <img src="'.thumb_fix($video->avatar, true, 60,50).'" data-name="'.addslashes($video->owner).'"/>
                        </a>
						<div class="user-box-txt">
						<a class="" href="'.profile_url($video->moderator_id,$video->owner).'" title="'.addslashes($video->owner).'">
                                <h3>'.$video->owner.'</h3>
                            </a> '.group_creative($video->group_id);
                            $time_ago = time_ago($video->created_at);
                            $time_ago = str_replace('2 неделя', '2 недели', $time_ago);
                            $time_ago = str_replace('3 неделя', '3 недели', $time_ago);
                            $time_ago = str_replace('месяцс', 'месяц', $time_ago);
                            $time_ago = str_replace('2 месяц ', '2 месяца ', $time_ago);
                            $time_ago = str_replace('3 месяц ', '3 месяца ', $time_ago);
                            $time_ago = str_replace('4 месяц ', '4 месяца ', $time_ago);
                            $time_ago = str_replace('5 месяц ', '5 месяцев ', $time_ago);
                            $time_ago = str_replace('6 месяц ', '6 месяцев ', $time_ago);
                            $time_ago = str_replace('7 месяц ', '7 месяцев ', $time_ago);
                            $time_ago = str_replace('8 месяц ', '8 месяцев ', $time_ago);
                            $time_ago = str_replace('9 месяц ', '9 месяцев ', $time_ago);
                            $time_ago = str_replace('10 месяц ', '10 месяцев ', $time_ago);
                            $time_ago = str_replace('11 месяц ', '11 месяцев ', $time_ago); 
                            $time_ago = str_replace('годс', 'год', $time_ago);
                            $time_ago = str_replace('2 год ', '2 года ', $time_ago);
                            $time_ago = str_replace('3 год ', '3 года ', $time_ago);
                            $time_ago = str_replace('4 год ', '4 года ', $time_ago);
                            $time_ago = str_replace('5 год ', '5 лет ', $time_ago);
                            $time_ago = str_replace('6 год ', '6 лет ', $time_ago);
                            $time_ago = str_replace('7 год ', '7 лет ', $time_ago);
                            $time_ago = str_replace('8 год ', '8 лет ', $time_ago);
                            $time_ago = str_replace('9 год ', '9 лет ', $time_ago);
                            $time_ago = str_replace('10 год ', '11 лет ', $time_ago);
                            $time_ago = str_replace('тому ', '', $time_ago);
							echo '<p>  '.$time_ago.'<br>'
                                .u_k(get_subscribers($video->moderator_id)).' подписчиков</p>
						</div>';


                        ?>

						
                        <div class="pull-right"><?php subscribe_box($video->moderator_id);?></div>
                    </div>					
                    <div style="clear:both"></div>
                </div>
            
                <div class="sharing-icos mtop10 odet hide">
				<?php do_action('before-social-box'); ?>
			<div class="has-shadow">
            <div class="text-center text-uppercase full bottom10 top20">
			<h4><?php  echo _lang('Let your friends enjoy it also!');?></h4>
			</div>			
             <div id ="jsshare" data-url="<?php echo $canonical; ?>" data-title="<?php echo _cut($video->title, 40); ?>"></div>                            
            </div>
			<div class="video-share mtop10 has-shadow right20 left20 clearfix">
			<div class="text-center text-uppercase full bottom20 top20">
			<h4><?php  echo _lang('Add it to your website');?></h4>
			</div>
                <div class="form-group form-material floating">
                    <div class="input-group">
                        <span class="input-group-addon">
                           <i class="material-icons">&#xE157;</i>
                        </span>
                        <div class="form-control-wrap">
                            <input type="text" name="link-to-this" id="share-this-link" class="form-control" title="<?php echo _lang('Link back');?>" value="<?php echo canonical();?>" />
                                <label class="floating-label">
                                    <?php  echo _lang('Link to this');?>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-material floating">
                        <div class="input-group">
                            <span class="input-group-addon">
                               <i class="material-icons">&#xE911;</i>
                            </span>
                            <div class="form-control-wrap">
							<div class="row">
							
							<div class="col-md-7">
<textarea style="min-height:80px" id="share-embed-code-small" name="embed-this" class="form-control" title=" <?php echo _lang('Embed this media on your page');?>"><iframe width="853" height="480" src="<?php echo site_url().embedcode.'/'._mHash($video->id).'/';?>" frameborder="0" allowfullscreen></iframe></textarea>
 <label class="floating-label"> <?php  echo _lang('Embed code');?></label>
     <div class="radio-custom radio-primary"><input type="radio" name="changeEmbed" class="styled" value="1"><label>1920x1080</label></div>
	<div class="radio-custom radio-primary"><input type="radio" name="changeEmbed" class="styled" value="2"><label>1280x720</label></div>	
	<div class="radio-custom radio-primary"><input type="radio" name="changeEmbed" class="styled" value="3"><label>854x480</label></div>	
	<div class="radio-custom radio-primary"><input type="radio" name="changeEmbed" class="styled" value="4"><label>640x360</label></div>	
	<div class="radio-custom radio-primary"><input type="radio" name="changeEmbed" class="styled" value="5"><label>426x240</label></div>
					  </div>
							<div class="col-md-4 col-md-offset-1">
  	<div class="well">
				<div class="form-group">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="material-icons">&#xE86F;</i></span>
                    <input type="number" class="form-control" name="CustomWidth" id="CustomWidth" placeholder="<?php echo _lang("Custom width");?>">
                  </div>
                </div>
				<div class="form-group">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="material-icons">&#xE883;</i></span>
                    <input type="number" name="CustomHeight" id="CustomHeight" class="form-control" placeholder="<?php echo _lang("Custom height");?>"> </div>
                </div>
				</div>
					</div>
							</div>
							</div>
                        </div>
                    </div>
					<div class="form-group form-material floating">
                    <div class="input-group">
                        <span class="input-group-addon">
                           <i class="material-icons">&#xE1B1;</i>
                        </span>
                        <div class="form-control-wrap">
                            <input type="text" name="link-to-this" id="share-this-responsive" class="form-control" title=" <?php echo _lang('Make the iframe responsive on your website');?>" value='<script src="<?php  echo site_url();?>lib/vid.js"></script>' />
                                <label class="floating-label">
                                    <?php  echo _lang('Responsive embed');?>
                                </label>
                            </div>
							<span class="help-block">
							<?php  echo _lang('Include this script into your page along with the iframe for a'); ?> <code><?php  echo _lang('responsive media embed');?></code>
                        <span>
						</div>
                    </div>
                </div>
                </div>
                <?php do_action('before-description-box'); ?>
                <div class="oboxed odet">
                    <ul id="media-info" class="list-unstyled">
                    <!--
                        <li>                                                    
 <div class="fb-like pull-left" data-href="<?php /*echo $canonical;*/ ?>" data-width="124" data-layout="standard" data-colorscheme="dark" data-action="like" data-show-faces="true" data-share="true"></div>
                          </li>
                    -->
						  <li>
						  <div id ="media-description" data-small="<?php echo _lang("show more");?>" data-big=" <?php echo _lang("show less");?>">
                            <?php echo makeLn(_html($video->description));?>
							<p style="font-weight:500; color:#333">
							 <?php echo _lang("Category :");?> <a href="<?php echo channel_url($video->category,$video->channel_name);?>" title="<?php echo _html($video->channel_name);?>">
                                <?php echo _html($video->channel_name);?>
                            </a>
							</p>
							<?php if($video->tags) { ?>
							<p> <?php echo pretty_tags($video->tags,'right20','#','');?></p>
							<?php } ?>
                            </div>
                        </li>                      
                    </ul>
                 
                    <?php do_action('after-description-box'); ?>
                </div>
                <div class="clearfix"></div>
                <div class="oboxed related-mobi mtop10 visible-sm visible-xs hidden-md hidden-lg">
                    <a id="revealRelated" href="javascript:void(0)">
                        <span class="show_more text-uppercase">
                            <?php echo _lang("show more");?>
                        </span>
                        <span class="show_more text-uppercase hide">
                            <?php echo _lang("show less");?>
                        </span>
                    </a>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
        <script>

        $(document).ready(function(){
            DOtrackview(<?php echo $video->id; ?>);
            
            if($('iframe').attr('id') != 'ytplayer')
            {
                window.onbeforeunload=function(){
                    return "Вы действительно хотите покинуть урок?";
                }
            }

        });

        </script>
    </div>
    <?php do_action('post-video'); ?>
    <!-- Start Report Sidebar -->
    <div class="modal fade" id="report-it" aria-hidden="true" aria-labelledby="report-it"
                        role="dialog" tabindex="-1">
        <div class="modal-dialog modal-sidebar modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title">
                        <?php  echo _lang('Report video');?>
                    </h4>
                </div>
                <div class="modal-body">
                    <?php if(!is_user()){?>
                    <p>
                        <?php  echo _lang('Please login in order to report media.');?>
                    </p>
                    <?php } elseif(is_user()){?>
                    <div class="ajax-form-result"></div>
                    <form class="horizontal-form ajax-form" action="
                        <?php echo site_url().'lib/ajax/report.php';?>" enctype="multipart/form-data" method="post">
                        <input type="hidden" name="id" value="
                            <?php  echo $video->id;?>" />
                            <input type="hidden" name="token" value="
                                <?php  echo $_SESSION['token'];?>" />
                                <div class="control-group" style="border-top: 1px solid #fff;">
                                    <label class="control-label">
                                        <?php  echo _lang('Reason for reporting');?>: 
                                    </div>
                                    <div class="controls">
                                        <div class="checkbox-custom checkbox-primary">
                                            <input type="checkbox" name="rep[]" value="
                                                <?php echo _lang('Media not playing');?>" class="checkbox-custom">
                                                <label>
                                                    <?php echo _lang('Video not playing');?>
                                                </label>
                                            </div>
                                            <div class="checkbox-custom checkbox-primary">
                                                <input type="checkbox" name="rep[]" value="
                                                    <?php  echo _lang('Wrong title/description');?>" class="styled">
                                                    <label>
                                                        <?php  echo _lang('Wrong title/description');?>
                                                    </label>
                                                </div>
                                                <div class="checkbox-custom checkbox-primary">
                                                    <input type="checkbox" name="rep[]" value="
                                                        <?php  echo _lang('Media is offensive');?>" class="styled">
                                                        <label>
                                                            <?php echo _lang('Video is offensive');?>
                                                        </label>
                                                    </div>
                                                    <div class="checkbox-custom checkbox-primary">
                                                        <input type="checkbox" name="rep[]" value="
                                                            <?php  echo _lang('Media is restricted');?>" class="styled">
                                                            <label>
                                                                <?php echo _lang('Video is restricted');?>
                                                            </label>
                                                        </div>
                                                        <div class="checkbox-custom checkbox-primary">
                                                            <input type="checkbox" name="rep[]" value="
                                                                <?php  echo _lang('Copyrighted material');?>" class="styled">
                                                                <label>
                                                                    <?php  echo _lang('Copyrighted material');?>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <textarea rows="5" cols="3" name="report-text" class="form-control" required></textarea>
                                                            <p>
                                                                <strong>
                                                                    <?php  echo _lang('Required'); ?>
                                                                </strong> :
                                                                <?php  echo _lang('Tell us what is wrong with the video in a few words');?>
                                                            </p>
                                                            <div class="row mtop20 bottom10">
                                                                <button class="btn btn-primary btn-block" type="submit">
                                                                    <?php  echo _lang('Send report');?>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                    <?php } ?>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default btn-block" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Modal -->
                                </div>
                            </div>
                            <!-- End Report Sidebar -->
