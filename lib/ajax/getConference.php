<?php include_once('../../load.php');


/* 
    Права:
        - Запрет смены пользовательских данных
        - Лобби вкл всегда


    Школы:
        - Создание конференций (классов)
        
    Учитель:
        - Приглашать
        - Кикать
        - Бан на сайте
        - Демонстрация экрана
        - Предложить показать экран
        - Запись вкл
        - Демонстрация видео
        - Мут всех
        - Задавать пароли
        - Speaker stats
        - Прямая трансляция (убрать из ютуба, заменить на jitsi)

    Студент:
        - Поднять руку
        - (Говорить только при поднятой руке)
        - Обсуждение совместно (фильмы итд)

    Родители:
    
    Автор (всё как учителю кроме статистики):

    Премиум:
        - Фоны
        - Стикеры
        - Сердечки как в инстаграме
        - Включить шифрование

*/

$roomName = isset($_GET['room'])
    ? $_GET['room']
    : null;

$width = isset($_GET['width'])
    ? intval($_GET['width'])
    : 700;

$height = isset($_GET['height'])
    ? intval($_GET['height'])
    : 700;

if(is_user() && $roomName !== null)
{
    $userModel = $cachedb->get_results("SELECT group_id,name,email,avatar,type from ".DB_PREFIX."users where id = '".user_id()."'");
    
    $userEmail = $userModel[0]->email;
    $userName = translit($userModel[0]->name);
    $userAvatar = thumb_fix($userModel[0]->avatar, true, 250, 250);

    echo("<div id='meet'></div>");
    echo("<div id='sweeet'></div>");

    echo("
        <script src='https://smartfooded.com/external_api.js'></script>
        <script>
            const domain = 'smartfooded.com';
            const options = {
                roomName: '$roomName',
                width: $width,
                height: $height,
                parentNode: document.querySelector('#meet'),
                userInfo: {
                    email: '$userEmail',
                    displayName: '$userName'
                },
            };

            window.JitsiApi = new JitsiMeetExternalAPI(domain, options);

            JitsiApi.executeCommand('avatarUrl', '$userAvatar');

        </script>
    ");
} else
{
    echo "<center>404</center>";
}
?>
