<?php if(_post('name') && _post('image')) {
	
	if(_post('name') !== null && _post('image') !== null)
	{	
		$sqlString = "INSERT INTO ".DB_PREFIX."conferences (`name`,`cover`,`description`,`moderator_id`) VALUES 
			('".toDb(_post('name'))."','".toDb(_post('image'))."','".toDb(_post('description'))."','".intval(user_id())."')";

		$db->query($sqlString);	
	}

} else { ?>

<div class="row clearfix ">
	<div id="formVid" class="col-md-12 pull-left well">
		<h3 style="display:block; margin:10px 20px"><?php echo _lang("New conference"); ?></h3>
		<form id="validate" class="form-horizontal styled" action="<?php echo admin_url('add-conference'); ?>" enctype="multipart/form-data" method="post">
			<div class="form-group form-material">
				<label class="control-label"><?php echo _lang("Conference cover image:"); ?></label>
				<div class="controls">
					<input type="text" id="image" name="image" class=" col-md-12" value="" placeholder="<?php echo _lang("Conference cover"); ?>">
					<span class="help-block" id="limit-text"><?php echo _lang("Conference cover image link"); ?></span>
				</div>
			</div>
			<div class="form-group form-material">
				<label class="control-label"><?php echo _lang("Conference room name:"); ?></label>
				<div class="controls">
					<input type="text" id="name" name="name" class=" col-md-12" value="" placeholder="<?php echo _lang("English Lesson"); ?>">
					<span class="help-block" id="limit-text"><?php echo _lang("Conference name"); ?></span>
				</div>
			</div>
			<div class="form-group form-material">
				<label class="control-label"><?php echo _lang("Conference description:"); ?></label>
				<div class="controls">
					<input type="text" id="description" name="description" class=" col-md-12" value="" placeholder="<?php echo _lang("English Lesson"); ?>">
					<span class="help-block" id="limit-text"><?php echo _lang("Conference description"); ?></span>
				</div>
			</div>
			<div class="form-group form-material">
				<button id="Subtn" class="btn btn-success btn-large pull-right" type="submit"><?php echo _lang("Continue"); ?></button>
			</div>
		</form>
	</div>
</div>	
<?php } ?>

