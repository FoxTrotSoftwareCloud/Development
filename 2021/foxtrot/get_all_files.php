<?php
ini_set('max_execution_time', 0);
define('DS',"/");

function dirToArray($dir,$folder) { 
   
   $result = array(); 

   $cdir = scandir($dir); 
   /*foreach ($cdir as $key => $value) 
   { 
      if (!in_array($value,array(".",".."))) 
      { 
         if (is_dir($dir.DS.$value)) 
         { 
            //dirToArray($dir.DS.$value); 
            $folder=$value;
            //mysql_query("insert into all_files set path='".$dir.DS.$value."', folder='".$folder."'");
            dirToArray($dir.DS.$value,$folder); 
         } 
         else 
         { 
            $filesize=filesize($dir.DS.$value)/1024;
            //mysql_query("insert into all_files set path='".$dir.DS."', folder='".$folder."',file='".$value."',size='".$filesize."'"); 
         } 
      } 
   } */
   
   return $cdir; 
} 
$all_file_array = dirToArray("E:\idc_file_foxtrot",'');
echo '<pre>';print_r($all_file_array);exit;
echo "done";
?>