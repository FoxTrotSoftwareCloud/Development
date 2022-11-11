<?php
require_once("include/config.php");
require_once(DIR_FS . "islogin.php");
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
$system_logo = isset($get_logo['logo']) ? $instance->re_db_input($get_logo['logo']) : '';
$get_company_name = $instance->get_company_name();
$system_company_name = isset($get_company_name['company_name']) ? $instance->re_db_input($get_company_name['company_name']) : '';
$img = '<img src="' . SITE_URL . "upload/logo/" . $system_logo . '" height="25px" />';

//filter batch report
if (isset($_GET['filter']) && $_GET['filter'] != '') {
    $filter_array = json_decode($_GET['filter'], true);
    $sponsor_id = isset($filter_array['sponsor'])?$filter_array['sponsor']:'';
    $report_for = isset($filter_array['report_for']) ? trim($filter_array['report_for']) : '';
    $company_id = isset($filter_array['company']) ? $filter_array['company'] : 0;
    $branch_id = isset($filter_array['branch']) ? $filter_array['branch'] : 0;
    $broker_id = isset($filter_array['broker']) ? $filter_array['broker'] : 0;
    $beginning_date = isset($filter_array['beginning_date']) && !empty($filter_array['beginning_date']) ? date('Y-m-d H:i:s', strtotime($instance->re_db_input($filter_array['beginning_date']))) : '';
    $ending_date = isset($filter_array['ending_date']) && !empty($filter_array['ending_date']) ? date('Y-m-d', strtotime($instance->re_db_input($filter_array['ending_date']))) : '';

    $total_records = 0;
    $total_records_sub = 0;

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
   
   
    if ($report_for == 1) {
        $is_recrod_found = false;

        $trade_data= $instance_trans->daily_trade_blotter_report($company_id,$branch_id,$broker_id,$beginning_date,$ending_date);
        // echo "<pre>"; print_r($trade_data);
    
        ?>
        <div class="print_section accounting">

            <table border="0" width="100%">
                <tr>
                    <td width="20%" align="left"><?php echo date("m/d/Y"); ?> </td>

                    <td width="60%" align="center"><?php echo $img; ?> <br />
                        <h5>DAILY TRADE BLOTTER REPORT </h5>
                        <h6>Trade Dates: <?php echo $trade_dates; ?></h6>
                        <h6>Company: <?php echo $company_name; ?>, Branch: <?php echo $branch_name; ?>, Broker: <?php echo $queried_brokers; ?> </h6>
                    </td>
                    <td width="20%" align="right" 1>Page 1</td>
                </tr>

            </table>
            <br />
          
            <div class="print_content">
                <table>
                    <thead>
                        <tr style="background:#f1f1f1;text-transform: capitalize;" style="width: 100%;">
                            <th style="font-size: 12px; width: 7%;">TRADE#</th>
                            <th style="padding: 8px 0px;font-size: 12px; width: 6%;">BRANCH</th>
                            <th style="padding: 8px 0px;font-size: 12px; width: 8%;">DATE</th>
                            <th style="padding: 8px 0px;font-size: 12px; width: 8%;">CHECK DATE</th>
                            <th style="padding: 8px 0px;font-size: 12px; width: 15%;" >CLIENT</th>
                            <th style="padding: 8px 0px;font-size: 12px; width: 13%;">BROKER</th>
                            <th style="padding: 8px 0px;font-size: 12px;">SPONSOR/ <br>PRODUCT</th>
                            <th style="padding: 8px 0px;font-size: 12px; width: 6%;">AMOUNT</th>
                            <th style="padding: 15px 0px;font-size: 12px; width: 8%;">COMM.REC</th>
                            <th style="padding: 8px 0px;font-size: 12px; width: 8%;">DATE REC/<br> DATE PAID</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                        $total_amount=0.00;
                        $total_comm_rec= 0.00;
                        foreach($trade_data as $trade){
                            $is_recrod_found=true;?>
                        <tr style="height:30px ;">
                            <td><?php echo $trade['id'] ?></td>
                            <td width="5%"><?php echo $trade['branch'] ?></td>
                            <td width="15%"><?php echo date('m/d/Y',strtotime($trade['trade_date']));?></td>
                            <td><?php echo ($trade['check_date']!= '0000-00-00')? date('m/d/Y',strtotime($trade['check_date'])):''; ?></td>
                            <td><?php echo $trade['client_lastname'].", ".$trade['client_firstname'] ?></td>
                            <td><?php echo $trade['broker_last_name'].", ".$trade['broker_firstname'] ?></td>
                            <td><?php echo $trade['sponsor_name']?></td>
                            <td><?php echo $trade['invest_amount'] ?></td>
                            <td><?php echo $trade['commission_received']?></td>
                            <td><?php echo date('m/d/Y',strtotime($trade['commission_received_date']));?></td>                        
                        </tr>
                        <tr>
                            <td colspan="2"><?php echo $trade['created_by'] ?></td>
                            <td colspan="2"><?php echo date('m/d/Y h:i:s',strtotime($trade['created_time']));?></td>
                            <td></td>
                            <td></td>
                            <td><?php echo $trade['product_name']?></td>
                            <td></td>
                            <td></td>
                            <td><?php echo ($trade['date_paid']!= '0000-00-00')? date('m/d/Y',strtotime($trade['date_paid'])):''; ?></td>
                        </tr>
                        <?php $total_amount += $trade['invest_amount'];
                              $total_comm_rec += $trade['commission_received'];
                        } ?>

                        <?php
                            if($is_recrod_found==true)
                            {?>
                        <tr>
                            <td colspan="6"></td>
                            <td style="text-align: center;"><hr><b>TOTAL:</b></td>
                            <td><hr><b><?php echo number_format($total_amount,2) ; ?></b></td>
                            <td colspan="2" style="padding-left: 3px;"><hr><b><?php echo number_format($total_comm_rec,2) ; ?></b></td>
                        </tr>
                        <?php } 
                        ?>
                        
                        <?php
                            if($is_recrod_found==false)
                            {?>
                                <tr>
                                   <td style="font-size:12px;text-align:center; padding: 15px 5px;" colspan="10"><b>No Records Found.</b> </td>
                                </tr>
                            <?php } 
                        ?>

                    </tbody>
                </table>
            </div>
        </div>
    <?php }  ?>
    </tbody>
    </table>
<?php  }

?>