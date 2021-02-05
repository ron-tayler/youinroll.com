<div id="sidebar" class="<?php if(is_video() || is_picture() || is_com('stream') || is_com('conversation')) {echo 'hide-all';} else {echo '';} ?> animated zoomInLeft">
    <div class="sidescroll">
        <?php do_action('sidebar-start');
	echo _ad('0','sidebar-start');

	//The menu
	echo '<div class="sidebar-nav blc"><ul>';
	echo '<li class="lihead';
	if (!isset($_SERVER['REQUEST_URI']) || ltrim($_SERVER['REQUEST_URI'],'/') === '') {
	echo ' item-activ';
	}
	echo '"><a href="'.site_url().'"><span class="iconed"><img src="/tpl/main/icon-menu/home.png" alt="icon" /></span> '._lang('Home').'</a><span class="tooltip-item">'._lang('Home').'</span></li>';
	/*
	echo '<li class="lihead hidden-md hidden-lg visible-sm-block visible-sm visible-xs-block visible-xs">
	<a data-target="#search-now" data-toggle="modal" href="javascript:void(0)" id="show-searched2"> <span class="iconed"> <i class="material-icons">&#xE8B6;</i> </span>'._lang('Search').'</a>
	</a>';
	*/
	if(get_option('premiumhub',1) == 1 ) {	
		echo '<li class="lihead';
		if ($_SERVER['REQUEST_URI'] == "/premiumhub/browse/")  {echo ' item-activ';} 
		echo '"><a href="'.hub_url(browse).'"><span class="iconed"><img src="/tpl/main/icon-menu/zvez.png" alt="icon" /></span> '._lang('Premium Hub').'</a><span class="tooltip-item">'._lang('Premium Hub').'</span></li>';	
	}

	echo '<li class="lihead';
	if ($_SERVER['REQUEST_URI'] == '/activity') {echo ' item-activ';}
	echo '"><a href="'.site_url().buzz.'"><span class="iconed"><img src="/tpl/main/icon-menu/activ.png" alt="icon" /></span> '._lang('What\'s up').'</a><span class="tooltip-item">'._lang('What\'s up').'</span></li>';

	echo '<li class="lihead';
	if ($_SERVER['REQUEST_URI'] == '/videos/browse/') {echo ' item-activ';}
	echo '"><a href="'.list_url(browse).'"><span class="iconed"><img src="/tpl/main/icon-menu/video.png" alt="icon" /></span> '._lang('Videos').'</a><span class="tooltip-item">'._lang('Videos').'</span></li>';

	if(get_option('musicmenu') == 1 ) {
	echo '<li class="lihead';

	if ($_SERVER['REQUEST_URI'] == '/music/browse/') {echo ' item-activ';}
	echo '"><a href="'.music_url(browse).'"><span class="iconed"><img src="/tpl/main/icon-menu/music.png" alt="icon" /></span> '._lang('Music').'</a><span class="tooltip-item">'._lang('Music').'</span></li>';	
	}
	/*
	if ($_SERVER['REQUEST_URI'] == '/music/browse/') {echo ' item-activ';}
	echo '"><span class="iconed temp-sidebar-class"><img src="/tpl/main/icon-menu/music.png"> <a href="'.music_url(browse).'">'._lang('Music').'</a></span></li>';	
	}
	*/
	if(get_option('showplaylists',1) == 1 ) {
	echo '<li class="lihead';
	if ($_SERVER['REQUEST_URI'] == '/lists/') {echo ' item-activ';}
	echo '"><a href="'.site_url().playlists.'/"><span class="iconed"><img src="/tpl/main/icon-menu/audio.png" alt="icon" /></span>'._lang('Playlists').'</a><span class="tooltip-item">'._lang('Playlists').'</span></li>';	
	}
	if(get_option('imagesmenu') == 1 ) {
	echo '<li class="lihead';
	if ($_SERVER['REQUEST_URI'] == '/images/browse/') {echo ' item-activ';}
	echo '"><a href="'.images_url(browse).'"><span class="iconed"><img src="/tpl/main/icon-menu/photo.png" alt="icon" /></span> '._lang('Pictures').'</a><span class="tooltip-item">'._lang('Pictures').'</span></li>';	
	echo '<li class="lihead';
	if ($_SERVER['REQUEST_URI'] == '/albums') {echo ' item-activ';}
	echo '"><a href="'.site_url().albums.'"><span class="iconed"><img src="/tpl/main/icon-menu/galer.png" alt="icon" /></span> '._lang('Galleries').'</a><span class="tooltip-item">'._lang('Galleries').'</span></li>';	

	}

	if(get_option('showusers',1) == 1 ) {
	echo '<li class="lihead';
	if ($_SERVER['REQUEST_URI'] == '/users/') {echo ' item-activ';}
	echo '"><a href="'.site_url().members.'/"><span class="iconed"><img src="/tpl/main/icon-menu/chanal.png" alt="icon" /></span>'._lang('Channels').'</a><span class="tooltip-item">'._lang('Channels').'</span></li>';	
	}
	if(get_option('showblog',1) == 1 ) {
	echo '<li class="lihead';
	if ($_SERVER['REQUEST_URI'] == '/blog/') {echo ' item-activ';}
	echo '"><a href="'.site_url().blog.'/"><span class="iconed"><img src="/tpl/main/icon-menu/blog.png" alt="icon" /></span>'._lang('Blog').'</a><span class="tooltip-item">'._lang('Blog').'</span></li>';	;
	}

	echo '</ul></div>';
	/* End of menu */
	?>
        <?php	
	if (is_user()) {
	/* start my playlists */	
	?>
	<hr />
	<div class='relative'>
		<div class="sidebar-nav blc">
			<ul>
				<li class='lihead'><a href="<?=site_url().me?>/?sk=likes"><span class="iconed icon-opacity"><i class="material-icons">&#xE8DC;</i></span><?= _lang('Понравившееся')?></a> </li>
				<li class='lihead'><a href="<?=site_url().me?>/?sk=history"><span class="iconed icon-opacity"><i class="material-icons">&#xE889;</i></span><?=_lang('History')?></a> </li>
				<li class='lihead'><a href="<?=site_url().me?>/?sk=later"><span class="iconed icon-opacity"><i class="material-icons">&#xE924;</i></span><?=_lang('Смотреть позже')?></a> </li>
			</ul>
		</div>
	</div>
	<div class='relative'>
		<h4 class="li-heading li-heading-iconed">
			<a style="color: #797E89;"
				href="https://youinroll.com/me?sk=playlists"
				title="<?php echo _("View all"); ?>">
				<h5><?=_lang('My Playlists')?></h5></a>
			
		</h4>
		<div class="sidebar-nav blc">
			<ul id="playlistItems">
				<i id="playlistDropdown" class="material-icons" type="button"
					data-page="1" title="<?= _lang('Expand'); ?>">
					&#xe313;
				</i>
				<i id="playlistMinimize" class="material-icons" title="<?= _lang('Minimize'); ?>">
					&#xe316;
				</i>
			</ul>
		</div>
	</div>
	<hr />
	<?
	/* end my playlists */
	/* start my albums */
	/* $albums = $cachedb->get_results("SELECT id, title FROM ".DB_PREFIX."playlists where owner= '".user_id()."' and picture not in ('[likes]','[history]','[later]') and ptype > 1 order by title asc limit 0,100");
	if($albums) { 
	foreach ($albums as $play) {
	echo '<li>
	<a href="'.playlist_url($play->id, $play->title).'" original-title="'.$play->title.'" title="'.$play->title.'"><i class="material-icons">&#xE438;</i>
	'._html(_cut($play->title, 24)).'
	</a>
	</li>';
	}
	}
	echo '</ul>
	</div>'; */

	/* end my albums */
	/* start my  subscriptions */ 
	?>
	<div class='relative'>
		<h4 class="li-heading">
			<a style="color: #797E89;"
				href="<?php echo profile_url(user_id(), user_name()); ?>?sk=subscribed"
				title="<?php echo _("View all"); ?>"><?php echo _lang('My subscriptions'); ?> </a>
		</h4>
		<div class="sidebar-nav blc">
			<ul id="subscribtionItems">
			</ul>
			<div id="subscriptionDropdown" class="" type="button" data-page="1">
				<i class="material-icons" type="button"
					data-page="1" title="<?= _lang('Expand'); ?>">
					&#xe313;
				</i><span><?= _lang('Expand'); ?></span>
			</div>
			<div id="subscriptionMinimize" class="" type="button">
				<i class="material-icons" title="<?= _lang('Minimize'); ?>">
					&#xe316;
				</i><span><?= _lang('Minimize'); ?></span>
			</div>
		</div>
	</div>
	<hr />
	<?php
		/* end subscriptions */
		echo '<div id="endSidebar"><h4 class="li-heading">'._lang('Другие возможности').'</h4>';
		echo '<div class="sidebar-nav blc"><ul>';
		
		echo '<li class="lihead';
		if ($_SERVER['REQUEST_URI'] == '/payment') {echo ' item-activ';}
		echo '"><a href="/payment"><span class="iconed"><img src="/tpl/main/icon-menu/zvez.png" alt="icon" /></span> '._lang("Get Premium").'</a><span class="tooltip-item">'._lang("Get Premium").'</span></li>';

		echo '<li class="lihead';
		if ($_SERVER['REQUEST_URI'] == '/dashboard?sk=edit') {echo ' item-activ';}
		echo '"><a href="/dashboard?sk=edit"><span class="iconed"><i class="icon material-icons">&#xE8B8;</i></span> '._lang("My Settings").'</a><span class="tooltip-item">'._lang("Get Premium").'</span></li>';
		
		echo "</ul></div></div>";
		do_action('user-sidebar-end');	
		} else {
		echo '<div class="blc mtop20 odet fs300" id="guestButton">';	
		echo _lang('Share videos, music and pictures, follow friends and keep track of what you enjoy!');
		echo '<p class="small mright20 mleft10"><a href="/login" class="btn-primary1 btn-small btn-block mtop20">		
		'._lang("Join us").'</a> </p>';	
		echo '</div>';
		echo '<div id="endSidebar"><h4 class="li-heading">'._lang('Другие возможности').'</h4>';
		echo '<div class="sidebar-nav blc"><ul>';
		
		echo '<li class="lihead';
		if ($_SERVER['REQUEST_URI'] == '/payment') {echo ' item-activ';}
		echo '"><a href="/payment"><span class="iconed"><img src="/tpl/main/icon-menu/zvez.png" alt="icon" /></span> '._lang("Get Premium").'</a><span class="tooltip-item">'._lang("Get Premium").'</span></li>';
		
		echo '</ul></div></div>';
		do_action('guest-sidebar');					
		}
		do_action('sidebar-end');
		echo _ad('0','sidebar-end')
		?>
                        <div class="blc">&nbsp;</div>
                </div>
		</div>