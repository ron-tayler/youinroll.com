<style>
.modal-dialog {
    margin: 10% auto;
    color: black !important;
}

.modal-title {
    color: black !important;
    font-weight: bold;
    margin-left: 5px;
}

.close {
    float: right;
}

.modal-inner {
    display: flex;
    justify-content: space-between;
    flex-direction: row;
    color: black !important;
}

.modal-inner h3 {
    margin-top: 0px;
}

.modal-inner>a {
    height: fit-content;
}
</style>
<div id='ShareModal' class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-body">

                <div class='modal-inner'>
                    <div class="sharing-icos mtop10 odet">
                        <?php do_action('before-social-box'); ?>
                        <div class="has-shadow">
                            <div class="text-center text-uppercase full bottom10 top20">
                                <h4><?php  echo _lang('Let your friends enjoy it also!');?></h4>
                            </div>
                            <div id="jsshare" data-url="<?php echo $canonical; ?>"
                                data-title="<?php echo _cut($streaminfo->name, 40); ?>">
                            </div>
                        </div>
                        <div class="video-share mtop10 has-shadow right20 left20 clearfix">
                            <div class="text-center text-uppercase full bottom20 top20">
                                <h4><?php  echo _lang('Add it to your website');?></h4>
                            </div>
                            <div class="form-group form-material floating">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="material-icons">&#xE157;</i>
                                    </span>
                                    <div class="form-control-wrap">
                                        <input type="text" name="link-to-this" id="share-this-link" class="form-control"
                                            title="<?php echo _lang('Link back');?>"
                                            value="<?php echo canonical();?>" />
                                        <label class="floating-label">
                                            <?php  echo _lang('Link to this');?>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>