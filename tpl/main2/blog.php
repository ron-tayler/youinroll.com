<?php the_sidebar(); ?>
<div class="row">
<div class="blog-holder col-md-9 col-xs-12">
<?php 
$posts = $db->get_results($vq);
if ($posts) {
?>
<ul class="blog-list  mtop20 list-unstyled">
<?php foreach ($posts as $ar) {
echo '
<li>';

echo '
	<div class="blog-post block block-inline">
		<header>
			<a class="text-primary" href="'.article_url($ar->pid, $ar->title).'" title="'._html($ar->title).'">
				<h3>'._html($ar->title).'</h3>
			</a>
		</header>';

if(isset($ar->pic) && !nullval($ar->pic)) {
echo '
		<div class="blog-image">							
			<div class="text-center ">
				<a href="'.article_url($ar->pid, $ar->title).'" title="'._html($ar->title).'">
					<img src="'.thumb_fix($ar->pic).'">
				</a>
			</div>							
		</div>';
}

$mini_text = "";
$mini_text = str_replace ("</p>", "</p> ", htmlspecialchars_decode($ar->content));
$mini_text = _html(_cut(strip_tags($mini_text),560));
$mini_text = str_replace ("ИзменитьПервый образовательный видеохостингСегодняПервый образовательный видеохостинг.", "", $mini_text);

echo '	<div class="blog-text">							
			'.$mini_text.'...	
		</div>';

echo  '
		<div class="blog-more">
			<a  href="'.article_url($ar->pid, $ar->title).'" title="'._html($ar->title).'">'._lang("Read More").'</a>
		</div>
	</div>
</li>';
}
?>
</ul>
<?php
} else {
echo _lang("Sorry but there are no results");	
}	

$a->show_pages($pagestructure);

?>
</div>
<?php include_once('blog-sidebar.php'); ?>
</div>
