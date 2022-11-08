<?php
require_once("include/config.php");
require_once(DIR_FS . "islogin.php");
$instance = new client_review();
$return_client_accounts = array();
$filter_array = array();
$product_category = '';
$product_category_name = '';
$beginning_date = '';
$ending_date = '';

$client_sponsor_instance = new manage_sponsor();
$get_sponsors = $client_sponsor_instance->select_sponsor();

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
    // $state_id = isset($filter_array['state'])?$filter_array['state']:0;
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
        $return_client_accounts = $instance->get_client_account_list($sponsor_id, $broker_id);


        $trade_data= $instance_trans->daily_trade_blotter_report($company_id,$branch_id,$broker_id,$beginning_date,$ending_date);
        // echo "<pre>"; print_r($trade_data);die;
    
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
                        <tr style="background:#f1f1f1;text-transform: capitalize;">
                            <th style="font-size: 12px;">TRADE#</th>
                            <th style="padding: 8px 3px;font-size: 12px;">BRANCH</th>
                            <th style="padding: 8px 3px;font-size: 12px;">DATE</th>
                            <th style="padding: 8px 3px;font-size: 12px;">CHECK DATE</th>
                            <th style="padding: 8px 3px;font-size: 12px;">CLIENT</th>
                            <th style="padding: 8px 3px;font-size: 12px;">BROKER</th>
                            <th style="padding: 8px 3px;font-size: 12px;">SPONSOR</th>
                            <th style="padding: 8px 3px;font-size: 12px;">PRODUCT</th>
                            <th style="padding: 8px 3px;font-size: 12px;">AMOUNT</th>
                            <th style="padding: 8px 3px;font-size: 12px;">COMM.EXP/COMM.REC</th>
                            <th style="padding: 8px 3px;font-size: 12px;">DATE REC,DATE PAID</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>111456</td>
                            <td>111</td>
                            <td>111</td>
                            <td>111</td>
                            <td>111</td>
                            <td>111</td>
                            <td>111</td>
                            <td>111</td>
                            <td>111</td>
                            <td>111</td>
                            <td>111</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    <?php }  ?>
    </tbody>
    </table>
<?php  }

?>