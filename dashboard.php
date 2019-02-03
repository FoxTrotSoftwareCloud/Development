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
				<h2>Dashboard</h2>
			</div>
			<div class="card" style="margin-bottom: 0.75rem">
				<div class="card-header">
					<h4 class="card-title mb-0">Gross Commissions Received Payroll-To-Date</h4>
				</div>
				<div class="card-body">
					<h5 class="card-subtitle mb-2" id="posted_commission_heading">
						<?php
						echo dashboard_posted_commissions();
						?>
					</h5>
					<form id="dashboard_form" class="dates_form mb-0">
						<div class="server_response_div">
							<div class="alert" role="alert"></div>
						</div>
						<label>Transactions through Payroll Cutoff Date:</label>
						<input type="date" name="to_date" required>
						<script type="text/javascript">
							var now = new Date();
							var day = ("0" + now.getDate()).slice( -2 );
							var month = ("0" + (now.getMonth() + 1)).slice( -2 );
							var today = now.getFullYear() + "-" + (month) + "-" + (day);
							$( "#dashboard_form input[type=date]" ).val( today );
						</script>
						<input class="btn btn-primary ml-sm-2" type="submit" value="Refresh" required>
						<input name="class" value="no_class" hidden>
						<input name="func" value="dashboard_update" hidden>
					</form>
				</div>
			</div>
			<div class="card-deck mb-3">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title mb-0">Gross Commissions By Product Category</h4>
					</div>
					<div class="card-body" style="height: 300px;">
						<?php
						try{
							$json_obj       = pie_chart_data_and_labels('dashboard_pie_chart');
							$pie_chart_data = $json_obj->data_arr['pie_chart_data'];
							echo "<script type='text/javascript'>
									var pie_chart_data = $pie_chart_data;
								</script>";
						}catch(Exception $e){
							catch_doc_first_load_exception($e, 'dashboard_form');
						}

						?>
						<canvas id="dashboard_pie_chart"></canvas>
						<script type="text/javascript" chart_id="dashboard_pie_chart" src="pie_chart_no_data.js"
						        ></script>
						<script type="text/javascript">
							var pie_charts_arr = [];
							pie_charts_arr.push( pie_chart );
							pie_charts_arr[0].data = pie_chart_data;
							pie_charts_arr[0].options.title = {
								display: true,
								fontSize: 14,
								text: "Payroll To Date"
							};
							pie_charts_arr[0].update();
						</script>
					</div>
					<div class="card-footer text-muted">
						Click on chart for details
					</div>
				</div>
				<div class="card d-none d-lg-block">
					<div class="card-header">
						<h4 class="card-title mb-0">Commission Statement</h4>
					</div>
					<div class="card-body">
						<object id="statement_pdf_object" data="none" type="application/pdf" height="260px"  width="100%"></object>
						<?php
						$x = statement::statements_list("{$_SESSION['company_name']}/data"); //x doesn't matter, initial the function for $_SESSION['first_statement_url']
						echo statement::statement_buttons_pdf_url_changer();
						?>
					</div>
					<div class="card-footer text-muted">
						Hover mouse to download or print
					</div>
				</div>

			</div>
			<div class="card-deck mb-5 pb-2">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title mb-0">Net Commissions Total</h4>
					</div>
					<div class="card-body">
						<form id="dashboard_time_period_form" class="dates_form col-md-12">
							<div class="server_response_div">
								<div class="alert" role="alert"></div>
							</div>
							<label>Date:</label>
							<select id="time_periods_select" name="time_period" class="mr-2">
								<option value="Year to Date">Year to Date</option>
								<option value="Month to Date">Month to Date</option>
								<option value="Previous 12 Months">Previous 12 Months</option>
								<option value="Last Year">Last Year</option>
								<option value="Last Month">Last Month</option>
 						 `   </select>
							<input name="choose_date_radio" value="date" hidden>
							<input name="choose_pay_radio" value="rep_comm" hidden>
							<input name="class" value="no_class" hidden>
							<input name="func" value="reports_update" hidden>
						</form>
						<?php
    						try{
    							$line_chart_data = line_chart_data_and_labels(['time_period' => 'Year to Date']);
    							echo "<script type='text/javascript'>
    									var line_chart_data = $line_chart_data;
    								</script>";
    						}catch(Exception $e){
    							catch_doc_first_load_exception($e, 'dashboard_time_period_form');
    						}
						?>
						<canvas id="dashboard_line_chart"></canvas>
						<script type="text/javascript" src="line_chart_no_data.js"
						        chart_id="dashboard_line_chart">
                        </script>
						<script type="text/javascript">
							line_chart.data = line_chart_data;
							line_chart.options.title = {
								display: true,
								fontSize: 14,
								text: "Net Commission"
							};
							line_chart.update();
						</script>
					</div>
					<div class="card-footer text-muted">
						Choose from the list to change the time period
					</div>
				</div>
				<div class="card">
					<div class="card-header">
						<h4 class="card-title mb-0">Top 10 Sponsors</h4>
					</div>
					<div class="card-body">
                    
                        <!--change by vishva 12/11/2018  2:55PM-->
                        
                        <form id="co_dashboard_time_period_form" class="dates_form col-md-12">
							<div class="server_response_div">
								<div class="alert" role="alert"></div>
							</div>
							<label>Date:</label>
							<select id="co_time_periods_select" name="co_time_period" class="mr-2">
								<option value="Year to Date">Year to Date</option>
								<option value="Month to Date">Month to Date</option>
								<option value="Previous 12 Months">Previous 12 Months</option>
								<option value="Last Year">Last Year</option>
								<option value="Last Month">Last Month</option>
 						 `   </select>
							<input name="choose_date_radio" value="date" hidden>
							<input name="choose_pay_radio" value="rep_comm" hidden>
							<input name="class" value="no_class" hidden>
							<input name="func" value="dashboard_top_sponsors" hidden>
						</form>
                    
						<div style="min-height: 300px" id="sponsor_table">
							<?php
                                 try
                                    {
                                     $json_obj = dashboard_top_sponsors(['co_time_period' => 'Year to Date']);
                                     echo $json_obj->data_arr['sponsor_table'];
			                        }
                                catch(Exception $e)
                                    {
                						catch_doc_first_load_exception($e, 'co_dashboard_time_period_form');
                					}
        					?>
						</div>
					</div>
					<div class="card-footer text-muted">
						Top sponsers on gross commission
					</div>
				</div>
			</div>

			<!-- Modal -->
			<div class="modal fade" id="drill_down_pie_chart_modal" tabindex="-1" role="dialog"
			     aria-hidden="true">
				<div class="modal-dialog" role="document" style="max-width: 1000px !important;">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="forgot_password_modal_title">Trades list</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div id="drill_down_table_div" class="modal-body"></div>
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