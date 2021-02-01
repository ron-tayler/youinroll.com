<?php the_sidebar(); 
$active = com();

if(isset($_POST['name']))
{
	$sqlString = "INSERT INTO ".DB_PREFIX."conferences (`name`,`cover`,`description`,`moderator_id`) VALUES 
		('".toDb(_post('name'))."','".toDb(_post('image'))."','".toDb(_post('description'))."','".intval(user_id())."')";

	$db->query($sqlString);

	$result = true;
}
?>
<div id="default-content" class="share-media">
<div class="row">
<?php if( isset($result) && $result === false ) { ?>
	<script>
	alert('Урок не добавлен')
	</script>
<?php } ?>
<?php if( isset($result) && $result === true ) { ?>
	<script>
	alert('Урок добавлен')
	</script>
<?php } ?>
<div class="container">
<div class="row">
	<div class="col order-md-1">
		<h4 class="mb-3">Инфо об уроке</h4>
		<form class="needs-validation" action='<?=site_url().addconf?>' method='post'>
		<div class="mb-3">
			<label for="name">Название</label>
			<input type="text" name="name" class="form-control" id="name" placeholder="Урок обж" required="">
		</div>
		<div class="mb-3">
			<label for="image">Ссылка на изображение</label>
			<input type="text" name="image" class="form-control" id="image" placeholder="https://test/test.jpg" required="">
		</div>
		<div class="mb-3">
			<label for="startedAt">Начало в</label>
			<input type="date" name="startedAt" class="form-control" id="startedAt" required="">
		</div>
		<div class="mb-3">
			<label for="playlist">Добавить в плейлист</label>
			<select class='form-control select2' name="playlist" id="playlist">
				<option name=''>asdas</option>
			</select>
		</div>
		<div class="mb-3">
			<label for="description">Описание</label>
			<textarea class="form-control" id="description" name="description" placeholder="Опиание" rows="3"></textarea>
		</div>
		<hr class="mb-4">
		<button class="btn btn-primary btn-lg btn-block" type="submit">Сохранить</button>
		</form>
	</div>
</div>
</div>