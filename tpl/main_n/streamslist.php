<?php

$options = DB_PREFIX."conferences.id,".DB_PREFIX."conferences.name,".DB_PREFIX."conferences.description,".DB_PREFIX."conferences.created_at,".DB_PREFIX."conferences.started_at,".DB_PREFIX."conferences.moderator_id,".DB_PREFIX."conferences.cover,".DB_PREFIX."conferences.likes,".DB_PREFIX."conferences.views,".DB_PREFIX."conferences.category,".DB_PREFIX."conferences.on_air";
/* Define list to load */
$interval = '';
if(_get('sort'))
{
switch(_get('sort')){
case "w":
$interval = "AND WEEK( DATE ) = WEEK( CURDATE( ) ) ";
break;
case "m":
$interval = "AND MONTH(date) = MONTH(CURDATE( ))";
break;
case "y":
$interval = "AND YEAR( DATE ) = YEAR( CURDATE( ) ) ";
break;
}
}

switch ($_GET['sk']) {
	case lessons:
		$heading = _lang('Мои уроки');	
        $heading_plus = _lang('Мои уроки');        
		$vq = "select ".$options.", ".DB_PREFIX."users.avatar as owner_avatar, ".DB_PREFIX."users.name as owner, ".DB_PREFIX."users.group_id FROM ".DB_PREFIX."conferences LEFT JOIN ".DB_PREFIX."users ON ".DB_PREFIX."conferences.moderator_id = ".DB_PREFIX."users.id "." WHERE ".DB_PREFIX."conferences.moderator_id = ".user_id()." AND ".DB_PREFIX."conferences.type = 'lesson'  ORDER BY ".DB_PREFIX."conferences.id DESC ".this_limit();
        $active = lessons;
		break;

	case raspisanie:
		$heading = ('Расписание');	
		$heading_plus = _lang('Расписание уроков');
		$sortop = true;
		$vq = "select ".$options.", ".DB_PREFIX."users.avatar as owner_avatar, ".DB_PREFIX."users.name as owner, ".DB_PREFIX."users.group_id FROM ".DB_PREFIX."conferences LEFT JOIN ".DB_PREFIX."users ON ".DB_PREFIX."conferences.moderator_id = ".DB_PREFIX."users.id ".DB_PREFIX."conferences.likes > 0 ORDER BY ".DB_PREFIX."conferences.likes DESC ".this_limit();
		$active = raspisanie;
		break;
	
	default:
		$heading = _lang('My Streams');	
		$heading_plus = _lang('My Streams');        
		$vq = "select ".$options.", ".DB_PREFIX."users.avatar as owner_avatar, ".DB_PREFIX."users.name as owner, ".DB_PREFIX."users.group_id FROM ".DB_PREFIX."conferences LEFT JOIN ".DB_PREFIX."users ON ".DB_PREFIX."conferences.moderator_id = ".DB_PREFIX."users.id "." WHERE ".DB_PREFIX."conferences.moderator_id = ".user_id()." AND ".DB_PREFIX."conferences.type = 'stream' ORDER BY ".DB_PREFIX."conferences.id DESC ".this_limit();
		$active = my;
		break;
}

the_sidebar();
if(isset($sortop) && $sortop) {
/* Most liked , Most viewed time sorting */
$st = '
<div class="btn-group pull-right">
       <a data-toggle="dropdown" class="btn btn-default btn-outline dropdown-toogle"> <i class="icon icon-calendar"></i>упорядочить<i class="icon icon-angle-down"></i> </a>
			<ul class="dropdown-menu dropdown-menu-right bullet">
			<li title="'._lang("This Week").'"><a href="'.lessonslist_url(token()).'?sort=w"><i class="icon icon-circle-thin"></i>'._lang("This Week").'</a></li>
			<li title="'._lang("This Month").'"><a href="'.lessonslist_url(token()).'?sort=m"><i class="icon icon-circle-thin"></i>'._lang("This Month").'</a></li>
			<li title="'._lang("This Year").'"><a href="'.lessonslist_url(token()).'?sort=y"><i class="icon icon-circle-thin"></i>'._lang("This Year").'</a></li>
			<li class="divider" role="presentation"></li>
			<li title="'._lang("This Week").'"><a href="'.lessonslist_url(token()).'"><i class="icon icon-circle-thin"></i>'._lang("All").'</a></li>
		</ul>
		</div>
';
}

 ?>
<div class="row main-holder">

    <ul class="nav nav-tabs nav-tabs-line mtop20">
        <li class="<?php aTab(my);?>" role="presentation"><a href="https://youinroll.com/lessons"> <i
                    class="material-icons">&#xE8E5;</i> <?php echo _lang('Мои трансляции'); ?></a></li>
		<li class="<?php aTab(lessons);?>" role="presentation"><a href="https://youinroll.com/lessons?sk=lessons"> <i
                    class="material-icons">&#xE8E5;</i> <?php echo _lang('Мои уроки'); ?></a></li>
        <li class="<?php aTab(raspisanie);?>" role="presentation"><a href="https://youinroll.com/lessons?sk=raspisanie">
                <i class="material-icons">&#xE8E5;</i> <?php echo _lang('Расписание'); ?></a></li>
        <li class="pull-right" role="presentation"><a href="<?php echo site_url().addconf; ?>"> <i
                    class="material-icons">&#xE2C3;</i> <?php echo _lang('Add Stream'); ?></a></li>
    </ul>
    <div id="videolist-content">
        <?php 

echo _ad('0','conference-list-top');
if($active === raspisanie)
{
	include_once(TPL.'/conference-raspisanie.php');

} else
{
	include_once(TPL.'/conference-loop.php');
}
echo _ad('0','conference-list-bottom');

?>
    </div>
</div>