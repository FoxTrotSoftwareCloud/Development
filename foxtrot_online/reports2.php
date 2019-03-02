<?php
require_once 'header.php';
?>

<html lang="en">
<head>
	<?php
	echo HEAD;
	?>
    <style>
    .alert-warning1{
        color: #856404;
        background-color: #fff3cd;
        border-color: #ffeeba;
    }
    </style>
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
		<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4" style="flex: 0 0 87.333333%;max-width: 87.333333%;">
			<div class="pt-3 pb-2 mb-2 border-bottom">
				<h2>
					Reports
				</h2>
			</div>
			<div class="row">
                <div class='col-sm-5'>
                    <div class="row">
                        <div class='col-sm-6' style="cursor: pointer;" id="client_account_list" onclick="open_currentreport_box(this.id);">
        					<div class='alert rp_section alert-warning1 rp_active'>
        						<strong>Client Account List</strong>
        					</div>
        				</div>
                        <div class='col-sm-6' style="cursor: pointer;" id="trades_on_hold" onclick="open_currentreport_box(this.id);">
        					<div class='alert rp_section alert-info'>
        						<strong>Trades On Hold</strong>
        					</div>
        				</div>
                    </div>
                </div>
                <div class='col-sm-2'>
                    <div class="row">
        				<div class='col-sm-12' style="cursor: pointer;" id="ytd_earnings" onclick="open_currentreport_box(this.id);">
        					<div class='alert rp_section alert-info'>
        						<strong>Year to Date Earnings</strong>
        					</div>
        				</div>
                    </div>
                </div>
                <div class='col-sm-5'>
                    <div class="row">
                        <div class='col-sm-6' style="cursor: pointer;" id="current_licensing" onclick="open_currentreport_box(this.id);">
        					<div class='alert rp_section alert-info'>
        						<strong>Current Licensing</strong>
        					</div>
        				</div>
                        <div class='col-sm-6' style="cursor: pointer;" id="adjustments" onclick="open_currentreport_box(this.id);">
        					<div class='alert rp_section alert-info'>
        						<strong>Adjustments</strong>
        					</div>
        				</div>
                    </div>
                </div>
            </div>
            <script type="text/javascript">
            function open_currentreport_box(id){
                if(id == 'ytd_earnings')
                {
                    $("#trades_on_hold_section").css('display','none');
                    $("#ytd_earnings_section").css('display','block');
                    $("#current_licensing_section").css('display','none');
                    $("#client_account_list_section").css('display','none');
                    $("#adjustments_section").css('display','none');
                    
                    $("#ytd_earnings_reports_table").css('width','100%');
                    $("#ytd_earnings_section").css('width','100%');
                }
                else if(id=='adjustments')
                {
                    $("#adjustments_section").css('display','block');
                    $("#trades_on_hold_section").css('display','none');
                    $("#ytd_earnings_section").css('display','none');
                    $("#current_licensing_section").css('display','none');
                    $("#client_account_list_section").css('display','none');
                    
                    $("#adjustments_section").css('width','100%');
                    $("#adjustments_reports_table").css('width','100%');
                    
                }
                else if(id=='current_licensing')
                {
                    $("#trades_on_hold_section").css('display','none');
                    $("#ytd_earnings_section").css('display','none');
                    $("#current_licensing_section").css('display','block');
                    $("#client_account_list_section").css('display','none');
                    $("#adjustments_section").css('display','none');
                    
                    $("#current_licensing_section").css('width','100%');
                    $("#current_licensing_reports_table").css('width','100%');
                    
                }
                else if(id=='trades_on_hold')
                {
                    $("#trades_on_hold_section").css('display','block');
                    $("#ytd_earnings_section").css('display','none');
                    $("#current_licensing_section").css('display','none');
                    $("#client_account_list_section").css('display','none');
                    $("#adjustments_section").css('display','none');
                    
                    $("#trades_on_hold_section").css('width','100%');
                    $("#trade_reports_table").css('width','100%');
                    
                }
                else{
                    
                    $("#trades_on_hold_section").css('display','none');
                    $("#ytd_earnings_section").css('display','none');
                    $("#current_licensing_section").css('display','none');
                    $("#client_account_list_section").css('display','block');
                    $("#adjustments_section").css('display','none');
                    
                    $("#client_account_list_section").css('width','100%');
                    $("#client_account_list_table").css('width','100%');
                }
                
            }
            </script>
            <div id="client_account_list_section">
			<div class="row">
				<form id="client_account_list_form" class="col-md-12 dates_form client_account_list_date_form">
					<div class="server_response_div mt-2">
						<div class="alert" role="alert"></div>
					</div>
					<div class="custom-control custom-checkbox">
						<input type="checkbox" name="client_account_list_all_dates" class="custom-control-input"
						       id="client_account_list_all_dates_checkbox" checked>
						<label class="custom-control-label" for="client_account_list_all_dates_checkbox">All Clients</label>
					</div>
					<label>From</label>
					<input type="date" name="client_account_list_from_date" disabled required><br class="d-xs-block d-sm-none">
					<label>To</label>
					<input type="date" name="client_account_list_to_date" disabled required><br class="d-xs-block d-sm-none">
					<input class="btn btn-primary ml-sm-2" type="submit" value="Apply" >
					<input name="class" value="no_class" hidden>
					<input name="func" value="client_account_list" hidden>
				</form>
			</div>
			<div class="table-responsive mb-5" style="overflow: hidden;width: 100% !important;">
				<table id="client_account_list_table"
				       class="main-table table table-hover table-striped table-sm text-center"
				       style="font-size: 0.8rem;width: 100% !important;">
					<thead>
					<tr>
						<th>CLIENT NAME</th>
                        <!--<th>CLIENT ACCOUNT</th>-->
						<th>CLIENT#</th>
						<th>ADDRESS</th>
						<th>TELEPHONE</th>
						<th>OPEN DATE</th>
						<th>BIRTH DATE</th>
						<th>LAST TRADE</th>
				    </tr>
					</thead>
					<tbody>
					<?php
					try{
						$json_obj = client_account_list(['all_dates' => 'on'], false);
						echo $json_obj->data_arr['client_account_list_table'];
						$pdf_title_first_line  = $json_obj->data_arr['pdf_title_first_line'];
						$pdf_title_second_line = $json_obj->data_arr['pdf_title_second_line'];
						echo "<script>
							var trade_pdf_title_first_line = '$pdf_title_first_line';
							var trade_pdf_title_second_line = '$pdf_title_second_line';
						</script>";
					}catch(Exception $e){
						catch_doc_first_load_exception($e, 'reports_form');
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
						var file_name = 'Client Account List ' + currentDate.getDate() + ' ' + months_names[currentDate.getMonth()] + ' ' + currentDate.getFullYear();
						var pdf_title = trade_pdf_title_first_line + '\n\r' + trade_pdf_title_second_line;
						var excel_title = trade_pdf_title_first_line + ' - ' + trade_pdf_title_second_line;
						$( '#client_account_list_table' ).DataTable( {
							language: {search: ""},
							pageLength: 50,
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
						$( '#client_account_list_table_filter input' ).addClass( 'form-control' ).attr( "placeholder", "Search" ).css( 'margin', 0 );
						$( '#client_account_list_table_filter' ).width( 210 ).css( 'float', 'right' );
						$( '.dt-buttons' ).width( 200 ).css( 'float', 'left' );
						if( $( document ).width() < 992 ){
							$( '#client_account_list_table_filter' ).width( '100%' ).addClass( 'text-left mt-2' );
							$( '#client_account_list_table_filter input' ).width( '100%' );
						}
					} );
                    $('.rp_section').click(function() {
                        $('.rp_active').removeClass('alert-warning1')
                        $('.rp_active').addClass('alert-info');
                        $('.rp_active').removeClass('rp_active')
                        $(this).addClass('rp_active');
                        $(this).addClass('alert-warning1');
                    });
				</script>
			</div>
            </div>
            <div id="trades_on_hold_section" style="display: none;">
			<div class="row">
				<form id="trade_reports_form" class="col-md-12 dates_form trades_date_form">
					<div class="server_response_div mt-2">
						<div class="alert" role="alert"></div>
					</div>
					<div class="custom-control custom-checkbox">
						<input type="checkbox" name="trade_reports_all_dates" class="custom-control-input"
						       id="trade_reports_all_dates_checkbox" checked>
						<label class="custom-control-label" for="trade_reports_all_dates_checkbox">All Trade Dates</label>
					</div>
					<label>From</label>
					<input type="date" name="trade_reports_from_date" disabled required><br class="d-xs-block d-sm-none">
					<label>To</label>
					<input type="date" name="trade_reports_to_date" disabled required><br class="d-xs-block d-sm-none">
					<input class="btn btn-primary ml-sm-2" type="submit" value="Apply" >
					<input name="class" value="no_class" hidden>
					<input name="func" value="hold_trades_update" hidden>
				</form>
			</div>
			<div class="table-responsive mb-5" style="overflow: hidden;width: 100% !important;">
				<table id="trade_reports_table"
				       class="main-table table table-hover table-striped table-sm text-center"
				       style="font-size: 0.8rem;width: 100% !important;">
					<thead>
					<tr>
						<th>DATE</th>
						<th>DATE RECEIVED</th>
						<th>CLIENT ACCOUNT</th>
						<th>CLIENT NAME</th>
						<th class='text-center'>PRODUCT DESCRIPTION</th>
                        <th>CUSIP</th>
						<th>PRINCIPAL</th>
                        <!--<th>COMMISSION EXPECTED</th>-->
						<th>COMMISSION RECEIVED</th>
						<th>PAYOUT RATE</th>
						<th>COMMISSION PAID</th>
						<th>DATE PAID</th>
                        <th>HOLD REASON</th>
					</tr>
					</thead>
					<tbody>
					<?php
					try{
						$json_obj = hold_trades_update(['all_dates' => 'on'], false);
						echo $json_obj->data_arr['trade_reports_table'];
						$pdf_title_first_line  = $json_obj->data_arr['pdf_title_first_line'];
						$pdf_title_second_line = $json_obj->data_arr['pdf_title_second_line'];
						echo "<script>
							var trade_pdf_title_first_line = '$pdf_title_first_line';
							var trade_pdf_title_second_line = '$pdf_title_second_line';
						</script>";
					}catch(Exception $e){
						catch_doc_first_load_exception($e, 'reports_form');
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
						var file_name = 'Transaction On Hold ' + currentDate.getDate() + ' ' + months_names[currentDate.getMonth()] + ' ' + currentDate.getFullYear();
						var pdf_title = trade_pdf_title_first_line + '\n\r' + trade_pdf_title_second_line;
						var excel_title = trade_pdf_title_first_line + ' - ' + trade_pdf_title_second_line;
						$( '#trade_reports_table' ).DataTable( {
							language: {search: ""},
							pageLength: 50,
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
						$( '#trade_reports_table_filter input' ).addClass( 'form-control' ).attr( "placeholder", "Search" ).css( 'margin', 0 );
						$( '#trade_reports_table_filter' ).width( 210 ).css( 'float', 'right' );
						$( '.dt-buttons' ).width( 200 ).css( 'float', 'left' );
						if( $( document ).width() < 992 ){
							$( '#trade_reports_table_filter' ).width( '100%' ).addClass( 'text-left mt-2' );
							$( '#trade_reports_table_filter input' ).width( '100%' );
						}
					} );
                    $('.rp_section').click(function() {
                        /*$('.rp_active').removeClass('alert-warning1')
                        $('.rp_active').addClass('alert-info');
                        $('.rp_active').removeClass('rp_active')
                        $(this).addClass('rp_active');
                        $(this).addClass('alert-warning1');*/
                    });
				</script>
			</div>
            </div>
            <div id="ytd_earnings_section" style="display: none;">
			<div class="row">
				<form id="ytd_earnings_form" class="col-md-12 dates_form ytd_date_form">
					<div class="server_response_div mt-2">
						<div class="alert" role="alert"></div>
					</div>
					<div class="custom-control custom-checkbox">
						<input type="checkbox" name="ytd_earnings_all_dates" class="custom-control-input"
						       id="ytd_earnings_all_dates_checkbox" checked>
						<label class="custom-control-label" for="ytd_earnings_all_dates_checkbox">Current Year To Date</label>
					</div>
					<label>From</label>
					<input type="date" name="ytd_earnings_from_date" disabled required><br class="d-xs-block d-sm-none">
					<label>To</label>
					<input type="date" name="ytd_earnings_to_date" disabled required><br class="d-xs-block d-sm-none">
					<input class="btn btn-primary ml-sm-2" type="submit" value="Apply" >
					<input name="class" value="no_class" hidden>
					<input name="func" value="ytd_earnings" hidden>
				</form>
			</div>
            <div class="table-responsive mb-5" style="overflow: hidden;width: 100%;">
				<table id="ytd_earnings_reports_table"
				       class="main-table table table-hover table-striped table-sm text-center"
				       style="font-size: 0.8rem;width: 100% !important;">
					<thead>
					<tr>
						<th>PAYROLL DATE</th>
                        <th>CHECK NO</th>
						<th>GROSS AMOUNT</th>
						<th>CHECK AMOUNT</th>
					</tr>
					</thead>
					<tbody>
					<?php
					try{
						$json_obj = ytd_earnings(['all_dates' => 'on'], false);
						echo $json_obj->data_arr['ytd_earnings_reports_table'];
						$pdf_title_first_line  = $json_obj->data_arr['pdf_title_first_line'];
						$pdf_title_second_line = $json_obj->data_arr['pdf_title_second_line'];
						echo "<script>
							var ytd_pdf_title_first_line = '$pdf_title_first_line';
							var ytd_pdf_title_second_line = '$pdf_title_second_line';
						</script>";
					}catch(Exception $e){
						catch_doc_first_load_exception($e, 'ytd_earnings_form');
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
						var file_name = 'Year to Date Earnings ' + currentDate.getDate() + ' ' + months_names[currentDate.getMonth()] + ' ' + currentDate.getFullYear();
						var pdf_title = ytd_pdf_title_first_line + '\n\r' + ytd_pdf_title_second_line;
						var excel_title = ytd_pdf_title_first_line + ' - ' + ytd_pdf_title_second_line;
						$( '#ytd_earnings_reports_table' ).DataTable( {
							language: {search: ""},
							pageLength: 50,
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
						$( '#ytd_earnings_reports_table_filter input' ).addClass( 'form-control' ).attr( "placeholder", "Search" ).css( 'margin', 0 );
						$( '#ytd_earnings_reports_table_filter' ).width( 210 ).css( 'float', 'right' );
						$( '.dt-buttons' ).width( 200 ).css( 'float', 'left' );
						if( $( document ).width() < 992 ){
							$( '#ytd_earnings_reports_table_filter' ).width( '100%' ).addClass( 'text-left mt-2' );
							$( '#ytd_earnings_reports_table_filter input' ).width( '100%' );
						}
					} );
                    $('.rp_section').click(function() {
                        /*
                        $('.rp_active').removeClass('alert-warning')
                        $('.rp_active').addClass('alert-info');
                        $('.rp_active').removeClass('rp_active')
                        $(this).addClass('rp_active');
                        $(this).addClass('alert-warning');
                        */
                    });
				</script>
			</div>
            </div>
            <div id="adjustments_section" style="display: none;">
			<div class="row">
				<form id="adjustments_form" class="col-md-12 dates_form adjustments_date_form">
					<div class="server_response_div mt-2">
						<div class="alert" role="alert"></div>
					</div>
					<div class="custom-control custom-checkbox">
						<input type="checkbox" name="adjustments_all_dates" class="custom-control-input"
						       id="adjustments_all_dates_checkbox" checked>
						<label class="custom-control-label" for="adjustments_all_dates_checkbox">All dates</label>
					</div>
					<label>From</label>
					<input type="date" name="adjustments_from_date" disabled required><br class="d-xs-block d-sm-none">
					<label>To</label>
					<input type="date" name="adjustments_to_date" disabled required><br class="d-xs-block d-sm-none">
					<input class="btn btn-primary ml-sm-2" type="submit" value="Apply" >
					<input name="class" value="no_class" hidden>
					<input name="func" value="adjustments" hidden>
				</form>
			</div>
            <div class="table-responsive mb-5" style="overflow: hidden;width: 100%;">
				<table id="adjustments_reports_table"
				       class="main-table table table-hover table-striped table-sm text-center"
				       style="font-size: 0.8rem;width: 100% !important;">
					<thead>
					<tr>
						<th>ADJUSTMENT DATE</th>
                        <th>PAY ON/AFTER DATE</th>
						<th>G/L ACCOUNT</th>
						<th>CATEGORY</th>
                        <th>DESCRIPTION</th>
                        <th>AMOUNT</th>
					</tr>
					</thead>
					<tbody>
					<?php
					try{
						$json_obj = adjustments(['all_dates' => 'on'], false);
						echo $json_obj->data_arr['adjustments_reports_table'];
						$pdf_title_first_line  = $json_obj->data_arr['pdf_title_first_line'];
						$pdf_title_second_line = $json_obj->data_arr['pdf_title_second_line'];
						echo "<script>
							var adjustments_pdf_title_first_line = '$pdf_title_first_line';
							var adjustments_pdf_title_second_line = '$pdf_title_second_line';
						</script>";
					}catch(Exception $e){
						catch_doc_first_load_exception($e, 'adjustments_form');
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
						var file_name = 'Adjustments ' + currentDate.getDate() + ' ' + months_names[currentDate.getMonth()] + ' ' + currentDate.getFullYear();
						var pdf_title = adjustments_pdf_title_first_line + '\n\r' + adjustments_pdf_title_second_line;
						var excel_title = adjustments_pdf_title_first_line + ' - ' + adjustments_pdf_title_second_line;
						$( '#adjustments_reports_table' ).DataTable( {
							language: {search: ""},
							pageLength: 50,
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
						$( '#adjustments_reports_table_filter input' ).addClass( 'form-control' ).attr( "placeholder", "Search" ).css( 'margin', 0 );
						$( '#adjustments_reports_table_filter' ).width( 210 ).css( 'float', 'right' );
						$( '.dt-buttons' ).width( 200 ).css( 'float', 'left' );
						if( $( document ).width() < 992 ){
							$( '#adjustments_reports_table_filter' ).width( '100%' ).addClass( 'text-left mt-2' );
							$( '#adjustments_reports_table_filter input' ).width( '100%' );
						}
					} );
                    $('.rp_section').click(function() {
                        /*
                        $('.rp_active').removeClass('alert-warning')
                        $('.rp_active').addClass('alert-info');
                        $('.rp_active').removeClass('rp_active')
                        $(this).addClass('rp_active');
                        $(this).addClass('alert-warning');
                        */
                    });
				</script>
			</div>
            </div>
            <div class="table-responsive mb-5" style="display: none;overflow: hidden;width: 100%;" id="current_licensing_section">
				<table id="current_licensing_reports_table"
				       class="main-table table table-hover table-striped table-sm text-center"
				       style="font-size: 0.8rem;width: 100%;">
					<thead>
					<tr>
						<?php
					    try{
    						$json_obj = current_licensing_header();
    						echo $json_obj->data_arr['current_licensing_header'];
                        }catch(Exception $e){
    						catch_doc_first_load_exception($e, 'reports_form');
    					}?>
					</tr>
					</thead>
					<tbody>
                    <?php
					try{
						$json_obj = current_licensing(['all_dates' => 'on'], false);
						echo $json_obj->data_arr['current_licensing_reports_table'];
						$pdf_title_first_line  = $json_obj->data_arr['pdf_title_first_line'];
						echo "<script>
							var lic_pdf_title_first_line = '$pdf_title_first_line';
						</script>";
					}catch(Exception $e){
						catch_doc_first_load_exception($e, 'reports_form');
					}
					?>
					</tbody>
				</table>
				<script type="text/javascript">
					$( document ).ready( function(){
					    var myGlyph_right = new Image();
                        myGlyph_right.src = 'check-mark-3-black.png';
                        var myGlyph_wrong = new Image();
                        myGlyph_wrong.src = 'wrong_checkmark1.png';
						var currentDate = new Date();
						var current_minutes = ('0'+ currentDate.getMinutes()).slice(-2);
						var top_massage = 'Created: ' + (currentDate.getMonth()+1) + '/' + currentDate.getDate() + '/' + currentDate.getFullYear() + ' ' + currentDate.getHours() + ':' + current_minutes;
						const months_names = ["January", "February", "March", "April", "May", "June",
							"July", "August", "September", "October", "November", "December"
						];
						var file_name = 'Current Licencing ' + currentDate.getDate() + ' ' + months_names[currentDate.getMonth()] + ' ' + currentDate.getFullYear();
						var pdf_title = lic_pdf_title_first_line;
						var excel_title = lic_pdf_title_first_line;
						$( '#current_licensing_reports_table' ).DataTable( {
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
									title: excel_title,
                                    customize: function(doc) {
                                        var sheet = doc.xl.worksheets['sheet1.xml'];
                                        //ensure doc.images exists
                                        
                                        //..add more images[xyz]=anotherDataUrl here
                                        // Loop over the cells in column `C`
                                        $('row c[r]', sheet).each( function () {//console.log($('row', sheet));
                                            // Get the value
                                            if ( $('is t', this).text() == '<img src="check-mark-3-black.png" height="15px;" width="15px;">' ) {
                                                $('is t', this).html('&#x2714;');
                                            }
                                            else if( $('is t', this).text() == '<img src="wrong_checkmark1.png" height="15px;" width="15px;">' ) {
                                                $('is t', this).html('&#x274C;');
                                            }
                                            else
                                            {
                                                
                                            }
                                        });
                                        
                                    },
                                    exportOptions : {
                                        stripHtml: false
                                    }
								},
								{
									extend: 'pdfHtml5',
									orientation: 'landscape',
									filename: file_name,
									messageTop: top_massage,
									title: pdf_title,
                                    customize: function(doc) {
                                        //ensure doc.images exists
                                        doc.images = doc.images || {};
                                        //build dictionary
                                        doc.images['myGlyph_right'] = getBase64Image(myGlyph_right);
                                        doc.images['myGlyph_wrong'] = getBase64Image(myGlyph_wrong);
                                        
                                        //..add more images[xyz]=anotherDataUrl here
                                        //console.log(doc);
                                        //when the content is <img src="myGlyph_right.png">
                                        //remove the text node and insert an image node
                                        for (var i=1;i<doc.content[2].table.body.length;i++) {
                                            
                                            for(var z=1;z<=7;z++)
                                            {
                                                if (doc.content[2].table.body[i][z].text == '<img src="check-mark-3-black.png" height="15px;" width="15px;">') {
                                                    delete doc.content[2].table.body[i][z].text;
                                                    doc.content[2].table.body[i][z].image = 'myGlyph_right';
                                                }else
                                                {
                                                    //delete doc.content[2].table.body[i][z].text;
                                                    //doc.content[2].table.body[i][z].image = 'myGlyph_wrong';
                                                }
                                            }
                                        }
                                    },
                                    exportOptions : {
                                        stripHtml: false
                                    }
								}
							],
							"order": [[ 0, "asc" ]],
							"scrollX": true
						} );

						$( '.buttons-html5' ).addClass( 'btn btn-secondary' );
						$( '#current_licensing_reports_table_filter input' ).addClass( 'form-control' ).attr( "placeholder", "Search" ).css( 'margin', 0 );
						$( '#current_licensing_reports_table_filter' ).width( 210 ).css( 'float', 'right' );
						$( '.dt-buttons' ).width( 200 ).css( 'float', 'left' );
						if( $( document ).width() < 992 ){
							$( '#current_licensing_reports_table_filter' ).width( '100%' ).addClass( 'text-left mt-2' );
							$( '#current_licensing_reports_table_filter input' ).width( '100%' );
						}
					} );
                    $('.rp_section').click(function() {
                        /*$('.rp_active').removeClass('alert-warning')
                        $('.rp_active').addClass('alert-info');
                        $('.rp_active').removeClass('rp_active')
                        $(this).addClass('rp_active');
                        $(this).addClass('alert-warning');
                        */
                    });
                    function getBase64Image(img) {
                        var width = 10;
                        var height = 10;
                        var canvas = document.createElement("canvas");
                        canvas.width = width;
                        canvas.height = height;
                        var ctx = canvas.getContext("2d");
                        ctx.drawImage(img, 0, 0, width, height);
                        return canvas.toDataURL("image/png");
                    }
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
<style>
.paginate_button {
    cursor: pointer;
    margin: 2px;
}
.alert-info:focus {
  background: pink;
}
.dataTables_scrollHeadInner{
    width: 100% !important;
}
table{
    width: 100% !important;
}
.check { 
font-family: "Arial Black", Helvetica, sans-serif; 
} 
</style>

