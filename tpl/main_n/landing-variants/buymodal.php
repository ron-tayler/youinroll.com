<!-- The Modal -->
<div class="modal bd-example-modal-lg fade" id="buyCourse">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title w-100 text-center">Записаться на курс</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <!-- adding Bootstrap Form here -->

                <form id="myBuyForm" action="https://youinroll.com/payment" method='POST' class="needs-validation">

                    <input id="course" value="<?=$info->id?>" type="hidden" name="course">
                    <input value="true" type="hidden" name="buyLandingCourse">
                    <input value="card" type="hidden" name="payment">

                    <div class="container">
                        <div class='form-check'>
                            <input class='form-check-input' type="checkbox" name="agree" required='true'>
                            <label class='form-check-label'>Я подтверждаю, что YouInRoll может автоматически осуществлять регулярные списания, включая применимые налоги. Я могу в любое время изменить или отменить это действие, обратившись в Службу поддержки YouInRoll - youinroll.com Частичный возврат средств не предусмотрен.</label>
                        </div>                                    
                        <div class='form-check'>
                            <input class='form-check-input' type="checkbox" name="agree2" required='true'>
                            <label class='form-check-label'>Я принимаю условия <a style="text-decoration:underline" href="https://youinroll.com/read/usloviya-ispolzovaniya/1/">использования</a></label>
                        </div>
                        <hr class="mb-4">
                        <div class='row'>
                            <p>
                            Выбирая «Завершить покупку», вы подтверждаете свое согласие с Условиями продажи и применимыми пунктами Политики конфиденциальности YouInRoll. Выбранный вами способ оплаты будет сохранен для совершения покупок в дальнейшем и (если применимо) для продления подписок.
                            </p>
                        </div>
                        <hr class="mb-4">
                        <h3><?=_html($info->title)?> - <span class='badge badge-success'><?=$info->prices?>р</span></h3>
                        <hr class="mb-4">
                        <div class="text-center">
                            <? if(is_user()) {?>
                                <button class="btn btn-success" type="submit">Перейти на страницу оплаты</button>
                            <?} else {?>
                                <a href="https://youinroll.com/login?backurl=/landing/<?=$info->id?>" class="btn btn-success" >Войти</a>
                            <?}?>

                        </div>
                    </div>
                </form>

            </div>

        </div>
    </div>
</div>