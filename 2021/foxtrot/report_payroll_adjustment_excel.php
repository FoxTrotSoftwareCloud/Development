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
    $get_adjustments_data = array();
    $company = 0;
    $payroll_date = '';
    $sort_by = '';
    $output_type = '';
    
    //filter payroll reconciliation report
    if(isset($_GET['filter']) && $_GET['filter'] != '')
    {
        $filter_array = json_decode($_GET['filter'],true);//echo '<pre>';print_r($filter_array);exit;
        $company = isset($filter_array['company'])?$filter_array['company']:0;
        $sort_by = isset($filter_array['sort_by'])?$filter_array['sort_by']:'';
        // 11/23/21 Payroll ID passed instead of 'payroll_date' from the form submit
        $payroll_id = isset($filter_array['payroll_id'])?$filter_array['payroll_id']:'';
        $get_payroll_upload = $instance_payroll->get_payroll_uploads($payroll_id);
        $payroll_date = date('m/d/Y', strtotime($get_payroll_upload['payroll_date']));
        $output_type = isset($filter_array['output_type'])?$filter_array['output_type']:'';
        
        $get_adjustments_data = $instance_payroll->get_adjustments_report_data($company,$payroll_id,$sort_by,$output_type);
    }
    if($company>0)
    {
        $company_name = isset($get_adjustments_data['company_name'])?$get_adjustments_data['company_name']:'';
    }
    else
    {
        $company_name = 'All Company';
    }
    if(isset($sort_by) && $sort_by == 1)
    {
        $sorted_by = 'Sorted by Rep Name';
    }
    else if(isset($sort_by) && $sort_by == 2)
    {
        $sorted_by = 'Sorted by Rep Number';
    }
    else if(isset($sort_by) && $sort_by == 3)
    {
        $sorted_by = 'Sorted by Category';
    }
    else if(isset($sort_by) && $sort_by == 4)
    {
        $sorted_by = 'Sorted by G/L Account';
    }
    else
    {
        $sorted_by='';
    }//print_r($sorted_by);exit;
    
    $creator                = "Foxtrot User";
    $last_modified_by       = "Foxtrot User";
    $title                  = "Foxtrot Payroll Adjustments Log Report Excel";
    $subject                = "Foxtrot Payroll Adjustments Log Report";
    $description            = "Foxtrot Payroll Adjustments Log Report. Generated on : ".date('Y-m-d h:i:s');
    $keywords               = "Foxtrot Payroll Adjustments Log Report office 2007";
    $category               = "Foxtrot Payroll Adjustments Log Report.";
    $total_sub_sheets       = 1;
    $sub_sheet_title_array  = array("Foxtrot Payroll Adjustments Log");
    $default_open_sub_sheet = 0; // 0 means first indexed sub sheets.
    $excel_name             = 'Foxtrot Payroll Adjustments Log Report';
    $sheet_data             = array();
    $i=6;
        
            
    // Set output excel array.
    /* Headers. */
    if(isset($output_type) && $output_type != '2')
    {
        $sheet_data = array( // Set sheet data.
            0=> // Excel sub sheet indexed.
            array(
                
                'A1'=>array('LOGO',array('bold','center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('A1','B1'))),
                'C1'=>array($company_name,array('normal','center','color'=>array('000000'),'size'=>array(12),'font_name'=>array('Calibri'),'merge'=>array('C1','F1'))),
                'G1'=>array($system_company_name,array('bold','center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('G1','H1'))),
                
                'A2'=>array('COMMISSION ADJUSTMENT LOG',array('bold','center','color'=>array('000000'),'size'=>array(14),'font_name'=>array('Calibri'),'merge'=>array('A2','H3'))),
                'A4'=>array($sorted_by,array('bold','center','color'=>array('000000'),'size'=>array(12),'font_name'=>array('Calibri'),'merge'=>array('A4','H4'))),
                'A5'=>array($payroll_date,array('bold','center','color'=>array('000000'),'size'=>array(12),'font_name'=>array('Calibri'),'merge'=>array('A5','H5'))),
                'A6'=>array('ADJUST#',array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
                'B6'=>array('REP#',array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
                'C6'=>array('CLEAR NUMBER',array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
                'D6'=>array('DESCRIPTION',array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
                'E6'=>array('CATEGORY',array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
                'F6'=>array('TAXABLE AMOUNT',array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
                'G6'=>array('NON TAXABLE AMOUNT',array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
                'H6'=>array('ADVANCE',array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
            )
        );
    }
    else
    {
        $sheet_data = array( // Set sheet data.
            0=> // Excel sub sheet indexed.
            array(
                
                'A1'=>array('LOGO',array('bold','center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('A1','B1'))),
                'C1'=>array($company_name,array('normal','center','color'=>array('000000'),'size'=>array(12),'font_name'=>array('Calibri'),'merge'=>array('C1','F1'))),
                'G1'=>array($system_company_name,array('bold','center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('G1','H1'))),
                
                'A2'=>array('COMMISSION ADJUSTMENT LOG',array('bold','center','color'=>array('000000'),'size'=>array(14),'font_name'=>array('Calibri'),'merge'=>array('A2','H3'))),
                'A4'=>array($sorted_by,array('bold','center','color'=>array('000000'),'size'=>array(12),'font_name'=>array('Calibri'),'merge'=>array('A4','H4'))),
                'A5'=>array('REP#',array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('A5','E5'))),
                'F5'=>array('TAXABLE AMOUNT',array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
                'G5'=>array('NON TAXABLE AMOUNT',array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
                'H5'=>array('ADVANCE',array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
            )
        );
    }
            
    if(isset($get_adjustments_data['data']) && $get_adjustments_data['data'] != array())
    {
        $c = $i;
        $report_taxable_adjustments = 0;
        $report_non_taxable_adjustments = 0;
        $report_advance = 0;
        if(isset($output_type) && ($output_type == '1' || $output_type == '3'))
        {
            foreach($get_adjustments_data['data'] as $adj_key=>$adj_data)
            {
                $i++;
                //$total_records = $total_records+1;
                $sheet_data[0]['A'.$i] = array('BROKER #'.strtoupper($adj_key),array('bold','left','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('A'.$i,'H'.$i)));
                
                $broker_taxable_adjustments = 0;
                $broker_non_taxable_adjustments = 0;
                $broker_advance = 0;
                foreach($adj_data as $adj_sub_key=>$adj_sub_data)
                { 
                    $i++;
                    if(isset($adj_sub_data['payroll_category']) && strtolower($adj_sub_data['payroll_category']) == strtolower('ADVANCE'))
                    {
                        $taxable_adjustments = 0;
                        $non_taxable_adjustments = 0;
                        $advance = isset($adj_sub_data['adjustment_amount']) && $adj_sub_data['adjustment_amount'] != '' ?$adj_sub_data['adjustment_amount']:0;
                    }
                    else
                    {
                        $taxable_adjustments = isset($adj_sub_data['taxable_adjustment']) && $adj_sub_data['taxable_adjustment'] == 1 ?$adj_sub_data['adjustment_amount']:0;
                        $non_taxable_adjustments = isset($adj_sub_data['taxable_adjustment']) && $adj_sub_data['taxable_adjustment'] == 0 ?$adj_sub_data['adjustment_amount']:0;
                        $advance = 0;
                    }
                    
                    $broker_taxable_adjustments = $broker_taxable_adjustments+$taxable_adjustments;
                    $broker_non_taxable_adjustments = $broker_non_taxable_adjustments+$non_taxable_adjustments;
                    $broker_advance = $broker_advance+$advance;
                    
                    $sheet_data[0]['A'.$i] = array($instance->re_db_output($adj_sub_data['id']),array('center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
                    $sheet_data[0]['B'.$i] = array($instance->re_db_output($adj_sub_data['broker_id']),array('center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
                    $sheet_data[0]['C'.$i] = array($instance->re_db_output($adj_sub_data['fund']),array('right','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
                    $sheet_data[0]['D'.$i] = array($instance->re_db_output($adj_sub_data['description']),array('left','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
                    $sheet_data[0]['E'.$i] = array($instance->re_db_output($adj_sub_data['payroll_category']),array('left','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
                    $sheet_data[0]['F'.$i] = array($instance->re_db_output('$'.number_format($taxable_adjustments,2)),array('right','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
                    $sheet_data[0]['G'.$i] = array($instance->re_db_output('$'.number_format($non_taxable_adjustments,2)),array('right','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
                    $sheet_data[0]['H'.$i] = array($instance->re_db_output('$'.number_format($advance,2)),array('right','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
                }$i++;
                $report_taxable_adjustments = $report_taxable_adjustments+$broker_taxable_adjustments;
                $report_non_taxable_adjustments = $report_non_taxable_adjustments+$broker_non_taxable_adjustments;
                $report_advance = $report_advance+$broker_advance;
                
                $sheet_data[0]['A'.$i] = array('* #'.strtoupper($adj_key).' TOTAL *',array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('A'.$i,'E'.$i)));
                $sheet_data[0]['F'.$i] = array($instance->re_db_output('$'.number_format($broker_taxable_adjustments,2)),array('bold','right','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri')));
                $sheet_data[0]['G'.$i] = array($instance->re_db_output('$'.number_format($broker_non_taxable_adjustments,2)),array('bold','right','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri')));
                $sheet_data[0]['H'.$i] = array($instance->re_db_output('$'.number_format($broker_advance,2)),array('bold','right','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri')));
            }
        }
        else if(isset($output_type) && $output_type == '2')
        {
            foreach($get_adjustments_data['data'] as $adj_key=>$adj_data)
            {
                //$i++;
                //$total_records = $total_records+1;
                //$sheet_data[0]['A'.$i] = array('BROKER #'.strtoupper($adj_key),array('bold','left','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('A'.$i,'H'.$i)));
                
                $broker_taxable_adjustments = 0;
                $broker_non_taxable_adjustments = 0;
                $broker_advance = 0;
                foreach($adj_data as $adj_sub_key=>$adj_sub_data)
                { 
                    if(isset($adj_sub_data['payroll_category']) && strtolower($adj_sub_data['payroll_category']) == strtolower('ADVANCE'))
                    {
                        $taxable_adjustments = 0;
                        $non_taxable_adjustments = 0;
                        $advance = isset($adj_sub_data['adjustment_amount']) && $adj_sub_data['adjustment_amount'] != '' ?$adj_sub_data['adjustment_amount']:0;
                    }
                    else
                    {
                        $taxable_adjustments = isset($adj_sub_data['taxable_adjustment']) && $adj_sub_data['taxable_adjustment'] == 1 ?$adj_sub_data['adjustment_amount']:0;
                        $non_taxable_adjustments = isset($adj_sub_data['taxable_adjustment']) && $adj_sub_data['taxable_adjustment'] == 0 ?$adj_sub_data['adjustment_amount']:0;
                        $advance = 0;
                    }
                    
                    $broker_taxable_adjustments = $broker_taxable_adjustments+$taxable_adjustments;
                    $broker_non_taxable_adjustments = $broker_non_taxable_adjustments+$non_taxable_adjustments;
                    $broker_advance = $broker_advance+$advance;
                }$i++;
                $report_taxable_adjustments = $report_taxable_adjustments+$broker_taxable_adjustments;
                $report_non_taxable_adjustments = $report_non_taxable_adjustments+$broker_non_taxable_adjustments;
                $report_advance = $report_advance+$broker_advance;
                
                $sheet_data[0]['A'.$i] = array('BROKER #'.strtoupper($adj_key),array('normal','left','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('A'.$i,'E'.$i)));
                $sheet_data[0]['F'.$i] = array($instance->re_db_output('$'.number_format($broker_taxable_adjustments,2)),array('normal','right','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
                $sheet_data[0]['G'.$i] = array($instance->re_db_output('$'.number_format($broker_non_taxable_adjustments,2)),array('normal','right','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
                $sheet_data[0]['H'.$i] = array($instance->re_db_output('$'.number_format($broker_advance,2)),array('normal','right','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
            }
        }
        $i++;
        $sheet_data[0]['A'.$i] = array('*** REPORT TOTALS ****',array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('A'.$i,'E'.$i)));
        $sheet_data[0]['F'.$i] = array($instance->re_db_output('$'.number_format($report_taxable_adjustments,2)),array('bold','right','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri')));
        $sheet_data[0]['G'.$i] = array($instance->re_db_output('$'.number_format($report_non_taxable_adjustments,2)),array('bold','right','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri')));
        $sheet_data[0]['H'.$i] = array($instance->re_db_output('$'.number_format($report_advance,2)),array('bold','right','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri')));
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