/* Загрузка подписок пользователя */
$('#subscriptionDropdown').on('click', document, function(){
    $.post(
        '/lib/ajax/getSubscriptions.php', {
            page: $(this).data('page'),
        },

        function(data){

            let newText = $('#subscriptionDropdown').data('minimize-text');

            if(data === "null" || $('.subscription-button').length >= 50)
            {
                $('#subscriptionDropdown').hide();
                $('#subscriptionMinimize').show();
                return null;
            }

            var result = JSON.parse(data);


            result.forEach(a => {
                let $bloc = $(`<li class="lihead subscription-button">
                    <a title="${a['name']}" href="${a['url']}">
                        <img src="${a['thumb']}" alt="${a['name']}">${a['name']}
                    </a>
                </li>`);

                $('#subscribtionItems').append($bloc);
            });

            /* Меняем тип кнопки */
            if(result.length < 7)
            {
                $('#subscriptionDropdown').hide();
                $('#subscriptionMinimize').show();
                return null;
            }
        }
    );

    let prevPage = parseInt($(this).data('page'));
    let page = prevPage + 1;

    $(this).data('page', page);
});

/* Свернуть */
$('#subscriptionMinimize').on('click', document, function(e) {
    let $blocs = $('.subscription-button');
    let reversedArray = [];

    for(let i = 0; i < $blocs.length; i++)
    {
        reversedArray.push($blocs[i]);
    }

    reversedArray = reversedArray.reverse();

    for (let index = 0; index <= parseInt($blocs.length - 7); index++)
    {
        $(reversedArray[index]).remove();
    }

    $('#subscriptionDropdown').show();
    $('#subscriptionDropdown').data('page', 2);
    $('#subscriptionMinimize').hide();
});


/* Загрузка плейлистов пользователя */
$('#playlistDropdown').on('click', document, function(){
    $.post(
        '/lib/ajax/getPlaylists.php', {
            page: $(this).data('page'),
        },

        function(data){

            let newText = $('#playlistDropdown').data('minimize-text');

            if(data === "null" || $('.playlist-button').length >= 50)
            {
                $('#playlistDropdown').hide();
                $('#playlistMinimize').show();
                return null;
            }

            var result = JSON.parse(data);


            result.forEach(a => {
                let $bloc = $(`<li class="lihead playlist-button">
                    <a href="${a['url']}" original-title="${a['title']}" title="${a['title']}">
                        <span class='iconed icon-opacity'>
                            <i class="material-icons">&#xE05F;</i>
                        </span>
                        ${a['title']}
                    </a>
                </li>`);

                $('#playlistItems').append($bloc);
            });

            /* Меняем тип кнопки */
            if(result.length < 7)
            {
                $('#playlistDropdown').hide();
                $('#playlistMinimize').show();
                return null;
            }
        }
    );

    let prevPage = parseInt($(this).data('page'));
    let page = prevPage + 1;

    $(this).data('page', page);
});

/* Свернуть */
$('#playlistMinimize').on('click', document, function(e) {
    let $blocs = $('.playlist-button');
    let reversedArray = [];

    for(let i = 0; i < $blocs.length; i++)
    {
        reversedArray.push($blocs[i]);
    }

    reversedArray = reversedArray.reverse();

    for (let index = 0; index <= parseInt($blocs.length); index++)
    {
        $(reversedArray[index]).remove();
    }

    $('#playlistDropdown').show();
    $('#playlistDropdown').data('page', 1);
    $('#playlistMinimize').hide();
});

// Функция на открытие sidebar
function sidebar_open(){
    $("#show-sidebar>.hamburger").addClass("is-active");
    $("#show-sidebar").addClass("is-active");
    sessionStorage.setItem('sidebarStatus', 'open');
    $("#sidebar").data('status', 'open');
    if ($('#sidebar').data('type') === 'iconic') {

        $("#sidebar").removeClass('hide');
        //$("#wrapper").addClass('haside');

        let sideSpace = parseInt($("#wrapper").offset().left);
        $("#wrapper").css({
            "margin-left": sideSpace,
            "margin-right": "0",
            "width": "auto"
        });

        $("#page").css('left',$("#sidebar").width());

    } else {
        $("#sidebar").removeClass('hide-all');

        if (!$('#tinted').length) {
            $('body').prepend('<div id="tinted"></div>');
            $('body').css('overflow-y', 'hidden');
            $('#tinted').click(function(e){
                sidebar_close();
            });
        }
    }
}

// Функция на закрытие sidebar
function sidebar_close(){
    sessionStorage.setItem('sidebarStatus', 'close');
    $("#sidebar").data('status', 'close');
    $("#show-sidebar>.hamburger").removeClass("is-active");
    $("#show-sidebar").removeClass("is-active");
    if ($('#sidebar').data('type') === 'iconic') {

        $("#sidebar").addClass('hide');
        $("#wrapper").removeClass('haside');

        let sideSpace = $("#sidebar").width();
        $("#wrapper").css({
            "margin-left": sideSpace,
            "margin-right": "0",
            "width": "auto"
        });

        $("#page").css('left',$("#sidebar").width());

        if ($('#tinted').length || $('#sidebar').hasClass('hide')) {
            $('#tinted').remove();
            $('body').css('overflow-y', 'auto');
        }

    } else {

        $("#sidebar").addClass('hide-all');

        $('#tinted').remove();
        $('body').css('overflow-y', 'auto');
    }
}
//End sidebar

$(document).on('ready', function(){
    $('#subscriptionDropdown').click();

    // Иницализация sidebar
    let sidebar_type = $("#sidebar").data('type');
    let sidebar_status = (sidebar_type === 'normal') ? 'close' : sessionStorage.getItem('sidebarStatus') ?? $("#sidebar").data('status');
    if (sidebar_status === 'open') {
        sidebar_open();
    } else if (sidebar_status === 'close') {
        sidebar_close();
    } else {
        console.error('Ошибка с параметром sidebar_status:', sidebar_status);
        sidebar_status = 'close';
        console.error('Параметр sidebar_status теперь:', sidebar_status);
    }
    sessionStorage.setItem('sidebarStatus', sidebar_status);
    $("#sidebar").data('status', sidebar_status);

    // Hook на кнопку sidebar
    $("#show-sidebar").click(function () {
        let sidebar_status = $("#sidebar").data('status');
        if (sidebar_status === 'open') {
            sidebar_close();
        } else if (sidebar_status === 'close') {
            sidebar_open();
        } else {
            console.error('Ошибка с параметром sidebar_status:', sidebar_status);
            console.error('Параметр sidebar_status теперь: close');
            sessionStorage.setItem('sidebarStatus', 'close');
            $("#sidebar").data('status', 'close');
        }
    });
    $("#rtf-show-sidebar").click(function () {
        let sidebar_status = $("#sidebar").data('status');
        if (sidebar_status === 'open') {
            sidebar_close();
        } else if (sidebar_status === 'close') {
            sidebar_open();
        } else {
            console.error('Ошибка с параметром sidebar_status:', sidebar_status);
            console.error('Параметр sidebar_status теперь: close');
            sessionStorage.setItem('sidebarStatus', 'close');
            $("#sidebar").data('status', 'close');
        }
    });
});
