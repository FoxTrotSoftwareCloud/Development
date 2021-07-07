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
    $beginning_date = isset($filter_array['beginning_date']) && !empty($filter_array['beginning_date']) ?date('Y-m-d H:i:s',strtotime($instance->re_db_input($filter_array['beginning_date']))):'';
    $ending_date = isset($filter_array['ending_date']) && !empty($filter_array['ending_date']) ? date('Y-m-d',strtotime($instance->re_db_input($filter_array['ending_date']))):'';
    $return_client_review_list = $instance->get_client_review_report($broker_id,$beginning_date,$ending_date);

        function get_broker_name_only($brokerA) {
            return $brokerA['first_name'].' '.$brokerA['last_name'];
        }
        $queried_brokers=isset($broker_id) && $broker_id!=0 ? implode(",",array_map("get_broker_name_only",array_filter($get_brokers,function ($brokerA) use ($broker_id){return $brokerA['id']==$broker_id ? true :false;}))) : '(All Brokers)';

        function get_sponsor_name_only($sponsorA) {
            return $sponsorA['name'];
        }
        function get_short_name_only($stateA) {
        return $stateA['short_name'];
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
                $date_output='';
                if (isset($filter_array['beginning_date']) && !empty($filter_array['beginning_date']) && isset($filter_array['ending_date']) && !empty($filter_array['ending_date'])) {
                        $date_output= date('m/d/Y',strtotime($filter_array['beginning_date'])).'&nbsp; through &nbsp;'.date('m/d/Y',strtotime($filter_array['ending_date'])) ;                        
                    }
                    else {
                        $date_output= date('1/01/1970').'&nbsp; through &nbsp;'.date('m/d/Y');
                    }
                $html .='<td style="width:60%;font-size:18px;font-weight:bold;text-align:center;">CLIENT REVIEW REPORT<br/><span style="text-align:center;font-size:12px;">
                '.$date_output.'</span>
                </td>'; 
                $html.='<td style="width:20%;text-align:center;">'.date('m/d/Y H:i:s').'</td></tr><tr><td colspan="4"></td></tr>';
                $html.='<tr><td></td></tr>
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
    $html='<table border="0" cellpadding="1" width="100%">
                <tr style="background-color:#f1f1f1;">
                            <th style="width:12%; height:30px;font-size:12px;line-height:20px;font-weight:bold;">Client Name</th>
                            <th style="width:12%; height:30px;font-size:12px;line-height:20px;font-weight:bold;">Account No.</th>
                            <th style="width:11%; height:30px;font-size:12px;line-height:20px;font-weight:bold;">Client No.</th>
                            <th style="width:11%; height:30px;font-size:12px;line-height:20px;font-weight:bold;">Telephone</th>
                            <th style="width:11%; height:30px;font-size:12px;line-height:20px;font-weight:bold;">Review Date</th>
                            <th style="width:11%; height:30px;font-size:12px;line-height:20px;font-weight:bold;">Birth Date</th>
                            <th style="width:32%; height:30px;font-size:13px;line-height:20px;font-weight:bold;">Address</th>
                        </tr>';
    
    
    if($return_client_review_list != array())
    {   
        /*echo "<pre>";
        print_r($return_client_review_list);die;*/
        $last_record=0;
        foreach($return_client_review_list as $client_review)
        {
            $is_recrod_found=true;
            if(!empty($client_review['client_accounts'])):
            $current_client_ac_list=array();
            $current_client_ac_list_count=0;
            $supervisor_shortname=substr($client_review['lfname'], 0,1).substr($client_review['bfname'], 0,1);
           $html.='<tr>
                        <td colspan="7">
                            <p class="supervisior" style="text-align:center;text-decoration:underline;font-style:italic;font-weight:bold;font-size:12px;">REVIEWING SUPERVISOR: '.$client_review['lfname'].''.$client_review['bfname'].' / '.$supervisor_shortname.'&nbsp;('.$client_review['broker_fund'].')</p>
                            <table style="border-spacing:0 2px">';
            
               foreach($client_review['client_accounts'] as $client_account):
               $current_client=$client_account['client_data'][$client_review['broker_id']][0];
               $current_client_ac_list_count+=count($client_account['client_data'][$client_review['broker_id']]['account_list']);
               $current_client_ac_list=$client_account['client_data'][$client_review['broker_id']]['account_list'];
               $current_client_sponsor_list=$client_account['client_data'][$client_review['broker_id']]['client_sponsor'];
               $is_middle_name=(!empty($current_client['mi'])) ? ' '.substr($current_client['mi'], 0,1).'.' :'';
               $state_name=implode(" ",array_map("get_short_name_only",array_filter($get_states,function ($stateA) use ($client){return $stateA['id']==$current_client['state'] ? true :false;})));
               $telephone_no=$current_client['telephone']!="" ? sprintf("(%s) %s-%s",
                              substr($current_client['telephone'], 0, 3),
                              substr($current_client['telephone'], 3, 3),
                              substr($current_client['telephone'], 6)) : '';
               $reviewed_at=(!empty($current_client['reviewed_at'])) ? date('m/d/Y',strtotime($current_client['reviewed_at'])) : '&nbsp;'.$supervisor_shortname.' /';
               $reviewed_by=(!empty($current_client['birth_date'])) ? date('m/d/Y',strtotime($current_client['birth_date'])) : '';
               // print_r($current_client);
             $html.='<tr>
                        <td colspan="2">'.$current_client['last_name'].',&nbsp;'.$current_client['first_name'].' '.$is_middle_name.'</td>
                        <td style="width:11%;">'.$current_client['client_file_number'].'</td>
                        <td style="width:11%;">'.$telephone_no.'</td>
                        <td style="width:11%;">'.$reviewed_at.'</td>
                        <td style="width:11%;">'.$reviewed_by.'</td>
                        <td style="width:34%;">'.$current_client['address1'].', '.$current_client['city'].', '.$state_name.' '.$current_client['zip_code'].'</td>
                    </tr>';


            if(count($current_client_ac_list) > 0):
               $sponsor_key=0;
               foreach($current_client_ac_list as $ac_no=>$ac_list):
               $sponsor_name=implode(",",array_map("get_sponsor_name_only",array_filter($get_sponsors,function ($sponsorA) use ($current_client_sponsor_list,$sponsor_key){return $sponsorA['id']==$current_client_sponsor_list[$sponsor_key] ? true :false;})));
               $sponsor_key++;   
            $html.='<tr>
                    <td style="width:11%;"></td>
                    <td colspan="2" style="background-color: #f1f1f1;">'.$ac_no.'</td>
                    <td style="background-color: #f1f1f1;">'.date('m/d/Y',strtotime($ac_list)).'</td>
                    <td colspan="2" style="background-color: #f1f1f1;">'.$sponsor_name.'</td>
                </tr>';
             endforeach; 
             endif;
             endforeach;
             
            $html.='</table>
                        </td>
                    </tr>';
            if($current_client_ac_list_count > 0):         
                $html.='<tr>
                        <td colspan="5">
                            <hr style="height: 1px;background: #555;">
                            <p style="text-transform: uppercase;font-weight: bold;font-size: 12px;">REVIEWING SUPERVISOR:'.$client_review['lfname'].''.$client_review['bfname'].''.$supervisor_shortname.' TOTAL: '.$current_client_ac_list_count.'</p>
                        </td>
                    </tr>';
            endif;
            endif;                                                                    
        }     
    }
    else
    {
        $html.='<tr>
                    <td style="font-size:8px;font-weight:cold;text-align:center;" colspan="8">No record found.</td>
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
    $pdf->Output('report_client_ac.pdf', 'I');
    
    exit;
?>