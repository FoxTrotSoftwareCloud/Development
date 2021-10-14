<style type="text/css">
    .checkbox-inline, .radio-inline{
        padding-left: 22px;
    }
    .checkbox input[type=checkbox], .checkbox-inline input[type=checkbox], .radio input[type=radio], .radio-inline input[type=radio]{
         margin-left: -22px;
         margin-top: 1px;
    }
</style>
<div class="container">
<h1>Report</h1>
    <div class="col-md-12 well">
        <form method="POST" id="report-form">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Select Report </label>
                    <select class="form-control" name="report_for" id="report_for">
                        <option value="1" <?php if(isset($report_for) && ($report_for == 1 || $report_for == '')){echo "selected='true'";}?>>Commission Posting Log</option>
                        <option value="2" <?php if(isset($report_for) && $report_for == 2){echo "selected='true'";}?>>Batch Report</option>
                        <option value="3" <?php if(isset($report_for) && $report_for == 3){echo "selected='true'";}?>>Hold Report</option>
                     
                        <option value="4" <?php if(isset($report_for) && $report_for == 4){echo "selected='true'";}?>>Payables Report</option>
                    </select>
                </div>
             </div>
        </div>
        <br />
        <div class="panel" id="report_filters">
        <div class="titlebox">Commission Reports</div><br />
        <div class="row">
         
            <div id="category_wrapper" class="col-md-4">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <input type="radio" class="radio" name="product_category" id="all_category" style="display: inline;" value="0" <?php if(isset($product_category) && ($product_category == 0 || $product_category == '')){echo "checked='checked'";}?>/> All Categories&nbsp;&nbsp;&nbsp;
                        </div>
                    </div>
                </div>
                <?php foreach($get_product_category as $category_key=>$category_val){//echo $product_category;exit; ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <input type="radio" class="radio" name="product_category" id="product_category_<?php echo $category_val['id'];?>" style="display: inline;" value="<?php echo $category_val['id'];?>" <?php if(isset($product_category) && $product_category == $category_val['id']){echo "checked='checked'";}?>/> <?php echo $category_val['type'];?>&nbsp;&nbsp;&nbsp;
                        </div>
                    </div>
                </div>
                <?php } ?>
                
            </div>
            
            <div id="company_wrapper" class="col-md-8">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Company </label>
                            <select class="form-control" name="company">
                                <option value="0">All Companies</option>
                                <?php foreach($get_multi_company as $key=>$val){?>
                                <option value="<?php echo $val['id'];?>" <?php if($company != '' && $company==$val['id']){echo "selected='selected'";} ?>><?php echo $val['company_name'];?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Batch </label>
                            <select class="form-control" name="batch">
                                <option value="0">All Batches</option>
                                <?php foreach($get_batches as $key=>$val){?>
                                <option value="<?php echo $val['id'];?>" <?php if(isset($batch) && $batch==$val['id']){?> selected="true"<?php } ?>><?php echo $val['id'].' '.$val['batch_desc'];?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Beginning Date </label>
                            <div id="demo-dp-range">
                                <div class="input-daterange input-group" id="datepicker">
                                    <input type="text" name="beginning_date" id="beginning_date" class="form-control" value="<?php if(isset($beginning_date) && $beginning_date != ''){ echo $beginning_date;} else {echo date('m/01/Y');} ?>"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
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
                             <label>Sort By</label> <br/>
                             <label class="radio-inline">
                                 <input type="radio" class="radio" name="sort_by" id="sort_by_date" style="display: inline;" value="3" <?php if(isset($sort_by) && ($sort_by == 3 || $sort_by == '')){echo "checked='checked'";}?>/> Date
                              </label>
                              <label class="radio-inline">
                                 <input type="radio" class="radio" name="sort_by" id="sort_by_batch_number" style="display: inline;" value="2" <?php if(isset($sort_by) && $sort_by == 2){echo "checked='checked'";}?>/> Batch Number&nbsp;&nbsp;&nbsp;
                               </label>
                              <label class="radio-inline">
                                 <input type="radio" class="radio" name="sort_by" id="sort_by_sponsor" style="display: inline;" value="1" <?php if(isset($sort_by) && ($sort_by == 1)){echo "checked='checked'";}?>/> Sponsor&nbsp;&nbsp;&nbsp;
                                </label> 
                           
                           
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">

                        <div class="form-group">
                            <label>Output to</label> <br/>
                            <label class="radio-inline">
                                <input type="radio" class="radio" name="output" id="output_to_screen" style="display: inline;" value="1" <?php if(isset($output) && ($output == 1 || $output == '')){echo "checked='checked'";}?>/>Screen&nbsp;&nbsp;&nbsp;
                            </label>
                            <label class="radio-inline">
                                <input type="radio" class="radio" name="output" id="output_to_printer" style="display: inline;" value="2" <?php if(isset($output) && $output == 2){echo "checked='checked'";}?>/>Printer&nbsp;&nbsp;&nbsp;
                            </label>
                            <label class="radio-inline">
                                <input type="radio" class="radio" name="output" id="output_to_excel" style="display: inline;" value="3" <?php if(isset($output) && $output == 3){echo "checked='checked'";}?>/>Excel&nbsp;&nbsp;&nbsp;
                            </label>    
                            <label class="radio-inline">
                                <input type="radio" class="radio" name="output" id="output_to_pdf" style="display: inline;" value="4" <?php if(isset($output) && $output == 4){echo "checked='checked'";}?>/>Output to PDF
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div id="payable-filter" class="col-md-8">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Company </label>
                            <select class="form-control" name="payable_company">
                                <option value="0">All Companies</option>
                                <?php foreach($get_multi_company as $key=>$val){?>
                                <option value="<?php echo $val['id'];?>" <?php if($company != '' && $company==$val['id']){echo "selected='selected'";} ?>><?php echo $val['company_name'];?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <input type="radio" class="radio" name="payable_type" id="payable_type" style="display: inline;" value="1"  <?php if(!isset($payable_type) || ($payable_type == 1 || $payable_type == '')){echo "checked='checked'";}?>  > Commissions Recevied
                            <input type="radio" class="radio" name="payable_type" id="payable_type" style="display: inline;" value="2"  <?php if(isset($payable_type) && ($payable_type == 2 || $payable_type == '')){echo "checked='checked'";}?>  > All Unpaid Commissions
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            Received on or before (cutoff):    <input type="text" name="cuttoff_date" id="cuttoff_date" class="form-control"  style="display:inline-block;width:150px;" value="<?php if(isset($cuttoff_date) && $cuttoff_date != ''){ echo $cuttoff_date;} else {echo date('m/d/Y');} ?>"/>
                           
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Sort By</label> <br/>
                            <label class="radio-inline">
                                 <input type="radio" class="radio" name="payable_sort_by" <?php if(isset($sort_by) && ($sort_by == 1 || $sort_by == '')){echo "checked='checked'";}?>  style="display: inline;" value="1" />  Broker
                            </label> 
                            <label class="radio-inline">    
                                <input type="radio" class="radio" name="payable_sort_by" <?php if(isset($sort_by) && ($sort_by == 2 )){echo "checked='checked'";}?> style="display: inline;" value="2" />  Sponser
                            </label>
                        
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Output to</label> <br/>
                            <label class="radio-inline">
                                <input type="radio" class="radio" name="payable_output" id="output_to_screen" style="display: inline;" value="1" <?php if(isset($output) && ($output == 1 || $output == '')){echo "checked='checked'";}?>/>Screen&nbsp;&nbsp;&nbsp;
                            </label>
                            <label class="radio-inline">    
                            <input type="radio" class="radio" name="payable_output" id="output_to_printer" style="display: inline;" value="2" <?php if(isset($output) && $output == 2){echo "checked='checked'";}?>/> Printer&nbsp;&nbsp;&nbsp; 
                        </label>
                            <label class="radio-inline">
                            <input type="radio" class="radio" name="payable_output" id="output_to_excel" style="display: inline;" value="3" <?php if(isset($output) && $output == 3){echo "checked='checked'";}?>/> Excel&nbsp;&nbsp;&nbsp;
                        </label>
                        <label class="radio-inline">
                            <input type="radio" class="radio" name="payable_output" id="output_to_pdf" style="display: inline;" value="4" <?php if(isset($output) && $output == 4){echo "checked='checked'";}?>/> PDF
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
    xmlhttp.open('GET', 'ajax_report.php?filter=<?php echo $_GET['filter']; ?>', true);
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
jQuery(function($){

    $("#cuttoff_date").datepicker({
        format: "mm/dd/yyyy",
        todayBtn: "linked",
        autoclose: true,
        todayHighlight: true
        }).on('show',function(){
            //$(".datepicker-dropdown").css("z-index",'1000000');
        });

    $("select[name='report_for']").change(function(){
             console.log(this.value,"this.value")
             if(this.value ==4){
                  $("#report_filters").addClass('payable-report');
             }
             else{
                     $("#report_filters").removeClass('payable-report');
             }
    }).trigger("change");
     $("#report-form").submit(function(ev){
  
       
             if($("input[name='output']:checked").val()== 4 || $("input[name='payable_output']:checked").val()== 4){
                ev.preventDefault();
                const data = new FormData(ev.target);
                   value = Object.fromEntries(data.entries());
                   report_for = $("select[name='report_for']").val() ;
                  // console.log(report_for )
                   if(report_for == "1"){
                          url = "http://foxtrotsoftware.com/CloudFox/report_transaction_by_batch.php?filter="+JSON.stringify(value);
                   }
                    if(report_for == "2"){
                        url = "http://foxtrotsoftware.com/CloudFox/report_batch.php?filter="+JSON.stringify(value);
                    
                   }
                  if(report_for == "3"){
                        url = "http://foxtrotsoftware.com/CloudFox/report_transaction_by_hold.php?filter="+JSON.stringify(value);
                    
                   }
                   if(report_for == "4"){
                        url = "http://foxtrotsoftware.com/CloudFox/report_transaction_by_payable.php?filter="+JSON.stringify(value);
                    
                   }


                   
               
                var win= window.open(url,"blank");
                win.focus();
                return false;
             }
     });
})

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