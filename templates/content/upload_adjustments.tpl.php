<div class="container">
<h1>Upload Adjustments</h1>
    <div class="col-md-12 well">
    <?php require_once(DIR_FS_INCLUDES."alerts.php"); ?>
        <form method="POST" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Upload Type </label>
                    <select class="form-control" name="upload_type" id="upload_type">
                        <option value="0">Select Type</option>
                        <option value="1" <?php if(isset($upload_type) && $upload_type==1){echo "selected='selected'";} ?>>Generic CSV Payroll Adjustment Template</option>
                        <option value="2" <?php if(isset($upload_type) && $upload_type==2){echo "selected='selected'";} ?>>FINRA eBill Fees</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Upload Adjustments</label>
                    <input type="file" name="upload_adjustments" accept=".csv" class="form-control" />
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group"><br />
                    <a style="float: right;"href="<?php echo SITE_URL."upload/AdjustmentsCSV.csv"; ?>">Download Payroll Adjustment Template</a><br />
                    <a style="float: right;"href="<?php echo SITE_URL."upload/FinraCSV.csv"; ?>">Download FINRA eBill Fees Template</a>
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