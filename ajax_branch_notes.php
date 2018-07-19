<?php 
require_once("include/config.php");
require_once(DIR_FS."islogin.php");
    
$instance = new branch_maintenance();   
$get_notes = $instance->select_notes();
$notes_id = 0;
?>
<table class="table table-bordered table-stripped table-hover">
    <thead>
        <th>Date</th>
        <th>User</th>
        <th>Notes</th>
    </thead>
    <tbody>
    <?php foreach($get_notes as $key=>$val){
        $notes_id = $val['id'];?>
        <tr>
            <td><?php echo date('d/m/Y',strtotime($val['date']));?></td>
            <input type="hidden" name="date" id="date" value="<?php echo date('Y-m-d',strtotime($val['date']));?>"/>
            <td><?php echo $val['user_id'];?></td>
            <input type="hidden" name="user_id" id="user_id" value="<?php echo $val['user_id'];?>"/>
            <td>
            <form method="post" id="add_client_notes_<?php echo $notes_id;?>" name="add_client_notes_<?php echo $notes_id;?>" onsubmit="return notessubmit(<?php echo $notes_id;?>);">
                <input type="text" name="client_note" class="form-control" id="client_note" value="<?php echo $val['notes'];?>" style="pointer-events: none;"/>
                <input type="hidden" name="notes_id" id="notes_id" value="<?php echo $notes_id;?>"/>
                <input type="hidden" name="add_notes" value="Add Notes"  />&nbsp;&nbsp;&nbsp;&nbsp;
	            <button type="submit" class="btn btn-sm btn-warning" name="add_notes" value="Add Notes"><i class="fa fa-save"></i> Save</button>
                <a href="#" onclick="openedit(<?php echo $notes_id;?>);" class="btn btn-sm btn-primary" ><i class="fa fa-edit"></i> Edit</a>
                <a href="#" onclick="delete_notes(<?php echo $notes_id;?>);" class="btn btn-sm btn-danger confirm" ><i class="fa fa-trash"></i> Delete</a>
            </form>
            </td>
        </tr>
    <?php } $notes_id++;?>
    <tr id="add_row_notes" style="display: none;">
            <td><?php echo date('d/m/Y');?></td>
            <input type="hidden" name="date" id="date" value="<?php echo date('Y-m-d');?>"/>
            <td><?php echo $_SESSION['user_name'];?></td>
            <input type="hidden" name="user_id" id="user_id" value="<?php echo $_SESSION['user_name'];?>"/>
            <td>
            <form method="post" id="add_client_notes_<?php echo $notes_id;?>" name="add_client_notes_<?php echo $notes_id;?>" onsubmit="return notessubmit(<?php echo $notes_id;?>);">
                <input type="text" name="client_note" class="form-control" id="client_note" value=""/>
                <input type="hidden" name="notes_id" id="notes_id" value="0"/>
                <input type="hidden" name="add_notes" value="Add Notes" />&nbsp;&nbsp;&nbsp;&nbsp;
	            <button type="submit" class="btn btn-sm btn-warning" name="add_notes" value="Add Notes"><i class="fa fa-save"></i> Save</button>
            </form>
            </td>
     </tr>
  </tbody>
</table>
