<div id="home-content" class="main-holder col-md-12">
	<div v-cloak>
		<video-carousel inline-template class="carousel">
			<div id='confSlider'>
				<i class="material-icons carousel__chevron" @click="changeVideo('right')">&#xe5cb;</i>
				<div class="carousel__content">
					<div class="carousel__video" v-for="(video, i) in videos" :class="video.position">
						<div @click="changeVideo(0, video.position)" class="inactive" v-if="!video.active"></div>
						<div v-if='video.position === "main"' class="carousel__overlay-top">
							<div class="carousel__overlay-picture">
								<img :src="video.channelImage" alt="">
							</div>
							<div class="carousel__overlay-text">
								<div class="carousel__overlay-name">
									{{video.channelName}}
								</div>
								<div class="carousel__overlay-title">
									{{video.channelTitle}}
								</div>
								<div class="carousel__overlay-views">
									{{video.channelViews}} Просмотров
								</div>
							</div>
							<div v-if="video.isPlanned" class="carousel__overlay-waiting">
								<span class="badge badge-warning">Скоро начнётся</span>
							</div>
							
							<div v-if="video.isOnAir" class="carousel__overlay-live">
								<div class="record-button"></div>
								<span class="u-inline-text">В эфире</span>
							</div>

							<div v-else class="carousel__overlay-waiting">
								<span class="badge badge-secondary">Завершено</span>
							</div>
						</div>
						<div class="carousel__overlay" v-if="!video.active && video.position == 'main'">
							<a v-bind:href="video.url"><i class="material-icons big-play" v-on:click="video.active = !video.active">
							&#xe037;
							</i>
							</a>
						</div>
						<div v-if='video.position === "main"' class="carousel__overlay-bottom">
							{{video.description}}
						</div>
						<img v-if="!video.active" :src="video.thumbnail" alt="" class="carousel__video-picture">
						
						<div class="carousel__placeholder-background" v-if="video.active"></div>
					</div>
				</div>
				<i class="material-icons carousel__chevron" @click="changeVideo('left')">&#xe5cc;</i>
			</div>
		</video-carousel>
	</div>
	<br><br>
	<div class='container'>
		<h1>Популярные видео:</h1>
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
			AND MONTH(date) = MONTH(CURDATE( ))
			ORDER BY
				videos.views
			DESC LIMIT 25";
		?>
		<?include(TPL.'/video-carousel.php');?>
	</div>
	<div class='container'>
		<h1>Новые видео:</h1>
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
			ORDER BY
				videos.id
			DESC LIMIT 25";
		?>
		<?include(TPL.'/video-carousel.php');?>
	</div>
	<div class='container'>
		<h1>Бесплатные курсы:</h1>
		<div class='loop-content course-row'>
			<?
				$popularCourses = $cachedb->get_results("SELECT playlists.id,playlists.title, playlists.price, playlists.description, playlists.picture, playlists.views, USER.name AS author, USER.avatar AS authorAvatar FROM .vibe_playlists AS playlists INNER JOIN vibe_users AS USER ON playlists.owner = USER.id AND playlists.ptype = 2 ORDER BY playlists.views DESC LIMIT 7");
			?>
			<?foreach ($popularCourses as $course) {?>
				<a class="card" href='<?=playlist_url($course->id, $course->title)?>'>
					<header class="card__header">
						<div class="thumb" style="background-image: url('<?=$course->picture?>')"></div>
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
			<a class="card" href='/playlists'>
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
		<h1>Платные курсы:</h1>
		<div class='loop-content course-row'>
			<?
				$popularCourses = $cachedb->get_results("SELECT playlists.id, playlists.title, playlists.price, playlists.description, playlists.picture, playlists.views, USER.name AS author, USER.avatar AS authorAvatar FROM .vibe_playlists AS playlists INNER JOIN vibe_users AS USER ON playlists.owner = USER.id AND playlists.price IS NOT NULL AND playlists.price <> 0 ORDER BY playlists.views DESC LIMIT 7");
			?>
			<?foreach ($popularCourses as $course) {?>
				<a class="card" href='<?=playlist_url($course->id, $course->title)?>'>
					<header class="card__header">
						<div class="thumb" style="background-image: url('<?=$course->picture?>')"></div>
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
			<a class="card" href='/playlists'>
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
</div>