<?php

class transaction extends db{
    
    public $errors = '';
    public $table = TRANSACTION_MASTER;
    
    public function insert_update($data){//echo '<pre>';print_r($data);exit;
            
			$id = isset($data['id'])?$this->re_db_input($data['id']):0;
            //$trade_number = isset($data['trade_number'])?$this->re_db_input($data['trade_number']):0;
            $client_name = isset($data['client_name'])?$this->re_db_input($data['client_name']):'0';
            $client_number = isset($data['client_number'])?$this->re_db_input($data['client_number']):'0';
            $broker_name = isset($data['broker_name'])?$this->re_db_input($data['broker_name']):'0';
            $product_cate = isset($data['product_cate'])?$this->re_db_input($data['product_cate']):'';
            $sponsor = isset($data['sponsor'])?$this->re_db_input($data['sponsor']):'';
            $product = isset($data['product'])?$this->re_db_input($data['product']):'';
            $batch = isset($data['batch'])?$this->re_db_input($data['batch']):'';
            $invest_amount = isset($data['invest_amount'])?$this->re_db_input($data['invest_amount']):0;
            $charge_amount = isset($data['charge_amount'])?$this->re_db_input($data['charge_amount']):0;
            $commission_received = isset($data['commission_received'])?$this->re_db_input($data['commission_received']):0;
            $commission_received_date = isset($data['commission_received_date'])?$this->re_db_input(date('Y-m-d',strtotime($data['commission_received_date']))):'0000-00-00';
            $posting_date = isset($data['posting_date'])?$this->re_db_input(date('Y-m-d',strtotime($data['posting_date']))):'0000-00-00';
            $trade_date = isset($data['trade_date'])?$this->re_db_input(date('Y-m-d',strtotime($data['trade_date']))):'0000-00-00';
            $settlement_date = isset($data['settlement_date'])?$this->re_db_input(date('Y-m-d',strtotime($data['settlement_date']))):'0000-00-00';
            $split = isset($data['split'])?$this->re_db_input($data['split']):'';
            /*$split_broker = isset($data['split_broker'])?$data['split_broker']:array();
            $split_rate = isset($data['split_rate'])?$data['split_rate']:array();
            $receiving_rep = isset($data['receiving_rep'])?$data['receiving_rep']:array();
            $per = isset($data['per'])?$data['per']:array();
            $split_client_id = isset($data['split_client_id'])?$data['split_client_id']:array();
            $split_broker_id = isset($data['split_broker_id'])?$data['split_broker_id']:array();*/
            $another_level = isset($data['another_level'])?$this->re_db_input($data['another_level']):'';
            $cancel = isset($data['cancel'])?$this->re_db_input($data['cancel']):'';
            $buy_sell = isset($data['buy_sell'])?$this->re_db_input($data['buy_sell']):'';
            $hold_commission = isset($data['hold_commission'])?$this->re_db_input($data['hold_commission']):'';
            $hold_resoan = isset($data['hold_resoan'])?$this->re_db_input($data['hold_resoan']):'';
            $units = isset($data['units'])?$this->re_db_input($data['units']):'';
            $shares = isset($data['shares'])?$this->re_db_input($data['shares']):'';
            
            	
            if($client_name=='0'){
				$this->errors = 'Please select client name.';
			}
            else if($broker_name=='0'){
				$this->errors = 'Please select broker name.';
			}
			else if($product_cate=='0'){
				$this->errors = 'Please select product category.';
			}
            else if($product=='0'){
				$this->errors = 'Please select product name.';
			}
            else if($batch=='0'){
				$this->errors = 'Please select batch name.';
			}
			else if($commission_received==''){
				$this->errors = 'Please enter commission received.';
			}
            else if($trade_date==''){
				$this->errors = 'Please enter trade date.';
			}
            else if($commission_received_date==''){
				$this->errors = 'Please enter commission received date.';
			}
            else if($settlement_date==''){
				$this->errors = 'Please enter settlement date.';
			}
            else if($split==''){
				$this->errors = 'Please select split commission .';
			}
            /*else if($split_rate==array()){
				$this->errors = 'Please enter split rate commission received.';
			}*/
            else if($hold_commission=='1' && $hold_resoan==''){
                $this->errors = 'Please enter commission hold resons.';
            }
			if($this->errors!=''){
				return $this->errors;
			}
			else{
			 
             $get_branch_company_detail = $this->select_branch_company_ref($broker_name);
             $branch = isset($get_branch_company_detail['branch_id'])?$get_branch_company_detail['branch_id']:'';
             $company = isset($get_branch_company_detail['company_id'])?$get_branch_company_detail['company_id']:'';
              
				if($id>=0){
					if($id==0){
						$q = "INSERT INTO ".$this->table." SET `client_name`='".$client_name."',`client_number`='".$client_number."',`broker_name`='".$broker_name."',
                        `product_cate`='".$product_cate."',`sponsor`='".$sponsor."',`product`='".$product."',`batch`='".$batch."',
                        `invest_amount`='".$invest_amount."',`commission_received_date`='".$commission_received_date."',`posting_date`='".$posting_date."',`trade_date`='".$trade_date."',`settlement_date`='".$settlement_date."',`charge_amount`='".$charge_amount."',`commission_received`='".$commission_received."',`split`='".$split."',
                        `another_level`='".$another_level."',`cancel`='".$cancel."',`buy_sell`='".$buy_sell."',
                        `hold_resoan`='".$hold_resoan."',`hold_commission`='".$hold_commission."',`units`='".$units."',`shares`='".$shares."',`branch`='".$branch."',`company`='".$company."'".$this->insert_common_sql();
						
                        $res = $this->re_db_query($q);
                        $last_inserted_id = $this->re_db_insert_id();
                        
                        /*foreach($split_rate as $key_rate=>$val_rate)
                        {
                            if($split==1 && $val_rate != '' && $split_broker[$key_rate]>0)
                            {
                				$q = "INSERT INTO `".TRANSACTION_TRADE_SPLITS."` SET `transaction_id`='".$last_inserted_id."',`split_client_id`='".$split_client_id[$key_rate]."',`split_broker_id`='".$split_broker_id[$key_rate]."',`split_broker`='".$split_broker[$key_rate]."',`split_rate`='".$val_rate."'".$this->insert_common_sql();
                				$res = $this->re_db_query($q);
                            }
                        }
                        foreach($receiving_rep as $key_override=>$val_override)
                        {
                            if($val_override != '' && $per[$key_override]>0)
                            {
                				$q = "INSERT INTO `".TRANSACTION_OVERRIDES."` SET `transaction_id`='".$last_inserted_id."',`receiving_rep`='".$val_override."',`per`='".$per[$key_override]."'".$this->insert_common_sql();
                				$res = $this->re_db_query($q);
                            }
                        }*/
                            
                        if($res){
						    $_SESSION['success'] = INSERT_MESSAGE;
							return true;
						}
						else{
							$_SESSION['warning'] = UNKWON_ERROR;
							return false;
						}
					}
					else if($id>0){
						$q = "UPDATE ".$this->table." SET `client_name`='".$client_name."',`client_number`='".$client_number."',`broker_name`='".$broker_name."',
                        `product_cate`='".$product_cate."',`sponsor`='".$sponsor."',`product`='".$product."',`batch`='".$batch."',
                        `invest_amount`='".$invest_amount."',`commission_received_date`='".$commission_received_date."',`posting_date`='".$posting_date."',`trade_date`='".$trade_date."',`settlement_date`='".$settlement_date."',`charge_amount`='".$charge_amount."',`commission_received`='".$commission_received."',`split`='".$split."',
                        `another_level`='".$another_level."',`cancel`='".$cancel."',`buy_sell`='".$buy_sell."',
                        `hold_resoan`='".$hold_resoan."',`hold_commission`='".$hold_commission."',`units`='".$units."',`shares`='".$shares."',`branch`='".$branch."',`company`='".$company."'".$this->update_common_sql()." WHERE `id`='".$id."'";
                        $res = $this->re_db_query($q);
                        
                        /*$q = "UPDATE `".TRANSACTION_TRADE_SPLITS."` SET `is_delete`='1' WHERE `transaction_id`='".$id."'";
				        $res = $this->re_db_query($q);
                        
                        foreach($split_rate as $key_rate=>$val_rate)
                        {
                            if($split==1 && $val_rate != '' && $split_broker[$key_rate]>0)
                            {
                				$q = "INSERT INTO `".TRANSACTION_TRADE_SPLITS."` SET `transaction_id`='".$id."',`split_client_id`='".$split_client_id[$key_rate]."',`split_broker_id`='".$split_broker_id[$key_rate]."',`split_broker`='".$split_broker[$key_rate]."',`split_rate`='".$val_rate."'".$this->insert_common_sql();
                				$res = $this->re_db_query($q);
                            }
                        }
                        $q = "UPDATE `".TRANSACTION_OVERRIDES."` SET `is_delete`='1' WHERE `transaction_id`='".$id."'";
				        $res = $this->re_db_query($q);
                        foreach($receiving_rep as $key_override=>$val_override)
                        {
                            if($val_override != '' && $per[$key_override]>0)
                            {
                				$q = "INSERT INTO `".TRANSACTION_OVERRIDES."` SET `transaction_id`='".$id."',`receiving_rep`='".$val_override."',`per`='".$per[$key_override]."'".$this->insert_common_sql();
                				$res = $this->re_db_query($q);
                            }
                        }*/
                            
                        if($res){
						    $_SESSION['success'] = UPDATE_MESSAGE;
							return true;
						}
						else{
							$_SESSION['warning'] = UNKWON_ERROR;
							return false;
						}
					}
				}
				else{
					$_SESSION['warning'] = UNKWON_ERROR;
					return false;
				}
			}
		}
        public function edit_transaction($id){
			$return = array();
			$q = "SELECT `at`.*
					FROM ".$this->table." AS `at`
                    WHERE `at`.`is_delete`='0' AND `at`.`id`='".$id."'";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
    			$return = $this->re_db_fetch_array($res);
            }
			return $return;
		}
        public function get_batch_date($batch_id){
			$return = array();
			
			$q = "SELECT batch_date
					FROM `".BATCH_MASTER."`
                    WHERE is_delete='0' and id =".$batch_id."
                    ";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                $return = $this->re_db_fetch_array($res);
            }
			return $return;
		}
        public function delete($id){
			$id = trim($this->re_db_input($id));
			if($id>0 && ($status==0 || $status==1) ){
				$q = "UPDATE `".$this->table."` SET `is_delete`='1' WHERE `id`='".$id."'";
				$res = $this->re_db_query($q);
				if($res){
				    $_SESSION['success'] = DELETE_MESSAGE;
					return true;
				}
				else{
				    $_SESSION['warning'] = UNKWON_ERROR;
					return false;
				}
			}
			else{
			     $_SESSION['warning'] = UNKWON_ERROR;
				return false;
			}
		}
        public function edit_splits($id){
			$return = array();
			$q = "SELECT `at`.*
					FROM `".TRANSACTION_TRADE_SPLITS."` AS `at`
                    WHERE `at`.`is_delete`='0' AND `at`.`transaction_id`='".$id."'";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
    			while($row = $this->re_db_fetch_array($res)){
    			     //print_r($row);exit;
                     array_push($return,$row);
                     
    			}
            }
			return $return;
		}
        public function edit_overrides($id){
			$return = array();
			$q = "SELECT `at`.*
					FROM `".TRANSACTION_OVERRIDES."` AS `at`
                    WHERE `at`.`is_delete`='0' AND `at`.`transaction_id`='".$id."'";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
    			while($row = $this->re_db_fetch_array($res)){
    			     //print_r($row);exit;
                     array_push($return,$row);
                     
    			}
            }
			return $return;
		}
        public function search_transcation($data){
            //echo '<pre>';print_r($data);exit;
            $search_type= isset($data['search_type'])?$this->re_db_input($data['search_type']):'';
            $search_text_batches= isset($data['search_text'])?$this->re_db_input($data['search_text']):'';
            
			$return = array();
			if($search_type==''){
				$this->errors = 'Please select search type.';
			}
            if($search_type=='client_name' ){
                $q = "SELECT `at`.*
					FROM `".$this->table."` AS `at`
                    WHERE `".$search_type."` in (SELECT `id` FROM ".CLIENT_MASTER." where `mi` like '".$search_text_batches."%' )   and `at`.`is_delete`='0'
                    ORDER BY `at`.`id` ASC";
            }
            else if($search_type=='id' || $search_type=='trade_date' || $search_type=='commission_received' || $search_type=='client_number'){
                $q = "SELECT `at`.*
					FROM `".$this->table."` AS `at`
                    WHERE `".$search_type."`like'".$search_text_batches."%' and `at`.`is_delete`='0'
                    ORDER BY `at`.`id` ASC";
            }
            else if($search_type=='batch'){
                $q = "SELECT `at`.*
					FROM `".$this->table."` AS `at`
                    WHERE `".$search_type."` in (SELECT `id` FROM ".BATCH_MASTER." where `batch_number` like '".$search_text_batches."%')and `at`.`is_delete`='0'
                    ORDER BY `at`.`id` ASC";
            }
            else{
                $q = "SELECT `at`.*
					FROM `".$this->table."` AS `at`
                    WHERE `at`.`is_delete`='0'
                    ORDER BY `at`.`id` ASC"; 
            }
            
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                $a = 0;
    			while($row = $this->re_db_fetch_array($res)){
    			     array_push($return,$row);
    			}
            }
			return $return;
		}
    public function select_sponsor(){
			$return = array();
			
			$q = "SELECT `at`.*
					FROM `".SPONSOR_MASTER."` AS `at`
                    WHERE `at`.`is_delete`='0'
                    ORDER BY `at`.`id` ASC";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                $a = 0;
    			while($row = $this->re_db_fetch_array($res)){
    			     array_push($return,$row);
                     
    			}
            }
			return $return;
		}
        public function get_product($id,$sponsor=''){
			$return = array();
            $con ='';
            
            if($sponsor != '')
            {
                $con = "and sponsor='".$sponsor."'";
            }
			
			$q = "SELECT `at`.*
					FROM `product_category_".$id."` AS `at`
                    WHERE `at`.`is_delete`='0' ".$con."
                    ORDER BY `at`.`id` ASC";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                $a = 0;
    			while($row = $this->re_db_fetch_array($res)){
    			     array_push($return,$row);
    			}
            }
			return $return;
		}
        public function select_category(){
			$return = array();
			
			$q = "SELECT `at`.*
					FROM `".PRODUCT_TYPE."` AS `at`
                    WHERE `at`.`is_delete`='0'
                    ORDER BY `at`.`id` ASC";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                $a = 0;
    			while($row = $this->re_db_fetch_array($res)){
    			     array_push($return,$row);
                     
    			}
            }
			return $return;
		}
        public function select_client(){
			$return = array();
			
			$q = "SELECT `at`.*
					FROM `".CLIENT_MASTER."` AS `at`
                    WHERE `at`.`is_delete`='0'
                    ORDER BY `at`.`id` ASC";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                $a = 0;
    			while($row = $this->re_db_fetch_array($res)){
    			     array_push($return,$row);
                     
    			}
            }
			return $return;
		}
        public function select_client_account_no($client_id){
			$return = '';
			
			$q = "SELECT `at`.`account_no`
					FROM `".CLIENT_ACCOUNT."` AS `at`
                    WHERE `at`.`is_delete`='0' AND `at`.`client_id`=".$client_id."
                    ORDER BY `at`.`id` ASC limit 1";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                $a = 0;
    			while($row = $this->re_db_fetch_array($res)){
    			     $return = $row['account_no'];
                     
    			}
            }
			return $return;
		}
        public function select_broker(){
			$return = array();
			
			$q = "SELECT `at`.*
					FROM `".BROKER_MASTER."` AS `at`
                    WHERE `at`.`is_delete`='0'
                    ORDER BY `at`.`last_name` ASC";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                $a = 0;
    			while($row = $this->re_db_fetch_array($res)){
    			     array_push($return,$row);
                     
    			}
            }
			return $return;
		}
        public function select_batch(){
			$return = array();
			
			$q = "SELECT `at`.*
					FROM `".BATCH_MASTER."` AS `at`
                    WHERE `at`.`is_delete`='0'
                    ORDER BY `at`.`id` ASC";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                $a = 0;
    			while($row = $this->re_db_fetch_array($res)){
    			     array_push($return,$row);
                     
    			}
            }
			return $return;
		}
        public function get_broker_split_rate($broker_id){
			$return = array();
            $con='';
            
            if($broker_id>0)
            {
                $con.=" AND `at`.`broker_id` = ".$broker_id."";
            }
			$q = "SELECT `at`.*
					FROM `".BROKER_PAYOUT_SPLIT."` AS `at`
                    WHERE `at`.`is_delete`='0' ".$con."
                    ORDER BY `at`.`id` ASC";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                $a = 0;
    			while($row = $this->re_db_fetch_array($res)){
    			     array_push($return,$row);
                     
    			}
            }
			return $return;
		}
        public function get_broker_override_rate($broker_id){
			$return = array();
            $con='';
            
            if($broker_id>0)
            {
                $con.=" AND `at`.`broker_id` = ".$broker_id."";
            }
			$q = "SELECT `at`.*
					FROM `".BROKER_PAYOUT_OVERRIDE."` AS `at`
                    WHERE `at`.`is_delete`='0' ".$con."
                    ORDER BY `at`.`id` ASC";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                $a = 0;
    			while($row = $this->re_db_fetch_array($res)){
    			     array_push($return,$row);
                     
    			}
            }
			return $return;
		}
        public function get_client_split_rate($client_id){
			$return = array();
            $con='';
            
            if($client_id>0)
            {
                $con.=" AND `at`.`id` = ".$client_id."";
            }
			$q = "SELECT `at`.`split_broker`,`at`.`split_rate`
					FROM `".CLIENT_MASTER."` AS `at`
                    WHERE `at`.`is_delete`='0' ".$con."
                    ORDER BY `at`.`id` ASC";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                $a = 0;
    			while($row = $this->re_db_fetch_array($res)){
    			     array_push($return,$row);
                     
    			}
            }
			return $return;
		}
        public function select_batch_date($batch_id){
			$return = '';
			
			$q = "SELECT `at`.`batch_date`
					FROM `".BATCH_MASTER."` AS `at`
                    WHERE `at`.`is_delete`='0' AND `at`.`id`=".$batch_id."
                    ORDER BY `at`.`id` ASC";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                $a = 0;
    			while($row = $this->re_db_fetch_array($res)){
    			     $return = $row['batch_date'];
                     
    			}
            }
			return $return;
		}
        public function select(){
			$return = array();
			
			$q = "SELECT `at`.*,`bt`.id as batch_number,`cl`.first_name as client_firstname,`cl`.last_name as client_lastname,`bm`.first_name as broker_firstname
					FROM `".$this->table."` AS `at`
                    LEFT JOIN `".BATCH_MASTER."` as `bt` on `bt`.`id` = `at`.`batch`
                    LEFT JOIN `".CLIENT_MASTER."` as `cl` on `cl`.`id` = `at`.`client_name`
                    LEFT JOIN `".BROKER_MASTER."` as `bm` on `bm`.`id` = `at`.`broker_name`
                    WHERE `at`.`is_delete`='0' and `at`.`is_payroll`='0'
                    ORDER BY `at`.`id` ASC";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                $a = 0;
    			while($row = $this->re_db_fetch_array($res)){
    			     array_push($return,$row);
    			}
            }
			return $return;
		}
        public function select_branch_company_ref($broker_id){
			$return = array();
			
			$q = "SELECT `at`.*,`bc`.`id` as branch_id,`bc`.*,`cn`.`id` as company_id
					FROM `".BROKER_BRANCHES."` AS `at`
                    LEFT JOIN `".BRANCH_MASTER."` as `bc` on `bc`.`id` = `at`.`branch1`
                    LEFT JOIN `".COMPANY_MASTER."` as `cn` on `cn`.`id` = `bc`.`company`
                    WHERE `at`.`is_delete`='0' AND `at`.`id`=".$broker_id."
                    ORDER BY `at`.`id` ASC";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                $a = 0;
    			while($row = $this->re_db_fetch_array($res)){
    			     $return = $row;
    			}
            }
			return $return;
		}
        public function select_data_report($product_category='',$company='',$batch='',$beginning_date='',$ending_date='',$sort_by='',$is_historical=0){
			$return = array();
            $con='';
            if($is_historical==0)
            {
                $con.=" AND `at`.`is_payroll`='0'";
            }
            if($product_category>0)
            {
                $con.=" AND `at`.`product_cate` = ".$product_category."";
            }
            if($company>0)
            {
                $con.=" AND `at`.`company` = ".$company."";
            }
            if($batch>0)
            {
                $con.=" AND `at`.`batch` = ".$batch."";
            }
            if($beginning_date != '' && $ending_date != '')
            {
                $con.=" AND `at`.`trade_date` between '".date('Y-m-d',strtotime($beginning_date))."' and '".date('Y-m-d',strtotime($ending_date))."'";
            }
            if($sort_by == 1)
            {
                $con .= " ORDER BY `at`.`sponsor` ASC";
            }
            else if($sort_by == 2)
            {
                $con .= " ORDER BY `at`.`batch` ASC";
            }
            else if($sort_by == 3)
            {
                $con .= " ORDER BY `bt`.`batch_date` ASC";
            }
            else
            {
                $con .= " ORDER BY `at`.`product_cate` ASC";
            }
			
			$q = "SELECT `at`.*,bm.first_name as broker_name,cm.first_name as client_name,bt.batch_desc
					FROM `".$this->table."` AS `at`
                    LEFT JOIN `".BATCH_MASTER."` as `bt` on `bt`.`id` = `at`.`batch`
                    LEFT JOIN `".BROKER_MASTER."` as `bm` on `bm`.`id` = `at`.`broker_name`
                    LEFT JOIN `".CLIENT_MASTER."` as `cm` on `cm`.`id` = `at`.`client_name`
                    WHERE `at`.`is_delete`='0' ".$con." ";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                $a = 0;
    			while($row = $this->re_db_fetch_array($res)){
    			     array_push($return,$row);
    			}
            }
			return $return;
		}
        
}
?>