<div class="py-5 text-center">
    <h2>Checkout form</h2>
    <p class="lead">Below is an example form built entirely with Bootstrap's form controls. Each required form group
        has a validation state that can be triggered by attempting to submit the form without completing it.</p>
</div>

<div class="row">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">


    <div class="credit-card-wrapper">
        <div class="credit-card front">
            <div class="card-title">Реквизиты карты</div>

            <div class="card-number">
                <div class="card-number-label">Номер</div>
                <div class="card-numbers">
                    <input type="text" class="tbx-number" maxlength='18' />
                </div>
            </div>

            <div class="card-expiration">
                <div class="card-expiration-label">
                    <div>Действует</div>
                    <div>до</div>
                </div>
                <div class="card-expiration-month">
                    <div class="card-expiration-month-label">Месяц</div>
                    <select class='drp-expiration-month'>
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
                    <select class='drp-expiration-year'>
                        <option value="15">15</option>
                        <option value="16">16</option>
                        <option value="17">17</option>
                        <option value="18">18</option>
                        <option value="19">19</option>
                        <option value="20">20</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="credit-card back unfocused">
            <div class="magnetic-strip"></div>
            <div class="credit-card-ccv">
                <div class="credit-card-ccv-label">CCV</div>
                <div class="credit-card-ccv-strip">
                    <div class="credit-card-ccv-box"></div>
                    <input type="text" class="tbx-ccv" maxlength='3' />
                    <div class="credit-card-ccv-prefix">XXX</div>
                </div>
            </div>
        </div>
    </div>

</div>