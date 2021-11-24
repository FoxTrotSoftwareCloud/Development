<?php
    @session_start();
    date_default_timezone_set ("America/Los_Angeles");
    ini_set('display_errors',1);
    define('HTTP_HOST','http://'.$_SERVER['HTTP_HOST'].'/');
	// Changed to dynamically find the local path - 11/04/21
    $localPath = substr(str_replace($_SERVER['DOCUMENT_ROOT'],'',dirname($_SERVER['SCRIPT_FILENAME'])),1)."/";
	//define('HTTP_SERVER', HTTP_HOST.'Cloudfox/'); 
	define('HTTP_SERVER', HTTP_HOST.$localPath); 
	define('ENABLE_SSL', false);
    define('IS_LIVE',0);
    
    define('SITE_URL', HTTP_HOST.$localPath);
    
	define('DIR_FS',$_SERVER['DOCUMENT_ROOT']."/".$localPath);
	define('DIR_FS_INCLUDES',DIR_FS.'include/');
    define('DIR_FS_CLASSES',DIR_FS_INCLUDES.'classes/');
	define('DIR_WS_TEMPLATES', DIR_FS.'templates/');
	define('DIR_WS_CONTENT', DIR_WS_TEMPLATES.'content/');
    
    define('SITE_ASSETS', SITE_URL.'assets/');
    define('SITE_CSS', SITE_ASSETS.'css/');
    define('SITE_JS', SITE_ASSETS.'js/');
    define('SITE_IMAGES', SITE_ASSETS.'images/');
    define('SITE_PLUGINS', SITE_ASSETS.'plugins/');
    
    define('SITE_URL_ADMIN', SITE_URL.'admin/');
	define('DIR_FS_ADMIN',DIR_FS.'admin/');
    define('DIR_WS_TEMPLATES_ADMIN', DIR_WS_TEMPLATES.'admin/');
	define('DIR_WS_CONTENT_ADMIN', DIR_WS_TEMPLATES_ADMIN.'content/');
    
    define('SITE_ADMIN_ASSETS', SITE_URL_ADMIN.'assets/');
    define('SITE_ADMIN_CSS', SITE_ADMIN_ASSETS.'css/');
    define('SITE_ADMIN_JS', SITE_ADMIN_ASSETS.'js/');
    define('SITE_ADMIN_IMAGES', SITE_ADMIN_ASSETS.'images/');
    define('SITE_ADMIN_PLUGINS', SITE_ADMIN_ASSETS.'plugins/');
    
    define('DIR_IMAGES_ADMIN',DIR_FS.'admin-images/');
    define('SITE_IMAGES_ADMIN',SITE_URL.'admin-images/');
    define('DEFAULT_IMAGE_ADMIN','default.jpg');
    
    define('SITE_LOGO','logo.png');
    define('SITE_FAVICON','favicon.png');
    //define('SMTP_HOST','smtp.gmail.com');
    define('SMTP_HOST','mail.foxtrotsoftware.com');
    //define('SMTP_ID','norepaly.kpi@gmail.com');
    define('SMTP_ID','demo1@foxtrotsoftware.com');
    //define('SMTP_PASSWORD','noreply123');
    define('SMTP_PASSWORD','We3b2!12');
    
    define('SITE_EXCEL',DIR_FS_INCLUDES.'PHPExcel/');
    include(DIR_FS_INCLUDES.'TCPDF-master/tcpdf.php');
    include(DIR_FS_INCLUDES.'phpmailer/class.phpmailer.php');
    include(DIR_FS_INCLUDES.'phpmailer/class.smtp.php');
    include(DIR_FS_INCLUDES.'phpmailer/PHPMailerAutoload.php');
    
    
    // local config
    /*
	define('DB_SERVER', '97.74.232.123');
	define('DB_SERVER_USERNAME', 'iipldemo_foxtrot');
	define('DB_SERVER_PASSWORD', 'Ij5Xyv{A}Ati');
	define('DB_DATABASE', 'iipldemo_foxtrot');
	define('USE_PCONNECT', 'false');
	define('STORE_SESSIONS', 'mysql');
    */
    
        // 10/30/21 Use the correct server
    if ($_SERVER['SERVER_NAME']=='localhost') {
        // local config -> 10/30/21 LI
        define('DB_SERVER', 'localhost');
        define('DB_SERVER_USERNAME', 'root');
        define('DB_SERVER_PASSWORD', '');
        define('DB_DATABASE', 'cloudfox');
        define('USE_PCONNECT', 'false');
        define('STORE_SESSIONS', 'mysql');
    } else {
        define('DB_SERVER', 'sql5c40n.carrierzone.com');
        define('DB_SERVER_USERNAME', 'jjixgbv9my353010');
        define('DB_SERVER_PASSWORD', 'We3b2!12');
        define('DB_DATABASE', 'CloudFox_jjixgbv9my353010');
        define('USE_PCONNECT', 'false');
        define('STORE_SESSIONS', 'mysql');
    }

    
    
    include(DIR_FS_INCLUDES.'tables.php');
    include(DIR_FS_INCLUDES.'db.class.php');
    include(DIR_FS_INCLUDES.'classes.php');    
    $dbins = new db();
    //exit;
    
    include(DIR_FS_INCLUDES.'constants.php');
    //include(DIR_FS_INCLUDES.'admin.php');
    include(DIR_FS_INCLUDES.'user.php');
    
?>