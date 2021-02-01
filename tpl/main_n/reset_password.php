<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8">
    <title><?=seo_title()?></title>
    <meta charset="UTF-8">  
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <base href="<?=site_url()?>" />  
    <meta name="description" content="<?=seo_desc()?>">
    <meta name="generator" content="PHPVibe" />
    <meta property="og:site_name" content="<?=get_option('site-logo-text')?>" />
    <meta property="fb:app_id" content="<?=Fb_Key?>" />
    <meta property="og:url" content="<?=canonical()?>" />
    <link rel="apple-touch-icon" sizes="180x180" href="lib/favicos/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="lib/favicos/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="lib/favicos/favicon-16x16.png">
    <link rel="manifest" href="lib/favicos/site.webmanifest">
    <link rel="mask-icon" href="lib/favicos/safari-pinned-tab.svg" color="#5bbad5">
    <link rel="shortcut icon" href="lib/favicos/favicon.ico">
    <meta name="msapplication-TileColor" content="#2b5797">
    <meta name="msapplication-config" content="lib/favicos/browserconfig.xml">
    <meta name="theme-color" content="#ffffff">
    <link rel='stylesheet' href="/tpl/main_n/styles/loginAssets/bootstrap.css">
    <link rel='stylesheet' href="tpl/main_n/styles/login.css">
</head>
<body>
<div class="container">
  <div class="form" id='resetpass'>
    <div class="sign-in-section">
      <h2 class='text-center'>Забыли пароль в</h2><h1>YouInRoll</h1>
      <div class='d-flex justify-content-center mt-2'>
        <small class='text-center text-secondary mt-2'>Находите новые идеи в <br>образовании</small>
      </div>
      <form action="<?=site_url()?>login" method='post' class='scrollbar scrollbar-secondary'>
        <br>
        <div class="form-field mt-2">
          <input id="email" name="email" type="email" placeholder="E-mail" />
        </div>
        <div class="form-field mt-2">
          <input class="btn btn-signin" value="Продолжить" />
        </div>
      </form>
    </div>
    <div class="form-busines mt-2">
        <input class="btn btn-busines" value="Назад" />
    </div>
  </div>
</div>
<div class="area" >
    <ul class="circles">
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
    </ul>
</div >
</body>
<footer>
<script src="https://code.jquery.com/jquery-2.1.0.min.js"></script>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script>

document.addEventListener('DOMContentLoaded', function(){

    $.get(
        'lib/ajax/getRandomAuthor.php',
        function(data){
            let authors = JSON.parse(data);

            console.log(authors);

            $('.circles > li').each(function(){
                let randomAuthor =  Math.floor(Math.random() * Math.floor(authors.length));
                $(this).css(`background-image`,`url('${authors[randomAuthor].avatar}')`);
            });
        }
    )

    $('.btn-busines').on('click', function(){
        location.href = '/login'
    })

    $('.btn-signin').on('click', function(e){
        e.preventDefault();

        $(`input`).removeClass('is-invalid');
        $('small').remove();

        $.post(
            'lib/ajax/forgetPassword.php',
            $('form').serialize(),
            function(data)
            {
                let response = JSON.parse(data);

                if(response.result !== '')
                {
                    let timeLeft = parseInt($('.btn-signin').val()); 

                    $(`input[name="email"]`).parent().append(`<small class="form-text text-muted">${response.result}</small>`)

                    $('.btn-signin').attr('disabled', true);

                    var timer = setInterval(function(){
                        if (--timeLeft >= 0) { // если таймер всё еще больше нуля
                            $('.btn-signin').val(timeLeft) // обновляем цифру
                        } else {
                            clearInterval(timer) // удаляем таймер

                            $('.btn-signin').val('Отправить');
                            $('.btn-signin').attr('disabled', false);
                        }
                    }, 1000)

                }

                if(response.errors !== null)
                {
                    for (const key in response.errors)
                    {
                        $(`input[name="${key}"]`).parent().append(`<small class="form-text text-muted">${response.errors[key]}</small>`)
                        $(`input[name="${key}"]`).addClass('is-invalid')
                    }
                }

            }
        )
    })

    setTimeout(() => {
        setInterval(() => {
            $.get(
                'lib/ajax/getRandomAuthor.php',
                function(data){
                    let authors = JSON.parse(data);

                    $('.circles > li').each(function(){
                        let randomAuthor =  Math.floor(Math.random() * Math.floor(authors.length));
                        $(this).css(`background-image`,`url('${authors[randomAuthor].avatar}')`);
                    });
                }
            )
        }, 25000);
    }, 30000);
    
})
</script>
</footer>
</html>