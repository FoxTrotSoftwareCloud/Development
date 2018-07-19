<?php 
require_once("include/config.php");
require_once(DIR_FS."islogin.php");
    
$instance = new client_maintenance();   
$get_attach = $instance->select_attach();
$attach_id = 0;
?>
<table class="table table-bordered table-stripped table-hover">
    <thead>
        <th>Date</th>
        <th>User</th>
        <th>File Name</th>
        <th>Upload File</th>
    </thead>
    <tbody>
    <?php foreach($get_attach as $key=>$val){
        $attach_id = $val['id'];?>
        <tr>
            <td><?php echo date('d/m/Y',strtotime($val['date']));?></td>
            <input type="hidden" name="date" id="date" value="<?php echo date('Y-m-d',strtotime($val['date']));?>"/>
            <td><?php echo $val['user_id'];?></td>
            <input type="hidden" name="user_id" id="user_id" value="<?php echo $val['user_id'];?>"/>
            <td><?php echo $val['file_name'];?></td>
            <td>
            <form id="add_client_attach_<?php echo $attach_id;?>" name="add_client_attach_<?php echo $attach_id;?>" onsubmit="return attachsubmit(<?php echo $attach_id;?>);" method="post" enctype="multipart/form-data">  
                <input type="file" class="form-control" name="file_attach" id="file_attach" style="width: 40% !important; display: inline;" value="<?php echo $val['attach'];?>"/>
                <input type="hidden" name="attach_id" id="attach_id" value="<?php echo $attach_id;?>"/>
                <input type="hidden" name="add_attach" value="Add Attach"  />&nbsp;&nbsp;&nbsp;&nbsp;
	            <button type="submit" class="btn btn-sm btn-warning" name="add_attach" value="Add Attach" style="display: inline;"><i class="fa fa-save"></i> Save</button>
                <a href="<?php echo SITE_URL."upload/".$val['attach']; ?>" download="<?php echo $val['file_name'];?>" class="btn btn-sm btn-success" ><i class="fa fa-trash"></i> Download</a>
                <a href="#" onclick="delete_attach(<?php echo $attach_id;?>);" class="btn btn-sm btn-danger confirm"><i class="fa fa-download"></i> Delete</a>              
            </form>
            </td>
        </tr>
    <?php } $attach_id++;?>
    <tr id="add_row_attach" style="display: none;">
            <td><?php echo date('d/m/Y');?></td>
            <input type="hidden" name="date" id="date" value="<?php echo date('Y-m-d');?>"/>
            <td><?php echo $_SESSION['user_name'];?></td>
            <input type="hidden" name="user_id" id="user_id" value="<?php echo $_SESSION['user_name'];?>"/>
            <td>No File</td>
            <td>
            <form id="add_client_attach_<?php echo $attach_id;?>" name="add_client_attach_<?php echo $attach_id;?>" onsubmit="return attachsubmit(<?php echo $attach_id;?>);" method="post" enctype="multipart/form-data">
                <input type="file" class="form-control" name="file_attach" id="file_attach" style="width: 40% !important; display: inline;"/>
                
                <input type="hidden" name="attach_id" id="attach_id" value="0"/>
                <input type="hidden" name="add_attach" value="Add Attach"  />&nbsp;&nbsp;&nbsp;&nbsp;
	            <button type="submit" class="btn btn-sm btn-warning" name="add_attach" value="Add Attach" style="display: inline;"><i class="fa fa-save"></i> Save</button>
                
            </form>
            </td>
     </tr>
  </tbody>
</table>
