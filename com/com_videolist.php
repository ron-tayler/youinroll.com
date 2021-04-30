<?php  //Global query options
$options = DB_PREFIX."videos.id,".DB_PREFIX."videos.title,".DB_PREFIX."videos.date,".DB_PREFIX."videos.user_id,".DB_PREFIX."videos.thumb,".DB_PREFIX."videos.views,".DB_PREFIX."videos.duration,".DB_PREFIX."videos.nsfw";
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
switch(token()){

case mostliked:
		$heading = ('Most Liked');	
        $heading_plus = _lang('Videos which have received the most likes');
		$sortop = true;
        $vq = "select ".$options.", ".DB_PREFIX."users.name as owner, ".DB_PREFIX."users.group_id FROM ".DB_PREFIX."videos LEFT JOIN ".DB_PREFIX."users ON ".DB_PREFIX."videos.user_id = ".DB_PREFIX."users.id WHERE ".DB_PREFIX."videos.liked > 0 and ".DB_PREFIX."videos.pub > 0 and ".DB_PREFIX."videos.media < 2 ".$interval." ORDER BY ".DB_PREFIX."videos.liked DESC ".this_limit();
		$active = mostliked;
		break;
case mostcom:
		$heading = ('Most Commented');	
        $heading_plus = _lang('Videos which have received the most comments');		
	    $vq = "select ".DB_PREFIX."videos.id,".DB_PREFIX."videos.title,".DB_PREFIX."videos.user_id,".DB_PREFIX."videos.thumb,".DB_PREFIX."videos.views,".DB_PREFIX."videos.liked,".DB_PREFIX."videos.duration,".DB_PREFIX."videos.nsfw, ".DB_PREFIX."users.name as owner , ".DB_PREFIX."users.group_id,  count(a.object_id) as cnt FROM ".DB_PREFIX."em_comments a LEFT JOIN ".DB_PREFIX."videos ON a.object_id LIKE CONCAT('video_', ".DB_PREFIX."videos.id) LEFT JOIN ".DB_PREFIX."users ON ".DB_PREFIX."videos.user_id = ".DB_PREFIX."users.id WHERE ".DB_PREFIX."videos.pub > 0 and ".DB_PREFIX."videos.media < 2 AND vibe_videos.course IS NULL  group by a.object_id order by cnt desc ".this_limit();
		
		$active = mostcom;
		break;		
case mostviewed:
		$heading = ('Most Viewed');	
        $heading_plus = _lang('Videos which have received the most views');
        $sortop = true;		
        $vq = "select ".$options.", ".DB_PREFIX."users.name as owner , ".DB_PREFIX."users.group_id FROM ".DB_PREFIX."videos LEFT JOIN ".DB_PREFIX."users ON ".DB_PREFIX."videos.user_id = ".DB_PREFIX."users.id WHERE ".DB_PREFIX."videos.views > 0 and ".DB_PREFIX."videos.pub > 0 and ".DB_PREFIX."videos.media < 2 ".$interval." AND vibe_videos.course IS NULL ORDER BY ".DB_PREFIX."videos.views DESC ".this_limit();
		$active = mostviewed;
		break;
case promoted:
		$heading = _lang('Featured');
        $heading_plus = _lang('Videos we\'ve picked for you');
		$sortop = true;		
		
		$vq = "SELECT
			videos.id,
			videos.title,
			videos.date,
			videos.user_id,
			videos.thumb,
			videos.views,
			videos.duration,
			videos.nsfw,
			users.name AS owner,
			users.avatar,
			users.group_id
		FROM
			".DB_PREFIX."videos AS videos
		LEFT JOIN ".DB_PREFIX."users AS users ON videos.user_id = users.id
		WHERE
			videos.pub > 0 AND videos.media < 2
		AND
			videos.course IS NULL
			$interval
		AND
			videos.featured = 1
		ORDER BY
			videos.id
		DESC ".this_limit();

        $active = promoted;
		break;
default:
		$heading = _lang('Newest videos');	
        $heading_plus = _lang('Most recently submited videos');        
		$vq = " SELECT
					videos.id,
					videos.title,
					videos.date,
					videos.user_id,
					videos.thumb,
					videos.views,
					videos.duration,
					videos.nsfw,
					users.name AS owner,
					users.avatar,
					users.group_id
				FROM
					".DB_PREFIX."videos AS videos
				LEFT JOIN ".DB_PREFIX."users AS users ON videos.user_id = users.id
				WHERE
					videos.pub > 0 AND videos.media < 2
				AND
					videos.course IS NULL
				ORDER BY
					videos.id
				DESC ".this_limit();
        $active = browse;
		break;		
}

// Canonical url
if(_get('sort')) {
$canonical = list_url(token())."?sort="._get('sort'); 
} else {
$canonical = list_url(token()); 
}
// SEO Filters
function modify_title( $text ) {
global $heading;
    return strip_tags(stripslashes($heading));
}
function modify_desc( $text ) {
global $heading_plus;
    return _cut(strip_tags(stripslashes($heading_plus)), 160);
}
add_filter( 'phpvibe_title', 'modify_title' );
add_filter( 'phpvibe_desc', 'modify_desc' );
//Time for design
//Time for design
$YNRtemplate->include('/videolist.php',
[
	'/tpl/main_n/styles/components/category.css',
	'/tpl/main_n/styles/components/coursecard.css'
],
[],
[
	'active' => $active,
	'heading' => $heading,
	'heading_plus' => $heading_plus,
	'vq' => $vq,
]
);
?>