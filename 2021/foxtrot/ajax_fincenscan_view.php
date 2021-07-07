<?php
require_once("include/config.php");
require_once(DIR_FS."islogin.php");
$instance = new ofac_fincen();
$get_fincen_data = array();
$get_fincen_main_data = array();
$total_records=0;

$fincen_main_id = isset($_GET['id'])?$instance->re_db_input($_GET['id']):0;
if($fincen_main_id >0)
{
    $get_fincen_main_data = $instance->select_fincen_data_master_report($fincen_main_id);
    $get_fincen_data = $instance->select_fincen_scan_report($fincen_main_id);
}
else
{
    $get_fincen_main_data = $instance->select_fincen_data_master_report();
    $fincen_main_id = isset($get_fincen_main_data['id'])?$instance->re_db_input($get_fincen_main_data['id']):0;
    $get_fincen_data = $instance->select_fincen_scan_report($fincen_main_id);
}
//print_r($get_ofac_data);exit;
$file_date = isset($get_fincen_main_data['created_time'])?$instance->re_db_input(date('m/d/Y',strtotime($get_fincen_main_data['created_time']))):'00/00/0000';
$total_matches = isset($get_fincen_main_data['total_match'])?$instance->re_db_input($get_fincen_main_data['total_match']):0;
$total_scan = isset($get_fincen_main_data['total_scan'])?$instance->re_db_input($get_fincen_main_data['total_scan']):0;
$cl_first_name = '';
$cl_middle_name = '';
$cl_last_name = '';

//DEFAULT PDF DATA:
$get_logo = $instance->get_system_logo();
$system_logo = isset($get_logo['logo'])?$instance->re_db_input($get_logo['logo']):'';
$get_company_name = $instance->get_company_name();
$system_company_name = isset($get_company_name['company_name'])?$instance->re_db_input($get_company_name['company_name']):'';
$img = '<img src="'.SITE_URL."upload/logo/".$system_logo.'" height="25px" />';
    
?>
<table border="0" width="100%">
        <tr>
            <?php 
            if(isset($system_logo) && $system_logo != '')
            {?>
                <td width="20%" align="left"><?php echo $img;?></td>
            <?php } ?>
            <td width="60%" style="font-size:12px;font-weight:bold;text-align:center;">Successful Brokerage, Inc</td>
            <?php
            if(isset($system_company_name) && $system_company_name != '')
            {?>
                <td width="20%" style="font-size:10px;font-weight:bold;text-align:right;"><?php echo $system_company_name;?></td>
            <?php
            }?>
        </tr>
        <tr>
            <td width="100%" colspan="3" style="font-size:14px;font-weight:bold;text-align:center;">FinCEN SEARCH </td>
        </tr>
        <tr>
            <td width="100%" colspan="3" style="font-size:12px;font-weight:bold;text-align:center;">File Date - <?php echo $file_date;?></td>
        </tr>
</table>
<br />
<table border="0" cellpadding="1" width="100%">
<tr style="background-color: #f1f1f1;">
   <td style="width:20%">
        <table border="0" width="100%">
            <tr style="background-color: #f1f1f1;">
                <td style="text-align:center;">
                    <h5>FINCEN NAME</h5>
                </td>
            </tr>
            <tr style="background-color: #f1f1f1;">
                <td style="text-align:center;">
                    <h5>FINCEN ADDRESS</h5>
                </td>
            </tr>
            <tr style="background-color: #f1f1f1;">
                <td style="text-align:center;">
                    <h5>FINCEN COUNTRY, PHONE</h5>
                </td>
            </tr>
            <tr style="background-color: #f1f1f1;">
                <td style="text-align:center;">
                    
                </td>
            </tr>
            <tr style="background-color: #f1f1f1;">
                <td style="text-align:center;">
                    
                </td>
            </tr>
        </table>
    </td>
    <td style="width:20%">
        <table border="0" width="100%">
            <tr style="background-color: #f1f1f1;">
                <td style="text-align:center">
                    <h5>TRACKING#</h5>
                </td>
            </tr>
            <tr style="background-color: #f1f1f1;">
                <td style="text-align:center">
                    <h5>KEY NUMBER</h5>
                </td>
            </tr>
            <tr style="background-color: #f1f1f1;">
                <td style="text-align:center">
                    <h5>NUMBER TYPE</h5>
                </td>
            </tr>
            <tr style="background-color: #f1f1f1;">
                <td style="text-align:center">
                    <h5>DOB</h5>
                </td>
            </tr>
            <tr style="background-color: #f1f1f1;">
                <td style="text-align:center;">
                    
                </td>
            </tr>
        </table>
    </td>
    <td style="width:20%">
        <table border="0" width="100%">
            <tr style="background-color: #f1f1f1;">
                <td style="text-align:center;">
                    <h5>CLIENT NAME</h5>
                </td>
            </tr>
            <tr style="background-color: #f1f1f1;">
                <td style="text-align:center;">
                    <h5>CLIENT ADDRESS</h5>
                </td>
            </tr>
            <tr style="background-color: #f1f1f1;">
                <td style="text-align:center;">
                    <h5>CLIENT COUNTRY, PHONE</h5>
                </td>
            </tr>
            <tr style="background-color: #f1f1f1;">
                <td style="text-align:center;">
                    
                </td>
            </tr>
            <tr style="background-color: #f1f1f1;">
                <td style="text-align:center;">
                    
                </td>
            </tr>
        </table>
    </td>
    <td style="width:20%">
        <table border="0" width="100%">
            <tr style="background-color: #f1f1f1;">
                <td style="text-align:center">
                    <h5>CLIENT#</h5>
                </td>
            </tr>
            <tr style="background-color: #f1f1f1;">
                <td style="text-align:center">
                    <h5>SOC.SEC#</h5>
                </td>
            </tr>
            <tr style="background-color: #f1f1f1;">
                <td style="text-align:center">
                    <h5>CIP#</h5>
                </td>
            </tr>
            <tr style="background-color: #f1f1f1;">
                <td style="text-align:center">
                    <h5>OPEN DATE</h5>
                </td>
            </tr>
            <tr style="background-color: #f1f1f1;">
                <td style="text-align:center">
                    <h5>DOB</h5>
                </td>
            </tr>
        </table>
    </td>
    <td style="width:20%">
        <table border="0" width="100%">
            <tr style="background-color: #f1f1f1;">
                <td style="text-align:center;">
                    <h5>REP NO</h5>
                </td>
            </tr>
            <tr style="background-color: #f1f1f1;">
                <td style="text-align:center;">
                    <h5>REP NAME</h5>
                </td>
            </tr>
            <tr style="background-color: #f1f1f1;">
                <td style="text-align:center;">
                    
                </td>
            </tr>
            <tr style="background-color: #f1f1f1;">
                <td style="text-align:center;">
                    
                </td>
            </tr>
            <tr style="background-color: #f1f1f1;">
                <td style="text-align:center;">
                    
                </td>
            </tr>
        </table>
    </td>
</tr>
</table>
<table border="0" cellpadding="1" width="100%">
<?php 
if($get_fincen_data != array())
{
    foreach($get_fincen_data as $key=>$val){
       
        $total_records=$total_records+1; 
        if($val['first_name'] != '')
        { 
            $cl_first_name = $val['first_name'];
        }
        else{ 
            $cl_first_name = '';
        }
        if($val['mi'] != '')
        { 
            $cl_middle_name = '-'.$val['mi'];
        }
        else{ 
            $cl_middle_name = '';
        }
        if($val['last_name'] != '')
        { 
            $cl_last_name = ','.$val['last_name'];
        }
        else{ 
            $cl_last_name = '';
        }
        
        if($val['fincen_firstname'] != '')
        { 
            $fincen_firstname = $val['fincen_firstname'];
        }
        else{ 
            $fincen_firstname = '';
        }
        if($val['fincen_miname'] != '')
        { 
            $fincen_miname = '-'.$val['fincen_miname'];
        }
        else{ 
            $fincen_miname = '';
        }
        if($val['fincen_lastname'] != '')
        { 
            $fincen_lastname = ','.$val['fincen_lastname'];
        }
        else{ 
            $fincen_lastname = '';
        }
        if($val['fincen_dob'] != '0000-00-00')
        {
            $fincen_dob = date('m/d/Y',strtotime($val['fincen_dob']));
        }
        else
        {
            $fincen_dob = '';
        }?>
            
        <tr>
            <td style="width:40%">
                <table border="0" cellpadding="1" width="100%">
                    <tr>
                        <td style="font-size:12px;font-weight:bold;text-align:left;">
                            MATCH CRITERIA:  NAME
                        </td>
                    </tr>
                </table>
                <table border="1" width="100%">
                    <tr>
                        <td>
                            <table border="0" cellpadding="1" width="100%">
                                <tr>
                                    <td style="font-weight:normal;font-size:10px;text-align:left;width:60%">
                                        <?php echo $fincen_firstname.''.$fincen_miname.''.$fincen_lastname;?>
                                    </td>
                                    <td style="font-weight:normal;font-size:10px;text-align:center;width:40%">
                                        <?php echo $val['fincen_tracking_no']; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-weight:normal;font-size:10px;text-align:left;width:60%">
                                        <?php if($val['fincen_address'] != ''){ echo $val['fincen_address']; }else{ echo '-';} ?>
                                    </td>
                                    <td style="font-weight:normal;font-size:10px;text-align:center;width:40%">
                                        <?php echo $val['fincen_number']; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-weight:normal;font-size:10px;text-align:left;width:60%">
                                        -
                                    </td>
                                    <td style="font-weight:normal;font-size:10px;text-align:center;width:40%">
                                        <?php echo $val['fincen_number_type']; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-weight:normal;font-size:10px;text-align:left;width:60%">
                                        <?php echo $val['fincen_country'].' '.$val['fincen_phone']; ?>
                                    </td>
                                    <td style="font-weight:normal;font-size:10px;text-align:center;width:40%">
                                        <?php if($fincen_dob != ''){ echo $fincen_dob; }else{ echo '-';} ?>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="width:2%" ></td>
            <td style="width:58%" >
                <table border="0" cellpadding="1" width="100%">
                    <tr>
                        <td style="font-size:12px;font-weight:bold;text-align:left;">
                            &nbsp;
                        </td>
                    </tr>
                </table>
                <table border="1" width="100%">
                    <tr>
                        <td>
                            <table border="0" cellpadding="1" width="100%">
                                <tr>
                                    <td style="font-weight:normal;font-size:10px;text-align:left;width:35%">
                                        <?php echo $cl_first_name.''.$cl_middle_name.''.$cl_last_name;?>
                                    </td>
                                    <td style="font-weight:normal;font-size:10px;text-align:center;width:35%">
                                        <?php echo $val['id'];?>
                                    </td>
                                    <td style="font-weight:normal;font-size:10px;text-align:center;width:30%">
                                        -
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-weight:normal;font-size:10px;text-align:left;width:35%">
                                       
                                    </td>
                                    <td style="font-weight:normal;font-size:10px;text-align:center;width:35%">
                                        <?php echo $val['client_ssn'];?>
                                    </td>
                                    <td style="font-weight:normal;font-size:10px;text-align:center;width:30%">
                                        -
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-weight:normal;font-size:10px;text-align:left;width:35%">
                                       <?php echo $val['address1'];?>
                                    </td>
                                    <td style="font-weight:normal;font-size:10px;text-align:center;width:35%">
                                        <?php echo date('m/d/Y',strtotime($val['open_date']));?>
                                    </td>
                                    <td style="font-weight:normal;font-size:10px;text-align:center;width:30%">
                                        
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-weight:normal;font-size:10px;text-align:left;width:35%">
                                       <?php echo $val['telephone'];?>
                                    </td>
                                    <td style="font-weight:normal;font-size:10px;text-align:center;width:35%">
                                        <?php echo date('m/d/Y',strtotime($val['birth_date']));?>
                                    </td>
                                    <td style="font-weight:normal;font-size:10px;text-align:center;width:30%">
                                        
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    <?php }?>
    <tr style="background-color: #f1f1f1;">
        <td style="font-size:10px;font-weight:bold;text-align:right;" colspan="8">Total Records: <?php echo $total_records;?></td>
    </tr>
<?php }else{ ?>
    
    <tr>
        <td style="font-size:10px;font-weight:cold;text-align:center;" colspan="8">No Records Found.</td>
    </tr>
<?php } ?> 
</table>

<style>
.h4, .h5, .h6, h4, h5, h6 {
    margin-top: 0px;
    margin-bottom: 0px;
}
</style>