<?php
    require_once("include/config.php");
    $instance = new client_ress();
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
<table id="data-table" class="table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
        <tr style="background-color: #f1f1f1;">
            <td><h4>NO#</h4></td>
            <td><h4>CLIENT NAME</h4></td>
            <td><h4>FROM BROKER</h4></td>
            <td><h4>TO BROKER</h4></td>
            <td><h4>REASSIGMENT DATE </h4></td>
        </tr>
    </thead>
    <tbody>
    <?php 
    $count=0;
    if($get_client_ress_data != array())
        {
            foreach($get_client_ress_data as $trans_key=>$trans_data)
            { $count++; 
            
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
               <td style="font-size:13px;font-weight:normal;text-align:left;"><?php echo $count; ?></td>
               <td style="font-size:13px;font-weight:normal;text-align:left;"><?php echo $trans_data['first_name']; ?></td>
               <td style="font-size:13px;font-weight:normal;text-align:left;"><?php echo $old_broker; ?></td>
               <td style="font-size:13px;font-weight:normal;text-align:left;"><?php echo $new_broker; ?></td>
               <td style="font-size:13px;font-weight:normal;text-align:left;"><?php echo date('m-d-Y',strtotime($trans_data['ressign_date'])); ?></td>
        </tr>
        <?php }
         
        }
        else
        {?>
        
        <tr>
            <td style="font-size:13px;font-weight:cold;text-align:center;" colspan="8">No Records Found.</td>
        </tr>
        
        <?php } ?>     
        
   </tbody>
</table>
       