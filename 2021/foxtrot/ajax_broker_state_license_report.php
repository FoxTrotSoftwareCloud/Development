<?php

require_once("include/config.php");
require_once(DIR_FS . "islogin.php");

$instance_branch = new branch_maintenance();
$client_maintenance_instance = new client_maintenance();
$instance = new broker_master();
$get_brokers = $instance->select_broker();

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
    $branch = isset($filter_array['branch']) ? $filter_array['branch'] : 0;
    $broker_id = isset($filter_array['broker']) ? $filter_array['broker'] : 0;
    $state = isset($filter_array['state']) ? $filter_array['state'] : 0;

    $total_records = 0;
    $total_records_sub = 0;

    function get_broker_name_only($brokerA)
    {
        return $brokerA['last_name'] . ', ' . $brokerA['first_name'];
    }

    $queried_brokers = isset($broker_id) && $broker_id != 0 ? implode(",", array_map("get_broker_name_only", array_filter($get_brokers, function ($brokerA) use ($broker_id) {
        return $brokerA['id'] == $broker_id ? true : false;
    }))) : 'All Brokers';

    $branch_name = 'All Branches';
    if (isset($branch) && $branch != 0) {
        $queried_branch = $instance_branch->select_branch_by_id($branch);
        $branch_name = $queried_branch['name'];
    }

    $state_name = 'All States';
    if (isset($state) && $state != 0) {
        $queried_state = $client_maintenance_instance->get_state_name($state);
        $state_name = $queried_state['state_name'];
    }

    $is_recrod_found = false;

    $list_data= $instance->broker_state_license_report($branch,$broker_id,$state);
    //echo "<pre>"; print_r($list_data);die;
?>
    <div class="print_section accounting">

        <table border="0" width="100%">
            <tr>
                <td width="20%" align="left"><?php echo date("m/d/Y"); ?> </td>

                <td width="60%" align="center"><?php echo $img; ?> <br />
                    <h5>BROKER STATE LICENSE REPORT </h5>
                    <h6>Branch: <?php echo $branch_name; ?> , Broker: <?php echo $queried_brokers; ?>, State: <?php echo $state_name; ?> </h6>
                    <h6>Sorted By: Branch->State->Broker</h6>
                </td>
                <td width="20%" align="right" 1>Page 1</td>
            </tr>
        </table>
        <br />

        <div class="print_content">
            <table>
                <thead>
                    <tr style="background:#f1f1f1;text-transform: capitalize;" style="width: 100%;">
                        <th style="padding: 8px 0px;font-size: 11px; width: 45%;"></th>
                        <th style="padding: 8px 0px;font-size: 12px; width: 55%;">TYPE </th>
                    </tr>
                </thead>
                <tbody>
                    <?php

                    foreach ($list_data as $row) {
                       // if ($row['appointments'] != array()) {
                            $is_recrod_found = true; ?>
                            <tr>
                                <td colspan="6" style="text-align: left;font-size: 12px;font-weight: bold;">
                                    <?php echo $row['name'] ?>
                                </td>
                            </tr>
                            <?php 
                            // foreach ($row['appointments'] as $appointment) {
                                // $broker_name = $appointment['broker_last_name'] . ", " . $appointment['broker_first_name'];
                             ?>
                                <!-- <tr style="height:30px ;">
                                    <td><?php echo $appointment['alias_name'] ?></td>
                                    <td><?php echo (isset($appointment['date']) && $appointment['date'] != '0000-00-00') ? date('m/d/Y', strtotime($appointment['date'])) : ''; ?></td>
                                    <td><?php if ($group_by == 'broker') {
                                            echo $appointment['sponsor_name'];
                                        } else {
                                            echo $broker_name;
                                        } ?></td>
                                    <td><?php echo $appointment['state_name'] ?></td>
                                    <td><?php echo (isset($appointment['termdate']) && $appointment['termdate'] != '0000-00-00') ? date('m/d/Y', strtotime($appointment['termdate'])) : ''; ?></td>
                                    <td></td>
                                </tr> -->

                                <?php 
                            // }
                      //  }
                    } ?>

                    <?php
                    if ($is_recrod_found == false) { ?>
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