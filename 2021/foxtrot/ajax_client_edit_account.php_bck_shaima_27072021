<?php  
  require_once("include/config.php");
require_once(DIR_FS."islogin.php");
    
$instance = new client_maintenance();  
if(isset($_GET['id']) && $_GET['id']>0){ 
$get_account = $instance->select_attach_id($_GET['id']);


?>
 <form method="post" name="add_new_account" onsubmit="return formsubmit_account();">
        <div class="inputpopup">
			<label>Joint Name:<span>*</span></label>
            <input type="text" name="joint_name" value="<?php echo $get_account['joint_name'];?>" id="joint_name" class="form-control" />
		</div>
        <div class="inputpopup">
			<label>SSN:<span>*</span></label>
            <input type="text" name="ssn" id="ssn" value="<?php echo $get_account['ssn'];?>" class="form-control" />
		</div>
        <div class="inputpopup">
			<label>DOB:<span>*</span></label>
            <input type="text" name="dob" id="dob" value="<?php echo $get_account['dob'];?>" class="form-control" />
        </div>
        <div class="inputpopup">
			<label>Income:<span>*</span></label>
            <input type="text" name="income" id="income" value="<?php echo $get_account['income'];?>" class="form-control" />
		</div>
        <div class="inputpopup">
			<label>Occupation:<span>*</span></label>
            <input type="text" name="occupation" id="occupation" value="<?php echo $get_account['occupation'];?>" class="form-control" />
		</div>
        <div class="inputpopup">
			<label>Position:<span>*</span></label>
            <input type="text" name="position" id="position" value="<?php echo $get_account['position'];?>" class="form-control" />
		</div>
        <div class="inputpopup">
			<label>Securities-Related Firm?:<span>*</span></label>
            <input type="checkbox" name="security_related_firm" value="1" <?php if($get_account['securities']==1){?>checked="true"<?php }?> id="security_related_firm" class="checkbox" />
		</div>
        <div class="inputpopup">
			<label>Employer:<span>*</span></label>
            <input type="text" name="employer" id="employer" value="<?php echo $get_account['employer'];?>" class="form-control" />
		</div>
        <div class="inputpopup">
			<label>Emp. Address:<span>*</span></label>
            <input type="text" name="employer_add" id="employer_add" value="<?php echo $get_account['employer_add'];?>" class="form-control" />
		</div>
		<div class="col-md-12">
            <div id="msg">
            </div>
        </div>
       	<div class="inputpopup">
		<label class="labelblank">&nbsp;</label>
            <input type="hidden" name="id" value="<?php echo $get_account['id']; ?>" />
            <input type="hidden" name="submit_account" value="Ok"  />&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="submit" value="Ok" onclick="parent.window.close();" name="submit_account" />
		</div>
	</form>	
 <?php }else { ?>    
    
    
    
    <form method="post" name="add_new_account" onsubmit="return formsubmit_account();">
        <div class="inputpopup">
			<label>Joint Name:<span>*</span></label>
            <input type="text" name="joint_name" id="joint_name" class="form-control" />
		</div>
        <div class="inputpopup">
			<label>SSN:<span>*</span></label>
            <input type="text" name="ssn" id="ssn" class="form-control" />
		</div>
        <div class="inputpopup">
			<label>DOB:<span>*</span></label>
            <input type="text" name="dob" id="dob" class="form-control" />
        </div>
        <div class="inputpopup">
			<label>Income:<span>*</span></label>
            <input type="text" name="income" id="income" class="form-control" />
		</div>
        <div class="inputpopup">
			<label>Occupation:<span>*</span></label>
            <input type="text" name="occupation" id="occupation" class="form-control" />
		</div>
        <div class="inputpopup">
			<label>Position:<span>*</span></label>
            <input type="text" name="position" id="position" class="form-control" />
		</div>
        <div class="inputpopup">
			<label>Securities-Related Firm?:<span>*</span></label>
            <input type="checkbox" name="security_related_firm" value="1" id="security_related_firm" class="checkbox" />
		</div>
        <div class="inputpopup">
			<label>Employer:<span>*</span></label>
            <input type="text" name="employer" id="employer" class="form-control" />
		</div>
        <div class="inputpopup">
			<label>Emp. Address:<span>*</span></label>
            <input type="text" name="employer_add" id="employer_add" class="form-control" />
		</div>
		<div class="col-md-12">
            <div id="msg">
            </div>
        </div>
       	<div class="inputpopup">
		<label class="labelblank">&nbsp;</label>
            <input type="hidden" name="id" value="0" />
            <input type="hidden" name="submit_account" value="Ok"  />&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="submit" value="Ok"  name="submit_account" />
		</div>
	</form>	
 <?php } ?>