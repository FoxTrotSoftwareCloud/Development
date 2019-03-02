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
				<h2>
					<?php echo ucfirst(basename(__FILE__, '.php')) ?>
				</h2>
			</div>
			<div id="activity_boxes_container_div" class="row text-center">
				<?php
				$json_obj = activity_update(['all_dates' => 'on'], true, false);
				echo $json_obj->data_arr['activity_boxes'];
				?>
			</div>
			<div class="row">
				<form id="activity_form" class="col-md-12 dates_form">
					<div class="server_response_div mt-2">
						<div class="alert" role="alert"></div>
					</div>
					<div class="custom-control custom-checkbox">
						<input type="checkbox" name="all_dates" class="custom-control-input"
						       id="all_dates_checkbox" checked>
						<label class="custom-control-label" for="all_dates_checkbox">All Trade Dates</label>
					</div>
					<label>From</label>
					<input type="date" name="from_date" disabled required><br class="d-xs-block d-sm-none">
					<label>To</label>
					<input type="date" name="to_date" disabled required><br class="d-xs-block d-sm-none">
					<input class="btn btn-primary ml-sm-2" type="submit" value="Filter">
					<input name="class" value="no_class" hidden>
					<input name="func" value="activity_update" hidden>
				</form>
			</div>
			<div class="table-responsive mb-5" style="overflow: hidden">
				<table id="activity_table"
				       class="main-table table table-hover table-striped table-sm text-center"
				       style="font-size: 0.8rem">
					<thead>
					<tr>
						<th>DATE</th>
						<th>DATE RECEIVED</th>
						<th>CLIENT ACCOUNT</th>
						<th>CLIENT NAME</th>
						<th>PRODUCT DESCRIPTION</th>
						<th>CUSIP</th>
						<th>PRINCIPAL</th>
						<th>COMMISSION EXPECTED</th>
						<th>COMMISSION RECEIVED</th>
						<th>PAYOUT RATE</th>
						<th>COMMISSION PAID</th>
						<th>DATE PAID</th>
					</tr>
					</thead>
					<tbody>
					<?php
					try{
						$json_obj = activity_update(['all_dates' => 'on'], false);
						echo $json_obj->data_arr['activity_table'];
						$pdf_title_first_line  = $json_obj->data_arr['pdf_title_first_line'];
						$pdf_title_second_line = $json_obj->data_arr['pdf_title_second_line'];
						echo "<script>
							var pdf_title_first_line = '$pdf_title_first_line';
							var pdf_title_second_line = '$pdf_title_second_line';
						</script>";
					}catch(Exception $e){
						catch_doc_first_load_exception($e, 'activity_form');
					}
					?>
					</tbody>
				</table>
				<script type="text/javascript">
					$( document ).ready( function(){
						var currentDate = new Date();
						var current_minutes = ('0'+ currentDate.getMinutes()).slice(-2);
						var top_massage = 'Created: ' + (currentDate.getMonth()+1) + '/' + currentDate.getDate() + '/' + currentDate.getFullYear() + ' ' + currentDate.getHours() + ':' + current_minutes;
						const months_names = ["January", "February", "March", "April", "May", "June",
							"July", "August", "September", "October", "November", "December"
						];
						var file_name = 'Transaction Activity ' + currentDate.getDate() + ' ' + months_names[currentDate.getMonth()] + ' ' + currentDate.getFullYear();
						var pdf_title = pdf_title_first_line + '\n\r' + pdf_title_second_line;
						var excel_title = pdf_title_first_line + ' - ' + pdf_title_second_line;
						$( '#activity_table' ).DataTable( {
							language: {search: ""},
							paging: false,
							info: false,
							dom: 'Bfrtip',
							buttons: [
								{
									extend: 'excelHtml5',
									orientation: 'landscape',
									filename: file_name,
									messageTop: top_massage,
									title: excel_title
								},
								{
									extend: 'pdfHtml5',
									orientation: 'landscape',
									filename: file_name,
									messageTop: top_massage,
									title: pdf_title
								}
							],
							"order": [[ 0, "desc" ]],
							"scrollX": true
						} );

						$( '.buttons-html5' ).addClass( 'btn btn-secondary' );
						$( '#activity_table_filter input' ).addClass( 'form-control' ).attr( "placeholder", "Search" ).css( 'margin', 0 );
						$( '#activity_table_filter' ).width( 210 ).css( 'float', 'right' );
						$( '.dt-buttons' ).width( 200 ).css( 'float', 'left' );
						if( $( document ).width() < 992 ){
							$( '#activity_table_filter' ).width( '100%' ).addClass( 'text-left mt-2' );
							$( '#activity_table_filter input' ).width( '100%' );
						}
					} );
				</script>
			</div>
		</main>
	</div>
</div>
<?php
require_once 'footer.php';
?>
</body>
</html>