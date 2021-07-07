<?php 
require_once("include/config.php");
require_once(DIR_FS."islogin.php");
$instance = new client_review();

//DEFAULT PDF DATA:
$get_logo = $instance->get_system_logo();
$system_logo = isset($get_logo['logo'])?$instance->re_db_input($get_logo['logo']):'';
$get_company_name = $instance->get_company_name();
$system_company_name = isset($get_company_name['company_name'])?$instance->re_db_input($get_company_name['company_name']):'';
$client_maintenance_instance = new client_maintenance();
$get_states = $client_maintenance_instance->select_state();
$instance_broker = new broker_master();
$get_brokers=$instance_broker->select_broker();
$client_sponsor_instance = new manage_sponsor();
$get_sponsors = $client_sponsor_instance->select_sponsor();
$return_batches = array();
$filter_array = array();
$product_category = '';
$product_category_name = '';
$beginning_date = '';
$ending_date = '';

//filter batch report
if(isset($_GET['filter']) && $_GET['filter'] != '')
{
    $filter_array = json_decode($_GET['filter'],true);//echo '<pre>';print_r($filter_array);exit;
    $state = isset($filter_array['state'])?$filter_array['state']:0;
    $broker_id = isset($filter_array['broker'])?$filter_array['broker']:0;
    $sponsor_id = isset($filter_array['sponsor'])?$filter_array['sponsor']:'';
    
    $return_client_accounts= $instance->get_client_account_list($sponsor_id,$broker_id);

        function get_broker_name_only($brokerA) {
            return $brokerA['first_name'].' '.$brokerA['last_name'];
        }
        $queried_brokers=isset($broker_id) && $broker_id!=0 ? implode(",",array_map("get_broker_name_only",array_filter($get_brokers,function ($brokerA) use ($broker_id){return $brokerA['id']==$broker_id ? true :false;}))) : '(All Brokers)';

        function get_sponsor_name_only($sponsorA) {
            return $sponsorA['name'];
        }
        $queried_sponsors=isset($sponsor_id) && $sponsor_id!=0 ? implode(",",array_map("get_sponsor_name_only",array_filter($get_sponsors,function ($sponsorA) use ($sponsor_id){return $sponsorA['id']==$sponsor_id ? true :false;}))) : '(All Sponsors)';
}

$total_received_amount = 0;
$total_posted_amount = 0;
$total_records=0;
$total_records_sub=0;

?>
<?php

    // create new PDF document
    $pdf = new RRPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    // add a page
    $pdf->AddPage('L');
    // Title
    $img = '<img src="'.SITE_URL."upload/logo/".$system_logo.'" height="25px" />';
    
    $pdf->SetFont('times','B',12);
    $pdf->SetFont('times','',10);
    $html='<table border="0" width="100%">
                <tr>';
                if(isset($system_logo) && $system_logo != '')
                {
                    $html .='<td width="20%" align="left">'.$img.'</td>';
                }
                if(isset($system_company_name) && $system_company_name != '')
                {
                    //$html.='<td width="20%" style="font-size:10px;font-weight:bold;text-align:right;">'.$system_company_name.'</td>';
                    $html_company_name="<span>$system_company_name</span><br/><br/>";
                }
                $html .='<td style="width:60%;font-size:14px;font-weight:bold;text-align:center;">'.$html_company_name.'
                Broker: '. $queried_brokers.', Client: (All Clients), Sponsor: '. $queried_sponsors.'
                </td>'; 
                $html.='<td style="width:20%;text-align:center;">'.date('m/d/Y H:i:s').'</td></tr><tr><td colspan="4"></td></tr>';
                $html.='</tr><tr><td></td></tr>
        </table>';
    $pdf->writeHTML($html, false, 0, false, 0);
    $pdf->Ln(2);
    
    /*$pdf->SetFont('times','B',12);
    $pdf->SetFont('times','',10);
    $html='<table border="0">
                <tr>';
                    $html .='<td width="100%" style="font-size:12px;font-weight:bold;text-align:center;">'.$beginning_date.'-'.$ending_date.'</td>';
            $html .='</tr>
            </table>';
    $pdf->writeHTML($html, false, 0, false, 0);
    $pdf->Ln(2);*/
    
    $pdf->SetFont('times','B',12);
    $pdf->SetFont('times','',10);
    $html='<table border="0" cellpadding="1" width="100%" border-spacing:0px;>
                <tr style="background-color: #f1f1f1;">
                            <th style="font-size:12px;font-weight:500;color:#393939;text-transform:capitalize;text-align:left;width:24%;height:40px;line-height:25px;">Client Name</th>
                            <th style="font-size:12px;font-weight:500;color:#393939;text-transform:capitalize;text-align:left;width:11%;height:40px;line-height:25px;">Account #</th>
                            <th style="font-size:12px;font-weight:500;color:#393939;text-transform:capitalize;text-align:left;width:11%;height:40px;line-height:25px;">Company</th>
                            <th></th>
                        </tr>';
    $html.='<tr><th colspan="8">
                        </th></tr>';
    
    
    if($return_client_accounts != array())
    {
        $last_record=0;
        $is_recrod_found=false;
        foreach($return_client_accounts as $broker)
        {
            $html.='<tr>
                        <td style="font-size:10px;font-weight:bold;">Broker: '.$broker['lfname'].',&nbsp;&nbsp;'.$broker['bfname'].'&nbsp;&nbsp;('.$broker['broker_fund'].')
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>';
            foreach($broker['clients'] as $client):
                $is_recrod_found=true;         
            $is_middle_name=(!empty($client['mi'])) ? ' '.substr($client['mi'], 0,1).'.' :'';
           $html.='<tr>
                    <td colspan="2"> '. $client['last_name'].',&nbsp; '.$client['first_name'].' '.$is_middle_name.'</td>
                    <td colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Client # &nbsp;'.$client['client_ssn']. '&nbsp;&nbsp;&nbsp;&nbsp;Clearing Account # '.$client['clearing_account'].'
                    </td>
                </tr>';
            if(!empty($client['client_accounts']) || !empty($client['clearing_account'])):
                foreach($client['client_accounts'] as $client_account):
            $html.='<tr>
                        <td></td>
                        <td>'.$client_account['account_no'].'</td>
                        <td>'.$client_account['sponsor_name'].'</td>
                    </tr>';
                endforeach;    
            endif;
        endforeach;
            $html.='<br/>';                             
        }     
    }
    if($is_recrod_found==false)
    {
        $html.='<tr>
                    <td style="font-size:8px;font-weight:cold;text-align:center;" colspan="8">No record found.</td>
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
    $pdf->Output('report_client_ac.pdf', 'I');
    
    exit;
?>