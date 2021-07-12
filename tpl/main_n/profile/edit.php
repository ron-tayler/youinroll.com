<?
    $token = md5(user_name().user_id().time());
?>

<div class="row clearfix">
    <div class="nav-tabs-horizontal">
        <ul class="nav nav-tabs nav-tabs-line" data-plugin="nav-tabs" role="tablist">
            <li class="active" role="presentation"><a data-toggle="tab" href="#avatarTab" aria-controls="avatarTab"
                    role="tab"><?php echo _lang("Details");?></a></li>
            <li role="presentation"><a data-toggle="tab" href="#textTab" aria-controls="textTab"
                    role="tab"><?php echo _lang("Branding");?></a></li>
        </ul>

        <!-- Details -->
        <div class="tab-content padding-top-20">
            <div class="tab-pane active" id="avatarTab" role="tabpanel">
                <div class='panel panel-transparent'>
                    <form autocomplete="off" action="<?php echo site_url();?>dashboard/" enctype="multipart/form-data"
                        method="post">
                        <input type="hidden" name="changeuser" class="hide" value="1" />
                        <div class="panel-heading">
                            <h4 class="panel-title"><?php echo _lang("Channel Name"); ?></h4>
                        </div>
                        <div class="panel-body">
                            <div class="form-group form-material floating">
                                <input type="text" name="name" class="form-control" name="inputFloatingText"
                                    value="<?php echo user_name();?>" />
                            </div>
                        </div>
                        <div class="panel-heading">
                            <h4 class="panel-title"><?php echo _lang("From city"); ?></h4>
                        </div>
                        <div class="panel-body">
                            <div class="form-group form-material floating">
                                <input type="text" name="city" class="form-control"
                                    value="<?php echo _html($profile->local);?>" />
                            </div>
                        </div>

                        <div class="panel-heading">
                            <h4 class="panel-title"><?php echo _lang("From country"); ?></h4>
                        </div>
                        <div class="panel-body">
                            <div class="form-group form-material floating">
                                <input type="text" name="country" class="form-control"
                                    value="<?php echo _html($profile->country);?>" />
                            </div>
                        </div>

                        <div class="panel-heading">
                            <h4 class="panel-title"><?php echo _lang("Channel about"); ?></h4>
                        </div>
                        <div class="panel-body">
                            <div class="form-group form-material floating">
                                <textarea class="form-control" rows="3"
                                    name="bio"><?php echo _html($profile->bio); ?></textarea>
                            </div>
                        </div>

                        <div class='panel-body'>
                            <div class="btn-group" data-toggle="buttons" role="group">
                                <label
                                    class="btn btn-outline btn-primary <?php if($profile->gender < 2) { ?>active<?php } ?>">
                                    <input type="radio" name="gender" autocomplete="off" value="1"
                                        <?php if($profile->gender < 2) { ?>checked="checked" <?php } ?> />
                                    <i class="icon icon-check text-active" aria-hidden="true"></i>
                                    <?php echo _lang("Male"); ?>
                                </label>
                                <label
                                    class="btn btn-outline btn-primary <?php if($profile->gender > 1) { ?>active<?php } ?>">
                                    <input type="radio" name="gender" autocomplete="off" value="2"
                                        <?php if($profile->gender > 1) { ?>checked="checked" <?php } ?> />
                                    <i class="icon icon-check text-active" aria-hidden="true"></i>
                                    <?php echo _lang("Female"); ?>
                                </label>
                            </div>
                        </div>

                        <div class="panel-heading">
                            <h4 class="panel-title"><?php echo _lang("Facebook profile link"); ?></h4>
                        </div>
                        <div class="panel-body">
                            <div class="form-group input-group form-material floating">
                                <input type="text" name="f-link"
                                    class="form-control <?php if(nullval($profile->fblink)) {echo "empty";} ?>"
                                    value="<?php echo _html($profile->fblink); ?>"
                                    data-hint="<?php echo _lang('Optional. If added it will be visible on your profile');?>" />
                                <span class="input-group-btn">
                                    <a href="https://www.facebook.com" target="_blank"
                                        title="<?php echo _lang("To Facebook"); ?>"
                                        class="btn btn-icon social-facebook tipS tooltip-scale" data-placement="left">
                                        <i class="material-icons bd-facebook">&#xE157;</i>
                                    </a>
                                </span>
                            </div>
                        </div>

                        <div class="panel-heading">
                            <h4 class="panel-title"><?php echo _lang("Instagram profile link"); ?></h4>
                        </div>
                        <div class="panel-body">
                            <div class="form-group input-group form-material floating">
                                <input type="text" name="ig-link"
                                    class="form-control <?php if(nullval($profile->iglink)) {echo "empty";} ?>"
                                    value="<?php echo _html($profile->iglink); ?>"
                                    data-hint="<?php echo _lang('Optional. If added it will be visible on your profile');?>" />
                                <span class="input-group-btn">
                                    <a href="http://www.instagram.com" target="_blank"
                                        title="<?php echo _lang("To Instagram"); ?>"
                                        class="btn btn-icon social-instagram tipS tooltip-scale"
                                        data-placement="left"><i class="material-icons bd-instagram">&#xE157;</i>
                                    </a>
                                </span>
                            </div>
                        </div>

                        <div class="panel-heading">
                            <h4 class="panel-title"><?php echo _lang("Twitter profile link"); ?></h4>
                        </div>
                        <div class="panel-body">
                            <div class="form-group input-group form-material floating">
                                <input type="text" name="tw-link"
                                    class="form-control <?php if(nullval($profile->twlink)) {echo "empty";} ?>"
                                    value="<?php echo _html($profile->twlink); ?>"
                                    data-hint="<?php echo _lang('Optional. If added it will be visible on your profile');?>" />
                                <span class="input-group-btn">
                                    <a href="http://www.twitter.com" target="_blank"
                                        title="<?php echo _lang("To Twitter"); ?>"
                                        class="btn btn-icon social-twitter tipS tooltip-scale" data-placement="left"><i
                                            class="material-icons bd-instagram">&#xE157;</i></a>
                                </span>
                            </div>
                        </div>

                        <div class="panel-heading">
                            <h4 class="panel-title"><?php echo _lang("Vk profile link"); ?></h4>
                        </div>
                        <div class="panel-body">
                            <div class="form-group input-group form-material floating">
                                <input type="text" name="vk-link" autocomplete="off"
                                    class="form-control <?php if(nullval($profile->vklink)) {echo "empty";} ?>"
                                    value="<?php echo _html($profile->vklink); ?>"
                                    data-hint="<?php echo _lang('Optional. If added it will be visible on your profile');?>" />
                                <span class="input-group-btn">
                                    <a href="http://www.vk.com" target="_blank" title="<?php echo _lang("To Vk"); ?>"
                                        class="btn btn-icon social-twitter tipS tooltip-scale" data-placement="left"><i
                                            class="material-icons bd-vk">&#xE157;</i></a>
                                </span>
                            </div>
                        </div>

                        <div class="panel-heading">
                            <h4 class="panel-title"><i class="icon icon-key"></i><?php echo _lang("Change password");?>
                            </h4>
                        </div>
                        <div class="panel-body">

                            <div class="form-group form-material floating">
                                <?php if(isset($_SESSION['loggedfrommail'])) { ?>
                                <input type="text" class="form-control" name="oldpassword"
                                    value="<?php echo _lang("Changing from mail recovery");?>" disabled />
                                <?php } else { ?>
                                <input type="password" class="form-control" name="oldpassword" />
                                <?php } ?>
                                <label class="floating-label"><?php echo _lang("Current password"); ?></label>
                            </div>
                            <div class="form-group form-material floating">
                                <input type="password" class="form-control" name="pass1" id="pass1" />
                                <label class="floating-label"><?php echo _lang("New password"); ?></label>
                            </div>
                            <div class="form-group form-material floating">
                                <input type="password" class="form-control" name="pass2" data-match="#pass1"
                                    data-match-error="<?php echo _lang("Passwords do not match"); ?>" />
                                <label class="floating-label"><?php echo _lang("Repeat password"); ?></label>
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>

                        <div class="block mtop20">
                            <button class="btn btn-block  btn-primary" style="color: #fff !important;"
                                type="submit"><?php echo _lang("Update"); ?></button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- End Details -->

            <!-- Branding -->
            <div class="tab-pane" id="textTab" role="tabpanel">
                <form action="<?php echo site_url();?>dashboard/" enctype="multipart/form-data" method="post">
                    <div class="panel panel-transparent">
                        <div class="panel-heading">
                            <h4 class="panel-title"><i
                                    class="icon icon-upload"></i><?php echo _lang("Change picture");?></h4>
                        </div>
                        <div class="panel-body">
                            <input type="hidden" id="changeavatar" name="changeavatar" value="yes" />
                            <div class="Pimg mbot10 row text-center">
                                <img class="img-responsinve" style="max-height:180px;" id="targetImg"
                                    src="<?php echo thumb_fix(user_avatar());?>" />
                            </div>
                            <div class="form-group form-material">
                                <label class="control-label"
                                    for="inputFile"><?php echo _lang("Choose picture");?></label>
                                <input type="text" class="form-control" placeholder="<?php echo _lang("Browse...");?>"
                                    readonly="" />
                                <input type="file" id="imgInp" name="avatar" accept=".jpg,.png,.jpeg,.PNG,.JPG,.JPG" />
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-transparent">
                        <div class="panel-heading">
                            <h4 class="panel-title"><i class="icon icon-upload"></i><?php echo _lang("Change cover");?>
                            </h4>
                        </div>
                        <div class="panel-body">
                            <input type="hidden" id="changecover" name="changecover" value="yes" />
                            <div class="Pimg mbot10 row text-center">
                                <img class="img-responsinve" style="width:100%; height:320px;" id="targetImg"
                                    src="<?php echo thumb_fix($profile->cover);?>" />
                            </div>
                            <div class="form-group form-material">
                                <label class="control-label" for="inputFile"><?php echo _lang("Choose cover");?></label>
                                <input type="text" class="form-control" placeholder="<?php echo _lang("Browse...");?>"
                                    readonly="" />
                                <input type="file" id="imgInp" name="cover"
                                    accept=".jpg,.png,.jpeg,.gif,.PNG,.JPG,.JPG" />
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-transparent">
                        <div class="panel-heading">
                            <h4 class="panel-title"><i
                                    class="icon icon-upload"></i><?php echo _lang("Change branding picture");?></h4>
                        </div>
                        <div class="panel-body">
                            <input type="hidden" id="changelanding_cover" name="changelanding_cover" value="yes" />
                            <div class="Pimg mbot10 row text-center">
                                <img class="img-responsinve" style="width:100%; height:320px;" id="targetImg"
                                    src="<?php echo thumb_fix($profile->landing_cover);?>" />
                            </div>
                            <div class="form-group form-material">
                                <label class="control-label" for="inputFile"><?php echo _lang("Choose cover");?></label>
                                <input type="text" class="form-control" placeholder="<?php echo _lang("Browse...");?>"
                                    readonly="" />
                                <input type="file" id="imgInp" name="landing_cover"
                                    accept=".jpg,.png,.jpeg,.gif,.PNG,.JPG,.JPG" />
                            </div>
                        </div>

                        <div class="panel-heading">
                            <h4 class="panel-title"><i class="icon icon-upload"></i><?php echo _lang("Landing video");?>
                            </h4>
                        </div>
                        <div class="panel-body">
                           <a href='https://youinroll.com/add-video?for=landing'><?=_lang('Upload video')?></a>
                        </div>

                        <div class="block mtop20">
                            <button class="btn btn-block  btn-primary" style="color: #fff !important;"
                                type="submit"><?php echo _lang("Update"); ?></button>
                        </div>
                </form>
            </div>
        </div>
        <!-- End Branding -->

    </div>
</div>




</div>
