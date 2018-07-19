<?php
    $current_user = array();
    
    if(isset($_SESSION['user_id'])&&$_SESSION['user_id']>0){
        $instance = new user_master();
        $current_user = $instance->get_by_id($_SESSION['user_id']);
    }
?>