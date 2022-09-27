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
$return_batches = array();
$filter_array = array();
$product_category = '';
$product_category_name = '';
$beginning_date = '';
$ending_date = '';
$instance_broker = new broker_master();
$get_brokers=$instance_broker->select_broker();
//filter batch report
if(isset($_GET['filter']) && $_GET['filter'] != '')
{
    $filter_array = json_decode($_GET['filter'],true);//echo '<pre>';print_r($filter_array);exit;
    $state_id = isset($filter_array['state'])?$filter_array['state']:0;
    $broker_id = $broker = isset($filter_array['broker'])?$filter_array['broker']:0;
    $sponsor = isset($filter_array['sponsor'])?$filter_array['sponsor']:'';
    
    $return_broker_list = $instance->get_client_report($state_id,$broker);
    function get_state_name($stateA) {
        return $stateA['id']==$state ? true :false;
    }
    function get_name_only($stateA) {
        return $stateA['name'];
    }
    function get_short_name_only($stateA) {
                return $stateA['short_name'];
            }
    $queried_states=isset($state_id) && $state_id!=0 ? implode(",",array_map("get_name_only",array_filter($get_states,function ($stateA) use ($state_id){return $stateA['id']==$state_id ? true :false;}))) : 'All';
    function get_broker_name_only($brokerA) {
        return $brokerA['first_name'].' '.$brokerA['last_name'];
    }
    $queried_brokers=isset($broker_id) && $broker_id!=0 ? implode(",",array_map("get_broker_name_only",array_filter($get_brokers,function ($brokerA) use ($broker_id){return $brokerA['id']==$broker_id ? true :false;}))) : 'All Brokers';
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
   /* $html='<table border="0" width="100%">
                <tr>';
                if(isset($system_logo) && $system_logo != '')
                {
                    $html .='<td width="20%" align="left">'.$img.'</td>';
                }
                $html .='<td colspan="3" style="font-size:14px;font-weight:bold;text-align:center;"> CLIENT LIST BY STATE<br/>State: '.$queried_states.'</td>'; 
                if(isset($system_company_name) && $system_company_name != '')
                {
                    //$html.='<td width="20%" style="font-size:10px;font-weight:bold;text-align:right;">'.$system_company_name.'</td>';
                }
                $html.='<td style="text-align:center;">'.date('m/d/Y H:i:s').'</td></tr><tr><td colspan="4"></td></tr>
        </table>';*/

    $subheading = 'CLIENT LIST BY STATE';
    $subheading2 = 'State: '.$queried_states.', Broker: '.$queried_brokers;

     $html='<table border="0" width="100%">
                        <tr>';
                         $html .='<td width="20%" align="left">'.date("m/d/Y").'</td>';
                        
                        $html .='<td width="60%" style="font-size:12px;font-weight:bold;text-align:center;">'.$img.'<br/><strong><h9>'.$subheading.'<br/>'.$subheading2.' <br></h9></strong></td>';
                                         
                            $html.='<td width="20%" align="right">Page 1</td>';
                        
                        $html.='</tr>
                </table> </br>';

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
    $pdf->SetMargins(10, 10, 20, true);

    $html='<table border="0" cellpadding="1" width="100%" border-spacing:0px;>
                <tr style="background-color: #f1f1f1;">
                    <th style="font-size:12px;font-weight:500;color:#393939;text-transform:capitalize;text-align:left;width:24%;height:40px;line-height:25px;">&nbsp;CLIENT NAME</th>
                    <th style="font-size:12px;font-weight:500;color:#393939;text-transform:capitalize;text-align:left;width:11%;height:40px;line-height:25px;">CLIENT NO.</th>
                    <th style="font-size:12px;font-weight:500;color:#393939;text-transform:capitalize;text-align:left;width:11%;height:40px;line-height:25px;">TELEPHONE</th>
                    <th style="font-size:12px;font-weight:500;color:#393939;text-transform:capitalize;text-align:left;width:11%;height:40px;line-height:25px;">OPEN DATE</th>
                    <th style="font-size:12px;font-weight:500;color:#393939;text-transform:capitalize;text-align:left;width:11%;height:40px;line-height:25px;">BIRTH DATE</th>
                    <th style="font-size:12px;font-weight:500;color:#393939;text-transform:capitalize;text-align:left;width:35%;height:40px;line-height:25px;">ADDRESS</th>
                </tr><tr><th colspan="7">
                        </th></tr>';
    
    if($return_broker_list != array())
    {
        $is_recrod_found=false;
        foreach($return_broker_list as $broker)
        {
            if(!empty($broker['clients'])): 
                $html.='<tr>
                            <td style="font-size:10px;font-weight:bold;">Broker: '.$broker['lfname'].',&nbsp;&nbsp;'.$broker['bfname'].'&nbsp;&nbsp;('.$broker['broker_fund'].')
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>';
                foreach($broker['clients'] as $client):
                $is_recrod_found=true;        
                $state_name=implode(" ",array_map("get_short_name_only",array_filter($get_states,function ($stateA) use ($client){return $stateA['id']==$client['state'] ? true :false;})));

               

                

                $telephone=$client['telephone']!="" ? sprintf("(%s) %s-%s",
                                      substr($client['telephone'], 0, 3),
                                      substr($client['telephone'], 3, 3),
                                      substr($client['telephone'], 6)) : '';
                $is_middle_name=(!empty($client['mi'])) ? ' '.substr($client['mi'], 0,1).'.' :'';      

                $address = !empty($client['address1']) ? $client['address1'].', ' : '';
                $address.= !empty($client['city']) ? $client['city'].', ' : '';
                $address.= !empty($client['state']) ? $state_name.' ' : '';
                $address.= !empty($client['zip_code']) ? $client['zip_code'].' ' : '';
                $html.='<tr>
                            <td style="font-size:10px;font-weight:normal;color:#393939;">'.($client['last_name']).', '.($client['first_name']).$is_middle_name.
                            '</td>
                            <td style="font-size:10px;font-weight:normal;color:#393939;">'.$client['client_file_number'].'</td>
                            <td style="font-size:10px;font-weight:normal;color:#393939;">'.$telephone.'</td>
                            <td style="font-size:10px;font-weight:normal;color:#393939;">'.date('m/d/Y',strtotime($client['open_date'])).'</td>
                            <td style="font-size:10px;font-weight:normal;color:#393939;">'.date('m/d/Y',strtotime($client['birth_date'])).'</td>
                            <td style="font-size:10px;font-weight:normal;color:#393939;">'.$address.'</td>
                        </tr>';
                endforeach;
                $html.='<br/>';                        
          endif;      
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
    $pdf->Output('report_client.pdf', 'I');
    
    exit;
?>