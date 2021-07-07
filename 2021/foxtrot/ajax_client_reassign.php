<?php
    require_once("include/config.php");
    $instance = new client_ress();
    
    //DEFAULT PDF DATA:
    $get_logo = $instance->get_system_logo();
    $system_logo = isset($get_logo['logo'])?$instance->re_db_input($get_logo['logo']):'';
    $get_company_name = $instance->get_company_name();
    $system_company_name = isset($get_company_name['company_name'])?$instance->re_db_input($get_company_name['company_name']):'';
    $img = '<img src="'.SITE_URL."upload/logo/".$system_logo.'" height="25px" />';
    $total_records=0;

    $get_client_ress_data = array();
    $get_broker = array();
    $from_broker = '';
    if(isset($_GET['from_broker']) && $_GET['from_broker'] != '')
    {
        $from_broker = $_GET['from_broker'];
        $get_client_ress_data = $instance->select_data_report($from_broker);
        $get_broker=$instance->select_broker();
    }   
    
?>
<table border="0" width="100%">
        <tr>
            <?php 
            if(isset($system_logo) && $system_logo != '')
            {?>
                <td width="20%" align="left"><?php echo $img;?></td>
            <?php } ?>
            <td width="60%" style="font-size:14px;font-weight:bold;text-align:center;">Client Reassignment List</td>
            <?php
            if(isset($system_company_name) && $system_company_name != '')
            {?>
                <td width="20%" style="font-size:10px;font-weight:bold;text-align:right;"><?php echo $system_company_name;?></td>
            <?php
            }?>
        </tr>
</table>
<br />
<table border="0" cellpadding="1" width="100%">
    <thead>
        <tr style="background-color: #f1f1f1;">
           <td style="text-align:center;"><h5>NO#</h5></td>
           <td style="text-align:center;"><h5>CLIENT NAME</h5></td>
           <td style="text-align:center;"><h5>FROM BROKER</h5></td>
           <td style="text-align:center;"><h5>TO BROKER</h5></td>
           <td style="text-align:center;"><h5>REASSIGNMENT DATE </h5></td>
        </tr>
    </thead>
    <tbody>
    <?php 
    $count=0;
    if($get_client_ress_data != array())
        {
            foreach($get_client_ress_data as $trans_key=>$trans_data)
            { $count++; 
                $total_records=$total_records+1;
                foreach($get_broker as $key=>$val){
                    if($val['id'] == $trans_data['broker_name'])
                    {
                        $new_broker=$val['first_name'].' '.$val['last_name'];
                    }
                    if($val['id'] == $trans_data['broker_old_name'])
                    {
                        $old_broker=$val['first_name'].' '.$val['last_name'];
                    }
                }?>
            <tr>
                   <td style="font-size:10px;font-weight:normal;text-align:center;"><?php echo $count; ?></td>
                   <td style="font-size:10px;font-weight:normal;text-align:center;"><?php echo $trans_data['first_name']; ?></td>
                   <td style="font-size:10px;font-weight:normal;text-align:center;"><?php echo $old_broker; ?></td>
                   <td style="font-size:10px;font-weight:normal;text-align:center;"><?php echo $new_broker; ?></td>
                   <td style="font-size:10px;font-weight:normal;text-align:center;"><?php echo date('m-d-Y',strtotime($trans_data['ressign_date'])); ?></td>
            </tr>
        <?php }?>
        <tr style="background-color: #f1f1f1;">
            <td colspan="4" style="font-size:10px;font-weight:bold;text-align:right;"></td>
            <td style="font-size:10px;font-weight:bold;text-align:center;">Total Records: <?php echo $total_records;?></td>
        </tr>
         
        <?php 
        }
        else
        {?>
        
        <tr>
            <td style="font-size:10px;font-weight:cold;text-align:center;" colspan="8">No Records Found.</td>
        </tr>
        
        <?php } ?>     
        
   </tbody>
</table>
       