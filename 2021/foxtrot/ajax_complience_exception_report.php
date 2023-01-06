<?php

require_once("include/config.php");
require_once(DIR_FS . "islogin.php");
$instance_import = new import();

$instance = new broker_master();
$get_brokers = $instance->select_broker();
// $instance_sponsor = new manage_sponsor();
$instance_client = new client_maintenance();

$filter_array = array();
$beginning_date = '';
$ending_date = '';


//DEFAULT PDF DATA:
$get_logo = $instance->get_system_logo();
$system_logo = isset($get_logo['logo']) ? $instance->re_db_input($get_logo['logo']) : '';
$get_company_name = $instance->get_company_name();
$system_company_name = isset($get_company_name['company_name']) ? $instance->re_db_input($get_company_name['company_name']) : '';
$img = '<img src="' . SITE_URL . "upload/logo/" . $system_logo . '" height="25px" />';

//filter
if (isset($_GET['filter']) && $_GET['filter'] != '') {
    $filter_array = json_decode($_GET['filter'], true);
    $client = isset($filter_array['client'])? $filter_array['client']:0;
    $broker_id = isset($filter_array['broker']) ? $filter_array['broker'] : 0;
    $beginning_date = isset($filter_array['beginning_date']) && !empty($filter_array['beginning_date']) ? date('Y-m-d', strtotime($instance->re_db_input($filter_array['beginning_date']))) : '';
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
   
   
    $client_name='All Clients';
    if(isset($client) && $client != 0){
        $queried_client=$instance_client->select_client_master($client);
        $client_name=$queried_client['last_name'].", ".$queried_client['first_name'];
    }
   
        $is_recrod_found = false;

        $list_data= $instance_import->complience_exception_report($beginning_date,$ending_date,$broker_id,$client);
        //echo "<pre>"; print_r($list_data);
        ?>
        <div class="print_section accounting">

            <table border="0" width="100%">
                <tr>
                    <td width="20%" align="left"><?php echo date("m/d/Y"); ?> </td>

                    <td width="60%" align="center"><?php echo $img; ?> <br />
                        <h5>COMPLIENCE EXCEPTION REPORT </h5>
                        <h6>Trade Dates: <?php echo $trade_dates; ?></h6>
                        <h6>Broker: <?php echo $queried_brokers; ?>, Client: <?php echo $client_name; ?> </h6>
                    </td>
                    <td width="20%" align="right" 1>Page 1</td>
                </tr>
            </table>
            <br />
          
            <div class="print_content">
                <table>
                    <thead>
                        <tr style="background:#f1f1f1;text-transform: capitalize;" style="width: 100%;">
                            <th style="font-size: 12px; width: 10%;">TRADE DATE <br>SCAN DATE</th>
                            <th style="padding: 8px 0px;font-size: 11px; width: 10%;">TRADE #</th>
                            <th style="padding: 8px 0px;font-size: 12px; width: 16%;">CLIENT</th>
                            <th style="padding: 8px 0px;font-size: 12px; width: 11%;">CLIENT ACCOUNT NUMBER</th>
                            <th style="padding: 8px 0px;font-size: 12px; width: 20%;">BROKER</th> 
                            <th style="padding: 8px 0px;font-size: 12px;">MESSAGE </th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                      
                        foreach($list_data as $row){
                            $is_recrod_found=true;?>
                            <tr style="height:20px;">
                                <td><?php echo (isset($row['trade_date']) && $row['trade_date']!= '0000-00-00')? date('m/d/Y',strtotime($row['trade_date'])):'--'; ?></td>
                                <td colspan="3"><?php echo $row['error_message'] ?></td>
                                <td></td>
                            </tr>
                            
                            <tr style="height:30px; border-bottom:1px solid black; vertical-align: text-top;">
                            
                                <td><?php echo (isset($row['scan_date']) && $row['scan_date']!= '0000-00-00')? date('m/d/Y',strtotime($row['scan_date'])):''; ?></td>
                                <!-- <td><?php echo $row['id']." - ".$row['detail_table'] ?></td> -->
                                <td><?php echo (isset($row['transaction_id']))? $row['transaction_id'] :''; ?></td>
                                <td><?php echo $row['client'] ?></td>
                                <td><?php echo isset($row['client_account'])?$row['client_account']:'' ?></td>
                                <td><?php echo $row['rep_name'] ?></td>
                                <td><?php echo isset($row['message'])?$row['message']:'' ?></td>
                            </tr>
                            
                        <?php } ?>
                    
                        <?php
                            if($is_recrod_found==false)
                            {?>
                                <tr>
                                   <td style="font-size:12px;text-align:center; padding: 15px 5px;" colspan="6"><b>No Records Found.</b> </td>
                                </tr>
                            <?php } 
                        ?>

                    </tbody>
                </table>
            </div>
        </div>

    </tbody>
    </table>
<?php  }

?>