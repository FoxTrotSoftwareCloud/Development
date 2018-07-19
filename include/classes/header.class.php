<?php

    class header_class extends db{
        
        public $errors = '';
        
        
        /*public function menu_select(){
			$return = array();
			
			$q = "SELECT `mm`.*,`umr`.* FROM `menu_master` as `mm` LEFT JOIN `ft_user_menu_rights` as `umr` on `umr`.`link_id`=`mm`.`link_id` WHERE `mm`.`parent_id`=0";
			
			$res = $this->re_db_query($q);
            if($this->re_db_num_rows($res)>0){
                $a = 0;
    			while($row = $this->re_db_fetch_array($res)){
    			     array_push($return,$row);
                     
    			}
            }
			return $return;
		}*/
		public function menu_select(){
    		$return = array();
    		
    		$q = "SELECT `m`.*
                FROM `".MENU_MASTER."` AS `m` LEFT JOIN `ft_user_menu_rights` as `umr` on `umr`.`link_id`=`m`.`link_id`
                WHERE `m`.`is_delete`='0' AND `m`.`parent_id`='0' AND `umr`.`user_id`='".$_SESSION['user_id']."'  ";
    		$res = $this->re_db_query($q);
    		while($row = $this->re_db_fetch_array($res)){  
    		    $row['submenu'] = $this->sub_menu_select($row['link_id']);
    			array_push($return,$row); 
                
    		}
    		return $return;
   		}
        public function sub_menu_select($link_id){
    		$return = array();
    		
    		$q = "SELECT `m`.*
                FROM `".MENU_MASTER."` AS `m` LEFT JOIN `ft_user_menu_rights` as `umr` on `umr`.`link_id`=`m`.`link_id`
                WHERE `m`.`is_delete`='0' AND `m`.`parent_id`='".$link_id."' AND `umr`.`user_id`='".$_SESSION['user_id']."' 
                ORDER BY `m`.`sort_order` ASC ";
    		$res = $this->re_db_query($q);
    		while($row = $this->re_db_fetch_array($res)){ 
    		    $row['submenu'] = $this->sub_submenu_select($row['link_id']);
    			array_push($return,$row);
    		}
    		return $return;
   		}
        public function sub_submenu_select($link_id){
    		$return = array();
    		
    		$q = "SELECT `m`.*
                FROM `".MENU_MASTER."` AS `m` LEFT JOIN `ft_user_menu_rights` as `umr` on `umr`.`link_id`=`m`.`link_id`
                WHERE `m`.`is_delete`='0' AND `m`.`parent_id`='".$link_id."' AND `umr`.`user_id`='".$_SESSION['user_id']."' 
                ORDER BY `m`.`sort_order` ASC ";
    		$res = $this->re_db_query($q);
    		while($row = $this->re_db_fetch_array($res)){ 
    		    array_push($return,$row);
    		}
    		return $return;
   		}
    }
?>