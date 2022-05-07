<?php
    require_once("include/config.php");
    require_once(DIR_FS."islogin.php");
    //DEFAULT REPORT DATA:
    $reportTitle = 'PAYROLL SUMMARY REPORT';
    $get_logo = $instance->get_system_logo();
    $system_logo = isset($get_logo['logo'])?$instance->re_db_input($get_logo['logo']):'';
    $get_company_name = $instance->get_company_name();
    $system_company_name = isset($get_company_name['company_name'])?$instance->re_db_input($get_company_name['company_name']):'';
    $image_path=SITE_URL."upload/logo/".$system_logo;
    
    $instance_payroll = new payroll();
    $filter_array = $reportData = $summaryData = array();
    
    //filter payroll reconciliation report
    if(isset($_GET['filter']) && $_GET['filter'] != '') {
        $filter_array = json_decode($_GET['filter'],true);
        $payroll_id = isset($filter_array['payroll_id'])?$filter_array['payroll_id']:'';
        $get_payroll_upload = $instance_payroll->get_payroll_uploads($payroll_id);
        $payroll_date = date('m/d/Y', strtotime($get_payroll_upload['payroll_date']));
        $output_type = isset($filter_array['output_type'])?$filter_array['output_type']:'';
        // 5/6/22 Company Filter
        $instance_company = new manage_company();
        $company = isset($filter_array['company'])?$filter_array['company']:0;
        $companyData = ($company==0) ? ["company_name"=>"All Companies"]: $instance_company->select_company_by_id($company);

        $summaryData = $instance_payroll->select_current_payroll($payroll_id, 1, $company);
        $reportData = $instance_payroll->get_payroll_summary_report_data($payroll_id, $company);
    }
    
    $creator                = "Foxtrot User";
    $last_modified_by       = "Foxtrot User";
    $title                  = "Foxtrot ".ucwords($reportTitle)." Excel";
    $subject                = "Foxtrot ".ucwords($reportTitle);
    $description            = "Foxtrot ".ucwords($reportTitle).". Generated on : ".date('Y-m-d h:i:s');
    $keywords               = "Foxtrot ".ucwords($reportTitle)." office 2007";
    $category               = "Foxtrot ".ucwords($reportTitle);
    $total_sub_sheets       = 1;
    $sub_sheet_title_array  = array("Foxtrot Payroll Summary");
    $default_open_sub_sheet = 0; // 0 means first indexed sub sheets.
    $excel_name             = 'Foxtrot '.ucwords(strtolower($reportTitle));
    $sheet_data             = array();
    $i=6;
            
    // Set output excel array.
    /* Headers. */
    $sheet_data = array( // Set sheet data.
        0=> // Excel sub sheet indexed.
        array(
            
            'A1'=>array('LOGO',array('bold','center','color'=>array('000000'),'size'=>array(16),'font_name'=>array('Calibri'),'merge'=>array('A1','B2'))),
            'D1'=>array($system_company_name,array('bold','right','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('D1','E1'))),
            
            'A3'=>array($reportTitle,array('bold','center','color'=>array('000000'),'size'=>array(14),'font_name'=>array('Calibri'),'merge'=>array('A3','E3'))),
            'A4'=>array($companyData['company_name'],array('bold','center','color'=>array('000000'),'size'=>array(12),'font_name'=>array('Calibri'),'merge'=>array('A4','E4'))),
            'A5'=>array($payroll_date,array('bold','center','color'=>array('000000'),'size'=>array(12),'font_name'=>array('Calibri'),'merge'=>array('A5','E5'))),
            'A6'=>array('PRODUCT CATEGORY',array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'),'borders'=>['bottom','medium',''])),
            'B6'=>array('TRADE COUNT',array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'),'borders'=>['bottom','medium',''])),
            'C6'=>array('GROSS COMMISSION',array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'),'borders'=>['bottom','medium',''])),
            'D6'=>array('% of GROSS',array('bold','right','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'),'borders'=>['bottom','medium',''])),
            'E6'=>array('NET COMMISSION',array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'),'borders'=>['bottom','medium','']))
        )
    );
            
    if(!empty($reportData)) {
        $c = $i;
        $report_trade_count_total = 0;
        $report_gross_commission_total = 0;
        $report_net_commission_total = 0;
        
        // Detail
        foreach($reportData as $dataKey=>$dataRow) {
            $pctOfGross = ($summaryData[0]['GROSS_COMMISSION']==0 ? 0 : round($dataRow['GROSS_COMMISSION']*100 /$summaryData[0]['GROSS_COMMISSION'], 0));

            $i++;
            $sheet_data[0]['A'.$i] = array($instance->re_db_output($dataRow['PRODUCT_CATEGORY']),array('normal','left','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri')));
            $sheet_data[0]['B'.$i] = array($instance->re_db_output(number_format($dataRow['TRADE_COUNT'],0)),array('normal','right','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri')));
            $sheet_data[0]['C'.$i] = array($instance->re_db_output($instance_payroll->payroll_accounting_format($dataRow['GROSS_COMMISSION'],2)),array('normal','right','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri')));
            $sheet_data[0]['D'.$i] = array($instance->re_db_output((string)$pctOfGross."%"),array('normal','right','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri')));
            $sheet_data[0]['E'.$i] = array($instance->re_db_output($instance_payroll->payroll_accounting_format($dataRow['NET_COMMISSION'],2)),array('normal','right','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri')));
   
            $report_trade_count_total += $dataRow['TRADE_COUNT'];
            $report_gross_commission_total += $dataRow['GROSS_COMMISSION'];
            $report_net_commission_total += $dataRow['NET_COMMISSION'];
        }
        // Detail Totals
        $i++;
        $sheet_data[0]['A'.$i] = array('* TRANSACTION TOTALS *',array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'),'borders'=>['top','medium','black']));
        $sheet_data[0]['B'.$i] = array($instance->re_db_output($report_trade_count_total),array('bold','right','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'),'borders'=>['top','medium','black']));
        $sheet_data[0]['C'.$i] = array($instance->re_db_output($instance_payroll->payroll_accounting_format($report_gross_commission_total,2)),array('bold','right','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'),'borders'=>['top','medium','black']));
        $sheet_data[0]['D'.$i] = array('100%',array('bold','right','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'),'borders'=>['top','medium','black']));
        $sheet_data[0]['E'.$i] = array($instance->re_db_output($instance_payroll->payroll_accounting_format($report_net_commission_total,2)),array('bold','right','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'),'borders'=>['top','medium','black']));

        $i++;
        $sheet_data[0]['D'.$i] = array('FINRA',array('normal','right','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri')));
        $sheet_data[0]['E'.$i] = array($instance->re_db_output($instance_payroll->payroll_accounting_format(-$summaryData[0]['FINRA_TOTAL'],2)),array('normal','right','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri')));
        $i++;
        $sheet_data[0]['D'.$i] = array('SIPC',array('normal','right','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri')));
        $sheet_data[0]['E'.$i] = array($instance->re_db_output($instance_payroll->payroll_accounting_format(-$summaryData[0]['SIPC_TOTAL'],2)),array('normal','right','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri')));
        $i++;
        $sheet_data[0]['D'.$i] = array('TAXABLE ADJUSTMENTS',array('normal','right','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri')));
        $sheet_data[0]['E'.$i] = array($instance->re_db_output($instance_payroll->payroll_accounting_format($summaryData[0]['TAXABLE_ADJUSTMENTS_TOTAL'],2)),array('normal','right','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri')));
        $i++;
        $sheet_data[0]['D'.$i] = array('NON-TAX ADJUSTMENTS',array('normal','right','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri')));
        $sheet_data[0]['E'.$i] = array($instance->re_db_output($instance_payroll->payroll_accounting_format($summaryData[0]['NON-TAXABLE_ADJUSTMENTS_TOTAL'],2)),array('normal','right','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri')));
        $i++;
        $sheet_data[0]['D'.$i] = array('OVERRIDES',array('normal','right','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri')));
        $sheet_data[0]['E'.$i] = array($instance->re_db_output($instance_payroll->payroll_accounting_format($summaryData[0]['OVERRIDES_PAID_TOTAL'],2)),array('normal','right','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri')));
        $i++;
        $sheet_data[0]['D'.$i] = array('PRIOR BALANCES',array('normal','right','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'),'border'=>array(array('bottom'),'linestyle'=>array('continuous'),'weight'=>array('double'))));
        $sheet_data[0]['E'.$i] = array($instance->re_db_output($instance_payroll->payroll_accounting_format($summaryData[0]['PRIOR_BALANCE_TOTAL'],2)),array('normal','right','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri')));
        $i++;
        $sheet_data[0]['D'.$i] = array('TOTAL PAYROLL',array('bold','right','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'),'borders'=>['top','double','red']));
        $sheet_data[0]['E'.$i] = array($instance->re_db_output($instance_payroll->payroll_accounting_format($summaryData[0]['TOTAL_PAYROLL'],2)),array('bold','right','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'),'borders'=>['top','double','red']));
        $i += 2;
        $sheet_data[0]['D'.$i] = array('TOTAL CHECKS',array('bold','right','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri')));
        $sheet_data[0]['E'.$i] = array($instance->re_db_output($instance_payroll->payroll_accounting_format($summaryData[0]['TOTAL_CHECKS'],2)),array('bold','right','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri')));
        $i++;
        $sheet_data[0]['D'.$i] = array('BALANCES CARRIED FORWARD',array('bold','right','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri')));
        $sheet_data[0]['E'.$i] = array($instance->re_db_output($instance_payroll->payroll_accounting_format($summaryData[0]['TOTAL_CARRIED_FORWARD'],2)),array('bold','right','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri')));
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