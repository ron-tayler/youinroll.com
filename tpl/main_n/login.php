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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/1.5.0/css/perfect-scrollbar.css" integrity="sha512-2xznCEl5y5T5huJ2hCmwhvVtIGVF1j/aNUEJwi/BzpWPKEzsZPGpwnP1JrIMmjPpQaVicWOYVu8QvAIg9hwv9w==" crossorigin="anonymous" />
    <link rel='stylesheet' href="tpl/main_n/styles/login.css">
</head>
<body>
<div class="container">
  <div class="form" id='login'>
    <div class="sign-in-section">
      <h2 class='text-center'>Добро пожаловать в</h2><h1>YouInRoll</h1>
      <div class='d-flex justify-content-center mt-2'>
        <!-- <small class='text-center text-secondary mt-2'>Находите новые идеи в <br>образовании</small> -->
      </div>
      <br>
      <form action="<?=site_url()?>register" method='post'>
        <div class="form-field">
          <input id="email" name="email" type="email" placeholder="Email" />
        </div>
        <div class="form-field">
          <input id="password" type="password" name="password" placeholder="Пароль" />
        </div>
        <small class='ml-3 mt-2 mb-2'><a href='resetpassword' style="font-weight:bold">Забыли пароль?</a></small>
        <div class="form-field mt-2">
          <button class="btn btn-signin">Войти</button>
        </div>
        <div class="d-flex justify-content-center mt-2">
            <span class="center text-dark">Или</span>
        </div>
        <ul class='mt-2 mb-2'>
            <li style="background-color: #80808038;">
                <a href="<?=site_url()?>?action=login&type=google" class="social-google-plus " style="margin-left: -75px;">
                <img height="45" src="https://i1.wp.com/www.androidawareness.com/wp-content/uploads/2018/10/google-icon.png?resize=500%2C400" />
                <span class='vertical-align-middle text-dark text-bold ml-2 mt-2' style="font-weight: bold; vertical-align: text-top;">Продолжить с Google</span>
                </a>
            </li>
        </ul>
        <div class="links text-center mt-4">
            <span style="font-size: 15px;" class='text-secondary'>Продолжая, вы принимаете условия YouInRoll:<br><span class='text-dark'><a>Условия предоставления услуг</a></span> и <span class='text-dark'><a>Политика <br> конфиденциальности.</a></span><span><br>
        </div>
      </form>
    </div>
    <div class="form-busines mt-2">
        <button class="btn btn-busines">Регистрация</button>
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
<script src="/tpl/main_n/styles/loginAssets/jquery.js"></script>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/1.5.0/perfect-scrollbar.js" integrity="sha512-4ejaN8M2YXbJ7KVP13DaCS0fZOoNCUPukqOMumr8r32Xz1/2wRw4nCKJrNmTxstfH5Gf2oLe27YpAPiQr2OnTQ==" crossorigin="anonymous"></script>
<script src="/tpl/main_n/styles/js/modules/JSJaC/jsjac.js"></script>
<script src="/tpl/main_n/styles/js/jitsi.js"></script>
<script>

window.backurl = `<?=$_GET['backurl'];?>`.replace(/\/$/g, '');

document.addEventListener('DOMContentLoaded', function(){

    $(`#captcha`).hide();

    const ps = new PerfectScrollbar($('.form')[0], {
        wheelPropagation: true,
        minScrollbarLength: 20
    });

    $('.btn-busines').on('click', function(){
        location.href = '/register'+'?backurl='+backurl;
    })

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

    $('.btn-signin').on('click', function(e){
        e.preventDefault();

        $(`input`).removeClass('is-invalid');
        $('small').remove();

        $.post(
            'lib/ajax/loginUser.php',
            $('form').serialize(),
            function(data)
            {
                let response = JSON.parse(data);

                if(response.result !== '')
                {
                    let jitsiClass = new JitsiChat();

                    let formData = document.forms[0];

                    location.href = 'https://youinroll.com'+backurl;
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