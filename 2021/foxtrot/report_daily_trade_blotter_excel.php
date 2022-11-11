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
$img = '<img src="upload/logo/'.$system_logo.'" height="25px" />';
$get_company_name = $instance->get_company_name();
$system_company_name = isset($get_company_name['company_name'])?$instance->re_db_input($get_company_name['company_name']):'';

$creator                = "Foxtrot User";
$last_modified_by       = "Foxtrot User";
// $title                  = "Production by  Category";
$subject                = "Daily Trade Blotter";
$description            = "Daily Trade Blotter";
$keywords               = "Daily Trade Blotter";
$category               = "Daily Trade Blotter";
$total_sub_sheets       = 1;

$default_open_sub_sheet = 0; // 0 means first indexed sub sheets.
$excel_name             = 'Client Review Report';
$sheet_data             = array();

$report_for = '';

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

    

    $heading = $excel_name = 'DAILY TRADE BLOTTER REPORT';
    $is_recrod_found=false;
    
    $subheading2= 'Company: '.$company_name.',Branch: '.$branch_name.'Broker: '. $queried_brokers;

    $sheet_data = array( // Set sheet data.
        0=> // Excel sub sheet indexed.
        array(
            
            'A1'=>array(date("m/d/Y"),array('bold','center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('A1','A2'))),
            'B1'=>array($heading." \r\n ".$subheading2."  ",array('bold','center','color'=>array('000000'),'size'=>array(14),'font_name'=>array('Calibri'),'merge'=>array('B1','W2'))),
            'X1'=>array(' PAGE 1',array('bold','center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('X1','Y2'))),
            
            'A3'=>array('',array('bold','center','color'=>array('000000'),'size'=>array(12),'font_name'=>array('Calibri'),'merge'=>array('A3','Y3'))),

            'B4'=>array(strtoupper('TRADE#'),array('bold','left','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
            'C4'=>array(strtoupper("BRANCH") ,array('bold','left','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
            'D4'=>array(strtoupper("DATE") ,array('bold','left','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
            'E4'=>array(strtoupper("CHECK DATE") ,array('bold','left','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
            'F4'=>array(strtoupper("CLIENT"),array('bold','left','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('F4','H4'))),
            'I4'=>array(strtoupper("BROKER"),array('bold','left','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('I4','K4'))),
            'L4'=>array(strtoupper("SPONSOR"),array('bold','left','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('L4','N4'))),
            'O4'=>array(strtoupper("PRODUCT"),array('bold','left','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('O4','Q4'))),
            'R4'=>array(strtoupper("AMOUNT") ,array('bold','left','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
            'S4'=>array(strtoupper("COMM.REC") ,array('bold','left','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
            'T4'=>array(strtoupper("DATE REC") ,array('bold','left','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
            'U4'=>array(strtoupper("DATE PAID") ,array('bold','left','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
            'V4'=>array(strtoupper("CREATED BY"),array('bold','left','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('V4','X4'))),
            'Y4'=>array(strtoupper("CREATED DATE") ,array('bold','left','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
        )
    );
    $i = 5;

    if($trade_data != array()) {
        $last_record=0;
        $is_recrod_found=false;
        $total_amount=0.00;
        $total_comm_rec= 0.00;
        foreach($trade_data as $trade) {

            $trade_date=date('m/d/Y',strtotime($trade['trade_date']));
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
            $client_name=$trade['client_lastname'].", ".$trade['client_firstname'];
            $broker_name =$trade['broker_last_name'].", ".$trade['broker_firstname'];
            $commission_rec_date=date('m/d/Y',strtotime($trade['commission_received_date']));
            $created_date=date('m/d/Y h:i:s',strtotime($trade['created_time']));

            $sheet_data[0]['B'.$i] = array($trade['id'],array('left','bold','size'=>array(8),'color'=>array('000000')));
            $sheet_data[0]['C'.$i] = array($trade['branch'],array('left','bold','size'=>array(8),'color'=>array('000000')));
            $sheet_data[0]['D'.$i] = array($trade_date,array('left','bold','size'=>array(8),'color'=>array('000000')));
            $sheet_data[0]['E'.$i] = array($trade['check_date'],array('left','bold','size'=>array(8),'color'=>array('000000')));
            $sheet_data[0]['F'.$i] = array($client_name, array('left','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('F'.$i,'H'.$i)));
            $sheet_data[0]['I'.$i] = array($broker_name, array('left','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('I'.$i,'K'.$i)));
            $sheet_data[0]['L'.$i] = array($trade['sponsor_name'], array('left','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('L'.$i,'N'.$i)));
            $sheet_data[0]['O'.$i] = array($trade['product_name'], array('left','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('O'.$i,'Q'.$i)));
            $sheet_data[0]['R'.$i] = array($trade['invest_amount'],array('left','bold','size'=>array(8),'color'=>array('000000')));
            $sheet_data[0]['S'.$i] = array($trade['commission_received'],array('left','bold','size'=>array(8),'color'=>array('000000')));
            $sheet_data[0]['T'.$i] = array($commission_rec_date,array('left','bold','size'=>array(8),'color'=>array('000000')));
            $sheet_data[0]['U'.$i] = array($trade['date_paid'],array('left','bold','size'=>array(8),'color'=>array('000000')));
            $sheet_data[0]['V'.$i] = array($trade['created_by'], array('left','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('V'.$i,'X'.$i)));
            $sheet_data[0]['Y'.$i] = array($created_date,array('left','bold','size'=>array(8),'color'=>array('000000')));

            $i= $i+1;
            $total_amount += $trade['invest_amount'];
            $total_comm_rec += $trade['commission_received'];
        }
        
        $i=$i+1;
        $sheet_data[0]['B'.$i] = array(' ', array('left','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('B'.$i,'P'.$i)));
        $sheet_data[0]['Q'.$i] = array('TOTAL:',array('left','bold','size'=>array(8),'color'=>array('000000')));
        $sheet_data[0]['R'.$i] = array(number_format($total_amount,2),array('left','bold','size'=>array(8),'color'=>array('000000')));
        $sheet_data[0]['S'.$i] = array(number_format($total_comm_rec,2),array('left','bold','size'=>array(8),'color'=>array('000000')));
        $sheet_data[0]['T'.$i] = array(' ', array('left','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('T'.$i,'Y'.$i)));

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



