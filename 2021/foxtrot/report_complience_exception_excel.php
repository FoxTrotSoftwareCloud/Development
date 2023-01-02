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

$creator                = "Foxtrot User";
$last_modified_by       = "Foxtrot User";
// $title                  = "Production by  Category";
$subject                = "Complience Exception";
$description            = "Complience Exception";
$keywords               = "Complience Exception";
$category               = "Complience Exception";
$total_sub_sheets       = 1;

$default_open_sub_sheet = 0; // 0 means first indexed sub sheets.
$excel_name             = 'Complience Exception Report';
$sheet_data             = array();

$report_for = '';

//filter
if (isset($_GET['filter']) && $_GET['filter'] != '') {
    $filter_array = json_decode($_GET['filter'], true);
    $client = isset($filter_array['client'])? $filter_array['client']:0;
    $broker_id = isset($filter_array['broker']) ? $filter_array['broker'] : 0;
    $beginning_date = isset($filter_array['beginning_date']) && !empty($filter_array['beginning_date']) ? date('Y-m-d', strtotime($instance->re_db_input($filter_array['beginning_date']))) : '';
    $ending_date = isset($filter_array['ending_date']) && !empty($filter_array['ending_date']) ? date('Y-m-d', strtotime($instance->re_db_input($filter_array['ending_date']))) : '';
    
}

function get_broker_name_only($brokerA)
{
    return $brokerA['first_name'] . ' ' . $brokerA['last_name'];
}

$queried_brokers = isset($broker_id) && $broker_id != 0 ? implode(",", array_map("get_broker_name_only", array_filter($get_brokers, function ($brokerA) use ($broker_id) {
    return $brokerA['id'] == $broker_id ? true : false;
}))) : 'All Brokers';

$trade_dates= ', Trade Dates: '.date('m/d/Y',strtotime($beginning_date)) ." thru ". date('m/d/Y',strtotime($ending_date));


$client_name='All Clients';
if(isset($client) && $client != 0){
    $queried_client=$instance_client->select_client_master($client);
    $client_name=$queried_client['last_name'].", ".$queried_client['first_name'];
}


$list_data= $instance_import->complience_exception_report($beginning_date,$ending_date,$broker_id,$client);
//echo "<pre>"; print_r($list_data);  die;

    $heading = $excel_name = 'COMPLIENCE EXCEPTION REPORT';
    $is_recrod_found=false;
    
    $subheading2= 'Broker: '.$queried_brokers.', Client: '.$client_name. $trade_dates;

    $sheet_data = array( // Set sheet data.
        0=> // Excel sub sheet indexed.
        array(
            
            'A1'=>array(date("m/d/Y"),array('bold','center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('A1','A2'))),
            'B1'=>array($heading." \r\n ".$subheading2."  ",array('bold','center','color'=>array('000000'),'size'=>array(14),'font_name'=>array('Calibri'),'merge'=>array('B1','U2'))),
            'V1'=>array(' PAGE 1',array('bold','center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('V1','W2'))),
            
            'A3'=>array('',array('bold','center','color'=>array('000000'),'size'=>array(12),'font_name'=>array('Calibri'),'merge'=>array('A3','W3'))),

            'B4'=>array(strtoupper('TRADE DATE'),array('bold','left','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
            'C4'=>array(strtoupper("SCAN DATE") ,array('bold','left','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
            'D4'=>array(strtoupper("EXCEPTION"),array('bold','left','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('D4','G4'))),

            'H4'=>array(strtoupper("STATUS") ,array('bold','left','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
            'I4'=>array(strtoupper("TRADE #") ,array('bold','left','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
            'J4'=>array(strtoupper("CLIENT"),array('bold','left','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('J4','L4'))),
            'M4'=>array(strtoupper("BROKER"),array('bold','left','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('M4','O4'))),
            'P4'=>array(strtoupper("MESSAGE"),array('bold','left','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('P4','W4'))),
          
        )
    );
    $i = 5;

    if($list_data != array()) {
        $last_record=0;
        $is_recrod_found=false;
        $total_amount=0.00;
        $total_comm_rec= 0.00;
        foreach($list_data as $exception) {

            $exception['trade_date']=(isset($exception['trade_date']) && $exception['trade_date'] != '0000-00-00')? date('m/d/Y',strtotime($exception['trade_date'])) :'--' ;

            $exception['scan_date']=(isset($exception['scan_date']) && $exception['scan_date'] != '0000-00-00')? date('m/d/Y',strtotime($exception['scan_date'])) :'--' ;

            $exception['message']=isset($exception['message'])? str_replace('<br>', ',  ',$exception['message'])  :'' ;


            $sheet_data[0]['B'.$i] = array($exception['trade_date'],array('left','bold','size'=>array(8),'color'=>array('000000')));
            $sheet_data[0]['C'.$i] = array($exception['scan_date'],array('left','bold','size'=>array(8),'color'=>array('000000')));
            $sheet_data[0]['D'.$i] = array($exception['error_message'], array('left','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('D'.$i,'G'.$i)));
            $sheet_data[0]['H'.$i] = array('FAIL',array('left','bold','size'=>array(8),'color'=>array('000000')));
            $sheet_data[0]['I'.$i] = array('',array('left','bold','size'=>array(8),'color'=>array('000000')));
            $sheet_data[0]['J'.$i] = array($exception['client'], array('left','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('J'.$i,'L'.$i)));
            $sheet_data[0]['M'.$i] = array($exception['rep_name'], array('left','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('M'.$i,'O'.$i)));
            $sheet_data[0]['P'.$i] = array($exception['message'], array('left','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('P'.$i,'W'.$i)));
          
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



