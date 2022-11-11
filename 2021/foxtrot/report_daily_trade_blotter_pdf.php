<?php 
require_once("include/config.php");
require_once(DIR_FS."islogin.php");
$instance = new client_review();
$filter_array = array();
$beginning_date = '';
$ending_date = '';
$instance_trans = new transaction();
$instance_broker = new broker_master();
$get_brokers = $instance_broker->select_broker();
$instance_company = new manage_company();
$instance_branch = new branch_maintenance();


//DEFAULT PDF DATA:
$get_logo = $instance->get_system_logo();
$system_logo = isset($get_logo['logo'])?$instance->re_db_input($get_logo['logo']):'';
$get_company_name = $instance->get_company_name();
$system_company_name = isset($get_company_name['company_name'])?$instance->re_db_input($get_company_name['company_name']):'';

//filter batch report
if(isset($_GET['filter']) && $_GET['filter'] != '')
{
    $filter_array = json_decode($_GET['filter'], true);
    $sponsor_id = isset($filter_array['sponsor'])?$filter_array['sponsor']:'';
    $report_for = isset($filter_array['report_for']) ? trim($filter_array['report_for']) : '';
    $company_id = isset($filter_array['company']) ? $filter_array['company'] : 0;
    $branch_id = isset($filter_array['branch']) ? $filter_array['branch'] : 0;
    $broker_id = isset($filter_array['broker']) ? $filter_array['broker'] : 0;
    $beginning_date = isset($filter_array['beginning_date']) && !empty($filter_array['beginning_date']) ? date('Y-m-d H:i:s', strtotime($instance->re_db_input($filter_array['beginning_date']))) : '';
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

    $trade_dates= date('m/d/Y',strtotime($beginning_date)) ." thru ". date('m/d/Y',strtotime($ending_date));

    $company_name='All Companies';
    if(isset($company_id) && $company_id != 0){
        $queried_company=$instance_company->select_company_by_id($company_id);
        $company_name=$queried_company['company_name'];
    }

    $branch_name='All Branches';
    if(isset($branch_id) && $branch_id != 0){
        $queried_branch=$instance_branch->select_branch_by_id($branch_id);
        $branch_name=$queried_branch['name'];
    }

    $trade_data= $instance_trans->daily_trade_blotter_report($company_id,$branch_id,$broker_id,$beginning_date,$ending_date);
    // echo "<pre>"; print_r($trade_data); 
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

    $subheading = 'DAILY TRADE BLOTTER REPORT';
    $subheading2 = 'Company: '.$company_name.',Branch: '.$branch_name.' Broker: '.$queried_brokers;

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
        <th style="font-size: 10px; width: 7%;">TRADE#</th>
        <th style="padding: 8px 0px;font-size: 10px; width: 6%;">BRANCH</th>
        <th style="padding: 8px 0px;font-size: 10px; width: 8%;">DATE</th>
        <th style="padding: 8px 0px;font-size: 10px; width: 8%;">CHECK DATE</th>
        <th style="padding: 8px 0px;font-size: 10px; width: 15%;" >CLIENT</th>
        <th style="padding: 8px 0px;font-size: 10px; width: 13%;">BROKER</th>
        <th style="padding: 8px 0px;font-size: 10px; width: 20%;">SPONSOR/ <br>PRODUCT</th>
        <th style="padding: 8px 0px;font-size: 10px; width: 7%;">AMOUNT</th>
        <th style="padding: 15px 0px;font-size: 10px; width: 8%;">COMM.REC</th>
        <th style="padding: 8px 0px;font-size: 10px; width: 9%;">DATE REC/<br> DATE PAID</th>
    </tr>
    <tr>
        <th colspan="10"></th>
    </tr>';

    //  echo $html;die;


    if($trade_data != array())
    {
        $total_amount=0.00;
        $total_comm_rec= 0.00;
        foreach($trade_data as $trade)
        {
            $is_recrod_found=true;
            if($trade['check_date']== '0000-00-00')
            { 
                $trade['check_date']='';
            } 
            else{
                $trade['check_date']=date('m/d/Y',strtotime($trade['check_date']));
            }

            if($trade['date_paid']== '0000-00-00')
            { 
                $trade['date_paid']='';
            } 
            else{
                $trade['date_paid']=date('m/d/Y',strtotime($trade['date_paid']));
            }
            $html.='<tr style="color:#393939;">
                        <td>'. $trade['id'].'</td>
                        <td>'.$trade['branch'].'</td>
                        <td>'. date('m/d/Y',strtotime($trade['trade_date'])) .'</td>
                        <td>'.$trade['check_date'].'</td>
                        <td>'.$trade['client_lastname'].", ".$trade['client_firstname'].'</td>
                        <td>'.$trade['broker_last_name'].", ".$trade['broker_firstname'].'</td>
                        <td>'.$trade['sponsor_name'].'</td>
                        <td>'.$trade['invest_amount'].'</td>
                        <td>'.$trade['commission_received'].'</td>
                        <td>'.date('m/d/Y',strtotime($trade['commission_received_date'])).'</td>
                    </tr>
                    <tr style="color:#393939;">
                        <td colspan="2">'. $trade['created_by'].'</td>
                        <td colspan="2">'. date('m/d/Y h:i:s',strtotime($trade['created_time'])).'</td>
                        <td></td>
                        <td></td>
                        <td>'.$trade['product_name'].'</td>
                        <td></td>
                        <td></td>
                        <td>'.$trade['date_paid'].'</td>
                    </tr>';
                    $total_amount += $trade['invest_amount'];
                    $total_comm_rec += $trade['commission_received'];
        }   
        if($is_recrod_found==true)
                            {
                                $html.='<tr>
                                        <td colspan="10"></td>
                                    </tr>
                                    <tr>
                                            <td colspan="6"></td>
                                            <td style="text-align: center;"><hr><b>TOTAL:</b></td>
                                            <td><hr><b>'. number_format($total_amount,2) .'</b></td>
                                            <td colspan="2" style="padding-left: 3px;"><hr><b>'. number_format($total_comm_rec,2) .'</b></td>
                                        </tr>';
                            }  
        }
        if($is_recrod_found==false)
        {
            $html.='<tr>
                        <td style="font-size:8px;font-weight:cold;text-align:center;" colspan="7">No record found.</td>
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
    $pdf->Output('report_daily_trade_blotter.pdf', 'I');
    
    exit;
?>