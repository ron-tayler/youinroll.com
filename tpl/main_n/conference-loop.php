<style>
table{
  width:100%;
  table-layout: fixed;
  color:black !important;
}

td,th{
  padding-bottom: 11px;
  padding-top: 11px;
  text-align: start;
  /* vertical-align: top; */
}

.stream-cover {
  position: relative;
  background-size: cover;
  height: 240px;
  padding: 20px;
  border-radius: 20px;
  width: 240px;
}

.stream-sub-cover {
  position:absolute;
  display: none;
  width: 240px;
  height: 240px;
  padding: 20px;
  background-size: cover;
  border-radius: 20px;
  top:0;
  left:0;
}

.stream-sub-cover * {
  color: white !important;
}

.stream-delete {
  position: absolute;
  bottom: 10px;
  right: 15px;
  width: 30px;
  text-align: center;
  height: 30px;
  border-radius: 25px;
  background-color: #ff0000c2;
}

.stream-delete i {
  line-height: 1.2
}

.stream-edit {
  position: absolute;
  bottom: 10px;
  left: 15px;
  width: 30px;
  text-align: center;
  height: 30px;
  border-radius: 25px;
  background-color: #ffeb00c2;
}

.stream-edit i {
  line-height: 1.2
}

.stream-cover:hover > .stream-sub-cover {
  display:block;
  background-color: #0000008c;
  transition: 1s ease-in 0s;
  backdrop-filter: blur(5px);
}

.row-name {
  color: white !important;
  font-weight: bold;
  padding-top: 20px;
  font-size: 20px;
}

@import url(https://fonts.googleapis.com/css?family=Roboto:400,500,300,700);


/* for custom scrollbar for webkit browser*/

::-webkit-scrollbar {
    width: 6px;
} 
::-webkit-scrollbar-track {
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3); 
} 
::-webkit-scrollbar-thumb {
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3); 
}
</style>


<section>
  <div class="tbl-header">
    <table cellpadding="0" cellspacing="0" border="0">
      <thead>
        <tr>
          <th>Стрим</th>
          <th>Описание</th>
          <th>Статус</th>
          <th>Начало в</th>
        </tr>
      </thead>
    </table>
  </div>
  <div class="tbl-content">
    <table cellpadding="0" cellspacing="0" border="0">
      <tbody>
	  	<?
        if(!nullval($vq)) { $videos = $db->get_results($vq); } else {$videos = false;}
      ?>
      <?if($videos) {
        foreach ($videos as $video) {
      ?>
      <tr>
        <td class="stream-name">
          <div class='stream-cover' style="background-image:url('<?=thumb_fix($video->cover, 240, 240)?>');">
            <div class='stream-sub-cover'>
              <a class='row-name' href='<?=stream_url($video->id, $video->name)?>'>
                <span><?=$video->name?></span>
              </a>
              <a class='stream-delete' data-id='<?=$video->id?>'>
                <i class='material-icons'>&#xe872;</i>
              </a>
              <a class='stream-edit' data-id='<?=$video->id?>'>
                <i class='material-icons'>&#xe3c9;</i>
              </a>
            </div>
          </div>
        </td>
        <td><?=$video->description?></td>
        <td>
        <?
        $badgeClass = 'warning';
        $badgeName = _lang('Waiting');

        if(strtotime($video->started_at) > strtotime()) {
          $badgeClass = 'warning';
          $badgeName = _lang('Waiting');
        }

        if(strtotime($video->started_at) < strtotime()) {
          $badgeClass = 'secondary';
          $badgeName = _lang('Off');
        }

        if((int)$video->on_air === 1) {
          $badgeClass = 'danger';
          $badgeName = _lang('On Air');
        }
        
        ?>
        <span class="badge badge-<?=$badgeClass?>"><?=$badgeName?></span></td>
        <td><?=date('H:i d-m', strtotime($video->started_at))?></td>
      </tr>
      <?}}?>
      </tbody>
    </table>
  </div>
</section>

<script>
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
</script>