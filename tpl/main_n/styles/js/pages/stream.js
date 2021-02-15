$('.stream-delete').on('click', function(){

    let id = $(this).data('id');
  
    $.get('/lib/ajax/removeStream.php',{
      id: id
    }, function(data){
      data = JSON.parse(data);
      if(data.result === 'success')
      {
        $(`.stream-delete[data-id=${id}]`).closest('tr').remove();
      }
    });
  });