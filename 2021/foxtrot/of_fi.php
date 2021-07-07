<?php
    require_once("include/config.php");
    require_once(DIR_FS."islogin.php");
    
    $action = isset($_GET['action'])&&$_GET['action']!=''?$dbins->re_db_input($_GET['action']):'view';
    $id = isset($_GET['id'])&&$_GET['id']!=''?$dbins->re_db_input($_GET['id']):0;
    
    $instance = new ofac_fincen();
    //$report = $instance->select_fincen_scan_report();
    if(isset($_POST['import'])&& $_POST['import']=='OFAC Scan'){
    
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
            }//echo '<pre>';print_r($get_array_data);exit;
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
    else if(isset($_POST['import_fincen'])&& $_POST['import_fincen']=='FINCEN Scan'){
        
        $filename=$_FILES["file_fincen"]["tmp_name"];	
        $array = array();
        $get_array_data = array();
        
    	 if($_FILES["file_fincen"]["size"] > 0)
    	 {
    	  	$file = fopen($filename, "r");
            while (($getData = fgetcsv($file, 10000, ",")) !== FALSE)
             {
                $fincen_lastname = isset($getData[1])?$instance->re_db_input($getData[1]):'';
                $fincen_firstname = isset($getData[2])?$instance->re_db_input($getData[2]):'';
                $fincen_miname = isset($getData[3])?$instance->re_db_input($getData[3]):'';
                $fincen_address = isset($getData[12])?$instance->re_db_input($getData[12]):'';
                $fincen_country = isset($getData[16])?$instance->re_db_input($getData[16]):'';
                $fincen_phone = isset($getData[17])?$instance->re_db_input($getData[17]):'';
                $fincen_tracking_no = isset($getData[0])?$instance->re_db_input($getData[0]):'';
                $fincen_keyno = isset($getData[9])?$instance->re_db_input($getData[9]):'';
                $fincen_no_type = isset($getData[10])?$instance->re_db_input($getData[10]):'';
                $fincen_dob = isset($getData[11])?$instance->re_db_input($getData[11]):'';
                
                $array[]=array("fincen_lastname"=>$fincen_lastname,"fincen_firstname"=>$fincen_firstname,"fincen_miname"=>$fincen_miname,"fincen_address"=>$fincen_address,"fincen_country"=>$fincen_country,"fincen_phone"=>$fincen_phone,"fincen_tracking_no"=>$fincen_tracking_no,"fincen_keyno"=>$fincen_keyno,"fincen_no_type"=>$fincen_no_type,"fincen_dob"=>$fincen_dob);
             }//echo '<pre>';print_r($array);exit;
            foreach($array as $key=>$val)
            { 
                $checkName=$instance->get_fincen_data($val['fincen_lastname'],$val['fincen_firstname']);
                if(is_array($checkName) && count($checkName)>0){
                    
                    $get_array_data[$key] = $checkName;
                    array_push($get_array_data[$key],$val);                     
                }
                //$get_array_data[$key] = $val;
            }//echo '<pre>';print_r($get_array_data);exit;            
            
            $total_scan = isset($array)?$instance->re_db_input(count($array)):0;
            
            if($get_array_data != array())
            {
                $return = $instance->insert_update_fincen($get_array_data,$total_scan);
                
                if($return===true){
                    
                        header('location:'.CURRENT_PAGE.'?tab=tab_d&open=report_fincen');exit;
                }
                else{
                    $error = !isset($_SESSION['warning'])?$return:'';
                }
            }
            else
            {
                $_SESSION['warning'] = "Please Select valid file.";
                header('location:'.CURRENT_PAGE.'?tab=tab_d');exit;
            }
            fclose($file);	
    	 }
    }
    /*else if(isset($_GET['action'])&&$_GET['action']=='print'&&isset($_GET['id'])&&$_GET['id']>0)
    {
        $id = $instance->re_db_input($_GET['id']);
        header("location:report_ofac_client_check.php?id=".$id);exit;
    }
    else if(isset($_GET['action'])&&$_GET['action']=='print_fincen'&&isset($_GET['id'])&&$_GET['id']>0)
    {
        $id = $instance->re_db_input($_GET['id']);
        header("location:report_fincen_client_check.php?id=".$id);exit;
    }*/
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
    else if(isset($_GET['action'])&&$_GET['action']=='delete_fincen'&&isset($_GET['id'])&&$_GET['id']>0)
    {
        $id = $instance->re_db_input($_GET['id']);
        $return = $instance->delete_fincen($id);
        if($return==true){
            header('location:'.CURRENT_PAGE.'?tab=tab_d');exit;
        }
        else{
            header('location:'.CURRENT_PAGE.'?tab=tab_d');exit;
        }
    }
    else if($action=='view'){
        
        $return = $instance->select_scan_file();
        $return_fincen = $instance->select_fincen_scan_file();
        
    }	 

    $content = "of_fi";
    include(DIR_WS_TEMPLATES."main_page.tpl.php");
?>