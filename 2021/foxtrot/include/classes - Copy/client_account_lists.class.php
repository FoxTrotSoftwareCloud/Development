<?php 
/**
 * 
 */
class client_account_list extends db
{
	
	public function get_client_account_list() {
		if (isset($_GET['page_no']) && $_GET['page_no']!="") {
			    $page_no = (int)$_GET['page_no'];
		    } 
		    else {
		        $page_no = 1;
	        }
	        $total_records_per_page = 3;
	        $offset = ($page_no-1) * $total_records_per_page;
			$previous_page = $page_no - 1;
			$next_page = $page_no + 1;
			$return = array();
			$q = "SELECT `client_ac`.*,sponsor.name as sponsor_name,client.id as cl_id,client.first_name,client.last_name,client.clearing_account as clearing_account,client.broker_name as broker_name
					FROM `".SPONSOR_MASTER."` AS `sponsor`
                    LEFT JOIN `".CLIENT_ACCOUNT."` as `client_ac` on `client_ac`.`sponsor_company`=`sponsor`.`id`
                    LEFT JOIN `".CLIENT_MASTER."` as `client` on
                    	`client`.`id`= `client_ac`.`client_id`
                    WHERE `client_ac`.`is_delete`='0' AND `client_ac`.`client_id`<>0 ";
            $q2="SELECT COUNT(*) as total_records FROM `".CLIENT_ACCOUNT."` AS `client_ac`";        
            if (isset($_GET['brokers'])) {
            	$brokers=implode(",", $_GET['brokers']);
            	$q.=" AND client.broker_name IN($brokers)";        	
            	// $q2.=" WHERE client.broker_name IN($brokers)";        	
            }
            if (isset($_GET['sponsors'])) {
            	$sponsors=implode(",", $_GET['sponsors']);
            	$q.=" AND client_ac.sponsor_company IN($sponsors)";        	
            	$q2.=" WHERE client_ac.sponsor_company IN($sponsors)";        	
            }        
            $q.="ORDER BY `client_ac`.`client_id` ASC LIMIT $offset,$total_records_per_page";        
			$res = $this->re_db_query($q);
			$res2 = $this->re_db_query($q2);
            if($this->re_db_num_rows($res)>0){
    			while($row = $this->re_db_fetch_array($res)){
    			     array_push($return,$row);
    			}
    			while ($row=$this->re_db_fetch_array($res2)) {
    				$total_count=$row;
    			}
            }
			return [$return,$total_count];
	}
}