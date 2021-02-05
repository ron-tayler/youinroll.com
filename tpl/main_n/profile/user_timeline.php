<?php 

$daysInMonth = range(1, date("t"));

$currentDay = date('d');
$currentMonth = date('m');
$currentYear = date('y');

$monthStartTimestamp = "$currentYear-$currentMonth-01 00:00:00";

$conferences = $db->get_results('SELECT * FROM '.DB_PREFIX.'conferences WHERE moderator_id = '.toDb($user)." AND started_at >= '$monthStartTimestamp'");

?>
<div id='channelCalendar' class="">
    <h4 class="loop-heading">
        <span><?=_lang("Last conferences")?></span>
    </h4>
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
                } ?>
                </li>
            <?}?>
        </ol>
    </div>
</div>
</div>