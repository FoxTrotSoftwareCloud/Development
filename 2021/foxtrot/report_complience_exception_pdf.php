<?php 

require_once("include/config.php");
require_once(DIR_FS . "islogin.php");
$instance_import = new import();

$instance = new broker_master();
$get_brokers = $instance->select_broker();
// $instance_sponsor = new manage_sponsor();
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


if (isset($_GET['filter']) && $_GET['filter'] != '') {
    $filter_array = json_decode($_GET['filter'], true);
    $client = isset($filter_array['client'])? $filter_array['client']:0;
    $broker_id = isset($filter_array['broker']) ? $filter_array['broker'] : 0;
    $beginning_date = isset($filter_array['beginning_date']) && !empty($filter_array['beginning_date']) ? date('Y-m-d', strtotime($instance->re_db_input($filter_array['beginning_date']))) : '';
    $ending_date = isset($filter_array['ending_date']) && !empty($filter_array['ending_date']) ? date('Y-m-d', strtotime($instance->re_db_input($filter_array['ending_date']))) : '';
    
}

$total_records = 0;
$total_records_sub = 0;

function get_broker_name_only($brokerA)
{
    return $brokerA['first_name'] . ' ' . $brokerA['last_name'];
}

$queried_brokers = isset($broker_id) && $broker_id != 0 ? implode(",", array_map("get_broker_name_only", array_filter($get_brokers, function ($brokerA) use ($broker_id) {
    return $brokerA['id'] == $broker_id ? true : false;
}))) : 'All Brokers';

$trade_dates= 'Trade Dates: '.date('m/d/Y',strtotime($beginning_date)) ." thru ". date('m/d/Y',strtotime($ending_date));


$client_name='All Clients';
if(isset($client) && $client != 0){
    $queried_client=$instance_client->select_client_master($client);
    $client_name=$queried_client['last_name'].", ".$queried_client['first_name'];
}

   
$list_data= $instance_import->complience_exception_report($beginning_date,$ending_date,$broker_id,$client);
    $is_recrod_found=false;


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

    $subheading = 'COMPLIENCE EXCEPTION REPORT';
    $subheading2 = 'Broker: '.$queried_brokers.',Client: '.$client_name;

     $html='<table border="0" width="100%">
                        <tr>';
                         $html .='<td width="20%" align="left">'.date("m/d/Y").'</td>';
                        
                        $html .='<td width="60%" style="font-size:12px;font-weight:bold;text-align:center;">'.$img.'<br/><strong><h9>'.$subheading.'<br/>'.$subheading2.'<br/>Trade Dates: '.$trade_dates.' <br></h9></strong></td>';
                                         
                            $html.='<td width="20%" align="right">Page 1</td>';
                        
                        $html.='</tr>
                </table> </br>';

                // echo $html;

    $pdf->writeHTML($html, false, 0, false, 0);
    $pdf->Ln(2);
    
    $pdf->SetFont('times','B',12);
    $pdf->SetFont('times','',10);
    $pdf->SetMargins(10, 10, 20, true);

    $html='<table border="0" cellpadding="1" width="100%" border-spacing:0px;>
    <tr style="background-color: #f1f1f1;color:#393939;margin:3px">
        <th style="font-size: 12px; width: 12%;">TRADE DATE <br>SCAN DATE</th>
        <th style="padding: 8px 0px;font-size: 12px; width: 12%;">TRADE #</th>
        <th style="padding: 8px 0px;font-size: 12px; width: 17%;">CLIENT</th>
        <th style="font-size: 12px; width: 15%;">CLIENT ACCOUNT NUMBER</th>
        <th style="padding: 8px 0px;font-size: 12px; width: 17%;">BROKER</th> 
        <th style="padding: 8px 0px;font-size: 12px; width: 27%;">MESSAGE </th>
    </tr>
    <tr>
        <th colspan="6"></th>
    </tr>';

    //  echo $html;die;


    if($list_data != array())
    {
        $total_amount=0.00;
        $total_comm_rec= 0.00;
        foreach($list_data as $exception)
        {
            $is_recrod_found=true;
            
            $exception['trade_date']=(isset($exception['trade_date']) && $exception['trade_date'] != '0000-00-00')? date('m/d/Y',strtotime($exception['trade_date'])) :'--' ;

            $exception['scan_date']=(isset($exception['scan_date']) && $exception['scan_date'] != '0000-00-00')? date('m/d/Y',strtotime($exception['scan_date'])) :'--' ;

            $exception['client_account']=isset($exception['client_account'])? $exception['client_account'] :'' ;
            $exception['transaction_id']=isset($exception['transaction_id'])? $exception['transaction_id'] :'' ;
            $exception['message']=isset($exception['message'])? $exception['message'] :'' ;
           
            $html.='<tr style="color:#393939;height:20px;">
                        <td>'. $exception['trade_date'] .'</td>
                        <td colspan="4">'.$exception['error_message'].'</td>
                        <td></td>
                    </tr>
                    <tr style="color:#393939;height:30px;vertical-align: text-top;">
                        <td>'. $exception['scan_date'].'</td>
                        <td>'.$exception['transaction_id'].'</td>
                        <td>'.$exception['client'].'</td>
                        <td>'.$exception['client_account'].'</td>
                        <td>'.$exception['rep_name'].'</td>
                        <td>'.$exception['message'].'</td>
                    </tr>';
                   
        }   
    }
        if($is_recrod_found==false)
        {
            $html.='<tr>
                        <td style="font-size:8px;font-weight:cold;text-align:center;" colspan="6">No record found.</td>
                    </tr>';
        }           
    $html.='</table>';

    /*echo $html;
    die();*/
    $pdf->writeHTML($html, false, 0, false, 0);
    $pdf->Ln(5);
   
    $pdf->lastPage();
    if(isset($_GET['open']) && $_GET['open'] == 'output_print')
    {
        $pdf->IncludeJS("print();");
    }
    $pdf->Output('complience_exceptions_report.pdf', 'I');
    
    exit;
?>