<?php the_sidebar();
//Add sorter 
if(isset($sortop) && $sortop) {
/* Most liked , Most viewed time sorting */
$st = '
<div class="btn-group pull-right">
       <a data-toggle="dropdown" class="btn btn-default btn-outline dropdown-toogle"> <i class="icon icon-calendar"></i>упорядочить<i class="icon icon-angle-down"></i> </a>
			<ul class="dropdown-menu dropdown-menu-right bullet">
			<li title="'._lang("This Week").'"><a href="'.list_url(token()).'?sort=w"><i class="icon icon-circle-thin"></i>'._lang("This Week").'</a></li>
			<li title="'._lang("This Month").'"><a href="'.list_url(token()).'?sort=m"><i class="icon icon-circle-thin"></i>'._lang("This Month").'</a></li>
			<li title="'._lang("This Year").'"><a href="'.list_url(token()).'?sort=y"><i class="icon icon-circle-thin"></i>'._lang("This Year").'</a></li>
			<li class="divider" role="presentation"></li>
			<li title="'._lang("This Week").'"><a href="'.list_url(token()).'"><i class="icon icon-circle-thin"></i>'._lang("All").'</a></li>
		</ul>
		</div>
';
}

 ?>
<div class="row main-holder">

<?
$categories = $cachedb->get_results('SELECT cat_id,cat_name,cat_desc,picture FROM '.DB_PREFIX.'channels where type = '.toDb(1).' AND child_of = '.toDb(0).'');
?>

<div class='container'>
	<h1>Категории</h1>
	<div class='loop-content course-row'>
		<?foreach ($categories as $category) {?>

			<a class="card" href='<?=channel_url($category->cat_id, $category->cat_name)?>'>
				<header class="card__header">
					<div class="thumb" style="background-image: url('<?=thumb_fix($category->picture, 240, 240)?>')"></div>
					<div class="after">
					<div class="square"></div>
					<div class="tri"></div>
					</div>
				</header>
				<div class="card__body">
					<h1 class="title">
						<?=$category->cat_name?>
					</h1>
					<h2 class="subtitle">
					<?=substr($category->cat_desc, 0, 200)?>
					</h2>
				</div>
			</a>
		<?}?>
	</div>
</div>

<br>

<div id="videolist-content">

<?php echo _ad('0','video-list-top');

$vq = isset($vq) ? $vq : $globalTemplateVariable['vq'];
$active = isset($active) ? $active : $globalTemplateVariable['active'];
$header = isset($header) ? $header : $globalTemplateVariable['header'];
$header_plus = isset($header_plus) ? $header_plus : $globalTemplateVariable['header_plus'];

//include_once(TPL.'/video-loop.php');
 echo _ad('0','video-list-bottom');
?>



</div>
</div>