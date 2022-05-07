<?php

class transaction extends db{

    public $errors = '';
    public $table = TRANSACTION_MASTER;
    public $broker_master = BROKER_MASTER;
    public $product_type = PRODUCT_TYPE;
    public function insert_update($data){//echo '<pre>';print_r($data);exit;

			$id = isset($data['id'])?$this->re_db_input($data['id']):0;
            //$trade_number = isset($data['trade_number'])?$this->re_db_input($data['trade_number']):0;
            $client_name = isset($data['client_name'])?$this->re_db_input($data['client_name']):'0';
            $client_number = isset($data['client_number'])?$this->re_db_input($data['client_number']):'0';
            $client_id_from_ac_no =0;

            //print(select_client_id($client_number));
	        /*$client_id_from_ac_no=$this->select_client_id($client_number);
	        if($client_id_from_ac_no=='' || $client_id_from_ac_no=='0')
	        {
	        		 if($client_number != '' && $client_name!='0' && $client_name!='')
                        {
            				$q = "INSERT INTO `".CLIENT_ACCOUNT."` SET `client_id`='".$client_name."',`account_no`='".$client_number."'".$this->insert_common_sql();
            				$res = $this->re_db_query($q);
                        }
	        }*/


            $ch_date =isset($data['ch_date']) && $data['ch_date']!=''?$this->re_db_input(date('Y-m-d',strtotime($data['ch_date']))):'0000-00-00';
	        $ch_amount =isset($data['ch_amount'])?$this->re_db_input(str_replace(',', '', $data['ch_amount'])):0;
	        $ch_no =isset($data['ch_no'])?$this->re_db_input($data['ch_no']):'';
	        $ch_pay_to =isset($data['ch_pay_to'])?$this->re_db_input($data['ch_pay_to']):'';

            $broker_name = isset($data['broker_name'])?$this->re_db_input($data['broker_name']):'0';
            $product_cate = isset($data['product_cate'])?$this->re_db_input($data['product_cate']):'';
            $sponsor = isset($data['sponsor'])?$this->re_db_input($data['sponsor']):'';
            $product = isset($data['product'])?$this->re_db_input($data['product']):'';
            $batch = isset($data['batch'])?$this->re_db_input($data['batch']):'';
            $invest_amount = isset($data['invest_amount'])?$this->re_db_input(str_replace(',', '', $data['invest_amount'])):0;
            $charge_amount = isset($data['charge_amount'])?$this->re_db_input(str_replace(',', '', $data['charge_amount'])):0;
            $commission_received = isset($data['commission_received'])?$this->re_db_input(str_replace(',', '', $data['commission_received'])):0;
   //          $formatter = new NumberFormatter('de_DE', NumberFormatter::CURRENCY);
			// $commission_received=var_dump($formatter->parseCurrency($data['commission_received'], $curr));

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
            $is_1035_exchange= isset($data['is_1035_exchange']) ? $this->re_db_input($data['is_1035_exchange']) : 0;
            $is_trail_trade = isset($data['is_trail_trade']) ? $this->re_db_input($data['is_trail_trade']):0;

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
            else if($trade_date=='' || $trade_date=='01/01/1970'){
				$this->errors = 'Please enter trade date.';
			}
            else if($commission_received_date=='' || $commission_received_date=='01/01/1970'){
				$this->errors = 'Please enter commission received date.';
			}
   //          else if($settlement_date==''){
			// 	$this->errors = 'Please enter settlement date.';
			// }
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

				$is_new_client = $client_number == -1 ;
	        if($is_new_client){
                  $client_number= $_POST['c_account_no'];
                   $q = "INSERT INTO `".CLIENT_ACCOUNT."` SET `client_id`='".$client_name."',`account_no`='".$client_number."',`sponsor_company`='".$_POST['c_sponsor']."'".$this->insert_common_sql();;
                   $res = $this->re_db_query($q);
            }
			 // 05/03/22 Branch & Company added to the Entry Form so user can choose a specific branch/company for the trade
            //  $get_branch_company_detail = $this->select_branch_company_ref($broker_name);
             $branch = isset($data['branch'])?$data['branch']:0;
             $company = isset($data['company'])?$data['company']:0;

				if($id>=0){
					if($id==0){
						$q = "INSERT INTO ".$this->table." SET `client_name`='".$client_name."',`source`='MN',`client_number`='".$client_number."',`broker_name`='".$broker_name."',
                        `product_cate`='".$product_cate."',`sponsor`='".$sponsor."',`product`='".$product."',`batch`='".$batch."',
                        `invest_amount`='".$invest_amount."',`commission_received_date`='".$commission_received_date."',`posting_date`='".$posting_date."',`trade_date`='".$trade_date."',`settlement_date`='".$settlement_date."',`charge_amount`='".$charge_amount."',`commission_received`='".$commission_received."',`split`='".$split."',
                        `another_level`='".$another_level."',`cancel`='".$cancel."',`buy_sell`='".$buy_sell."',`ch_no`='".$ch_no."', `ch_pay_to`='".$ch_pay_to."', `ch_date`='".$ch_date."', `ch_amount`='".$ch_amount."',
                        `hold_resoan`='".$hold_resoan."',`hold_commission`='".$hold_commission."',`units`='".$units."',`shares`='".$shares."',`branch`='".$branch."' ,`is_1035_exchange`='".$is_1035_exchange."',`trail_trade`='".$is_trail_trade."'  ,`company`='".$company."'".$this->insert_common_sql();

                        $res = $this->re_db_query($q);
                        $last_inserted_id = $this->re_db_insert_id();

                        $this->save_split_commission_data($last_inserted_id,$data);

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
                        `ch_no`='".$ch_no."', `ch_pay_to`='".$ch_pay_to."', `ch_date`='".$ch_date."', `ch_amount`='".$ch_amount."',
                        `hold_resoan`='".$hold_resoan."',`hold_commission`='".$hold_commission."',`units`='".$units."',`shares`='".$shares."',`branch`='".$branch."' ,`is_1035_exchange`='".$is_1035_exchange."',`trail_trade`='".$is_trail_trade."' ,`company`='".$company."'".$this->update_common_sql()." WHERE `id`='".$id."'";
                        $res = $this->re_db_query($q);

                        $this->save_split_commission_data($id,$data);

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

		public function save_split_commission_data($transaction_id,$data){
			//print_r($data);
			$val = $data['override'];
			foreach($val['receiving_rep1'] as $index =>$rap){

                       $row_id = isset($val['row_id'][$index])?$this->re_db_input($val['row_id'][$index]):'';
	                   $from1=isset($val['from1'][$index])?$this->re_db_input(date('Y-m-d',strtotime($val['from1'][$index]))):'0000-00-00';
	                   $to1=isset($val['to1'][$index])?$this->re_db_input(date('Y-m-d',strtotime($val['to1'][$index]))):'0000-00-00';
	                   $product_category=isset($val['product_category1'][$index])?$this->re_db_input($val['product_category1'][$index]):'';
	                   $per1=isset($val['per1'][$index])?$this->re_db_input($val['per1'][$index]):'';
	                   if($from1!='' && $to1!='' && $rap != ''){
	                   	  if($row_id > 0){
                              $q = "update `ft_transaction_split_commissions` SET `broker_id`='".$_SESSION['last_insert_id']."',`rap` = '".$rap."',`from`='".$from1."' ,`to`='".$to1."' ,`product_category`='".$product_category."',client_id='".$data['client_name']."',transaction_id='".$transaction_id."' ,`per`='".$per1."' where id='".$row_id."'";
		                     $res = $this->re_db_query($q);

	                   	  }
	                   	  else{

	                   	  	 $q = "INSERT INTO `ft_transaction_split_commissions` SET `broker_id`='".$_SESSION['last_insert_id']."',`rap` = '".$rap."',`from`='".$from1."' ,`to`='".$to1."' ,`product_category`='".$product_category."',transaction_id='".$transaction_id."',client_id='".$data['client_name']."' ,`per`='".$per1."' ".$this->insert_common_sql();
		                     $res = $this->re_db_query($q);

	                   	  }

				        }
				        if(!empty($data['deleted_rows'])){
				        	$deleted_rows= explode(",",$data['deleted_rows']);
				        	foreach($deleted_rows as $row_id){
				        		if(is_numeric($row_id)){
				        			  $q = "delete from  `ft_transaction_split_commissions`  where id='".$row_id."'";
				        		  $res = $this->re_db_query($q);
				        		}

				        	}
				        }
			}
			//die;
		}

		public function load_split_commission($transaction_id){
			$originalArray=array();
			$qq1="SELECT * FROM `ft_transaction_split_commissions` WHERE is_delete=0 AND `transaction_id`=".$transaction_id."";
            $res = $this->re_db_query($qq1);
            $originalArray = array();
            if($this->re_db_num_rows($res)>0)
            {
              while($row = $this->re_db_fetch_array($res)){
                array_push($originalArray,$row);
              }
            }
            return $originalArray;
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
                $con = " and sponsor='".$sponsor."'";
            }
            if($id != '')
            {
                $con = " and category='".$id."'";
            }

			$q = "SELECT `at`.*
					FROM `ft_products` AS `at`
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
        public function select_category($ids = [] ){
			$return = array();
			$where_and = '';

			if(!empty($ids)) $where_and = 'and at.id IN ('.implode(',',$ids).')';
			$q = "SELECT `at`.*
					FROM `".PRODUCT_TYPE."` AS `at`
                    WHERE `at`.`is_delete`='0' ".$where_and."
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

			$q = "SELECT `at`.*,concat(`at`.`last_name`,' ',`at`.`mi`,' ',`at`.`first_name`) as fullname
					FROM `".CLIENT_MASTER."` AS `at`
                    WHERE `at`.`is_delete`='0'
                    ORDER BY fullname ASC";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                $a = 0;
    			while($row = $this->re_db_fetch_array($res)){
    			     array_push($return,$row);

    			}
            }
			return $return;
		}
		public function select_all_client_account_no(){
			$return = array();

			$q = "SELECT `at`.`account_no`
					FROM `".CLIENT_ACCOUNT."` AS `at`
                    WHERE `at`.`is_delete`='0'
                    ORDER BY `at`.`id` ";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                $a = 0;
    			while($row = $this->re_db_fetch_array($res)){
    			     array_push($return,$row['account_no']);

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

		public function select_client_all_account_no($client_id){
			$return=array();
           $q = "SELECT `at`.`account_no`
					FROM `".CLIENT_ACCOUNT."` AS `at`
                    WHERE `at`.`is_delete`='0' AND `at`.`client_id`='".$client_id."'
                    ORDER BY `at`.`id` ASC ";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                $a = 0;
    			while($row = $this->re_db_fetch_array($res)){
    			     $return[] = $row['account_no'];

    			}
            }
			return $return;
		}

		public function load_broker_hold_commission($broker_id){

			$return=array();
           $q = "SELECT *
					FROM `ft_transaction_split_commissions` AS `at`
                    WHERE `at`.`is_delete`='0' AND `at`.`broker_id`='".$broker_id."'
                    ORDER BY `at`.`id` ASC ";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                $a = 0;
    			while($row = $this->re_db_fetch_array($res)){
    			     $return[] = $row['account_no'];

    			}
            }
			return $return;
		}


		 public function select_client_id($client_number=''){
			$return = '';

			$q = "SELECT `at`.`client_id`
					FROM `".CLIENT_ACCOUNT."` AS `at`
                    WHERE `at`.`is_delete`='0' AND `at`.`account_no`='".$client_number."'
                    ORDER BY `at`.`client_id` ASC limit 1";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                $a = 0;
    			while($row = $this->re_db_fetch_array($res)){
    			     $return = $row['client_id'];

    			}
            }
			return $return;
		}
        public function select_broker(){
			$return = array();

			$q = "SELECT `at`.*,concat(`at`.`last_name`,' ',`at`.`first_name`) as fullname
					FROM `".BROKER_MASTER."` AS `at`
                    WHERE `at`.`is_delete`='0'
                    ORDER BY fullname ASC";
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
                    ORDER BY `at`.`id` desc";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                $a = 0;
    			while($row = $this->re_db_fetch_array($res)){
    			     array_push($return,$row);

    			}
            }
			return $return;
		}
		 public function select_batch_max_id(){
			$return = array();

			$q = "SELECT `at`.`id`
					FROM `".BATCH_MASTER."` AS `at`
                    WHERE `at`.`is_delete`='0'
                    ORDER BY `at`.`id` desc limit 1";
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
			$return = array();

			$q = "SELECT `at`.`batch_date`,`at`.`pro_category`,`at`.`sponsor`
					FROM `".BATCH_MASTER."` AS `at`
                    WHERE `at`.`is_delete`='0' AND `at`.`id`=".$batch_id."
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
        public function select(){
			$return = array();

			$q = "SELECT `at`.*,`bt`.id as batch_number,`bt`.batch_desc as batch_desc,`cl`.first_name as client_firstname,`cl`.last_name as client_lastname,`bm`.first_name as broker_firstname,`bm`.last_name as broker_last_name
					FROM `".$this->table."` AS `at`
                    LEFT JOIN `".BATCH_MASTER."` as `bt` on `bt`.`id` = `at`.`batch`
                    LEFT JOIN `".CLIENT_MASTER."` as `cl` on `cl`.`id` = `at`.`client_name`
                    LEFT JOIN `".BROKER_MASTER."` as `bm` on `bm`.`id` = `at`.`broker_name`
                    WHERE `at`.`is_delete`='0'
                    ORDER BY `at`.`trade_date` desc";
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
        public function select_data_report($product_category='',$company='',$batch='',$beginning_date='',$ending_date='',$sort_by='',$is_historical=0,$is_hold=false){
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
                $con.=" AND `at`.`commission_received_date` between '".date('Y-m-d',strtotime($beginning_date))."' and '".date('Y-m-d',strtotime($ending_date))."'";
            }
            if($is_hold){
            	 $con.=" AND hold_commission=1 ";
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


			 $q = "SELECT `at`.*,bm.first_name as broker_name,bm.last_name as broker_last_name,bm.id as broker_id,cm.first_name as client_name,cm.last_name as client_last_name,bt.batch_desc
					FROM `".$this->table."` AS `at`
                    LEFT JOIN `".BATCH_MASTER."` as `bt` on `bt`.`id` = `at`.`batch`
                    LEFT JOIN `".BROKER_MASTER."` as `bm` on `bm`.`id` = `at`.`broker_name`
                    LEFT JOIN `".CLIENT_MASTER."` as `cm` on `cm`.`id` = `at`.`client_name`
                    WHERE `at`.`is_delete`='0' ".$con." ";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                $a = 0;
    			while($row = $this->re_db_fetch_array($res)){
    				$brokder_id=$row['broker_id'];
    				$row['product_name'] = $this->get_product_name_from($row['product_cate'],$row['product']);
    				$row['client_name'] = $row['client_last_name'].', '.$row['client_name'];
    				if(isset($return[$row['broker_id']])){
    			     //array_push($return,$row);
    					 $return[$row['broker_id']][]=$row;
    				}
    				else{
    					  $return[$row['broker_id']]=array();
    					 $return[$row['broker_id']][]=$row;
    				}
    			}
            }
			return $return;
		}
		public function select_data_commission_posting_log($product_category='',$company='',$batch='',$beginning_date='',$ending_date='',$sort_by='',$is_historical=0,$is_hold=false){
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
                $con.=" AND `at`.`commission_received_date` between '".date('Y-m-d',strtotime($beginning_date))."' and '".date('Y-m-d',strtotime($ending_date))."'";
            }
            if($is_hold){
            	 $con.=" AND hold_commission=1 ";
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




			 $q = "SELECT `at`.*,bm.first_name as broker_name,bm.last_name as broker_last_name,bm.id as broker_id,cm.first_name as client_name,cm.last_name as client_last_name,bt.batch_desc
					FROM `".$this->table."` AS `at`
                    LEFT JOIN `".BATCH_MASTER."` as `bt` on `bt`.`id` = `at`.`batch`
                    LEFT JOIN `".BROKER_MASTER."` as `bm` on `bm`.`id` = `at`.`broker_name`
                    LEFT JOIN `".CLIENT_MASTER."` as `cm` on `cm`.`id` = `at`.`client_name`
                    WHERE `at`.`is_delete`='0' ".$con." ";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                $a = 0;
    			while($row = $this->re_db_fetch_array($res)){
    				$brokder_id=$row['broker_id'];
    				$row['product_name'] = $this->get_product_name_from($row['product_cate'],$row['product']);
    				$row['client_name'] = $row['client_last_name'].', '.$row['client_name'];

    				if(!isset($return[$row['batch']]))
    					$return[$row['batch']]=array("id"=>$row['batch'],"batch_desc"=>$row['batch_desc'],"child"=>array());
    				if(!isset($return[$row['batch']]['child'][$row['broker_id']]))
    				   $return[$row['batch']]['child'][$row['broker_id']]=array();

    				$return[$row['batch']]['child'][$row['broker_id']][]=$row;

    				/*if(isset($return[$row['broker_id']])){
    			     //array_push($return,$row);
    					 $return[$row['broker_id']][]=$row;
    				}
    				else{
    					  $return[$row['broker_id']]=array();
    					 $return[$row['broker_id']][]=$row;
    				}*/
    			}
            }
			return $return;
		}

		function get_product_name_from($to_category,$product_id){
             static $product_list=array();
             $index=$to_category.'-'.$product_id;
                    if(isset($product_list[$index]))
                    	return $product_list[$index];
             $query="select name from `ft_products` as at where  `at`.`id`='".$product_id."' limit 1";
            	$res = $this->re_db_query($query);

            	if($this->re_db_num_rows($res)>0){
                      $product_data = $this->re_db_fetch_array($res);
                      $product_list[$index]=isset($product_data['name']) ? $product_data['name']: '';
                     return isset($product_data['name']) ? $product_data['name']: '';
            	}

            	return "";

		}
		function get_sponser_name($id){
             static $sponser_list=array();
             $index=$id;
                    if(isset($product_list[$index]))
                    	return $product_list[$index];
            $q = "SELECT `sm`.name"
					." FROM `".SPONSOR_MASTER."` AS `sm`"
					." WHERE
					    `sm`.`id`='$id'  limit 1"
			;
			$res = $this->re_db_query($q);

			if($this->re_db_num_rows($res)>0) {
				$product_data = $this->re_db_fetch_array($res);
				$sponser_list[$index]=isset($product_data['name']) ? $product_data['name']: '';
                     return isset($product_data['name']) ? $product_data['name']: '';
			}

			return "";

		}



		public function get_payable_report($product_category='',$company='',$batch='',$cutoffdate='',$sort_by='',$payable_type){
			$return = array();
            $con='';

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
            if($payable_type==2){
               $con.=" AND hold_commission=1 ";
            }
            else{
                   $con.=" AND hold_commission=2 ";
            }
            if($cutoffdate != '')
            {
                $con.=" AND `at`.`commission_received_date` <= '".date('Y-m-d',strtotime($cutoffdate))."' ";
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




			 $q = "SELECT `at`.*,bm.first_name as broker_name,bm.last_name as broker_last_name,bm.id as broker_id,cm.first_name as client_name,cm.last_name as client_last_name,bt.batch_desc
					FROM `".$this->table."` AS `at`
                    LEFT JOIN `".BATCH_MASTER."` as `bt` on `bt`.`id` = `at`.`batch`
                    LEFT JOIN `".BROKER_MASTER."` as `bm` on `bm`.`id` = `at`.`broker_name`
                    LEFT JOIN `".CLIENT_MASTER."` as `cm` on `cm`.`id` = `at`.`client_name`
                    WHERE  `at`.`is_delete`='0' ".$con." ";
			$res = $this->re_db_query($q);

			$broker_rates=$this->get_broker_payout_rates();
			$broker_payout_overide=$this->get_broker_payout_override();

            if($this->re_db_num_rows($res)>0){
                $a = 0;
    			while($row = $this->re_db_fetch_array($res)){

    				$row['product_name'] = $this->get_product_name_from($row['product_cate'],$row['product']);
    				$row['rates']=isset($broker_rates[$row['broker_id']][$row['product_cate']]) ? $broker_rates[$row['broker_id']][$row['product_cate']]: 0;
    				$row['payout']=isset($broker_payout_overide[$row['broker_id']][$row['product_cate']]) ? $broker_payout_overide[$row['broker_id']][$row['product_cate']]: 0;
    				$row['client_name'] = $row['client_last_name'].', '.$row['client_name'];
    				if($this->is_payable_overide($row)){

    						$row['product_name'] = " * ".$row['product_name'];
    					}
    				if(isset($return[$row['broker_id']])){




    			     //array_push($return,$row);
    					 $return[$row['broker_id']][]=$row;
    				}
    				else{
    					  $return[$row['broker_id']]=array();





    					 $return[$row['broker_id']][]=$row;

    				}
    			}
            }

			return $return;
		}

		function is_payable_overide($row){
              $isBetween=false;

             foreach($row['payout'] as $payout){
             	 $trade_date = date("Y-m-d",strtotime($row['trade_date']));
             	  $payout_from_date = date("Y-m-d",strtotime($payout['from']));
             	  $payout_to_date = date("Y-m-d",strtotime($payout['to']));

                  if($payout_from_date <= $trade_date && $payout_to_date >= $trade_date){
                  	$isBetween=true;
                  	break;
                  }
             }
             return $isBetween;
		}


		function get_broker_payout_override(){
			$rates=array();
           	$q = "SELECT `at`.*
					FROM `".BROKER_PAYOUT_OVERRIDE."` AS `at`";
            $res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
               while($row = $this->re_db_fetch_array($res)){
                  if(!isset($rates[$row['broker_id']])){
                     	$rates[$row['broker_id']]=array();
                     }
                      $rates[$row['broker_id']][$row['product_category']][]=$row;

               }
            }




            return $rates;
		}

        function get_broker_payout_rates(){
        	$rates=array();
            $q = "SELECT category_rates,category_id,broker_id FROM `".PAYOUT_FIXED_RATES."` AS `at`";
            $res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
               while($row = $this->re_db_fetch_array($res)){
                     if(!isset($rates[$row['broker_id']])){
                     	$rates[$row['broker_id']]=array();
                     }
                      $rates[$row['broker_id']][$row['category_id']]=(float)$row['category_rates'];


                  /*if(isset($rates['broker_id'])){
                      $rates['broker_id'][$row['category_id']]=(float)$row['category_rates'];
                  }
                  else{
                  	    $rates['broker_id']=array();
                  	    $rates['broker_id'][$row['category_id']]=(float)$row['category_rates'];
                  }*/
               	  // $rates[$row['category_id']]= (float)$row['category_rates'];
               }
            }

            return $rates;
        }

        public function select_transcation_history_report($branch=0,$broker='',$rep='',$client='',$product='',$beginning_date='',$ending_date='',$batch=0,$date_by="1",$filter_by="1",$is_trail=0,$sponsor=0,$index_column)
        {

			$return = array();
            $con='';

            if($branch>0)
            {
                $con.=" AND `at`.`branch` = ".$branch." ";
                //$index_column='branch';
            }
            if($broker>0)
            {
                $con.=" AND `at`.`broker_name` = ".$broker." ";
               // $index_column='broker_name';
            }
            if($client>0)
            {
                $con.=" AND `at`.`client_name` = ".$client." ";
                //$index_column='client_name';
            }
            if($product>0)
            {
                $con.=" AND `at`.`product` = ".$product." ";
               // $index_column='product';
            }
            if($batch>0)
            {
                $con.=" AND `at`.`batch` = '".$batch."' ";
               // $index_column='batch';
            }
            if($sponsor>0)
            {
                $con.=" AND `at`.`sponsor` = '".$sponsor."' ";
               // $index_column='sponsor';
            }
            if($is_trail>0)
            {
                $con.=" AND `at`.`trail_trade` = '0' ";
            }
            if($filter_by == "1" && $beginning_date != '' && $ending_date != '')
            {
                if($date_by == "2")
                   $con.=" AND `at`.`commission_received_date` between '".date('Y-m-d',strtotime($beginning_date))."' and '".date('Y-m-d',strtotime($ending_date))."' ";
            	else
                 $con.=" AND `at`.`trade_date` between '".date('Y-m-d',strtotime($beginning_date))."' and '".date('Y-m-d',strtotime($ending_date))."' ";
            }


              $con .= " ORDER BY `at`.`trade_date` ASC ";


		       $q = "SELECT `at`.*,br.name as branch_name,bm.first_name as broker_name,bm.last_name as broker_last_name,bm.id as broker_id,cm.first_name as client_name,cm.last_name as client_last_name,bt.batch_desc,br.name as branch_name,pt.type as product_category_name
					FROM `".$this->table."` AS `at`
                    LEFT JOIN `".BATCH_MASTER."` as `bt` on `bt`.`id` = `at`.`batch`
                    LEFT JOIN `".PRODUCT_TYPE."` as `pt` on `pt`.`id` = `at`.`product_cate`
                    LEFT JOIN `ft_branch_master` as `br` on `br`.`id` = `at`.`branch`
                    LEFT JOIN `".BROKER_MASTER."` as `bm` on `bm`.`id` = `at`.`broker_name`
                    LEFT JOIN `".CLIENT_MASTER."` as `cm` on `cm`.`id` = `at`.`client_name`
                    WHERE `at`.`is_delete`='0' ".$con." ";
				$res = $this->re_db_query($q);


	            if($this->re_db_num_rows($res)>0){
	                $a = 0;
	    			while($row = $this->re_db_fetch_array($res)){

	    				$brokder_id=$row['broker_id'];
	    				$row['product_name'] = $this->get_product_name_from($row['product_cate'],$row['product']);
	    				$row['client_name'] = $row['client_last_name'].', '.$row['client_name'];
	    				if(!isset($return[$row[$index_column]])) {
	    					$heading_title="BROKER: ".$row['broker_last_name'].', '.$row['broker_name'];
	    					if($index_column =="batch")
	                            $heading_title="BATCH: ".$row['batch_desc'];
	                        if($index_column =="product")
	                            $heading_title="PRODUCT: ".$row['product_name'];
	                        if($index_column =="client_name")
	                            $heading_title="CLIENT: ".$row['client_name'];
	                         if($index_column =="branch")
	                            $heading_title="BRANCH: ".$row['branch_name'];
	                         if($index_column =="sponsor")
	                            $heading_title="SPONSOR: ".$this->get_sponser_name($row['sponsor']);

	    					 $return[$row[$index_column]]=array("broker"=>$heading_title,"products"=>array());
	    				}


	    				$return[$row[$index_column]]["products"][]=$row;


	    				/*$brokder_id=$row['broker_id'];
	    				$row['product_name'] = $this->get_product_name_from($row['product_cate'],$row['product']);
	    				$row['client_name'] = $row['client_last_name'].', '.$row['client_name'];
	    				$return[]=$row;*/
	    				/*if(isset($return[$row['broker_id']])){
	    			     //array_push($return,$row);
	    					 $return[$row['broker_id']][]=$row;
	    				}
	    				else{
	    					  $return[$row['broker_id']]=array();
	    					 $return[$row['broker_id']][]=$row;
	    				}*/
	    			}
	            }

			return $return;
		}

 public function select_transcation_history_report_v2($report_for,$sort_by=1,$branch=0,$broker='',$rep='',$client='',$product='',$beginning_date='',$ending_date='',$batch=0,$date_by="1",$filter_by="1",$is_trail=0,$prod_cat=array())
 	{
			$return = array();
            $con='';

            if($branch>0)
            {
                $con.=" AND `at`.`branch` = ".$branch." ";
            }
            if($broker>0)
            {
                $con.=" AND `at`.`broker_name` = ".$broker." ";
            }
            if($client>0)
            {
                $con.=" AND `at`.`client_name` = ".$client." ";
            }
            if($product>0)
            {
                $con.=" AND `at`.`product` = ".$product." ";
            }
            if($batch>0)
            {
                $con.=" AND `at`.`batch` = '".$batch."' ";
            }
            if($is_trail>0)
            {
                $con.=" AND `at`.`trail_trade` = '0' ";
            }
            //print(implode(',',$prod_cat));exit;
            if($filter_by == "1" && $beginning_date != '' && $ending_date != '')
            {
                if($date_by == "2")
                   $con.=" AND `at`.`commission_received_date` between '".date('Y-m-d',strtotime($beginning_date))."' and '".date('Y-m-d',strtotime($ending_date))."' ";
            	else
                 $con.=" AND `at`.`trade_date` between '".date('Y-m-d',strtotime($beginning_date))."' and '".date('Y-m-d',strtotime($ending_date))."' ";
            }
            if($report_for=="Production by Product Category")
            {
            	$con.=" Group by `at`.product_cate,`at`.product";
            }
            else if($report_for=="Product Category Summary Report")
            {
            	$con.=" Group by `at`.product_cate,`at`.product";
            }
            else if($report_for=="Production by Sponsor Report")
            {
            	if(implode(',',$prod_cat)!='')
            	{
            		$con.=" and at.product_cate in ('".implode(',',$prod_cat)."') Group by sm.name";
            	}
            	else
            	{
            		$con.=" Group by sm.name";
            	}

            }

            if($sort_by==1)
            {
            	$con.= " ORDER BY sm.name ASC ";
            }
            else if($sort_by==2)
            {
            	$con.= " ORDER BY  SUM(`at`.invest_amount) DESC ";
            }

                $q="SELECT `at`.product_cate,`at`.sponsor,`at`.product_cate,`at`.product,sum(`at`.invest_amount) as invest_amount,sum(`at`.charge_amount) as charge_amount,sum(`at`.commission_received) as commission_received,bm.first_name as broker_name,bm.last_name as broker_last_name,bm.id as broker_id,cm.first_name as client_name,cm.last_name as client_last_name,bt.batch_desc,br.name as branch_name,pt.type as product_category_name,sm.name as sponsor_name FROM `".$this->table."` AS `at`
                    LEFT JOIN `".BATCH_MASTER."` as `bt` on `bt`.`id` = `at`.`batch`
                    LEFT JOIN `".PRODUCT_TYPE."` as `pt` on `pt`.`id` = `at`.`product_cate`
                    LEFT JOIN `ft_branch_master` as `br` on `br`.`id` = `at`.`branch`
                    LEFT JOIN `".BROKER_MASTER."` as `bm` on `bm`.`id` = `at`.`broker_name`
                    LEFT JOIN `".CLIENT_MASTER."` as `cm` on `cm`.`id` = `at`.`client_name`
                    LEFT JOIN `".SPONSOR_MASTER."` as `sm` on `sm`.`id` = `at`.`sponsor`
                    WHERE `at`.`is_delete`='0' ".$con;
           // echo $q;
			$res = $this->re_db_query($q);


            if($this->re_db_num_rows($res)>0){
                $a = 0;
    			while($row = $this->re_db_fetch_array($res)){
    				$brokder_id=$row['broker_id'];
    				$row['product_name'] = $this->get_product_name_from($row['product_cate'],$row['product']);
    				$row['client_name'] = $row['client_last_name'].', '.$row['client_name'];
    				$return[]=$row;
    				/*if(isset($return[$row['broker_id']])){
    			     //array_push($return,$row);
    					 $return[$row['broker_id']][]=$row;
    				}
    				else{
    					  $return[$row['broker_id']]=array();
    					 $return[$row['broker_id']][]=$row;
    				}*/
    			}
            }
            //print_r($return);

			return $return;
		}

		public function select_sponsor_by_id($id){
			$return = array();

			$q = "SELECT sm.name
					FROM `".SPONSOR_MASTER."` AS `sm`
                    WHERE `sm`.`is_delete`='0'
                    AND `sm`.`id`=$id
                    ORDER BY `sm`.`id` ASC";
			$res = $this->re_db_query($q);
		      if($this->re_db_num_rows($res)>0)
		      {
		        $row = $this->re_db_fetch_array($res);
		        return $row['name'];
		      }
			return array();
		}
		public function select_transcation_history_by_broker($broker='',$beginning_date='',$ending_date='',$date_by="1",$filter_by="1",$is_trail=0){
			$return = array();
            $con='';

            if($broker>0)
            {
                $con.=" AND `at`.`broker_name` = ".$broker." ";
            }
            if($filter_by == "1" && $beginning_date != '' && $ending_date != '')
            {
            	if($date_by == "2")
                   $con.=" AND `at`.`commission_received_date` between '".date('Y-m-d',strtotime($beginning_date))."' and '".date('Y-m-d',strtotime($ending_date))."' ";
            	else
                 $con.=" AND `at`.`trade_date` between '".date('Y-m-d',strtotime($beginning_date))."' and '".date('Y-m-d',strtotime($ending_date))."' ";
            }
            if($is_trail>0)
            {
                $con.=" AND `at`.`trail_trade` = '0' ";
            }



             $con .= " ORDER BY broker_last_name,bm.first_name ASC ";


		     $q = "SELECT `at`.*,bm.first_name as broker_name,bm.last_name as broker_last_name,bm.id as broker_id,cm.first_name as client_name,cm.last_name as client_last_name,bt.batch_desc,br.name as branch_name
					FROM `".$this->table."` AS `at`
                    LEFT JOIN `".BATCH_MASTER."` as `bt` on `bt`.`id` = `at`.`batch`
                    LEFT JOIN `ft_branch_master` as `br` on `br`.`id` = `at`.`branch`
                    LEFT JOIN `".BROKER_MASTER."` as `bm` on `bm`.`id` = `at`.`broker_name`
                    LEFT JOIN `".CLIENT_MASTER."` as `cm` on `cm`.`id` = `at`.`client_name`
                    WHERE `at`.`is_delete`='0' ".$con." ";
			$res = $this->re_db_query($q);


            if($this->re_db_num_rows($res)>0){
                $a = 0;
    			while($row = $this->re_db_fetch_array($res)){
    				$brokder_id=$row['broker_id'];
    				$row['product_name'] = $this->get_product_name_from($row['product_cate'],$row['product']);
    				$row['client_name'] = $row['client_last_name'].', '.$row['client_name'];
    				if(!isset($return[$row['broker_id']])){
    					 $return[$row['broker_id']]=array("broker"=>$row['broker_last_name'].', '.$row['broker_name'],"products"=>array());
    				}
    				if(!isset($return[$row['broker_id']]["products"][$row['product']])){
    					$return[$row['broker_id']]["products"][$row['product']]=array();
    				}

    				/*if(!isset($return[$row['broker_id']][$row['product']])){
                        $return[$row['broker_id']][$row['product']]=array();
    				}*/

    				$return[$row['broker_id']]["products"][$row['product']][]=$row;



    				/*if(isset($return[$row['product']])){
    			     //array_push($return,$row);
    					 $return[$row['product']][]=$row;
    				}
    				else{
    					  $return[$row['product']]=array();
    					 $return[$row['product']][]=$row;
    				}*/
    			}
            }

            /*foreach($return as $broker=>$value){
            	$col = array_column( $value, "product" );
				array_multisort( $col, SORT_ASC, $value );
            	$return[$broker]=$value;
            }*/

			return $return;
		}

		public function select_transcation_history_by_broker_v2($broker='',$beginning_date='',$ending_date='',$date_by="1",$filter_by="1",$is_trail=0){
			$return = array();
            $con='';

            if($broker>0)
            {
                $con.=" AND `at`.`broker_name` = ".$broker." ";
            }
            if($filter_by == "1" && $beginning_date != '' && $ending_date != '')
            {
            	if($date_by == "2")
                   $con.=" AND `at`.`commission_received_date` between '".date('Y-m-d',strtotime($beginning_date))."' and '".date('Y-m-d',strtotime($ending_date))."' ";
            	else
                 $con.=" AND `at`.`trade_date` between '".date('Y-m-d',strtotime($beginning_date))."' and '".date('Y-m-d',strtotime($ending_date))."' ";
            }
            if($is_trail>0)
            {
                $con.=" AND `at`.`trail_trade` = '0' ";
            }



             $con .= " ORDER BY broker_last_name,bm.first_name ASC ";


		     $q = "SELECT `at`.*,bm.first_name as broker_name,bm.last_name as broker_last_name,bm.id as broker_id,cm.first_name as client_name,cm.last_name as client_last_name,bt.batch_desc,br.name as branch_name
					FROM `".$this->table."` AS `at`
                    LEFT JOIN `".BATCH_MASTER."` as `bt` on `bt`.`id` = `at`.`batch`
                    LEFT JOIN `ft_branch_master` as `br` on `br`.`id` = `at`.`branch`
                    LEFT JOIN `".BROKER_MASTER."` as `bm` on `bm`.`id` = `at`.`broker_name`
                    LEFT JOIN `".CLIENT_MASTER."` as `cm` on `cm`.`id` = `at`.`client_name`
                    WHERE `at`.`is_delete`='0' ".$con." ";
            print($q);exit;
			$res = $this->re_db_query($q);


            if($this->re_db_num_rows($res)>0){
                $a = 0;
    			while($row = $this->re_db_fetch_array($res)){
    				$brokder_id=$row['broker_id'];
    				$row['product_name'] = $this->get_product_name_from($row['product_cate'],$row['product']);
    				$row['client_name'] = $row['client_last_name'].', '.$row['client_name'];
    				if(!isset($return[$row['broker_id']])){
    					 $return[$row['broker_id']]=array("broker"=>$row['broker_last_name'].', '.$row['broker_name'],"products"=>array());
    				}
    				if(!isset($return[$row['broker_id']]["products"][$row['product']])){
    					$return[$row['broker_id']]["products"][$row['product']]=array();
    				}

    				/*if(!isset($return[$row['broker_id']][$row['product']])){
                        $return[$row['broker_id']][$row['product']]=array();
    				}*/

    				$return[$row['broker_id']]["products"][$row['product']][]=$row;



    				/*if(isset($return[$row['product']])){
    			     //array_push($return,$row);
    					 $return[$row['product']][]=$row;
    				}
    				else{
    					  $return[$row['product']]=array();
    					 $return[$row['product']][]=$row;
    				}*/
    			}
            }

            /*foreach($return as $broker=>$value){
            	$col = array_column( $value, "product" );
				array_multisort( $col, SORT_ASC, $value );
            	$return[$broker]=$value;
            }*/

			return $return;
		}

		function select_year_to_date_sale_report($beginning_date,$ending_date,$company,$without_earning,$earning_by){

		        // 11/1/21 li - phase out field "rep_name", and rename "rep_number" to "broker_id" to normalize database
		    	$return = array();
		        $con='';

		        $con.=" and payroll_date between '".$beginning_date."' and '".$ending_date."' ";
		        if($without_earning!=1){
		        	$con.=" and net_earnings <> '' ";
		        }
		        if($earning_by == 2){
		        	$con.=" and branch > 0 ";
		        }
		        else{
		        		$con.=" and broker_id > 0 ";
		        }

		    	  $q = "SELECT `pr`.`id`,
		                     `pr`.`payroll_date`,
		                     `pr`.`broker_id` AS `rep_number`,
		                     `pr`.`broker_id` AS `rep_name`,
		                     `pr`.`clearing_number`,
		                     `pr`.`gross_production`,
		                     `pr`.`check_amount`,
		                     `pr`.`minimum_check_amount`,
		                     `pr`.`finra`,
		                     `pr`.`sipc`,
		                     `pr`.`sipc_gross`,
		                     `pr`.`net_production`,
		                     `pr`.`adjustments`,
		                     `pr`.`taxable_adjustments`,
		                     `pr`.`non-taxable_adjustments`,
		                     `pr`.`net_earnings`,
		                     `pr`.`status`,
		                     `pr`.`is_delete`,
		                     pr.branch,`pr`.`broker_id`,
		                     concat(bm.last_name,', ',bm.first_name) as broker_fullname,
		                     `bm`.first_name as broker_firstname,`bm`.last_name as broker_lastname,
		                     br.name as branch_name
		    			FROM `".PRIOR_PAYROLL_MASTER."` AS `pr`
		                LEFT JOIN `".BROKER_MASTER."` as `bm` on `bm`.`id` = `pr`.`broker_id`
		                LEFT JOIN ft_branch_master as   br on br.id = pr.branch
		                WHERE `pr`.`is_delete`='0' ".$con."
		                ORDER BY `bm`.`last_name` desc";
		            // echo $q;
		        $all_transactions = [];
		    	$res = $this->re_db_query($q);
		        if($this->re_db_num_rows($res)>0){
		            $a = 0;
		    		while($row = $this->re_db_fetch_array($res)){

		    				$branch_id = !empty($row['branch']) ? $row['branch'] : '-1';

		    				if(!isset($return[$branch_id])) {
		    					$return[$branch_id] = ['branch_name'=>$row['branch_name'],'transactions'=>[]];
		    				}
		    				$return[$branch_id]['transactions'][] = $row;

		    		     	array_push($all_transactions,$row);
		    		}
		        }

		    	return ($earning_by == 2) ? $return : $all_transactions;

		}

		function select_broker_ranking_sale_report($cat_ids = [],$company='',$rank_order_by=0,$limit=0,$earning_by=[]) {

		        // 11/1/21 li - phase out field "rep_name", and rename "rep_number" to "broker_id" to normalize database
		    	$return = array();

		        /*$ranks = ['Rank on Total sales','Rank on Gross concessions','Rank on Total Earnings',
                                    'Rank on Profitability'];*/

		   		$ranks = ['Rank on Total Earnings','Rank on Gross Concessions','Rank on Total Sales',
                                    'Rank on Profitability'];
		        $where = '';
		        $order_by = 'order by  bm.last_name desc';

		        if(!empty($cat_ids)) {
		        	$where.= ' AND tm.product_cate IN  ('.implode(',',$cat_ids).')';
		        }
		        if($company > 0) {
		        	$where.= ' AND tm.company='.$company;
		        }

		        if(!empty($rank_order_by)) {
		        	$order_by = 'order by '.(int) $rank_order_by.' desc';
		        }

		        if(!empty($earning_by)&&is_array($earning_by)) {

		        	list('earning_by'=>$earning_type,'beginning_date'=>$start_date,'ending_date'=>$end_date) = $earning_by;
		        	if($earning_type == 2) $where.=" and tm.trade_date between '".date('Y-m-d',strtotime($start_date))."' and '".date('Y-m-d',strtotime($end_date))."' ";
		        }

		        $limit_query = '';
		        if(!empty($limit)) $limit_query = 'LIMIT '.$limit;

		        $q = "SELECT
		       		SUM(tm.commission_received) as total_earnings,
		       		SUM(tm.invest_amount) as total_investment,
		        	SUM(tm.charge_amount) as total_concessions,
		        	SUM(tm.commission_received-tm.charge_amount) AS total_profit ,
		        	tm.broker_name,
		        	bm.internal as internal_id,
		        	concat(bm.last_name,', ',bm.first_name) as broker_fullname
		        	FROM $this->table tm
		        	LEFT JOIN $this->broker_master as bm on bm.id = tm.broker_name
		         	where 1=1 $where  group by broker_name $order_by  $limit_query ";

		    	$res = $this->re_db_query($q);
		        if($this->re_db_num_rows($res)>0){

		    		while($row = $this->re_db_fetch_array($res)){
		    		     array_push($return,$row);
		    		}
		        }
		    return $return;
		}
		public function select_annual_broker_report($year=0,$is_trail=0,$broker=0,$company=0,$date_type = 1) {
			$return = array();
            $con='';

            $date_column_name = ($date_type == 1) ? 'trade_date' : 'settlement_date';

            if($year > 0) $con.=' AND YEAR(at.'.$date_column_name.') = '.$year;

            if($broker>0) $con.=" AND `at`.`broker_name` = ".$broker." ";

            if($company > 0) $con.= ' AND at.company='.$company;

            if($is_trail>0) $con.=" AND `at`.`trail_trade` = '0' ";


            $q="SELECT  COUNT(*) as no_of_trades, at.$date_column_name as main_date,
                	EXTRACT(month FROM at.$date_column_name) as month_name,
                	`at`.product_cate,`at`.sponsor,`at`.product_cate,`at`.product,sum(`at`.invest_amount) as invest_amount,sum(`at`.charge_amount) as gross_conession,sum(`at`.commission_received) as commission_received,
                	SUM(at.commission_received-at.charge_amount) AS net_commission,
                	bm.first_name as broker_name,bm.last_name as broker_last_name,bm.id as broker_id,bm.internal as internal_id,cm.first_name as client_name,cm.last_name as client_last_name,bt.batch_desc,br.name as branch_name,pt.type as product_category_name,sm.name as sponsor_name FROM `".$this->table."` AS `at`
                    LEFT JOIN `".BATCH_MASTER."` as `bt` on `bt`.`id` = `at`.`batch`
                    LEFT JOIN `".PRODUCT_TYPE."` as `pt` on `pt`.`id` = `at`.`product_cate`
                    LEFT JOIN `ft_branch_master` as `br` on `br`.`id` = `at`.`branch`
                    LEFT JOIN `".BROKER_MASTER."` as `bm` on `bm`.`id` = `at`.`broker_name`
                    LEFT JOIN `".CLIENT_MASTER."` as `cm` on `cm`.`id` = `at`.`client_name`
                    LEFT JOIN `".SPONSOR_MASTER."` as `sm` on `sm`.`id` = `at`.`sponsor`
                    WHERE `at`.`is_delete`='0' ".$con." group by EXTRACT(month FROM at.$date_column_name)";

			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
    			while($row = $this->re_db_fetch_array($res)){
    				$return[$row['month_name']]=$row;
    			}
            }
			return $return;
		}
		function select_monthly_broker_production_report($company='',$earning_by=[]) {

		        // 11/1/21 li - phase out field "rep_name", and rename "rep_number" to "broker_id" to normalize database
		    	$return = array();

		        $ranks = ['Rank on Total sales','Rank on Gross concessions','Rank on Total Earnings',
                                    'Rank on Profitability'];

		        $where = '';
		        $order_by = 'order by  bm.last_name desc';


		        if($company > 0) {
		        	$where.= ' AND tm.company='.$company;
		        }

		        if(!empty($earning_by)&&is_array($earning_by)) {

		        	list('earning_by'=>$earning_type,'beginning_date'=>$start_date,'ending_date'=>$end_date) = $earning_by;
		        	if($earning_type == 2) $where.=" and tm.trade_date between '".date('Y-m-d',strtotime($start_date))."' and '".date('Y-m-d',strtotime($end_date))."' ";
		        }



		        $q = "SELECT
		        	SUM(tm.invest_amount) as total_investment,
		        	SUM(tm.charge_amount) as total_concessions,
		        	SUM(tm.commission_received) as total_earnings,
		        	SUM(tm.commission_received-tm.charge_amount) AS total_profit ,
		        	SUM(tm.commission_received-tm.charge_amount) AS net_commission,
		        	SUM(tm.commission_received) as total_commission_received,
		        	tm.broker_name,tm.product_cate,
		        	concat(bm.last_name,', ',bm.first_name) as broker_fullname,
		        	bm.internal as internal_id,
		        	pt.type as product_cat_name
		        	FROM $this->table tm
		        	LEFT JOIN $this->broker_master as bm on bm.id = tm.broker_name
		         	LEFT JOIN $this->product_type as pt on pt.id = tm.product_cate
		         	where 1=1 $where  group by broker_name,product_cate $order_by  $limit_query ";
		    	//echo $q;
		    	$res = $this->re_db_query($q);
		        if($this->re_db_num_rows($res)>0){

		    		while($row = $this->re_db_fetch_array($res)){
		    			//	$brokder_id=$row['broker_id'];
		    				if(!isset($return[$row['broker_name']])) {
		    					$return[$row['broker_name']] = [
		    							'broker_full_name'=>$row['broker_fullname'],
		    							'broker_id'=>$row['broker_name'],
		    							'internal_id'=>$row['internal_id'],
		    							'transactions'=>[]
		    						];
		    				}
		    		     	$return[$row['broker_name']]['transactions'][]= $row;
		    		}
		        }
		    return $return;
		}
		function select_monthly_branch_office_report($company=0,$branch=0,$end_date = '',$start_date= '' ) {

		        // 11/1/21 li - phase out field "rep_name", and rename "rep_number" to "broker_id" to normalize database
		    	$return = array();



		        $where = '';
		        $order_by = 'order by  bm.last_name desc';
		        if($company > 0) {
		        	$where.= ' AND tm.company='.$company;
		        }
		        if($branch > 0) {
		        	$where.= ' AND tm.branch='.$branch;
		        }

		        if(!empty($end_date)) {
		        	$where.= ' AND tm.trade_date <="'.date('Y-m-d',strtotime($end_date)).'"  ';
		        }

		        if(!empty($start_date)) {
		        	$where.= ' AND tm.trade_date >="'.date('Y-m-d',strtotime($start_date)).'"  ';
		        }
		        /*if(!empty($earning_by)&&is_array($earning_by)) {

		        	list('earning_by'=>$earning_type,'beginning_date'=>$start_date,'ending_date'=>$end_date) = $earning_by;
		        	if($earning_type == 2) $where.=" and tm.trade_date between '".date('Y-m-d',strtotime($start_date))."' and '".date('Y-m-d',strtotime($end_date))."' ";
		        }*/



		        $q = "SELECT
		        	SUM(tm.invest_amount) as total_investment,
		        	SUM(tm.charge_amount) as total_concessions,
		        	SUM(tm.commission_received) as total_earnings,
		        	SUM(tm.commission_received-tm.charge_amount) AS total_profit,
		        	SUM(tm.commission_received-tm.charge_amount) AS net_commission,
		        	SUM(tm.commission_received) as total_commission_received,
		        	tm.broker_name,tm.product_cate,br.name as branch_name,
		        	concat(bm.last_name,', ',bm.first_name) as broker_fullname,
		        	bm.internal as internal_id,
		        	pt.type as product_cat_name,tm.branch
		        	FROM $this->table tm
		        	LEFT JOIN $this->broker_master as bm on bm.id = tm.broker_name
		         	LEFT JOIN $this->product_type as pt on pt.id = tm.product_cate
		         	LEFT JOIN ft_branch_master as br on br.id = tm.branch
		         	where 1=1 $where  group by tm.branch,tm.broker_name,tm.product_cate $order_by  $limit_query ";
		    	//echo $q;
		    	$res = $this->re_db_query($q);
		        if($this->re_db_num_rows($res)>0){

		    		while($row = $this->re_db_fetch_array($res)){
		    			//	$brokder_id=$row['broker_id'];
		    				$branch_id = !empty($row['branch']) ? $row['branch'] : '-1';

		    				if(!isset($return[$branch_id])) {
		    					$return[$branch_id] = ['branch_name'=>$row['branch_name'],'brokers'=>[]];
		    				}
		    				if(!isset($return[$branch_id]['brokers'][$row['broker_name']])) {
		    					$return[$branch_id]['brokers'][$row['broker_name']] = ['broker_full_name'=>$row['broker_fullname'],'internal_id'=>$row['internal_id'],'transactions'=>[]];
		    				}
		    				$return[$branch_id]['brokers'][$row['broker_name']]['transactions'][] = $row;

		    				/*if(!isset($return[$branch_id][$row['broker_name']])) {
		    					$return[$branch_id][$row['broker_name']] = [
		    							'branch_name'=>$row['branch_name'],
		    							'branch_id'=>$row['branch'],
		    							'transactions'=>[]
		    						];
		    				}
		    		     	$return[$branch_id][$row['broker_name']]['transactions'][]= $row;*/
		    		}
		        }
		    return $return;
		}

}

?>