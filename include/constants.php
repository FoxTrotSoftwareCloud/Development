<?php
    
    define('DIR_WS_USERS',DIR_FS.'users/');
    define('SITE_USERS',SITE_URL.'users/');
    
    define('DIR_WS_UPLOAD',DIR_FS.'upload/');
    define('SITE_UPLOAD',SITE_URL.'upload/');
    
    define('DEFAULT_URSER_IMAGE','default.jpg');
    
    define('CURRENT_FILE',basename($_SERVER['SCRIPT_FILENAME']));
    define('CURRENT_PAGE',HTTP_HOST.ltrim(parse_url($_SERVER['REQUEST_URI'])['path'],'/'));
    
    define('CURRENT_PAGE_QRY',trim(CURRENT_PAGE.'?'.$_SERVER['QUERY_STRING'],'?'));
    define('CURRENT_DATETIME',date('Y-m-d H:i:s'));
    
    define('STATUS_MESSAGE','Status changed successfully.');
    define('USER_REGISTER_MESSAGE','User registred successfully.');
    define('DELETE_MESSAGE','Record deleted successfully.');
    define('DELETE_IMAGE_MESSAGE','Image deleted successfully.');
    define('INSERT_MESSAGE','Data successfully saved.');
    define('UPDATE_MESSAGE','Data updated successfully.');
    define('UNKWON_ERROR','Something went wrong, please try again.');
	
	define('PASSWORD_UPDATE_SUCCESS','Your password updated successfully.');
    
    /* encryption */
    define('SECRET_KEY','lx.kCe@%D');
    define('SECRET_IV','Kuc&*sX$2x');
    
    $q = "SELECT * FROM `".SITE_OPTIONS."`";
    $res = $dbins->re_db_query($q);
    while($row = $dbins->re_db_fetch_array($res)){
        $key = strtoupper($row['name']);
        $key = str_replace(" ","_",$key);
        if (!defined($key)){
            define($key,$row['value']);
        }
    }
    
?>