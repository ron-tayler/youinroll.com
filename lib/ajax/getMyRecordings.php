<?php include_once('../../load.php');

$roomId = intval($_GET['roomId']);

$myRecords = [];

$myConference = $db->get_row('SELECT name FROM vibe_conferences WHERE moderator_id = '.user_id().' AND id = '.toDb($roomId));
//$myConference = $db->get_row('SELECT name FROM vibe_conferences WHERE id = '.toDb($roomId));

if($myConference) {
    $room = urlencode(strtolower(transliterate($myConference->name))."-user-".user_id());

    $room = str_replace('+','%20', $room);

    $link = "http://video-library-service.com";
    $curl = curl_init($link);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, "roomName=$room".'_');
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    //curl_setopt($curl, CURLOPT_HTTPHEADER, array('Host: smartfooded-search.com'));
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    $html = curl_exec($curl);

    $info = [];

    $files = json_decode($html);

    foreach ($files as $file) {

        preg_match('/.*_(\d\d\d\d\-\d\d\-\d\d\-\d\d\-\d\d\-\d\d)\./', $file, $date);

        if(isset($date[1]))
        {
            $date = DateTime::createFromFormat('Y-m-d-h-i-s', $date[1])->format('Y/m/d');
        } else
        {
            $date = 'Давно';
        }

        $info[] = [
            'link' => "http://video-library-service.com/download.php?path=".$file,
            'date' => $date
        ];
    }

    $myRecords = [
        'files' => $info
    ];
}

echo json_encode($myRecords);
?>