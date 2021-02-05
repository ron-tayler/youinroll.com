<? global $db, $cachedb,$next;	?>
</div>
</div>
<a href="#" id="linkTop" class="backtotop">
    <img src="/tpl/main/images/up-arrow.svg" class="up-arrow" alt="icon" />
</a>
<div id="footer" class="row block full oboxed">
    <div class="row footer-holder">
        <div class="container footer-inner">
            <div class="row">
                <div class="col-md-12 text-center">
                    <div class="row row-socials">

                        <ul class="socialfooter">
                            <?if(not_empty(get_option("our_facebook", "#"))) {?>
                            <li>
                                <a rel="nofollow" class="tipS" href="<?=get_option("our_facebook")?>" target="_blank"
                                    title="<?=_lang("Facebook")?>">
                                    <img src="/tpl/main/socicon/fb.png" alt="icon" />
                                </a>
                            </li>
                            <?}?>
                            <?if(not_empty(get_option("our_googleplus", "#"))) {?>
                            <li>
                                <a rel="nofollow" class="tipS" href="<?=get_option("our_googleplus")?>" target="_blank"
                                    title="<?=_lang("Google Plus")?>">
                                    <img src="/tpl/main/socicon/gg.png" alt="icon" />
                                </a>
                            </li>
                            <?}?>
                            <?if(not_empty(get_option("our_pinterest", "#"))) {?>
                            <li>
                                <a rel="nofollow" class="tipS" href="<?=get_option("our_pinterest")?>" target="_blank"
                                    title="<?=_lang("Pinterest")?>">
                                    <img src="/tpl/main/socicon/in.png" alt="icon" />
                                </a>
                            </li>
                            <?}?>
                            <?if(not_empty(get_option("our_twitter", "#"))) {?>
                            <li>
                                <a rel="nofollow" class="tipS" href="<?=get_option("our_twitter")?>" target="_blank"
                                    title="<?=_lang("Twitter")?>">
                                    <img src="/tpl/main/socicon/gg.png" alt="icon" />
                                </a>
                            </li>
                            <?}?>
                            <?if(not_empty(get_option("our_rss", "#"))) {?>
                            <li>
                                <a rel="nofollow" class="tipS" href="<?=get_option("our_rss")?>" target="_blank"
                                    title="<?=_lang("Feedburner")?>">
                                    <img src="/tpl/main/socicon/rs.png" alt="icon" />
                                </a>
                            </li>
                            <?}?>
                            <?if(not_empty(get_option("our_skype", "#"))) {?>
                            <li>
                                <a rel="nofollow" class="tipS" href="<?=get_option("our_skype")?>" target="_blank"
                                    title="<?=_lang("Skype")?>">
                                    <img src="/tpl/main/socicon/sk.png" alt="icon" />
                                </a>
                            </li>
                            <?}?>
                            <?if(not_empty(get_option("our_vimeo", "#"))) {?>
                            <li>
                                <a rel="nofollow" class="tipS" href="<?=get_option("our_vimeo")?>" target="_blank"
                                    title="<?=_lang("Vimeo")?>">
                                    <img src="/tpl/main/socicon/vm.png" alt="icon" />
                                </a>
                            </li>
                            <?}?>
                            <?if(not_empty(get_option("our_dribbble", "#"))) {?>
                            <li>
                                <a rel="nofollow" class="tipS" href="<?=get_option("our_dribbble")?>" target="_blank"
                                    title="<?=_lang("Dribbble")?>">
                                    <img src="/tpl/main/socicon/Dribbble.png" alt="icon" />
                                </a>
                            </li>
                            <?}?>
                            <?if(not_empty(get_option("our_linkedin", "#"))) {?>
                            <li>
                                <a rel="nofollow" class="tipS" href="<?=get_option("our_linkedin")?>" target="_blank"
                                    title="<?=_lang("Linked in")?>">
                                    <img src="/tpl/main/socicon/lk.png" alt="icon" />
                                </a>
                            </li>
                            <?}?>
                        </ul>
                    </div>
                    <div class="row row-links">
                        <?
    $posts = $cachedb->get_results("select title,pid from ".DB_PREFIX."pages where menu = 1 ORDER BY m_order, title ASC limit 0,100");
    if($posts) {
      foreach ($posts as $px) {?>
                        <a class="btn btn-flat btn-default btn-squared" href="<?=page_url($px->pid, $px->title)?>"
                            title="<?=_html($px->title)?>">
                            <?=_cut(_html($px->title),190)?>
                        </a>
                        <?}	
    }
    ?>
                    </div>
                    <div class="row row-rights">
                        <?=site_copy()?>
                    </div>
                </div>
            </div>
            ';
            $footer .='
        </div>
    </div>
</div>
<!-- Start Search Modal -->
<div class="modal fade" id="search-now" aria-hidden="true" data-backdrop="false" aria-labelledby="search-now"
    role="dialog" tabindex="-1">
    <div class="modal-dialog modal-sidebar modal-searcher">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="panel panel-transparent">
                    <div class="row search-now-clone">


                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Search Modal -->
<script>
  let site_url = "<?=site_url()?>";
  let nv_lang = "<?=_lang("Next video starting soon")?>";
  let select2choice = "<?=_lang("Select option")?>";
  let delete_com_text = "<?=_lang("Delete this comment?")?>";
</script>

<script src="/tpl/main_n/styles/js/jquery.js"></script>
<script>

  let acanceltext = "<?=_lang("Cancel")?>";
  let startNextVideo, moveToNext, nextPlayUrl;

</script>

<script src="https://smartfooded.com/libs/external_api.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script src="<?=tpl()?>styles/minjs.php"></script>

<? if(is_user()) {?>
<script src="/tpl/main_n/styles/js/chat.js"></script>
<script src="/tpl/main_n/styles/js/modules/JSJaC/jsjac.js"></script>
<script src="/tpl/main_n/styles/js/jitsi.js"></script>

  <script>
    window.chatClass = new YRChat();
    window.jitsiClass = new JitsiChat();

    jitsiClass.startListener();
  </script>
<?}?>

<script>
$('#searchform').on('submit', function(e){
  
  e.preventDefault();

  let value = $('#searchform :input').first().val();

  let tag = encodeURIComponent(value).replace(/%20/g, '+');

  location.href=`https://youinroll.com/show/${tag}?type=video`;

  return false;
})

</script>

<?=$additionalJs?>

<div id="fb-root"></div>
<script>
(function(d, s, id) {
    let js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s);
    js.id = id;
    js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId='.Fb_Key.'";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
</script>
<?=extra_js()?>
<?=_pjs(get_option('googletracking'))?>
<script>
$(document).ready(function() {
    $("#show-sidebar").click(function() {
        $(".hamburger").toggleClass("is-active");
    });
});
</script>
</body>
</html>