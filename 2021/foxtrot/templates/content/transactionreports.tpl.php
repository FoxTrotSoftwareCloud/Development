
<div class="container">
<h1>Report</h1>
    <div class="col-md-12 well">
        <form method="POST" id="report_form">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Select Report </label>
                    <select class="form-control" name="report_for" id="report_for">
                        <option <?php  echo ($report_for == 'branch') ? "selected='selected'": ''; ?> value="branch">By Branch</option>
                         <option <?php  echo ($report_for == 'sponsor') ? "selected='selected'": ''; ?> value="sponsor">By Sponsor</option>
                        <option <?php  echo ($report_for == 'client') ? "selected='selected'": ''; ?> value="client">By Client</option>
                        <option <?php  echo ($report_for == 'product') ? "selected='selected'": ''; ?> value="product">By Product</option>
                        <option <?php  echo ($report_for == 'broker') ? "selected='selected'": ''; ?> value="broker">By Broker</option>
                        <option <?php  echo ($report_for == 'batch') ? "selected='selected'": ''; ?> value="batch">By Batch</option>
                       
                    </select> 
                </div>
             </div>
        </div>
        <br />
        <div class="panel" id="report_filters">
        <div class="titlebox">Transcation History Reports</div><br />
        <div class="row">
            <div class="col-md-8">
                <div class="">
                    <div class="row" id="branch_filter_wrap">
                        <div class="col-md-12">
                            <div class="form-group wrap">
                                <label>Branch </label>
                                <select class="form-control state" name="branch">
                                    <option value="0">All Branches</option>
                                    <?php
                                            
                                     foreach($get_branch as $branch_row):
                            
                                     ?>
                                        <option value="<?php echo $branch_row['id']; ?>" <?php echo isset($branch) && $branch==$branch_row['id'] ? 'selected' : '' ?>><?php echo $branch_row['name']; ?></option>
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
                    <div class="row" id="sponsor_filter_wrap">
                        <div class="col-md-12">
                            <div class="form-group wrap">
                                <label>Sponser </label>
                                <select class="form-control sponser" name="sponsor">
                                    <option value="0">All Sponsers</option>
                                    <?php foreach($get_sponsors as $get_sponsor): ?>
                                        <option value="<?php echo $get_sponsor['id']; ?>" <?php if(isset($sponser) && $sponser==$val['id']){ ?>selected="true"<?php } ?>><?php echo $get_sponsor['name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="client_filter_wrap">
                        <div class="col-md-12">
                            <div class="form-group wrap">
                                <label>Clients </label>
                                <select class="form-control sponser" name="client">
                                    <option value="0">All Clients</option>
                                    <?php foreach($get_client as $key=>$val){?>
                                       <option value="<?php echo $val['id'];?>" <?php if(isset($client_name) && $client_name==$val['id']){ ?>selected="true"<?php } ?>><?php echo $val['last_name'].' '.$val['mi'].' '.$val['first_name'];?></option>
                                    <?php }; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="product_filter_wrap">
                         <div class="col-md-12">
                            <div class="form-group wrap">
                                <label>Product Category <span class="text-red">*</span></label><br />
                                    
                                <select class="form-control" data-required="true" name="batch_cate" id="batch_cate" onchange="get_product(this.value);">
                                    <option value="0">Select Product category</option>
                                     <?php foreach($product_category as $key=>$val){      ?>
                                    <option value="<?php echo $val['id'];?>" <?php if(isset($batch_cate) && $batch_cate==$val['id']){?> selected="true"<?php } ?>><?php echo $val['type'];?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div> 
                        <div class="col-md-12">
                            <div class="form-group wrap">
                                <label>Product </label>

                                <select class="form-control sponser" id="product_dropdown" name="product">
                                    <option value="0">All Products</option>
                                    <?php foreach($products as $key=>$val){
                                               $val['name']=trim($val['name']);
                                              
                                                if(empty($val['name'])) continue;
                                        ?>
                                       <option  data-cat="<?php echo $val['category'] ?>" value="<?php echo $val['id'];?>" <?php if(isset($product) && $product==$val['id']){ ?>selected="true"<?php } ?>><?php echo $val['name'];?></option>
                                    <?php }; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="batch_filter_wrap">
                        <div class="col-md-12">
                            <div class="form-group wrap">
                                <label>Product Category <span class="text-red">*</span></label><br />
                                    
                                <select class="form-control" data-required="true" name="batch_cate" id="batch_cate" onchange="get_batches(this.value);">
                                    <option value="0">Select Product category</option>
                                     <?php foreach($product_category as $key=>$val){?>
                                    <option value="<?php echo $val['id'];?>" <?php if(isset($batch_cate) && $batch_cate==$val['id']){?> selected="true"<?php } ?>><?php echo $val['type'];?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>     
                        <div class="col-md-12">
                            <div class="form-group wrap">
                                <label>Batches </label>
                                <select class="form-control sponser" id="batch_dropdown" name="batch">
                                    <option value="0">All Batches</option>
                                    <?php 
                                         
                                    foreach($get_batches as $get_batch): ?>
                                        <option value="<?php echo $get_batch['id']; ?>" <?php if(isset($batch) && $batch==$get_batch['id']){ ?>selected="true"<?php } ?> data-cat="<?php echo $get_batch['pro_category'] ?>"><?php echo $get_batch['batch_desc']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                </div>
                
                <div class="row">
                        <div class="col-md-12">
                                <div class="form-group">
                                     <input type="checkbox" value="1" checked="checked"> Exclude 12B1 Trails
                                </div>
                        </div>
                </div>
                <div class="row">

                   
                             
                              <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="radio-inline">
                                           <input type="radio" class="radio" name="filter_by" id="filter_by" style="display: inline;" value="2" <?php if(isset($filter_by) && ($filter_by == 2)){echo "checked='checked'";}?>/>All Trades 
                                        </label>
                                        <label class="radio-inline">   
                                            <input type="radio" class="radio" name="filter_by" id="filter_by" style="display: inline;" value="1" <?php if(isset($filter_by) && ($filter_by == 1 || $filter_by == '')){echo "checked='checked'";}?>/>Select Dates
                                        </label>
                                    </div>
                              </div>      
                                 
                                  <div class="col-md-8" id="date_filter_block">
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
                                       
                               
                            </div>   
                        </div>     
                                <div class="row">
                                   
                                   
                                                 
                                        <div class="col-md-12">
                                                <label>Output Based on</label>
                                                <div class="form-group">
                                                    <label class="radio-inline"> 
                                                      <input type="radio" class="radio" name="date_by" id="date_by" style="display: inline;" value="1" <?php if(isset($date_by) && ($date_by == 1 || $date_by == '')){echo "checked='checked'";}?>/> Trade Date&nbsp;&nbsp;&nbsp;
                                                    </label>
                                                    <label class="radio-inline">  
                                                       <input type="radio" class="radio" name="date_by" id="date_by" style="display: inline;" value="2" <?php if(isset($date_by) && ($date_by == 2)){echo "checked='checked'";}?>/> Commission Received Date
                                                    </label>   
                                                </div>
                                        </div>
                                  
                                 </div>    
                           
              
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Output to</label><br/>
                            <label class="radio-inline">
                                <input type="radio" class="radio" name="output" id="output_to_screen" style="display: inline;" value="1" <?php if(isset($output) && ($output == 1 || $output == '')){echo "checked='checked'";}?>/> Screen&nbsp;&nbsp;&nbsp;
                            </label>
                            <label class="radio-inline">
                                <input type="radio" class="radio" name="output" id="output_to_printer" style="display: inline;" value="2" <?php if(isset($output) && $output == 2){echo "checked='checked'";}?>/> Printer&nbsp;&nbsp;&nbsp; 
                            </label>
                            <label class="radio-inline">
                                <input type="radio" class="radio" name="output" id="output_to_excel" style="display: inline;" value="3" <?php if(isset($output) && $output == 3){echo "checked='checked'";}?>/> Excel&nbsp;&nbsp;&nbsp;
                            </label>
                            <label class="radio-inline">
                                <input type="radio" class="radio" name="output" id="output_to_pdf" style="display: inline;" value="4" <?php if(isset($output) && $output == 4){echo "checked='checked'";}?>/> PDF
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
    xmlhttp.open('GET', 'ajax_transaction_report.php?filter=<?php echo $_GET['filter']; ?>', true);
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
      
      if(cat ==0){
         $("select#batch_dropdown").find("option").show();
      }
      else{
              $("select#batch_dropdown").find("option").hide();
             $("select#batch_dropdown").find("option[data-cat='"+cat+"']").show();
      }
     
}
function get_product(cat){
    
      if(cat ==0){
         $("select#product_dropdown").find("option").show();
      }
      else{
              $("select#product_dropdown").find("option").hide();
             $("select#product_dropdown").find("option[data-cat='"+cat+"']").show();
      }
     
}

 $(function() {
             
             $("#batch_cate").trigger("change");
               $("#batch_cate").trigger("change");
             
            $("input[name='filter_by']").change(function(){
                  console.log(this.value)
                   if($("input[name='filter_by']:checked").val() == "2"){
                            $('#date_filter_block').hide();
                   }
                   else{
                            $('#date_filter_block').show();
                   }
            }).trigger('change');
            $("#report_for").on('change', function(event) {
                event.preventDefault();
                let _option=$(this).children('option:selected').val();
                 var siblings= $('#'+_option+"_filter_wrap").siblings();
                   siblings.find("input,select").attr("disabled","disabled").end().hide();;

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

                            url = "http://foxtrotsoftware.com/CloudFox/transaction_broker_report_print.php?filter="+JSON.stringify(value);
                        }
                        else{
                              url = "http://foxtrotsoftware.com/CloudFox/transaction_report_print.php?filter="+JSON.stringify(value);
                        }
                        
                       


                       
                   
                    var win= window.open(url,"blank");
                    win.focus();
                    return false;
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
.checkbox-inline, .radio-inline {
    padding-left: 22px;
}
.checkbox input[type=checkbox], .checkbox-inline input[type=checkbox], .radio input[type=radio], .radio-inline input[type=radio]{
    margin-left: -22px;
    margin-top: 1px;
}
#date_filter_block label {
    width: auto;
    display: inline-block;
    float: left;
    margin-right: 10px;
    font-size: 14PX;
}
#date_filter_block #demo-dp-range{
    display: inline-block;
    width: 150px;
}
#date_filter_block #demo-dp-range {
    width: 111px;
    float: left;
}
</style>