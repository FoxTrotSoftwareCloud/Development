<div class="container">
<h1>Report</h1>
    <div class="col-md-12 well">
        <form method="POST" id="report_form" action="/CloudFox/client_reports.php">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Select Report </label>
                    <select class="form-control" name="report_for" id="report_for">
                        <option value="1" <?php if(isset($report_for) && ($report_for == 1 || $report_for == '')){echo "selected='true'";}?>>Account Numbers</option>
                        <option value="2" <?php if(isset($report_for) && $report_for == 2){echo "selected='true'";}?>>Client Lists</option>
                        <!-- <option value="3" <?php if(isset($report_for) && $report_for == 3){echo "selected='true'";}?>>Client Review Report</option> -->
                    </select>
                </div>
             </div>
        </div>
        <br />
        <div class="panel" id="report_filters">
        <div class="titlebox">Client Reports</div><br />
        <div class="row">
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group wrap">
                            <label>State </label>
                            <select class="form-control state" name="state">
                                <option value="0">All States</option>
                                <?php foreach($get_states as $statetemp): ?>
                                    <option value="<?php echo $statetemp['id']; ?>" <?php echo isset($state) && $state==$statetemp['id'] ? 'selected' : '' ?>><?php echo $statetemp['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label id="broker_label">Broker </label>
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
                    <div class="col-md-12">
                        <div class="form-group wrap">
                            <label>Sponser </label>
                            <select class="form-control sponser" name="sponsor">
                                <option value="0">All Sponsers</option>
                                <?php foreach($get_sponsors as $get_sponsor): ?>
                                    <option value="<?php echo $get_sponsor['id']; ?>"><?php echo $get_sponsor['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group beginning_date">
                            <label>Beginning Date </label>
                            <div id="demo-dp-range">
                                <div class="input-daterange input-group" id="datepicker">
                                    <input type="text" name="beginning_date" id="beginning_date" class="form-control" value="<?php if(isset($beginning_date) && $beginning_date != ''){ echo $beginning_date;} else {echo date('m/01/Y');} ?>"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group ending_date">
                            <label>Ending Date </label>
                            <div id="demo-dp-range">
                                <div class="input-daterange input-group" id="datepicker">
                                    <input type="text" name="ending_date" id="ending_date" class="form-control" value="<?php if(isset($ending_date) && $ending_date != ''){ echo $ending_date;} else {echo date('m/d/Y');} ?>"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <input type="radio" class="radio" name="output" id="output_to_screen" style="display: inline;" value="1" <?php if(isset($output) && ($output == 1 || $output == '')){echo "checked='checked'";}?>/> Output to Screen&nbsp;&nbsp;&nbsp;
                            <input type="radio" class="radio" name="output" id="output_to_printer" style="display: inline;" value="2" <?php if(isset($output) && $output == 2){echo "checked='checked'";}?>/> Output to Printer&nbsp;&nbsp;&nbsp; 
                            <input type="radio" class="radio" name="output" id="output_to_excel" style="display: inline;" value="3" <?php if(isset($output) && $output == 3){echo "checked='checked'";}?>/> Output to Excel&nbsp;&nbsp;&nbsp; 
                            <input type="radio" class="radio" name="output" id="output_to_pdf" style="display: inline;" value="4" <?php if(isset($output) && $output == 4){echo "checked='checked'";}?>/> Output to PDF
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
    xmlhttp.open('GET', 'ajax_client_report.php?filter=<?php echo $_GET['filter']; ?>', true);
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
            $("#report_for").on('change', function(event) {
                event.preventDefault();
                let _option=$(this).children('option:selected').val();
                if (_option==2 || _option==3) {
                    $(".sponser").parents('.wrap').hide();
                }
                else {
                   $(".sponser").parents('.wrap').show(); 
                }
                if (_option==1 || _option==3) {
                    $(".state").parents('.wrap').hide();
                }
                else {
                    $(".state").parents('.wrap').show();
                }
                if (_option!=3) {
                    $(".beginning_date").hide();
                    $(".ending_date").hide();
                }
                else {
                    $("#broker_label").text('Supervisor')
                    $("#broker_dropdown").children('option:eq(0)').text("All Supervisors")
                    $(".beginning_date").show();
                    $(".ending_date").show();
                }
            }).trigger('change');


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