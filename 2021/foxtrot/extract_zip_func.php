<?php
public function insert_update_files($data){
            
            $dir    = "idcfile/";
            $files1 = scandir($dir);echo '<pre>';print_r($files1);exit;
            
			$file = isset($_FILES['file'])?$_FILES['file']:array();
            $valid_file = array('zip');
            
            $file_import = '';  
            $ext_filename = '';
            
            $file_name = isset($file['name'])?$file['name']:'';
            $tmp_name = isset($file['tmp_name'])?$file['tmp_name']:'';
            $error = isset($file['error'])?$file['error']:0;
            $size = isset($file['size'])?$file['size']:'';
            $type = isset($file['type'])?$file['type']:'';
            $target_dir = DIR_FS."import_files/";
            $ext = strtolower(end(explode('.',$file_name)));
            
            if($file_name!='')
            {
                if(!in_array($ext,$valid_file))
                {
                    $this->errors = 'Please select valid file.';
                }
                else
                {
                      $zip = new ZipArchive;
                      $res = $zip->open($tmp_name);
                      
                      if ($res === TRUE) {
                        for ($i = 0; $i < $zip->numFiles; $i++) {
                             
                             $ext_filename = $zip->getNameIndex($i);
                             
                         }
                         $zip->extractTo(DIR_FS."extract_files/");
                         $zip->close();
                      } 
                }
            }
            if($this->errors!=''){
				return $this->errors;
			}
            else
            {
                $q = "INSERT INTO `".IMPORT_CURRENT_FILES."` SET `imported_date`='".date('Y-m-d')."',`last_processed_date`='',`file_name`='".$ext_filename."',`file_type`='".$file_name."',`batch`=''".$this->insert_common_sql();
    			$res = $this->re_db_query($q);
                $id = $this->re_db_insert_id();
    			if($res){
    			    $_SESSION['success'] = INSERT_MESSAGE;
    				return true;
    			}
    			else{
    				$_SESSION['warning'] = UNKWON_ERROR;
    				return false;
    			}
            }
		}
?>