/* Загрузка подписок пользователя */
$('#subscriptionDropdown').on('click', document, function(){
    $.post(
        site_url + 'lib/ajax/getSubscriptions.php', { 
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
                let $bloc = $(`<li class="subscription-button">
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

/* При загрузке страницы подгружаются подписки */
$(document).on('ready', function(){
    $('#subscriptionDropdown').click();
});