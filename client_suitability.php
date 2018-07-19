<?php
    require_once("include/config.php");
    require_once(DIR_FS."islogin.php");
    
    $error = '';
    $type = '';
    $action = isset($_GET['action'])&&$_GET['action']!=''?$dbins->re_db_input($_GET['action']):'view_objective';
    
    $id = isset($_GET['id'])&&$_GET['id']!=''?$dbins->re_db_input($_GET['id']):0;
    
    $instance = new client_suitebility_master();
    
    if(isset($_POST['submit_objective'])&& $_POST['submit_objective']=='Save'){
        	 
        $id = isset($_POST['id'])?$instance->re_db_input($_POST['id']):0;
        $option = isset($_POST['option'])?$instance->re_db_input($_POST['option']):''; 
        $return = $instance->insert_update_objective($_POST);       
        if($return===true){
            header("location:".CURRENT_PAGE."?action=view_objective");exit;
        }
        else{
            $error = !isset($_SESSION['warning'])?$return:'';
        }
    }
    else if($action=='edit_objective' && $id>0){
        $return = $instance->edit_objective($id);
        $option = $instance->re_db_output($return['option']);        
    } 
    else if(isset($_GET['action'])&&$_GET['action']=='delete_objective'&&isset($_GET['id'])&&$_GET['id']>0)
    {
        $id = $instance->re_db_input($_GET['id']);
        $return = $instance->delete_objective($id);
        if($return==true){
            header('location:'.CURRENT_PAGE.'?action=view_objective');exit;
        }
        else{
            header('location:'.CURRENT_PAGE);exit;
        }
    }
    else if($action=='view_objective'){    
        $return_objective = $instance->select_objective();
    }
    if(isset($_POST['submit_income'])&& $_POST['submit_income']=='Save'){
        	 
        $id = isset($_POST['id'])?$instance->re_db_input($_POST['id']):0;
        $option = isset($_POST['option'])?$instance->re_db_input($_POST['option']):''; 
        $return = $instance->insert_update_income($_POST);       
        if($return===true){
            header("location:".CURRENT_PAGE."?action=view_income");exit;
        }
        else{
            $error = !isset($_SESSION['warning'])?$return:'';
        }
    }
    else if($action=='edit_income' && $id>0){
        $return = $instance->edit_income($id);
        $option = $instance->re_db_output($return['option']);        
    } 
    else if(isset($_GET['action'])&&$_GET['action']=='delete_income'&&isset($_GET['id'])&&$_GET['id']>0)
    {
        $id = $instance->re_db_input($_GET['id']);
        $return = $instance->delete_income($id);
        if($return==true){
            header('location:'.CURRENT_PAGE.'?action=view_income');exit;
        }
        else{
            header('location:'.CURRENT_PAGE);exit;
        }
    }
    else if($action=='view_income'){    
        $return_income = $instance->select_income();
    }
    else if(isset($_POST['submit_horizon'])&& $_POST['submit_horizon']=='Save'){
        	 
        $id = isset($_POST['id'])?$instance->re_db_input($_POST['id']):0;
        $option = isset($_POST['option'])?$instance->re_db_input($_POST['option']):''; 
        $return = $instance->insert_update_horizon($_POST);       
        if($return===true){
            header("location:".CURRENT_PAGE.'?action=view_horizon');exit;
        }
        else{
            $error = !isset($_SESSION['warning'])?$return:'';
        }
    }
    else if($action=='edit_horizon' && $id>0){
        $return = $instance->edit_horizon($id);
        $option = $instance->re_db_output($return['option']);        
    } 
    else if(isset($_GET['action'])&&$_GET['action']=='delete_horizon'&&isset($_GET['id'])&&$_GET['id']>0)
    {
        $id = $instance->re_db_input($_GET['id']);
        $return = $instance->delete_horizon($id);
        if($return==true){
            header('location:'.CURRENT_PAGE.'?action=view_horizon');exit;
        }
        else{
            header('location:'.CURRENT_PAGE);exit;
        }
    }
    else if($action=='view_horizon'){
        $return_horizon =$instance->select_horizon();
    } 
    else if(isset($_POST['submit_networth'])&& $_POST['submit_networth']=='Save'){
        	 
        $id = isset($_POST['id'])?$instance->re_db_input($_POST['id']):0;
        $option = isset($_POST['option'])?$instance->re_db_input($_POST['option']):''; 
        $return = $instance->insert_update_networth($_POST);       
        if($return===true){
            header("location:".CURRENT_PAGE.'?action=view_networth');exit;
        }
        else{
            $error = !isset($_SESSION['warning'])?$return:'';
        }
    }
    else if($action=='edit_networth' && $id>0){
        $return = $instance->edit_networth($id);
        $option = $instance->re_db_output($return['option']);        
    } 
    else if(isset($_GET['action'])&&$_GET['action']=='delete_networth'&&isset($_GET['id'])&&$_GET['id']>0)
    {
        $id = $instance->re_db_input($_GET['id']);
        $return = $instance->delete_networth($id);
        if($return==true){
            header('location:'.CURRENT_PAGE.'?action=view_networth');exit;
        }
        else{
            header('location:'.CURRENT_PAGE);exit;
        }
    }
    else if($action=='view_networth'){
        $return_networth=$instance->select_networth();
    }
    else if(isset($_POST['submit_risk_tolerance'])&& $_POST['submit_risk_tolerance']=='Save'){
        	 
        $id = isset($_POST['id'])?$instance->re_db_input($_POST['id']):0;
        $option = isset($_POST['option'])?$instance->re_db_input($_POST['option']):''; 
        $return = $instance->insert_update_risk_tolerance($_POST);       
        if($return===true){
            header("location:".CURRENT_PAGE.'?action=view_risk_tolerance');exit;
        }
        else{
            $error = !isset($_SESSION['warning'])?$return:'';
        }
    }
    else if($action=='edit_risk_tolerance' && $id>0){
        $return = $instance->edit_risk_tolerance($id);
        $option = $instance->re_db_output($return['option']);        
    } 
    else if(isset($_GET['action'])&&$_GET['action']=='delete_risk_tolerance'&&isset($_GET['id'])&&$_GET['id']>0)
    {
        $id = $instance->re_db_input($_GET['id']);
        $return = $instance->delete_risk_tolerance($id);
        if($return==true){
            header('location:'.CURRENT_PAGE.'?action=view_risk_tolerance');exit;
        }
        else{
            header('location:'.CURRENT_PAGE);exit;
        }
    }
    else if($action=='view_risk_tolerance'){
        $return_risk_tolerance=$instance->select_risk_tolerance();
    }
    else if(isset($_POST['submit_annual_expenses'])&& $_POST['submit_annual_expenses']=='Save'){
        	 
        $id = isset($_POST['id'])?$instance->re_db_input($_POST['id']):0;
        $option = isset($_POST['option'])?$instance->re_db_input($_POST['option']):''; 
        $return = $instance->insert_update_annual_expenses($_POST);       
        if($return===true){
            header("location:".CURRENT_PAGE.'?action=view_annual_expenses');exit;
        }
        else{
            $error = !isset($_SESSION['warning'])?$return:'';
        }
    }
    else if($action=='edit_annual_expenses' && $id>0){
        $return = $instance->edit_annual_expenses($id);
        $option = $instance->re_db_output($return['option']);        
    } 
    else if(isset($_GET['action'])&&$_GET['action']=='delete_annual_expenses'&&isset($_GET['id'])&&$_GET['id']>0)
    {
        $id = $instance->re_db_input($_GET['id']);
        $return = $instance->delete_annual_expenses($id);
        if($return==true){
            header('location:'.CURRENT_PAGE.'?action=view_annual_expenses');exit;
        }
        else{
            header('location:'.CURRENT_PAGE);exit;
        }
    }
    else if($action=='view_annual_expenses'){
        $return_annual_expenses=$instance->select_annual_expenses();
    }
    else if(isset($_POST['submit_liqudity_needs'])&& $_POST['submit_liqudity_needs']=='Save'){
        	 
        $id = isset($_POST['id'])?$instance->re_db_input($_POST['id']):0;
        $option = isset($_POST['option'])?$instance->re_db_input($_POST['option']):''; 
        $return = $instance->insert_update_liqudity_needs($_POST);       
        if($return===true){
            header("location:".CURRENT_PAGE.'?action=view_liqudity_needs');exit;
        }
        else{
            $error = !isset($_SESSION['warning'])?$return:'';
        }
    }
    else if($action=='edit_liqudity_needs' && $id>0){
        $return = $instance->edit_liqudity_needs($id);
        $option = $instance->re_db_output($return['option']);        
    } 
    else if(isset($_GET['action'])&&$_GET['action']=='delete_liqudity_needs'&&isset($_GET['id'])&&$_GET['id']>0)
    {
        $id = $instance->re_db_input($_GET['id']);
        $return = $instance->delete_liqudity_needs($id);
        if($return==true){
            header('location:'.CURRENT_PAGE.'?action=view_liqudity_needs');exit;
        }
        else{
            header('location:'.CURRENT_PAGE);exit;
        }
    }
    else if($action=='view_liqudity_needs'){
        $return_liqudity_needs=$instance->select_liqudity_needs();
    }
    else if(isset($_POST['submit_liquid_net_worth'])&& $_POST['submit_liquid_net_worth']=='Save'){
        	 
        $id = isset($_POST['id'])?$instance->re_db_input($_POST['id']):0;
        $option = isset($_POST['option'])?$instance->re_db_input($_POST['option']):''; 
        $return = $instance->insert_update_liquid_net_worth($_POST);       
        if($return===true){
            header("location:".CURRENT_PAGE.'?action=view_liquid_net_worth');exit;
        }
        else{
            $error = !isset($_SESSION['warning'])?$return:'';
        }
    }
    else if($action=='edit_liquid_net_worth' && $id>0){
        $return = $instance->edit_liquid_net_worth($id);
        $option = $instance->re_db_output($return['option']);        
    } 
    else if(isset($_GET['action'])&&$_GET['action']=='delete_liquid_net_worth'&&isset($_GET['id'])&&$_GET['id']>0)
    {
        $id = $instance->re_db_input($_GET['id']);
        $return = $instance->delete_liquid_net_worth($id);
        if($return==true){
            header('location:'.CURRENT_PAGE.'?action=view_liquid_net_worth');exit;
        }
        else{
            header('location:'.CURRENT_PAGE);exit;
        }
    }
    else if($action=='view_liquid_net_worth'){
        $return_liquid_net_worth=$instance->select_liquid_net_worth();
    } 
    else if(isset($_POST['submit_special_expenses'])&& $_POST['submit_special_expenses']=='Save'){
        	 
        $id = isset($_POST['id'])?$instance->re_db_input($_POST['id']):0;
        $option = isset($_POST['option'])?$instance->re_db_input($_POST['option']):''; 
        $return = $instance->insert_update_special_expenses($_POST);       
        if($return===true){
            header("location:".CURRENT_PAGE.'?action=view_special_expenses');exit;
        }
        else{
            $error = !isset($_SESSION['warning'])?$return:'';
        }
    }
    else if($action=='edit_special_expenses' && $id>0){
        $return = $instance->edit_special_expenses($id);
        $option = $instance->re_db_output($return['option']);        
    } 
    else if(isset($_GET['action'])&&$_GET['action']=='delete_special_expenses'&&isset($_GET['id'])&&$_GET['id']>0)
    {
        $id = $instance->re_db_input($_GET['id']);
        $return = $instance->delete_special_expenses($id);
        if($return==true){
            header('location:'.CURRENT_PAGE.'?action=view_special_expenses');exit;
        }
        else{
            header('location:'.CURRENT_PAGE);exit;
        }
    }
    else if($action=='view_special_expenses'){
        $return_special_expenses=$instance->select_special_expenses();
    } 
    else if(isset($_POST['submit_portfolio'])&& $_POST['submit_portfolio']=='Save'){
        	 
        $id = isset($_POST['id'])?$instance->re_db_input($_POST['id']):0;
        $option = isset($_POST['option'])?$instance->re_db_input($_POST['option']):''; 
        $return = $instance->insert_update_portfolio($_POST);       
        if($return===true){
            header("location:".CURRENT_PAGE.'?action=view_portfolio');exit;
        }
        else{
            $error = !isset($_SESSION['warning'])?$return:'';
        }
    }
    else if($action=='edit_portfolio' && $id>0){
        $return = $instance->edit_portfolio($id);
        $option = $instance->re_db_output($return['option']);        
    } 
    else if(isset($_GET['action'])&&$_GET['action']=='delete_portfolio'&&isset($_GET['id'])&&$_GET['id']>0)
    {
        $id = $instance->re_db_input($_GET['id']);
        $return = $instance->delete_portfolio($id);
        if($return==true){
            header('location:'.CURRENT_PAGE.'?action=view_portfolio');exit;
        }
        else{
            header('location:'.CURRENT_PAGE);exit;
        }
    }
    else if($action=='view_portfolio'){
        $return_portfolio=$instance->select_portfolio();
    }
    else if(isset($_POST['submit_time_for_exp'])&& $_POST['submit_time_for_exp']=='Save'){
        	 
        $id = isset($_POST['id'])?$instance->re_db_input($_POST['id']):0;
        $option = isset($_POST['option'])?$instance->re_db_input($_POST['option']):''; 
        $return = $instance->insert_update_time_for_exp($_POST);       
        if($return===true){
            header("location:".CURRENT_PAGE.'?action=view_time_for_exp');exit;
        }
        else{
            $error = !isset($_SESSION['warning'])?$return:'';
        }
    }
    else if($action=='edit_time_for_exp' && $id>0){
        $return = $instance->edit_time_for_exp($id);
        $option = $instance->re_db_output($return['option']);        
    } 
    else if(isset($_GET['action'])&&$_GET['action']=='delete_time_for_exp'&&isset($_GET['id'])&&$_GET['id']>0)
    {
        $id = $instance->re_db_input($_GET['id']);
        $return = $instance->delete_time_for_exp($id);
        if($return==true){
            header('location:'.CURRENT_PAGE.'?action=view_time_for_exp');exit;
        }
        else{
            header('location:'.CURRENT_PAGE);exit;
        }
    }
    else if($action=='view_time_for_exp'){
        $return_time_for_exp=$instance->select_time_for_exp();
    }
    else if(isset($_POST['submit_account_use'])&& $_POST['submit_account_use']=='Save'){
        	 
        $id = isset($_POST['id'])?$instance->re_db_input($_POST['id']):0;
        $option = isset($_POST['option'])?$instance->re_db_input($_POST['option']):''; 
        $return = $instance->insert_update_account_use($_POST);       
        if($return===true){
            header("location:".CURRENT_PAGE.'?action=view_account_use');exit;
        }
        else{
            $error = !isset($_SESSION['warning'])?$return:'';
        }
    }
    else if($action=='edit_account_use' && $id>0){
        $return = $instance->edit_account_use($id);
        $option = $instance->re_db_output($return['option']);        
    } 
    else if(isset($_GET['action'])&&$_GET['action']=='delete_account_use'&&isset($_GET['id'])&&$_GET['id']>0)
    {
        $id = $instance->re_db_input($_GET['id']);
        $return = $instance->delete_account_use($id);
        if($return==true){
            header('location:'.CURRENT_PAGE.'?action=view_account_use');exit;
        }
        else{
            header('location:'.CURRENT_PAGE);exit;
        }
    }
    else if($action=='view_account_use'){
        $return_account_use=$instance->select_account_use();
    }
    $content = "client_suitability";
    include(DIR_WS_TEMPLATES."main_page.tpl.php");
?>