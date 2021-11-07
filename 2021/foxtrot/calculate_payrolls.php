<?php
    require_once("include/config.php");
    require_once(DIR_FS."islogin.php");
    
    $action = isset($_GET['action'])&&$_GET['action']!=''?$dbins->re_db_input($_GET['action']):'view';
    $id = isset($_GET['id'])&&$_GET['id']!=''?$dbins->re_db_input($_GET['id']):0;
    
    $instance = new payroll();
    // Use "Payroll Date" dropdown instead of user input box. Report data stored by Payroll ID (payroll_upload table->id) 11/05/21 li
    $get_payroll_uploads = $instance->get_payroll_uploads(0,1);
    $payrollSelectedKey= 0;
    // $payroll_date = date('m/d/Y');
    // $payroll_id = 0;
    // if(count($get_payroll_uploads)>0)
    // {
    //     $payroll_date = $get_payroll_uploads[$payrollSelectedKey]['payroll_date'];
    //     $payroll_id = $get_payroll_uploads[$payrollSelectedKey]['id'];
    //     $is_payroll_calculated = 1;
    // }
    // else
    // {
    //     $is_payroll_calculated = 0;
    // }
    
    if(isset($_POST['submit'])&& $_POST['submit']=='Proceed'){
        $payrollSelectedKey = $_POST['payroll_selected_key'];
        $payroll_date = $get_payroll_uploads[$_POST['payroll_selected_key']]['payroll_date'];
        $payroll_id = $get_payroll_uploads[$_POST['payroll_selected_key']]['id'];

        $return = $instance->calculate_payroll(['payroll_date'=>$payroll_date, 'payroll_id'=>$payroll_id],$payroll_date);
        
        if($return===true){
            // Commented out: Code not functional. Probably a future mod - 11/07/21 li
           // header("location:".SITE_URL."review_payroll.php?action=view");exit;
        }
        else{
            $error = !isset($_SESSION['warning'])?$return:'';
        }
    }
    
    $content = "calculate_payrolls";
    include(DIR_WS_TEMPLATES."main_page.tpl.php");
?>