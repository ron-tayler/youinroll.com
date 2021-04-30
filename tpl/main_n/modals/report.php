<div class="modal fade" id="report-it" aria-hidden="true" aria-labelledby="report-it" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-sidebar modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">
                    <?php  echo _lang('Report video');?>
                </h4>
            </div>
            <div class="modal-body">
                <?php if(!is_user()){?>
                <p>
                    <?php  echo _lang('Please login in order to report media.');?>
                </p>
                <?php } elseif(is_user()){?>
                <div class="ajax-form-result"></div>
                <form class="horizontal-form ajax-form" action="
                        <?php echo site_url().'lib/ajax/report.php';?>" enctype="multipart/form-data" method="post">
                    <input type="hidden" name="id" value="
                            <?php  echo $entity->id;?>" />
                    <input type="hidden" name="type" value="<?=$entity->type;?>" />
                    <input type="hidden" name="token" value="
                                <?php  echo $_SESSION['token'];?>" />
                    <div class="control-group" style="border-top: 1px solid #fff;">
                        <label class="control-label">
                            <?php  echo _lang('Reason for reporting');?>:
                    </div>
                    <div class="controls">
                        <div class="checkbox-custom checkbox-primary">
                            <input type="checkbox" name="rep[]" value="
                                                <?php echo _lang('Media not playing');?>" class="checkbox-custom">
                            <label>
                                <?php echo _lang('Video not playing');?>
                            </label>
                        </div>
                        <div class="checkbox-custom checkbox-primary">
                            <input type="checkbox" name="rep[]" value="
                                                    <?php  echo _lang('Wrong title/description');?>" class="styled">
                            <label>
                                <?php  echo _lang('Wrong title/description');?>
                            </label>
                        </div>
                        <div class="checkbox-custom checkbox-primary">
                            <input type="checkbox" name="rep[]" value="
                                                        <?php  echo _lang('Media is offensive');?>" class="styled">
                            <label>
                                <?php echo _lang('Video is offensive');?>
                            </label>
                        </div>
                        <div class="checkbox-custom checkbox-primary">
                            <input type="checkbox" name="rep[]" value="
                                                            <?php  echo _lang('Media is restricted');?>"
                                class="styled">
                            <label>
                                <?php echo _lang('Video is restricted');?>
                            </label>
                        </div>
                        <div class="checkbox-custom checkbox-primary">
                            <input type="checkbox" name="rep[]" value="
                                                                <?php  echo _lang('Copyrighted material');?>"
                                class="styled">
                            <label>
                                <?php  echo _lang('Copyrighted material');?>
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <textarea rows="5" cols="3" name="report-text" class="form-control" required></textarea>
                        <p>
                            <strong>
                                <?php  echo _lang('Required'); ?>
                            </strong> :
                            <?php  echo _lang('Tell us what is wrong with the video in a few words');?>
                        </p>
                        <div class="row mtop20 bottom10">
                            <button class="btn btn-primary btn-block" type="submit">
                                <?php  echo _lang('Send report');?>
                            </button>
                        </div>
                    </div>
                </form>
                <?php } ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-block" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>