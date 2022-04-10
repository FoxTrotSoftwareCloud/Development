<?php
    //test
    require_once("include/config.php");
    require_once(DIR_FS."islogin.php");
    $action = isset($_GET['action'])&&$_GET['action']!=''?$dbins->re_db_input($_GET['action']):'view';
    $id = isset($_GET['id'])&&$_GET['id']!=''?$dbins->re_db_input($_GET['id']):0;
    $ftp_id = isset($_GET['ftp_id'])&&$_GET['ftp_id']!=''?$dbins->re_db_input($_GET['ftp_id']):0;
    $return = array();
    $host_name = '';
    $user_name = '';
    $password = '';
    $confirm_password = '';
    $folder_location = '';
    $status = 1;
    $process_file = '';
    $ftp_file_type = '';
    $get_file_data = '';
    $return_exception = array();
    $total_commission_amount = 0;
    $dataTableOrder = isset($_GET['reprocessed']) ? '[3, "desc"], [0, "asc"], [1, "asc"]' : '';
    
    $instance = new import();
    $get_product_category = $instance->select_category();
    $get_objective = $instance->get_objectives_data();
    $instance_broker = new broker_master();
    $get_broker = $instance_broker->select(1);
    $instance_client = new client_maintenance();
    $get_client = $instance_client->select(1);
    $instance_sponsor = new manage_sponsor();
    $get_sponsor = $instance_sponsor->select_sponsor();
    $instance_batches = new batches();
    $instance_importGeneric = new import_generic();

    if(isset($_GET['id']) && $_GET['id'] !='')
    {
        $get_total_commission = $instance->get_total_commission_amount($_GET['id']);
        $total_commission_amount = $get_total_commission;
    }

    if(isset($_POST['go'])&& $_POST['go']=='go'){

        $id = isset($_POST['id']) ? $instance->re_db_input($_POST['id']) : 0;
        $process_file = isset($_POST['process_file_'.$id]) ? $instance->re_db_input($_POST['process_file_'.$id]) : '';
        // "File Type" is populated in select_current_files($file_id, sfrBreakout) function
        if (!isset($_POST['process_file_type'])) {
            $file_type = 1;
        } else if ($_POST['process_file_type'] == 'DST Commission'){
            $file_type = 2;
        } else if ($_POST['process_file_type'] == 'Security File'){
            $file_type = 3;
        } else if (stripos($_POST['process_file_type'],'generic commission')!==false){
            $file_type = 9;
        } else {
            $file_type = 1;
        }

        // Action Choices
        if(isset($process_file) && $process_file == 1)
        {
            $return = $instance->delete_current_files($id);
        }
        else if(isset($process_file) && $process_file == 2)
        {
            $return = $instance->process_current_files($id);
            
            if($return == ''){
                header("location:".SITE_URL."import.php?reprocessed=1");
                exit;
            } else {
                $dataTableOrder = '';
            }
        }
        else if(isset($process_file) && $process_file == 3)
        {
            header("location:".CURRENT_PAGE."?tab=processed_files&id=".$id);exit;
        }
        else if(isset($process_file) && $process_file == 4)
        {
            header("location:".CURRENT_PAGE."?tab=review_files&id=$id&file_type=$file_type");
            exit;
        }
        else if(isset($process_file) && $process_file == 5)
        {
            $return = $instance->reprocess_current_files($id, $file_type, 0);
        }
        else if(isset($process_file) && $process_file == 6)
        {
            $return = $instance->move_to_archived_files($id);
        }
        else if(isset($process_file) && $process_file == 7)
        {
            header("location:".CURRENT_PAGE."?tab=preview_files&id=".$id);exit;
        }
        else{
            header("location:".CURRENT_PAGE);exit;
        }

        if($return===true){
            header("location:".CURRENT_PAGE.'?reprocessed=1');
            exit;
        }
        else{
            $error = !isset($_SESSION['warning'])?$return:'';
        }
    }
    else if (isset($_POST['note']) and $_POST['note'] == "save_note") {
        $return = $instance->save_note($_POST);
        if($return == true) {
            header("location:".CURRENT_PAGE);exit;
        }else{
            $error = !isset($_SESSION['warning'])?$return:'';
        }
    }
    else if(isset($_GET['action']) && $_GET['action'] == 'process_file' && $_GET['file_id'] !='')
    {
        $id = $_GET['file_id'];
        $return = $instance->process_current_files($id);
        if($return == '')
        {
            header("location:".SITE_URL."import.php");exit;
        }
    }
    else if(isset($_POST['go_archive']) && $_POST['go_archive'] == 'go_archive')
    {
        $id = isset($_POST['id'])?$instance->re_db_input($_POST['id']):0;
        header("location:".CURRENT_PAGE."?tab=view_processed_files&id=".$id);exit;
    }
    else if(isset($_GET['action'])&& $_GET['action']=='process_all'){

        $get_all_current_files = $instance->select_current_file_id();

        foreach($get_all_current_files as $key_file_id=>$val_file_id)
        {
            $file_id = isset($val_file_id['id'])?$instance->re_db_input($val_file_id['id']):0;
            $return = $instance->process_current_files($file_id);
        }

        if($return===true){

            header("location:".SITE_URL."import.php");exit;
        }
        else{
            header("location:".SITE_URL."import.php");exit;
        }
    }
    else if(isset($_POST['submit'])&& $_POST['submit']=='Save'){
        $id = isset($_POST['id'])?$instance->re_db_input($_POST['id']):0;
        $host_name = isset($_POST['host_name'])?$instance->re_db_input($_POST['host_name']):'';
        $user_name = isset($_POST['user_name'])?$instance->re_db_input($_POST['user_name']):'';
        $password = isset($_POST['password'])?$instance->re_db_input($_POST['password']):'';
        $confirm_password = isset($_POST['confirm_password'])?$instance->re_db_input($_POST['confirm_password']):'';
        $folder_location = isset($_POST['folder_location'])?$instance->re_db_input($_POST['folder_location']):'';
        $ftp_file_type = isset($_POST['ftp_file_type'])?$instance->re_db_input($_POST['ftp_file_type']):'';
        $status = isset($_POST['status'])?$instance->re_db_input($_POST['status']):'';

        $return = $instance->insert_update_ftp($_POST);
        if($return===true){

            header("location:".CURRENT_PAGE."?tab=open_ftp");exit;

        }
        else{
            $error = !isset($_SESSION['warning'])?$return:'';
        }
    }
    else if(isset($_POST['resolve_exception']) && $_POST['resolve_exception']=='Resolve Exception')
    {
        $exception_file_id = isset($_POST['exception_file_id'])?$instance->re_db_input($_POST['exception_file_id']):0;
        $exception_data_id = isset($_POST['exception_data_id'])?$instance->re_db_input($_POST['exception_data_id']):0;
        $exception_field = isset($_POST['exception_field'])?$instance->re_db_input($_POST['exception_field']):'';

        if($exception_field == 'u5'){
            $exception_value = isset($_POST['exception_value_date'])?$instance->re_db_input($_POST['exception_value_date']):'';
        }else{
            $exception_value = isset($_POST['exception_value'])?$instance->re_db_input($_POST['exception_value']):'';
        }

        $return = $instance->resolve_exceptions($_POST);

        if($return===true){
            echo '1';
            exit;
        }else{
            $error = !isset($_SESSION['warning'])?$return:'';
        }

        echo $error;
        exit;
    }
    else if(isset($_POST['fetch_files']) && $_POST['fetch_files']== 'Fetch Files')
    {
        $return = $instance->insert_update_files($_POST);
        if($return===true){
            echo '1';exit;
        }
        else{
            $error = !isset($_SESSION['warning'])?$return:'';
        }
        echo $error;
        exit;
    }
    else if(isset($_POST['upload_generic_csv_file']) && $_POST['upload_generic_csv_file']=='upload_generic_csv_file')
    {
        $error = '';
        $uploaded =  0;

        // Validate file parameters
        if (empty($_FILES['upload_generic_csv_file']['name'])){
            $error = 'No file specified. Procedure cancelled.';
        } else if (file_exists(rtrim($instance_importGeneric->dataInterface['local_folder'],"/")."/".$_FILES['upload_generic_csv_file']['name'])){
            $error = "File '".$_FILES['upload_generic_csv_file']['name']."' already uploaded. Please select another file or rename current file.";
        } else if (empty($_POST['generic_sponsor'])){
            $error = "No SPONSOR specified. Please select a SPONSOR from the dropdown list.";
        } else if (empty($_POST['generic_product_category'])){
            $error = "No PRODUCT CATEGORY specified. Please select a PRODUCT CATEGORY from the dropdown list.";
        }

        // Upload file
        if (empty($error)){
            $uploaded = $instance->upload_file($_FILES['upload_generic_csv_file'], $instance_importGeneric->dataInterface['local_folder']);
        }
        if ($uploaded){
            // $uploaded = $instance_importGeneric->process_file($_FILES['upload_generic_csv_file']['name']);
            $uploaded = $instance_importGeneric->process_file($_FILES['upload_generic_csv_file']['name'], $_POST['generic_sponsor'], $_POST['generic_product_category']);
        } else if (empty($error)){
            $error = 'File not uploaded. Please check privileges on the directory & file: '.$$_FILES['upload_generic_csv_file']['name'];
        }
        
        if(empty($error) AND $uploaded){
            $_SESSION['success'] = "File '".$_FILES['upload_generic_csv_file']['name']."' uploaded and processed.";
            header("location:".CURRENT_PAGE."?action=view&reprocessed=1");
        } else{
            $_SESSION['warning'] = !empty($error) ? $error : 'Problem occurred. File not processed.';
            header("location:".CURRENT_PAGE."?tab=open_ftp");
        }
        exit;
    }
    else if($action=='edit_ftp' && $ftp_id>0)
    {
        $return = $instance->edit_ftp($ftp_id);
        $ftp_id = isset($return['id'])?$instance->re_db_output($return['id']):0;
        $host_name = isset($return['host_name'])?$instance->re_db_output($return['host_name']):'';
        $user_name = isset($return['user_name'])?$instance->re_db_output($return['user_name']):'';
        $password = isset($return['password'])?$instance->re_db_output($return['password']):'';
        $folder_location = isset($return['folder_location'])?$instance->re_db_output($return['folder_location']):'';
        $ftp_file_type = isset($return['ftp_file_type'])?$instance->re_db_output($return['ftp_file_type']):'';
        $status = isset($return['status'])?$instance->re_db_output($return['status']):'';
    }
    else if(isset($action) && $action=='open_ftp')
    {//print_r($action);exit;
        header("location:".CURRENT_PAGE."?tab=open_ftp");exit;
    }
    else if(isset($_GET['tab']) && $_GET['tab'] =='open_ftp')
    {
        $return_ftplist = $instance->select_ftp();
    }
    else if(isset($_GET['tab']) && $_GET['tab'] =='get_ftp' && $ftp_id>0)
    {
        $return_ftp_host = $instance->select_ftp_user($ftp_id);

    }
    else if(isset($_GET['action'])&&$_GET['action']=='ftp_status' && $ftp_id>0 &&isset($_GET['status'])&&($_GET['status']==0 || $_GET['status']==1))
    {
        $status = $instance->re_db_input($_GET['status']);
        $return = $instance->ftp_status($ftp_id,$status);
        if($return==true){
            header('location:'.CURRENT_PAGE."?tab=open_ftp");exit;
        }else{
            header('location:'.CURRENT_PAGE."?tab=open_ftp");exit;
        }
    }
    else if(isset($_GET['action'])&&$_GET['action']=='delete_ftp' && $ftp_id>0)
    {
        $return = $instance->ftp_delete($ftp_id);
        if($return==true){
            header('location:'.CURRENT_PAGE."?tab=open_ftp");exit;
        }else{
            header('location:'.CURRENT_PAGE."?tab=open_ftp");exit;
        }
    }
    else if(isset($_GET['broker_termination']) && $_GET['broker_termination']!='')
    {
        $broker_id = $instance->re_db_input($_GET['broker_termination']);
        $return = $instance->check_u5_termination($broker_id);
        if($return != ''){
            $current_date = date('Y-m-d');

            if($current_date>$return){
                echo date('m/d/Y',strtotime($return));exit;
            }else{
                echo '0';exit;
            }
        }else{
            echo '0';exit;
        }
    }
    else if(isset($_GET['error_code_id']) && $_GET['error_code_id']=='19'){
        $a=0;
    }
    else if($action=='view'){
        $return = $instance->select_current_files();//echo '<pre>';print_r($return);exit;
    }

    $content = "import";
    include(DIR_WS_TEMPLATES."main_page.tpl.php");
?>
