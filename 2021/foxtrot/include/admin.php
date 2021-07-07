<?php
    $current_admin = array();
    
    if(isset($_SESSION['admin_id'])&&$_SESSION['admin_id']>0){
        $instance = new admin_master();
        $current_admin = $instance->get_by_id($_SESSION['admin_id']);
    }
?>