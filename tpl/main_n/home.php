<div id="home-content" class="main-holder col-md-12">
	<div class='container'>
		<h1>Платные курсы от сообщества:</h1>
		<div class='loop-content course-row'>
			<?
				$popularCourses = $cachedb->get_results("SELECT playlists.id, playlists.title, playlists.price, playlists.description, playlists.picture, playlists.views, USER.name AS author, USER.avatar AS authorAvatar FROM .vibe_playlists AS playlists INNER JOIN vibe_users AS USER ON playlists.owner = USER.id AND playlists.price IS NOT NULL AND playlists.price <> 0 ORDER BY playlists.views DESC LIMIT 7");
			?>
			<?foreach ($popularCourses as $course) {?>

				<?
				$hasLand = false;
				$landing = $cachedb->get_row('SELECT id FROM vibe_landings WHERE playlist_id = '.toDb($course->id));
				
				if($landing){
					$hasLand = true;
				}
				?>
				<a class="card" href='<?=($hasLand) ? "landing/$landing->id" : playlist_url($course->id, $course->title)?>'>
					<header class="card__header">
						<div class="thumb" style="background-image: url('<?=thumb_fix($course->picture, 240, 240)?>')"></div>
						<div class="category">
							<span><?=(intval($course->price) !== 0) ? $course->price.'р' : 'Бесплатно'?></span>
						</div>
						<div class="after">
						<div class="square"></div>
						<div class="tri"></div>
						</div>
					</header>
					<div class="card__body">
						<h1 class="title">
						<?=$course->title?>
						</h1>
						<h2 class="subtitle">
						Просмотров: <?=$course->views?>
						</h2>
					</div>
				</a>
			<?}?>
			<a class="card" href='/lists'>
				<header class="card__header">
					<div class="after">
					<div class="square"></div>
					<div class="tri"></div>
					</div>
				</header>
				<div class="card__body">
					<h1 class="title">
						Показать все
					</h1>
				</div>
			</a>
		</div>
	</div>
	
	<div class='container'>
		<h1>Учебные материалы:</h1>
		<div class='loop-content course-row'>
			<?
				$popularCourses = $cachedb->get_results("SELECT playlists.id,playlists.title, playlists.price, playlists.description, playlists.picture, playlists.views, USER.name AS author, USER.avatar AS authorAvatar FROM .vibe_playlists AS playlists INNER JOIN vibe_users AS USER ON playlists.owner = USER.id AND playlists.ptype = 2 ORDER BY playlists.views DESC LIMIT 7");
			?>
			<?foreach ($popularCourses as $course) {?>
				<a class="card" href='<?=playlist_url($course->id, $course->title)?>'>
					<header class="card__header">
						<div class="thumb" style="background-image: url('<?=thumb_fix($course->picture, 240, 240)?>')"></div>
						<div class="category">
							<span><?=(intval($course->price) !== 0) ? $course->price : 'Бесплатно'?></span>
						</div>
						<div class="after">
						<div class="square"></div>
						<div class="tri"></div>
						</div>
					</header>
					<div class="card__body">
						<h1 class="title">
						<?=$course->title?>
						</h1>
						<h2 class="subtitle">
						Просмотров: <?=$course->views?>
						</h2>
					</div>
				</a>
			<?}?>
			<a class="card" href='/albums'>
				<header class="card__header">
					<div class="after">
					<div class="square"></div>
					<div class="tri"></div>
					</div>
				</header>
				<div class="card__body">
					<h1 class="title">
						Показать все
					</h1>
				</div>
			</a>
		</div>
	</div>

	<br><br>

	<div class='container'>
		<h1>Активные каналы, которые могут вам понравиться:</h1>
		<?
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
			AND MONTH(date) = MONTH(CURDATE())
			ORDER BY videos.id
			DESC LIMIT 25";
		?>
		<?include(TPL.'/video-carousel.php');?>
	</div>
</div>