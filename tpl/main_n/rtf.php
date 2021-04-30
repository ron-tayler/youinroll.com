<?php

?>
<div class="page">
    <h4 class="rtf_header">RTF Tst Page</h4>
    <div class="rtf_texts">
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquid autem beatae consequatur culpa cumque cupiditate delectus dicta, dignissimos, dolorem dolorum error est hic illo ipsum iure laborum modi officiis perspiciatis placeat quae quasi recusandae repellendus reprehenderit sapiente similique soluta suscipit temporibus ullam ut velit. Delectus excepturi id nulla ullam vero voluptatem. A animi architecto consectetur consequuntur debitis deleniti distinctio doloremque dolorum eaque eligendi eum harum impedit in iste magni maxime mollitia nam natus necessitatibus neque praesentium quaerat quia quos, recusandae reiciendis ut vero voluptatibus! Animi aperiam aspernatur at consectetur culpa cupiditate, dolorum eos et eveniet incidunt ipsam iste itaque laboriosam magnam minima molestiae officia omnis quasi qui quo sed sint, suscipit unde velit veritatis vero vitae? A aperiam asperiores autem beatae consectetur cum, debitis deleniti dicta dignissimos distinctio dolorem ducimus eligendi in incidunt iure maxime molestiae non nostrum nulla numquam odio perspiciatis porro, possimus praesentium provident quibusdam repellat repellendus temporibus vero voluptate. Ducimus iste libero molestiae, quasi qui velit. Adipisci aspernatur, consequuntur deserunt dolore doloremque eius eum ex fugiat fugit ipsum iure labore laudantium minima molestias nihil nisi qui quia repudiandae? Accusantium beatae dicta dignissimos dolorem eaque esse excepturi facilis id impedit ipsa, libero, mollitia nobis, obcaecati optio soluta vel?</p>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquid autem beatae consequatur culpa cumque cupiditate delectus dicta, dignissimos, dolorem dolorum error est hic illo ipsum iure laborum modi officiis perspiciatis placeat quae quasi recusandae repellendus reprehenderit sapiente similique soluta suscipit temporibus ullam ut velit. Delectus excepturi id nulla ullam vero voluptatem. A animi architecto consectetur consequuntur debitis deleniti distinctio doloremque dolorum eaque eligendi eum harum impedit in iste magni maxime mollitia nam natus necessitatibus neque praesentium quaerat quia quos, recusandae reiciendis ut vero voluptatibus! Animi aperiam aspernatur at consectetur culpa cupiditate, dolorum eos et eveniet incidunt ipsam iste itaque laboriosam magnam minima molestiae officia omnis quasi qui quo sed sint, suscipit unde velit veritatis vero vitae? A aperiam asperiores autem beatae consectetur cum, debitis deleniti dicta dignissimos distinctio dolorem ducimus eligendi in incidunt iure maxime molestiae non nostrum nulla numquam odio perspiciatis porro, possimus praesentium provident quibusdam repellat repellendus temporibus vero voluptate. Ducimus iste libero molestiae, quasi qui velit. Adipisci aspernatur, consequuntur deserunt dolore doloremque eius eum ex fugiat fugit ipsum iure labore laudantium minima molestias nihil nisi qui quia repudiandae? Accusantium beatae dicta dignissimos dolorem eaque esse excepturi facilis id impedit ipsa, libero, mollitia nobis, obcaecati optio soluta vel?</p>
    </div>
    <button onclick="rtf_load1()">Home</button>
    <button onclick="rtf_load2()">Profile</button>
</div>
<script>
    function rtf_load1(){
        $.ajax({
            url: '/rtf/page_home/2',
            dataType: 'json',
            method: 'GET',
            data: {},
            success: function(json){
                $('.rtf_header').html(json.header);
                $('.rtf_texts').html('<p>'+json.text+'</p>');
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        })
    }
    function rtf_load2(){
        $.ajax({
            url: '/rtf/page_profile/3',
            dataType: 'json',
            method: 'GET',
            data: {},
            success: function(json){
                $('.rtf_header').html("Account | " + json.name);
                $('.rtf_texts').html(json.description);
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        })
    }
</script>
