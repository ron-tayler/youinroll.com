<?

/* 
* Шаблонизатор, самописный, версия 22.8
*/
class YNRTemplate {

    /* 
    * Добавить между хедером и футером
    */
    public function include($templatePath, $cssPathes = [], $jsPathes = [], $globalTemplateVariable = null)
    {   
        global $db, $cachedb, $next, $active;

        $additionalCss = '<!-- Page Styles --> ';
        $additionalJs = '<!-- Page Scripts -->';

        foreach ($cssPathes as $cssPath)
        {
            $additionalCss .= $cssPath ? '<link rel="stylesheet" href="'.$cssPath.'" type="text/css">' : '';
        }

        foreach ($jsPathes as $jsPath)
        {
            $additionalJs .= $jsPath ? '<script src="'.$jsPath.'"></script>' : '';
        }
        
        if (!is_ajax_call()) {  include(TPL.'/general/tpl.header.php'); } 
        include_once(TPL.$templatePath);
        if (!is_ajax_call()) {  include(TPL.'/general/tpl.footer.php'); } 
    }

    public function openLanding($id)
    {
        $id = intval($id);

        if($id === 0)
        {
            layout(404);
            die();
        }

        global $db;

        $info = $db->get_row('SELECT * FROM '.DB_PREFIX."landings WHERE id = '".toDb($id)."' LIMIT 0,1");

        if($info === null) {
            layout(404);
            die();
        }

        $reviews = $db->get_results("
        SELECT
            comments.*,
            USER.name AS NAME,
            USER.avatar AS avatar
        FROM
            ".DB_PREFIX."em_comments AS comments
        INNER JOIN ".DB_PREFIX."users AS USER
        ON
            comments.sender_id = USER.id AND comments.object_id = 'u-$info->user_id'
        ORDER BY
            comments.id
        DESC

        LIMIT 25
        ");

        $playlistPrice = $db->get_var('SELECT price FROM '.DB_PREFIX."playlists WHERE id = '".toDb($info->playlist_id)."'");
        
        $userInfo = json_decode($info->userInfo);

        $info->price_percentage = floor(($playlistPrice / $info->prices) * 100);
        $info->price_new = $playlistPrice;

        include(TPL.'/general/landing.header.php');
        include(TPL.'/landing-variants/landing-1.php');
        include(TPL.'/general/landing.footer.php');
    }
}

?>