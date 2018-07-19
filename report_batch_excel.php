<?php
    require_once("include/config.php");
    require_once(DIR_FS."islogin.php");
    include 'include/PHPExcel/classes/PHPExcel.php';
    
    $instance = new batches();

    $return = array();
    $filter_array = array();
    $product_category = '';
    $product_category_name = '';
    $beginning_date = '';
    $ending_date = '';
    
    $total_received_amount = 0;
    $total_posted_amount = 0;
    $total_records=0;
    $total_records_sub=0;
    
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
    
    $objPHPExcel = new PHPExcel();
    /*$objDrawing = new PHPExcel_Worksheet_HeaderFooterDrawing();
    $objDrawing->setName('Image');
    $objDrawing->setPath("upload/logo/".$system_logo);//print_r($objDrawing);exit;
    $objDrawing->setHeight(36);
    $objPHPExcel->getActiveSheet()->getHeaderFooter()->addImage($objDrawing, PHPExcel_Worksheet_HeaderFooter::IMAGE_HEADER_LEFT);
    $objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddHeader('&L&G&');*/
    
    // Create new picture object and insert picture
    /*$objDrawing = new PHPExcel_Worksheet_Drawing();
    $objDrawing->setName('Image');
    $objDrawing->setDescription('Image');
    $objDrawing->setPath("upload/logo/".$system_logo);
    $objDrawing->setHeight(50);
    $objDrawing->setCoordinates('A1');
    $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());*/
    
   // Set output excel array.
    /* Headers. */
    
    /*$objDrawing = new PHPExcel_Worksheet_Drawing();    //create object for Worksheet drawing

    $objDrawing->setName('Customer Signature');        //set name to image
    
    $objDrawing->setDescription('Customer Signature'); //set description to image
    
    //$signature = $reportdetails[$rowCount][$value];    //Path to signature .jpg file
    $objDrawing->setPath(SITE_URL."upload/1517662211258367.jpg");
    
    $objDrawing->setOffsetX(25);                       //setOffsetX works properly
    $objDrawing->setOffsetY(10);                       //setOffsetY works properly
    
    $objDrawing->setCoordinates('F1');        //set image to cell
    
    $objDrawing->setWidth(32);                 //set width, height
    $objDrawing->setHeight(32);  
                         
    $objDrawing->setWorksheet($objPHPExcel->getActiveSheet()); */ //save
    
    /*
    $objDrawing = new PHPExcel_Worksheet_Drawing();
    $objDrawing->setName('test_img');
    $objDrawing->setDescription('test_img');
    $objDrawing->setPath(SITE_URL."upload/logo/".$system_logo);
    $objDrawing->setCoordinates('F1');                      
    //setOffsetX works properly
    $objDrawing->setOffsetX(5); 
    $objDrawing->setOffsetY(5);                
    //set width, height
    $objDrawing->setWidth(100); 
    $objDrawing->setHeight(35); 
    $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
    */
    
    $sheet_data = array( // Set sheet data.
        0=> // Excel sub sheet indexed.
        array(
            'A1'=>array('LOGO',array('bold','center','color'=>array('000000'),'size'=>array(16),'font_name'=>array('Calibri'),'merge'=>array('A1','A2'))),
            'B1'=>array($product_category_name.' Batch Report',array('bold','center','color'=>array('000000'),'size'=>array(14),'font_name'=>array('Calibri'),'merge'=>array('B1','D2'))),
            'E1'=>array($system_company_name,array('bold','center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri'),'merge'=>array('E1','E2'))),
            'A3'=>array($beginning_date.'-'.$ending_date,array('bold','center','color'=>array('000000'),'size'=>array(12),'font_name'=>array('Calibri'),'merge'=>array('A3','E3'))),
            'A4'=>array('BATCH#',array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
            'B4'=>array('DATE RECEIVED',array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
            'C4'=>array('AMOUNT RECEIVED',array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
            'D4'=>array('POSTED TO DATE',array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
            'E4'=>array('STATEMENT DESCRIPTION',array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri'))),
            
        )
    );
            
        if(is_array($return) && count($return)>0)
        {
            $c = $i;
            foreach($return as $key=>$val){
                $i++;
                $c = $i;
                $sheet_data[0]['A'.$i] = array($instance->re_db_output('Investment Category: '.$key),array('bold','left','size'=>array(10),'color'=>array('000000'),'merge'=>array('A'.$i,'E'.$i)));
                $posted_commission_amount = 0;
                $amount_received = 0;
                
                $cat_total_received_amount = 0;
                $cat_total_posted_amount = 0;
                
                foreach($val as $sub_key=>$sub_val)
                { $i++;
                    $total_records_sub = $total_records_sub+1;
                    $get_commission_amount = $instance->get_commission_total($sub_val['id']);
                    $amount_received = $sub_val['check_amount'];
                    
                    if(isset($get_commission_amount['posted_commission_amount']) && $get_commission_amount['posted_commission_amount']!='')
                    {
                        $posted_commission_amount = $get_commission_amount['posted_commission_amount'];
                        
                    }else
                    { $posted_commission_amount = 0;}
                    $cat_total_posted_amount = $cat_total_posted_amount+$posted_commission_amount;
                    $cat_total_received_amount = $cat_total_received_amount+$amount_received;
                    
                    $sheet_data[0]['A'.$i] = array($instance->re_db_output($sub_val['id']),array('center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
                    $sheet_data[0]['B'.$i] = array($instance->re_db_output(date('m/d/Y',strtotime($sub_val['batch_date']))),array('center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
                    $sheet_data[0]['C'.$i] = array($instance->re_db_output('$'.number_format($amount_received,2)),array('right','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
                    $sheet_data[0]['D'.$i] = array($instance->re_db_output('$'.number_format($posted_commission_amount,2)),array('right','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
                    $sheet_data[0]['E'.$i] = array($instance->re_db_output($sub_val['batch_desc']),array('center','color'=>array('000000'),'size'=>array(10),'font_name'=>array('Calibri')));
                       
                }
                $total_records=$total_records+$total_records_sub;
                $i++;
                $total_posted_amount = $total_posted_amount+$cat_total_posted_amount;
                $total_received_amount = $total_received_amount+$cat_total_received_amount;
                $sheet_data[0]['A'.$i] = array('Total Category Records: '.$total_records_sub,array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri')));
                $sheet_data[0]['B'.$i] = array('** Category Total **',array('bold','right','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri')));
                $sheet_data[0]['C'.$i] = array($instance->re_db_output('$'.number_format($cat_total_received_amount,2)),array('bold','right','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri')));
                $sheet_data[0]['D'.$i] = array($instance->re_db_output('$'.number_format($cat_total_posted_amount,2)),array('bold','right','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri')));
                $sheet_data[0]['E'.$i] = array($instance->re_db_output(''),array('bold','right','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri')));
                //$i--;
            }
            $i++;
            $i=$i+1;
            $sheet_data[0]['A'.$i] = array('Total Records: '.$total_records,array('bold','center','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri')));
            $sheet_data[0]['B'.$i] = array('*** Total ***',array('bold','right','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri')));
            $sheet_data[0]['C'.$i] = array($instance->re_db_output('$'.number_format($total_received_amount,2)),array('bold','right','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri')));
            $sheet_data[0]['D'.$i] = array($instance->re_db_output('$'.number_format($total_posted_amount,2)),array('bold','right','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri')));
            $sheet_data[0]['E'.$i] = array($instance->re_db_output(''),array('bold','right','color'=>array('000000'),'background'=>array('f1f1f1'),'size'=>array(10),'font_name'=>array('Calibri')));
        }
        else{
            $i++;
            $sheet_data[0]['A'.$i] = array($instance->re_db_output('No record found.'),array('center','size'=>array(10),'color'=>array('000000'),'merge'=>array('A'.$i,'E'.$i)));
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