<?php the_sidebar();
//Add sorter 
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
        <li class="<?php aTab(my);?>" role="presentation"><a href="https://youinroll.com/streams"> <i
                    class="material-icons">&#xE8E5;</i> <?php echo _lang('My Streams'); ?></a></li>
        <?
		$hasAccess = $cachedb->get_row('SELECT id FROM '.DB_PREFIX."users_groups WHERE is_bussines = '1' AND id = ".toDb(user_group()));
		if($hasAccess) {
	?>
        <li class="<?php aTab(lessons);?>" role="presentation"><a href="https://youinroll.com/streams?sk=lessons"> <i
                    class="material-icons">&#xE8E5;</i> <?php echo _lang('My Lessons'); ?></a></li>
        <? } ?>
        <li class="<?php aTab(raspisanie);?>" role="presentation"><a href="https://youinroll.com/streams?sk=raspisanie">
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