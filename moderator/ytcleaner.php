<?php //Cleaner

if(isset($_POST['checkRow'])) {
foreach ($_POST['checkRow'] as $del) {
$act = isset($_POST['massaction']) ? $_POST['massaction'] : "unpublish";	
switch ($act) {
case "unpublish":
unpublish_video(intval($del));
break;
case "remove":
delete_video(intval($del));
break;
}

}
echo '<div class="msg-info">Perfomed '.$act.' action on videos #'.implode(',', $_POST['checkRow']).'</div>';
}

$count = $db->get_row("Select count(*) as nr from ".DB_PREFIX."videos where pub >  0 and media < 2 and source like '%youtube%' ");
//$db->debug();
?>
<div class="row">
<h3>Youtube Broken Videos Cleaner</h3>				
</div>

<form class="form-horizontal styled" action="<?php echo admin_url('ytcleaner');?>&p=<?php echo this_page();?>" enctype="multipart/form-data" method="post">
<div class="cleafix full"></div>
<fieldset>
<div class="panel top10 multicheck">
<div class="panel-heading">
<h3 class="panel-title">

 <div id="spinCard">
 <span class="label label-success">Checking videos!</span>
 <div class="msg inline"> </div> 
 </div>
 <div id="spinCard-done" class="hide">
 <span class="label label-success">Done!</span>
 </div>
</h3> 

<ul class="panel-actions">
    <li class="chbox">
	<div class="checkbox-custom checkbox-primary nopad"> <input type="checkbox" name="checkRows" class="check-all" /> <label for="checkRows"></label> </div>
	</li>
    <li>
	<select id="massaction" name="massaction" class="select">	
	<option value="unpublish">Unpublish all selected</option>
	<option value="remove">Remove all selected</option>								  
	</select>
    </li>
	<li class="chbutton">
	<button class="btn btn-primary btn-sm tipS" type="submit" title="<?php echo _lang("Do mass action"); ?>"><i class="material-icons">&#xE877;</i></button>
	</li>
</ul>
</div>

<div class="panel-body" style="border-top: 1px solid #e4eaec; padding-top:15px;">
 <div class="multilist">

 
<ul class="list-group">
	
	</ul>
</div>						
</fieldset>					
</form>
<script>
var clurl = '<?php echo admin_url();?>ytcleanertaks.php';
var page = 0;
var maxPage = <?php echo ceil($count->nr / bpp()) ?>;
function getManifestations() {
	if(page <= maxPage) {
    if(! $('#spinCard').hasClass('data-loading')) {
		$('#spinCard-done').addClass('hide');
        $('#spinCard').addClass('data-loading').removeClass('hide');
		$('#spinCard > .msg').html('Page ' + page + ' of ' + maxPage);
	   page++;
       $.ajax({
            url : clurl + '?p=' + page
        }).done(function (data) {
            $('ul.list-group').append(data);
        }).fail(function () {
           console.log('List of videos could not be loaded.');
        }).always(function () {
            $('#spinCard').removeClass('data-loading').addClass('hide');
			$('#spinCard-done').removeClass('hide');			
			getManifestations(page);
			
        });
      }
    } else {
	$('body').find(':checkbox').each(function() {$(this).addClass('icheckbox-primary');});	
	$('input:not(.check-all,.check-all-notb)').iCheck({mode: 'default', checkboxClass: 'icheckbox_flat-blue',radioClass: 'iradio_flat-blue'});
 	
	}
}
getManifestations();	
</script>