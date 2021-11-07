<div class="container">
<h1>Calculate</h1>
    <div class="col-md-12 well">
    <?php require_once(DIR_FS_INCLUDES."alerts.php"); ?>
        <form method="POST">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Payroll Date <span class="text-red">*</span></label>
                    <div id="demo-dp-range">
                        <select class="form-control" name="payroll_selected_key">
                            <?php foreach($get_payroll_uploads as $key=>$val){?>
                            <option value="<?php echo $key;?>" <?php echo ($key==$payrollSelectedKey)?'selected':'' ?>><?php echo date('m/d/Y', strtotime($val['payroll_date']));?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-footer">
            <div class="selectwrap">
				<div class="selectwrap">
                    <a href="<?php echo CURRENT_PAGE;?>"><input type="button" name="cancel" value="Cancel" style="float: right;"/></a>
					<input type="submit" name="submit"  value="Proceed" style="float: right;"/>
                </div>
            </div>
        </div>
        </form>
    </div>
</div>

<script type="text/javascript">
$('#demo-dp-range .input-daterange').datepicker({
        format: "mm/dd/yyyy",
        todayBtn: "linked",
        autoclose: true,
        todayHighlight: true
        }).on('show',function(){
            $(".datepicker-dropdown").css("z-index",'1000000');
        });
</script>
<style>
.btn-primary {
    color: #fff;
    background-color: #337ab7 !important;
    border-color: #2e6da4 !important;
}
</style>