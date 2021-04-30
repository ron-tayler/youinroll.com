<?php the_sidebar(); 
$active = com();

$confType = $globalTemplateVariable;

if(isset($_POST['name']))
{
	$sqlString = "INSERT INTO ".DB_PREFIX."conferences (`name`,`cover`,`description`,`moderator_id`) VALUES 
		('".toDb(_post('name'))."','".toDb(_post('image'))."','".toDb(_post('description'))."','".intval(user_id())."')";

	$db->query($sqlString);

	$result = true;
}
?>
<style>
.hm-gradient {
    background-image: linear-gradient(to top, #f3e7e9 0%, #e3eeff 99%, #e3eeff 100%);
}

.darken-grey-text {
    color: #2E2E2E;
}

.danger-text {
    color: #ff3547;
}

.default-text {
    color: #2BBBAD;
}

.info-text {
    color: #33b5e5;
}

.form-white .md-form label {
    color: #fff;
}

.form-white input[type=text]:focus:not([readonly]) {
    border-bottom: 1px solid #fff;
    -webkit-box-shadow: 0 1px 0 0 #fff;
    box-shadow: 0 1px 0 0 #fff;
}

.form-white input[type=text]:focus:not([readonly])+label {
    color: #fff;
}

.form-white input[type=password]:focus:not([readonly]) {
    border-bottom: 1px solid #fff;
    -webkit-box-shadow: 0 1px 0 0 #fff;
    box-shadow: 0 1px 0 0 #fff;
}

.form-white input[type=password]:focus:not([readonly])+label {
    color: #fff;
}

.form-white input[type=password],
.form-white input[type=text] {
    border-bottom: 1px solid #fff;
}

.form-white .form-control:focus {
    color: #fff;
}

.form-white .form-control {
    color: #fff;
}

.form-white textarea.md-textarea:focus:not([readonly]) {
    border-bottom: 1px solid #fff;
    box-shadow: 0 1px 0 0 #fff;
    color: #fff;
}

.form-white textarea.md-textarea {
    border-bottom: 1px solid #fff;
    color: #fff;
}

.form-white textarea.md-textarea:focus:not([readonly])+label {
    color: #fff;
}

.ripe-malinka-gradient {
    background-image: linear-gradient(120deg, #f093fb 0%, #f5576c 100%);
}

.near-moon-gradient {
    background-image: linear-gradient(to bottom, #5ee7df 0%, #b490ca 100%);
}
</style>
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
</ul>
<div id="default-content" class="share-media">
    <div class="row odet isBoxed">
		<?=$error?>
        <div id="formVid block">
            <h1><?=_lang("Create $confType")?></h1>
            <form method='POST' class="row" action="<?=site_url()?>addconf"
                enctype="multipart/form-data">
                <div class="col-md-7">
                    <input type="hidden" name="type" id="type" value="<?=$confType?>" readonly />
                    <div class="control-group">
                        <div class="form-group form-material floating">
                            <input type="text" id="title" name="title" class="form-control" required="" value="">
                            <label class="floating-label"><?=_lang("Title")?></label>
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="form-group">
                            <label class="control-label"><?=_lang("Description")?></label>
                            <textarea id="description" name="description" class=" form-control auto"
                                required=""></textarea>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><?=_lang("Tags:")?></span>
                                <div class="form-control withtags">
                                    <input type="text" id="tags" name="tags" class="tags form-control" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label"><?=_lang("Conference Options:")?></label>
                        <div class="controls row">
                            <div class="col-md-2 col-xs-3">
                                <div class="input-group">
                                    <span class="input-group-addon"><?=_lang("Started At")?></span>
                                    <input type="date" class="form-control" min="<?=date('d-m-Y',time())?>"
                                        name="started_at" value="">
									<input type="time" class="form-control" min=""
                                        name="time" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-md-offset-1">
                    <div class="row">
                        <div class="control-group">
                            <label class="control-label"><?=_lang("Conference category:")?></label>
                            <div class="form-group form-material floating">
                                <?=cats_select('categ','select',' form-control')?>
                            </div>
                        </div>
                        <div class="control-group mtop20">
                            <div class="controls " style="padding-left:3px; ">
                                <div class="form-group form-material">
                                    <label class="control-label" for="inputFile"><?=_lang("Choose thumbnail:")?></label>
                                    <input type="text" class="form-control" placeholder="<?_lang(" Browse for image")?>"
                                    readonly="" />
                                    <input type="file" name="preview_image" id="preview_image" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="control-group blc bottom20">
                    <button class="btn btn-primary pull-right" type="submit">
                        <?=_lang("Save conference")?>
                    </button>

                    <div class="clearfix"></div>
                </div>
            </form>
        </div>
        <div class="clearfix"></div>
    </div>
</div>