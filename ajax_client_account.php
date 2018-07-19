<?php 
require_once("include/config.php");
require_once(DIR_FS."islogin.php");
    
$instance = new client_maintenance();   
$get_account = $instance->select_account();
$account_id = 0;
?>
<table class="table table-bordered table-stripped table-hover">
    <thead>
        <th>Joint Name</th>
        <th>SSN</th>
        <th>DOB</th>
        <th>Income</th>
        <th>Occupation</th>
        <th>Position</th>
        <th>Securities-Related Firm?</th>
        <th>Employer</th>
        <th>Employer Address</th>
        <th>ACTION</th>
    </thead>
    <tbody>
    <?php foreach($get_account as $key=>$val){
        $account_id = $val['id'];?>
        <tr>               
            <td><?php echo $val['joint_name'];?></td>
            <td><?php echo $val['ssn'];?></td>
            <td><?php echo $val['dob'];?></td>
            <td><?php echo $val['income'];?></td>
            <td><?php echo $val['occupation'];?></td>
            <td><?php echo $val['position'];?></td>
            <td><?php if($val['securities']==1){ echo 'Yes';} else { echo 'No';}?></td>
            <td><?php echo $val['employer'];?></td>
            <td><?php echo $val['employer_add'];?></td>
            <td>
                <form method="post" id="add_client_notes_<?php echo $account_id;?>" name="add_client_notes_<?php echo $account_id;?>" onsubmit="return notessubmit(<?php echo $account_id;?>);">
                <input type="hidden" name="account_id" id="account_id" value="<?php echo $account_id;?>"/>
                <input type="hidden" name="add_account" value="Add Account"  />&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="#add_joint_account" data-toggle="modal" style="float: right !important; margin-right: 5px !important;" onclick="get_client_edit(<?php echo $account_id;?>);" class="btn btn-sm btn-primary" ><i class="fa fa-edit"></i> Edit</a>
                </form>
            </td>
                
            
        </tr>
    <?php } ?>
  
  </tbody>
</table>
