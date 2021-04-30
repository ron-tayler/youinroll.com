<?php
include_once('../../load.php');
ini_set('display_errors', 0);

$entity = [];
$page = isset($_GET['page']) ? $_GET['page'] : null;

if(is_user())
{

    switch ($page) {
        case 'dashboard':

            $sql = "SELECT group_id,name,email,avatar,type from ".DB_PREFIX."users where id = '".user_id()."' LIMIT 0,1";

            $user = $cachedb->get_row($sql);

            $subscribers = $cachedb->get_var("SELECT COUNT(*) FROM ".DB_PREFIX."users_friends WHERE fid = '".user_id()."'");
            
            $videos = $cachedb->get_var("SELECT COUNT(*) FROM ".DB_PREFIX."videos WHERE user_id = '".user_id()."'");
            $videosViews = $cachedb->get_var("SELECT SUM(views) FROM ".DB_PREFIX."videos WHERE user_id = '".user_id()."'");
            $videosLikes = $cachedb->get_var("SELECT SUM(liked) FROM ".DB_PREFIX."videos WHERE user_id = '".user_id()."'");
            $videosDisikes = $cachedb->get_var("SELECT SUM(disliked) FROM ".DB_PREFIX."videos WHERE user_id = '".user_id()."'");

            $courseSallary = $cachedb->get_var("SELECT SUM(playlist.price) AS total FROM ".DB_PREFIX."playlists AS playlist
                INNER JOIN vibe_users_courses AS course
                ON
                    course.playlist_id = playlist.id
                AND playlist.owner = '".toDb(user_id())."'
                AND course.status = 'ready'
            ");

            $lastConference = $db->get_row('SELECT * FROM '.DB_PREFIX.'conferences WHERE moderator_id = '.toDb(user_id()).' ORDER BY id DESC LIMIT 0,1');

            if($lastConference)
            {
                $lastConference->cover = thumb_fix($lastConference->cover, 180, 180);
            }

            $news = $db->get_results('SELECT pid,date,title,pic FROM '.DB_PREFIX.'pages ORDER BY pid DESC LIMIT 9');

            $lastSubs = $db->get_results("SELECT
                USER.id,
                USER.avatar,
                USER.name
            FROM
                vibe_users_friends AS subs
            INNER JOIN vibe_users AS USER
            ON
                subs.uid = USER.id AND subs.fid = '".toDb(user_id())
            ."'ORDER BY
                subs.id
            DESC
            LIMIT 5");
            

            if($lastSubs)
            {   
                foreach ($lastSubs as $lastSub) {
                    $lastSub->avatar = thumb_fix($lastSub->avatar, 180, 180);
                }
            }

            $lastReport = $db->get_row('SELECT vid,sid,to_user,reason FROM '.DB_PREFIX.'reports WHERE to_user = '.toDb(user_id()));

            if($lastReport)
            {   
                $lastReport->reason = strip_tags(html_entity_decode(unserialize($lastReport->reason)[0]));
                $lastReport->url = (intval($lastReport->vid) === 0) ? conference_url($lastReport->sid,'reported') : video_url($lastReport->vid, 'reported');
                $lastReport->url = 'https://youinroll.com'.$lastReport->url;
            }

            $entity = [
                'user' => $user,
                'subs' => intval($subscribers),
                'lastSubs' => $lastSubs,
                'lastRep' => $lastReport,
                'videos' => [
                    'count' => intval($videos),
                    'views' => intval($videosViews),
                    'likes' => intval($videosLikes),
                    'dislikes' => intval($videosDisikes)
                ],
                'courses' => [
                    'sallary' => intval($courseSallary)
                ],
                'lastConference' => $lastConference,
                'news' => $news
            ];

            break;
        
        case 'content-video':

            $pageFrom = isset($_GET['pageNum']) ? intval($_GET['pageNum']) : 1;

            $pageFrom = ($pageFrom - 1) * 25;

            $sql = "SELECT id, thumb, tags, description, date, title, views, liked, disliked, nsfw FROM vibe_videos WHERE user_id = '".toDb(user_id())."' ORDER BY id DESC LIMIT $pageFrom, 25";
            
            $videos = $db->get_results($sql);

            $totalPages = $db->get_var("SELECT CEILING(COUNT(*) / 25) FROM ".DB_PREFIX."videos WHERE user_id = '".toDb(user_id())."'");

            if($videos)
            {
                foreach ($videos as $video) {

                    $video->title = htmlentities($video->title);

                    $video->description = htmlentities($video->description);

                    $video->id = intval($video->id);

                    $video->url = video_url($video->id,$video->title);

                    $video->views = intval($video->views);
                    $video->disliked = intval($video->disliked);
                    $video->nsfw = intval($video->nsfw);
                    $video->liked = intval($video->liked);
                    $video->thumb = thumb_fix($video->thumb, 180, 180);
                    $video->tags = explode(',', trim($video->tags)); 
                }

                $entity = [
                    "videos" => $videos,
                    "totalPages" => $totalPages
                ];
            }
            
            break;

            case 'content-conference':

                $pageFrom = isset($_GET['pageNum']) ? intval($_GET['pageNum']) : 1;
    
                $pageFrom = ($pageFrom - 1) * 25;
    
                $sql = "SELECT id, cover, tags, description, name, views, likes, started_at, likes, on_air FROM vibe_conferences WHERE moderator_id = '".toDb(user_id())."' AND type = 'stream' ORDER BY id DESC LIMIT $pageFrom, 25";
                
                $videos = $db->get_results($sql);
    
                $totalPages = $db->get_var("SELECT CEILING(COUNT(*) / 25) FROM ".DB_PREFIX."conferences WHERE moderator_id = '".toDb(user_id())."' AND type = 'stream'");
    
                if($videos)
                {
                    foreach ($videos as $video) {
    
                        $video->name = htmlentities($video->name);
    
                        $video->description = htmlentities($video->description);
    
                        $video->id = intval($video->id);
    
                        $video->url = conference_url($video->id,$video->title);
    
                        $video->views = intval($video->views);
                        $video->likes = intval($video->disliked);
                        $video->cover = thumb_fix($video->cover, 180, 180);
                        $video->tags = explode(',', trim($video->tags)); 
                    }
    
                    $entity = [
                        "videos" => $videos,
                        "totalPages" => $totalPages
                    ];
                }
                
                break;

            case 'content-lesson':

                $pageFrom = isset($_GET['pageNum']) ? intval($_GET['pageNum']) : 1;
    
                $pageFrom = ($pageFrom - 1) * 25;
    
                $sql = "SELECT id, cover, tags, description, name, views, likes, started_at, likes, on_air FROM vibe_conferences WHERE moderator_id = '".toDb(user_id())."' AND type = 'lesson' ORDER BY id DESC LIMIT $pageFrom, 25";
                
                $videos = $db->get_results($sql);
    
                $totalPages = $db->get_var("SELECT CEILING(COUNT(*) / 25) FROM ".DB_PREFIX."conferences WHERE moderator_id = '".toDb(user_id())."' AND type = 'lesson'");
    
                if($videos)
                {
                    foreach ($videos as $video) {
    
                        $video->name = htmlentities($video->name);
    
                        $video->description = htmlentities($video->description);
    
                        $video->id = intval($video->id);
    
                        $video->url = conference_url($video->id,$video->title);
    
                        $video->views = intval($video->views);
                        $video->likes = intval($video->disliked);
                        $video->cover = thumb_fix($video->cover, 180, 180);
                        $video->tags = explode(',', trim($video->tags)); 
                    }
    
                    $entity = [
                        "videos" => $videos,
                        "totalPages" => $totalPages
                    ];
                }
                
                break;

        case 'profile':
            $sql = "SELECT
                USER.id,
                USER.name,
                USER.email,
                USER.bio,
                USER.vklink,
                USER.twlink,
                USER.glink,
                USER.iglink,
                USER.email,
                USER.avatar,
                USER.cover,
                USER.chatRoom,
                groups.name as role
            FROM
                vibe_users as USER
            INNER JOIN vibe_users_groups AS groups ON 
                USER.id = '".toDb(user_id())."' AND USER.group_id = groups.id
            LIMIT 0, 1";
            
            $entity = $cachedb->get_row($sql);

            $entity->avatar = 'https://youinroll.com/'.$entity->avatar;

            $entity->profileUrl = profile_url($entity->id, $entity->name);
            $entity->chatRoom = md5($entity->chatRoom);
                        
            break;
        
        default:
            $sql = "SELECT id,group_id,name,email,avatar,type from ".DB_PREFIX."users where id = '".user_id()."' LIMIT 0,1";
            
            $entity = $cachedb->get_row($sql);

            $entity->avatar = thumb_fix($entity->avatar, 180, 180);
            $entity->url = profile_url($entity->id, $entity->name);
            
            break;
    }
}

echo json_encode($entity, true);
?>