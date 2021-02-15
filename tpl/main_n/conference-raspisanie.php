<?php
/* require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

use Google_Client;

if(is_user())
{
    $user = $db->get_results("SELECT * FROM ".DB_PREFIX."users where id = ".toDb(user_id()));
    
} else
{
    layout(404);
    die();
}

$client = new Google_Client();
$client->setApplicationName('Quickstart');
$client->setScopes(Google_Service_Calendar::CALENDAR_READONLY);
$client->setAuthConfig(__DIR__.'/credentionals.json');
$client->setAccessType('offline');
$client->setPrompt('select_account consent'); */


$daysInMonth = range(1, date("t"));

$currentDay = date('d');
$currentMonth = date('m');
$currentYear = date('y');

$monthStartTimestamp = "$currentYear-$currentMonth-01 00:00:00";

$conferences = $db->get_results('SELECT * FROM '.DB_PREFIX.'conferences WHERE moderator_id = '.toDb(user_id())." AND started_at >= '$monthStartTimestamp'");


?>
<div id='channelCalendar' class="">
    <div class="calendar">
        <ol class="day-names">
            <li>
                Sunday
            </li>
            <li>
                Monday
            </li>
            <li>
                Tuesday
            </li>
            <li>
                Wednesday
            </li>
            <li>
                Thursday
            </li>
            <li>
                Friday
            </li>
            <li>
                Saturday
            </li>
        </ol>
        <ol class="days">
            <? foreach ($daysInMonth as $day) { ?>
                <li>
                    <div class="date">
                        <?=$day;?>
                    </div>
                <?
                if($conferences) {
                foreach($conferences as $conference)
                {
                    $confDay = date('j', strtotime($conference->started_at));

                    if($confDay === (string)$day)
                    {
                        $confTime = date('h:m', strtotime($conference->started_at));
                        ?>
                        <div class="event" title="<?=$conference->description;?>">
                            <p class="event-name"><?=$conference->name;?></p>
                            <span class='time'><?=$confTime;?></span>
                            <? $tooltip = 'tooltiptext'; 
                                if((int)$day > 15) { $tooltip .= ' tooltip-top-right'; }
                                if( ( (int)$day % 7 ) === 0 ) { $tooltip .= ' tooltip-left'; }
                            ?>
                            <span class="<?=$tooltip;?>"><img src='<?=$conference->cover;?>' /><br><?=$conference->description;?></span>
                        </div>
                        <?
                    }
                }} ?>
                </li>
            <?}?>
        </ol>
    </div>
</div>
<?
function renderActivation($authUrl)
{
    echo "<div class='container'>
    <div>
        <a href='$authUrl' target='blanc' id='redeemCode' class='btn btn-primary btn-lg'>Подключить календарь</a>
    </div>
    <div class='bd-example' style='display:none'>
        <form id='codeForm'>
            <div class='form-group'>
                <label for='exampleInputEmail1'>Код</label>
                <input type='text' name='code' class='form-control' id='exampleInputEmail1' aria-describedby='emailHelp' placeholder='Enter code'>
                <small id='emailHelp' class='form-text text-muted'>Введите код полученный после перехода с кнопки</small>
            </div>
        </form>
        <button id='submitCode' class='btn btn-primary'>Submit</button>
    </div>
    <script>
        $('#redeemCode').on('click', function(){
            $('.bd-example').show();
        });

        $('.container').on('click', '#submitCode', function(){
            $.post(
                site_url + 'lib/ajax/saveCalendar.php', { 
                    code: $(`input[name='code']`).val()
                },
                
                function(data){
                    location.reload();
                }
            );
        });
        
    </script>
    </div>";
}

/* $userToken = ($user !== null)
    ? $user[0]->gcalendar
    : null;

if($userToken === null)
{
    $authUrl = $client->createAuthUrl();
    renderActivation($authUrl);

} else
{   
    try {

        $client->setAccessToken($userToken);
        
        $service = new Google_Service_Calendar($client);

        // Print the next 10 events on the user's calendar.
        $calendarId = 'primary';
        $optParams = array(
            'maxResults' => 10,
            'orderBy' => 'startTime',
            'singleEvents' => true,
            'timeMin' => date('c'),
        );

        $results = $service->events->listEvents($calendarId, $optParams);
        $events = $results->getItems();

        var_dump($events);

        if (empty($events)) {
            echo "No upcoming events found.\n";
        } else {
            echo "Upcoming events:\n";
            foreach ($events as $event) {
                
                $start = $event->start->dateTime;
                if (empty($start)) {
                    $start = $event->start->date;
                }
            }
        }
    } catch (\Throwable $th) {
        echo "<pre>";
        print_r($th);
        die();
    }
} */

do_action('conferenceloop-end');
?>