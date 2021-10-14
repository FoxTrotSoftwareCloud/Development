<div class="container">
<h1>Upload</h1>
    <div class="col-md-12 well">
    <?php require_once(DIR_FS_INCLUDES."alerts.php"); ?>
        <form method="POST">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Clearing Business Cutoff Date <span class="text-red">*</span></label>
                    <div id="demo-dp-range">
                        <div class="input-daterange input-group" id="datepicker">
                            <input type="text" name="clearing_business_cutoff_date" id="clearing_business_cutoff_date" class="form-control" value="<?php echo $clearing_business_cutoff_date;?>"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Direct Business Cutoff Date <span class="text-red">*</span></label>
                    <div id="demo-dp-range">
                        <div class="input-daterange input-group" id="datepicker">
                            <input type="text" name="direct_business_cutoff_date" id="direct_business_cutoff_date" class="form-control" value="<?php echo $direct_business_cutoff_date;?>"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-footer">
            <div class="selectwrap">
				<div class="selectwrap">
                    <a href="<?php echo CURRENT_PAGE;?>"><input type="button" name="cancel" value="Cancel" style="float: right;"/></a>
					<input type="submit" name="reverse_payroll"  value="Reverse Payroll" style="float: right;"/>
                    <input type="submit" name="upload_payroll"  value="Upload Payroll" style="float: right;"/>
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