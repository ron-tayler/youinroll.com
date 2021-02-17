<?php include_once('../../load.php'); do_action('pre-video');?>

<?
$streamId = token_id();

$streamInfo = $db->get_row("SELECT id,name,cover,description,category,likes,views,moderator_id FROM ".DB_PREFIX."conferences where id = '".$streamId."' AND type = 'stream' limit  0,1");

if($streamInfo !== null)
{
    $streamInfo->categoryName = $db->get_row('SELECT cat_name FROM '.DB_PREFIX.'channels WHERE cat_id = '.toDb($streamInfo->category).'LIMIT(0,1)');
    $userInfo = $db->get_row("SELECT id,avatar,name,onAir FROM ".DB_PREFIX."users where id = ".toDb((int)$streamInfo->moderator_id)." limit  0,1");
    $userInfo->isAuthor = ((int)$streamInfo->moderator_id === user_id());

    if($userInfo->isAuthor)
    {
        $db->query('UPDATE '.DB_PREFIX.'conferences SET on_air = true WHERE id = '.toDb($streamId));
    }
}
?>

<div class="stream-holder row">
    <div id="renderPlaylist">
        <div class="col-xs-12">
            <div id="stream-content" class="col-xs-12">
                <div class="video-player pull-left" id="stream" data-stream="<?=$streamId?>">

                    <div class="clearfix"></div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    
    <div class="stream-under col-xs-12">
        <div class="oboxed odet mtop10">
            <div class="stream-header">
                <h1><?=$streamInfo->name?></h1>
                <div class='stream-header-end'>
                    <?=((int)$userInfo->id === user_id()) ? '' : subscribe_box($streamInfo->moderator_id, '', false, 'modal'); ?>
                    <?php subscribe_box($streamInfo->moderator_id, 'btn btn-primary tipS mleft10 mright10', false, 'follow'); ?>
                    <button class="btn btn-secondary2"><i class="icon-threedot"></i></button>
                </div>
            </div>
            <div class="row vibe-interactions">
                <div class="streamer-row">
                    <div class='streamerStatus <?=((int)$userInfo->onAir !== 1) ? '' : 'online'?>'>
                        <img id="streamerImage" src="<?=$userInfo->avatar?>" width="40" height="40" />
                    </div>
                    <div class='streamerData'>
                        <h1 id='streamerName'><?=$userInfo->name?></h1>
                        <div class="user-media-actions">
                            <div class="like-views">
                                <img src="/tpl/main/images/eye.svg" class="eye" /><?=number_format($stream->views)?>
							</div>
                            <div class="interaction-icons">
                                <div class="likes-bar">
                                    <?php if($is_liked) { ?>
                                    <div class="aaa">
                                        <a href="javascript:RemoveLike(<?php echo $streamInfo->id;?>)" id="i-like-it"
                                            class="isLiked pv_tip likes" title=" <?php echo _lang('Remove from liked');?>">
                                            <!-- <i class="material-icons">&#xE8DC;</i> -->
                                            <img src="/tpl/main/images/like.svg" class="like" />
                                            <span><?php echo number_format($streamInfo->liked);?></span>
                                        </a>
                                    </div>
                                    <?php } else { ?>
                                    <div class="aaa">
                                        <a href="javascript:iLikeThis(<?php echo $streamInfo->id;?>)" id="i-like-it"
                                            class="pv_tip likes" title=" <?php echo _lang('Like');?>">
                                            <!-- <i class="material-icons">&#xE8DC;</i> -->
                                            <img src="/tpl/main/images/like.svg" class="like" />
                                            <span><?php echo number_format($streamInfo->liked);?></span>
                                        </a>
                                    </div>
                                    <?php } ?>
                                    <div class="aaa ">
                                        <a href="javascript:iHateThis(<?php echo $streamInfo->id;?>)" id="i-dislike-it"
                                            class="pv_tip dislikes <?php if($is_disliked) { echo 'isLiked'; }?>"
                                            data-toggle="tooltip" data-placement="top" title=" <?php echo _lang('Dislike');?>">
                                            <!-- <i class="material-icons">&#xE8DB;</i> -->
                                            <img src="/tpl/main/images/dislike.svg" class="dislike" />
                                            <span> <?php echo number_format($streamInfo->disliked); ?></span>
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
                                    <a id="social-share" href="javascript:void(0)"
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
                                <?php if (is_user()) { ?>
                                <?php if ((int)user_id() === (int)$streamInfo->moderator_id) { ?>
                                <div class="aaa">
                                    <a href="<?=site_url().lessonsettings.'/'.$streamInfo->id?>">
                                        <span><?php echo _lang("Настройки урока");?></span>
                                    </a>
                                </div>
                                <?php } ?>
                                <?php } ?>
                                <div class="aaa">
                                    <a class="tipS" title=" <?php echo _lang('Report');?>" data-target="#report-it"
                                        data-toggle="modal" href="javascript:void(0)"
                                        title=" <?php echo _lang('Report media');?>">
                                        <!-- <i class="material-icons">&#xE153;</i> -->
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="clearfix"></div>
        </div>

        <div class="user-container full top20">

        </div>

        <div class="sharing-icos mtop10 odet hide">
            <?php do_action('before-social-box'); ?>
            <div class="has-shadow">
                <div class="text-center text-uppercase full bottom10 top20">
                    <h4><?php  echo _lang('Let your friends enjoy it also!');?></h4>
                </div>
                <div id="jsshare" data-url="<?php echo $canonical; ?>"
                    data-title="<?php echo _cut($streaminfo->name, 40); ?>"></div>
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
                            <input type="text" name="link-to-this" id="share-this-link" class="form-control"
                                title="<?php echo _lang('Link back');?>" value="<?php echo canonical();?>" />
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
                                    <textarea style="min-height:80px" id="share-embed-code-small" name="embed-this"
                                        class="form-control"
                                        title=" <?php echo _lang('Embed this media on your page');?>"><iframe width="853" height="480" src="<?php echo site_url().embedcode.'/'._mHash($video->id).'/';?>" frameborder="0" allowfullscreen></iframe></textarea>
                                    <label class="floating-label"> <?php  echo _lang('Embed code');?></label>
                                    <div class="radio-custom radio-primary"><input type="radio" name="changeEmbed"
                                            class="styled" value="1"><label>1920x1080</label></div>
                                    <div class="radio-custom radio-primary"><input type="radio" name="changeEmbed"
                                            class="styled" value="2"><label>1280x720</label></div>
                                    <div class="radio-custom radio-primary"><input type="radio" name="changeEmbed"
                                            class="styled" value="3"><label>854x480</label></div>
                                    <div class="radio-custom radio-primary"><input type="radio" name="changeEmbed"
                                            class="styled" value="4"><label>640x360</label></div>
                                    <div class="radio-custom radio-primary"><input type="radio" name="changeEmbed"
                                            class="styled" value="5"><label>426x240</label></div>
                                </div>
                                <div class="col-md-4 col-md-offset-1">
                                    <div class="well">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i
                                                        class="material-icons">&#xE86F;</i></span>
                                                <input type="number" class="form-control" name="CustomWidth"
                                                    id="CustomWidth" placeholder="<?php echo _lang("Custom width");?>">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i
                                                        class="material-icons">&#xE883;</i></span>
                                                <input type="number" name="CustomHeight" id="CustomHeight"
                                                    class="form-control"
                                                    placeholder="<?php echo _lang("Custom height");?>">
                                            </div>
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
                            <input type="text" name="link-to-this" id="share-this-responsive" class="form-control"
                                title=" <?php echo _lang('Make the iframe responsive on your website');?>"
                                value='<script src="<?php  echo site_url();?>lib/vid.js"></script>' />
                            <label class="floating-label">
                                <?php  echo _lang('Responsive embed');?>
                            </label>
                        </div>
                        <span class="help-block">
                            <?php  echo _lang('Include this script into your page along with the iframe for a'); ?>
                            <code><?php  echo _lang('responsive media embed');?></code>
                            <span>
                    </div>
                </div>
            </div>
        </div>
        <?php do_action('before-description-box'); ?>
        <div class="oboxed odet">
            <ul id="media-info" class="list-unstyled">
                <li>
                    <div id="media-description" data-small="<?php echo _lang("show more");?>"
                        data-big=" <?php echo _lang("show less");?>">
                        <?php echo makeLn(_html($streamInfo->description));?>
                        <p style="font-weight:500; color:#333">
                            <?= _lang("Category :") . $streamInfo->categoryName->cat_name?> <a
                                href="<?php echo channel_url($streamInfo->category,$streamInfo->channel_name);?>"
                                title="<?php echo _html($streamInfo->channel_name);?>">
                                <?php echo _html($streamInfo->channel_name);?>
                            </a>
                        </p>
                        <?php if($streamInfo->tags) { ?>
                        <p> <?php echo pretty_tags($streamInfo->tags,'right20','#','');?></p>
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
</div>
<?php do_action('post-video'); ?>
<!-- Start Report Sidebar -->
<div class="modal fade" id="report-it" aria-hidden="true" aria-labelledby="report-it" role="dialog" tabindex="-1">
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
                                                            <?php  echo _lang('Media is restricted');?>"
                                class="styled">
                            <label>
                                <?php echo _lang('Video is restricted');?>
                            </label>
                        </div>
                        <div class="checkbox-custom checkbox-primary">
                            <input type="checkbox" name="rep[]" value="
                                                                <?php  echo _lang('Copyrighted material');?>"
                                class="styled">
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
</div>
</div>