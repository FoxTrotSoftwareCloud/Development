<div class="container">
<h1>Report</h1>
    <div class="col-md-12 well">
        <form method="POST" id="report_form">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Select Report</label>
                    <select class="form-control" name="report_for" id="report_for">
                        <option <?php  echo ($report_for == 'Production by Product Category') ? "selected='selected'": ''; ?> value="Production by Product Category">Production by Product Category</option>
                         <option <?php  echo ($report_for == 'Category Summary Report') ? "selected='selected'": ''; ?> value="Category Summary Report">Category Summary Report</option>
                        <option <?php  echo ($report_for == 'Production by Sponsor Report') ? "selected='selected'": ''; ?> value="Production by Sponsor Report">Production by Sponsor Report</option>
                        <option <?php  echo ($report_for == 'product') ? "selected='selected'": ''; ?> value="product">Year-to-Date Earnings Report</option>
                        <option <?php  echo ($report_for == 'broker') ? "selected='selected'": ''; ?> value="broker">Broker Rankings by Production</option>
                        <option <?php  echo ($report_for == 'batch') ? "selected='selected'": ''; ?> value="batch">Monthly Broker Production Report</option>
                       <option <?php  echo ($report_for == 'batch') ? "selected='selected'": ''; ?> value="batch">Monthly Branch Office Production</option>
                       <option <?php  echo ($report_for == 'batch') ? "selected='selected'": ''; ?> value="batch">Annual Broker Production</option>
                       <option <?php  echo ($report_for == 'batch') ? "selected='selected'": ''; ?> value="batch">Client Rankings Report`</option>
                       <option <?php  echo ($report_for == 'batch') ? "selected='selected'": ''; ?> value="batch">Broker State Production Report</option>
                       <option <?php  echo ($report_for == 'batch') ? "selected='selected'": ''; ?> value="batch">Broker Monthly Production by Product Category</option>
                       <option <?php  echo ($report_for == 'batch') ? "selected='selected'": ''; ?> value="batch">Broker Gross Production by Category</option>
                    <option <?php  echo ($report_for == 'batch') ? "selected='selected'": ''; ?> value="batch">Brokers without Production</option>
                    <option <?php  echo ($report_for == 'batch') ? "selected='selected'": ''; ?> value="batch">Broker-Dealer Retention</option>
                    <option <?php  echo ($report_for == 'batch') ? "selected='selected'": ''; ?> value="batch">Broker Gross Production by Product Category</option>
                    <option <?php  echo ($report_for == 'batch') ? "selected='selected'": ''; ?> value="batch">Broker Production by Product</option>
                    </select> 
                </div>
             </div>
        </div>
        <br />
        <div class="panel" id="report_filters">
        <div class="titlebox">Sales Production Reports</div><br />
        <div class="row">
            <div class="col-md-8">
                <div class="">
                    <div class="row" id="branch_filter_wrap">
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
                    </div>
                    <div class="row" id="broker_filter_wrap">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label id="broker_label">Broker </label>
                                <select class="form-control" name="broker" id="broker_dropdown">
                                    <option value="0">All Brokers</option>
                                    <?php foreach($get_brokers as $brokerN): ?>
                                        <option value="<?php echo $brokerN['id']; ?>" <?php echo isset($broker) && $broker==$brokerN['id'] ? 'selected' : '' ?>><?php echo $brokerN['last_name'].' '.$brokerN['first_name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                            
                        
                    </div>
                       

                       <div class="row" id="div_PROD_CAT">
                        <div class="col-md-12">                                   
                            <label id="CAT_label">Product Category</label> 
                            <div class="form-group">
                            <select name="prod_cat[]" id="prod_cat" class="form-control chosen-select" multiple="true">
                                <option value="" >Select Category</option>
                                <?php foreach($product_category as $key => $val) {?>
                                        <option <?php echo in_array($val['id'],$prod_cat)?'selected="selected"':''; ?> value="<?php echo $val['id'];?>"><?php echo $val['type']?></a></option>
                                <?php } ?>
                            </select>
                            </div>
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
                    <div class="row" id="div_sort_by">
                        <div class="col-md-12">
                                <div class="form-group">
                                     
                                      <input type="radio" class="radio" name="sort_by" id="sort_by" style="display: inline;" value="1" <?php if(isset($sort_by) && ($sort_by == 1 || $sort_by == '')){echo "checked='checked'";}?>/> Sort by Sponsor&nbsp;&nbsp;&nbsp;
                                       <input type="radio" class="radio" name="sort_by" id="sort_by" style="display: inline;" value="2" <?php if(isset($sort_by) && ($sort_by == 2)){echo "checked='checked'";}?>/> Sort by Investment Amount
                                </div>
                        </div>
                    </div>



                     <div class="row" id="div_date_by">
                        <div class="col-md-12">
                                <div class="form-group">
                                     
                                      <input type="radio" class="radio" name="date_by" id="date_by" style="display: inline;" value="1" <?php if(isset($date_by) && ($date_by == 1 || $date_by == '')){echo "checked='checked'";}?>/> Transaction Date&nbsp;&nbsp;&nbsp;
                                       <input type="radio" class="radio" name="date_by" id="date_by" style="display: inline;" value="2" <?php if(isset($date_by) && ($date_by == 2)){echo "checked='checked'";}?>/>Commission Received Date
                                </div>
                        </div>
                    </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <input type="radio" class="radio" name="output" id="output_to_screen" style="display: inline;" value="1" <?php if(isset($output) && ($output == 1 || $output == '')){echo "checked='checked'";}?>/> Output to Screen&nbsp;&nbsp;&nbsp;
                            <input type="radio" class="radio" name="output" id="output_to_printer" style="display: inline;" value="2" <?php if(isset($output) && $output == 2){echo "checked='checked'";}?>/> Output to Printer&nbsp;&nbsp;&nbsp; 
                         <!-- <input type="radio"  class="radio" name="output" id="output_to_excel" style="display: inline;" value="3" <?php if(isset($output) && $output == 3){echo "checked='checked'";}?>/> Output to Excel&nbsp;&nbsp;&nbsp; -->
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
    xmlhttp.open('GET', 'ajax_sales_report.php?filter=<?php echo $_GET['filter']; ?>', true);
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
                  console.log(this.value)
                   if($("input[name='filter_by']:checked").val() == "2"){
                            $('#div_sort_by').hide();
                            $('#broker_filter_wrap').hide();                            
                            $('#div_trade_by').show();
                   }
                   else{
                            $('#div_sort_by').show();
                            $('#broker_filter_wrap').show();
                            $('#div_trade_by').hide();
                   }
            }).trigger('change');
            $("#report_for").on('change', function(event) {
                event.preventDefault();
                let _option=$(this).children('option:selected').val();
                 var siblings= $('#'+_option+"_filter_wrap").siblings();
                   siblings.find("input,select").attr("disabled","disabled").end().hide();;
                   if(_option=="Category Summary Report")
                   {
                        $('#div_sort_by').hide();
                        $('#broker_filter_wrap').hide();  
                        $('#div_date_by').show();
                   }
                   else
                   {
                        $('#div_sort_by').show();
                        $('#broker_filter_wrap').show();
                        $('#div_date_by').hide();
                   }
                    if(_option=="Production by Sponsor Report")
                    {
                        $('#div_PROD_CAT').show();
                        $('#div_sort_by').hide();
                        $('#broker_filter_wrap').hide();
                    }
                    else
                    {
                        $('#div_PROD_CAT').hide();
                    }
                   $('#'+_option+"_filter_wrap").find("input,select").removeAttr("disabled").end().show();
            }).trigger('change');

              $("#report_form").submit(function(ev){
  
       
                 if($("input[name='output']:checked").val()== 4){
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
                 }
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