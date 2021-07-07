<?php
    include_once("include/config.php");
    session_destroy();
    header("location:".SITE_URL."sign-in");exit;
?>