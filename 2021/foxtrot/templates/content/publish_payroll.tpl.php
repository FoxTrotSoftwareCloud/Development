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
                    <div id="myModal" class="modal fade" role="dialog" aria-hidden="true" tabindex="-1">
                    	<div class="modal-dialog modal-lg">
                    		<!-- Modal content-->
                    		<div class="modal-content">
                                <!--<div class="modal-header" style="margin-bottom: 0px !important;">
                        			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
                        			<h4 class="modal-title">Report</h4>
                        		</div>-->
                                
                    			<div class="modal-body" id="output_screen_content">Loading...</div>
                                <!--<div class="modal-footer" style="margin-bottom: 0px !important;">
                        			<a href="<?php echo SITE_URL;?>report_batch.php?open=output_print&filter=<?php if(isset($_GET['filter']) && $_GET['filter']){ echo $_GET['filter']; }?>" class="btn btn-warning">Output to Printer</a>
                                    <a href="<?php echo SITE_URL;?>report_batch.php?filter=<?php if(isset($_GET['filter']) && $_GET['filter']){ echo $_GET['filter']; }?>" class="btn btn-warning">Output to PDF</a>
                        		</div>-->
                    		</div>
                    	</div>
                    </div>
                </form>
             </div>
        </div>
    </div>
</div>
<?php if(isset($_GET['filter']) && $_GET['filter'] != '' && $output == 1){?>
<script>
//location.href=location.href.replace(/&?open=([^&]$|[^&]*)/i, "");
$(document).ready(function(){
    
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) 
        {
            document.getElementById("output_screen_content").innerHTML = this.responseText;
        }
    };
    xmlhttp.open('GET', 'ajax_publish_payroll_report_view.php?filter=<?php echo $_GET['filter']; ?>', true);
    xmlhttp.send();


    $('#myModal').modal({
    		show: true
    	});
       //location.href=location.href.replace(/&?open=([^&]$|[^&]*)/i, ""); 
});

</script>
<?php } ?>
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