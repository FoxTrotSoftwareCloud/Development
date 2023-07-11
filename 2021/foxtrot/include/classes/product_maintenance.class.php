<?php
class product_maintenance extends db
{

	public $table = PRODUCT_MAINTENANCE;
	public $errors = '';


	public function insert_update_product($data, $isReturn = false)
	{ 

		$id = isset($data['id']) ? $this->re_db_input($data['id']) : 0;
		$category = isset($data['product_category']) ? $this->re_db_input($data['product_category']) : '';
		$name = isset($data['name']) ? $this->re_db_input($data['name']) : '';
		$sponsor = isset($data['sponsor']) ? $this->re_db_input($data['sponsor']) : '';
		$ticker_symbol = isset($data['ticker_symbol']) ? $this->re_db_input($data['ticker_symbol']) : '';
		$cusip = isset($data['cusip']) ? $this->re_db_input($data['cusip']) : '';
		$security = isset($data['security']) ? $this->re_db_input($data['security']) : '';
		$receive = isset($data['allowable_receivable']) ? $this->re_db_input($data['allowable_receivable']) : 0;
		$income = isset($data['income']) ? $this->re_db_input($data['income']) : 0;
		$networth = isset($data['networth']) ? $this->re_db_input($data['networth']) : 0;
		$networthonly = isset($data['networthonly']) ? $this->re_db_input($data['networthonly']) : 0;
		$minimum_investment = isset($data['minimum_investment']) ? $this->re_db_input($data['minimum_investment']) : '';
		$minimum_offer = isset($data['minimum_offer']) ? $this->re_db_input($data['minimum_offer']) : '';
		$maximum_offer = isset($data['maximum_offer']) ? $this->re_db_input($data['maximum_offer']) : '';
		$objective = isset($data['objectives']) ? $this->re_db_input($data['objectives']) : '';
		$non_commissionable = isset($data['non_commissionable']) ? $this->re_db_input($data['non_commissionable']) : 0;
		$class_type = isset($data['class_type']) ? $this->re_db_input($data['class_type']) : '';
		$fund_code = isset($data['fund_code']) ? $this->re_db_input($data['fund_code']) : '';
		$sweep_fee = isset($data['sweep_fee']) ? $this->re_db_input($data['sweep_fee']) : 0;
		$min_threshold = isset($data['min_threshold']) ? $data['min_threshold'] : array();
		$max_threshold = isset($data['max_threshold']) ? $data['max_threshold'] : array();
		$threshold_id = isset($data['threshold_id']) ? $data['threshold_id'] : array();
		$min_rate = isset($data['min_rate']) ? $data['min_rate'] : array();
		$max_rate = isset($data['max_rate']) ? $data['max_rate'] : array();
		$ria_specific = isset($data['investment_banking_type']) ? $this->re_db_input($data['investment_banking_type']) : '';
		$ria_specific_type = isset($data['ria_specific_type']) ? $this->re_db_input($data['ria_specific_type']) : '';
		$based = isset($data['based_type']) ? $this->re_db_input($data['based_type']) : '';
		$fee_rate = isset($data['fee_rate']) ? $this->re_db_input($data['fee_rate']) : '';
		$st_bo = isset($data['stocks_bonds']) ? $this->re_db_input($data['stocks_bonds']) : '';
		$m_date = isset($data['maturity_date']) ? $this->re_db_input(date('Y-m-d', strtotime($data['maturity_date']))) : '0000-00-00
			';
		$type = isset($data['type']) ? $this->re_db_input($data['type']) : '';
		$var = isset($data['variable_annuities']) ? $this->re_db_input($data['variable_annuities']) : '';
		$reg_type = isset($data['registration_type']) ? $this->re_db_input($data['registration_type']) : '';
		//for import
		$for_import = isset($data['for_import']) ? $this->re_db_input($data['for_import']) : 'false';
		$file_id = isset($data['file_id']) ? $this->re_db_input($data['file_id']) : '';
		$detail_data_id = isset($data['detail_data_id']) ? $this->re_db_input($data['detail_data_id']) : '';


		if ($name == '') {
			$this->errors = 'Please enter product name.';
		} else if ($category == '') {
			$this->errors = 'Please select product category.';
		} else if ($sponsor == '') {
			$this->errors = 'Please select sponsor.';
		}
		if ($this->errors != '') {
			return $this->errors;
		} else {
			//-- Product Threshold 
			//- 06/15/22 Original code wasn't working - the page only stores the 1.max_threshold, 2.min_rate
			$productRates = [];
			$element = 0;
			foreach ($max_threshold as $key => $value) {
				if (!empty($value) and !empty($min_rate[$key])) {
					$productRates[$element]['max_threshold'] = (int)$value;
					$productRates[$element]['min_threshold'] = $key ? (int)$max_threshold[$key - 1] + 1 : 0;
					$productRates[$element]['min_rate'] = (float)$min_rate[$key];

					$element++;
				}
			}

			/* check duplicate record */
			$con = '';
			if ($id > 0) {   // update
				//$con = " AND `id`!='".$id."'";

				$q = "UPDATE `" . PRODUCT_LIST . "` SET `category`='" . $category . "',`name`='" . strtoupper($name) . "',`sponsor`='" . $sponsor . "',`ticker_symbol`='" . $ticker_symbol . "',`cusip`='" . $cusip . "',`security`='" . $security . "',`receive`='" . $receive . "',`income`='" . $income . "',`networth`='" . $networth . "',`networthonly`='" . $networthonly . "',`minimum_investment`='" . $minimum_investment . "',`minimum_offer`='" . $minimum_offer . "',`maximum_offer`='" . $maximum_offer . "',`objective`='" . $objective . "',`non_commissionable`='" . $non_commissionable . "',`class_type`='" . $class_type . "',`fund_code`='" . $fund_code . "',`sweep_fee`='" . $sweep_fee . "',`ria_specific`='" . $ria_specific . "',`ria_specific_type`='" . $ria_specific_type . "',`based`='" . $based . "',`fee_rate`='" . $fee_rate . "',`st_bo`='" . $st_bo . "',`m_date`='" . $m_date . "',`type`='" . $type . "',`var`='" . $var . "',`reg_type`='" . $reg_type . "'" . $this->update_common_sql() . " WHERE `id`='" . $id . "'";
				$res = $this->re_db_query($q);

				//-- Product Threshold/Commission Rate Breakpoints (MF Products)
				//- 06/16/22 Original code wasn't working - the page only stores the 1.max_threshold, 2.min_rate
				$getProdRates = $this->get_product_rates_ids($id);
				$getIndex = 0;
				$getLastIndex = ($getProdRates ? count($getProdRates) - 1 : 0);

				foreach ($productRates as $key => $row) {
					if (!empty($row['max_threshold']) && !empty($productRates[$key]['min_rate'])) {
						if (isset($getProdRates[$getIndex])) {
							$q = "UPDATE `" . PRODUCT_RATES . "`"
								. " SET "
								. "`product_id`=" . (int)$id
								. ",`min_threshold`='" . $row['min_threshold'] . "'"
								. ",`max_threshold`='" . $row['max_threshold'] . "'"
								. ",`min_rate`=" . $row['min_rate']
								. ",`max_rate`=0"
								. $this->update_common_sql()
								. " WHERE `id`=" . (int)$getProdRates[$getIndex];
							$getIndex++;
						} else {
							$q = "INSERT INTO `" . PRODUCT_RATES . "`"
								. " SET "
								. "`product_id`=" . (int)$id
								. ",`min_threshold`='" . $row['min_threshold'] . "'"
								. ",`max_threshold`='" . $row['max_threshold'] . "'"
								. ",`min_rate`=" . $row['min_rate']
								. ",`max_rate`=0"
								. $this->insert_common_sql();
						}
						$res = $this->re_db_query($q);
					}
				}

				if ($getIndex <= $getLastIndex) {
					for ($i = $getIndex; $i <= $getLastIndex; $i++) {
						$q = "UPDATE `" . PRODUCT_RATES . "`"
							. " SET `is_delete`=1"
							. " WHERE id=" . (int)$getProdRates[$i];
						$res = $this->re_db_query($q);
					}
				}

				if ($res) {
					//$_SESSION['tran']
					if ($isReturn) {
						$_SESSION['new_product_id'] = $id;
					}
					$_SESSION['success'] = UPDATE_MESSAGE;
					return true;
				} else {
					$_SESSION['warning'] = UNKWON_ERROR;
					return false;
				}
			} else { // insert
				/* check duplicate record */
				$symbolQuery = $cusipQuery = 0;
				$ticker_symbol = $this->re_db_input($ticker_symbol);
				$cusip = $this->re_db_input($cusip);

				if (!empty($ticker_symbol)) {
					$symbolQuery = $this->product_list_by_query("`ticker_symbol`='$ticker_symbol'");
				}

				if (!empty($cusip)) {
					$cusipQuery = $this->product_list_by_query("`cusip`='$cusip'");
				}

				if ($symbolQuery) {
					$this->errors = 'Symbol already exists.';
				} else if ($cusipQuery) {
					$this->errors = 'Cusip already exists..';
				} else {
					$q = "INSERT INTO `" . PRODUCT_LIST . "` SET `category`='" . $category . "',`name`='" . strtoupper($name) . "',`sponsor`='" . $sponsor . "',`ticker_symbol`='" . $ticker_symbol . "',`cusip`='" . $cusip . "',`security`='" . $security . "',`receive`='" . $receive . "',`income`='" . $income . "',`networth`='" . $networth . "',`networthonly`='" . $networthonly . "',`minimum_investment`='" . $minimum_investment . "',`minimum_offer`='" . $minimum_offer . "',`maximum_offer`='" . $maximum_offer . "',`objective`='" . $objective . "',`non_commissionable`='" . $non_commissionable . "',`class_type`='" . $class_type . "',`fund_code`='" . $fund_code . "',`sweep_fee`='" . $sweep_fee . "',`ria_specific`='" . $ria_specific . "',`ria_specific_type`='" . $ria_specific_type . "',`based`='" . $based . "',`fee_rate`='" . $fee_rate . "',`st_bo`='" . $st_bo . "',`m_date`='" . $m_date . "',`type`='" . $type . "',`var`='" . $var . "',`reg_type`='" . $reg_type . "'" . $this->insert_common_sql();

					$res = $this->re_db_query($q);
					$last_inserted_id = $this->re_db_insert_id();

					foreach ($min_threshold as $key_thres => $val_thres) {
						if ($val_thres != '' && $min_rate[$key_thres] > 0) {
							$q = "INSERT INTO `" . PRODUCT_RATES . "` SET `product_id`='" . $last_inserted_id . "',`min_threshold`='" . $val_thres . "',`max_threshold`='" . $max_threshold[$key_thres] . "',`min_rate`='" . $min_rate[$key_thres] . "',`max_rate`='" . $max_rate[$key_thres] . "'" . $this->insert_common_sql();
							$res = $this->re_db_query($q);
						}
					}

					if ($res) {
						if ($isReturn) {
							$_SESSION['new_product_id'] = $last_inserted_id;
						}
						$_SESSION['success'] = INSERT_MESSAGE;
						return true;
					} else {
						$_SESSION['warning'] = UNKWON_ERROR;
						return false;
					}
				}
			}
		}
	}

	public function get_product_rates_ids($product_id)
	{
		$return = array();
		$q = "SELECT `at`.id
					FROM `" . PRODUCT_RATES . "` AS `at`
                    WHERE `at`.`is_delete`='0' AND `at`.`product_id`='" . $product_id . "'";
		$res = $this->re_db_query($q);
		if ($this->re_db_num_rows($res) > 0) {
			while ($row = $this->re_db_fetch_array($res)) {
				array_push($return, $row['id']);
			}
		}
		return $return;
	}

	/**
	 * @param post array
	 * @return true if success, error message if any errors
	 * */
	public function insert_update($data, $isReturn = false)
	{ 

		$id = isset($data['id']) ? $this->re_db_input($data['id']) : 0;
		$category = isset($data['product_category']) ? $this->re_db_input($data['product_category']) : '';
		$name = isset($data['name']) ? $this->re_db_input($data['name']) : '';
		$sponsor = isset($data['sponsor']) ? $this->re_db_input($data['sponsor']) : '';
		$ticker_symbol = isset($data['ticker_symbol']) ? $this->re_db_input($data['ticker_symbol']) : '';
		$cusip = isset($data['cusip']) ? $this->re_db_input($data['cusip']) : '';
		$security = isset($data['security']) ? $this->re_db_input($data['security']) : '';
		$receive = isset($data['allowable_receivable']) ? $this->re_db_input($data['allowable_receivable']) : 0;
		$income = isset($data['income']) ? $this->re_db_input($data['income']) : 0;
		$networth = isset($data['networth']) ? $this->re_db_input($data['networth']) : 0;
		$networthonly = isset($data['networthonly']) ? $this->re_db_input($data['networthonly']) : 0;
		$minimum_investment = isset($data['minimum_investment']) ? $this->re_db_input($data['minimum_investment']) : 0;
		$minimum_offer = isset($data['minimum_offer']) ? $this->re_db_input($data['minimum_offer']) : '';
		$maximum_offer = isset($data['maximum_offer']) ? $this->re_db_input($data['maximum_offer']) : '';
		$objective = isset($data['objectives']) ? $this->re_db_input($data['objectives']) : '';
		$non_commissionable = isset($data['non_commissionable']) ? $this->re_db_input($data['non_commissionable']) : 0;
		$class_type = isset($data['class_type']) ? $this->re_db_input($data['class_type']) : '';
		$fund_code = isset($data['fund_code']) ? $this->re_db_input($data['fund_code']) : '';
		$sweep_fee = isset($data['sweep_fee']) ? $this->re_db_input($data['sweep_fee']) : 0;
		$min_threshold = isset($data['min_threshold']) ? $data['min_threshold'] : array();
		$max_threshold = isset($data['max_threshold']) ? $data['max_threshold'] : array();
		$min_rate = isset($data['min_rate']) ? $data['min_rate'] : array();
		$max_rate = isset($data['max_rate']) ? $data['max_rate'] : array();
		$ria_specific = isset($data['investment_banking_type']) ? $this->re_db_input($data['investment_banking_type']) : '';
		$ria_specific_type = isset($data['ria_specific_type']) ? $this->re_db_input($data['ria_specific_type']) : '';
		$based = isset($data['based_type']) ? $this->re_db_input($data['based_type']) : '';
		$fee_rate = isset($data['fee_rate']) ? $this->re_db_input($data['fee_rate']) : '';
		$st_bo = isset($data['stocks_bonds']) ? $this->re_db_input($data['stocks_bonds']) : '';
		$m_date = isset($data['maturity_date']) ? $this->re_db_input(date('Y-m-d', strtotime($data['maturity_date']))) : '0000-00-00
			';
		$type = isset($data['type']) ? $this->re_db_input($data['type']) : '';
		$var = isset($data['variable_annuities']) ? $this->re_db_input($data['variable_annuities']) : '';
		$reg_type = isset($data['registration_type']) ? $this->re_db_input($data['registration_type']) : '';
		//for import
		$for_import = isset($data['for_import']) ? $this->re_db_input($data['for_import']) : 'false';
		$file_id = isset($data['file_id']) ? $this->re_db_input($data['file_id']) : '';
		$detail_data_id = isset($data['detail_data_id']) ? $this->re_db_input($data['detail_data_id']) : '';

		if ($name == '') {
			$this->errors = 'Please enter product name.';
		} else if ($category == '') {
			$this->errors = 'Please select product category.';
		} else if ($sponsor == '') {
			$this->errors = 'Please select sponsor.';
		}
		if ($this->errors != '') {
			return $this->errors;
		} else {

			/* check duplicate record */
			$con = '';
			if ($id > 0) {
				$con = " AND `id`!='" . $id . "'";
			}
			$q = "SELECT * FROM `product_category_" . $category . "` WHERE `is_delete`='0' AND `name`='" . $name . "'" . $con;
			$res = $this->re_db_query($q);
			$return = $this->re_db_num_rows($res);
			if ($return > 0) {
				$this->errors = 'This product is already exists.';
			}

			if ($this->errors != '') {
				return $this->errors;
			} else if ($id >= 0) {
				if ($id == 0) {
					$q = "INSERT INTO `product_category_" . $category . "` SET `category`='" . $category . "',`name`='" . strtoupper($name) . "',`sponsor`='" . $sponsor . "',`ticker_symbol`='" . $ticker_symbol . "',`cusip`='" . $cusip . "',`security`='" . $security . "',`receive`='" . $receive . "',`income`='" . $income . "',`networth`='" . $networth . "',`networthonly`='" . $networthonly . "',`minimum_investment`='" . $minimum_investment . "',`minimum_offer`='" . $minimum_offer . "',`maximum_offer`='" . $maximum_offer . "',`objective`='" . $objective . "',`non_commissionable`='" . $non_commissionable . "',`class_type`='" . $class_type . "',`fund_code`='" . $fund_code . "',`sweep_fee`='" . $sweep_fee . "',`ria_specific`='" . $ria_specific . "',`ria_specific_type`='" . $ria_specific_type . "',`based`='" . $based . "',`fee_rate`='" . $fee_rate . "',`st_bo`='" . $st_bo . "',`m_date`='" . $m_date . "',`type`='" . $type . "',`var`='" . $var . "',`reg_type`='" . $reg_type . "'" . $this->insert_common_sql();
					$res = $this->re_db_query($q);
					$last_inserted_id = $this->re_db_insert_id();

					foreach ($min_threshold as $key_thres => $val_thres) {
						if ($val_thres != '' && $min_rate[$key_thres] > 0) {
							$q = "INSERT INTO `product_rates_" . $category . "` SET `product_id`='" . $last_inserted_id . "',`min_threshold`='" . $val_thres . "',`max_threshold`='" . $max_threshold[$key_thres] . "',`min_rate`='" . $min_rate[$key_thres] . "',`max_rate`='" . $max_rate[$key_thres] . "'" . $this->insert_common_sql();
							$res = $this->re_db_query($q);
						}
					}

					if ($res) {
						if ($isReturn) {
							$_SESSION['new_product_id'] = $last_inserted_id;
						}
						$_SESSION['success'] = INSERT_MESSAGE;
						return true;
					} else {
						$_SESSION['warning'] = UNKWON_ERROR;
						return false;
					}
				} else if ($id > 0) {
					$q = "UPDATE `product_category_" . $category . "` SET `category`='" . $category . "',`name`='" . strtoupper($name) . "',`sponsor`='" . $sponsor . "',`ticker_symbol`='" . $ticker_symbol . "',`cusip`='" . $cusip . "',`security`='" . $security . "',`receive`='" . $receive . "',`income`='" . $income . "',`networth`='" . $networth . "',`networthonly`='" . $networthonly . "',`minimum_investment`='" . $minimum_investment . "',`minimum_offer`='" . $minimum_offer . "',`maximum_offer`='" . $maximum_offer . "',`objective`='" . $objective . "',`non_commissionable`='" . $non_commissionable . "',`class_type`='" . $class_type . "',`fund_code`='" . $fund_code . "',`sweep_fee`='" . $sweep_fee . "',`ria_specific`='" . $ria_specific . "',`ria_specific_type`='" . $ria_specific_type . "',`based`='" . $based . "',`fee_rate`='" . $fee_rate . "',`st_bo`='" . $st_bo . "',`m_date`='" . $m_date . "',`type`='" . $type . "',`var`='" . $var . "',`reg_type`='" . $reg_type . "'" . $this->update_common_sql() . " WHERE `id`='" . $id . "'";
					$res = $this->re_db_query($q);


					$q = "UPDATE `product_rates_" . $category . "` SET `is_delete`='1' WHERE `product_id`='" . $id . "'";
					$res = $this->re_db_query($q);

					foreach ($min_threshold as $key_thres => $val_thres) {
						if ($val_thres != '' && $min_rate[$key_thres] > 0) {
							$q = "INSERT INTO `product_rates_" . $category . "` SET `product_id`='" . $id . "',`min_threshold`='" . $val_thres . "',`max_threshold`='" . $max_threshold[$key_thres] . "',`min_rate`='" . $min_rate[$key_thres] . "',`max_rate`='" . $max_rate[$key_thres] . "'" . $this->insert_common_sql();
							$res = $this->re_db_query($q);
						}
					}
					if ($res) {
						//$_SESSION['tran']
						if ($isReturn) {
							$_SESSION['new_product_id'] = $id;
						}
						$_SESSION['success'] = UPDATE_MESSAGE;
						return true;
					} else {
						$_SESSION['warning'] = UNKWON_ERROR;
						return false;
					}
				}
			} else {
				$_SESSION['warning'] = UNKWON_ERROR;
				return false;
			}
		}
	}
	public function insert_update_product_notes($data)
	{ 
		$notes_id = isset($data['notes_id']) ? $this->re_db_input($data['notes_id']) : 0;
		$date = isset($data['date']) ? $this->re_db_input($data['date']) : '';
		$user_id = isset($data['user_id']) ? $this->re_db_input($data['user_id']) : '';
		$client_note = isset($data['client_note']) ? $this->re_db_input($data['client_note']) : '';

		if ($client_note == '') {
			$this->errors = 'Please enter notes.';
		}
		if ($this->errors != '') {
			return $this->errors;
		} else {
			if ($notes_id == 0) {
				$q = "INSERT INTO `" . PRODUCT_NOTES . "` SET `date`='" . $date . "',`user_id`='" . $user_id . "',`notes`='" . $client_note . "'" . $this->insert_common_sql();
				$res = $this->re_db_query($q);

				$notes_id = $this->re_db_insert_id();
				if ($res) {
					$_SESSION['success'] = INSERT_MESSAGE;
					return true;
				} else {
					$_SESSION['warning'] = UNKWON_ERROR;
					return false;
				}
			} else if ($notes_id > 0) {

				$q = "UPDATE `" . PRODUCT_NOTES . "` SET `date`='" . $date . "',`user_id`='" . $user_id . "',`notes`='" . $client_note . "'" . $this->update_common_sql() . " WHERE `id`='" . $notes_id . "'";
				$res = $this->re_db_query($q);

				if ($res) {
					$_SESSION['success'] = UPDATE_MESSAGE;
					return true;
				} else {
					$_SESSION['warning'] = UNKWON_ERROR;
					return false;
				}
			}
		}
	}
	public function insert_update_product_attach($data)
	{ 
		$attach_id = isset($data['attach_id']) ? $this->re_db_input($data['attach_id']) : 0;
		$date = isset($data['date']) ? $this->re_db_input($data['date']) : '';
		$user_id = isset($data['user_id']) ? $this->re_db_input($data['user_id']) : '';
		$file = isset($_FILES['file_attach']) ? $_FILES['file_attach'] : array();
		$valid_file = array('png', 'jpg', 'jpeg', 'bmp', 'pdf', 'xls', 'txt', 'xlsx');
		$attachment = $file;
		$file = '';
		$file_name = isset($attachment['name']) ? $attachment['name'] : '';
		$tmp_name = isset($attachment['tmp_name']) ? $attachment['tmp_name'] : '';
		$error = isset($attachment['error']) ? $attachment['error'] : 0;
		$size = isset($attachment['size']) ? $attachment['size'] : '';
		$type = isset($attachment['type']) ? $attachment['type'] : '';
		$target_dir = DIR_FS . "upload/";
		$ext = strtolower(end(explode('.', $file_name)));

		if ($file_name == '') {
			$this->errors = 'Please select file.';
		}

		if ($file_name != '') {
			if (!in_array($ext, $valid_file)) {
				$this->errors = "Please select valid file. valid file extensions are ( 'png', 'jpg', 'jpeg', 'bmp', 'pdf', 'xls', 'txt', 'xlsx')";
			} else {
				$attachment_file = time() . rand(100000, 999999) . '.' . $ext;
				move_uploaded_file($tmp_name, $target_dir . $attachment_file);
				$file = $attachment_file;

				if ($attach_id == 0) {
					$q = "INSERT INTO `" . PRODUCT_ATTACH . "` SET `date`='" . $date . "',`user_id`='" . $user_id . "',`attach`='" . $file . "' ,`file_name`='" . $file_name . "'" . $this->insert_common_sql();
					$res = $this->re_db_query($q);

					$attach_id = $this->re_db_insert_id();
					if ($res) {
						$_SESSION['success'] = INSERT_MESSAGE;
						return true;
					} else {
						$_SESSION['warning'] = UNKWON_ERROR;
						return false;
					}
				} else if ($attach_id > 0) {

					$q = "UPDATE `" . PRODUCT_ATTACH . "` SET `date`='" . $date . "',`user_id`='" . $user_id . "',`attach`='" . $file . "' ,`file_name`='" . $file_name . "'" . $this->update_common_sql() . " WHERE `id`='" . $attach_id . "'";
					$res = $this->re_db_query($q);

					if ($res) {
						$_SESSION['success'] = UPDATE_MESSAGE;
						return true;
					} else {
						$_SESSION['warning'] = UNKWON_ERROR;
						return false;
					}
				}
			}
		}
		if ($this->errors != '') {
			return $this->errors;
		}
	}
	public function select_attach()
	{
		$return = array();

		$q = "SELECT `s`.*
					FROM `" . PRODUCT_ATTACH . "` AS `s`
                    WHERE `s`.`is_delete`='0'
                    ORDER BY `s`.`id` ASC";
		$res = $this->re_db_query($q);
		if ($this->re_db_num_rows($res) > 0) {

			$a = 0;
			while ($row = $this->re_db_fetch_array($res)) {
				array_push($return, $row);
			}
		}
		return $return;
	}
	public function select_notes()
	{
		$return = array();

		$q = "SELECT `s`.*
					FROM `" . PRODUCT_NOTES . "` AS `s`
                    WHERE `s`.`is_delete`='0'
                    ORDER BY `s`.`id` ASC";
		$res = $this->re_db_query($q);
		if ($this->re_db_num_rows($res) > 0) {

			$a = 0;
			while ($row = $this->re_db_fetch_array($res)) {
				array_push($return, $row);
			}
		}
		return $return;
	}
	public function delete_attach($id)
	{
		$id = trim($this->re_db_input($id));
		if ($id > 0) {
			$q = "UPDATE `" . PRODUCT_ATTACH . "` SET `is_delete`='1' WHERE `id`='" . $id . "'";
			$res = $this->re_db_query($q);
			if ($res) {
				$_SESSION['success'] = DELETE_MESSAGE;
				return true;
			} else {
				$_SESSION['warning'] = UNKWON_ERROR;
				return false;
			}
		} else {
			$_SESSION['warning'] = UNKWON_ERROR;
			return false;
		}
	}
	public function get_previous_product($id, $category)
	{
		$return = array();

		$q = "SELECT `at`.*
					FROM `product_category_" . $category . "` AS `at`
                    WHERE `at`.`is_delete`='0' and `at`.`id`<" . $id . "
                    ORDER BY `at`.`id` DESC LIMIT 1";
		$res = $this->re_db_query($q);
		if ($this->re_db_num_rows($res) > 0) {
			$return = $this->re_db_fetch_array($res);
		} else {
			return false;
		}
		return $return;
	}
	public function get_transaction_on_product($id, $category)
	{
		$return = array();

		$q = "SELECT `at`.*,`pro`.`name` as `product_name`
					FROM `" . TRANSACTION_MASTER . "` AS `at`
                    LEFT JOIN `" . PRODUCT_LIST . "` AS `pro` on `pro`.`id`=`at`.`product`
                    WHERE `at`.`is_delete`='0' and `at`.`product`=" . $id . " and `at`.`product_cate`=" . $category . "
                    ORDER BY `at`.`id` DESC";
		$res = $this->re_db_query($q);
		if ($this->re_db_num_rows($res) > 0) {
			while ($row = $this->re_db_fetch_array($res)) {
				array_push($return, $row);
			}
		}
		return $return;
	}
	public function get_next_product($id, $category)
	{
		$return = array();

		$q = "SELECT `at`.*
					FROM `product_category_" . $category . "` AS `at`
                    WHERE `at`.`is_delete`='0' and `at`.`id`>" . $id . "
                    ORDER BY `at`.`id` ASC LIMIT 1";
		$res = $this->re_db_query($q);
		if ($this->re_db_num_rows($res) > 0) {
			$return = $this->re_db_fetch_array($res);
		} else {
			return false;
		}
		return $return;
	}
	public function get_product_changes($id, $category)
	{
		$return = array();
		$q = "SELECT `at`.*,u.first_name as user_initial
					FROM `" . PRODUCT_HISTORY . "` AS `at`
                    LEFT JOIN `" . USER_MASTER . "` as `u` on `u`.`id`=`at`.`modified_by`
                    WHERE `at`.`is_delete`='0' AND `at`.`product_id`='" . $id . "'";
		$res = $this->re_db_query($q);
		if ($this->re_db_num_rows($res) > 0) {
			while ($row = $this->re_db_fetch_array($res)) {
				array_push($return, $row);
			}
		}
		return $return;
	}
	public function get_sponsor_name($id)
	{
		$return = array();
		$q = "SELECT `at`.name as sponsor
					FROM `" . SPONSOR_MASTER . "` AS `at`
                    WHERE `at`.`is_delete`='0' AND `at`.`id`='" . $id . "'";
		$res = $this->re_db_query($q);
		if ($this->re_db_num_rows($res) > 0) {
			$return = $this->re_db_fetch_array($res);
		}
		return $return;
	}
	public function delete_notes($id)
	{
		$id = trim($this->re_db_input($id));
		if ($id > 0) {
			$q = "UPDATE `" . PRODUCT_NOTES . "` SET `is_delete`='1' WHERE `id`='" . $id . "'";
			$res = $this->re_db_query($q);
			if ($res) {
				$_SESSION['success'] = DELETE_MESSAGE;
				return true;
			} else {
				$_SESSION['warning'] = UNKWON_ERROR;
				return false;
			}
		} else {
			$_SESSION['warning'] = UNKWON_ERROR;
			return false;
		}
	}
	/**
	 * @param int status, default all
	 * @return array of record if success, error message if any errors
	 * */
	public function select_product_category($category = '')
	{
		$return = array();
		$categoryQuery = '';

		if (!empty($category)) {
			$categoryQuery = " AND `at`.`category`=" . (int)($this->re_db_input($category));
		}

		// 02/24/22 Products consolidated into one table "ft_products"
		// $q = "SELECT `at`.*,pc.type,sp.name as sponsor
		// 		FROM `product_category_".$category."` AS `at`
		//         LEFT JOIN `".PRODUCT_TYPE."` as `pc` on `pc`.`id`=`at`.`category`
		//         LEFT JOIN `".SPONSOR_MASTER."` as `sp` on `sp`.`id`=`at`.`sponsor`
		//         WHERE `at`.`is_delete`='0'
		//         ORDER BY `at`.`id` ASC";
		$q = "SELECT `at`.*,pc.type,sp.name as sponsor"
			. " FROM `" . PRODUCT_LIST . "` AS `at`"
			. " LEFT JOIN `" . PRODUCT_TYPE . "` as `pc` on `pc`.`id`=`at`.`category`"
			. " LEFT JOIN `" . SPONSOR_MASTER . "` as `sp` on `sp`.`id`=`at`.`sponsor`"
			. " WHERE `at`.`is_delete`='0'"
			. $categoryQuery
			. " ORDER BY `at`.`id` ASC";

		$res = $this->re_db_query($q);
		if ($this->re_db_num_rows($res) > 0) {
			$a = 0;
			while ($row = $this->re_db_fetch_array($res)) {
				array_push($return, $row);
			}
		}
		return $return;
	}
	public function select_category($id = 0)
	{
		$return = array();
		$con = (empty((int)$id) ? '' : " AND `id` = " . (int)$this->re_db_input($id));

		$q = "SELECT `at`.*"
			. " FROM `" . PRODUCT_TYPE . "` AS `at`"
			. " WHERE `at`.`is_delete`='0'"
			. $con
			. " ORDER BY `at`.`id` ASC";
		$res = $this->re_db_query($q);

		if ($this->re_db_num_rows($res) > 0) {
			while ($row = $this->re_db_fetch_array($res)) {
				array_push($return, $row);
			}
		}
		return $return;
	}
	public function select_sponsor($sortOrder = 1)
	{
		$return = array();
		$sortOrder = (int)$this->re_db_input($sortOrder);

		$orderBy = " `at`.`id`";
		if ($sortOrder == 1) {
			$orderBy = " `at`.`name`, " . $orderBy;
		}

		$q = "SELECT `at`.*
					FROM `" . SPONSOR_MASTER . "` AS `at`
                    WHERE `at`.`is_delete`='0'
                    ORDER BY $orderBy";
		$res = $this->re_db_query($q);
		if ($this->re_db_num_rows($res) > 0) {
			$a = 0;
			$return = $this->re_db_fetch_all($res);
		}
		return $return;
	}
	public function search_product($search_text = '', $search_category = '')
	{
		$return = array();
		$con = '';
		if ($search_text != '' && $search_text >= 0) {
			$con .= " AND (`clm`.`name` LIKE '" . $search_text . "%' || `clm`.`cusip` LIKE '" . $search_text . "%' || `clm`.`ticker_symbol` LIKE '" . $search_text . "%') ";
		}

		$q = "SELECT `clm`.*,pc.type,sp.name as sponsor
					FROM `product_category_" . $search_category . "` AS `clm`
                    LEFT JOIN `" . PRODUCT_TYPE . "` as `pc` on `pc`.`id`=`clm`.`category`
                    LEFT JOIN `" . SPONSOR_MASTER . "` as `sp` on `sp`.`id`=`clm`.`sponsor`
                    WHERE `clm`.`is_delete`='0' " . $con . "
                    ORDER BY `clm`.`id` ASC ";
		$res = $this->re_db_query($q);
		if ($this->re_db_num_rows($res) > 0) {
			$a = 0;
			while ($row = $this->re_db_fetch_array($res)) {
			
				array_push($return, $row);
			}
		}
		return $return;
	}
	/**
	 * @param int id
	 * @return array of record if success, error message if any errors
	 * */
	public function edit_product($id, $category = '')
	{
		$return = array();
		$con = '';
		$id = (int)$this->re_db_input($id);

		if (!empty($category)) {
			$con = " AND `at`.`category` = " . (int)$this->re_db_input($category);
		}

		$q = "SELECT `at`.*"
			. " FROM `" . PRODUCT_LIST . "` AS `at`"
			. " WHERE `at`.`is_delete`='0'"
			. " AND `at`.`id`='" . $id . "'"
			. $con;
		$res = $this->re_db_query($q);
		if ($this->re_db_num_rows($res) > 0) {
			$return = $this->re_db_fetch_array($res);
		}
		return $return;
	}

	//-- 06/16/22 Order by lower limit, so the Maintain Product grid will be in ascending order, according to $ amount
	public function edit_product_rates($id, $category = '')
	{
		$return = array();
		$id = (int)$this->re_db_input($id);

		$q = "SELECT `at`.*"
			. " FROM `" . PRODUCT_RATES . "` AS `at`"
			. " WHERE `at`.`is_delete`='0'"
			. " AND `at`.`product_id`=$id"
			. " ORDER BY `at`.`min_threshold`,`at`.`max_threshold`";

		$res = $this->re_db_query($q);

		if ($this->re_db_num_rows($res) > 0) {
			while ($row = $this->re_db_fetch_array($res)) {
				array_push($return, $row);
			}
		}
		return $return;
	}

	/**
	 * @param id of record
	 * @param status to update
	 * @return true if success, false message if any errors
	 * */
	public function product_status($id, $status, $category = '')
	{
		$id = trim($this->re_db_input($id));
		$status = trim($this->re_db_input($status));
		if ($id > 0 && ($status == 0 || $status == 1)) {
			$q = "UPDATE `" . PRODUCT_LIST . "` SET `status`='" . $status . "' WHERE `id`='" . $id . "'";
			$res = $this->re_db_query($q);
			if ($res) {
				$_SESSION['success'] = STATUS_MESSAGE;
				return true;
			} else {
				$_SESSION['warning'] = UNKWON_ERROR;
				return false;
			}
		} else {
			$_SESSION['warning'] = UNKWON_ERROR;
			return false;
		}
	}

	/**
	 * @param id of record
	 * @return true if success, false message if any errors
	 * */
	public function product_delete($id, $category = '')
	{
		$id = trim($this->re_db_input($id));
		$category = trim($this->re_db_input($category));

		if ($id > 0) {
			$q = "UPDATE `" . PRODUCT_LIST . "` SET `is_delete`='1' WHERE `id`='" . $id . "'";
			$res = $this->re_db_query($q);

			if ($res) {
				$_SESSION['success'] = DELETE_MESSAGE;
				return true;
			} else {
				$_SESSION['warning'] = UNKWON_ERROR;
				return false;
			}
		} else {
			$_SESSION['warning'] = UNKWON_ERROR;
			return false;
		}
	}

	public function load_product_list()
	{
		$return = array();

		$q = "SELECT `at`.*,pc.type,sp.name as sponsor
					FROM `" . PRODUCT_LIST . "` AS `at`
                    LEFT JOIN `" . PRODUCT_TYPE . "` as `pc` on `pc`.`id`=`at`.`category`
                    LEFT JOIN `" . SPONSOR_MASTER . "` as `sp` on `sp`.`id`=`at`.`sponsor`
                    WHERE `at`.`is_delete`='0'
                    ORDER BY `at`.`id` ASC";

		$res = $this->re_db_query($q);

		if ($this->re_db_num_rows($res) > 0) {
			$a = 0;
			while ($row = $this->re_db_fetch_array($res)) {
				array_push($return, $row);
			}
		}

		return $return;
	}

	public function product_list()
	{
		$return = array();
		$q = "SELECT id, name
					FROM `" . PRODUCT_LIST . "`
                    WHERE `is_delete`='0'
                    ORDER BY `id` ASC";

		$res = $this->re_db_query($q);

		if ($this->re_db_num_rows($res) > 0) {
			$a = 0;
			while ($row = $this->re_db_fetch_array($res)) {
				array_push($return, $row);
			}
		}

		return $return;
	}

	public function product_list_by_name()
	{
		$return = array();

		$q = "SELECT id, name, category
					FROM `" . PRODUCT_LIST . "`
                    WHERE `is_delete`='0'
                    ORDER BY `name` ASC";

		$res = $this->re_db_query($q);

		if ($this->re_db_num_rows($res) > 0) {
			$a = 0;
			while ($row = $this->re_db_fetch_array($res)) {
				array_push($return, $row);
			}
		}

		return $return;
	}

	/*
		 * Generic function for the Import module to pull products by various criteria: CUSIP_number, Symbol, Product Category, id, name, etc. -> AND  any combination thereof
		 * @param string $queryString
		 * @return array
		 */
	public function product_list_by_query($queryString = '1')
	{
		$return = array();
		$queryString = trim($queryString);

		$q = "SELECT *"
			. " FROM `" . PRODUCT_LIST . "`"
			. " WHERE $queryString"
			. " ORDER BY `name`, `id`";

		$res = $this->re_db_query($q);

		if ($this->re_db_num_rows($res) > 0) {
			$return = $this->re_db_fetch_array($res);
		}

		return $return;
	}
}
