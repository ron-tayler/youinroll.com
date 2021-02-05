<?php include_once('../../load.php');

$page = intval( 
    isset($_POST['page'])
    ? $_POST['page']
    : 1
);

if(is_user() && $page > 0)
{
    $itemsCount = 7;

    $offset = ($page - 1) * $itemsCount;

    //0,5 5,10 10,15
    $plays = $cachedb->get_results(
        "SELECT id,title FROM ".DB_PREFIX."playlists where owner = '".user_id()."' and picture not in ('[likes]','[history]','[later]') and ptype < 2 order by title desc limit $offset,$itemsCount");

    if($plays !== null)
    {
        foreach ($plays as $play)
        {
            $play->title = _html(_cut($play->title, 24));
            $play->url =playlist_url($play->id, $play->title);
        }
    }
}

echo json_encode($plays);
?>