/* Загрузка подписок пользователя */
function subscriptionDropdown(){
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
            if(result.length < 5)
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
}

/* Свернуть */
function subscriptionMinimize(e){
    let $blocs = $('.subscription-button');
    let reversedArray = [];

    for(let i = 0; i < $blocs.length; i++)
    {
        reversedArray.push($blocs[i]);
    }

    reversedArray = reversedArray.reverse();

    for (let index = 0; index <= parseInt($blocs.length - 5); index++)
    {
        $(reversedArray[index]).remove();
    }

    $('#subscriptionDropdown').show();
    $('#subscriptionDropdown').data('page', 2);
    $('#subscriptionMinimize').hide();
}

/* Загрузка плейлистов пользователя */
function playlistDropdown(){
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
            if(result.length < 5)
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
}

/* Свернуть */
function playlistMinimize(e){
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
}

// Функция на открытие sidebar
function sidebar_open(){
    sessionStorage.setItem('sidebarStatus', 'open');
    $("#sidebar").data('status', 'open');

    $("#show-sidebar").addClass("is-active");
    $("#rtf-show-sidebar").addClass("is-active");

    if ($('#sidebar').data('type') === 'iconic') {

        $("#sidebar").removeClass('mini');
        $("#sidebar").removeClass('hide');
        //$('#wrapper').each((i,e)=>$(e).css('left',$("#sidebar").width()));
        $("#wrapper").css({
            "margin-left": $("#sidebar").width(),
            "margin-right": "0",
            "width": "auto"
        });

    } else {
        $("#sidebar").removeClass('hide');
        $("#sidebar").removeClass('hide-all');

        if (!$('#tinted').length) {
            $('body').prepend('<div id="tinted"></div>');
            $('#page').css('overflow-y', 'hidden');
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

    $("#show-sidebar").removeClass("is-active");
    $("#rtf-show-sidebar").removeClass("is-active");

    if ($('#sidebar').data('type') === 'iconic') {

        $("#sidebar").addClass('mini');
        $("#sidebar").addClass('hide');

        //$('#wrapper').each((i,e)=>$(e).css('left',$("#sidebar").width()));
        $("#wrapper").css({
            "margin-left": $("#sidebar").width(),
            "margin-right": "0",
            "width": "auto"
        });

    } else {

        $("#sidebar").addClass('hide');
        $("#sidebar").addClass('hide-all');

        $('#tinted').remove();
        $('#page').css('overflow-y', 'auto');
    }
}
//End sidebar

$(document).on('ready', function(){
    $('#subscriptionDropdown').click(subscriptionDropdown);
    $('#subscriptionMinimize').click(subscriptionMinimize);

    $('#playlistDropdown').click(playlistDropdown);
    $('#playlistMinimize').click(playlistMinimize);

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
    function showSidebar() {
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
    }
    $("#show-sidebar").click(showSidebar);
    $("#rtf-show-sidebar").click(showSidebar);

    $('.sidebar-element-box').click(function(event,el){
        console.log(event.currentTarget);
        goto_page($(event.currentTarget).data('page'));
    });
});
