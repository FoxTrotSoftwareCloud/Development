<?php
require_once("include/config.php");
require_once(DIR_FS."islogin.php");
$instance = new client_review();
$return_broker_list = array();
$return_client_accounts = array();
$filter_array = array();
$product_category = '';
$product_category_name = '';
$beginning_date = '';
$ending_date = '';
$client_maintenance_instance = new client_maintenance();
$get_states = $client_maintenance_instance->select_state();
$instance_broker = new broker_master();
$get_brokers=$instance_broker->select_broker();
$client_sponsor_instance = new manage_sponsor();
$get_sponsors = $client_sponsor_instance->select_sponsor();
//DEFAULT PDF DATA:
$get_logo = $instance->get_system_logo();
$system_logo = isset($get_logo['logo'])?$instance->re_db_input($get_logo['logo']):'';
$get_company_name = $instance->get_company_name();
$system_company_name = isset($get_company_name['company_name'])?$instance->re_db_input($get_company_name['company_name']):'';
$img = '<img src="'.SITE_URL."upload/logo/".$system_logo.'" height="25px" />';

//filter batch report
if(isset($_GET['filter']) && $_GET['filter'] != '')
{
    $filter_array = json_decode($_GET['filter'],true);
    $state_id = isset($filter_array['state'])?$filter_array['state']:0;
    $broker_id = isset($filter_array['broker'])?$filter_array['broker']:0;
    $sponsor_id = isset($filter_array['sponsor'])?$filter_array['sponsor']:'';
    $report_for = isset($filter_array['report_for'])?trim($filter_array['report_for']):'';
    $beginning_date = isset($filter_array['beginning_date']) && !empty($filter_array['beginning_date']) ?date('Y-m-d H:i:s',strtotime($instance->re_db_input($filter_array['beginning_date']))):'';
    $ending_date = isset($filter_array['ending_date']) && !empty($filter_array['ending_date']) ? date('Y-m-d',strtotime($instance->re_db_input($filter_array['ending_date']))):'';

    $dont_contact_client =  isset($filter_array['dont_contact_client'])?$filter_array['dont_contact_client']:0;
    $total_records=0;
    $total_records_sub=0;
    
    function get_broker_name_only($brokerA) {
        return $brokerA['first_name'].' '.$brokerA['last_name'];
    }
    $queried_brokers=isset($broker_id) && $broker_id!=0 ? implode(",",array_map("get_broker_name_only",array_filter($get_brokers,function ($brokerA) use ($broker_id){return $brokerA['id']==$broker_id ? true :false;}))) : 'All Brokers';

    function get_sponsor_name_only($sponsorA) {
        return $sponsorA['name'];
    }
    function get_state_name($stateA) {
        return $stateA['id']==$state_id ? true :false;
    }
    function get_name_only($stateA) {
        return $stateA['name'];
    }
    function get_short_name_only($stateA) {
        return $stateA['short_name'];
    }
    if($report_for==1)
    {
    $is_recrod_found=false;
        $return_client_accounts= $instance->get_client_account_list($sponsor_id,$broker_id);
       /* echo "<pre>";
        print_r($return_client_accounts);die;*/
        $queried_sponsors=isset($sponsor_id) && $sponsor_id!=0 ? implode(",",array_map("get_sponsor_name_only",array_filter($get_sponsors,function ($sponsorA) use ($sponsor_id){return $sponsorA['id']==$sponsor_id ? true :false;}))) : 'All Sponsors';
        ?>
       <div class="print_section accounting">


         <table border="0" width="100%">
                    <tr>
                         <td width="20%" align="left"><?php echo date("m/d/Y"); ?> </td>

                        <td width="60%" align="center"><?php echo $img; ?> <br/>
                            <h5>CLIENT ACCOUNT LISTING REPORT </h5> 
                            <h6>Broker: <?php echo $queried_brokers; ?>, Sponsor: <?php echo $queried_sponsors; ?></h6> 
                        </td>
                         <td width="20%" align="right" 1>Page 1</td>
                    </tr>    
                    
            </table>
            <br />
<!-- 
        <div class="print_head">
            <div class="date">
                <?php echo  $img; ?>
            </div>
            <div class="main_title">
                <p style="display:block;">  
                    <?php echo $system_company_name; ?>                  
                    <span style="margin-top: 0px;"></span>
                </p>
            </div>
            <div class="page_no">
                <p><?php echo date('m/d/Y H:i:s'); ?></p>
            </div>
        </div> -->
        <div class="print_content">
            <table>
                <thead>
                    <tr style="background:#f1f1f1;text-transform: capitalize;">
                        <th style="padding: 8px 5px;font-size: 12px;">CLIENT NAME</th>
                        <th style="padding: 8px 5px;font-size: 12px;">ACCOUNT NO.</th>
                        <th style="padding: 8px 5px;font-size: 12px;">COMPANY</th>
                        <th> </th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $last_record=0;
                    foreach($return_client_accounts as $broker):
                        $is_recrod_found=true;
                        // $is_middle_name=(!empty($client['mi'])) ? ' '.substr($client['mi'], 0,1).'.' :'';
                     ?>
                    <tr>
                        <td colspan="4" style="text-align: left;font-size: 12px;font-weight: bold;">
                            Broker: <?php echo $broker['lfname'].',&nbsp;'.$broker['bfname'].'&nbsp;&nbsp;('.$broker['broker_fund'].')'; ?>
                        </td>
                    </tr>
                        <?php foreach($broker['clients'] as $client):
                                $is_middle_name=(!empty($client['mi'])) ? ' '.substr($client['mi'], 0,1).'.' :'';
                        ?>
                            <tr>
                                <td colspan="2" style="font-size: 12px;" > 
                                    <?php echo $client['last_name'].',&nbsp;'.$client['first_name']; ?>
                                </td>
                                <td colspan="2" style="padding-left:70px;font-size: 12px;">
                                    <?php echo "Client # ".$client['client_ssn']. '&nbsp;&nbsp;&nbsp; Clearing Account # '.$client['clearing_account']; ?>
                                </td>
                            </tr>
                             
                         
                    <?php 
                    if(!empty($client['client_accounts']) || !empty($client['clearing_account'])): ?>
                        <?php foreach($client['client_accounts'] as $client_account):
                            
                         ?>
                    <tr>
                        <td></td>
                        <td style="padding-left:10px;font-size: 12px;"><?php echo $client_account['account_no']; ?></td>
                        <td  style="padding-left:10px;font-size: 12px;"><?php echo $client_account['sponsor_name']; ?></td>
                        <td></td>
                    </tr>
                    <?php endforeach; endif; endforeach;?>
                   
                    <?php
                    
            endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
            <?php } 
            
      else if($report_for==2)
      {
            $return_broker_list = $instance->get_client_report($state_id,$broker_id);
            /*echo "<pre>";
            print_r($return_broker_list);die;*/
            
            // $queried_states=isset($state_id) && $state_id!=0 ? implode(",",array_map("get_name_only",array_filter($get_states,"get_state_name"))) : 'ALL';

            $queried_states=isset($state_id) && $state_id!=0 ? implode(",",array_map("get_name_only",array_filter($get_states,function ($stateA) use ($state_id){return $stateA['id']==$state_id ? true :false;}))) : 'All';    
            ?>


    <div class="print_section">

             <table border="0" width="100%">
                    <tr>
                         <td width="20%" align="left"><?php echo date("m/d/Y"); ?> </td>

                        <td width="60%" align="center"><?php echo $img; ?> <br/>
                            <h5> CLIENT LIST BY STATE  </h5> 
                            <h6>State: <?php echo $queried_states; ?>, Broker: <?php echo $queried_brokers; ?></h6> 
                        </td>
                         <td width="20%" align="right" 1>Page 1</td>
                    </tr>    
                    
            </table>
            <br />

     <!--    <div class="print_head">
            <div class="date">
                <?php echo $img; ?>                
            </div>
            <div class="main_title">
                <p>
                    Client List By State 
                </p>
                <span>State: <p style="text-transform: uppercase;padding-left: 6px;"><?php echo $queried_states; ?></p></span>
            </div>
            <div class="page_no"><p><?php echo date('m/d/Y H:i:s'); ?></p></div>
        </div> -->
        <div class="print_content">
            <table>
                <thead>
                    <tr style="background: #f1f1f1;text-transform: capitalize;">
                        <th>Client Name</th>
                        <th>Client No.</th>
                        <th>Telephone</th>
                        <th>Open Date</th>
                        <th>Birth Date</th>
                        <th>Address</th>
                    </tr>                    
                </thead>
                <tbody>
                    <?php 
                    $last_record=0;
                    $is_recrod_found=false;
                    foreach($return_broker_list as $broker):
                     if(!empty($broker['clients'])): 
                     $is_recrod_found=true;  
                     ?>
                    <tr>
                        <td colspan="6" style="text-align: left;">
                            <h6>Broker: <?php echo $broker['lfname'].',&nbsp;&nbsp;'.$broker['bfname'].'&nbsp;&nbsp;('.$broker['broker_fund'].')'; ?></h6>
                            <?php foreach($broker['clients'] as $client):
                                $is_middle_name=(!empty($client['mi'])) ? ' '.substr($client['mi'], 0,1).'.' :'';
                                $state_name=implode(" ",array_map("get_short_name_only",array_filter($get_states,function ($stateA) use ($client){return $stateA['id']==$client['state'] ? true :false;})));
                                
                                $address = !empty($client['address1']) ? $client['address1'].', ' : '';
                                $address.= !empty($client['city']) ? $client['city'].', ' : '';
                                $address.= !empty($client['state']) ? $state_name.' ' : '';
                                $address.= !empty($client['zip_code']) ? $client['zip_code'].' ' : '';

                                ?>
                            <tr>
                                <td style="text-transform:none;">
                                    <?php echo $client['last_name'].', '.$client['first_name'].$is_middle_name; ?>

                                </td>
                                <td><?php echo $client['client_file_number']; ?></td>
                                <td><?php
                                echo $client['telephone']!="" ? sprintf("(%s) %s-%s",
                                  substr($client['telephone'], 0, 3),
                                  substr($client['telephone'], 3, 3),
                                  substr($client['telephone'], 6)) : '';
                            ?></td>
                                <td><?php echo (!empty($client['open_date'])) ? date('m/d/Y',strtotime($client['open_date'])) :'/'; ?></td>
                                <td><?php echo (!empty($client['birth_date'])) ? date('m/d/Y',strtotime($client['birth_date'])) : ''; ?></td>
                                <td style="text-transform:none;"> <?php
                                 
                                echo $address; 
                            ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </td>
                    </tr>
                    <?php
                    endif; 
            endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
            <?php }

            elseif ($report_for==3) {
                ?>

                  

    <div class="client_review_report_section">
      <table border="0" width="100%">
                    <tr>
                         <td width="20%" align="left"><?php echo date("m/d/Y"); ?> </td>

                         <td width="60%" align="center"><?php echo $img; ?> <br/>
                          <strong><h5> CLIENT REVIEW REPORT </h5> <h6><?php
                    if (isset($filter_array['beginning_date']) && !empty($filter_array['beginning_date']) && isset($filter_array['ending_date']) && !empty($filter_array['ending_date'])) {
                        echo date('m/d/Y',strtotime($filter_array['beginning_date'])).'&nbsp; through &nbsp;'.date('m/d/Y',strtotime($filter_array['ending_date'])) ;                        
                    }
                    else {
                        echo date('1/01/1970').'&nbsp; through &nbsp;'.date('m/d/Y');
                    }
                    ?></h6> </td>
                         <td width="20%" align="right" 1>Page 1</td>
                    </tr>    
                    
            </table>
            <br />
        <div class="client_review_report_content">
            <table>
                <thead>
                    <tr>
                        <th style="text-transform: uppercase;">Client Name</th>
                        <th style="text-transform: uppercase;">Account No.</th>
                        <th  style="text-transform: uppercase;">Client No.</th>
                        <th style="text-transform: uppercase;">Telephone</th>
                        <th style="text-transform: uppercase;">Review Date</th>
                        <th style="text-transform: uppercase;">Birth Date</th>
                        <th style="text-transform: uppercase;">Address</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $last_record=0;
                    $is_recrod_found=false;
                     $return_client_review_list = $instance->get_client_review_report($broker_id,$beginning_date,$ending_date,$dont_contact_client);
                    /*echo "<pre>";
                    print_r($return_client_review_list);die;*/
                    foreach($return_client_review_list as $client_review):
                    $is_recrod_found=true;
                    if(!empty($client_review['client_accounts'])):
                    $current_client_ac_list=array();
                    $current_client_ac_list_count=0;
                    $supervisor_shortname=substr($client_review['bfname'], 0,1).substr($client_review['lfname'], 0,1);  
                    ?>
                    <tr>
                        <td colspan="7">
                            <p class="supervisior"  style="text-transform: uppercase;">
                                REVIEWING SUPERVISOR: <?php echo $client_review['bfname'].'&nbsp;'.$client_review['lfname'].'&nbsp;/&nbsp;'.$supervisor_shortname.'&nbsp;('.$client_review['broker_fund'].')'; ?>
                            </p>
                            <table>
                                <?php 
                                
                                   foreach($client_review['client_accounts'] as $client_account):
                                   $current_client=$client_account['client_data'][$client_review['broker_id']][0];
                                   $current_client_ac_list_count+=count($client_account['client_data'][$client_review['broker_id']]['account_list']);
                                   $current_client_ac_list=$client_account['client_data'][$client_review['broker_id']]['account_list'];
                                   $current_client_sponsor_list=$client_account['client_data'][$client_review['broker_id']]['client_sponsor'];
                                   $is_middle_name=(!empty($current_client['mi'])) ? ' '.substr($current_client['mi'], 0,1).'.' :''; 
                                ?>
                                    <tr>
                                        <td colspan="2" style="text-transform: none;"><?php echo $current_client['last_name'].',&nbsp;'.$current_client['first_name'];  ?></td>
                                        <td style="width: 11%;"><?php echo $current_client['client_file_number']; ?></td>
                                        <td style="width: 11%;"><?php
                                            echo $current_client['telephone']!="" ? sprintf("(%s) %s-%s",
                                              substr($current_client['telephone'], 0, 3),
                                              substr($current_client['telephone'], 3, 3),
                                              substr($current_client['telephone'], 6)) : '';
                                        ?></td>
                                        <td style="width: 11%;"><?php echo (!empty($current_client['reviewed_at'])) ? date('m/d/Y',strtotime($current_client['reviewed_at'])).'&nbsp;'.$supervisor_shortname :'/'; ?></td>
                                        <td style="width: 11%;"><?php echo (!empty($current_client['birth_date'])) ? date('m/d/Y',strtotime($current_client['birth_date'])) : ''; ?></td>
                                        <td style="width: 34%;"><?php
                                         $state_name=implode(" ",array_map("get_short_name_only",array_filter($get_states,function ($stateA) use ($client){return $stateA['id']==$current_client['state'] ? true :false;})));
                                        echo $current_client['address1'].', '.$current_client['city'].', '.$state_name.' '.$current_client['zip_code']; 
                                    ?></td>
                                    </tr>
                                    <?php if(count($current_client_ac_list) > 0):
                                       $sponsor_key=0;
                                       foreach($current_client_ac_list as $ac_no=>$ac_list):
                                       $sponsor_name=implode(",",array_map("get_sponsor_name_only",array_filter($get_sponsors,function ($sponsorA) use ($current_client_sponsor_list,$sponsor_key){return $sponsorA['id']==$current_client_sponsor_list[$sponsor_key] ? true :false;})));
                                       $sponsor_key++;
                                    ?>
                                    <tr>
                                        <td style="width:11%;"></td>
                                        <td colspan="2" style="background: #f1f1f1;font-size: 10px;"><?php echo $ac_no; ?></td>
                                        <td style="background: #f1f1f1;font-size: 10px;"><?php echo date('m/d/Y',strtotime($ac_list)); ?></td>
                                        <td colspan="2" style="background: #f1f1f1;font-size: 10px;"><?php echo $sponsor_name; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php endif;?>
                                <?php endforeach; ?>

                            
                            </table>
                        </td>
                    </tr>
                    <?php if($current_client_ac_list_count > 0): ?>
                    <tr>
                        <td colspan="5">
                            <hr style="height: 1px;background: #555;margin: 0;">
                            <p style="text-transform: uppercase;font-weight: bold;font-size: 12px;">REVIEWING SUPERVISOR: <?php echo $client_review['bfname'].'&nbsp;'.$client_review['lfname'].'&nbsp;/&nbsp;'.$supervisor_shortname; ?> TOTAL: <?php echo $current_client_ac_list_count; ?></p>
                        </td>
                    </tr>
                    <?php endif; ?>
                    <?php
                endif;
                endforeach;
                     ?>
                </tbody>    
            </table>
        </div>
    </div>                
            <?php    
            }

            if($is_recrod_found==false)
            {?>
                <tr>
                   <td style="font-size:10px;font-weight:normal;text-align:left;" colspan="8">No Records Found.</td>
                </tr>
            <?php } ?>           
            </tbody>
    </table>       
<?php  }

?>
       