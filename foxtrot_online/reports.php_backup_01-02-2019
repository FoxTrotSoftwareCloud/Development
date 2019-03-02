<?php
require_once 'header.php';
?>

<html lang="en">
<head>
	<?php
	echo HEAD;
	?>
</head>

<body>
<div class='loader'></div>
<!--Top Navigation Bar-->
<?php echo show_top_navigation_bar(); ?>

<!--Content-->
<div class="container-fluid">
	<div class="row">
		<!--Sidebar-->
		<?php echo show_sidebar(basename(__FILE__, '.php')); ?>

		<!--Main Content-->
		<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
			<div class="pt-3 pb-2 mb-2 border-bottom">
				<h2>Graphs & Analytics</h2>
			</div>
			<form id="reports_form" class="dates_form col-md-12">
				<div class="server_response_div">
					<div class="alert" role="alert"></div>
				</div>
				<label>Date:</label>
				<select id="time_periods_select" name="time_period" class="mr-2">
					<option id='dates_form_option_all_dates' value="all_dates">All Dates</option>
					<option value="Year to Date">Year to Date</option>
					<option value="Month to Date">Month to Date</option>
					<option value="Previous 12 Months">Previous 12 Months</option>
					<option value="Last Year">Last Year</option>
					<option value="Last Month">Last Month</option>
					<option id="dates_form_option_custom" value="Custom">Custom</option>
				</select>
				<div class="row">
					<div id="reports_form_dates_radios_div" class="col-xs-2 mr-3 ml-3">
						<div class="custom-control custom-radio custom-control">
							<input type="radio" id="trade_date_radio" name="choose_date_radio"
							       class="custom-control-input" value="date" checked disabled>
							<label class="custom-control-label" for="trade_date_radio">Trade Date</label>
						</div>
						<div class="custom-control custom-radio custom-control">
							<input type="radio" id="date_posted_radio" name="choose_date_radio"
							       class="custom-control-input" value="date_rec" disabled>
							<label class="custom-control-label" for="date_posted_radio">Date
								Posted</label>
						</div>
					</div>
					<div class="col-xs-3">
						<div class="custom-control custom-radio custom-control">
							<input type="radio" id="net_pay_radio" name="choose_pay_radio"
							       class="custom-control-input" value="rep_comm" checked>
							<label class="custom-control-label" for="net_pay_radio">Net Pay</label>
						</div>
						<div class="custom-control custom-radio custom-control">
							<input type="radio" id="gross_comm_radio" name="choose_pay_radio"
							       class="custom-control-input" value="comm_rec">
							<label class="custom-control-label" for="gross_comm_radio">Gross
								Commission</label>
						</div>
					</div>
					<input class="btn btn-primary mt-1 mb-1 ml-2" type="submit" value="Apply">
				</div>
				<div class="hidden_form_div mt-2">
					<label>From</label>
					<input type="date" name="from_date" required disabled><br class="d-xs-block d-sm-none">
					<label>To</label>
					<input type="date" name="to_date" required disabled><br class="d-xs-block d-sm-none">
				</div>
				<input name="class" value="no_class" hidden>
				<input name="func" value="reports_update" hidden>
			</form>
			<div class="row"> <!-- Line Chart div -->
				<div class="col-xl-8 col-xs-12">
					<?php
					try{
						$line_chart_data = line_chart_data_and_labels(['time_period' => 'all_dates']);
						echo "<script type='text/javascript'>
							var line_chart_data = $line_chart_data;
						</script>";
					}catch(Exception $e){
						catch_doc_first_load_exception($e, 'reports_form');
					}
					?>
					<canvas id="reports_line_chart"></canvas>
					<script type="text/javascript" src="line_chart_no_data.js"
					        chart_id="reports_line_chart"></script>
					<script type="text/javascript">
						line_chart.data = line_chart_data;
						line_chart.update();
					</script>
				</div>
			</div>

			<div class="row mt-5 mb-5">
				<div class="col-md-7 mb-5" style="height: 300px;"> <!-- Pie Chart div -->
					<?php
					try{
						$json_obj       = pie_chart_data_and_labels('reports_pie_chart');
						$pie_chart_data = $json_obj->data_arr['pie_chart_data'];
						echo "<script type='text/javascript'>
							var pie_chart_data = $pie_chart_data;
						</script>";
					}catch(Exception $e){
						catch_doc_first_load_exception($e, 'reports_form');
					}
					?>
					<canvas id="reports_pie_chart"></canvas>
					<script type="text/javascript" src="pie_chart_no_data.js"
					        chart_id="reports_pie_chart"></script>
					<script type="text/javascript">
						var pie_charts_arr = [];
						pie_charts_arr.push(pie_chart);
						pie_charts_arr[0].data = pie_chart_data;
						pie_charts_arr[0].options.title = {
							display: true,
							fontSize: 14,
							text: "Breakdown by Product Category"
						};
						pie_charts_arr[0].update();
					</script>
					<p class="text-center text-lg-left mb-0"><small class="text-muted ml-lg-4 pl-lg-5">Click on chart for details</small></p>
				</div>
				<div id="reports_table" class="col-md-5">
					<?php
					try{
						$json_obj = pie_chart_data_and_labels('reports_pie_chart');
						echo $json_obj->data_arr['reports_table_html'];
					}catch(Exception $e){
						catch_doc_first_load_exception($e, 'reports_form');
					}
					?>
				</div>
			</div>

			<!-- Modal -->
			<div class="modal fade" id="drill_down_pie_chart_modal" tabindex="-1" role="dialog"
			     aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="forgot_password_modal_title">Trades list</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div id="drill_down_table_div" class="modal-body">
						</div>
					</div>
				</div>
			</div>


		</main>
	</div>
</div>


<?php
require_once 'footer.php';
?>
</body>
</html>