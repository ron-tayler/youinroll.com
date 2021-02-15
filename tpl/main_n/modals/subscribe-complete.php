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
<div id='SubscribeModal' class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" style="width:62%" role="document">
        <div class="modal-content">
            <div class="modal-body">

                <div class='modal-inner'>
                    <div class="row">
                        <div class="col order-md-1">
                            <form class="needs-validation" action="https://youinroll.com/payment" method="POST" novalidate="">

                                <input type='hidden' name="buySubscribe" id="buySubscribe">

                                <h4 class="mb-3">Оплата подписки</h4>

                                <div class="d-block my-3">
                                    <div id="pricing">
                                        <div class="row slideanim">
                                            <div class="price-col active col-md-4"  data-price="159р" data-month="1">
                                                <div class="panel panel-default text-left">
                                                    <h5>Обновляется ежемесячно</h5>
                                                    <div class='panel-price'>
                                                        <div style='position:relative'>
                                                            <small>250р</small>
                                                            <small>-10%</small>
                                                            <small>159р</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="price-col col-md-4" data-price="430р" data-month="3">
                                                <div class="panel panel-default text-left">
                                                    <h5>Обновляется каждые 3 месяца</h5>
                                                    <div class='panel-price'>
                                                        <div style='position:relative'>
                                                            <small>477р</small>
                                                            <small>- 10%</small>
                                                            <small>430р</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="price-col col-md-4" data-price="811р" data-month="6">
                                                <div class="panel panel-default text-left">
                                                    <h5>Обновляется каждые 6 месяцев</h5>
                                                    <div class='panel-price'>
                                                        <div style='position:relative'>
                                                            <small>954р</small>
                                                            <small>- 15%</small>
                                                            <small>811р</small>
                                                        </div>
                                                    </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type='hidden' name="monthes" id="monthInput">
                                        </div>
                                    </div>
                                </div>

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
                                                <input id="card" value="card" type="radio" name="payment">
                                                Оплата картой
                                            </div>
                                        </label>
                                        <label for="paymaster" class="method paymaster" data-type='paymaster'>
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
                                        </label>
                                    </div>
                                </div>
                                <hr class="mb-4">
                                <!-- <div id='type-card'>
                                    <div class="credit-card-wrapper">
                                        <div class="credit-card front">
                                            <div class="card-title">Реквизиты карты</div>

                                            <div class="card-number">
                                                <div class="card-number-label">Номер</div>
                                                <div class="card-numbers">
                                                    <input name='number' type="tel" autocomplete="cc-number" maxlength="19" inputmode="numeric" pattern="[0-9\s]{13,19}" class="tbx-number" maxlength='18' />
                                                </div>
                                            </div>

                                            <div class="card-expiration">
                                                <div class="card-expiration-label">
                                                    <div>Действует</div>
                                                    <div>до</div>
                                                </div>
                                                <div class="card-expiration-month">
                                                    <div class="card-expiration-month-label">Месяц</div>
                                                    <select name='month' class='drp-expiration-month'>
                                                        <option value="01">01</option>
                                                        <option value="02">03</option>
                                                        <option value="03">03</option>
                                                        <option value="04">04</option>
                                                        <option value="05">05</option>
                                                        <option value="06">06</option>
                                                        <option value="07">07</option>
                                                        <option value="08">08</option>
                                                        <option value="09">09</option>
                                                        <option value="10">10</option>
                                                        <option value="11">11</option>
                                                        <option value="12">12</option>
                                                    </select>
                                                </div>
                                                <div class="card-expiration-slash">/</div>
                                                <div class="card-expiration-year">
                                                    <div class="card-expiration-year-label">Год</div>
                                                    <select class='drp-expiration-year' name='year'>
                                                        <option value="21">21</option>
                                                        <option value="22">22</option>
                                                        <option value="23">23</option>
                                                        <option value="24">24</option>
                                                        <option value="25">25</option>
                                                        <option value="26">26</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="credit-card back unfocused">
                                            <div class="magnetic-strip"></div>
                                            <div class="credit-card-ccv">
                                                <div class="credit-card-ccv-label">CVV</div>
                                                <div class="credit-card-ccv-strip">
                                                    <div class="credit-card-ccv-box"></div>
                                                    <input type="text" class="tbx-ccv" name='cvv' maxlength='3'/>
                                                    <div class="credit-card-ccv-prefix">XXX</div>
                                                </div>
                                            </div>
                                            <div class='cc-row'>
                                                <div class="credit-card-name">
                                                    <div class="credit-card-name-box"></div>
                                                    <input name='name' type="text" class="form-control  tbx-name" />
                                                    <div class="credit-card-name-prefix">Имя</div>
                                                </div>
                                                <div class="credit-card-lastname">
                                                    <div class="credit-card-lastname-box"></div>
                                                    <input name='lastname' type="text" class="form-control tbx-lastname" />
                                                    <div class="credit-card-lastname-prefix">Фамилия</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> -->
                                <hr class="mb-4">
                                <div class='d-block my-3 step last'>
                                    <div class='row' style='display:inline-flex'>
                                        <input type="checkbox" name="agree">
                                        <label>Я подтверждаю, что YouInRoll может автоматически осуществлять регулярные списания, включая применимые налоги. Я могу в любое время изменить или отменить это действие, обратившись в Службу поддержки YouInRoll - youinroll.com Частичный возврат средств не предусмотрен.</label>
                                    </div>                                    
                                    <div class='row'>
                                        <input type="checkbox" name="agree2">
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
                                            <th>Срок действия</th>
                                            <th>Цена</th>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Подписка</td>
                                                <td class='receipt-date'><?=date('Y-m-d', strtotime("+1 month"))?></td>
                                                <td class='receipt-price'>159р</td>
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