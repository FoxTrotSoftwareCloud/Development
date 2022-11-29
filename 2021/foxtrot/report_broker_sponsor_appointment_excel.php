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

$creator                = "Foxtrot User";
$last_modified_by       = "Foxtrot User";

$subject                = "BROKER SPONSOR APPOINTMENTS REPORT";
$description            = "BROKER SPONSOR APPOINTMENTS REPORT";
$keywords               = "BROKER SPONSOR APPOINTMENTS REPORT";
$category               = "BROKER SPONSOR APPOINTMENTS REPORT";
$total_sub_sheets       = 1;

$default_open_sub_sheet = 0; // 0 means first indexed sub sheets.
$excel_name             = 'Broker Sponsor Appointments Report';
$sheet_data             = array();

$report_for = '';

//filter report
if(isset($_GET['filter']) && $_GET['filter'] != '')
{
    $filter_array = json_decode($_GET['filter'], true);
    $sponsor_id = isset($filter_array['sponsor'])?$filter_array['sponsor']:'';
    $broker_id = isset($filter_array['broker']) ? $filter_array['broker'] : 0;
    $group_by = $filter_array['allias_groupby'];
}

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
    //echo "<pre>"; print_r($list_data);die;

    $heading = $excel_name = 'BROKER SPONSOR APPOINTMENTS';    
    $subheading2= 'Broker: '. $queried_brokers.', Sponsor: '. $sponsor_name;

    $broker_sponsor_heading= ($group_by=='broker')?"COMPANY": "BROKER";
    $sheet_data = array( // Set sheet data.
        0=> // Excel sub sheet indexed.
        array(
            
            'A1'=>array(date("m/d/Y"),array('bold','center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('A1','A3'))),
            'B1'=>array($heading." \r\n ".$subheading2."  ",array('bold','center','color'=>array('000000'),'size'=>array(14),'font_name'=>array('Calibri'),'merge'=>array('B1','J3'))),
            'K1'=>array(' PAGE 1',array('bold','center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('K1','L3'))),
            
            'A4'=>array('',array('bold','center','color'=>array('000000'),'size'=>array(12),'font_name'=>array('Calibri'),'merge'=>array('A4','L4'))),

            'B5'=>array(strtoupper("APPT #"),array('bold','left','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('B5','D5'))),
            'E5'=>array(strtoupper("DATE") ,array('bold','left','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),    
            'F5'=>array($broker_sponsor_heading,array('bold','left','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('F5','I5'))),
            'J5'=>array(strtoupper("STATE") ,array('bold','left','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
            'K5'=>array(strtoupper("TERM DATE") ,array('bold','left','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
            'L5'=>array(strtoupper("REP #") ,array('bold','left','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),

            'A6'=>array(" " ,array('bold','left','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
            'A6'=>array('',array('bold','center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('A6','L6'))),
        )
    );
    $i = 7;
    

    if($list_data != array()) {
        $last_record=0;
        $is_recrod_found=false;
        $total_amount=0.00;
        $total_comm_rec= 0.00;
        foreach($list_data as $row) {

            // $row['employ_number']=isset($row['employ_number'])?$row['employ_number']:'';
            // $row['expiration'] = (isset($row['expiration']) && $row['expiration']!= '0000-00-00')? date('m/d/Y',strtotime($row['expiration'])):''; 
            // $row['employ_state']=isset($row['employ_state'])?$row['employ_state']:'';
            // $row['date_verified'] = (isset($row['date_verified']) && $row['date_verified']!= '0000-00-00')? date('m/d/Y',strtotime($row['date_verified'])):''; 
            // $row['open_date'] = (isset($row['open_date']) && $row['open_date']!= '0000-00-00')? date('m/d/Y',strtotime($row['open_date'])):''; 
            // $row['birth_date'] = (isset($row['birth_date']) && $row['birth_date']!= '0000-00-00')? date('m/d/Y',strtotime($row['birth_date'])):''; 
        
            $sheet_data[0]['B'.$i] = array($row['full_name'], array('bold','left','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('B'.$i,'D'.$i)));
           
            // $sheet_data[0]['D'.$i] = array($row['employ_number'],array('left','size'=>array(8),'color'=>array('000000')));
            // $sheet_data[0]['E'.$i] = array($row['expiration'],array('left','size'=>array(8),'color'=>array('000000')));
            // $sheet_data[0]['F'.$i] = array($row['employ_state'], array('left','color'=>array('000000'),'size'=>array(10)));
            // $sheet_data[0]['G'.$i] = array($row['date_verified'], array('left','color'=>array('000000'),'size'=>array(10)));
            // $sheet_data[0]['H'.$i] = array($row['open_date'], array('left','color'=>array('000000'),'size'=>array(10)));
            // $sheet_data[0]['I'.$i] = array($row['birth_date'], array('left','color'=>array('000000'),'size'=>array(10)));
            
            // $sheet_data[0]['M'.$i] = array($address, array('left','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('M'.$i,'O'.$i)));
            

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



