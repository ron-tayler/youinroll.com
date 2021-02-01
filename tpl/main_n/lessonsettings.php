<?php the_sidebar(); 
$active = com();

if(isset($_POST['name']))
{
	$sqlString = "INSERT INTO ".DB_PREFIX."conferences (`name`,`cover`,`description`,`moderator_id`) VALUES 
		('".toDb(_post('name'))."','".toDb(_post('image'))."','".toDb(_post('description'))."','".intval(user_id())."')";

	//$db->query($sqlString);

	$result = true;
}

if(intval(token_id()) !== null)
{
	$lessonId = intval(token_id());

	$sqlString = "SELECT * FROM ".DB_PREFIX."conferences WHERE moderator_id = ".toDb(user_id())." AND id = ". toDb($lessonId);

	$lesson = $db->get_results($sqlString);


	$lessonUsers = $db->get_results("SELECT * FROM ".DB_PREFIX."conference_participants LEFT JOIN ".DB_PREFIX."users ON ".DB_PREFIX."conference_participants.user_id = ".DB_PREFIX."users.id WHERE conference_id = " . toDb($lessonId));

} else
{
	die();
}

?>
<div id="default-content" class="share-media">
<div class="row">
<?php if( isset($result) && $result === false ) { ?>

<?php } ?>
<?php if( isset($result) && $result === true ) { ?>

<?php } ?>
<div class="container">
<a href='<?=lessonslist_url(my)?>'>Назад</a>
<h2>Добавление учеников</h2>
<div class="table-responsive">
	<table class="table table-striped table-sm" id="studentsTable">
		<thead>
		<tr>
			<th>#</th>
			<th>Почта</th>
			<th>Действие</th>
		</tr>
		</thead>
		<tbody>
		<?php foreach($lessonUsers as $lessonUser) { ?>
		<tr>
			<td><?=$lessonUser->id?></td>
			<td><?=$lessonUser->email?></td>
			<td><button data-lesson='<?=$lessonId?>' data-id='<?=$lessonUser->id?>' class='rempeople'>-</button></td>
		</tr>
		<?php } ?>
		<tr>
			<td><input class='form-control' placeholder="Введите id ученика" name='id' /></td>
			<td><input class='form-control w-100' placeholder="Введите почту ученика" name='email' /></td>
			<td><button data-lesson='<?=$lessonId?>' id='addpeople'>+</button></td>
		</tr>
		</tbody>
	</table>
</div>
</div>
<script>
	$('#addpeople').on('click', function(){
		$.post(
			site_url + 'lib/ajax/addStudent.php', { 
				lesson: $(this).data('lesson'),
				id: $('input[name="id"]').val(),
				email: $('input[name="email"]').val(),
			},
			
			function(data){

				data = JSON.parse(data);

				$('input[name="id"]').val('');
				$('input[name="email"]').val('');

				if(data.error === null || data.error === undefined)
				{
					$('#studentsTable').find('tbody').prepend(`
					<tr>
						<td>${data.id}</td>
						<td>${data.email}</td>
						<td><button data-lesson='${data.lesson}' data-id='${data.id}' class='rempeople'>-</button></td>
					</tr>
					`);

				} else
				{
					switch (data.error) {
						case 'user not found':
							alert('Пользователь не найден');
							break;

						case 'user was added':
							alert('Пользователь уже есть в списке');
							break;
					
						default:
							alert('Неизвестная ошибка');
							break;
					}
				}
			}
		);
	});

	$('#studentsTable').on('click', '.rempeople', function(){

		let $buttonPressed = $(this);

		$.post(
			site_url + 'lib/ajax/remStudent.php', { 
				lesson: $(this).data('lesson'),
				id: $(this).data('id'),
			},
			
			function(data){

				data = JSON.parse(data);

				if(data.error === null || data.error === undefined)
				{
					$buttonPressed.closest('tr').remove();
				} else
				{
					switch (data.error) {
						case 'user not found':
							alert('Пользователь не найден');
							break;
					
						default:
							alert('Неизвестная ошибка');
							break;
					}
				}
			}
		);
	});
</script>