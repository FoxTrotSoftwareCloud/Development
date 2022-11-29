<?php

require_once("include/config.php");
require_once(DIR_FS . "islogin.php");
$instance = new broker_master();
$get_brokers = $instance->select_broker();
$instance_sponsor = new manage_sponsor();

$filter_array = array();
$beginning_date = '';
$ending_date = '';


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
    $broker_id = isset($filter_array['broker']) ? $filter_array['broker'] : 0;

    $total_records = 0;
    $total_records_sub = 0;

    function get_broker_name_only($brokerA)
    {
        return $brokerA['first_name'] . ' ' . $brokerA['last_name'];
    }
    
    $queried_brokers = isset($broker_id) && $broker_id != 0 ? implode(",", array_map("get_broker_name_only", array_filter($get_brokers, function ($brokerA) use ($broker_id) {
        return $brokerA['id'] == $broker_id ? true : false;
    }))) : 'All Brokers';


    $sponsor_name='All Sponsors';
    if(isset($sponsor_id) && $sponsor_id != 0){
        $queried_sponsor=$instance_sponsor->select_sponsor_by_id($sponsor_id);
        $sponsor_name=$queried_sponsor['name'];
    }
   
        $is_recrod_found = false;

        $list_data= $instance->broker_sponsor_appointment_report($broker_id,$sponsor_id);
        //echo "<pre>"; print_r($list_data);
        ?>
        <div class="print_section accounting">

            <table border="0" width="100%">
                <tr>
                    <td width="20%" align="left"><?php echo date("m/d/Y"); ?> </td>

                    <td width="60%" align="center"><?php echo $img; ?> <br />
                        <h5>BROKER SPONSOR APPOINTMENTS LISTING REPORT </h5>
                        <h6>Broker: <?php echo $queried_brokers; ?>, Sponsor: <?php echo $sponsor_name; ?>  </h6>
                    </td>
                    <td width="20%" align="right" 1>Page 1</td>
                </tr>
            </table>
            <br />
          
            <div class="print_content">
                <table>
                    <thead>
                        <tr style="background:#f1f1f1;text-transform: capitalize;" style="width: 100%;">
                            <th style="font-size: 12px; width: 20%;">APPT #</th>
                            <th style="padding: 8px 0px;font-size: 12px; width: 10%;">DATE</th>
                            <th style="padding: 8px 0px;font-size: 12px; width: 30%;" >COMPANY</th>
                            <th style="padding: 8px 0px;font-size: 12px; width: 10%;" >STATE</th>
                            <th style="padding: 8px 0px;font-size: 12px; width: 10%;" >TERM DATE</th>
                            <th style="padding: 8px 0px;font-size: 12px; width: 20%;">REP #</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                        $total_amount=0.00;
                        $total_comm_rec= 0.00;
                        foreach($list_data as $row){
                            $is_recrod_found=true;?>
                        <tr style="height:30px ;">
                            <td><?php echo $row['alias_name'] ?></td>
                            <td><?php echo (isset($row['date']) && $row['date']!= '0000-00-00')? date('m/d/Y',strtotime($row['date'])):''; ?></td>
                            <td><?php echo $row['sponsor_name']?></td>
                            <td><?php echo $row['state_name']?></td>
                            <td><?php echo (isset($row['termdate']) && $row['termdate']!= '0000-00-00')? date('m/d/Y',strtotime($row['termdate'])):''; ?></td>
                            <td><?php echo $row['broker_last_name'].", ".$row['broker_first_name'] ?></td>
                        </tr>
                       
                        <?php 
                            // $total_amount += $trade['invest_amount'];
                            // $total_comm_rec += $trade['commission_received'];
                        } ?>
                    
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