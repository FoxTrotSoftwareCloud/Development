<?php 
/**
 * 
 */
class client_review extends db
{
	public $errors = '';

	public $table = CLIENT_MASTER;
	
	public function get_client_report($state,$broker) {
		$return = array();
        $con='';
        $con2='';
        if ($state!=0) {
        	$con.=" AND client.state=$state";
        }
        if ($broker!=0) {
        	$con.=" AND client.broker_name='".$broker."'";
        	$con2.=" AND broker.id='".$broker."'";
        }
        $all_brokers=array();
        $broker_query="SELECT CONCAT(broker.last_name,' ',broker.first_name)  broker_order, broker.first_name as bfname, broker.middle_name as mi , broker.last_name as lfname,broker.id as broker_id,broker.fund as broker_fund FROM `".BROKER_MASTER."` AS `broker` WHERE `broker`.`is_delete`='0'".$con2." ORDER BY broker_order ASC";

        $client_query = "SELECT CONCAT(client.last_name,' ',client.first_name) client_order,`client`.*
				FROM `".CLIENT_MASTER."` AS `client`
	            WHERE `client`.`is_delete`='0' AND client.first_name!=''".$con." ORDER BY client_order ASC";
	    $res = $this->re_db_query($client_query);
	    $res2 = $this->re_db_query($broker_query);
        if($this->re_db_num_rows($res)>0){
            $a = 0;
			while($row = $this->re_db_fetch_array($res)){
			     array_push($return,$row);
			}
        }
        if($this->re_db_num_rows($res2)>0){
            $a = 0;
			while($row = $this->re_db_fetch_array($res2)){
			     array_push($all_brokers,$row);
			}
        }

        foreach($all_brokers as &$broker) {
        	$broker['clients']=array_filter($return,function($client) use ($broker){
        		return $broker['broker_id']==$client['broker_name'] ? true :false;
        	});
        }
		return $all_brokers;        
	}

	public function get_client_account_list($sponsor,$broker) {
			$return = array();
			$con='';
			$con2='';
			$con11='';
            if ($broker!=0) {
            	$con.=" AND client.broker_name =$broker";
            	$con11.=" AND broker.id='".$broker."'";        	
            }
            if ($sponsor!=0) {
            	$con2.=" AND client_ac.sponsor_company=$sponsor";        	
            }
            $all_brokers=array();  
            $all_clients=array();
            $broker_query="SELECT CONCAT(broker.last_name,' ',broker.first_name)  broker_order, broker.first_name as bfname, broker.middle_name as mi , broker.last_name as lfname,broker.id as broker_id,broker.fund as broker_fund FROM `".BROKER_MASTER."` AS `broker` WHERE `broker`.`is_delete`='0'".$con11." ORDER BY broker_order ASC";

            $client_query="SELECT CONCAT(client.last_name,' ',client.first_name) client_order , client.id as cl_id,client.first_name,client.last_name,client.clearing_account as clearing_account,client.broker_name as broker_name,client.client_ssn,client.mi,client_ac.client_id FROM `".CLIENT_MASTER."` as `client` 
            	LEFT JOIN `".CLIENT_ACCOUNT."` as `client_ac` on `client_ac`.`client_id`=`client`.`id`
            	WHERE `client`.`is_delete`='0' ".$con.$con2." GROUP BY client.id ORDER BY client_order ASC";      
			$client_account_query = "SELECT `client_ac`.*,sponsor.name as sponsor_name
					FROM `".SPONSOR_MASTER."` AS `sponsor`
                    LEFT JOIN `".CLIENT_ACCOUNT."` as `client_ac` on `client_ac`.`sponsor_company`=`sponsor`.`id`
                    WHERE `client_ac`.`is_delete`='0' AND `client_ac`.`client_id`<>0 ".$con2." ORDER BY `client_ac`.`client_id` ASC";           
			$res = $this->re_db_query($client_query);
			$res2 = $this->re_db_query($client_account_query);
			$res3 = $this->re_db_query($broker_query);
            if($this->re_db_num_rows($res)>0){
    			while($row = $this->re_db_fetch_array($res)){
    			     array_push($all_clients,$row);
    			}
            }
            if($this->re_db_num_rows($res2)>0){
    			while($row = $this->re_db_fetch_array($res2)){
    			     array_push($return,$row);
    			}
            }
            if($this->re_db_num_rows($res3)>0){
	            $a = 0;
				while($row = $this->re_db_fetch_array($res3)){
				     array_push($all_brokers,$row);
				}
	        }

            foreach($all_clients as &$client) {
	        	$client['client_accounts']=array_filter($return,function($client_ac) use ($client){
	        		return $client['cl_id']==$client_ac['client_id'] ? true :false;
	        	});
	        }
	        foreach($all_brokers as &$broker) {
	        	$broker['clients']=array_filter($all_clients,function($client) use ($broker){
	        		return $broker['broker_id']==$client['broker_name'] ? true :false;
	        	});
	        }
			return $all_brokers;
	}

	public function get_client_review_report($broker_id,$beginning_date,$ending_date,$dont_contact_client=0) {
		$return = array();
		$con='';
		$con2='';
		if (!empty($beginning_date) && !empty($ending_date)) {
        	$con.=" AND DATE(client.reviewed_at) BETWEEN '".$beginning_date."' AND '".$ending_date."'";
        	
        }
        if ($broker_id!=0) {
        	$con2.=" AND broker.id='".$broker_id."'";        	
        }
        if($dont_contact_client > 0) {
        	$con.= ' and client.do_not_contact = 0 ';
        }
		$all_brokers=array();
		$all_clients=array();
		$all_client_ids=array();
        $broker_query="SELECT CONCAT(broker.last_name,' ',broker.first_name)  broker_order, broker.first_name as bfname, broker.last_name as lfname,broker.id as broker_id,broker.fund as broker_fund FROM `".BROKER_MASTER."` AS `broker`

        	 WHERE `broker`.`is_delete`='0'".$con2." ORDER BY broker_order ASC";
        $client_query = "SELECT CONCAT(client.last_name,' ',client.first_name) client_order,client_ac.account_no as client_acssn,client_ac.client_id,client_ac.created_time as ac_created_on,client_ac.sponsor_company as client_sponsor,`client`.*
			FROM `".CLIENT_MASTER."` AS `client`
            LEFT JOIN `".CLIENT_ACCOUNT."` as `client_ac` on `client_ac`.`client_id`=`client`.`id`
            WHERE `client`.`is_delete`='0' AND client.is_reviewed=1 AND client.first_name!=''".$con." ORDER BY client_order ASC";
            // echo $client_query;die;
        $client_ids="SELECT CONCAT(client.last_name,' ',client.first_name) client_order, id FROM `".CLIENT_MASTER."` AS `client` WHERE `client`.`is_delete`='0' ORDER BY client_order ASC";    
        $res = $this->re_db_query($client_query);
        $res1_1 = $this->re_db_query($client_ids);
		$res2 = $this->re_db_query($broker_query);
		if($this->re_db_num_rows($res1_1)>0){
			while($row = $this->re_db_fetch_array($res1_1)){
			     array_push($all_client_ids,$row);
			}
        }
        if($this->re_db_num_rows($res)>0){
			while($row = $this->re_db_fetch_array($res)){
			     array_push($all_clients,$row);
			}
        }
        if($this->re_db_num_rows($res2)>0){
			while($row = $this->re_db_fetch_array($res2)){
			     array_push($all_brokers,$row);
			}
        }

        foreach($all_client_ids as &$client_id) {
        	$temp=array_filter($all_clients,function($client_ac) use ($client_id){
        		return $client_id['id']==$client_ac['id'] ? true :false;
        	});
        	$account_ids=array_unique(array_column($temp, 'client_acssn'));
        	$account_created=array_column($temp, 'ac_created_on');
        	$client_sponsor=array_column($temp, 'client_sponsor');
        	$temp2=array_values(array_unique($temp));
        	$temp2['account_list']=array_combine($account_ids, $account_created);
        	$temp2['client_sponsor']=$client_sponsor;
        	$client_id['client_data'][$temp2[0]['reviewed_by']]=$temp2;
        }
        foreach($all_brokers as &$broker) {

        	$broker['client_accounts']=array_filter($all_client_ids,function($client_ac) use ($broker){
        		// print_r($client_ac);die;
        		return $broker['broker_id']==$client_ac['client_data'][$broker['broker_id']][0]['reviewed_by'] ? true :false;
        	});
        }
        return $all_brokers;    
	}
}
?>