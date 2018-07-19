<?php
	class product_master extends db{
		
		public $table = PRODUCT_TYPE;
		public $errors = '';
        
        /**
		 * @param post array
		 * @return true if success, error message if any errors
		 * */
		public function insert_update($data){
			$id = isset($data['id'])?$this->re_db_input($data['id']):0;
			$type = isset($data['type'])?$this->re_db_input($data['type']):'';
            $type_code = isset($data['type_code'])?$this->re_db_input($data['type_code']):'';
			
			if($type==''){
				$this->errors = 'Please enter type.';
			}
			
			if($this->errors!=''){
				return $this->errors;
			}
			else{
				    
				/* check duplicate record */
				$con = '';
				if($id>0){
					$con = " AND `id`!='".$id."'";
				}
				$q = "SELECT * FROM `".$this->table."` WHERE `is_delete`='0' AND `type`='".$type."' ".$con;
				$res = $this->re_db_query($q);
				$return = $this->re_db_num_rows($res);
				if($return>0){
					$this->errors = 'This type is already exists.';
				}
				
				if($this->errors!=''){
					return $this->errors;
				}
				else if($id>=0){
					if($id==0){
						$q = "INSERT INTO `".$this->table."` SET `type`='".$type."',`type_code`='".$type_code."'".$this->insert_common_sql();
						$res = $this->re_db_query($q);
                        $id = $this->re_db_insert_id();
						if($res){
						    $q = "CREATE TABLE product_category_".$id."
                                    (id INT(12) PRIMARY KEY AUTO_INCREMENT,
                                        category INT(12),
                                        name VARCHAR(100),
                                        sponsor VARCHAR(100),
                                        ticker_symbol VARCHAR(100),
                                        cusip VARCHAR(100),
                                        security VARCHAR(100),
                                        receive TINYINT(1) NOT NULL DEFAULT '0',
                                        income VARCHAR(100),
                                        networth VARCHAR(100),
                                        networthonly VARCHAR(100),
                                        minimum_investment VARCHAR(100),
                                        minimum_offer VARCHAR(100),
                                        maximum_offer VARCHAR(100),
                                        objective VARCHAR(100),
                                        non_commissionable TINYINT(1) NOT NULL DEFAULT '0',
                                        class_type VARCHAR(100),
                                        fund_code VARCHAR(100),
                                        sweep_fee TINYINT(1) NOT NULL DEFAULT '0',
                                        ria_specific VARCHAR(100),
                                        ria_specific_type VARCHAR(100),
                                        based VARCHAR(100),
                                        fee_rate VARCHAR(100),
                                        st_bo VARCHAR(100),
                                        m_date DATE,
                                        type VARCHAR(100),
                                        var VARCHAR(100),
                                        reg_type VARCHAR(100),
                                        status TINYINT(1) NOT NULL DEFAULT '1',
                                        is_delete TINYINT(1) NOT NULL DEFAULT '0',
                                        created_by  INT(12),
                                        created_time DATETIME,
                                        created_ip VARCHAR(100),
                                        modified_by  INT(12),
                                        modified_time DATETIME,
                                        modified_ip VARCHAR(100)
                                    );";
						    $res = $this->re_db_query($q);
                            
                            $q = "CREATE TABLE product_history_".$id."
                                    (id INT(12) PRIMARY KEY AUTO_INCREMENT,
                                        product_id INT(12),
                                        field VARCHAR(100),
                                        old_value VARCHAR(100),
                                        new_value VARCHAR(100),
                                        status TINYINT(1) NOT NULL DEFAULT '1',
                                        is_delete TINYINT(1) NOT NULL DEFAULT '0',
                                        created_by  INT(12),
                                        created_time DATETIME,
                                        created_ip VARCHAR(100),
                                        modified_by  INT(12),
                                        modified_time DATETIME,
                                        modified_ip VARCHAR(100)
                                    );";
						    $res = $this->re_db_query($q);
                            
                            $q = "CREATE TABLE product_rates_".$id."
                                    (id INT(12) PRIMARY KEY AUTO_INCREMENT,
                                        product_id INT(12),
                                        min_threshold VARCHAR(100),
                                        max_threshold VARCHAR(100),
                                        min_rate FLOAT(8,2),
                                        max_rate FLOAT(8,2),
                                        status TINYINT(1) NOT NULL DEFAULT '1',
                                        is_delete TINYINT(1) NOT NULL DEFAULT '0',
                                        created_by  INT(12),
                                        created_time DATETIME,
                                        created_ip VARCHAR(100),
                                        modified_by  INT(12),
                                        modified_time DATETIME,
                                        modified_ip VARCHAR(100)
                                    );";
						    $res = $this->re_db_query($q);
                            
                            /*$q = "CREATE TRIGGER updateProduct_".$id." AFTER UPDATE ON product_category_".$id."
                                    FOR EACH ROW
                                    
                                    BEGIN
                                    	IF NEW.name <> OLD.name THEN  
                                            INSERT INTO product_history_".$id." (product_id,field,old_value,new_value,status,is_delete,created_by,created_time,created_ip,modified_by,modified_time) 
                                    		  							  VALUES(NEW.id,'name',OLD.name,NEW.name,NEW.status,NEW.is_delete,NEW.created_by,NEW.created_time,NEW.created_ip,NEW.modified_by,NEW.modified_time);
                                        END IF;
                                        IF NEW.category <> OLD.category THEN  
                                            INSERT INTO product_history_".$id." (product_id,field,old_value,new_value,status,is_delete,created_by,created_time,created_ip,modified_by,modified_time) 
                                    		  							  VALUES(NEW.id,'category',OLD.category,NEW.category,NEW.status,NEW.is_delete,NEW.created_by,NEW.created_time,NEW.created_ip,NEW.modified_by,NEW.modified_time);
                                        END IF;
                                        IF NEW.sponsor <> OLD.sponsor THEN  
                                            INSERT INTO product_history_".$id." (product_id,field,old_value,new_value,status,is_delete,created_by,created_time,created_ip,modified_by,modified_time) 
                                    		  							  VALUES(NEW.id,'sponsor',OLD.sponsor,NEW.sponsor,NEW.status,NEW.is_delete,NEW.created_by,NEW.created_time,NEW.created_ip,NEW.modified_by,NEW.modified_time);
                                        END IF;
                                        IF NEW.ticker_symbol <> OLD.ticker_symbol THEN  
                                            INSERT INTO product_history_".$id." (product_id,field,old_value,new_value,status,is_delete,created_by,created_time,created_ip,modified_by,modified_time) 
                                    		  							  VALUES(NEW.id,'ticker_symbol',OLD.ticker_symbol,NEW.ticker_symbol,NEW.status,NEW.is_delete,NEW.created_by,NEW.created_time,NEW.created_ip,NEW.modified_by,NEW.modified_time);
                                        END IF;
                                        IF NEW.cusip <> OLD.cusip THEN  
                                            INSERT INTO product_history_".$id." (product_id,field,old_value,new_value,status,is_delete,created_by,created_time,created_ip,modified_by,modified_time) 
                                    		  							  VALUES(NEW.id,'cusip',OLD.cusip,NEW.cusip,NEW.status,NEW.is_delete,NEW.created_by,NEW.created_time,NEW.created_ip,NEW.modified_by,NEW.modified_time);
                                        END IF;
                                        IF NEW.security <> OLD.security THEN  
                                            INSERT INTO product_history_".$id." (product_id,field,old_value,new_value,status,is_delete,created_by,created_time,created_ip,modified_by,modified_time) 
                                    		  							  VALUES(NEW.id,'security',OLD.security,NEW.security,NEW.status,NEW.is_delete,NEW.created_by,NEW.created_time,NEW.created_ip,NEW.modified_by,NEW.modified_time);
                                        END IF;
                                        IF NEW.receive <> OLD.receive THEN  
                                            INSERT INTO product_history_".$id." (product_id,field,old_value,new_value,status,is_delete,created_by,created_time,created_ip,modified_by,modified_time) 
                                    		  							  VALUES(NEW.id,'receive',OLD.receive,NEW.receive,NEW.status,NEW.is_delete,NEW.created_by,NEW.created_time,NEW.created_ip,NEW.modified_by,NEW.modified_time);
                                        END IF;
                                        IF NEW.income <> OLD.income THEN  
                                            INSERT INTO product_history_".$id." (product_id,field,old_value,new_value,status,is_delete,created_by,created_time,created_ip,modified_by,modified_time) 
                                    		  							  VALUES(NEW.id,'income',OLD.income,NEW.income,NEW.status,NEW.is_delete,NEW.created_by,NEW.created_time,NEW.created_ip,NEW.modified_by,NEW.modified_time);
                                        END IF;
                                        IF NEW.networth <> OLD.networth THEN  
                                            INSERT INTO product_history_".$id." (product_id,field,old_value,new_value,status,is_delete,created_by,created_time,created_ip,modified_by,modified_time) 
                                    		  							  VALUES(NEW.id,'networth',OLD.networth,NEW.networth,NEW.status,NEW.is_delete,NEW.created_by,NEW.created_time,NEW.created_ip,NEW.modified_by,NEW.modified_time);
                                        END IF;
                                        IF NEW.networthonly <> OLD.networthonly THEN  
                                            INSERT INTO product_history_".$id." (product_id,field,old_value,new_value,status,is_delete,created_by,created_time,created_ip,modified_by,modified_time) 
                                    		  							  VALUES(NEW.id,'networthonly',OLD.networthonly,NEW.networthonly,NEW.status,NEW.is_delete,NEW.created_by,NEW.created_time,NEW.created_ip,NEW.modified_by,NEW.modified_time);
                                        END IF;
                                        IF NEW.minimum_investment <> OLD.minimum_investment THEN  
                                            INSERT INTO product_history_".$id." (product_id,field,old_value,new_value,status,is_delete,created_by,created_time,created_ip,modified_by,modified_time) 
                                    		  							  VALUES(NEW.id,'minimum_investment',OLD.minimum_investment,NEW.minimum_investment,NEW.status,NEW.is_delete,NEW.created_by,NEW.created_time,NEW.created_ip,NEW.modified_by,NEW.modified_time);
                                        END IF;
                                        IF NEW.minimum_offer <> OLD.minimum_offer THEN  
                                            INSERT INTO product_history_".$id." (product_id,field,old_value,new_value,status,is_delete,created_by,created_time,created_ip,modified_by,modified_time) 
                                    		  							  VALUES(NEW.id,'minimum_offer',OLD.minimum_offer,NEW.minimum_offer,NEW.status,NEW.is_delete,NEW.created_by,NEW.created_time,NEW.created_ip,NEW.modified_by,NEW.modified_time);
                                        END IF;
                                        IF NEW.maximum_offer <> OLD.maximum_offer THEN  
                                            INSERT INTO product_history_".$id." (product_id,field,old_value,new_value,status,is_delete,created_by,created_time,created_ip,modified_by,modified_time) 
                                    		  							  VALUES(NEW.id,'maximum_offer',OLD.maximum_offer,NEW.maximum_offer,NEW.status,NEW.is_delete,NEW.created_by,NEW.created_time,NEW.created_ip,NEW.modified_by,NEW.modified_time);
                                        END IF;
                                        IF NEW.objective <> OLD.objective THEN  
                                            INSERT INTO product_history_".$id." (product_id,field,old_value,new_value,status,is_delete,created_by,created_time,created_ip,modified_by,modified_time) 
                                    		  							  VALUES(NEW.id,'objective',OLD.objective,NEW.objective,NEW.status,NEW.is_delete,NEW.created_by,NEW.created_time,NEW.created_ip,NEW.modified_by,NEW.modified_time);
                                        END IF;
                                        IF NEW.non_commissionable <> OLD.non_commissionable THEN  
                                            INSERT INTO product_history_".$id." (product_id,field,old_value,new_value,status,is_delete,created_by,created_time,created_ip,modified_by,modified_time) 
                                    		  							  VALUES(NEW.id,'non_commissionable',OLD.non_commissionable,NEW.non_commissionable,NEW.status,NEW.is_delete,NEW.created_by,NEW.created_time,NEW.created_ip,NEW.modified_by,NEW.modified_time);
                                        END IF;
                                        IF NEW.class_type <> OLD.class_type THEN  
                                            INSERT INTO product_history_".$id." (product_id,field,old_value,new_value,status,is_delete,created_by,created_time,created_ip,modified_by,modified_time) 
                                    		  							  VALUES(NEW.id,'class_type',OLD.class_type,NEW.class_type,NEW.status,NEW.is_delete,NEW.created_by,NEW.created_time,NEW.created_ip,NEW.modified_by,NEW.modified_time);
                                        END IF;
                                        IF NEW.fund_code <> OLD.fund_code THEN  
                                            INSERT INTO product_history_".$id." (product_id,field,old_value,new_value,status,is_delete,created_by,created_time,created_ip,modified_by,modified_time) 
                                    		  							  VALUES(NEW.id,'fund_code',OLD.fund_code,NEW.fund_code,NEW.status,NEW.is_delete,NEW.created_by,NEW.created_time,NEW.created_ip,NEW.modified_by,NEW.modified_time);
                                        END IF;
                                        IF NEW.sweep_fee <> OLD.sweep_fee THEN  
                                            INSERT INTO product_history_".$id." (product_id,field,old_value,new_value,status,is_delete,created_by,created_time,created_ip,modified_by,modified_time) 
                                    		  							  VALUES(NEW.id,'sweep_fee',OLD.sweep_fee,NEW.sweep_fee,NEW.status,NEW.is_delete,NEW.created_by,NEW.created_time,NEW.created_ip,NEW.modified_by,NEW.modified_time);
                                        END IF;
                                        IF NEW.ria_specific <> OLD.ria_specific THEN  
                                            INSERT INTO product_history_".$id." (product_id,field,old_value,new_value,status,is_delete,created_by,created_time,created_ip,modified_by,modified_time) 
                                    		  							  VALUES(NEW.id,'ria_specific',OLD.ria_specific,NEW.ria_specific,NEW.status,NEW.is_delete,NEW.created_by,NEW.created_time,NEW.created_ip,NEW.modified_by,NEW.modified_time);
                                        END IF;
                                        IF NEW.ria_specific_type <> OLD.ria_specific_type THEN  
                                            INSERT INTO product_history_".$id." (product_id,field,old_value,new_value,status,is_delete,created_by,created_time,created_ip,modified_by,modified_time) 
                                    		  							  VALUES(NEW.id,'ria_specific_type',OLD.ria_specific_type,NEW.ria_specific_type,NEW.status,NEW.is_delete,NEW.created_by,NEW.created_time,NEW.created_ip,NEW.modified_by,NEW.modified_time);
                                        END IF;
                                        IF NEW.based <> OLD.based THEN  
                                            INSERT INTO product_history_".$id." (product_id,field,old_value,new_value,status,is_delete,created_by,created_time,created_ip,modified_by,modified_time) 
                                    		  							  VALUES(NEW.id,'based',OLD.based,NEW.based,NEW.status,NEW.is_delete,NEW.created_by,NEW.created_time,NEW.created_ip,NEW.modified_by,NEW.modified_time);
                                        END IF;
                                        IF NEW.fee_rate <> OLD.fee_rate THEN  
                                            INSERT INTO product_history_".$id." (product_id,field,old_value,new_value,status,is_delete,created_by,created_time,created_ip,modified_by,modified_time) 
                                    		  							  VALUES(NEW.id,'fee_rate',OLD.fee_rate,NEW.fee_rate,NEW.status,NEW.is_delete,NEW.created_by,NEW.created_time,NEW.created_ip,NEW.modified_by,NEW.modified_time);
                                        END IF;
                                        IF NEW.st_bo <> OLD.st_bo THEN  
                                            INSERT INTO product_history_".$id." (product_id,field,old_value,new_value,status,is_delete,created_by,created_time,created_ip,modified_by,modified_time) 
                                    		  							  VALUES(NEW.id,'st_bo',OLD.st_bo,NEW.st_bo,NEW.status,NEW.is_delete,NEW.created_by,NEW.created_time,NEW.created_ip,NEW.modified_by,NEW.modified_time);
                                        END IF;
                                        IF NEW.m_date <> OLD.m_date THEN  
                                            INSERT INTO product_history_".$id." (product_id,field,old_value,new_value,status,is_delete,created_by,created_time,created_ip,modified_by,modified_time) 
                                    		  							  VALUES(NEW.id,'m_date',OLD.m_date,NEW.m_date,NEW.status,NEW.is_delete,NEW.created_by,NEW.created_time,NEW.created_ip,NEW.modified_by,NEW.modified_time);
                                        END IF;
                                        IF NEW.type <> OLD.type THEN  
                                            INSERT INTO product_history_".$id." (product_id,field,old_value,new_value,status,is_delete,created_by,created_time,created_ip,modified_by,modified_time) 
                                    		  							  VALUES(NEW.id,'type',OLD.type,NEW.type,NEW.status,NEW.is_delete,NEW.created_by,NEW.created_time,NEW.created_ip,NEW.modified_by,NEW.modified_time);
                                        END IF;
                                        IF NEW.var <> OLD.var THEN  
                                            INSERT INTO product_history_".$id." (product_id,field,old_value,new_value,status,is_delete,created_by,created_time,created_ip,modified_by,modified_time) 
                                    		  							  VALUES(NEW.id,'var',OLD.var,NEW.var,NEW.status,NEW.is_delete,NEW.created_by,NEW.created_time,NEW.created_ip,NEW.modified_by,NEW.modified_time);
                                        END IF;
                                        IF NEW.reg_type <> OLD.reg_type THEN  
                                            INSERT INTO product_history_".$id." (product_id,field,old_value,new_value,status,is_delete,created_by,created_time,created_ip,modified_by,modified_time) 
                                    		  							  VALUES(NEW.id,'reg_type',OLD.reg_type,NEW.reg_type,NEW.status,NEW.is_delete,NEW.created_by,NEW.created_time,NEW.created_ip,NEW.modified_by,NEW.modified_time);
                                        END IF;
                                    END";
						    $res = $this->re_db_query($q);*/
                            
                            
						    $_SESSION['success'] = INSERT_MESSAGE;
							return true;
						}
						else{
							$_SESSION['warning'] = UNKWON_ERROR;
							return false;
						}
					}
					else if($id>0){
						$q = "UPDATE `".$this->table."` SET `type`='".$type."',`type_code`='".$type_code."' ".$this->update_common_sql()." WHERE `id`='".$id."'";
						$res = $this->re_db_query($q);
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
        
        /**
		 * @param int status, default all
		 * @return array of record if success, error message if any errors
		 * */
		public function select_product_type(){
			$return = array();
			
			$q = "SELECT `at`.*
					FROM `".$this->table."` AS `at`
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
        
        /**
		 * @param int id
		 * @return array of record if success, error message if any errors
		 * */
		public function edit($id){
			$return = array();
			$q = "SELECT `at`.*
					FROM `".$this->table."` AS `at`
                    WHERE `at`.`is_delete`='0' AND `at`.`id`='".$id."'";
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
    			$return = $this->re_db_fetch_array($res);
            }
			return $return;
		}
        
        /**
		 * @param id of record
		 * @param status to update
		 * @return true if success, false message if any errors
		 * */
		public function status($id,$status){
			$id = trim($this->re_db_input($id));
			$status = trim($this->re_db_input($status));
			if($id>0 && ($status==0 || $status==1) ){
				$q = "UPDATE `".$this->table."` SET `status`='".$status."' WHERE `id`='".$id."'";
				$res = $this->re_db_query($q);
				if($res){
				    $_SESSION['success'] = STATUS_MESSAGE;
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
		
		/**
		 * @param id of record
		 * @return true if success, false message if any errors
		 * */
		/*public function delete($id){
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
		}*/
        
    }
?>