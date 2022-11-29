<?php

require_once("include/config.php");
require_once(DIR_FS . "islogin.php");
$instance = new broker_master();
$get_brokers = $instance->select_broker();
$instance_sponsor = new manage_sponsor();
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
    $sponsor_id = isset($filter_array['sponsor'])?$filter_array['sponsor']:'';
    $broker_id = isset($filter_array['broker']) ? $filter_array['broker'] : 0;
    $cip_client = isset($filter_array['cip_client']) ? $filter_array['cip_client'] : 0;
    $exclude_donot_contact_client = isset($filter_array['dont_contact_client']) ? $filter_array['dont_contact_client'] : 0;
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
   
    $cip_info = "All Clients";
    if($cip_client == 1){
        $cip_info = "Clients With CIP Data";
    }
    $sponsor_name='All Sponsors';
    if(isset($sponsor_id) && $sponsor_id != 0){
        $queried_sponsor=$instance_sponsor->select_sponsor_by_id($sponsor_id);
        $sponsor_name=$queried_sponsor['name'];
    }
   
        $is_recrod_found = false;

        $list_data= $instance_client->client_cip_report($beginning_date,$ending_date,$broker_id,$cip_client,$exclude_donot_contact_client,$sponsor_id);
        //echo "<pre>"; print_r($list_data);
        ?>
        <div class="print_section accounting">

            <table border="0" width="100%">
                <tr>
                    <td width="20%" align="left"><?php echo date("m/d/Y"); ?> </td>

                    <td width="60%" align="center"><?php echo $img; ?> <br />
                        <h5>CLIENT CIP REPORT </h5>
                        <h6>Trade Dates: <?php echo $trade_dates; ?></h6>
                        <h6>Broker: <?php echo $queried_brokers; ?>, Sponsor: <?php echo $sponsor_name; ?>,<?php echo " ".$cip_info  ?> </h6>
                    </td>
                    <td width="20%" align="right" 1>Page 1</td>
                </tr>
            </table>
            <br />
          
            <div class="print_content">
                <table>
                    <thead>
                        <tr style="background:#f1f1f1;text-transform: capitalize;" style="width: 100%;">
                            <th style="font-size: 12px; width: 7%;">ID</th>
                            <th style="font-size: 12px; width: 7%;">TYPE</th>
                            <th style="padding: 8px 0px;font-size: 12px; width: 6%;">NUMBER</th>
                            <th style="padding: 8px 0px;font-size: 12px; width: 8%;">EXPIRATION DATE</th>
                            <th style="padding: 8px 0px;font-size: 12px; width: 6%;">STATE</th>
                            <th style="padding: 8px 0px;font-size: 12px; width: 8%;" >VARIFIED DATE</th>
                            <th style="padding: 8px 0px;font-size: 12px; width: 6%;">OPEN DATE</th>
                            <th style="padding: 8px 0px;font-size: 12px;">DOB</th>
                            <th style="padding: 8px 0px;font-size: 12px;">NAME</th>
                            <th style="padding: 8px 0px;font-size: 12px; width: 13%;">ADDRESS</th>
                            <th style="padding: 15px 0px;font-size: 12px; width: 8%;">CITY</th>
                            <th style="padding: 8px 0px;font-size: 12px; width: 8%;">STATE</th>
                            <th style="padding: 8px 0px;font-size: 12px; width: 8%;">ZIP CODE</th>
                            <th style="padding: 8px 0px;font-size: 12px; width: 8%;">PHONE NUMBER</th>
                            <th style="padding: 8px 0px;font-size: 12px; width: 8%;">SSN</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                      
                        foreach($list_data as $row){
                            $is_recrod_found=true;?>
                        <tr style="height:30px ;">
                            <td></td>
                            <td></td>
                            <td><?php echo (isset($row['employ_number']))?$row['employ_number']:'' ?></td>
                            <td><?php echo (isset($row['expiration']) && $row['expiration']!= '0000-00-00')? date('m/d/Y',strtotime($row['expiration'])):''; ?></td>
                            <td><?php echo (isset($row['employ_state']))?$row['employ_state']:'' ?></td>
                            <td><?php echo (isset($row['date_verified']) && $row['date_verified']!= '0000-00-00')? date('m/d/Y',strtotime($row['date_verified'])):''; ?></td>
                            <td><?php echo (isset($row['open_date']) && $row['open_date']!= '0000-00-00')? date('m/d/Y',strtotime($row['open_date'])):''; ?></td>
                            <td><?php echo (isset($row['birth_date']) && $row['birth_date']!= '0000-00-00')? date('m/d/Y',strtotime($row['birth_date'])):''; ?></td>
                            <td><?php echo $row['last_name'] ?>,<?php echo $row['first_name'] ?></td>
                            <td><?php echo $row['address1'] ?><br><?php echo $row['address2'] ?></td>
                            <td><?php echo $row['city'] ?></td>
                            <td><?php echo $row['state_name'] ?></td>
                            <td><?php echo $row['zip_code'] ?></td>
                            <td><?php echo $row['telephone'] ?></td>
                            <td><?php echo $row['client_ssn'] ?></td>
                        </tr>
                       
                        <?php } ?>
                    
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

    </tbody>
    </table>
<?php  }

?>