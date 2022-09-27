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

$creator                = "Foxtrot User";
$last_modified_by       = "Foxtrot User";
// $title                  = "Production by  Category";
$subject                = " Production by Production Category";
$description            = " Production by Production Category";
$keywords               = " Production by Production Category";
$category               = " Production by Production Category.";
$total_sub_sheets       = 1;

$default_open_sub_sheet = 0; // 0 means first indexed sub sheets.
$excel_name             = 'Client Review Report';

$sheet_data             = array();


$report_for = '';

//filter batch report
if(isset($_GET['filter']) && $_GET['filter'] != '')
{
    $filter_array = json_decode($_GET['filter'],true);//echo '<pre>';print_r($filter_array);exit;

    $report_for = isset($filter_array['report_for'])?trim($filter_array['report_for']):'';
    $state = isset($filter_array['state'])?$filter_array['state']:0;
    $broker_id = isset($filter_array['broker'])?$filter_array['broker']:0;
    $sponsor_id = isset($filter_array['sponsor'])?$filter_array['sponsor']:'';
    $beginning_date = isset($filter_array['beginning_date']) && !empty($filter_array['beginning_date']) ?date('Y-m-d H:i:s',strtotime($instance->re_db_input($filter_array['beginning_date']))):'';
    $ending_date = isset($filter_array['ending_date']) && !empty($filter_array['ending_date']) ? date('Y-m-d',strtotime($instance->re_db_input($filter_array['ending_date']))):'';
    $dont_contact_client =  isset($filter_array['dont_contact_client'])?$filter_array['dont_contact_client']:0;

        function get_broker_name_only($brokerA) {
            return $brokerA['first_name'].' '.$brokerA['last_name'];
        }
        $queried_brokers=isset($broker_id) && $broker_id!=0 ? implode(",",array_map("get_broker_name_only",array_filter($get_brokers,function ($brokerA) use ($broker_id){return $brokerA['id']==$broker_id ? true :false;}))) : 'All Brokers';

        function get_sponsor_name_only($sponsorA) {
            return $sponsorA['name'];
        }
        function get_short_name_only($stateA) {
        return $stateA['short_name'];
    }
    
}
$queried_sponsors=isset($sponsor_id) && $sponsor_id!=0 ? implode(",",array_map("get_sponsor_name_only",array_filter($get_sponsors,function ($sponsorA) use ($sponsor_id){return $sponsorA['id']==$sponsor_id ? true :false;}))) : 'All Sponsors';

$queried_states=isset($state_id) && $state_id!=0 ? implode(",",array_map("get_name_only",array_filter($get_states,function ($stateA) use ($state_id){return $stateA['id']==$state_id ? true :false;}))) : 'ALL';

if($report_for == 3 ) {
    $return_client_review_list = $instance->get_client_review_report($broker_id,$beginning_date,$ending_date,$dont_contact_client);
    $total_received_amount = 0;
    $total_posted_amount = 0;
    $total_records=0;
    $total_records_sub=0;


    $date_output='';
    if (isset($filter_array['beginning_date']) && !empty($filter_array['beginning_date']) && isset($filter_array['ending_date']) && !empty($filter_array['ending_date'])) {
            $date_output= date('m/d/Y',strtotime($filter_array['beginning_date'])).'  through  '.date('m/d/Y',strtotime($filter_array['ending_date'])) ;                        
    } else {
        $date_output= date('1/01/1970').'  through  '.date('m/d/Y');
    }

    $sheet_data = array( // Set sheet data.
            0=> // Excel sub sheet indexed.
            array(
                
                'A1'=>array(date("m/d/Y"),array('bold','center','color'=>array('000000'),'size'=>array(16),'font_name'=>array('Calibri'),'merge'=>array('A1','A2'))),
                'B1'=>array($img."\r\n  CLIENT REVIEW REPORT \r\n ".$date_output."  ",array('bold','center','color'=>array('000000'),'size'=>array(14),'font_name'=>array('Calibri'),'merge'=>array('B1','F2'))),
                'G1'=>array(' PAGE 1',array('bold','center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('G1','H2'))),
                
                'A3'=>array('',array('bold','center','color'=>array('000000'),'size'=>array(12),'font_name'=>array('Calibri'),'merge'=>array('A3','H3'))),
                'A4'=>array(strtoupper('Client Name'),array('bold','left','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
                'B4'=>array(strtoupper("Account No.") ,array('bold','left','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
                'C4'=>array(strtoupper("Client No."),array('bold','left','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
                'D4'=>array(strtoupper('Telephone'),array('bold','left','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
               
                'E4'=>array(strtoupper('Review Date'),array('bold','left','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
                'F4'=>array(strtoupper('Birth Date'),array('bold','left','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
                'G4'=>array(strtoupper('Address'),array('bold','left','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),  
            )
         );
    $excel_font_style = array('left','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'));
    $inner_row_excel_font_style = array('left','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'),'background'=>array('f1f1f1'));
    //$excel_font_style = array('center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'));
    //'background'=>array('f1f1f1'),
  	$i=5;
	if($return_client_review_list != array()) {   
        
        $last_record=0;
        foreach($return_client_review_list as $client_review)
        {
            $is_recrod_found=true;
            if(!empty($client_review['client_accounts'])):
	            $current_client_ac_list=array();
	            $current_client_ac_list_count=0;
	            $supervisor_shortname=substr($client_review['bfname'], 0,1).substr($client_review['lfname'], 0,1);


	            $inner_heading = 'REVIEWING SUPERVISOR: '.strtoupper($client_review['bfname']).' '.strtoupper($client_review['lfname']).' / '.$supervisor_shortname.' ('.$client_review['broker_fund'].')';

	            $sheet_data[0]['A'.$i] = array($inner_heading,array('center','bold','italic','size'=>array(10),'color'=>array('000000'),'merge'=>array('A'.$i,'G'.$i)));

	            	$i=$i+1;

	        
	            
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
	               $reviewed_at=(!empty($current_client['reviewed_at'])) ? date('m/d/Y',strtotime($current_client['reviewed_at'])) : ' '.$supervisor_shortname.' /';
	               $reviewed_by=(!empty($current_client['birth_date'])) ? date('m/d/Y',strtotime($current_client['birth_date'])) : '';


	                $sheet_data[0]['A'.$i] = array($current_client['last_name'].', '.$current_client['first_name'],
	                				array('left','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('A'.$i,'B'.$i)));	               
	                $sheet_data[0]['C'.$i] = array($current_client['client_file_number'],$excel_font_style);
	                $sheet_data[0]['D'.$i] = array($telephone_no,$excel_font_style);
	                $sheet_data[0]['E'.$i] = array($reviewed_at,$excel_font_style);
	                $sheet_data[0]['F'.$i] = array($reviewed_by,$excel_font_style);
	                $sheet_data[0]['G'.$i] = array($current_client['address1'].', '.$current_client['city'].', '.$state_name.' '.$current_client['zip_code'], array('left','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
                


	           


		            if(count($current_client_ac_list) > 0):

		            	
		               $sponsor_key=0;
		               foreach($current_client_ac_list as $ac_no=>$ac_list):
		               	$i++;
		               $sponsor_name=implode(",",array_map("get_sponsor_name_only",array_filter($get_sponsors,function ($sponsorA) use ($current_client_sponsor_list,$sponsor_key){return $sponsorA['id']==$current_client_sponsor_list[$sponsor_key] ? true :false;})));
		               	$sponsor_key++;   
		                   	$sheet_data[0]['B'.$i] = array($ac_no,$inner_row_excel_font_style);
		                   	$sheet_data[0]['C'.$i] = array(' ',$inner_row_excel_font_style);
		                    $sheet_data[0]['D'.$i] = array(date('m/d/Y',strtotime($ac_list)),$inner_row_excel_font_style);
		                    $sheet_data[0]['E'.$i] = array($sponsor_name,$inner_row_excel_font_style);
		          
		             	endforeach; 
		             endif;

		             $i++;
	             endforeach;
	             
	            $html.='</table>
	                        </td>
	                    </tr>';
		            if($current_client_ac_list_count > 0):     
		            	$footer_heading = ' REVIEWING SUPERVISOR: '.strtoupper($client_review['bfname']).' '.strtoupper($client_review['lfname']).' / '.$supervisor_shortname.' TOTAL:  '.$current_client_ac_list_count;
		            	//$i = $i+1;
		            	$sheet_data[0]['A'.$i] = array($footer_heading,array('bold','center','size'=>array(10),'color'=>array('000000'),'merge'=>array('A'.$i,'G'.$i)));    
		            endif;
            endif;                                                                    
        }     
    }
    else
    {
    		$i++;
       $sheet_data[0]['A'.$i] = array($instance->re_db_output('No record found.'),array('center','size'=>array(10),'color'=>array('000000'),'merge'=>array('A'.$i,'G'.$i)));
    }   
}
else if($report_for == 1) {

    $heading = $excel_name = 'CLIENT ACCOUNT LISTING REPORT';
    $is_recrod_found=false;
    $return_client_accounts= $instance->get_client_account_list($sponsor_id,$broker_id);
    
    $subheading2= 'Broker: '. $queried_brokers.', Sponsor: '. $queried_sponsors;

    $sheet_data = array( // Set sheet data.
        0=> // Excel sub sheet indexed.
        array(
            
            'A1'=>array(date("m/d/Y"),array('bold','center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('A1','A2'))),
            'B1'=>array($img."\r\n  ".$heading." \r\n ".$subheading2."  ",array('bold','center','color'=>array('000000'),'size'=>array(14),'font_name'=>array('Calibri'),'merge'=>array('B1','H2'))),
            'I1'=>array(' PAGE 1',array('bold','center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('I1','J2'))),
            
            'A3'=>array('',array('bold','center','color'=>array('000000'),'size'=>array(12),'font_name'=>array('Calibri'),'merge'=>array('A3','H3'))),
            'B4'=>array(strtoupper('Client Name'),array('bold','left','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('B4','D4'))),
            'E4'=>array(strtoupper("Account No.") ,array('bold','left','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
            'F4'=>array(strtoupper("Company"),array('bold','left','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('F4','J4'))),
            
        )
    );
    $i = 5;
    if($return_client_accounts != array()) {
        $last_record=0;
        $is_recrod_found=false;
        foreach($return_client_accounts as $broker) {
            $broker_name ='Broker: '.$broker['lfname'].', '.$broker['bfname'].'  ('.$broker['broker_fund'].')';
            $sheet_data[0]['B'.$i] = array($broker_name,array('left','bold','size'=>array(8),'color'=>array('000000'),'merge'=>array('B'.$i,'D'.$i)));
            $sheet_data[0]['E'.$i] = array(' ', array('left','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('E'.$i,'J'.$i)));
            $i= $i+1;
            foreach($broker['clients'] as $client):
                $is_recrod_found=true;         
                $is_middle_name=(!empty($client['mi'])) ? ' '.substr($client['mi'], 0,1).'.' :'';

                $cl_name = $client['last_name'].', '.$client['first_name'];

                $cl_ac = str_repeat(' ',15).'Client # '.$client['client_ssn']. '    Clearing Account # '.$client['clearing_account'];

                $sheet_data[0]['B'.$i] = array($cl_name, array('left','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('B'.$i,'D'.$i)));

                $sheet_data[0]['F'.$i] = array($cl_ac, array('left','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('F'.$i,'J'.$i)));


                if(!empty($client['client_accounts']) || !empty($client['clearing_account'])):

                    foreach($client['client_accounts'] as $client_account):
                        ++$i;
                        $sheet_data[0]['B'.$i] = array(' ', array('left','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('B'.$i,'D'.$i)));
                        $sheet_data[0]['E'.$i] = array($client_account['account_no'], array('left','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
                        $sheet_data[0]['F'.$i] = array($client_account['sponsor_name'], array('left','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('F'.$i,'J'.$i)));
                    endforeach;   
                endif;
                $i++;
            endforeach;

        }
    }

}
else if ($report_for == 2) {

    $heading = $excel_name = 'CLIENT LIST BY STATE';
    $is_recrod_found=false;
    $return_broker_list = $instance->get_client_report($state_id,$broker_id);
    
    $subheading2 = 'State: '.$queried_states.', Broker: '.$queried_brokers;

    $sheet_data = array( // Set sheet data.
        0=> // Excel sub sheet indexed.
        array(
            
            'A1'=>array(date("m/d/Y"),array('bold','center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('A1','A2'))),
            'B1'=>array($img."\r\n  ".$heading." \r\n ".$subheading2."  ",array('bold','center','color'=>array('000000'),'size'=>array(14),'font_name'=>array('Calibri'),'merge'=>array('B1','G2'))),
            'H1'=>array(' PAGE 1',array('bold','center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('H1','I2'))),
            
            'A3'=>array('',array('bold','center','color'=>array('000000'),'size'=>array(12),'font_name'=>array('Calibri'),'merge'=>array('A3','H3'))),
            'B4'=>array(('CLIENT NAME'),array('bold','left','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
            'C4'=>array(("CLIENT NO.") ,array('bold','left','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
            'D4'=>array(("TELEPHONE"),array('bold','left','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
            'E4'=>array(("OPEN DATE"),array('bold','left','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
            'F4'=>array(("BIRTH DATE"),array('bold','left','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
            'G4'=>array(("ADDRESS"),array('bold','left','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
            
        )
    );
    $i = 5;
    if($return_broker_list != array()) {
        $is_recrod_found=false;
        foreach($return_broker_list as $broker) {
            if(!empty($broker['clients'])): 
                $broker_name = 'Broker: '.$broker['lfname'].', '.$broker['bfname'].' ('.$broker['broker_fund'].')';
                $sheet_data[0]['B'.$i] = array($broker_name,array('left','bold','size'=>array(8),'color'=>array('000000')));
                $sheet_data[0]['C'.$i] = array(' ', array('left','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('C'.$i,'G'.$i)));
                foreach($broker['clients'] as $client):
                    ++$i;

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
                    $cl_name = ($client['last_name']).', '.($client['first_name']).$is_middle_name;
                    $sheet_data[0]['B'.$i] = array($cl_name,array('left','size'=>array(10),'color'=>array('000000')));
                    $sheet_data[0]['C'.$i] = array($client['client_file_number'],array('left','size'=>array(10),'color'=>array('000000')));
                    $sheet_data[0]['D'.$i] = array($telephone,array('left','size'=>array(10),'color'=>array('000000')));
                    $sheet_data[0]['E'.$i] = array(date('m/d/Y',strtotime($client['open_date'])),array('left','size'=>array(10),'color'=>array('000000')));
                    $sheet_data[0]['F'.$i] = array(date('m/d/Y',strtotime($client['birth_date'])),array('left','size'=>array(10),'color'=>array('000000')));
                    $sheet_data[0]['G'.$i] = array($address,array('left','size'=>array(10),'color'=>array('000000')));

                endforeach;
            endif; 

        }
    }

}


$sub_sheet_title_array  = (array) $excel_name;
$title                  = $excel_name;
$Excel = new Excel();
    $args =  array(
        'creator'=>$creator,
        'last_modified_by'=>$last_modified_by,
        'title'=>$title,
        'subject'=>$subject,
        'description'=>$description,
        'keywords'=>$keywords,
        'category'=>$category,
        'total_sub_sheets'=>$total_sub_sheets,
        'sub_sheet_title'=>$sub_sheet_title_array,
        'default_open_sub_sheet'=>$default_open_sub_sheet,
        'sheet_data'=>$sheet_data,
        'excel_name'=>$excel_name
    );
   
$formPost = $Excel->generate( $args);