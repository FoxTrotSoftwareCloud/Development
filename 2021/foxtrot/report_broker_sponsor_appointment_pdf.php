<?php 

require_once("include/config.php");
require_once(DIR_FS . "islogin.php");
$instance = new broker_master();
$get_brokers = $instance->select_broker();
$instance_sponsor = new manage_sponsor();

$filter_array = array();
$beginning_date = '';
$ending_date = '';

//DEFAULT PDF DATA:
$get_logo = $instance->get_system_logo();
$system_logo = isset($get_logo['logo']) ? $instance->re_db_input($get_logo['logo']) : '';
$get_company_name = $instance->get_company_name();
$system_company_name = isset($get_company_name['company_name']) ? $instance->re_db_input($get_company_name['company_name']) : '';

//filter batch report
if(isset($_GET['filter']) && $_GET['filter'] != '')
{
   
    $filter_array = json_decode($_GET['filter'], true);
    $sponsor_id = isset($filter_array['sponsor'])?$filter_array['sponsor']:'';
    $broker_id = isset($filter_array['broker']) ? $filter_array['broker'] : 0;
    $group_by = $filter_array['allias_groupby'];

    $total_records = 0;
    $total_records_sub = 0;

    function get_broker_name_only($brokerA)
    {
        return $brokerA['last_name'] . ' ' . $brokerA['first_name'];
    }
    
    $queried_brokers = isset($broker_id) && $broker_id != 0 ? implode(",", array_map("get_broker_name_only", array_filter($get_brokers, function ($brokerA) use ($broker_id) {
        return $brokerA['id'] == $broker_id ? true : false;
    }))) : 'All Brokers';


    $sponsor_name='All Sponsors';
    if(isset($sponsor_id) && $sponsor_id != 0){
        $queried_sponsor=$instance_sponsor->select_sponsor_by_id($sponsor_id);
        $sponsor_name=$queried_sponsor['name'];
    }
   
    $is_recrod_found = false;

    $list_data= $instance->broker_sponsor_appointment_report($broker_id,$sponsor_id,$group_by);

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


    $subheading = 'BROKER SPONSOR APPOINTMENTS LISTING REPORT';
    $subheading2= 'Broker: '. $queried_brokers.', Sponsor: '. $sponsor_name;
    $html='<table border="0" width="100%">
                        <tr>';
                         $html .='<td width="20%" align="left">'.date("m/d/Y").'</td>';
                        
                        $html .='<td width="60%" style="font-size:12px;font-weight:bold;text-align:center;">'.$img.'<br/><strong><h9>'.$subheading.'<br/>'.$subheading2 .'<br></h9></strong></td>';
                                         
                            $html.='<td width="20%" align="right">Page 1</td>';
                        
                        $html.='</tr>
                </table> </br>';



    $pdf->writeHTML($html, false, 0, false, 0);
    $pdf->Ln(2);
    
    $pdf->SetFont('times','B',12);
    $pdf->SetFont('times','',10);

    $heading= ($group_by=='broker')?"COMPANY": "BROKER";
    $html='<table border="0" cellpadding="1" width="100%" border-spacing:0px;>
            <tr style="background-color: #f1f1f1;color:#393939;">
            <th style="font-size: 12px; width: 20%;">APPT #</th>
            <th style="padding: 8px 8px;font-size: 12px; width: 10%;">DATE</th>
            <th style="padding: 8px 8px;font-size: 12px; width: 30%;" >'.$heading.'</th>
            <th style="padding: 8px 8px;font-size: 12px; width: 10%;" >STATE</th>
            <th style="padding: 8px 8px;font-size: 12px; width: 10%;" >TERM DATE</th>
            <th style="padding: 8px 8px;font-size: 12px; width: 20%;">REP #</th>
            </tr>';
    $html.='<tr><th colspan="6">
                        </th></tr>';
    
    
    if($list_data != array())
    {
        $last_record=0;
        foreach($list_data as $row)
        {
            if($row['appointments']!=array()){
                $is_recrod_found=true;             
                $html.='<tr>
                            <td colspan="6" style="text-align: left;font-size: 10px;font-weight: bold;">'.$row['full_name'].'</td>
                        </tr>';
            
                foreach($row['appointments'] as $appointment){
                    $appointment['date'] = (isset($appointment['date']) && $appointment['date']!= '0000-00-00')? date('m/d/Y',strtotime($appointment['date'])):''; 
                    $broker_name=$appointment['broker_last_name'].", ".$appointment['broker_first_name'];
                    $broker_sponsor_name= ($group_by=='broker')? $appointment['sponsor_name']: $broker_name;
                    $appointment['termdate'] = (isset($appointment['termdate']) && $appointment['termdate']!= '0000-00-00')? date('m/d/Y',strtotime($appointment['termdate'])):''; 
                    $html.='<tr>
                            <td>'.$appointment['alias_name'].'</td>
                            <td>'.$appointment['date'].'</td>
                            <td>'.$broker_sponsor_name.'</td>
                            <td>'.$appointment['state_name'].'</td>
                            <td>'.$appointment['termdate'].'</td>
                            <td></td>
                
                        </tr>';
                }
                $html.='<br/>'; 
            }                            
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
    $pdf->Output('report_broker_sponsor_appointment.pdf', 'I');
    
    exit;
?>