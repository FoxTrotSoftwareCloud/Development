<?php 

require_once("include/config.php");
require_once(DIR_FS . "islogin.php");
$instance = new broker_master();
$get_brokers = $instance->select_broker();
$instance_sponsor = new manage_sponsor();
$instance_client = new client_maintenance();

$filter_array = array();
$beginning_date = '';
$ending_date = '';

//DEFAULT PDF DATA:
$get_logo = $instance->get_system_logo();
$system_logo = isset($get_logo['logo']) ? $instance->re_db_input($get_logo['logo']) : '';
$get_company_name = $instance->get_company_name();
$system_company_name = isset($get_company_name['company_name']) ? $instance->re_db_input($get_company_name['company_name']) : '';
$img = '<img src="' . SITE_URL . "upload/logo/" . $system_logo . '" height="25px" />';

//filter batch report
if(isset($_GET['filter']) && $_GET['filter'] != '')
{
    $filter_array = json_decode($_GET['filter'], true);
    $sponsor_id = isset($filter_array['sponsor'])?$filter_array['sponsor']:'';
    $broker_id = isset($filter_array['broker']) ? $filter_array['broker'] : 0;
    $cip_client = isset($filter_array['cip_client']) ? $filter_array['cip_client'] : 0;
    $exclude_donot_contact_client = isset($filter_array['dont_contact_client']) ? $filter_array['dont_contact_client'] : 0;
    $beginning_date = isset($filter_array['beginning_date']) && !empty($filter_array['beginning_date']) ? date('Y-m-d H:i:s', strtotime($instance->re_db_input($filter_array['beginning_date']))) : '';
    $ending_date = isset($filter_array['ending_date']) && !empty($filter_array['ending_date']) ? date('Y-m-d', strtotime($instance->re_db_input($filter_array['ending_date']))) : '';

    $total_records = 0;
    $total_records_sub = 0;

    function get_broker_name_only($brokerA)
    {
        return $brokerA['first_name'] . ' ' . $brokerA['last_name'];
    }
    
    $queried_brokers = isset($broker_id) && $broker_id != 0 ? implode(",", array_map("get_broker_name_only", array_filter($get_brokers, function ($brokerA) use ($broker_id) {
        return $brokerA['id'] == $broker_id ? true : false;
    }))) : 'All Brokers';

    $trade_dates= date('m/d/Y',strtotime($beginning_date)) ." thru ". date('m/d/Y',strtotime($ending_date));
   
    $cip_info = "All Clients";
    if($cip_client == 1){
        $cip_info = "Clients With CIP Data";
    }
    $sponsor_name='All Sponsors';
    if(isset($sponsor_id) && $sponsor_id != 0){
        $queried_sponsor=$instance_sponsor->select_sponsor_by_id($sponsor_id);
        $sponsor_name=$queried_sponsor['name'];
    }
   
        $is_recrod_found = false;

        $list_data= $instance_client->client_cip_report($beginning_date,$ending_date,$broker_id,$cip_client,$exclude_donot_contact_client,$sponsor_id);
        //echo "<pre>"; print_r($list_data);
}
?>
<?php

    // create new PDF document
    $pdf = new RRPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    // add a page
    $pdf->AddPage('L');
    // Title
    $img = '<img src="upload/logo/'.$system_logo.'" height="25px" />';
    
    $pdf->SetFont('times','B',12);
    $pdf->SetFont('times','',10);


    $subheading = 'CLIENT CIP REPORT';
    $subheading3= 'Open Dates: '. $trade_dates;
    $subheading2= 'Broker: '. $queried_brokers.', Sponsor: '. $sponsor_name.", ".$cip_info ;
    $dont_contact_client="";
    if($exclude_donot_contact_client == 1){
        $dont_contact_client= "<br> Excluding 'Do Not Contact' Clients";
    }
     $html='<table border="0" width="100%">
                        <tr>';
                         $html .='<td width="20%" align="left">'.date("m/d/Y").'</td>';
                        
                        $html .='<td width="60%" style="font-size:12px;font-weight:bold;text-align:center;">'.$img.'<br/><strong><h9>'.$subheading.'<br/>'.$subheading3.' <br> '.$subheading2.$dont_contact_client .'<br></h9></strong></td>';
                                         
                            $html.='<td width="20%" align="right">Page 1</td>';
                        
                        $html.='</tr>
                </table> </br>';



    $pdf->writeHTML($html, false, 0, false, 0);
    $pdf->Ln(2);
    
  
    
    $pdf->SetFont('times','B',12);
    $pdf->SetFont('times','',10);
    $html='<table border="0" cellpadding="1" width="100%" border-spacing:0px;>
            <tr style="background-color: #f1f1f1;color:#393939;margin:3px;font-size: 10px">
                <th style="padding: 8px 0px; ">NAME</th>
                <th style="padding: 8px 0px;  width: 10%;">ADDRESS</th>
                <th style="padding: 15px 0px;  width: 6%;">CITY</th>
                <th style="padding: 8px 0px;  width: 6%;">STATE</th>
                <th style="padding: 8px 0px;  width: 6%;">ZIP CODE</th>
                <th style="padding: 8px 0px;  width: 8%;">PHONE NUMBER</th>
                <th style="padding: 8px 0px;  width: 8%;">SSN</th>
                <th style="padding: 8px 0px;  width: 7%;">OPEN DATE</th>
                <th style="padding: 8px 0px; ">DOB</th>
                <th style="  width: 5%;">ID</th>
                <th style="  width: 5%;">TYPE</th>
                <th style="padding: 8px 0px;  width: 6%;">NUMBER</th>
                <th style="padding: 8px 0px;  width: 7%;">EXPIRATION DATE</th>
                <th style="padding: 8px 0px;  width: 5%;">STATE</th>
                <th style="padding: 8px 0px;  width: 7%;" >VARIFIED DATE</th>
            </tr>';
    $html.='<tr><th colspan="15">
                        </th></tr>';
    
    
    if($list_data != array())
    {
        $last_record=0;
        foreach($list_data as $row)
        {
            $is_recrod_found=true;
            $row['employ_number']=isset($row['employ_number'])?$row['employ_number']:'';
            $row['expiration'] = (isset($row['expiration']) && $row['expiration']!= '0000-00-00')? date('m/d/Y',strtotime($row['expiration'])):''; 
            $row['employ_state']=isset($row['employ_state'])?$row['employ_state']:'';
            $row['date_verified'] = (isset($row['date_verified']) && $row['date_verified']!= '0000-00-00')? date('m/d/Y',strtotime($row['date_verified'])):''; 
            $row['open_date'] = (isset($row['open_date']) && $row['open_date']!= '0000-00-00')? date('m/d/Y',strtotime($row['open_date'])):''; 
            $row['birth_date'] = (isset($row['birth_date']) && $row['birth_date']!= '0000-00-00')? date('m/d/Y',strtotime($row['birth_date'])):''; 
            $html.='<tr>
                        <td>'.$row['last_name'].', '.$row['first_name'].'</td>
                        <td>'.$row['address1'].', '.$row['address2'].'</td>
                        <td>'.$row['city'].'</td>
                        <td>'.$row['state_name'].'</td>
                        <td>'.$row['zip_code'].'</td>
                        <td>'.$row['telephone'].'</td>
                        <td>'.$row['client_ssn'].'</td>
                        <td>'.$row['open_date'].'</td>
                        <td>'.$row['birth_date'].'</td>
                        <td></td>
                        <td></td>
                        <td>'.$row['employ_number'].'</td>
                        <td>'.$row['expiration'].'</td>
                        <td>'.$row['employ_state'].'</td>
                        <td>'.$row['date_verified'].'</td>
                    </tr>';
           
            $html.='<br/>';                             
        }     
    }
    if($is_recrod_found==false)
    {
        $html.='<tr>
                    <td style="font-size:8px;font-weight:cold;text-align:center;" colspan="15">No record found.</td>
                </tr>';
    }           
    $html.='</table>';
    $pdf->writeHTML($html, false, 0, false, 0);
    $pdf->Ln(5);
   
    $pdf->lastPage();
    if(isset($_GET['open']) && $_GET['open'] == 'output_print')
    {
        $pdf->IncludeJS("print();");
    }
    $pdf->Output('report_client_CIP.pdf', 'I');
    
    exit;
?>