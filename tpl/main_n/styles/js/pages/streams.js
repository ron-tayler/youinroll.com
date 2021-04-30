$(document).on('ready', function() {

    $('.stream-delete').on('click', function() {

        let id = $(this).data('id');
        let $element = $(this).parent().closest('tr')

        $.get('/lib/ajax/removeStream.php', {
            'id': id
        }, function(data) {
            $element.remove();
        })
    })
})