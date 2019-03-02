<?php
try{
	require_once "backstage.php";
	//session_start();

	//Check if cookies are in use for log in
	/*if(permrep::is_remembered()){
		if(basename($_SERVER["PHP_SELF"], '.php') == 'login'){ //If page is login
			header("Location: dashboard.php"); //redirect to dashboard
		}
	} elseif(!isset($_SESSION['permrep_obj']) && basename($_SERVER["PHP_SELF"], '.php') != 'login'){
		header("Location: login.php"); //redirect to login
	}*/
    if(isset($_SESSION['company_name']) || isset($_GET['company_name']))
    {
    	if(permrep::is_remembered()){
    		if(basename($_SERVER["PHP_SELF"], '.php') == 'login'){ //If page is login
    			header("Location: dashboard.php"); //redirect to dashboard
    		}
    	} elseif(!isset($_SESSION['permrep_obj']) && basename($_SERVER["PHP_SELF"], '.php') != 'login'){
    		header("Location: login.php"); //redirect to login
    	}
    }elseif(!isset($_SESSION['permrep_obj']) && basename($_SERVER["PHP_SELF"], '.php') != 'login'){
		header("Location: login.php"); //redirect to login
	}

	//Security
	$_GET["company_name"] = isset($_GET["company_name"])?addslashes(htmlentities($_GET["company_name"])):'';

	//Choose DB
	if(!isset($_SESSION['db_name']) || ($_SESSION['db_name'] != $_GET["company_name"])){
		db_choose($_GET);
	}
    else
    {
        db_choose($_GET);
    }

	echo '<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=UA-125789539-1"></script>
		<script>
			window.dataLayer = window.dataLayer || [];
			function gtag(){dataLayer.push(arguments);}
			gtag("js", new Date());
			
			gtag("config", "UA-125789539-1");
		</script>';

	echo "<script>
			window.company_name = '{$_SESSION['company_name']}';
		</script>";

	db_connect();
} catch(Exception $e){
	$GLOBALS['thrown_exception'] = $e->getMessage();
}

