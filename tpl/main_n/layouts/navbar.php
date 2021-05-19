<div class="fixed-top">
    <div class="row block" style="position:relative;">
        <div class="logo-wrapper">
            <a id="show-sidebar" href="javascript:void(0)" title="<?=_lang('Show sidebar')?>" data-type="<?=$type?>">
                <div class="hamburger" id="hamburger">
                    <span class="line"></span>
                    <span class="line"></span>
                    <span class="line"></span>
                </div>
            </a>
            <a href="<?=site_url()?>" title="" class="logo"><?=str_replace("/>"," alt=\"logo\" />",show_logo())?></a>
            <br style="clear:both;" />
        </div>
        <div class="header">

            <div class="searchWidget">
                <form action="" method="get" id="searchform" <? if(get_option(' youtube-suggest',1)> 0) { echo
                    'autocomplete="off"'; } ?>>
                    <div class="search-holder">
                        <span class="search-button">
                            <button type="submit">
                                <i class="search-icon"></i>
                            </button>
                        </span>
                        <div class="form-control-wrap">
                            <input type="text" class="form-control input-lg empty" name="tag" value=""
                                placeholder="<?=_lang("Search media")?>">
                        </div>
                        <div></div>
                    </div>

                </form>
                <?if(get_option('youtube-suggest',1) > 0) {?>
                <div id="suggest-results"></div>
                <?}?>
            </div>
            
            <div class="user-quick">
                <a class="top-link" id="show-search"><i class="material-icons">search</i></a>
                <?if(!is_user()) {?>
                <a id="openusr" class="btn uav btn-small no-user" href="/login" data-animation="scale-up" role="button"
                    title="<?=_lang('Login')?>">
                    <img title="<?=_lang('Guest')?>" data-name="<?=_lang('Guest')?>"
                        src="/tpl/main/images/man-avatar.svg" class="man-avatar-icon" alt="icon" />
                    Войти</a>
            </div>
            <?} else {?>
                <?
                if(is_user())
                {

                global $db;

                $messagesCount = 0;

                try {

                  $sql = 'SELECT * FROM '.DB_PREFIX. 'conversations WHERE user_id = '.toDb(user_id());

                  $conferences = $db->get_results($sql);

                  foreach ($conferences as $conference) {

                      $sql = 'SELECT * FROM '.DB_PREFIX. 'messages WHERE user_id <> '.toDb(user_id()). ' AND readed = 0 AND conversation_id = '.toDb($conference->conf_id);

                      $sql2 = $db->get_results($sql);

                      $messagesCount = $messagesCount + count($sql2 ?? []);
                  }


                } catch (\Throwable $th) {
                
                }

                if($messages !== null)
                {
                  $messagesCount = count($messages);
                }
                ?>
            <?}?>
            <?if((get_option('upmenu') == 1) ||  is_moderator()) {?>
            <i class="material-icons"></i>
            <a id="uplBtn" href="<?=site_url().share?>" class="top-link" title="<?=_lang('Upload video')?>">
                <img src="/tpl/main/images/download.svg" class="download-icon" alt="icon" /></a>
            <?}?>

            <a id="notifs" href="<?=site_url()?>dashboard?sk=activity" class="top-link">
                <img src="/tpl/main/images/notifications.svg" class="notifications-icon" alt="icon" />
            </a>
            <?if(get_option('showusers','1') == 1 ) {?>
            <a id="chnls" class="top-link" href="<?=site_url()?>members/"><img src="/tpl/main/images/chanels.svg"
                    class="chanels-icon" alt="icon" /></a>
            <?}?>
            <a id="openusr" class="btn uav btn-small dropdown-toggle" data-toggle="dropdown" href="#"
                aria-expanded="false" data-animation="scale-up" role="button" title="<?=_lang('Dashboard')?>">
                <?if (user_name() != '') {?>
                <img data-name="<?=addslashes(user_name())?>" src="<?=thumb_fix(user_avatar(), true, 35,35)?>"
                    alt="logo" />
                <?} else {?>
                <img src="/tpl/main/images/man-avatar.svg" class="man-avatar-icon" alt="icon" />
                <?}?>
            </a>
            <ul class="dropdown-menu dropdown-left" role="menu">
                <li role="presentation" class="drop-head"><?=group_creative(user_group())?>
                    <a href="<?=profile_url(user_id(), user_name())?>">
                        <?=user_name()?>
                    </a>
                    <? if( !is_empty(premium_upto())) {
                        if (new DateTime() > new DateTime(premium_upto())) {?>
                    <p class="small nomargin">
                        <a href="<?=site_url()?>payment"><?=_lang("Premium expired")?></a>
                    </p>
                    <?}}?>
                </li>
                <li role="presentation">
                    <a href="<?=site_url()?>studio">
                        <i class="icon material-icons">
                            new_releases
                        </i>
                        <?= _lang('Студия')?>
                    </a>
                </li>
                <li role="presentation">
                    <a href="<?=site_url().me?>/?sk=likes">
                        <i class="icon material-icons">&#xE8DC;</i>
                        <?= _lang('Понравившееся')?>
                    </a>
                </li>
				<li>
                    <a href="<?=site_url().me?>/?sk=history">
                        <i class="icon material-icons">&#xE889;</i>
                        <?=_lang('History')?>
                    </a>
                </li>
				<li role="presentation">
                    <a href="<?=site_url().me?>/?sk=later">
                        <i class="icon material-icons">&#xE924;</i>
                        <?=_lang('Смотреть позже')?>
                    </a>
                </li>
                <?
                      if(get_option('allowpremium') == 1 ) {
                      if( is_empty(premium_upto())) {
                    ?>
                <li role="presentation">
                    <a href="<?=site_url()?>payment">
                        <i class="icon material-icons">&#xE8D0;</i>
                        <?=_lang("Get Premium")?>
                    </a>
                </li>
                <?}}?>
                <? /*
                <li class="my-buzz" role="presentation">
                    <a href="<?=site_url()?>dashboard/">
                        <i class="icon material-icons">&#xE031;</i>
                        <?=_lang('Media Studio')?>
                    </a>
                </li>
                <li role="presentation">
                    <a href="<?=site_url()?>dashboard/?sk=edit">
                        <i class="icon material-icons">&#xE8B8;</i>
                        <?=_lang("My Settings")?>
                    </a>
                </li> */ ?>
                <? /*<li role="presentation">
                    <a href="<?=site_url().me?>">
                        <i class="icon material-icons">&#xE04A;</i>
                        <?=_lang("Мои видео")?>
                    </a>
                </li>
                <li role="presentation">
                    <a href="<?=site_url().me?>?sk=music">
                        <i class="icon material-icons">&#xE030;</i>
                        <?=_lang("My Music")?>
                    </a>
                </li>
                <li role="presentation">
                    <a href="<?=site_url().me?>?sk=images">
                        <i class="icon material-icons">&#xE413;</i>
                        <?=_lang("My Images")?>
                    </a>
                </li> */?>
                <?if(is_admin()){?>
                <li role="presentation">
                    <a href="<?=ADMINCP?>">
                        <i class="icon material-icons">&#xE8A4;</i>
                        <?=_lang("Administration")?>
                    </a>
                </li>
                <?}?>
                <li role="presentation" class="drop-footer"><a href="/?action=logout"><i
                            class="icon material-icons">&#xE14C;</i><?=_lang("Logout")?></a></li>
            </ul>
        </div>
        <?}?>
    </div>
</div>
</div>