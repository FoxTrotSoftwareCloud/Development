<?php
    require_once("include/config.php");
    require_once(DIR_FS."islogin.php");
    
    $instance = new transaction();

    //DEFAULT PDF DATA:
    $get_logo = $instance->get_system_logo();
    $system_logo = isset($get_logo['logo'])?$instance->re_db_input($get_logo['logo']):'';
    $get_company_name = $instance->get_company_name();
    $system_company_name = isset($get_company_name['company_name'])?$instance->re_db_input($get_company_name['company_name']):'';
    $image_path=SITE_URL."upload/logo/".$system_logo;
    
    $instance_payroll = new payroll();
    $filter_array = array();
    $get_reconci_data = array();
    
    //filter payroll reconciliation report
    if(isset($_GET['filter']) && $_GET['filter'] != '')
    {
        $filter_array = json_decode($_GET['filter'],true);
        $product_category = isset($filter_array['product_category'])?$filter_array['product_category']:0;
        // 11/23/21 Payroll ID passed instead of 'payroll_date' from the form submit
        $payroll_id = isset($filter_array['payroll_id'])?$filter_array['payroll_id']:'';
        $get_payroll_upload = $instance_payroll->get_payroll_uploads($payroll_id);
        $payroll_date = date('m/d/Y', strtotime($get_payroll_upload['payroll_date']));

        $get_reconci_data = $instance_payroll->get_reconciliation_report_data($product_category,$payroll_id);
    }
    
    $creator                = "Foxtrot User";
    $last_modified_by       = "Foxtrot User";
    $title                  = "Foxtrot Payroll Reconciliation Report Excel";
    $subject                = "Foxtrot Payroll Reconciliation Report";
    $description            = "Foxtrot Payroll Reconciliation Report. Generated on : ".date('Y-m-d h:i:s');
    $keywords               = "Foxtrot Payroll Reconciliation report office 2007";
    $category               = "Foxtrot Payroll Reconciliation Report.";
    $total_sub_sheets       = 1;
    $sub_sheet_title_array  = array("Foxtrot Payroll Reconciliation");
    $default_open_sub_sheet = 0; // 0 means first indexed sub sheets.
    $excel_name             = 'Foxtrot Payroll Reconciliation Report';
    $sheet_data             = array();
    $i=6;
        
            
    // Set output excel array.
    /* Headers. */
    $sheet_data = array( // Set sheet data.
        0=> // Excel sub sheet indexed.
        array(
            
            'A1'=>array('LOGO',array('bold','center','color'=>array('000000'),'size'=>array(16),'font_name'=>array('Calibri'),'merge'=>array('A1','B2'))),
            'G1'=>array($system_company_name,array('bold','center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('G1','I2'))),
            
            'A3'=>array('PAYROLL BATCH REPORT',array('bold','center','color'=>array('000000'),'size'=>array(14),'font_name'=>array('Calibri'),'merge'=>array('A3','I3'))),
            'A4'=>array('ALL BATCH GROUPS',array('bold','center','color'=>array('000000'),'size'=>array(12),'font_name'=>array('Calibri'),'merge'=>array('A4','I4'))),
            'A5'=>array($payroll_date,array('bold','center','color'=>array('000000'),'size'=>array(12),'font_name'=>array('Calibri'),'merge'=>array('A5','I5'))),
            'A6'=>array('BATCH#',array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
            'B6'=>array('BATCH DATE',array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
            'C6'=>array('STATEMENT \ DESCRIPTION',array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
            'D6'=>array('TRADE COUNT',array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
            'E6'=>array('GROSS COMMISION',array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
            'F6'=>array('HOLD COMMISION',array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
            'G6'=>array('TOTAL COMMISSION',array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
            'H6'=>array('CHECK AMOUNT',array('bold','center','color'=>array('000000'),'size'=>array(10),'background'=>array('f1f1f1'),'font_name'=>array('Calibri'))),
            'I6'=>array('DIFFERENCE',array('bold','center','color'=>array('000000'),'size'=>array(10),'background'=>array('f1f1f1'),'font_name'=>array('Calibri'))),
        )
    );
            
    if(is_array($get_reconci_data) && count($get_reconci_data)>0)
    {
        $c = $i;
        $report_trade_count_total = 0;
        $report_gross_commission_total = 0;
        $report_hold_commission_total = 0;
        $report_total_commission_total = 0;
        $report_check_amount_total = 0;
        $report_difference_total = 0;
        foreach($get_reconci_data as $recon_key=>$recon_data)
        {
            $i++;
            //$total_records = $total_records+1;
            $sheet_data[0]['A'.$i] = array($instance->re_db_output($recon_key),array('bold','left','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('A'.$i,'I'.$i)));
            
            $category_trade_count = 0;
            $category_gross_commission = 0;
            $category_hold_commission = 0;
            $category_total_commission = 0;
            $category_check_amount = 0;
            $category_difference = 0;
            foreach($recon_data as $recon_sub_key=>$recon_sub_data)
            {
                $i++;
                $difference = $recon_sub_data['batch_check_amount']-$recon_sub_data['total_commission'];
                $category_trade_count = $category_trade_count+$recon_sub_data['trade_count'];
                $category_gross_commission = $category_gross_commission+$recon_sub_data['gross_commission'];
                $category_hold_commission = $category_hold_commission+$recon_sub_data['total_hold_commission'];
                $category_total_commission = $category_total_commission+$recon_sub_data['total_commission'];
                $category_check_amount = $category_check_amount+$recon_sub_data['batch_check_amount'];
                $category_difference = $category_difference+$difference;
                
                $sheet_data[0]['A'.$i] = array($instance->re_db_output($recon_sub_data['batch_number']),array('center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
                $sheet_data[0]['B'.$i] = array($instance->re_db_output(date('m/d/Y',strtotime($recon_sub_data['batch_date']))),array('center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
                $sheet_data[0]['C'.$i] = array($instance->re_db_output($recon_sub_data['batch_description']),array('right','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
                $sheet_data[0]['D'.$i] = array($instance->re_db_output($recon_sub_data['trade_count']),array('right','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
                $sheet_data[0]['E'.$i] = array($instance->re_db_output('$'.number_format($recon_sub_data['gross_commission'],2)),array('right','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
                $sheet_data[0]['F'.$i] = array($instance->re_db_output('$'.number_format($recon_sub_data['total_hold_commission'],2)),array('right','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
                $sheet_data[0]['G'.$i] = array($instance->re_db_output('$'.number_format($recon_sub_data['total_commission'],2)),array('right','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
                $sheet_data[0]['H'.$i] = array($instance->re_db_output('$'.number_format($recon_sub_data['batch_check_amount'],2)),array('right','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
                $sheet_data[0]['I'.$i] = array($instance->re_db_output('$'.number_format($difference,2)),array('right','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
            }$i++;
            $report_trade_count_total = $report_trade_count_total+$category_trade_count;
            $report_gross_commission_total = $report_gross_commission_total+$category_gross_commission;
            $report_hold_commission_total = $report_hold_commission_total+$category_hold_commission;
            $report_total_commission_total = $report_total_commission_total+$category_total_commission;
            $report_check_amount_total = $report_check_amount_total+$category_check_amount;
            $report_difference_total = $report_difference_total+$category_difference;
            
            $sheet_data[0]['A'.$i] = array('* '.strtoupper($recon_key).' SUBTOTAL *',array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('A'.$i,'C'.$i)));
            $sheet_data[0]['D'.$i] = array($instance->re_db_output($category_trade_count),array('bold','right','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri')));
            $sheet_data[0]['E'.$i] = array($instance->re_db_output('$'.number_format($category_gross_commission,2)),array('bold','right','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri')));
            $sheet_data[0]['F'.$i] = array($instance->re_db_output('$'.number_format($category_hold_commission,2)),array('bold','right','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri')));
            $sheet_data[0]['G'.$i] = array($instance->re_db_output('$'.number_format($category_total_commission,2)),array('bold','right','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri')));
            $sheet_data[0]['H'.$i] = array($instance->re_db_output('$'.number_format($category_check_amount,2)),array('bold','right','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri')));
            $sheet_data[0]['I'.$i] = array($instance->re_db_output('$'.number_format($category_difference,2)),array('bold','right','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri')));
        }
        $i++;
        $sheet_data[0]['A'.$i] = array('REPORT TOTAL: ',array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('A'.$i,'C'.$i)));
        $sheet_data[0]['D'.$i] = array($instance->re_db_output($report_trade_count_total),array('bold','right','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri')));
        $sheet_data[0]['E'.$i] = array($instance->re_db_output('$'.number_format($report_gross_commission_total,2)),array('bold','right','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri')));
        $sheet_data[0]['F'.$i] = array($instance->re_db_output('$'.number_format($report_hold_commission_total,2)),array('bold','right','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri')));
        $sheet_data[0]['G'.$i] = array($instance->re_db_output('$'.number_format($report_total_commission_total,2)),array('bold','right','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri')));
        $sheet_data[0]['H'.$i] = array($instance->re_db_output('$'.number_format($report_check_amount_total,2)),array('bold','right','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri')));
        $sheet_data[0]['I'.$i] = array($instance->re_db_output('$'.number_format($report_difference_total,2)),array('bold','right','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri')));
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