<div class="container">
<h1>Report</h1>
    <div class="col-md-12 well">
        <form method="POST" id="report_form" action="">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <input type="hidden" name="report_for" value="1">
                </div>
             </div>
        </div>
        <br />
        <div class="panel" id="report_filters">
        <div class="titlebox">Daily Trade Blotter Reports</div><br />
        <div class="row">
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group wrap">
                            <label>Company </label>
                            <select class="form-control state" name="company">
                                <option value="0">All Companies</option>
                                <?php foreach($get_company as $companyN): ?>
                                    <option value="<?php echo $companyN['id']; ?>" <?php echo isset($company) && $company==$companyN['id'] ? 'selected' : '' ?>><?php echo $companyN['company_name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group wrap">
                            <label>Branch Manager</label>
                            <select class="form-control" name="" onchange="get_branch_by_manager()" id="branch_manager">
                                <option value="0">All Branch Managers</option>
                                <?php foreach($manager_list as $managerN): ?>
                                    <option value="<?php echo $managerN['branch_id']; ?>"><?php echo $managerN['last_name'].", ".$managerN['first_name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group wrap">
                            <label>Branch</label>
                            <?php 
                                if($is_branch_manager >0){
                                    $branch_detail=$instance_branch->select_branch_by_id($is_branch_manager);
                                ?>

                                    <input type="text" value="<?php echo $branch_detail['name']; ?>" class="form-control" disabled>
                                    <input type="hidden" name="branch" value="<?php echo $is_branch_manager ?>" class="form-control">

                            <?php }else{ ?>
                                <select class="form-control" name="branch" id="branch_id">
                                        <option value="0">All Branches</option>
                                        <?php foreach($branch_list as $branchN): ?>
                                            <option value="<?php echo $branchN['id'];?>" <?php echo isset($branch) && $branch==$branchN['id'] ? 'selected' : '' ?>><?php echo $branchN['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                            <?php } ?>
                        </div>
                    </div>
                </div>
               
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label id="broker_label">Broker</label>
                            <select class="form-control" name="broker" id="broker_dropdown">
                                <option value="0">All Brokers</option>
                                <?php foreach($get_brokers as $brokerN): ?>
                                    <option value="<?php echo $brokerN['id']; ?>" <?php echo isset($broker) && $broker==$brokerN['id'] ? 'selected' : '' ?>><?php echo $brokerN['last_name'].', '.$brokerN['first_name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group beginning_date">
                            <label>Beginning Trade Date </label>
                            <div id="demo-dp-range">
                                <div class="input-daterange input-group" id="datepicker">
                                    <input type="text" name="beginning_date" id="beginning_date" class="form-control" value="<?php if(isset($beginning_date) && $beginning_date != ''){ echo $beginning_date;} else {echo date('m/01/Y');} ?>"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group ending_date">
                            <label>Ending Trade Date </label>
                            <div id="demo-dp-range">
                                <div class="input-daterange input-group" id="datepicker">
                                    <input type="text" name="ending_date" id="ending_date" class="form-control" value="<?php if(isset($ending_date) && $ending_date != ''){ echo $ending_date;} else {echo date('m/d/Y');} ?>"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="radio-inline">
                                    <input type="radio" class="radio" name="output" id="output_to_screen" value="1" <?php if(isset($output) && ($output == 1 || $output == '')){echo "checked='checked'";}?>/>&nbsp; Output to Screen &nbsp;
                            </label>
                            <label class="radio-inline">
                                <input type="radio" class="radio" name="output" id="output_to_printer" style="display: inline;" value="2" <?php if(isset($output) && $output == 2){echo "checked='checked'";}?>/>&nbsp; Output to Printer&nbsp; 
                            </label>
                            <label class="radio-inline">
                                <input type="radio" class="radio" name="output" id="output_to_excel" style="display: inline;" value="3" <?php if(isset($output) && $output == 3){echo "checked='checked'";}?>/>&nbsp; Output to Excel&nbsp;
                            </label>
                            <label class="radio-inline"> 
                                <input type="radio" class="radio" name="output" id="output_to_pdf" style="display: inline;" value="4" <?php if(isset($output) && $output == 4){echo "checked='checked'";}?>/> &nbsp;Output to PDF
                            </label>
                        </div>
                    </div>
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
    xmlhttp.open('GET', 'ajax_daily_trade_blotter_report.php?filter=<?php echo $_GET['filter']; ?>', true);
    xmlhttp.send();


    $('#myModal').modal({
            show: true
        });
       //location.href=location.href.replace(/&?open=([^&]$|[^&]*)/i, ""); 
});

</script>
<?php } ?>
<?php if(isset($_GET['open']) && $_GET['open'] == "output_print" && isset($_GET['from_broker']) && $_GET['from_broker'] != ''){?>
<script>
window.print();
</script>
<?php } ?>

<script type="text/javascript">
$('#demo-dp-range .input-daterange').datepicker({
        format: "mm/dd/yyyy",
        todayBtn: "linked",
        autoclose: true,
        todayHighlight: true
        }).on('show',function(){
            $(".datepicker-dropdown").css("z-index",'1000000');
        });

 $(function() {
            $("#report_form").submit(function(ev){
                var _form = $(this);
                var output_type = $(this).find("input[name='output']:checked").val();
                if(output_type == 4 || output_type == 2) {
                 
                    _form.attr('target','_blank');
                     //return false;
                }
                else {
                    _form.removeAttr('target');
                }
             });

        });       

        function get_branch_by_manager(){
            branch_id= $('#branch_manager').val();
            $('#branch_id').val(branch_id);
        }
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
input[type=radio]{
    margin-top: 1px;
}
</style>