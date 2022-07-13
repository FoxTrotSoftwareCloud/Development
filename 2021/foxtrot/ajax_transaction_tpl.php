<?php
// 05/03/22 Renamed from "ajax_hold_commissions.php - renamed to make more sense
// * Called from transactions.tpl.php
require_once("include/config.php");
require_once(DIR_FS."islogin.php");

if(
   (isset($_GET['broker_id']) && $_GET['broker_id'] != '')
   || (isset($_GET['action']) && $_GET['action']=='branch_company' && !empty($_GET['branch']))
){
   if(isset($_GET['action']) && $_GET['action'] == 'split_commission'){
      //---------------------------------//
      //-- Create SPLIT REP/RATE Modal --//
      //-- 07/11/22 Don't need dates for trades
      //---------------------------------//
      $broker_class = new broker_master();
      $payroll_class = new payroll();
      $ruleEngine_class = new rules();
      $select_broker =$broker_class->select_broker();
      $product_category = $broker_class->select_category();
      $doc_id2 = $validTradeOrDate = 0;
      $transaction_id = ( empty($_GET['transaction_id']) ? 0 : (int)$dbins->re_db_input($_GET['transaction_id']) );
      $broker_id = ( empty($_GET['broker_id']) ? 0 : (int)$dbins->re_db_input($_GET['broker_id']) );
      $trade_date = ( empty($_GET['trade_date']) ? '' : $dbins->re_db_input($_GET['trade_date']) );
      
      if($transaction_id) {
      	$edit_split = $payroll_class->select_trade_splits($transaction_id);
      } else {
         $edit_split = $broker_class->edit_split($broker_id);
      }

      foreach($edit_split as $editSplitKey=>$editSplitVal){  
         if ($broker_id > 0){
            $splitBroker = $editSplitVal['rap'];
            $splitRate = $editSplitVal['rate'];
            // Check Rep's Split Start/Until parameters
            $validTradeOrDate = $ruleEngine_class->check_date($trade_date, $editSplitVal['start'], $editSplitVal['until']);
         } else {
            $splitBroker = $editSplitVal['split_broker'];
            $splitRate = $editSplitVal['split_rate'];
            $validTradeOrDate = 1;
         }
         
         if ($validTradeOrDate) {
            $doc_id2++; 
      ?>
            <tr class="tr" data-rowid="<?php  echo $doc_id2 ?>">
               <!-- Column 1: Split Receiving Rep -->
               <td>
                  <input type="hidden" name="split[]" value="<?php echo $transaction_id.'@/@'.$broker_id; ?>" />
                  <select name="split_rep[]"  class="form-control" style="padding-right: 30px;">
                     <option value="">Select Broker</option>
                     <?php foreach($select_broker as $key => $val) {
                        if($val['id'] != $broker_id){?>
                           <option <?php if($splitBroker==$val['id']) {?>selected<?php } ?> value="<?php echo $val['id']?>"><?php echo strtoupper($val['last_name'].(($val['last_name']=='' || $val['first_name']=='') ? "" : ", ").$val['first_name']) ?></option>
                        <?php } } ?>
                  </select>
               </td>
               <!-- 2: Split Rate -->
               <td>
                  <div class="input-group">
                     <input type="number" name="split_rate[]" step="0.01" onchange="handleChange(this);" value="<?php echo $splitRate;?>" class="form-control" /><span class="input-group-addon">%</span>
                  </div>
               </td>
               <!-- 07/11/22 Start/Until not needed for trades -->
               <!-- 3: Starting Date -->
               <!-- <td>
                  <div id="demo-dp-range">
                     <div class="input-daterange input-group" id="datepicker">
                        <input type="text" name="split_start[]" value="<?php echo date('m/d/Y',strtotime($editSplitVal['start'])); ?>" class="form-control"  />
                        <label class="input-group-addon btn" for="split[from1][<?php echo $doc_id2;?>]">
                           <span class="fa fa-calendar"></span>
                        </label>
                     </div>
                  </div>
               </td> -->
               <!-- 4: Until Date -->
               <!-- <td>
                  <div id="demo-dp-range">
                     <div class="input-daterange input-group" id="datepicker">
                        <input type="text" name="split_until[]" value="<?php echo date('m/d/Y',strtotime($editSplitVal['until'])); ?>" class="form-control"  />
                        <label class="input-group-addon btn" for="split[to1][<?php echo $doc_id2;?>]">
                           <span class="fa fa-calendar"></span>
                        </label>
                     </div>
                  </div>
               </td> -->
               <td>
                  <button type="button" tabindex="-1" class="btn remove-row btn-icon btn-circle"><i class="fa fa-minus"></i></button>
               </td>
            </tr>
            <?php } //if ($validTradeOrDate) 
      } // foreach
      $doc_id2++ ; 
      ?>

      <tr id="add_split_row" name="add_split_row">
            <!-- Add/Blank 1: Receiving Rep -->
         <td>
            <select id="add_split_rep" name="split_rep[]"  class="form-control">
               <option value="">Select Broker</option>
               <?php foreach($select_broker as $key => $val) {
                  if($val['id'] != $broker_id){?>
               <option value="<?php echo $val['id']?>"><?php echo strtoupper($val['last_name'].(($val['last_name']=='' || $val['first_name']=='') ? "" : ", ").$val['first_name']) ?></option>
               <?php } } ?>
            </select>
         </td>
         <!-- Add/Blank 2: Split Rate -->
         <td>
            <div class="input-group">
               <input type="number" name="split_rate[]" id="add_split_rate" onchange="handleChange(this);" step="0.01" value="" class="form-control" /><span class="input-group-addon">%</span>
            </div>
         </td>
         <!-- Add/Blank 3: Start Date -->
         <!-- <td>
            <div id="demo-dp-range">
               <div class="input-daterange input-group" id="datepicker">
                  <input type="text" name="split_start[]"  id="add_split_start" class="form-control"  />
                  <label class="input-group-addon btn" for="split">
                  <span class="fa fa-calendar"></span>
                  </label>
               </div>
            </div>
         </td> -->
         <!-- Add/Blank 4: Until Date -->
         <!-- <td>
            <div id="demo-dp-range">
               <div class="input-daterange input-group" id="datepicker">
                  <input type="text" name="split_until[]"  id="add_split_until" class="form-control"  />
                  <label class="input-group-addon btn" for="split">
                  <span class="fa fa-calendar"></span>
                  </label>
               </div>
            </div>
         </td> -->
         <td>
            <button type="button" onclick="add_split_row(<?php echo $doc_id2;?>);" class="btn btn-purple btn-icon btn-circle"><i class="fa fa-plus"></i></button>
         </td>
      </tr>
   <?php
   //--- onChange(branch) -> Update Company dropdown
   } else if(isset($_GET['action']) AND $_GET['action']=='branch_company' AND !empty($_GET['branch'])) {
      $return = ["id"=>0, "company"=>0];
      $instance_branch = new branch_maintenance();
      $branchData = $instance_branch->select_branch_by_id((int)$_GET['branch']);

      if (!empty($branchData)){
         $return = ["id"=>(int)$branchData['id'],"company"=>(int)$branchData['company']];
      }
      echo json_encode($return);

   //--- Hold Commissions & Branch/Company return
   } else {
      $return = ['hold_commission'=>'', 'broker_id'=>$_GET['broker_id'], 'branch'=>0, 'company'=>0];
      $broker_class = new broker_master();
      $check_broker_commission = $broker_class->check_broker_commission_status($_GET['broker_id']);
      if (count($check_broker_commission)){
         $return['hold_commission'] = $check_broker_commission['hold_commissions'];
      }

      // 05/02/22 Branch & company array
      $instance_broker_master = new broker_master();
      $get_broker =$instance_broker_master->select_broker_by_branch_company($_GET['broker_id']);
      if (count($get_broker) AND (int)$get_broker[0]['branch_id1']!=0){
         $return['branch'] = (int)$get_broker[0]['branch_id1'];
      }
      if (count($get_broker) AND (int)$get_broker[0]['company_id1']!=0){
         $return['company'] = (int)$get_broker[0]['company_id1'];
      }

      echo json_encode($return);
    }

} else if(isset($_POST['addProdFromTrans'])) {
   $_SESSION['addProdFromTrans'] = [];
   
   foreach ($_POST['addProdFromTrans'] AS $row){
      $_SESSION['addProdFromTrans'][$row['name']] = $row['value'];   
   }
   
   $x = 0;
}
?>