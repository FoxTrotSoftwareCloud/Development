<?php
    require_once("include/config.php");
    require_once(DIR_FS."islogin.php");
    //include 'include/PHPExcel/classes/PHPExcel.php';
    
    $instance = new import();

    //DEFAULT EXCEL DATA:
    $get_logo = $instance->get_system_logo();
    $system_logo = isset($get_logo['logo'])?$instance->re_db_input($get_logo['logo']):'';
    $get_company_name = $instance->get_company_name();
    $system_company_name = isset($get_company_name['company_name'])?$instance->re_db_input($get_company_name['company_name']):'';
    
    $return_solved_exception = array();
    $file_id = isset($_GET['id'])?$instance->re_db_input($_GET['id']):0;
    $get_file_data = $instance->select_user_files($file_id);
    $get_total_commission = $instance->get_total_commission_amount($file_id);
    $total_commission_amount = $get_total_commission;
    $get_file_type = $instance->get_file_type($file_id);
    $return_solved_exception = $instance->select_solved_exception_data($file_id);
    $batch_id = $instance->get_file_batch($file_id);
    
    $total_investments = 0;
    $total_commissions = 0;
    $total_records=0;
        //echo '<pre>';print_r($return);exit;
        
        
    $creator                = "Foxtrot User";
    $last_modified_by       = "Foxtrot User";
    $title                  = "Foxtrot Review Processed Data Excel";
    $subject                = "Foxtrot Review Processed Data";
    $description            = "Foxtrot Review Processed Data. Generated on : ".date('Y-m-d h:i:s');
    $keywords               = "Foxtrot Review Processed Data office 2007";
    $category               = "Foxtrot Review Processed Data.";
    $total_sub_sheets       = 1;
    $sub_sheet_title_array  = array("Review Processed Data");
    $default_open_sub_sheet = 0; // 0 means first indexed sub sheets.
    $excel_name             = 'Foxtrot Review Processed Data';
    $sheet_data             = array();
    $i=5;
    
    //$objPHPExcel = new PHPExcel();
    
    if(isset($get_file_type) && $get_file_type == '1')
    {
        $sheet_data = array( // Set sheet data.
            0=> // Excel sub sheet indexed.
            array(
                'A1'=>array('LOGO',array('bold','center','color'=>array('000000'),'size'=>array(16),'font_name'=>array('Calibri'),'merge'=>array('A1','A2'))),
                'B1'=>array('REVIEW PROCESSED DATA',array('bold','center','color'=>array('000000'),'size'=>array(14),'font_name'=>array('Calibri'),'merge'=>array('B1','E2'))),
                'F1'=>array($system_company_name,array('bold','center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('F1','F2'))),
                
                'A3'=>array('File: '.$get_file_data['file_name'],array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
                'B3'=>array('Source: '.$get_file_data['source'],array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
                'C3'=>array('File Type: '.$get_file_data['file_type'],array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('C3','D3'))),
                'E3'=>array('Date: '.date('m/d/Y',strtotime($get_file_data['last_processed_date'])),array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
                'F3'=>array('',array('bold','left','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
                
                'A4'=>array('DATE',array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
                'B4'=>array('REP#',array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
                'C4'=>array('REP NAME',array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
                'D4'=>array('ACCOUNT#',array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
                'E4'=>array('CLIENT NAME',array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
                'F4'=>array('CLIENT ADDRESS',array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri')))
            )
        );
    }
    else if(isset($get_file_type) && $get_file_type == '2')
    {
        $sheet_data = array( // Set sheet data.
            0=> // Excel sub sheet indexed.
            array(
                'A1'=>array('LOGO',array('bold','center','color'=>array('000000'),'size'=>array(16),'font_name'=>array('Calibri'),'merge'=>array('A1','A2'))),
                'B1'=>array('REVIEW PROCESSED DATA',array('bold','center','color'=>array('000000'),'size'=>array(14),'font_name'=>array('Calibri'),'merge'=>array('B1','E2'))),
                'F1'=>array($system_company_name,array('bold','center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('F1','H2'))),
                
                'A3'=>array('File: '.$get_file_data['file_name'],array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
                'B3'=>array('Source: '.$get_file_data['source'],array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
                'C3'=>array('File Type: '.$get_file_data['file_type'],array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
                'D3'=>array('Date: '.date('m/d/Y',strtotime($get_file_data['last_processed_date'])),array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
                'E3'=>array('Batch: '.$batch_id,array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
                'F3'=>array('Amount: $'.number_format($total_commission_amount,2),array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('F3','H3'))),
                
                'A4'=>array('DATE',array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
                'B4'=>array('REP#',array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
                'C4'=>array('REP NAME',array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
                'D4'=>array('ACCOUNT#',array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
                'E4'=>array('CLIENT NAME',array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
                'F4'=>array('CUSIP',array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
                'G4'=>array('PRINCIPAL',array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
                'H4'=>array('COMMISSION',array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri')))
            )
        );
    }
    
            
    if($return_solved_exception != array())
    {
        foreach($return_solved_exception as $process_key=>$process_val)
        {
            $total_records = $total_records+1;
                    
            $sheet_data[0]['A'.$i] = array($instance->re_db_output(date('m/d/Y',strtotime($process_val['date']))),array('center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
            $sheet_data[0]['B'.$i] = array($instance->re_db_output($process_val['rep']),array('center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
            $sheet_data[0]['C'.$i] = array($instance->re_db_output($process_val['rep_name']),array('center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
            $sheet_data[0]['D'.$i] = array($instance->re_db_output($process_val['account_no']),array('center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
            $sheet_data[0]['E'.$i] = array($instance->re_db_output($process_val['client']),array('center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
            if(isset($get_file_type) && $get_file_type == '1')
            {
                $get_client_data = $instance->get_client_data($file_id,$process_val['temp_data_id']);
                $sheet_data[0]['F'.$i] = array($instance->re_db_output($get_client_data[0]['client_address']),array('center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
            }     
            else if(isset($get_file_type) && $get_file_type == '2')
            {
                $total_investments = $total_investments+$process_val['principal'];
                $total_commissions = $total_commissions+$process_val['commission'];
                
                $sheet_data[0]['F'.$i] = array($instance->re_db_output($process_val['cusip']),array('center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
                if($process_val['principal'] > 0){ 
                    $sheet_data[0]['G'.$i] = array('$'.number_format($process_val['principal'],2),array('right','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
                }else{
                    $sheet_data[0]['G'.$i] = array('$0',array('center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
                }
                if($process_val['commission'] > 0){ 
                    $sheet_data[0]['H'.$i] = array('$'.number_format($process_val['commission'],2),array('right','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
                }else{
                    $sheet_data[0]['H'.$i] = array('$0',array('center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
                }
            }
            $i++;      
        }
        if(isset($get_file_type) && $get_file_type == '1')
        {
            $sheet_data[0]['F'.$i] = array('Total Records: '.$total_records,array('bold','right','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
        }
        else if(isset($get_file_type) && $get_file_type == '2')
        {
            $sheet_data[0]['F'.$i] = array('Total Records: '.$total_records,array('bold','right','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
            $sheet_data[0]['G'.$i] = array('$'.number_format($total_investments,2),array('bold','right','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
            $sheet_data[0]['H'.$i] = array('$'.number_format($total_commissions,2),array('bold','right','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
        } 
    }
    else{
        if(isset($get_file_type) && $get_file_type == '1')
        {
            $sheet_data[0]['A'.$i] = array($instance->re_db_output('No record found.'),array('center','size'=>array(10),'color'=>array('000000'),'merge'=>array('A'.$i,'F'.$i)));
        }
        else
        {
            $sheet_data[0]['A'.$i] = array($instance->re_db_output('No record found.'),array('center','size'=>array(10),'color'=>array('000000'),'merge'=>array('A'.$i,'H'.$i)));
        }
        $i++;
    }
        
    // Create Excel instance.
    $Excel = new Excel();
    
    $formPost = $Excel->generate(
        array(
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
        )
    );
    
        
    
    
?>