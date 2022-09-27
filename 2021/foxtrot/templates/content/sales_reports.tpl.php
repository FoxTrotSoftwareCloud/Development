<div class="container">
<h1>Report</h1>
    <div class="col-md-12 well">
        <form method="POST" id="report_form">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Select Report</label>
                    <select class="form-control" name="report_for" id="report_for">
                        <option data-options=".sort_by_options,.broker-options,.companies-options,.date-ranger-options,.output-options" <?php  echo ($report_for == 'Production by Product Category') ? "selected='selected'": ''; ?> value="Production by Product Category">Production by Product Category</option>
                         <option data-options=".companies-options,.date-ranger-options,.transaction-date-options,.output-options" <?php  echo ($report_for == 'Product Category Summary Report') ? "selected='selected'": ''; ?> value="Product Category Summary Report">Product Category Summary Report</option>
                        <option data-options=".companies-options,.product-categories,.date-ranger-options,.output-options" <?php  echo ($report_for == 'Production by Sponsor Report') ? "selected='selected'": ''; ?> value="Production by Sponsor Report">Production by Sponsor Report</option>
                        <option data-options=".year_to_date_filter_wrap,.output-options" <?php  echo ($report_for == 'year_to_date') ? "selected='selected'": ''; ?> value="year_to_date">Year-to-Date Earnings Report</option>
                        <option data-options=".broker_ranking_filter_wrap,.product-categories,.output-options" <?php  echo ($report_for == 'broker_ranking') ? "selected='selected'": ''; ?> value="broker_ranking">Broker Rankings Report</option>
                        <option data-options=".companies-options,.output-options,.date-ranger-options" <?php  echo ($report_for == 'monthly_broker_production') ? "selected='selected'": ''; ?> value="monthly_broker_production">Broker Production Report</option>
                       <option data-options=".companies-options,.branch-options,.output-options,.date-ranger-options" <?php  echo ($report_for == 'monthly_branch_office') ? "selected='selected'": ''; ?> value="monthly_branch_office"> Branch Office Production</option>
                       <option data-options=".annual-broker-date-type,.broker-options,.exclude-trails,.year-options,.companies-options,.output-options" <?php  echo ($report_for == 'annual_broker_report') ? "selected='selected'": ''; ?> value="annual_broker_report">Annual Broker Production</option>
                       <!-- <option data-options="" <?php  echo ($report_for == 'batch') ? "selected='selected'": ''; ?> value="batch">Client Rankings Report</option>
                       <option data-options="" <?php  echo ($report_for == 'batch') ? "selected='selected'": ''; ?> value="batch">Broker State Production Report</option>
                       <option data-options="" <?php  echo ($report_for == 'batch') ? "selected='selected'": ''; ?> value="batch">Broker Monthly Production by Product Category</option>
                       <option data-options="" <?php  echo ($report_for == 'batch') ? "selected='selected'": ''; ?> value="batch">Broker Gross Production by Category</option>
                    <option data-options="" <?php  echo ($report_for == 'batch') ? "selected='selected'": ''; ?> value="batch">Brokers without Production</option>
                    <option data-options="" <?php  echo ($report_for == 'batch') ? "selected='selected'": ''; ?> value="batch">Broker-Dealer Retention</option>
                    <option data-options="" <?php  echo ($report_for == 'batch') ? "selected='selected'": ''; ?> value="batch">Broker Gross Production by Product Category</option>
                    <option data-options="" <?php  echo ($report_for == 'batch') ? "selected='selected'": ''; ?> value="batch">Broker Production by Product</option> -->
                    </select> 
                </div>
             </div>
        </div>
        <br />
        <div class="panel" id="report_filters">
        <div class="titlebox">Sales Production Reports</div><br />
        <div class="row">
            <div class="col-md-9">
                <div class="fox-reporting-options">
                    <div class="row companies-options" id="branch_filter_wrap">
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
                     <div class="row branch-options" id="branch_filter_wrap">
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
                    <div class="row broker-options" id="broker_filter_wrap">
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
                    <div class="row year-options">
                     <div class="col-md-12">
                            <div class="form-group wrap">
                                <label>Year </label>
                                <select class="form-control state" name="report_year">
                                    
                                    <?php
                                            $current_year= date("Y");
                                            $year_range = $current_year-10;
                                            $i=0;
                                         while($current_year > $year_range):
                                              $title= $i==0 ? "$current_year - Current Year": ($i==1 ? "$current_year - Previous Year " : $current_year);
                                     ?>
                                        <option value="<?php echo $current_year; ?>" <?php echo isset($report_year) && $report_year==$current_year ? 'selected' : '' ?>><?php echo $title;  ?></option>
                                    <?php $current_year--;$i++; endwhile; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row exclude-trails">
                      <div class="col-md-12 ">
                            <div class="form-group"> 
                                    <input type="checkbox" id="is_trail" name="is_trail" value="1" style="display:inline;"> 
                                    <label for="is_trail">Exclude Trails</label>
                            </div>
                      </div>
                  </div>
                  <div class="row annual-broker-date-type">
                      <div class="col-md-12 ">
                            <div class="form-group"> 
                                    <input checked style="display:inline;" type="radio" class="radio" name="annual-broker-date-type" value="1"> Trade Date  &nbsp;&nbsp;&nbsp;
                                    <input style="display:inline;" type="radio" class="radio" name="annual-broker-date-type" value="2"> Settlement Date
                            </div>
                      </div>
                  </div>

                    <div class="row year_to_date_filter_wrap" id="year_to_date_filter_wrap">
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
                            <div class="form-group wrap">
                                <label>Year </label>
                                <select class="form-control state" name="report_year">
                                    
                                    <?php
                                            $current_year= date("Y");
                                            $year_range = $current_year-10;
                                            $i=0;
                                         while($current_year > $year_range):
                                             $title= $i==0 ? "$current_year - Current Year": ($i==1 ? "$current_year - Previous Year " : $current_year);
                                     ?>
                                        <option value="<?php echo $current_year; ?>" <?php echo isset($report_year) && $report_year==$current_year ? 'selected' : '' ?>><?php echo $title;  ?></option>
                                    <?php $current_year--;$i++; endwhile; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                                <div class="form-group">
                                     
                                      <input  type="radio" class="radio" name="earning_by" id="earning_by_broker" style="display: inline;" value="1" <?php if($sort_by == 1 || $sort_by == ''){echo 'checked="checked"';}?>/> Earnings By Broker
                                       <input type="radio" class="radio" name="earning_by" id="earning_by_branch" style="display: inline;" value="2" <?php if(isset($sort_by) && ($sort_by == 2)){echo 'checked="checked"';}?>/> Earnings By Branch
                                </div>
                        </div>
                        <div class="col-md-12">
                                <div class="form-group">
                                     <input type="checkbox" class="checkbox" name="without_earning" id="without_earning" style="display: inline;" value="1" <?php if(isset($without_earning) && ($without_earning == 1)){echo "checked='checked'";}?>/> Include Brokers With No Earnings
                                </div>
                        </div>          
                       <!--  <div class="col-md-12">
                            <div class="form-group">
                                <input type="radio" class="radio" name="year_output" id="output_to_screen" style="display: inline;" value="1" <?php if(isset($output) && ($output == 1 || $output == '')){echo "checked='checked'";}?>/> Output to Screen&nbsp;&nbsp;&nbsp;
                                <input type="radio" class="radio" name="year_output" id="output_to_printer" style="display: inline;" value="2" <?php if(isset($output) && $output == 2){echo "checked='checked'";}?>/> Output to Printer&nbsp;&nbsp;&nbsp; 
                             <input type="radio"  class="radio" name="year_output" id="output_to_excel" style="display: inline;" value="3" <?php if(isset($output) && $output == 3){echo "checked='checked'";}?>/> Output to Excel&nbsp;&nbsp;&nbsp; 
                                <input type="radio" class="radio" name="year_output" id="output_to_pdf" style="display: inline;" value="4" <?php if(isset($output) && $output == 4){echo "checked='checked'";}?>/> Output to PDF
                            </div>
                        </div> -->
                    </div>

                     <div class="row product-categories" id="div_PROD_CAT">
                        <div class="col-md-12">                                   
                            <label id="CAT_label">Product Category</label> 
                            

                            <div class="form-group">
                                <select name="prod_cat[]" data-placeholder="Select one or more categories" id="prod_cat" class="form-control chosen-select" multiple="true">
                                    <option value="-1" <?php echo empty($prod_cat) ? 'selected="selected"' : ''; ?>>  All Categories</option>
                                    <?php 


                                    foreach($product_category as $key => $val) {?>
                                            <option data-options="" <?php echo in_array($val['id'],$prod_cat)?'selected="selected"':''; ?> value="<?php echo $val['id'];?>"><?php echo $val['type']?></a></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>           
                    </div>
                    <div class="row broker_ranking_filter_wrap" id="broker_ranking_filter_wrap">
                        <div class="col-md-12">
                            <div class="form-group wrap">
                                <label>Company </label>
                                <select class="form-control state" name="company">
                                    <option value="0" selected="selected">All Companies</option>
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

                                <?php 
                                    $ranks = ['Rank on Total Earnings','Rank on Gross Concessions','Rank on Total Sales',
                                    'Rank on Profitability'];
                                    foreach ($ranks as $key => $rank_name) {
                                        $uid = strtolower(str_replace(' ','_', $rank_name));
                                        $checked = ($key==0) ? 'checked' : '';
                                        echo '<input '.$checked.' type="radio" class="radio" name="report_rank" id="'.$uid.'" style="display: inline;" value="'.($key+1).'" /> <label for="'.$uid.'"> '.$rank_name.'&nbsp;&nbsp;&nbsp; </label> ';
                                    }


                                ?>
                                
                                
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <input id="broker_type_all-brokers" checked="checked" type="radio" class="radio" name="broker_type" value="1" style="display: inline;"> 
                                <label for="broker_type_all-brokers"> All Brokers  &nbsp;&nbsp;&nbsp; </label>
                                <input id="broker_type_top-broker" type="radio" class="radio" name="broker_type" value="2" style="display: inline;"> 
                                <label for="broker_type_top-broker">Top Brokers Only &nbsp;&nbsp;&nbsp; </label> 
                                <input type="number" value="1" name="top_broker_count" min="1" max="200" style="display: inline;">
                            </div>
                        </div>


                        <div class="col-md-12">
                                <div class="form-group">
                                     
                                      <input type="radio" class="radio earning_by" name="date_earning_by" id="earning_by" style="display: inline;" value="1" <?php if(isset($sort_by) && ($sort_by == 1 || $sort_by == '')){echo "checked='checked'";}?>/> Current Payroll
                                       <input type="radio" class="radio earning_by" name="date_earning_by" id="earning_by" style="display: inline;" value="2" <?php if(isset($sort_by) && ($sort_by == 2)){echo "checked='checked'";}?>/> Specified Date Range
                                </div>
                        </div>
                        <div class="col-md-12 date-options" <?php if(isset($sort_by) && ($sort_by == 1 || $sort_by == '')){echo "style='display:none'";}?>/>
                                <div class="col-md-6 beginning_date">
                            <div class="form-group beginning_date">
                                <label>Beginning Date </label>
                                <div id="demo-dp-range">
                                    <div class="input-daterange input-group" id="datepicker">
                                        <input type="text" name="beginning_date" id="beginning_date" class="form-control" value="<?php if(isset($beginning_date) && $beginning_date != ''){ echo $beginning_date;} else {echo date('m/01/Y');} ?>"/>
                                    </div>
                                </div>
                            </div>
                        </div> 
                        <div class="col-md-6 ending_date">
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
                        <?php /*    
                    <div class="col-md-12">
                            <div class="form-group wrap">
                                <label>Year </label>
                               
                            </div>
                        </div>

                        <div class="col-md-12">
                                <div class="form-group">
                                     <input type="checkbox" class="checkbox" name="without_earning" id="without_earning" style="display: inline;" value="1" <?php if(isset($without_earning) && ($without_earning == 1)){echo "checked='checked'";}?>/> Include Brokers With No Earning
                                </div>
                        </div>    

                        
                        */ ?>


                    </div>
                       

                   
                
                <div class="row date-ranger-options">
                      

                        <div class="col-md-6 beginning_date">
                            <div class="form-group beginning_date">
                                <label>Beginning Date </label>
                                <div id="demo-dp-range">
                                    <div class="input-daterange input-group" id="datepicker">
                                        <input type="text" name="beginning_date" id="beginning_date" class="form-control" value="<?php if(isset($beginning_date) && $beginning_date != ''){ echo $beginning_date;} else {echo date('m/01/Y');} ?>"/>
                                    </div>
                                </div>
                            </div>
                        </div> 
                        <div class="col-md-6 ending_date">
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
                    <div class="row sort_by_options" id="div_sort_by">
                        <div class="col-md-12">
                                <div class="form-group">
                                     
                                      <input type="radio" class="radio" name="sort_by" id="sort_by" style="display: inline;" value="1" <?php if(isset($sort_by) && ($sort_by == 1 || $sort_by == '')){echo "checked='checked'";}?>/> Sort by Sponsor&nbsp;&nbsp;&nbsp;
                                       <input type="radio" class="radio" name="sort_by" id="sort_by" style="display: inline;" value="2" <?php if(isset($sort_by) && ($sort_by == 2)){echo "checked='checked'";}?>/> Sort by Investment Amount
                                </div>
                        </div>
                    </div>



                     <div class="row transaction-date-options" id="div_date_by">
                        <div class="col-md-12">
                                <div class="form-group">
                                     
                                      <input type="radio" class="radio" name="date_by" id="date_by" style="display: inline;" value="1" <?php if(isset($date_by) && ($date_by == 1 || $date_by == '')){echo "checked='checked'";}?>/> Transaction Date&nbsp;&nbsp;&nbsp;
                                       <input type="radio" class="radio" name="date_by" id="date_by" style="display: inline;" value="2" <?php if(isset($date_by) && ($date_by == 2)){echo "checked='checked'";}?>/>Commission Received Date
                                </div>
                        </div>
                    </div>
                <div class="row output-options" >
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
 $.fn.serializeObject = function() {
        var o = {};
        var a = this.serializeArray();
        $.each(a, function() {
            this.name = this.name.replace(/[\[\]']+/g,'');

            if (o[this.name]) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });
        return o;
    };


 $(function() {

        $(document).on('click','#broker_ranking_filter_wrap .earning_by',function(){
            var currentValue = $(this).val();
            if(currentValue == 2) {
                $('.date-options').show();
            }
            else  $('.date-options').hide();

        })
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
                console.log(_option);
                var reporting_options = $('option:selected', this).data('options');
                 var siblings= $('#'+_option+"_filter_wrap").siblings();
                   siblings.find("input,select").attr("disabled","disabled").end().hide();;
                   $("#broker_ranking_filter_wrap").hide();

                   if(_option=="Product Category Summary Report")
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
                   /* if(_option=='year_to_date'){
                          $("#year_to_date_filter_wrap").siblings().hide().end().show();
                    }
                    else{
                           $("#year_to_date_filter_wrap").siblings().show().end().hide();
                    }*/
                    if(reporting_options != '' ){
                        console.log(reporting_options);
                        $('.fox-reporting-options').attr('id',_option);
                        $('.fox-reporting-options .row:not('+reporting_options+')').hide().find("input,select").attr("disabled","disabled");
                        $(reporting_options).show().find("input,select").removeAttr("disabled");
                         $('#prod_cat').trigger('chosen:updated')
                        
                       /* $('.fox-reporting-options .row:not('+reporting_options+')').;*/
                        
                    }
                   /* else{
                           $("#year_to_date_filter_wrap").siblings().show().end().hide();
                    }*/
                   $('#'+_option+"_filter_wrap").find("input,select").removeAttr("disabled").end().show();

                   //$('.broker_ranking_filter_wrap .earning_by').trigger("click");
            }).trigger('change');

        $("#report_form").submit(function(ev){
            var report_for = $("select[name='report_for']").val() ;
            var output_type = $("input[name='output']:checked").val();
            
            if(output_type == 4 || output_type == 2) {
                ev.preventDefault();
                var formData_obj = $(this).serializeObject();
                const data = new FormData(ev.target);
                    value = Object.fromEntries(data.entries());
                 
                if( $("#report_for").val() == "broker"){
                    url = "http://foxtrotsoftware.com/CloudFox/sales_broker_report_print.php?filter="+JSON.stringify(formData_obj);
                }
                else{
                    url = "http://foxtrotsoftware.com/CloudFox/sales_report_print.php?filter="+JSON.stringify(formData_obj);
                }
                if(output_type == 2) url+='&open=output_print';
               
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
        
        $('.chosen-select').chosen({

        });

        $("#prod_cat").change(function(){
            var selected = $(this).find(":selected").map((_, e) => e.value).get();
               // console.log(selected,$(this).find("option[value=-1]"),selected.includes("-1"), );
                if(selected.includes("-1")){
                    $(this).find("option[value=-1]").siblings().attr('disabled',true).end().removeAttr('disabled');
                }
                else{
                      $(this).find("option[value=-1]").siblings().removeAttr('disabled').end().attr('disabled',true);
                }
                if(selected.length==0){
                      $(this).find("option").removeAttr('disabled');
                }
                $(this).trigger('chosen:updated')
        }).trigger("change");
       /* $(document).on('change','.chosen-select',function(){
            var get_current_values = $(this).val();
            if(get_current_values != null) {
                var isAllCategory = (get_current_values[0] == '');
                if(isAllCategory) {
                  
                    // $('.chosen-select').find("option [value!='0'] ").prop('disabled','disabled');
                     //  $('.chosen-select').trigger('chosen:updated');

                }
               
            }
           
        })*/
        
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

input[type=checkbox] + label , input[type=radio] + label {
    font-weight: normal;
}
.modal-heading-row td {
    text-transform: uppercase;
    /*border-bottom: 1px solid;*/
       font-weight: 500;
    font-size: 14px;

}
tbody.modal-tbody-rows td {font-family: inherit;
    font-weight: 500;
    line-height: 1.1;
    color: inherit;font-size: 14px;
    padding: 10px 0;
}
td span.broker_name {
    border-bottom: 1px solid;
}
tr.t-footer-items td:not(:first-child) {
   /* border-top: 1px solid;*/
}
thead.modal-heading-row tr,tr.t-footer-items {background-color: #f1f1f1;}
/*tr.t-footer-items td.total {font-size: 14px;}*/
tr.t-footer-items td {font-weight: bold;     font-size: 12px;}
td .branch_name {font-weight: bold;border-bottom: 1px solid;}
td.td-branch {padding-top: 10px;}
</style>