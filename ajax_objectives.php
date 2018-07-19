<?php 
require_once("include/config.php");
require_once(DIR_FS."islogin.php");
?>
<?php
$instance = new client_maintenance();
if(isset($_GET['all_objectives']) && $_GET['all_objectives'] != '')
{
    //print_r($_SESSION['all_objectives']);exit;
    $current_add_objectives = $_GET['all_objectives'];
    $current_store_data = explode(',',$current_add_objectives);
    $_POST['allobjectives']=$current_store_data;
    $return = $instance->insert_update_allobjectives($_POST);
    //echo $return;
}
if(isset($_GET['objectives']) && $_GET['objectives'] != '')
{
    $_POST['objectives']=$_GET['objectives'];//print_r($_POST['objectives']);exit;
    $return = $instance->insert_update_objectives($_POST);
    //echo $return;
}
if(isset($_GET['delete_objective']) && $_GET['delete_objective'] != '')
{
    $delete_id=$_GET['delete_objective'];//print_r($_POST['objectives']);exit;
    $return = $instance->delete_objectives($delete_id);
    //echo $return;
}
if(isset($_GET['delete_allobjective']) && $_GET['delete_allobjective'] != '')
{
    $delete_id=$_GET['delete_allobjective'];//print_r($_POST['objectives']);exit;
    $return = $instance->delete_allobjectives($delete_id);
    //echo $return;
}
?>