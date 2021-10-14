<?php
require_once("include/config.php");
require_once(DIR_FS."islogin.php");
$instance = new batches();
$instance_trans = new transaction();
$get_trans_data = array();
$return_batches = array();
$filter_array = array();
$product_category = '';
$product_category_name = '';
$beginning_date = '';
$ending_date = '';

//filter batch report
if(isset($_GET['filter']) && $_GET['filter'] != '')
{
    $filter_array = json_decode($_GET['filter'],true);
    $product_category = isset($filter_array['product_category'])?$filter_array['product_category']:0;
    $company = isset($filter_array['company'])?$filter_array['company']:0;
    $batch = isset($filter_array['batch'])?$filter_array['batch']:0;
    $beginning_date = isset($filter_array['beginning_date'])?$filter_array['beginning_date']:'';
    $ending_date = isset($filter_array['ending_date'])?$filter_array['ending_date']:'';
    $sort_by = isset($filter_array['sort_by'])?$filter_array['sort_by']:'';
    $report_for = isset($filter_array['report_for'])?trim($filter_array['report_for']):'';
    
    if($report_for==1)
    {
    
        $return_batches = $instance->get_all_category_batch_data($product_category,$company,$batch,$beginning_date,$ending_date,$sort_by);
        if($product_category>0)
        {
            $product_category_name = $instance->get_product_type($product_category);
        }
        else
        {
            $product_category_name = 'All Categories';
        }
    
        $total_received_amount = 0;
        $total_posted_amount = 0;

        ?>
        <table id="data-table" class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th colspan="5"><h3 style="text-align: center;"><?php echo $product_category_name.' Batch Report';?></h3></th>
                </tr>
                <tr>
                    <th colspan="5"><h4 style="text-align: center;"><?php echo $beginning_date.'-'.$ending_date;?></h4></th>
                </tr>
                <tr>
                    <th><h4>Batch#</h4></th>
                    <th><h4>Date Received</h4></th>
                    <th><h4>Amount Received</h4></th>
                    <th><h4>Posted to Date</h4></th>
                    <th><h4>Statement Description</h4></th>
                </tr>
            </thead>
            <tbody>
            <?php 
            if($return_batches != array())
            {
                foreach($return_batches as $main_key=>$main_val)
                {?>
                    <tr>
                           <td colspan="5" style="font-size:13px;font-weight:bold;text-align:left;">Investment Category: <?php echo $main_key; ?></td>
                    </tr>
              <?php $posted_commission_amount = 0;
                    $amount_received = 0;
                    
                    $cat_total_received_amount = 0;
                    $cat_total_posted_amount = 0;
                    
                    foreach($main_val as $sub_key=>$sub_val)
                    {
                        $get_commission_amount = $instance->get_commission_total($sub_val['id']);
                        $amount_received = $sub_val['check_amount'];
                        
                        if(isset($get_commission_amount['posted_commission_amount']) && $get_commission_amount['posted_commission_amount']!='')
                        {
                            $posted_commission_amount = $get_commission_amount['posted_commission_amount'];
                            
                        }else
                        { $posted_commission_amount = 0;}
                        $cat_total_posted_amount = $cat_total_posted_amount+$posted_commission_amount;
                        $cat_total_received_amount = $cat_total_received_amount+$amount_received;
                        ?>
                        <tr>
                               <td style="font-size:13px;font-weight:normal;text-align:left;"><?php echo $sub_val['id']; ?></td>
                               <td style="font-size:13px;font-weight:normal;text-align:left;"><?php echo date('m/d/Y',strtotime($sub_val['batch_date'])); ?></td>
                               <td style="font-size:13px;font-weight:normal;text-align:right;"><?php echo '$'.number_format($amount_received,2); ?></td>
                               <td style="font-size:13px;font-weight:normal;text-align:right;"><?php echo '$'.number_format($posted_commission_amount,2); ?></td>
                               <td style="font-size:13px;font-weight:normal;text-align:left;"><?php echo $sub_val['batch_desc']; ?></td>
                        </tr>
                    <?php }
                    $total_posted_amount = $total_posted_amount+$cat_total_posted_amount;
                    $total_received_amount = $total_received_amount+$cat_total_received_amount; ?>
                    <tr>
                           <td style="font-size:13px;font-weight:bold;text-align:right;"></td>
                           <td style="font-size:13px;font-weight:bold;text-align:right;">** Category Total **</td>
                           <td style="font-size:13px;font-weight:bold;text-align:right;"><?php echo '$'.number_format($cat_total_received_amount,2); ?></td>
                           <td style="font-size:13px;font-weight:bold;text-align:right;"><?php echo '$'.number_format($cat_total_posted_amount,2); ?></td>
                           <td style="font-size:13px;font-weight:bold;text-align:right;"></td>
                    </tr>
            <?php 
                }?>
                <tr>
                    <td style="font-size:13px;font-weight:bold;text-align:right;"></td>
                    <td style="font-size:13px;font-weight:bold;text-align:right;">*** Total ***</td>
                    <td style="font-size:13px;font-weight:bold;text-align:right;"><?php echo '$'.number_format($total_received_amount,2); ?></td>
                    <td style="font-size:13px;font-weight:bold;text-align:right;"><?php echo '$'.number_format($total_posted_amount,2); ?></td>
                    <td style="font-size:13px;font-weight:bold;text-align:right;"></td>
                </tr> 
                
            <?php 
            }
            else
            {?>
                
            <tr>
                <td style="font-size:13px;font-weight:normal;text-align:center;" colspan="8">No record found.</td>
            </tr>
            
            <?php } ?>   
            
             
           </tbody>
        </table>
<?php }
      else if($report_for==2)
      {
            $get_trans_data = $instance_trans->select_data_report($product_category,$company,$batch,$beginning_date,$ending_date,$sort_by);
            $batch_desc = isset($get_trans_data[0]['batch_desc'])?$instance->re_db_input($get_trans_data[0]['batch_desc']):'';
            $total_amount_invested = 0;
            $total_commission_received = 0;
            $total_charges = 0;
    
            ?>
            <table id="data-table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                    <?php if($batch > 0){?>
                        <th colspan="8"><h3 style="text-align: center;">TRANSACTION BY BATCH REPORT : <?php echo strtoupper($batch_desc);?></h3></th>
                    <?php } else { ?>
                        <th colspan="8"><h3 style="text-align: center;">TRANSACTION BY BATCH REPORT : ALL BATCHES</h3></th>
                    <?php } ?>
                    </tr>
                    <tr>
                    <?php if($batch > 0){?>
                        <th colspan="8"><h4 style="text-align: center;">Batch #<?php echo $batch;?></h4></th>
                    <?php } ?>
                    </tr>
                    <tr>
                        <td><h4>TRADE#</h4></td>
                        <td><h4>BROKER</h4></td>
                        <td><h4>CLIENT</h4></td>
                        <td><h4>TRADE DATE</h4></td>
                        <td><h4>DATE RECEIVED</h4></td>
                        <td><h4>AMOUNT INVESTED</h4></td>
                        <td><h4>COMMISSION RECEIVED</h4></td>
                        <td><h4>CHARGE</h4></td>
                    </tr>
                </thead>
                <tbody>
                <?php 
                if($get_trans_data != array())
                {
                    foreach($get_trans_data as $trans_key=>$trans_data)
                    {
                        $total_amount_invested = ($total_amount_invested+$trans_data['invest_amount']);
                        $total_commission_received = ($total_commission_received+$trans_data['commission_received']);
                        $total_charges = ($total_charges+$trans_data['charge_amount']);?>
                        <tr>
                               <td style="font-size:13px;font-weight:normal;text-align:left;"><?php echo $trans_data['id']; ?></td>
                               <td style="font-size:13px;font-weight:normal;text-align:left;"><?php echo $trans_data['broker_name']; ?></td>
                               <td style="font-size:13px;font-weight:normal;text-align:left;"><?php echo $trans_data['client_name']; ?></td>
                               <td style="font-size:13px;font-weight:normal;text-align:left;"><?php if($trans_data['trade_date'] != '0000-00-00'){ echo date('m/d/Y',strtotime($trans_data['trade_date'])); } ?></td>
                               <td style="font-size:13px;font-weight:normal;text-align:left;"><?php if($trans_data['commission_received_date'] != '0000-00-00'){ echo date('m/d/Y',strtotime($trans_data['commission_received_date'])); } ?></td>
                               <td style="font-size:13px;font-weight:normal;text-align:right;"><?php echo '$'.number_format($trans_data['invest_amount'],2); ?></td>
                               <td style="font-size:13px;font-weight:normal;text-align:right;"><?php echo '$'.number_format($trans_data['commission_received'],2); ?></td>
                               <td style="font-size:13px;font-weight:normal;text-align:right;"><?php echo '$'.number_format($trans_data['charge_amount'],2); ?></td>
                    </tr>
                <?php } ?>
                <tr>
                   
                   <td style="font-size:13px;font-weight:bold;text-align:right;" colspan="5">Report Total : </td>
                   <td style="font-size:13px;font-weight:bold;text-align:right;"><?php echo '$'.number_format($total_amount_invested,2);?></td>
                   <td style="font-size:13px;font-weight:bold;text-align:right;"><?php echo '$'.number_format($total_commission_received,2);?></td>
                   <td style="font-size:13px;font-weight:bold;text-align:right;"><?php echo '$'.number_format($total_charges,2);?></td>
                </tr>
            <?php } 
            else
            {?>
                <tr>
                   <td style="font-size:13px;font-weight:normal;text-align:center;" colspan="8">No Records Found.</td>
                </tr>
            <?php } ?>           
            </tbody>
    </table>       
<?php  }
}
?>
       