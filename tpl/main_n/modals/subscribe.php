<style>
.modal-dialog {
    margin: 10% auto;
    color: black !important;
}
.modal-title > img {
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
    display:flex;
    justify-content: space-between;
    flex-direction: row;
    color: black !important;
}

.modal-inner h3 {
    margin-top: 0px;
}

.modal-inner > a {
    height: fit-content;
}

</style>

<?
if (!is_user()) {
    //It's guest
    $btnc = "btn btn-labled social-google-plus subscriber";

} elseif ($user <> user_id()) {

    $btnc = "btn btn-labled social-google-plus subscriber";
    
} else {
	
    if(is_video()) {
        $btnc = "btn btn-default subscriber";
    } elseif(is_picture()) {
        $btnc = "btn btn-default subscriber";
    } else {	
        //It's you
        $btnc = "btn btn-default subscriber";
        echo '<a href="'.site_url().'dashboard/" class="'.$btnc.'"><i class="icon icon-cogs"></i>'._lang('Settings').'</a>';
    }
}
?>
<div id='SubscribeModal' class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background-image: url('<?=$profile->cover?>')">
        <h3 class="modal-title">
            <img src='<?=$profile->avatar?>' /> <?=$profile->name?> 
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </h3>
      </div>
      <div class="modal-body">

        <div class='modal-inner'>
            <div class='col'>
                <h3>Оплатить премиум</h3>
                <p>Премиум подписка за 159р в месяц</p>
            </div>
            <form class="needs-validation" action="https://paymaster.ru/payment" novalidate="">
            <input name='LMI_MERCHANT_ID' type='hidden' value='4cae70bd-2415-4dc8-bb55-e4c3c9439d66' required/>
            <input name='LMI_PAYMENT_AMOUNT' type='hidden' value='159' required/>
            <input name='LMI_CURRENCY' type='hidden' value='RUB' required/>
            <input name='LMI_PAYMENT_DESC' type='hidden' value='Оплата премиума для: <?=$user?>' required/>
            <input name='LMI_PAYER_EMAIL' type='hidden' value='<?=$user?>'/>
            <? if(is_user()) { ?>
            <a href="/payment" style="color:white !important" class="<?=$btnc?>" type="submit">Подписаться*</a>
            <? } else { ?>
            <a class="<?=$btnc?>" href="/login">Подписаться*</a>  
            <?}?>
            </form>
        </div>
        <center><small>Эта подписка напрямую поддержит автора</small></center>
        <hr />
        
        <center><a class="btn btn-lg btn-primary" href='https://youinroll.com/read/dobro-pozhalovat/93/'>Подробнее</a></center>
        <!-- <p>Modal body text goes here.</p> -->
        
        
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>