<?php

class MySQLDump { 
    var $cmd; 
    var  $chmodname; 
    function MySQLDump($dbUser, $dbPass, $dbName, $dest, $filenamedate, $zip = 'gz',$dbHost ='') 
    { 
        $zip_util = array('gz'=>'gzip','bz2'=>'bzip2'); 
        if (array_key_exists($zip, $zip_util)) { 
            $fname = $filenamedate . '.sql.' . $zip;  
            $this->cmd = 'mysqldump -u' . $dbUser . ' -p' . $dbPass .  ' -h' . $dbHost . ' ' . $dbName . '| ' . $zip_util[$zip] . ' >' . $dest . '/' . $fname; 
        } else { 
            $fname = $filenamedate . '.sql';  
            $this->cmd = 'mysqldump -u' . $dbUser . ' -p' . $dbPass .  ' -h' . $dbHost . ' ' . $dbName . ' >' . $dest . '/' . $fname; 
        } 
        $this->chmodname = $dest ."/". $filenamedate.".sql";
    }
    function backup() 
    { 
        exec($this->cmd, $error); 
        if ($error) { 
        trigger_error('Backup failed: ' . $error); 
        } 
        // chmod($this->chmodname,0777);
    } 
}


			

$dbHost         = 'sql5c40n.carrierzone.com';
//$dbHost         = 'localhost';
$dbUser         = 'jjixgbv9my802728';       
$dbPass         = 'We3b2!12';           
$dbName         = 'lifemark_jjixgbv9my802728';        
$dest           = "/services/webpages/f/o/foxtrotsoftware.com/public/foxtrot_online/db";
$filenamedate   = 'FDB-'.date('dmYHis',mktime(gmdate('H'),gmdate('i')+330,gmdate('s'),gmdate('m'),gmdate('d'),gmdate('Y'))); 
$zip            = 'gz';
//$to             = "uratombackup@gmail.com";
$subject        = "FOX Live DB Backup at ".$filenamedate;
$body           = "FOX subsidy DB backup is done ".date('d-m-Y H:i:s');
$files          = $dest.'/'.$filenamedate . '.sql.' . $zip;


$mysqlDump = new MySQLDump($dbUser, $dbPass, $dbName, $dest, $filenamedate, $zip, $dbHost); 
$mysqlDump->backup(); 

?>