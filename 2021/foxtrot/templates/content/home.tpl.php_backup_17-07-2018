<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/highcharts-3d.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>

<script type="text/javascript" lang="javaScript">
$(document).ready(function (){


 var data_investment_amount = <?php if($invest_amount==''){ echo '0'; } else {echo $invest_amount; }?>;
 var data_charge_amount = <?php if($charge_amount == '') {echo '0'; }else{echo $charge_amount; }?>;
 var data_commission_received_amount = <?php if($commission_received_amount==''){echo '0';}else {echo $commission_received_amount;} ?>;
 
 var di_completed_files = <?php echo $di_completed_files; ?>;
 var di_partially_completed = <?php echo $di_partially_completed_files; ?>;
 var di_new_files = <?php echo $di_new_files; ?>;

Highcharts.chart('container_daily_importing', {
    chart: {
        type: 'pie',
        options3d: {
            enabled: true,
            alpha: 45,
            beta: 0,
            width: 849,
            height: 400
        }
    },
    title: {
        text: ''
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            depth: 20,
            dataLabels: {
                enabled: false,
                format: '{point.name}'
            }
        }
    },
    series: [{
        type: 'pie',
        name: 'Imported Files',
        data: [
            
            ['Partially Completed', di_partially_completed],
            ['New Files', di_new_files],
            ['Completed', di_completed_files]
        ]
    }]
});
Highcharts.chart('container2', {
    chart: {
        type: 'bar'
    },
    title: {
        text: ''
    },
    
    xAxis: {
        categories: 
        <?php echo $ytd_product_category_chart;?>
        ,
        title: {
            text: null
        }
    },
    yAxis: {
        min: 0,
        title: {
            text: '',
            align: ''
        },
        labels: {
            overflow: ''
        }
    },
    plotOptions: {
        bar: {
            dataLabels: {
                enabled: true
            }
        }
    },
    credits: {
        enabled: false
    },
    series: [{
        color:'orange',
        name: 'Investment Amount',
        data: [<?php echo $ytd_total_investment_amount_chart;?>]

    }, {
        color:'green',
        name: 'Commission Received',
        data: [<?php echo $ytd_total_commission_received_chart;?>]

    }, { 
        color:'black',
        name: 'Commission Paid',
        data: [<?php echo $ytd_total_commission_pending_chart;?>]

    }]
});
Highcharts.chart('container3', {
    chart: {
        type: 'column'
    },
    title: {
        text: ''
    },
    xAxis: {
        categories: <?php echo $dis_month_list; ?>,
        crosshair: true
    },
    yAxis: {
        min: 0,
        title: {
            text: ''
        }
    },
    plotOptions: {
        column: {
            pointPadding: 0,
            borderWidth: 0
        }
    },
    series: [{
        name: 'Transaction Processed',
        data: [<?php echo $transaction_processed_data ; ?>]

    }, {
        name: 'Transaction on Hold',
        data: [<?php echo $transaction_hold_data; ?>]

    }]
});
Highcharts.chart('container_commission', {
    chart: {
        type: 'pie',
        options3d: {
            enabled: true,
            alpha: 45,
            beta: 0,
            width: 849,
            height: 400
        }
    },
    title: {
        text: ''
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            depth: 20,
            dataLabels: {
                enabled: false,
                format: '{point.name}'
            }
        }
    },
    series: [{
        type: 'pie',
        name: 'Commision Ammount',
        data: [
            ['Investment Amount', data_investment_amount],
            ['Charge Amount', data_charge_amount],
            ['Commission Received Amount', data_commission_received_amount]
        ]
    }]
});
Highcharts.chart('container_payroll', {
    chart: {
        type: 'column'
    },
    title: {
        text: ''
    },
    xAxis: {
        categories: [
            'Last Cutoff',
            'Gross Commission',
            'Average Payout Rate',
            'Charges',
            'Net Commission',
            'Adjustment',
            'Total Check Amount',
            'Balance Carried Forword',
            'Retention'
        ],
        crosshair: true
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Commission'
        }
    },
    tooltip: {
        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
            '<td style="padding:0"><b>{point.y:.1f} mm</b></td></tr>',
        footerFormat: '</table>',
        shared: true,
        useHTML: true
    },
    plotOptions: {
        column: {
            pointPadding: 0.2,
            borderWidth: 0,
            height:30
        }
    },
    series: [{
        
        name: 'Payroll',
        data: [42.4, 33.2, 34.5, 39.7, 52.6, 75.5, 57.4, 60.4, 47.6]

    }]
});
});
</script>
<div class="sectionwrapper">
  <div class="container">
  <?php require_once(DIR_FS_INCLUDES."alerts.php"); ?>
    <form method="post" id="filter_chart" >
        <input type="hidden" name="from_date" id="from_date" value="" class="form-control" />
        <input type="hidden" name="to_date" id="to_date" value="<?php echo date('Y-m-d');?>" class="form-control" />
        <input type="hidden" name="chart_id" id="chart_id" value=""/>
        <input type="hidden" name="filter" id="filter" value="Filter"/>
    </form>
    <div class="row" id="chart_row">
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 pull-left">
			<div class="graphbox" id="chart_1">
				<div class="graphboxtitle">Daily Importing <i class="fa fa-calendar datepicker" aria-hidden="true" id="calendar_1"></i><i id="showhideclick" class="fa fa-chevron-circle-up"></i></div>
				<div class="graphboxcontent dailyimporting">
					<div class="graphdata01">
						<div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 pull-left">
								<span class="data01title"><b>New Files to Process:</b></span>
								<span class="data01count"><?php echo $di_new_files; ?></span>
							</div>
							<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 pull-left">
								<span class="data01title"><b>Completed Files:</b></span>
								<span class="data01count"><?php echo $di_completed_files; ?></span>
							</div>
							<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 pull-left">
								<span class="data01title"><b>Partially Completed Files:</b></span>
								<span class="data01count"><?php echo $di_partially_completed_files; ?></span>
							</div>
							
						</div>
					</div>
                    <table width='100%' class="graphdata02"> 
                        <tr>
                            <td>New Files to Process:</td>
                            <td><?php echo $di_new_files; ?></td>
                            <td rowspan="5" width='60%'><div id="container_daily_importing" style="min-width: 200px; height: 200px;  max-width: 3000px; margin:  auto"></div></td>
                        </tr>
                        <tr>
                            <td>Completed Files:</td>
                            <td><?php echo $di_completed_files; ?></td>
                        </tr>
                        <tr>
                            <td>Partially Completed Files:</td>
                            <td><?php echo $di_partially_completed_files; ?></td>
                        </tr>
                        
                    </table>
					<div class="graphimg">
                    </div>
				</div>
			</div>
            <div class="graphbox">
				<div class="graphboxtitle">Commissions <i class="fa fa-calendar datepicker" aria-hidden="true" id="calendar_2"></i></i><i id="showhideclick_2" class="fa fa-chevron-circle-up"></i></div>
				<div class="graphboxcontent dailyimporting">
					<div class="graphdata01_2">
						<div class="row">
							<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 pull-left">
								<span class="data01title"><b>Investment Amount:</b></span>
								<span class="data01count">$<?php echo number_format($invest_amount);?></span>
							</div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 pull-left">
								<span class="data01title"><b>Commission Received:</b></span>
								<span class="data01count">$<?php echo number_format($commission_received_amount);?></span>
							</div>
							<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 pull-left">
								<span class="data01title"><b>Ticket Charges:</b></span>
								<span class="data01count">$<?php echo number_format($charge_amount);?></span>
							</div>
							
						</div>
					</div>
                    <table width='100%' class="graphdata02_2"> 
                        <tr>
                            <td>Investment Amount:</td>
                            <td>$<?php echo number_format($invest_amount);?></td>
                            <td rowspan="5" style="width: 60%;"><div id="container_commission" style="min-width: 200px; height: 200px; max-width: 3000px; margin:  auto"></div></td>
                        </tr>
                        <tr>
                            <td>Commission Received:</td>
                            <td>$<?php echo number_format($commission_received_amount);?></td>
                        </tr>
                        <tr>
                            <td>Ticket Charges:</td>
                            <td>$<?php echo number_format($charge_amount);?></td>
                        </tr>
                    </table>
					<div class="graphimg">
                    </div>
				</div>
            </div>
            <div class="graphbox">
				<div class="graphboxtitle">Payroll <i class="fa fa-calendar datepicker" aria-hidden="true" id="calendar_3"></i><i id="showhideclick_3" class="fa fa-chevron-circle-up"></i></div>
				<div class="graphboxcontent dailyimporting">
					<div class="graphdata01_3">
						<div class="row">
							<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 pull-left">
								<span class="data01title"><b>Last Cutoff :</b></span>
								<span class="data01count">15-11-2017</span>
							</div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 pull-left">
								<span class="data01title"><b>Gross Commission :</b></span>
								<span class="data01count">$325k</span>
							</div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 pull-left">
								<span class="data01title"><b>Average Payout Rate :</b></span>
								<span class="data01count">$346.512.1</span>
							</div>
							<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 pull-left">
								<span class="data01title"><b>Charges :</b></span>
								<span class="data01count">$1.5k</span>
							</div>
							<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 pull-left">
								<span class="data01title"><b>Net Commission :</b></span>
								<span class="data01count">$228k</span>
							</div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 pull-left">
								<span class="data01title"><b>Adjustment :</b></span>
								<span class="data01count">$4.5k</span>
							</div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 pull-left">
								<span class="data01title"><b>Total Check Amount :</b></span>
								<span class="data01count">$265k</span>
							</div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 pull-left">
								<span class="data01title"><b>Balance Carried Forword :</b></span>
								<span class="data01count">$45k</span>
							</div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 pull-left">
								<span class="data01title"><b>Retention :</b></span>
								<span class="data01count">$415k</span>
							</div>
						</div>
					</div>
                    <table width='100%' class="graphdata02_3"> 
                        <tr>
                            <td>Last Cutoff</td>
                            <td>15-11-2017</td>
                            <td rowspan="9" style="width: 60%;"><div id="container_payroll" style="min-width: 200px; height: 200px; max-width: 3000px; margin:  auto"></div></td>
                        </tr>
                        <tr>
                            <td>Gross Commission</td>
                            <td>$325k</td>
                        </tr>
                        <tr>
                            <td>Average Payout Rate</td>
                            <td>$346.512.1</td>
                        </tr>
                        <tr>
                            <td>Charges</td>
                            <td>$1.5k</td>
                        </tr>
                        <tr>
                            <td>Net Commission</td>
                            <td>$228k</td>
                        </tr>
                        <tr>
                            <td>Adjustment</td>
                            <td>$4.5k</td>
                        </tr>
                        <tr>
                            <td>Total Check Amount</td>
                            <td>$265k</td>
                        </tr>
                        <tr>
                            <td>Balance Carried Forword</td>
                            <td>$45k</td>
                        </tr>
                        <tr>
                            <td>Retention</td>
                            <td>$415k</td>
                        </tr>
                    </table>
					<div class="graphimg">
                    </div>
				</div>
            </div>
		</div>	
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 pull-right">
            <div class="graphbox graphbox02">
			<div class="graphboxtitle">Compliance <i class="fa fa-calendar datepicker" aria-hidden="true" id="calendar_4"></i><i id="showhideclick_4" class="fa fa-chevron-circle-up"></i></div>
			<div class="graphboxcontent dailyimporting">
				<div class="graphdata01_4">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pull-left">
							<span class="data01title"><b>Transactions Processed: </b></span>
							<span class="data01count"><?php echo '&nbsp;&nbsp;'.$total_processed_transaction;?></span>
						</div>
                    </div>
     	            <div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pull-left">
							<span class="data01title"><b>Transactions on Hold: </b></span>
							<span class="data01count"><?php echo '&nbsp;&nbsp;'.$total_hold_transaction; ?></span>
						</div>
					</div>
				</div>
                <table width='100%' class="graphdata02_4"> 
                    <tr>
                        <td style="text-align: right;min-width: 145px;">Transactions Processed: </td>
                        <td style="text-align: left;min-width: 145px;"><?php echo '&nbsp;&nbsp;'.$total_processed_transaction; ?></td>
                    </tr>
                    <tr>
                        <td style="text-align: right;min-width: 145px;">Transactions on Hold: </td>
                        <td style="text-align: left;min-width: 145px;"><?php echo '&nbsp;&nbsp;'.$total_hold_transaction;?></td>
                    </tr>
                    <tr>
                        <td colspan="2"> <div id="container3" style="min-width: 290px; height: 400px; margin: 0 auto"></div> </td>
                    </tr>
                </table>
				<div class="graphimg">
                </div>
			</div>
        </div>
        <div class="graphbox graphbox02">
			<div class="graphboxtitle">YTD Production <i class="fa fa-calendar datepicker2" aria-hidden="true" id="calendar_5"></i><i id="showhideclick_5" class="fa fa-chevron-circle-up"></i></div>
			<div class="graphboxcontent dailyimporting">
				<div class="graphdata01_5">
					<div class="row">
                        <?php 
                        foreach($ytd_product_category as $key=>$val){
                        ?>
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pull-left">
							<span class="data01title"><b><?php echo $val;?>:</b></span>
							<span class="data01count"><?php echo $ytd_total_investment_amount[$key];?></span>
                            <span class="data01count"><?php echo $ytd_total_commission_received[$key];?></span>
                            <span class="data01count"><?php echo $ytd_total_commission_pending[$key];?></span>
                        </div>
                        <?php } ?>
					</div>
				</div>
                <table width='100%' class="graphdata02_5"> 
                    <tr>
                        <td colspan="4" width='60%'><div id="container2" style="min-width: 190px; max-width: 800px; height: 400px; margin: 0 auto"></div></td>
                    </tr>
                    <?php 
                        foreach($ytd_product_category as $key=>$val){
                        ?>
                    <tr>
                        <td><?php echo $val;?>:</td>
                        <td><?php echo $ytd_total_investment_amount[$key];?></td>
                        <td><?php echo $ytd_total_commission_received[$key];?></td>
                        <td><?php echo $ytd_total_commission_pending[$key];?></td>
                    </tr>
                    <?php } ?>
                </table>
				<div class="graphimg">
                </div>
			</div>
        </div>
	</div>
</div>
</div>
  </div>
</div>
<script type="text/javascript">
function dateChanged(ev)
{
    var id = ev.target.id
    var date = ev['date'].toDateString()
    var lastChar = id.substr(id.length - 1);
    document.getElementById("chart_id").value = lastChar;
    document.getElementById("from_date").value = date;
    document.getElementById("filter_chart").submit();
    
}
$(document).ready(function(){
    $('.datepicker').datepicker({
        format: "mm-yyyy",
        startView: "months", 
        minViewMode: "months",
        todayBtn: "linked",
        autoclose: true,
        todayHighlight: true
        
        }).on('show',function(){
            $(".datepicker-dropdown").css("z-index",'1000000');
        }).on('changeDate', dateChanged);
        
        $('.datepicker2').datepicker({
        format: "yyyy",
        startView: "years", 
        minViewMode: "years",
        todayBtn: "linked",
        autoclose: true,
        todayHighlight: true
        
        }).on('show',function(){
            $(".datepicker-dropdown").css("z-index",'1000000');
        }).on('changeDate', dateChanged);
		  
});
</script>

<script type="text/javascript">
$(function(){
	$("#showhideclick").click(function () {
	  $('.graphdata01').toggleClass('showhidata');	  
		});
	  
	$("#showhideclick").click(function () {
	  $('.graphdata02').toggleClass('showhidata');	  
		});
})
$('#showhideclick').click(function(){
    $(this).toggleClass('fa-chevron-circle-down fa-chevron-circle-up')
})
$(function(){
	$("#showhideclick_2").click(function () {
	  $('.graphdata01_2').toggleClass('showhidata');	  
		});
	  
	$("#showhideclick_2").click(function () {
	  $('.graphdata02_2').toggleClass('showhidata');	  
		});
})

$('#showhideclick_2').click(function(){
    $(this).toggleClass('fa-chevron-circle-down fa-chevron-circle-up')
})
$(function(){
	$("#showhideclick_3").click(function () {
	  $('.graphdata01_3').toggleClass('showhidata');	  
		});
	  
	$("#showhideclick_3").click(function () {
	  $('.graphdata02_3').toggleClass('showhidata');	  
		});
})

$('#showhideclick_3').click(function(){
    $(this).toggleClass('fa-chevron-circle-down fa-chevron-circle-up')
});
$(function(){
	$("#showhideclick_4").click(function () {
	  $('.graphdata01_4').toggleClass('showhidata');	  
		});
	  
	$("#showhideclick_4").click(function () {
	  $('.graphdata02_4').toggleClass('showhidata');	  
		});
})

$('#showhideclick_4').click(function(){
    $(this).toggleClass('fa-chevron-circle-down fa-chevron-circle-up')
});
$(function(){
	$("#showhideclick_5").click(function () {
	  $('.graphdata01_5').toggleClass('showhidata');	  
		});
	  
	$("#showhideclick_5").click(function () {
	  $('.graphdata02_5').toggleClass('showhidata');	  
		});
})

$('#showhideclick_5').click(function(){
    $(this).toggleClass('fa-chevron-circle-down fa-chevron-circle-up')
});
</script>