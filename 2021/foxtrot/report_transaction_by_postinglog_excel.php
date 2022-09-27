<?php
    require_once("include/config.php");
    require_once(DIR_FS."islogin.php");
    
    $instance = new transaction();

    $return = array();
    $filter_array = array();
    $product_category = '';
    $product_category_name = '';
    $beginning_date = '';
    $ending_date = '';
    $batch = 0;
    $total_records=0;
    
    //DEFAULT PDF DATA:
    $get_logo = $instance->get_system_logo();
    $system_logo = isset($get_logo['logo'])?$instance->re_db_input($get_logo['logo']):'';
    $get_company_name = $instance->get_company_name();
    $system_company_name = isset($get_company_name['company_name'])?$instance->re_db_input($get_company_name['company_name']):'';
    $image_path=SITE_URL."upload/logo/".$system_logo;

    if(isset($_GET['filter']) && $_GET['filter'] != '')
    {
        $filter_array = json_decode($_GET['filter'],true);//echo '<pre>';print_r($filter_array);exit;
        $product_category = isset($filter_array['product_category'])?$filter_array['product_category']:0;
        $company = isset($filter_array['company'])?$filter_array['company']:0;
        $batch = isset($filter_array['batch'])?$filter_array['batch']:0;
        $beginning_date = isset($filter_array['beginning_date'])?$filter_array['beginning_date']:'';
        $ending_date = isset($filter_array['ending_date'])?$filter_array['ending_date']:'';
        $sort_by = isset($filter_array['sort_by'])?$filter_array['sort_by']:'';
        
        
        
    }
    $return = $instance->select_data_commission_posting_log($product_category,$company,$batch,$beginning_date,$ending_date,$sort_by,1);
    $index= array_key_first($get_trans_data);
    $batch_desc = isset($get_trans_data[$index][0]['batch_desc'])? $instance->re_db_input($get_trans_data[$index][0]['batch_desc']):'';
    if($batch>0)
    {
        
            $index= array_key_first($return);
            $batch_desc = isset($return[$index][0]['batch_desc'])? $instance->re_db_input($return[$index][0]['batch_desc']):'';
        $batch_name = 'Batch #'.$batch;
    }
    else
    {
        $batch_desc = 'ALL BATCHES';
        $batch_name = '';
    }
    $heading_title='TRANSACTION BY BATCH REPORT';
    if($filter_array['report_for'] == 1)
        $heading_title="COMMISSION POSTING LOG";
    
    $total_amount_invested = 0;
    $total_commission_received = 0;
    $total_charges = 0;
        //echo '<pre>';print_r($return);exit;
        
        
    $creator                = "Foxtrot User";
    $last_modified_by       = "Foxtrot User";
    $title                  = "Foxtrot Transaction Report Excel";
    $subject                = "Foxtrot Transaction Report";
    $description            = "Foxtrot Transaction Report. Generated on : ".date('Y-m-d h:i:s');
    $keywords               = "Foxtrot Transaction report office 2007";
    $category               = "Foxtrot Transaction Report.";
    $total_sub_sheets       = 1;
    $sub_sheet_title_array  = array("Foxtrot Transaction");
    $default_open_sub_sheet = 0; // 0 means first indexed sub sheets.
    $excel_name             = 'Foxtrot Transaction Report';
    $sheet_data             = array();
    $i=4;
        
            
    // Set output excel array.
    /* Headers. */
    $sheet_data = array( // Set sheet data.
        0=> // Excel sub sheet indexed.
        array(
            
            'A1'=>array('LOGO',array('bold','center','color'=>array('000000'),'size'=>array(16),'font_name'=>array('Calibri'),'merge'=>array('A1','A2'))),
            'B1'=>array($heading_title.' : '.$batch_desc,array('bold','center','color'=>array('000000'),'size'=>array(14),'font_name'=>array('Calibri'),'merge'=>array('B1','F2'))),
            'G1'=>array($system_company_name,array('bold','center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('G1','H2'))),
            
            'A3'=>array($batch_name,array('bold','center','color'=>array('000000'),'size'=>array(12),'font_name'=>array('Calibri'),'merge'=>array('A3','H3'))),
            'A4'=>array('TRADE#',array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
            'B4'=>array('BATCH#',array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
            'C4'=>array('CLIENT ACCOUNT #',array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
            'D4'=>array('CLIENT',array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
            'E4'=>array('TRADE DATE',array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
            'F4'=>array('DATE RECEIVED',array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
            'G4'=>array('AMOUNT INVESTED',array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
            'H4'=>array('CHARGE',array('bold','center','color'=>array('000000'),'size'=>array(10),'background'=>array('f1f1f1'),'font_name'=>array('Calibri'))),
            'I4'=>array('COMMISSION RECEIVED',array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
            
            
        )
    );
            
        if(is_array($return) && count($return)>0)
        {
             foreach($return as $trans_main_key=>$trans_main)
            {
                   $i++;
                 /*$html.='<tr><td style="font-size:8px;font-weight:bold;text-align:left;" colspan="9">BATCH : '.$trans_main["batch_desc"].'</td></tr>';*/
                 $sheet_data[0]['A'.$i] = array('Batch: '.$trans_main["batch_desc"],array('bold','left','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('A'.$i,'H'.$i)));
                 foreach($trans_main["child"] as $trans_main_key=>$trans_main_data) {     
                        $sub_total_records=0;
                        $sub_total_amount_invested = 0;
                        $sub_total_commission_received = 0;
                        $sub_total_charges = 0;
                        $c = $i;
                        
                        $i++;
                        $broker_name = $trans_main_data[0]['broker_last_name'].', '.$trans_main_data[0]['broker_name'].' (#'.$trans_main_key.')';
                        $sheet_data[0]['A'.$i] = array('BROKER: '.($broker_name),array('bold','left','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('A'.$i,'H'.$i)));
                        foreach($trans_main_data as $key=>$val){
                            $i++;
                            $total_records = $total_records+1;
                            $sub_total_records = $sub_total_records+1;
                            $c = $i;
                            $trade_date='';
                            $commission_received_date='';
                            $total_amount_invested = ($total_amount_invested+$val['invest_amount']);
                            $total_commission_received = ($total_commission_received+$val['commission_received']);
                            $total_charges = ($total_charges+$val['charge_amount']);
                            
                            $sub_total_amount_invested = ($sub_total_amount_invested+$val['invest_amount']);
                            $sub_total_commission_received = ($sub_total_commission_received+$val['commission_received']);
                            $sub_total_charges = ($sub_total_charges+$val['charge_amount']);
                            if($val['trade_date'] != '0000-00-00'){ $trade_date = date('m/d/Y',strtotime($val['trade_date'])); }
                            if($val['commission_received_date'] != '0000-00-00'){ $commission_received_date = date('m/d/Y',strtotime($val['commission_received_date'])); }
                            
                                
                                $sheet_data[0]['A'.$i] = array($instance->re_db_output($val['id']),array('center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
                                $sheet_data[0]['B'.$i] = array($instance->re_db_output($val['batch']),array('center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
                                $sheet_data[0]['C'.$i] = array($instance->re_db_output($val['client_number']),array('center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
                                $sheet_data[0]['D'.$i] = array($instance->re_db_output($val['client_name']),array('center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
                                $sheet_data[0]['E'.$i] = array($instance->re_db_output($trade_date),array('center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
                                $sheet_data[0]['F'.$i] = array($instance->re_db_output($commission_received_date),array('center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
                                $sheet_data[0]['G'.$i] = array($instance->re_db_output('$'.number_format($val['invest_amount'],2)),array('right','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
                                $sheet_data[0]['H'.$i] = array($instance->re_db_output('$'.number_format($val['charge_amount'],2)),array('right','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
                                $sheet_data[0]['I'.$i] = array($instance->re_db_output('$'.number_format($val['commission_received'],2)),array('right','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
                        }
                        $i++;
                        $sheet_data[0]['A'.$i] = array('',array('bold','right','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('A'.$i,'B'.$i,'C'.$i)));
                        $sheet_data[0]['D'.$i] = array($broker_name.' TOTAL: ',array('bold','right','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('D'.$i,'F'.$i)));
                        $sheet_data[0]['G'.$i] = array($instance->re_db_output('$'.number_format($sub_total_amount_invested,2)),array('bold','right','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri')));
                        $sheet_data[0]['H'.$i] = array($instance->re_db_output('$'.number_format($sub_total_charges,2)),array('bold','right','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri')));
                        $sheet_data[0]['I'.$i] = array($instance->re_db_output('$'.number_format($sub_total_commission_received,2)),array('bold','right','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri')));
                    }
                }
            $i++;
            $sheet_data[0]['A'.$i] = array('Total Records: '.$total_records,array('bold','right','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('A'.$i,'B'.$i,'C'.$i,'C'.$i)));
            $sheet_data[0]['D'.$i] = array('*** REPORT TOTALS ***',array('bold','right','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('D'.$i,'F'.$i)));
            $sheet_data[0]['G'.$i] = array($instance->re_db_output('$'.number_format($total_amount_invested,2)),array('bold','right','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri')));
            $sheet_data[0]['H'.$i] = array($instance->re_db_output('$'.number_format($total_charges,2)),array('bold','right','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri')));
            $sheet_data[0]['I'.$i] = array($instance->re_db_output('$'.number_format($total_commission_received,2)),array('bold','right','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri')));
            
        }
        else
        {
            $i++;
            $sheet_data[0]['A'.$i] = array($instance->re_db_output('No record found.'),array('center','size'=>array(10),'color'=>array('000000'),'merge'=>array('A'.$i,'H'.$i)));
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