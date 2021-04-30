<style>
.modal-backdrop {
  display:none;
}

.modal-dialog {
    margin: 10% auto;
    color: black !important;
}

.modal-title>img {
    width: 50px;
    height: 50px;
    border-radius: 50%;
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
<div id='addParticipantModal' class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title">
            Добавить участника
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </h3>
      </div>
      <div class="modal-body">

        <div class='modal-inner'>
        <input class='form-control' placeholder='Найти участника'/>
        </div>
        
        
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>