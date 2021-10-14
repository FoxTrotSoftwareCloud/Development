<?php
require_once("include/config.php");
require_once(DIR_FS."islogin.php");
$instance = new ofac_fincen();
$get_ofac_data = array();
$get_ofac_main_data = array();

$ofac_main_id = isset($_GET['id'])?$instance->re_db_input($_GET['id']):0;
if($ofac_main_id >0)
{
    $get_ofac_main_data = $instance->select_data_master_report($ofac_main_id);
    $get_ofac_data = $instance->select_data_report($ofac_main_id);
}
else
{
    $get_ofac_main_data = $instance->select_data_master_report();
    $ofac_main_id = isset($get_ofac_main_data['id'])?$instance->re_db_input($get_ofac_main_data['id']):0;
    $get_ofac_data = $instance->select_data_report($ofac_main_id);
}
//print_r($get_ofac_data);exit;
$file_date = isset($get_ofac_main_data['created_time'])?$instance->re_db_input(date('m/d/Y',strtotime($get_ofac_main_data['created_time']))):'00/00/0000';
$total_matches = isset($get_ofac_main_data['total_match'])?$instance->re_db_input($get_ofac_main_data['total_match']):0;
$total_scan = isset($get_ofac_main_data['total_scan'])?$instance->re_db_input($get_ofac_main_data['total_scan']):0;
$count=0;
    
?>
<table>
<tr>
   <th>File Date :</th>
   <td><?php echo $file_date; ?></td>
</tr>
</table>
<table id="data-table" class="table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
        <tr style="background-color: #f1f1f1;">
            <td><h4>SDN NAME</h4></td>
            <td><h4>ID NO.</h4></td>
            <td><h4>PROGRAM</h4></td>
            <td><h4>CLIENT NO.</h4></td>
            <td><h4>FOXTROT CLIENT NAME </h4></td>
            <td><h4>REP NO.</h4></td>
            <td><h4>REP NAME</h4></td>
        </tr>
    </thead>
    <tbody>
    <?php 
    if($get_ofac_data != array())
    {
        foreach($get_ofac_data as $key=>$val)
        { ?>
        <tr>
               <td style="font-size:13px;font-weight:normal;text-align:left;"><?php echo $val['sdn_name']; ?></td>
               <td style="font-size:13px;font-weight:normal;text-align:left;"><?php echo $val['id_no']; ?></td>
               <td style="font-size:13px;font-weight:normal;text-align:left;"><?php echo $val['program']; ?></td>
               <td style="font-size:13px;font-weight:normal;text-align:left;"><?php echo $val['client_id']; ?></td>
               <td style="font-size:13px;font-weight:normal;text-align:left;"><?php echo $val['client_name']; ?></td>
               <td style="font-size:13px;font-weight:normal;text-align:left;"><?php echo '-'; ?></td>
               <td style="font-size:13px;font-weight:normal;text-align:left;"><?php echo '-'; ?></td>
        </tr>
        <?php 
        }
    }
    else
    {?>
    
    <tr>
        <td style="font-size:13px;font-weight:cold;text-align:center;" colspan="8">No Records Found.</td>
    </tr>
    <?php } ?>     
   </tbody>
</table>
<table>
<tr>
   <td>Total Scanned:</td>
   <td><?php echo $total_scan; ?></td>
</tr>
<tr>
   <td>Total Matches:</td>
   <td><?php echo $total_matches; ?></td>
</tr>
<tr>
   <td>*Foxtrot Client Name - </td>
   <td>Match found in joint name</td>
</tr>
</table>
