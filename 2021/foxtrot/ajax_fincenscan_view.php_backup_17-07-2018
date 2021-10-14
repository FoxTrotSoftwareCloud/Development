<?php
require_once("include/config.php");
require_once(DIR_FS."islogin.php");
$instance = new ofac_fincen();
$get_fincen_data = array();
$get_fincen_main_data = array();

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
    
?>
<table>
<tr>
   <th>File Date :</th>
   <td><?php echo $file_date; ?></td>
</tr>
</table>
<table border="0" cellpadding="5" width="100%">
<tr>
    <td style="width:20%">
        <table border="0" width="100%">
            <tr>
                <td>
                    FINCEN NAME
                </td>
            </tr>
            <tr>
                <td>
                    FINCEN ADDRESS
                </td>
            </tr>
            <tr>
                <td>
                    FINCEN COUNTRY, PHONE
                </td>
            </tr>
        </table>
    </td>
    <td style="width:20%">
        <table border="0" width="100%">
            <tr>
                <td style="text-align:center">
                    TRACKING#
                </td>
            </tr>
            <tr>
                <td style="text-align:center">
                    KEY NUMBER
                </td>
            </tr>
            <tr>
                <td style="text-align:center">
                    NUMBER TYPE
                </td>
            </tr>
            <tr>
                <td style="text-align:center">
                    DOB
                </td>
            </tr>
        </table>
    </td>
    <td style="width:20%">
        <table border="0" width="100%">
            <tr>
                <td>
                    CLIENT NAME
                </td>
            </tr>
            <tr>
                <td>
                    CLIENT ADDRESS
                </td>
            </tr>
            <tr>
                <td>
                    CLIENT COUNTRY, PHONE
                </td>
            </tr>
        </table>
    </td>
    <td style="width:20%">
        <table border="0" width="100%">
            <tr>
                <td style="text-align:center">
                    CLIENT#
                </td>
            </tr>
            <tr>
                <td style="text-align:center">
                    SOC.SEC#
                </td>
            </tr>
            <tr>
                <td style="text-align:center">
                    CIP#
                </td>
            </tr>
            <tr>
                <td style="text-align:center">
                    OPEN DATE
                </td>
            </tr>
            <tr>
                <td style="text-align:center">
                    DOB
                </td>
            </tr>
        </table>
    </td>
    <td style="width:20%">
        <table border="0" width="100%">
            <tr>
                <td>
                    REP NO
                </td>
            </tr>
            <tr>
                <td>
                    REP NAME
                </td>
            </tr>
        </table>
    </td>
</tr>
</table>
<table border="0" cellpadding="5" width="100%">
<?php 
if($get_fincen_data != array())
{
    foreach($get_fincen_data as $key=>$val){
         
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
                <table border="0" cellpadding="5" width="100%">
                    <tr>
                        <td style="font-size:14px;font-weight:bold;text-align:left;">
                            MATCH CRITERIA:  NAME
                        </td>
                    </tr>
                </table>
                <table border="1" width="100%">
                    <tr>
                        <td>
                            <table border="0" cellpadding="5" width="100%">
                                <tr>
                                    <td style="font-weight:normal;text-align:left;width:60%">
                                        <?php echo $fincen_firstname.''.$fincen_miname.''.$fincen_lastname;?>
                                    </td>
                                    <td style="font-weight:normal;text-align:center;width:40%">
                                        <?php echo $val['fincen_tracking_no']; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-weight:normal;text-align:left;width:60%">
                                        <?php echo $val['fincen_address']; ?>
                                    </td>
                                    <td style="font-weight:normal;text-align:center;width:40%">
                                        <?php echo $val['fincen_number']; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-weight:normal;text-align:left;width:60%">
                                        
                                    </td>
                                    <td style="font-weight:normal;text-align:center;width:40%">
                                        <?php echo $val['fincen_number_type']; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-weight:normal;text-align:left;width:60%">
                                        <?php echo $val['fincen_country'].' '.$val['fincen_phone']; ?>
                                    </td>
                                    <td style="font-weight:normal;text-align:center;width:40%">
                                        <?php echo $fincen_dob; ?>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="width:2%" ></td>
            <td style="width:58%" >
                <table border="0" cellpadding="5" width="100%">
                    <tr>
                        <td style="font-size:14px;font-weight:bold;text-align:left;">
                            
                        </td>
                    </tr>
                </table>
                <br/>
                <table border="1" width="100%">
                    <tr>
                        <td>
                            <table border="0" cellpadding="5" width="100%">
                                <tr>
                                    <td style="font-weight:normal;text-align:left;width:35%">
                                        <?php echo $cl_first_name.''.$cl_middle_name.''.$cl_last_name;?>
                                    </td>
                                    <td style="font-weight:normal;text-align:center;width:35%">
                                        <?php echo $val['id'];?>
                                    </td>
                                    <td style="font-weight:normal;text-align:center;width:30%">
                                        -
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-weight:normal;text-align:left;width:35%">
                                       
                                    </td>
                                    <td style="font-weight:normal;text-align:center;width:35%">
                                        <?php echo $val['client_ssn'];?>
                                    </td>
                                    <td style="font-weight:normal;text-align:center;width:30%">
                                        -
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-weight:normal;text-align:left;width:35%">
                                       <?php echo $val['address1'];?>
                                    </td>
                                    <td style="font-weight:normal;text-align:center;width:35%">
                                        <?php echo date('m/d/Y',strtotime($val['open_date']));?>
                                    </td>
                                    <td style="font-weight:normal;text-align:center;width:30%">
                                        
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-weight:normal;text-align:left;width:35%">
                                       <?php echo $val['telephone'];?>
                                    </td>
                                    <td style="font-weight:normal;text-align:center;width:35%">
                                        <?php echo date('m/d/Y',strtotime($val['birth_date']));?>
                                    </td>
                                    <td style="font-weight:normal;text-align:center;width:30%">
                                        
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    <?php }
}else{?>
    
    <tr>
        <td style="font-size:13px;font-weight:cold;text-align:center;" colspan="8">No Records Found.</td>
    </tr>
<?php } ?> 
</table>

