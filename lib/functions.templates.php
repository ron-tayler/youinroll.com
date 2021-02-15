<?

/* 
* Шаблонизатор, самописный, версия 22.8
*/
class YNRTemplate {

    /* 
    * Добавить между хедером и футером
    */
    public function include(string $templatePath, $cssPathes = [], $jsPathes = [], $globalTemplateVariable = null)
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
        

        include(TPL.'/general/tpl.header.php'); 
        include_once(TPL.$templatePath);
        include(TPL.'/general/tpl.footer.php');
    }
}

?>