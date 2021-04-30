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
<div id='EditStreamModal' class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class='modal-title'><?=_lang('Edit Stream')?></h3>
            </div>

            <div class="modal-body">

                <div class='modal-inner'>
                    <div class="row odet">
                        <form id="streamSettings" action="" enctype="multipart/form-data" method="post">
                            <input type='hidden' name='id' value='<?=$streamId?>'/>
                            <div class="col-md-7">
                                <div class="control-group">
                                    <div class="form-group form-material floating">
                                        <input type="text" id="title" name="title" class="form-control" required=""
                                            value="">
                                        <label class="floating-label"><?=_lang("Title")?></label>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <div class="form-group">
                                        <label class="control-label"><?=_lang("Description")?></label>
                                        <textarea id="description" name="description" class=" form-control auto"
                                        ></textarea>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><?=_lang("Tags:")?></span>
                                            <div class="form-control withtags">
                                                <input type="text" id="tags" name="tags" class="tags form-control"
                                                    value="">
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
                                                <label class="control-label"
                                                    for="inputFile"><?=_lang("Choose thumbnail:")?></label>
                                                <input type="text" class="form-control" placeholder="<?_lang(" Browse
                                                    for image")?>"
                                                readonly="" />
                                                <input type="file" name="preview_image" id="preview_image" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="control-group blc bottom20">
                                <button id="editConf" class="btn btn-primary pull-left" type="submit">
                                    <?=_lang("Save conference")?>
                                </button>

                                <div class="clearfix"></div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>