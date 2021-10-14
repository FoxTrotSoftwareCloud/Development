<div class="container">
<h1>Publish</h1>
<div class="col-lg-12 well">
<?php require_once(DIR_FS_INCLUDES."alerts.php"); ?>
    <div class="tab-content col-md-12">
        <div class="tab-pane active" id="tab_a">
            <form method="post" target="_blank">
                <div class="tab-content">
                    <div class="row">
                        <div class="col-md-4" style="float: right;">
                            <div class="form-group">
                                
                            </div>
                         </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Publish Report </label>
                                <select class="form-control" name="publish_report" id="publish_report" onchange="open_reports(this.value);">
                                    <option value="1">Broker Statements</option>
                                    <option value="2">Company Statements</option>
                                    <option value="3">Adjustment Report</option>
                                    <option value="4">Reconciliation Report</option>
                                </select>
                            </div>
                         </div>
                    </div>
                    <br />
                    <div class="panel" id="report_filters" style="display: none;">
                    
                    </div>
                    <div class="panel-footer"><br />
                        <div class="selectwrap">
            				<div class="selectwrap">
                                <input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
                                <a href="<?php echo CURRENT_PAGE;?>"><input type="button" name="cancel" value="Cancel" style="float: right;"/></a>
            					<input type="submit" name="submit"  value="Proceed" style="float: right;"/>
                            </div>
                        </div>
                    </div>
                </form>
             </div>
        </div>
    </div>
</div>
<script type="text/javascript">
//date format
$('#demo-dp-range .input-daterange').datepicker({
        format: "mm/dd/yyyy",
        todayBtn: "linked",
        autoclose: true,
        todayHighlight: true
        }).on('show',function(){
            $(".datepicker-dropdown").css("z-index",'1000000');
        });
function open_reports(report_name)
{
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) 
        {
            document.getElementById("report_filters").innerHTML = this.responseText;
            $("#report_filters").css('display','block');
        }
    };
    xmlhttp.open('GET', 'ajax_publish_payroll_report.php?report_name='+report_name, true);
    xmlhttp.send();
}
$(document).ready(function(){
    report_name = document.getElementById("publish_report").value;
    open_reports(report_name);
});
</script>
<style>
.btn-primary {
    color: #fff;
    background-color: #337ab7 !important;
    border-color: #2e6da4 !important;
}
#report_filters{
    border: 1px solid #cccccc !important;
    padding:10px;
}
.titlebox{
    float:left;
    font-weight: bold;
    padding:0 5px;
    margin:-20px 0 0 30px;
    background:#fff;
}
</style>