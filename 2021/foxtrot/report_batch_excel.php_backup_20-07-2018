<?php
    require_once("include/config.php");
    require_once(DIR_FS."islogin.php");
    
    $instance = new batches();

    $return = array();
    $filter_array = array();
    $product_category = '';
    $product_category_name = '';
    $beginning_date = '';
    $ending_date = '';
    
    $total_received_amount = 0;
    $total_posted_amount = 0;

    if(isset($_GET['filter']) && $_GET['filter'] != '')
    {
        $filter_array = json_decode($_GET['filter'],true);//echo '<pre>';print_r($filter_array);exit;
        $product_category = isset($filter_array['product_category'])?$filter_array['product_category']:0;
        $company = isset($filter_array['company'])?$filter_array['company']:0;
        $batch = isset($filter_array['batch'])?$filter_array['batch']:0;
        $beginning_date = isset($filter_array['beginning_date'])?$filter_array['beginning_date']:'';
        $ending_date = isset($filter_array['ending_date'])?$filter_array['ending_date']:'';
        $sort_by = isset($filter_array['sort_by'])?$filter_array['sort_by']:'';
        
        $return = $instance->get_all_category_batch_data($product_category,$company,$batch,$beginning_date,$ending_date,$sort_by);
        if($product_category>0)
        {
            $product_category_name = $instance->get_product_type($product_category);
        }
        else
        {
            $product_category_name = 'All Categories';
        }
    }
        //echo '<pre>';print_r($return);exit;
        
        
    $creator                = "Foxtrot User";
    $last_modified_by       = "Foxtrot User";
    $title                  = "Foxtrot Batch Report Excel";
    $subject                = "Foxtrot Batch Report";
    $description            = "Foxtrot Batch Report. Generated on : ".date('Y-m-d h:i:s');
    $keywords               = "Foxtrot Batch report office 2007";
    $category               = "Foxtrot Batch Report.";
    $total_sub_sheets       = 1;
    $sub_sheet_title_array  = array("Foxtrot Batch");
    $default_open_sub_sheet = 0; // 0 means first indexed sub sheets.
    $excel_name             = 'Foxtrot Batch Report';
    $sheet_data             = array();
    $i=4;
        
            
    // Set output excel array.
    /* Headers. */
    $sheet_data = array( // Set sheet data.
        0=> // Excel sub sheet indexed.
        array(
            
            'A1'=>array($product_category_name.' Batch Report',array('bold','center','color'=>array('000000'),'size'=>array(16),'font_name'=>array('Calibri'),'merge'=>array('A1','E2'))),
            
            /*'A2'=>array("Product No. and Unit No. are for our purpose only. Kindly do not change it.",array('bold','color'=>array('dd4b39'),'size'=>array(13),'font_name'=>array('Calibri'),'merge'=>array('A2','G2'))),*/
            
            'A3'=>array($beginning_date.'-'.$ending_date,array('bold','center','color'=>array('000000'),'size'=>array(12),'font_name'=>array('Calibri'),'merge'=>array('A3','E3'))),
            'A4'=>array('BATCH#',array('bold','center','color'=>array('000000'),'size'=>array(13),'font_name'=>array('Calibri'))),
            'B4'=>array('DATE RECEIVED',array('bold','center','color'=>array('000000'),'size'=>array(13),'font_name'=>array('Calibri'))),
            'C4'=>array('AMOUNT RECEIVED',array('bold','center','color'=>array('000000'),'size'=>array(13),'font_name'=>array('Calibri'))),
            'D4'=>array('POSTED TO DATE',array('bold','center','color'=>array('000000'),'size'=>array(13),'font_name'=>array('Calibri'))),
            'E4'=>array('STATEMENT DESCRIPTION',array('bold','center','color'=>array('000000'),'size'=>array(13),'font_name'=>array('Calibri'))),
            
        )
    );
            
        if(is_array($return) && count($return)>0)
        {
            $c = $i;
            foreach($return as $key=>$val){
                $i++;
                $c = $i;
                $sheet_data[0]['A'.$i] = array($instance->re_db_output('Investment Category: '.$key),array('bold','left','size'=>array(13),'color'=>array('000000'),'merge'=>array('A'.$i,'E'.$i)));
                $posted_commission_amount = 0;
                $amount_received = 0;
                
                $cat_total_received_amount = 0;
                $cat_total_posted_amount = 0;
                
                foreach($val as $sub_key=>$sub_val)
                { $i++;
                    $get_commission_amount = $instance->get_commission_total($sub_val['id']);
                    $amount_received = $sub_val['check_amount'];
                    
                    if(isset($get_commission_amount['posted_commission_amount']) && $get_commission_amount['posted_commission_amount']!='')
                    {
                        $posted_commission_amount = $get_commission_amount['posted_commission_amount'];
                        
                    }else
                    { $posted_commission_amount = 0;}
                    $cat_total_posted_amount = $cat_total_posted_amount+$posted_commission_amount;
                    $cat_total_received_amount = $cat_total_received_amount+$amount_received;
                    
                    $sheet_data[0]['A'.$i] = array($instance->re_db_output($sub_val['id']),array('right','color'=>array('000000'),'size'=>array(13),'font_name'=>array('Calibri')));
                    $sheet_data[0]['B'.$i] = array($instance->re_db_output(date('m/d/Y',strtotime($sub_val['batch_date']))),array('right','color'=>array('000000'),'size'=>array(13),'font_name'=>array('Calibri')));
                    $sheet_data[0]['C'.$i] = array($instance->re_db_output('$'.number_format($amount_received,2)),array('right','color'=>array('000000'),'size'=>array(13),'font_name'=>array('Calibri')));
                    $sheet_data[0]['D'.$i] = array($instance->re_db_output('$'.number_format($posted_commission_amount,2)),array('right','color'=>array('000000'),'size'=>array(13),'font_name'=>array('Calibri')));
                    $sheet_data[0]['E'.$i] = array($instance->re_db_output($sub_val['batch_desc']),array('right','color'=>array('000000'),'size'=>array(13),'font_name'=>array('Calibri')));
                       
                }
                $i++;
                $total_posted_amount = $total_posted_amount+$cat_total_posted_amount;
                $total_received_amount = $total_received_amount+$cat_total_received_amount;
                $sheet_data[0]['A'.$i] = array('** Category Total **',array('bold','right','color'=>array('000000'),'size'=>array(13),'font_name'=>array('Calibri'),'merge'=>array('A'.$i,'B'.$i)));
                $sheet_data[0]['C'.$i] = array($instance->re_db_output('$'.number_format($cat_total_received_amount,2)),array('bold','right','color'=>array('000000'),'size'=>array(13),'font_name'=>array('Calibri')));
                $sheet_data[0]['D'.$i] = array($instance->re_db_output('$'.number_format($cat_total_posted_amount,2)),array('bold','right','color'=>array('000000'),'size'=>array(13),'font_name'=>array('Calibri')));
                //$i--;
            }
            $i++;
            $sheet_data[0]['A'.$i] = array('*** Total ***',array('bold','right','color'=>array('000000'),'size'=>array(13),'font_name'=>array('Calibri'),'merge'=>array('A'.$i,'B'.$i)));
            $sheet_data[0]['C'.$i] = array($instance->re_db_output('$'.number_format($total_received_amount,2)),array('bold','right','color'=>array('000000'),'size'=>array(13),'font_name'=>array('Calibri')));
            $sheet_data[0]['D'.$i] = array($instance->re_db_output('$'.number_format($total_posted_amount,2)),array('bold','right','color'=>array('000000'),'size'=>array(13),'font_name'=>array('Calibri')));
            
        }
        else{
            $i++;
            $sheet_data[0]['A'.$i] = array($instance->re_db_output('No record found.'),array('center','size'=>array(13),'color'=>array('000000'),'merge'=>array('A'.$i,'E'.$i)));
            /*$sheet_data = array( // Set sheet data.
                0=> // Excel sub sheet indexed.
                array(
                    'A1'=>'No record found.'
                )
            );*/
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