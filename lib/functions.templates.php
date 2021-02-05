<?

/* 
* Шаблонизатор, самописный, версия 22.8
*/
class YNRTemplate {

    /* 
    * Добавить между хедером и футером
    */
    public function include(string $templatePath, $cssPath = null, $jsPath = null)
    {   
        global $db, $cachedb,$next;

        $additionalJs = $jsPath ? '<!-- Page Scripts --> <script src="'.$jsPath.'"></script><!-- End Page Scripts -->' : '';
        $additionalCss = $cssPath ? '<link rel="stylesheet" href="'.$cssPath.'" type="text/css">' : '';

        include(TPL.'/general/tpl.header.php'); 
        include_once(TPL.$templatePath);
        include(TPL.'/general/tpl.footer.php');
    }
}

?>