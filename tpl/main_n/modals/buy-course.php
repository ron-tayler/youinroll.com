<style>
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
<div id='BuyCourseModal' class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">

                <div class='modal-inner'>
                    <div class="row">
                        <div class="col order-md-1">
                            <form class="needs-validation" action="https://youinroll.com/payment" method="POST">

                                <h4 class="mb-3">Оплата курса <?=_html($playlist->title)?></h4>

                                <input id="course" value="<?=$playlist->id?>" type="hidden" name="course">
                                <input value="true" type="hidden" name="buyCourse">

                                <hr class="mb-4">
                                <div class="d-block my-3 step choose">
                                    <div class="payment-method">
                                        <label for="card" class="method card" data-type='card'>
                                            <div class="card-logos">
                                                <img
                                                    src="https://designmodo.com/demo/checkout-panel/img/visa_logo.png" />
                                                <img
                                                    src="https://designmodo.com/demo/checkout-panel/img/mastercard_logo.png" />
                                            </div>

                                            <div class="radio-input">
                                                <input id="card" value="card" type="radio" name="payment" checked>
                                                Оплата картой
                                            </div>
                                        </label>
                                        <!-- <label for="paymaster" class="method paymaster" data-type='paymaster'>
                                            <img src="https://designmodo.com/demo/checkout-panel/img/paypal_logo.png" />
                                            <div class="radio-input">
                                                <input id="paymaster" value="paymaster" type="radio" name="payment">
                                                PayMaster
                                            </div>
                                        </label>
                                        <label for="other" class="method other" data-type='other'>
                                            <img src="https://designmodo.com/demo/checkout-panel/img/paypal_logo.png" />
                                            <div class="radio-input">
                                                <input id="other" value="other" type="radio" name="payment">
                                                Другие методы
                                            </div>
                                        </label> -->
                                    </div>
                                </div>
                                <hr class="mb-4">
                                <div class='d-block my-3 step last'>
                                    <div class='row' style='display: inline-block'>
                                        <input type="checkbox" name="agree" required='true'>
                                        <label>Я подтверждаю, что YouInRoll может автоматически осуществлять регулярные списания, включая применимые налоги. Я могу в любое время изменить или отменить это действие, обратившись в Службу поддержки YouInRoll - youinroll.com Частичный возврат средств не предусмотрен.</label>
                                    </div>                                    
                                    <div class='row'>
                                        <input type="checkbox" name="agree2" required='true'>
                                        <label>Я принимаю условия <a style="text-decoration:underline" href="https://youinroll.com/read/usloviya-ispolzovaniya/1/">использования</a></label>
                                    </div>
                                    <div class='row'>
                                        <p>
                                        Выбирая «Завершить покупку», вы подтверждаете свое согласие с Условиями продажи и применимыми пунктами Политики конфиденциальности YouInRoll. Выбранный вами способ оплаты будет сохранен для совершения покупок в дальнейшем и (если применимо) для продления подписок.
                                        </p>
                                    </div>
                                    <hr class="mb-4">
                                    <table class='receipt'>
                                        <thead>
                                            <th>Наименование</th>
                                            <th>Цена</th>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><?=_html($playlist->title)?></td>
                                                <td class='receipt-price'><?=_html($playlist->price)?>р</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <hr class="mb-4">
                                    <button class="btn btn-primary btn-lg btn-block" type="submit">Завершить покупку</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>