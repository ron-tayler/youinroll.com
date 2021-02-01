<?php 

if( isset($_POST['id']) ) {

	if(isset($_POST['room']) && $_POST['room'] !== null)
	{
		$db->query("UPDATE  ".DB_PREFIX."conferences SET name='".toDb($_POST['room'])."' WHERE id = '".intval($_POST['id'])."'");	
	}

	if(isset($_POST['cover']) && $_POST['cover'] !== null)
	{
		$db->query("UPDATE  ".DB_PREFIX."conferences SET cover='".toDb($_POST['cover'])."' WHERE id = '".intval($_POST['id'])."'");	
	}

	if(isset($_POST['description']) && $_POST['description'] !== null)
	{
		$db->query("UPDATE  ".DB_PREFIX."conferences SET description='".toDb($_POST['description'])."' WHERE id = '".intval($_POST['id'])."'");	
	}

}

$video = $db->get_row("SELECT * from ".DB_PREFIX."conferences where id = '".intval(_get("vid"))."' ");

if($video) {
?>

<div class="row row-setts">
	<h3>Update <a href="<?php echo conference_url($video->name); ?>" target="_blank"><?php echo $video->name; ?> <i class="icon icon-play-circle"></i></a></h3>
	<div id="thumbus" class="row odet mtop20 text-center"> 
		<a href="#" class="thumb-selects" data-url="">
			<img src=" <?php echo($video->cover); ?>" class="img-selected"/>
		</a>
	</div>
	<form id="validate" class="form-horizontal styled" action="<?php echo admin_url('edit-conference');?>&vid=<?php echo $video->id; ?>" enctype="multipart/form-data" method="post">
	<fieldset>
		<input type="hidden" name="id" id="edited-video" value = "<?php echo $video->id; ?>"/>
		<div class="form-group form-material">
			<label class="control-label"><i class="icon-bookmark"></i><?php echo _lang("Room name"); ?></label>
			<div class="controls">
				<input type="text" name="room" class="validate[required] col-md-12" value="<?php echo $video->name; ?>" /> 						
			</div>	
		</div>
		<div class="form-group form-material">
			<label class="control-label"><i class="icon-bookmark"></i><?php echo _lang("Cover image"); ?></label>
			<div class="controls">
				<input type="text" name="cover" class="validate[required] col-md-12" value="<?php echo $video->cover; ?>" /> 						
			</div>	
		</div>
		<div class="form-group form-material">
			<label class="control-label"><?php echo _lang("Description"); ?></label>
			<div class="controls">
				<textarea rows="5" cols="5" name="description" class="auto validate[required] col-md-12" style="overflow: hidden; word-wrap: break-word; resize: horizontal; height: 88px;"><?php echo($video->description); ?></textarea>					
			</div>	
		</div>

		<div class="page-footer">
			<div class="row">
				<button class="btn btn-large btn-primary pull-right" type="submit"><?php echo _lang("Update video"); ?></button>	
			</div>	
		</div>
	</fieldset>						
	</form>
</div>

<?php
} else {
echo '<div class="msg-warning">Missing video</div>';
} ?>
</div>
