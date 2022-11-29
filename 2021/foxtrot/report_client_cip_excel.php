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
$system_logo = isset($get_logo['logo'])?$instance->re_db_input($get_logo['logo']):'';
$img = '<img src="upload/logo/'.$system_logo.'" height="25px" />';
$get_company_name = $instance->get_company_name();
$system_company_name = isset($get_company_name['company_name'])?$instance->re_db_input($get_company_name['company_name']):'';

$creator                = "Foxtrot User";
$last_modified_by       = "Foxtrot User";

$subject                = "CLIENT CIP REPORT";
$description            = "CLIENT CIP REPORT";
$keywords               = "CLIENT CIP REPORT";
$category               = "CLIENT CIP REPORT";
$total_sub_sheets       = 1;

$default_open_sub_sheet = 0; // 0 means first indexed sub sheets.
$excel_name             = 'Client CIP Report';
$sheet_data             = array();

$report_for = '';

//filter report
if(isset($_GET['filter']) && $_GET['filter'] != '')
{
    $filter_array = json_decode($_GET['filter'], true);
    $sponsor_id = isset($filter_array['sponsor'])?$filter_array['sponsor']:'';
    $broker_id = isset($filter_array['broker']) ? $filter_array['broker'] : 0;
    $cip_client = isset($filter_array['cip_client']) ? $filter_array['cip_client'] : 0;
    $exclude_donot_contact_client = isset($filter_array['dont_contact_client']) ? $filter_array['dont_contact_client'] : 0;
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
    
    $heading = $excel_name = 'CLIENT CIP REPORT';    
    $subheading2= 'Broker: '. $queried_brokers.', Sponsor: '. $sponsor_name.", ".$cip_info ;


    $sheet_data = array( // Set sheet data.
        0=> // Excel sub sheet indexed.
        array(
            
            'A1'=>array(date("m/d/Y"),array('bold','center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('A1','A2'))),
            'B1'=>array($heading." \r\n ".$subheading2."  ",array('bold','center','color'=>array('000000'),'size'=>array(14),'font_name'=>array('Calibri'),'merge'=>array('B1','S2'))),
            'T1'=>array(' PAGE 1',array('bold','center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('T1','U2'))),
            
            'A3'=>array('',array('bold','center','color'=>array('000000'),'size'=>array(12),'font_name'=>array('Calibri'),'merge'=>array('A3','Y3'))),


            'B4'=>array(strtoupper('ID'),array('bold','left','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
            'C4'=>array(strtoupper("TYPE") ,array('bold','left','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
            'D4'=>array(strtoupper("NUMBER") ,array('bold','left','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
            'E4'=>array(strtoupper("EXPIRATION DATE") ,array('bold','left','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
            'F4'=>array(strtoupper("STATE") ,array('bold','left','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
            'G4'=>array(strtoupper("VARIFIED DATE") ,array('bold','left','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
            'H4'=>array(strtoupper("OPEN DATE") ,array('bold','left','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
            'I4'=>array(strtoupper("DOB") ,array('bold','left','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
            'J4'=>array(strtoupper("NAME"),array('bold','left','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('J4','L4'))),
            'M4'=>array(strtoupper("ADDRESS"),array('bold','left','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('M4','O4'))),
            'P4'=>array(strtoupper("CITY") ,array('bold','left','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
            'Q4'=>array(strtoupper("STATE") ,array('bold','left','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
            'R4'=>array(strtoupper("ZIP CODE") ,array('bold','left','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
            'S4'=>array(strtoupper("PHONE NUMBER"),array('bold','left','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('S4','T4'))),
            'U4'=>array(strtoupper("SSN") ,array('bold','left','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
        )
    );
    $i = 5;

    if($list_data != array()) {
        $last_record=0;
        $is_recrod_found=false;
        $total_amount=0.00;
        $total_comm_rec= 0.00;
        foreach($list_data as $row) {

            $row['employ_number']=isset($row['employ_number'])?$row['employ_number']:'';
            $row['expiration'] = (isset($row['expiration']) && $row['expiration']!= '0000-00-00')? date('m/d/Y',strtotime($row['expiration'])):''; 
            $row['employ_state']=isset($row['employ_state'])?$row['employ_state']:'';
            $row['date_verified'] = (isset($row['date_verified']) && $row['date_verified']!= '0000-00-00')? date('m/d/Y',strtotime($row['date_verified'])):''; 
            $row['open_date'] = (isset($row['open_date']) && $row['open_date']!= '0000-00-00')? date('m/d/Y',strtotime($row['open_date'])):''; 
            $row['birth_date'] = (isset($row['birth_date']) && $row['birth_date']!= '0000-00-00')? date('m/d/Y',strtotime($row['birth_date'])):''; 
            $name = $row['last_name'].", ".$row['first_name'];
            $address = $row['address1'].', '.$row['address2'];


            // $sheet_data[0]['B'.$i] = array($row['id'],array('left','bold','size'=>array(8),'color'=>array('000000')));
            // $sheet_data[0]['C'.$i] = array($row['branch'],array('left','bold','size'=>array(8),'color'=>array('000000')));
            $sheet_data[0]['D'.$i] = array($row['employ_number'],array('left','size'=>array(8),'color'=>array('000000')));
            $sheet_data[0]['E'.$i] = array($row['expiration'],array('left','size'=>array(8),'color'=>array('000000')));
            $sheet_data[0]['F'.$i] = array($row['employ_state'], array('left','color'=>array('000000'),'size'=>array(10)));
            $sheet_data[0]['G'.$i] = array($row['date_verified'], array('left','color'=>array('000000'),'size'=>array(10)));
            $sheet_data[0]['H'.$i] = array($row['open_date'], array('left','color'=>array('000000'),'size'=>array(10)));
            $sheet_data[0]['I'.$i] = array($row['birth_date'], array('left','color'=>array('000000'),'size'=>array(10)));
            $sheet_data[0]['J'.$i] = array($name, array('left','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('J'.$i,'L'.$i)));
            $sheet_data[0]['M'.$i] = array($address, array('left','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('M'.$i,'O'.$i)));
            $sheet_data[0]['P'.$i] = array($row['city'],array('left','bold','size'=>array(8),'color'=>array('000000')));
            $sheet_data[0]['Q'.$i] = array($row['state_name'],array('left','bold','size'=>array(8),'color'=>array('000000')));
            $sheet_data[0]['R'.$i] = array($row['zip_code'],array('left','bold','size'=>array(8),'color'=>array('000000')));
            $sheet_data[0]['S'.$i] = array($row['telephone'], array('left','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('S'.$i,'T'.$i)));
            $sheet_data[0]['U'.$i] = array($row['client_ssn'],array('left','bold','size'=>array(8),'color'=>array('000000')));

            $i= $i+1;
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



