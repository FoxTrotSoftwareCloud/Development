<?php 

/**
 * "SELECT broker.first_name as bfname, broker.last_name as lfname,`client`.*
	FROM `".BROKER_MASTER."` AS `broker`
    LEFT JOIN `".CLIENT_MASTER."` as `client` on `client`.`broker_name`=`broker`.`id`
    WHERE `broker`.`is_delete`='0' ORDER BY `broker`.`id` ASC"
 */
class client_list extends db
{
	
	public $errors = '';

	public $table = CLIENT_MASTER;

		public function get_client_list() 
		{

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
			$q = "SELECT broker.first_name as bfname, broker.last_name as lfname,`client`.*
					FROM `".BROKER_MASTER."` AS `broker`
                    LEFT JOIN `".CLIENT_MASTER."` as `client` on `client`.`broker_name`=`broker`.`id`
                    WHERE `broker`.`is_delete`='0'";  //AND `broker`.`broker_id`='".$id."'";
            $q2="SELECT COUNT(*) as total_records FROM `".CLIENT_MASTER."` AS `client`";        
            if (isset($_GET['brokers'])) {
            	$bankers=implode(",", $_GET['brokers']);
            	$q.=" AND client.broker_name IN($bankers)";        	
            	$q2.=" WHERE client.broker_name IN($bankers)";        	
            }
            if (isset($_GET['states'])) {
            	$states=implode(",", $_GET['states']);
            	$q.=" AND client.state IN($states)";        	
            	$q2.=" WHERE client.state IN($states)";        	
            }        
            $q.="ORDER BY `broker`.`id` ASC LIMIT $offset,$total_records_per_page";        
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
?>