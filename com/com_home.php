<?php 

if(isset($_GET['test']))
{
    $YNRtemplate->include('/home-dev.php');
} else
{
    $YNRtemplate->include('/home.php');
}


?>