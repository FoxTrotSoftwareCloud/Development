<?php  
  require_once("include/config.php");
require_once(DIR_FS."islogin.php");
    
$instance = new client_maintenance();  
if(isset($_GET['id']) && $_GET['id']>0){ 
$get_account = $instance->select_attach_id($_GET['id']);


?>
<script type="text/javascript">
	$.fn.regexMask = function(mask) {
    $(this).keypress(function (event) {
        if (!event.charCode) return true;
        var part1 = this.value.substring(0, this.selectionStart);
        var part2 = this.value.substring(this.selectionEnd, this.value.length);
        if (!mask.test(part1 + String.fromCharCode(event.charCode) + part2))
            return false;
    });
};
</script>
<script type="text/javascript">

$(document).ready(function(){
    $('#ch_no').mask("999999");
});
</script>
 <form method="post" name="add_new_account" onsubmit="return formsubmit_account();">
        <div class="inputpopup">
			<label>Cheque No:</label>
            <input type="text" name="ch_no" id="ch_no" value="<?php echo $get_account['joint_name'];?>"  class="form-control" />
		</div>
        <div class="inputpopup">
			<label>Cheque Amount:</label>
            <input type="text" name="ch_amount" onChange="setnumber_format(this)" id="ch_amount" value="<?php echo $get_account['ssn'];?>" class="form-control" />
		</div>
		
        <div class="inputpopup">

			<label>Date:</label>
            <input type="text" name="ch_date" id="ch_date" value="<?php echo $get_account['dob'];?>" class="form-control" />
        </div>
        <div class="inputpopup">
			<label>Payable to:</label>
            <input type="text" name="ch_pay_to" id="ch_pay_to" value="<?php echo $get_account['employer_add'];?>" class="form-control" />
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
			<label>Cheque No:</label>
            <input type="text" name="ch_no" id="ch_no"  class="form-control" />
		</div>
        <div class="inputpopup">
			<label>Cheque Amount:</label>
            <input type="text" name="ch_amount" onChange="setnumber_format(this)" id="ch_amount" class="form-control" />
		</div>		
        <div class="inputpopup">

			<label>Date:</label>
            <input type="text" name="ch_date" id="ch_date" class="form-control" />
        </div>
        <div class="inputpopup">
			<label>Payable to:</label>
            <input type="text" name="ch_pay_to" maxlength="40" id="ch_pay_to" class="form-control" />
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