<div class="container">
<h1>Report</h1>
    <div class="col-md-12 well">
        <form method="POST" id="report_form">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Select Report</label>
                    <select class="form-control" name="report_for" id="report_for">
                        <option  value="year_to_date">Year-to-Date Earnings Report</option>
                        <option  value="broker_ranking">Broker Ranking Report</option>
                        <option  value="year_to_date">Monthly Broker Production Report </option>
                        <option  value="year_to_date">Monthly Branch Production report </option>
                        <option  value="year_to_date">Annual Broker Production</option>
                        <option  value="year_to_date">Client Rankings Report</option>
                        <option  value="year_to_date">Broker State Production Report</option>
                        <option  value="year_to_date">Broker Gross Production by Category</option>
                        <option  value="year_to_date">Brokers without Production</option>
                        <option  value="year_to_date">Broker-Dealer Retention</option>


                       
                    </select> 
                </div>
             </div>
        </div>
        <br />
        <div class="panel" id="report_filters">
        <div class="titlebox">Sales Reports</div><br />
        <div class="row">
            <div class="col-md-8">
                <div class="">
                    <div class="row" id="year_to_date_filter_wrap">
                        <div class="col-md-12">
                            <div class="form-group wrap">
                                <label>Company </label>
                                <select class="form-control state" name="company">
                                    <option value="0">All Companies</option>
                                    <?php
                                            
                                     foreach($get_multi_company as $company_row):
                            
                                     ?>
                                        <option value="<?php echo $company_row['id']; ?>" <?php echo isset($company) && $company==$company_row['id'] ? 'selected' : '' ?>><?php echo $company_row['company_name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12">
                                <div class="form-group">
                                     
                                      <input type="radio" class="radio" name="sort_by" id="sort_by" style="display: inline;" value="1" <?php if(isset($sort_by) && ($sort_by == 1 || $sort_by == '')){echo "checked='checked'";}?>/> Earning By Broker
                                       <input type="radio" class="radio" name="sort_by" id="sort_by" style="display: inline;" value="2" <?php if(isset($sort_by) && ($sort_by == 2)){echo "checked='checked'";}?>/> Earning By Branch
                                </div>
                        </div>
                         <div class="col-md-12">
                                <div class="form-group">
                                     <input type="checkbox" class="checkbox" name="sort_by" id="sort_by" style="display: inline;" value="2" <?php if(isset($without_earning) && ($without_earning == 2)){echo "checked='checked'";}?>/> Include Brokers With No Earning
                                </div>
                          </div>          
                        <div class="col-md-12">
                        <div class="form-group">
                            <input type="radio" class="radio" name="output" id="output_to_screen" style="display: inline;" value="1" <?php if(isset($output) && ($output == 1 || $output == '')){echo "checked='checked'";}?>/> Output to Screen&nbsp;&nbsp;&nbsp;
                            <input type="radio" class="radio" name="output" id="output_to_printer" style="display: inline;" value="2" <?php if(isset($output) && $output == 2){echo "checked='checked'";}?>/> Output to Printer&nbsp;&nbsp;&nbsp; 
                         <input type="radio"  class="radio" name="output" id="output_to_excel" style="display: inline;" value="3" <?php if(isset($output) && $output == 3){echo "checked='checked'";}?>/> Output to Excel&nbsp;&nbsp;&nbsp; 
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
    xmlhttp.open('GET', 'ajax_sales_reporting.php?filter=<?php echo $_GET['filter']; ?>', true);
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
function get_batches(cat){
      console.log($("select#batch_dropdown").find("option[data-cat='"+cat+"']"),'dd');
      if(cat ==0){
         $("select#batch_dropdown").find("option").show();
      }
      else{
              $("select#batch_dropdown").find("option").hide();
             $("select#batch_dropdown").find("option[data-cat='"+cat+"']").show();
      }
     
}
function get_product(cat){
      console.log($("select#product_dropdown").find("option[data-cat='"+cat+"']"),'dd');
      if(cat ==0){
         $("select#product_dropdown").find("option").show();
      }
      else{
              $("select#product_dropdown").find("option").hide();
             $("select#product_dropdown").find("option[data-cat='"+cat+"']").show();
      }
     
}

 $(function() {

             $("input[name='filter_by']").change(function(){
                 /* console.log(this.value)
                   if($("input[name='filter_by']:checked").val() == "2"){
                            $('#div_sort_by').hide();
                            $('#broker_filter_wrap').hide();                            
                            $('#div_trade_by').show();
                   }
                   else{
                            $('#div_sort_by').show();
                            $('#broker_filter_wrap').show();
                            $('#div_trade_by').hide();
                   }*/
            }).trigger('change');
            $("#report_for").on('change', function(event) {
               
            }).trigger('change');

              $("#report_form").submit(function(ev){
  
       
                 /*if($("input[name='output']:checked").val()== 4){
                    ev.preventDefault();
                    const data = new FormData(ev.target);
                       value = Object.fromEntries(data.entries());
                       report_for = $("select[name='report_for']").val() ;
                      // console.log(report_for )
                      
                        if( $("#report_for").val() == "broker"){

                            url = "http://foxtrotsoftware.com/CloudFox/sales_broker_report_print.php?filter="+JSON.stringify(value);
                        }
                        else{
                              url = "http://foxtrotsoftware.com/CloudFox/sales_report_print.php?filter="+JSON.stringify(value);
                        }
                        
                       


                       
                   
                    var win= window.open(url,"blank");
                    win.focus();
                    return false;
                 }*/
         });
        });       
</script>
<link href="<?php echo SITE_PLUGINS; ?>chosen/chosen.min.css" rel="stylesheet" />
<script src="<?php echo SITE_PLUGINS; ?>chosen/chosen.jquery.min.js"></script>

<script type="text/javascript">         
    $(document).ready(function(e){
        $( document ).on( 'click', '.bs-dropdown-to-select-group .dropdown-menu li', function( event ) {
            var $target = $( event.currentTarget );
            $target.closest('.bs-dropdown-to-select-group')
                .find('[data-bind="bs-drp-sel-value"]').val($target.attr('data-value'))
                .end()
                .children('.dropdown-toggle').dropdown('toggle');
            $target.closest('.bs-dropdown-to-select-group')
                .find('[data-bind="bs-drp-sel-label"]').text($(this).find('a').html());
            return false;
        });
        
        $('.sel').trigger('click');
        $('.bs-dropdown-to-select-group').removeClass('open');
        
        $('.chosen-select').chosen();
        
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
.chosen-container
{
    width: 100% !important;
}
</style>