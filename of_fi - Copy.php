<?php
    require_once("include/config.php");
    require_once(DIR_FS."islogin.php");
    
    $action = isset($_GET['action'])&&$_GET['action']!=''?$dbins->re_db_input($_GET['action']):'view';
    $id = isset($_GET['id'])&&$_GET['id']!=''?$dbins->re_db_input($_GET['id']):0;
    $instance = new ofac_fincen();
    
    if(isset($_POST['import'])&& $_POST['import']=='OFAC System Scan'){
    
        $filename=$_FILES["file"]["tmp_name"];	
        $array = array();
        $get_array_data = array();
        
    	 if($_FILES["file"]["size"] > 0)
    	 {
    	  	$file = fopen($filename, "r");
            while (($getData = fgetcsv($file, 10000, ",")) !== FALSE)
             {
                $id_no = isset($getData[0])?$instance->re_db_input($getData[0]):0;
                $sdn_name = isset($getData[1])?$instance->re_db_input($getData[1]):'';
                $program = isset($getData[3])?$instance->re_db_input($getData[3]):'';
                $other_data = isset($getData[11])?$instance->re_db_input($getData[11]):'';
                
                $array[]=array("id_no"=>$id_no,"sdn_name"=>$sdn_name,"program"=>$program,"other_data"=>$other_data);
             }
            foreach($array as $key=>$val)
            { 
                $checkName=$instance->get_ofac_data($val['sdn_name'],$val['other_data']);
                if(is_array($checkName) && count($checkName)>0){
                    
                    $get_array_data[$key] = $checkName;
                    array_push($get_array_data[$key],$val);                     
                }
                //$get_array_data[$key] = $val;
            }echo '<pre>';print_r($get_array_data);exit;
            $total_scan = isset($array)?$instance->re_db_input(count($array)):0;
            
            if($get_array_data != array())
            {
                $return = $instance->insert_update($get_array_data,$total_scan);
                
                if($return===true){
                    
                        header('location:'.CURRENT_PAGE.'?tab=tab_b&open=report');exit;
                }
                else{
                    $error = !isset($_SESSION['warning'])?$return:'';
                }
            }
            else
            {
                $_SESSION['warning'] = "Please Select valid file.";
                header('location:'.CURRENT_PAGE.'?tab=tab_b');exit;
            }
            fclose($file);	
    	 }
    }
    else if(isset($_GET['action'])&&$_GET['action']=='print'&&isset($_GET['id'])&&$_GET['id']>0)
    {
        $id = $instance->re_db_input($_GET['id']);
        header("location:report_ofac_client_check.php?id=".$id);exit;
    }
    else if(isset($_GET['action'])&&$_GET['action']=='delete'&&isset($_GET['id'])&&$_GET['id']>0)
    {
        $id = $instance->re_db_input($_GET['id']);
        $return = $instance->delete($id);
        if($return==true){
            header('location:'.CURRENT_PAGE.'?tab=tab_b');exit;
        }
        else{
            header('location:'.CURRENT_PAGE.'?tab=tab_b');exit;
        }
    }
    else if($action=='view'){
        
        $return = $instance->select_scan_file();
        
    }	 

    $content = "of_fi";
    include(DIR_WS_TEMPLATES."main_page.tpl.php");
?>