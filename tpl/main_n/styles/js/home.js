/* Загрузка видео на главной */
$(document).on('ready', function(){
    $.get(
        site_url + 'lib/ajax/getMainVideos.php', {},
        
        function(data){

            if(data === "null")
            {
                return null;
            }

            var result = JSON.parse(data);

            
        }
    );
});