<style>
table{
  width:100%;
  table-layout: fixed;
  color:black !important;
}
.tbl-header{
  background-color: rgba(255,255,255,0.3);
 }
.tbl-content{
  margin-top: 0px;
  border: 1px solid rgba(255,255,255,0.3);
}
th{
  padding: 20px 15px;
  text-align: left;
  font-weight: 500;
  font-size: 12px;
  text-transform: uppercase;
}
td{
  padding: 15px;
  vertical-align:top;
  text-align: left;
  font-weight: 300;
  font-size: 14px;
  border-bottom: solid 1px rgba(255,255,255,0.1);
}

td:first-of-type {
	text-align: center;
	font-weight: bold;
}

.row-name {
	position: relative;
    width: 100%;
	border-radius: 12px;
}

.row-name > img {
	border-radius: 12px;
}

td:first-of-type:hover span.name{
	display: block;
    position: absolute;
    text-align: center;
    backdrop-filter: blur(1px);
    width: inherit;
    font-size: 18px;
    padding: 28%;
    color: white;
    background-color: #000000bd;
    border-radius: 12px;
    height: auto;
}

.row-name > .name {
	display:none;
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
          <th>Урок</th>
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
		<? if($videos) {
			foreach ($videos as $video) {
		?>
        <tr>
			<td>
				<a class='row-name' href=''>
					<span class='name'><?=$video->name?></span>
					<img src='<?=$video->cover?>'>
				</a>
			</td>
          	<td><?=$video->description?></td>
          	<td><span class="badge badge-success">Completed</span></td>
          	<td><?=date('h:i m-Y', strtotime($video->started_at))?></td>
        </tr>
		<? }} ?>
      </tbody>
    </table>
  </div>
</section>