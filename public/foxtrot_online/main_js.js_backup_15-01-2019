$( document ).ready( function(){

	/**
	 * Hide loader div on page load
	 */
	$( ".loader" ).hide();

	/**
	 * Toggle sidebar
	 */
	$( '.navbar-toggler' ).click( function(){
		var is_shown = $( '.sidebar' ).attr( 'style' );
		if( is_shown == undefined ){
			$( '.sidebar' ).attr( 'style', 'display:block !important' );
		}else{
			$( '.sidebar' ).removeAttr( 'style' );
		}
	} );


	/**
	 Disable/Enable the dates input fields according to the checkbox 'checked' state.
	 */
	$( '#all_dates_checkbox' ).click( function(){
		var is_checked = $( '#all_dates_checkbox' )["0"].checked;  //if true - means it became checked after clicking,
		if( is_checked == true ){
			$( '.dates_form input[type=date]' ).prop( "disabled", true );
			$.post( 'junction.php', $( '#activity_form' ).serialize(), function( data ){
				var json_obj = $.parseJSON( data );
				if( json_obj.status == true ){
					$( "#activity_table" ).DataTable().destroy();
					$( "#activity_table tbody" ).html( json_obj.data_arr['activity_table'] );

					var currentDate = new Date();
					var top_massage = 'Created: ' + (currentDate.getMonth()+1) + '/' + currentDate.getDate() + '/' + currentDate.getFullYear() + ' ' + currentDate.getHours() + ':' + currentDate.getMinutes();
					const months_names = ["January", "February", "March", "April", "May", "June",
						"July", "August", "September", "October", "November", "December"
					];
					var file_name = 'Transaction Activity ' + currentDate.getDate() + ' ' + months_names[currentDate.getMonth()] + ' ' + currentDate.getFullYear();
					var pdf_title = json_obj.data_arr.pdf_title_first_line + '\n\r' + json_obj.data_arr.pdf_title_second_line;
					var excel_title = json_obj.data_arr.pdf_title_first_line + ' - ' + json_obj.data_arr.pdf_title_second_line;
					$( '#activity_table' ).DataTable( {
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
					$( '#activity_table_filter input' ).addClass( 'form-control' ).attr( "placeholder", "Search" ).css( 'margin', 0 );
					$( '#activity_table_filter' ).width( 210 ).css( 'float', 'right' );
					$( '.dt-buttons' ).width( 200 ).css( 'float', 'left' );
					if( $( document ).width() < 992 ){
						$( '#activity_table_filter' ).width( '100%' ).addClass( 'text-left mt-2' );
						$( '#activity_table_filter input' ).width( '100%' );
					}
                    
                    $( "#brokerage_commissions_table" ).DataTable().destroy();
					$( "#brokerage_commissions_table tbody" ).html( json_obj.data_arr['brokerage_commissions_table'] );

					var currentDate = new Date();
					var top_massage = 'Created: ' + (currentDate.getMonth()+1) + '/' + currentDate.getDate() + '/' + currentDate.getFullYear() + ' ' + currentDate.getHours() + ':' + currentDate.getMinutes();
					
					var file_name = 'Transaction Activity ' + currentDate.getDate() + ' ' + months_names[currentDate.getMonth()] + ' ' + currentDate.getFullYear();
					var pdf_title = json_obj.data_arr.pdf_title_first_line + '\n\r' + json_obj.data_arr.pdf_title_second_line;
					var excel_title = json_obj.data_arr.pdf_title_first_line + ' - ' + json_obj.data_arr.pdf_title_second_line;
					$( '#brokerage_commissions_table' ).DataTable( {
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
					$( '#brokerage_commissions_table_filter input' ).addClass( 'form-control' ).attr( "placeholder", "Search" ).css( 'margin', 0 );
					$( '#brokerage_commissions_table_filter' ).width( 210 ).css( 'float', 'right' );
					$( '.dt-buttons' ).width( 200 ).css( 'float', 'left' );
					if( $( document ).width() < 992 ){
						$( '#brokerage_commissions_table_filter' ).width( '100%' ).addClass( 'text-left mt-2' );
						$( '#brokerage_commissions_table_filter input' ).width( '100%' );
					}
                    
                    $( "#trail_commissions_table" ).DataTable().destroy();
					$( "#trail_commissions_table tbody" ).html( json_obj.data_arr['trail_commissions_table'] );

					var currentDate = new Date();
					var top_massage = 'Created: ' + (currentDate.getMonth()+1) + '/' + currentDate.getDate() + '/' + currentDate.getFullYear() + ' ' + currentDate.getHours() + ':' + currentDate.getMinutes();
					
					var file_name = 'Transaction Activity ' + currentDate.getDate() + ' ' + months_names[currentDate.getMonth()] + ' ' + currentDate.getFullYear();
					var pdf_title = json_obj.data_arr.pdf_title_first_line + '\n\r' + json_obj.data_arr.pdf_title_second_line;
					var excel_title = json_obj.data_arr.pdf_title_first_line + ' - ' + json_obj.data_arr.pdf_title_second_line;
					$( '#trail_commissions_table' ).DataTable( {
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
					$( '#trail_commissions_table_filter input' ).addClass( 'form-control' ).attr( "placeholder", "Search" ).css( 'margin', 0 );
					$( '#trail_commissions_table_filter' ).width( 210 ).css( 'float', 'right' );
					$( '.dt-buttons' ).width( 200 ).css( 'float', 'left' );
					if( $( document ).width() < 992 ){
						$( '#trail_commissions_table_filter' ).width( '100%' ).addClass( 'text-left mt-2' );
						$( '#trail_commissions_table_filter input' ).width( '100%' );
					}
                    
                    $( "#clearing_commissions_table" ).DataTable().destroy();
					$( "#clearing_commissions_table tbody" ).html( json_obj.data_arr['clearing_commissions_table'] );

					var currentDate = new Date();
					var top_massage = 'Created: ' + (currentDate.getMonth()+1) + '/' + currentDate.getDate() + '/' + currentDate.getFullYear() + ' ' + currentDate.getHours() + ':' + currentDate.getMinutes();
					
					var file_name = 'Transaction Activity ' + currentDate.getDate() + ' ' + months_names[currentDate.getMonth()] + ' ' + currentDate.getFullYear();
					var pdf_title = json_obj.data_arr.pdf_title_first_line + '\n\r' + json_obj.data_arr.pdf_title_second_line;
					var excel_title = json_obj.data_arr.pdf_title_first_line + ' - ' + json_obj.data_arr.pdf_title_second_line;
					$( '#clearing_commissions_table' ).DataTable( {
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
					$( '#clearing_commissions_table_filter input' ).addClass( 'form-control' ).attr( "placeholder", "Search" ).css( 'margin', 0 );
					$( '#clearing_commissions_table_filter' ).width( 210 ).css( 'float', 'right' );
					$( '.dt-buttons' ).width( 200 ).css( 'float', 'left' );
					if( $( document ).width() < 992 ){
						$( '#clearing_commissions_table_filter' ).width( '100%' ).addClass( 'text-left mt-2' );
						$( '#clearing_commissions_table_filter input' ).width( '100%' );
					}
                    
                    $( "#advisory_table" ).DataTable().destroy();
					$( "#advisory_table tbody" ).html( json_obj.data_arr['advisory_table'] );

					var currentDate = new Date();
					var top_massage = 'Created: ' + (currentDate.getMonth()+1) + '/' + currentDate.getDate() + '/' + currentDate.getFullYear() + ' ' + currentDate.getHours() + ':' + currentDate.getMinutes();
					
					var file_name = 'Transaction Activity ' + currentDate.getDate() + ' ' + months_names[currentDate.getMonth()] + ' ' + currentDate.getFullYear();
					var pdf_title = json_obj.data_arr.pdf_title_first_line + '\n\r' + json_obj.data_arr.pdf_title_second_line;
					var excel_title = json_obj.data_arr.pdf_title_first_line + ' - ' + json_obj.data_arr.pdf_title_second_line;
					$( '#advisory_table' ).DataTable( {
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
					$( '#advisory_table_filter input' ).addClass( 'form-control' ).attr( "placeholder", "Search" ).css( 'margin', 0 );
					$( '#advisory_table_filter' ).width( 210 ).css( 'float', 'right' );
					$( '.dt-buttons' ).width( 200 ).css( 'float', 'left' );
					if( $( document ).width() < 992 ){
						$( '#advisory_table_filter' ).width( '100%' ).addClass( 'text-left mt-2' );
						$( '#advisory_table_filter input' ).width( '100%' );
					}


					// $( '#activity_table_filter label' ).after( '<small id="search_note" class="form-text text-muted" style="margin-top: -0.5em">Enter EXACTLY what you\'re looking for</small>' );

					$( "#activity_boxes_container_div" ).html( json_obj.data_arr['activity_boxes'] );
					$( ".server_response_div .alert" ).removeClass( 'alert-warning alert-danger' ).addClass( 'alert-success' ).text( 'Table generated successfully.' ).show();
				}else{ //If there is an error
					$( ".server_response_div .alert" ).text( json_obj.error_message ).show();
					if( json_obj.error_level == 0 ){
						$( ".server_response_div .alert" ).removeClass( 'alert-success alert-danger' ).addClass( 'alert-warning' );
					}else{
						$( ".server_response_div .alert" ).removeClass( 'alert-success alert-warning' ).addClass( 'alert-danger' );
					}
				}
				window.setTimeout(function() {
					$(".alert-success, .alert-danger, .alert-warning").slideUp();
				}, 4000);
			} );
		}else{
			$( '.dates_form input[type=date]' ).prop( "disabled", false );
		}
	} );

    //Trade reports form filter
    $( '#trade_reports_all_dates_checkbox' ).click( function(){
		var is_checked = $( '#trade_reports_all_dates_checkbox' )["0"].checked;  //if true - means it became checked after clicking,
		if( is_checked == true ){
		  //console.log('hiii');
			$( '.trades_date_form input[type=date]' ).prop( "disabled", true );
			$.post( 'junction.php', $( '#trade_reports_form' ).serialize(), function( data ){
				var json_obj = $.parseJSON( data );
				if( json_obj.status == true ){
					$( "#trade_reports_table" ).DataTable().destroy();
					$( "#trade_reports_table tbody" ).html( json_obj.data_arr['trade_reports_table'] );

					var currentDate = new Date();
					var top_massage = 'Created: ' + (currentDate.getMonth()+1) + '/' + currentDate.getDate() + '/' + currentDate.getFullYear() + ' ' + currentDate.getHours() + ':' + currentDate.getMinutes();
					const months_names = ["January", "February", "March", "April", "May", "June",
						"July", "August", "September", "October", "November", "December"
					];
					var file_name = 'Transaction On Hold ' + currentDate.getDate() + ' ' + months_names[currentDate.getMonth()] + ' ' + currentDate.getFullYear();
					var pdf_title = json_obj.data_arr.pdf_title_first_line + '\n\r' + json_obj.data_arr.pdf_title_second_line;
					var excel_title = json_obj.data_arr.pdf_title_first_line + ' - ' + json_obj.data_arr.pdf_title_second_line;
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

					// $( '#activity_table_filter label' ).after( '<small id="search_note" class="form-text text-muted" style="margin-top: -0.5em">Enter EXACTLY what you\'re looking for</small>' );

					//$( "#trade_reports_boxes_container_div" ).html( json_obj.data_arr['trade_reports_boxes'] );
					$( ".server_response_div .alert" ).removeClass( 'alert-warning alert-danger' ).addClass( 'alert-success' ).text( 'Table generated successfully.' ).show();
				}else{ //If there is an error
					$( ".server_response_div .alert" ).text( json_obj.error_message ).show();
					if( json_obj.error_level == 0 ){
						$( ".server_response_div .alert" ).removeClass( 'alert-success alert-danger' ).addClass( 'alert-warning' );
					}else{
						$( ".server_response_div .alert" ).removeClass( 'alert-success alert-warning' ).addClass( 'alert-danger' );
					}
				}
				window.setTimeout(function() {
					$(".alert-success, .alert-danger, .alert-warning").slideUp();
				}, 4000);
			} );
		}else{
			$( '.trades_date_form input[type=date]' ).prop( "disabled", false );
            //console.log($( '.dates_form input[type=date]' ));
		}
	} );
    
    //YTD reports form filter
    $( '#ytd_earnings_all_dates_checkbox' ).click( function(){
		var is_checked = $( '#ytd_earnings_all_dates_checkbox' )["0"].checked;  //if true - means it became checked after clicking,
		if( is_checked == true ){
		  
			$( '.ytd_date_form input[type=date]' ).prop( "disabled", true );
			$.post( 'junction.php', $( '#ytd_earnings_form' ).serialize(), function( data ){
				var json_obj = $.parseJSON( data );
				if( json_obj.status == true ){
					$( "#ytd_earnings_reports_table" ).DataTable().destroy();
					$( "#ytd_earnings_reports_table tbody" ).html( json_obj.data_arr['ytd_earnings_reports_table'] );

					var currentDate = new Date();
					var top_massage = 'Created: ' + (currentDate.getMonth()+1) + '/' + currentDate.getDate() + '/' + currentDate.getFullYear() + ' ' + currentDate.getHours() + ':' + currentDate.getMinutes();
					const months_names = ["January", "February", "March", "April", "May", "June",
						"July", "August", "September", "October", "November", "December"
					];
					var file_name = 'Year to Date Earnings ' + currentDate.getDate() + ' ' + months_names[currentDate.getMonth()] + ' ' + currentDate.getFullYear();
					var pdf_title = json_obj.data_arr.pdf_title_first_line + '\n\r' + json_obj.data_arr.pdf_title_second_line;
					var excel_title = json_obj.data_arr.pdf_title_first_line + ' - ' + json_obj.data_arr.pdf_title_second_line;
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
 
					// $( '#activity_table_filter label' ).after( '<small id="search_note" class="form-text text-muted" style="margin-top: -0.5em">Enter EXACTLY what you\'re looking for</small>' );

					//$( "#trade_reports_boxes_container_div" ).html( json_obj.data_arr['trade_reports_boxes'] );
					$( ".server_response_div .alert" ).removeClass( 'alert-warning alert-danger' ).addClass( 'alert-success' ).text( 'Table generated successfully.' ).show();
				}else{ //If there is an error
					$( ".server_response_div .alert" ).text( json_obj.error_message ).show();
					if( json_obj.error_level == 0 ){
						$( ".server_response_div .alert" ).removeClass( 'alert-success alert-danger' ).addClass( 'alert-warning' );
					}else{
						$( ".server_response_div .alert" ).removeClass( 'alert-success alert-warning' ).addClass( 'alert-danger' );
					}
				}
				window.setTimeout(function() {
					$(".alert-success, .alert-danger, .alert-warning").slideUp();
				}, 4000);
			} );
		}else{
			$( '.ytd_date_form input[type=date]' ).prop( "disabled", false );
            //console.log($( '.dates_form input[type=date]' ));
		}
	} );
    
    //Client account list reports form filter
    $( '#client_account_list_all_dates_checkbox' ).click( function(){
		var is_checked = $( '#client_account_list_all_dates_checkbox' )["0"].checked;  //if true - means it became checked after clicking,
		if( is_checked == true ){
		  
			$( '.client_account_list_date_form input[type=date]' ).prop( "disabled", true );
			$.post( 'junction.php', $( '#client_account_list_form' ).serialize(), function( data ){
				var json_obj = $.parseJSON( data );
				if( json_obj.status == true ){
					$( "#client_account_list_table" ).DataTable().destroy();
					$( "#client_account_list_table tbody" ).html( json_obj.data_arr['client_account_list_table'] );

					var currentDate = new Date();
					var top_massage = 'Created: ' + (currentDate.getMonth()+1) + '/' + currentDate.getDate() + '/' + currentDate.getFullYear() + ' ' + currentDate.getHours() + ':' + currentDate.getMinutes();
					const months_names = ["January", "February", "March", "April", "May", "June",
						"July", "August", "September", "October", "November", "December"
					];
					var file_name = 'Client Account List ' + currentDate.getDate() + ' ' + months_names[currentDate.getMonth()] + ' ' + currentDate.getFullYear();
					var pdf_title = json_obj.data_arr.pdf_title_first_line + '\n\r' + json_obj.data_arr.pdf_title_second_line;
					var excel_title = json_obj.data_arr.pdf_title_first_line + ' - ' + json_obj.data_arr.pdf_title_second_line;
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
 
					// $( '#activity_table_filter label' ).after( '<small id="search_note" class="form-text text-muted" style="margin-top: -0.5em">Enter EXACTLY what you\'re looking for</small>' );

					//$( "#trade_reports_boxes_container_div" ).html( json_obj.data_arr['trade_reports_boxes'] );
					$( ".server_response_div .alert" ).removeClass( 'alert-warning alert-danger' ).addClass( 'alert-success' ).text( 'Table generated successfully.' ).show();
				}else{ //If there is an error
					$( ".server_response_div .alert" ).text( json_obj.error_message ).show();
					if( json_obj.error_level == 0 ){
						$( ".server_response_div .alert" ).removeClass( 'alert-success alert-danger' ).addClass( 'alert-warning' );
					}else{
						$( ".server_response_div .alert" ).removeClass( 'alert-success alert-warning' ).addClass( 'alert-danger' );
					}
				}
				window.setTimeout(function() {
					$(".alert-success, .alert-danger, .alert-warning").slideUp();
				}, 4000);
			} );
		}else{//console.log('hii');
			$( '.client_account_list_date_form input[type=date]' ).prop( "disabled", false );
            //console.log($( '.dates_form input[type=date]' ));
		}
	} );



	/**
	 Changes data attribute of html pdf embed (object) after choosing a pdf to view
	 And the buttons Download and Open.
	 */
	$( '#statements_select' ).change( function(){
		var value_of_selected_option = $( this ).find( "option:selected" ).attr( "value" );
		if( value_of_selected_option != "none" ){
			var company_name = window.company_name;
			$( '#statement_pdf_object' ).attr( 'data', company_name + '/data/' + value_of_selected_option + '#view=Fit' );
			$( '.statement_toolbar' ).attr( 'href', company_name + '/data/' + value_of_selected_option );
		}
	} );

	/**
	 Log in form submit
	 */
	$( "#log_in_form" ).submit( function( event ){
		event.preventDefault(); //Prevent the form from submitting normally
		$.post( 'junction.php', $( '#log_in_form' ).serialize(), function( data ){
			var json_obj = $.parseJSON( data );//console.log(json_obj);
			if( json_obj.status == true ){
				window.location.replace( "dashboard.php" );
			}else{ //If there is an error
				$( "#log_in_form .server_response_div .alert" ).text( json_obj.error_message ).show();
				if( json_obj.error_level == 0 ){
					$( "#log_in_form .server_response_div .alert" ).removeClass( 'alert-danger' ).addClass( 'alert-warning' );
				}else{
					$( "#log_in_form .server_response_div .alert" ).removeClass( 'alert-warning' ).addClass( 'alert-danger' );
				}
			}
		} );
	} );


	/**
	 Autofocus on Select input when forgot_password modal is opened.
	 */
	$( '#forgot_password_modal' ).on( 'shown.bs.modal', function(){
		$( '#forgot_password_modal select' ).trigger( 'focus' )
	} );


	/**
	 Forgot password form submit
	 */
	$( "#forgot_password_form" ).submit( function( event ){
		event.preventDefault(); //Prevent the form from submitting normally
		$.post( 'junction.php', $( '#forgot_password_form' ).serialize(), function( data ){
			var json_obj = $.parseJSON( data );
			if( json_obj.status == true ){
				$( "#forgot_password_form .server_response_div .alert" ).removeClass( 'alert-warning alert-danger' ).addClass( 'alert-success' ).text( 'Password and username sent to your E-mail. Check your inbox for mails from FoxTrot Online.' ).show();
			}else{ //If there is an error
				$( "#forgot_password_form .server_response_div .alert" ).text( json_obj.error_message ).show();
				if( json_obj.error_level == 0 ){
					$( "#forgot_password_form .server_response_div .alert" ).removeClass( 'alert-success alert-danger' ).addClass( 'alert-warning' );
				}else{
					$( "#forgot_password_form .server_response_div .alert" ).removeClass( 'alert-success alert-warning' ).addClass( 'alert-danger' );
				}
			}
		} );
	} );


	/**
	 Activity form submit
	 */
	$( "#activity_form" ).submit( function( event ){
		event.preventDefault(); //Prevent the form from submitting normally
		$.post( 'junction.php', $( '#activity_form' ).serialize(), function( data ){
			var json_obj = $.parseJSON( data );
			if( json_obj.status == true ){
			 
				$( "#activity_table" ).DataTable().destroy();
				$( "#activity_table tbody" ).html( json_obj.data_arr['activity_table'] );

				var currentDate = new Date();
				var top_massage = 'Created: ' + (currentDate.getMonth()+1) + '/' + currentDate.getDate() + '/' + currentDate.getFullYear() + ' ' + currentDate.getHours() + ':' + currentDate.getMinutes();
				const months_names = ["January", "February", "March", "April", "May", "June",
					"July", "August", "September", "October", "November", "December"
				];
				var file_name = 'Transaction Activity ' + currentDate.getDate() + ' ' + months_names[currentDate.getMonth()] + ' ' + currentDate.getFullYear();
				var pdf_title = json_obj.data_arr.pdf_title_first_line + '\n\r' + json_obj.data_arr.pdf_title_second_line;
				var excel_title = json_obj.data_arr.pdf_title_first_line + ' - ' + json_obj.data_arr.pdf_title_second_line;
				$( '#activity_table' ).DataTable( {
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
				$( '#activity_table_filter input' ).addClass( 'form-control' ).attr( "placeholder", "Search" ).css( 'margin', 0 );
				$( '#activity_table_filter' ).width( 210 ).css( 'float', 'right' );
				$( '.dt-buttons' ).width( 200 ).css( 'float', 'left' );
				if( $( document ).width() < 992 ){
					$( '#activity_table_filter' ).width( '100%' ).addClass( 'text-left mt-2' );
					$( '#activity_table_filter input' ).width( '100%' );
				}
                
                $( "#brokerage_commissions_table" ).DataTable().destroy();
				$( "#brokerage_commissions_table tbody" ).html( json_obj.data_arr['brokerage_commissions_table'] );

				$( '#brokerage_commissions_table' ).DataTable( {
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
				$( '#brokerage_commissions_table_filter input' ).addClass( 'form-control' ).attr( "placeholder", "Search" ).css( 'margin', 0 );
				$( '#brokerage_commissions_table_filter' ).width( 210 ).css( 'float', 'right' );
				$( '.dt-buttons' ).width( 200 ).css( 'float', 'left' );
				if( $( document ).width() < 992 ){
					$( '#brokerage_commissions_table_filter' ).width( '100%' ).addClass( 'text-left mt-2' );
					$( '#brokerage_commissions_table_filter input' ).width( '100%' );
				}
                
                $( "#trail_commissions_table" ).DataTable().destroy();
				$( "#trail_commissions_table tbody" ).html( json_obj.data_arr['trail_commissions_table'] );

				$( '#trail_commissions_table' ).DataTable( {
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
				$( '#trail_commissions_table_filter input' ).addClass( 'form-control' ).attr( "placeholder", "Search" ).css( 'margin', 0 );
				$( '#trail_commissions_table_filter' ).width( 210 ).css( 'float', 'right' );
				$( '.dt-buttons' ).width( 200 ).css( 'float', 'left' );
				if( $( document ).width() < 992 ){
					$( '#trail_commissions_table_filter' ).width( '100%' ).addClass( 'text-left mt-2' );
					$( '#trail_commissions_table_filter input' ).width( '100%' );
				}

                $( "#clearing_commissions_table" ).DataTable().destroy();
				$( "#clearing_commissions_table tbody" ).html( json_obj.data_arr['clearing_commissions_table'] );

				$( '#clearing_commissions_table' ).DataTable( {
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
				$( '#clearing_commissions_table_filter input' ).addClass( 'form-control' ).attr( "placeholder", "Search" ).css( 'margin', 0 );
				$( '#clearing_commissions_table_filter' ).width( 210 ).css( 'float', 'right' );
				$( '.dt-buttons' ).width( 200 ).css( 'float', 'left' );
				if( $( document ).width() < 992 ){
					$( '#clearing_commissions_table_filter' ).width( '100%' ).addClass( 'text-left mt-2' );
					$( '#clearing_commissions_table_filter input' ).width( '100%' );
				}
                
                $( "#advisory_table" ).DataTable().destroy();
				$( "#advisory_table tbody" ).html( json_obj.data_arr['advisory_table'] );

				$( '#advisory_table' ).DataTable( {
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
				$( '#advisory_table_filter input' ).addClass( 'form-control' ).attr( "placeholder", "Search" ).css( 'margin', 0 );
				$( '#advisory_table_filter' ).width( 210 ).css( 'float', 'right' );
				$( '.dt-buttons' ).width( 200 ).css( 'float', 'left' );
				if( $( document ).width() < 992 ){
					$( '#advisory_table_filter' ).width( '100%' ).addClass( 'text-left mt-2' );
					$( '#advisory_table_filter input' ).width( '100%' );
				}
				// $( '#activity_table_filter label' ).after( '<small id="search_note" class="form-text text-muted" style="margin-top: -0.5em">Enter EXACTLY what you\'re looking for</small>' );


				$( "#activity_boxes_container_div" ).html( json_obj.data_arr['activity_boxes'] );
				$( ".server_response_div .alert" ).removeClass( 'alert-warning alert-danger' ).addClass( 'alert-success' ).text( 'Table generated successfully.' ).show();
			}else{ //If there is an error
				$( ".server_response_div .alert" ).text( json_obj.error_message ).show();
				if( json_obj.error_level == 0 ){
					$( ".server_response_div .alert" ).removeClass( 'alert-success alert-danger' ).addClass( 'alert-warning' );
				}else{
					$( ".server_response_div .alert" ).removeClass( 'alert-success alert-warning' ).addClass( 'alert-danger' );
				}
			}
            refresh_table();
			window.setTimeout(function() {
				$(".alert-success, .alert-danger, .alert-warning").slideUp();
			}, 4000);
		} );
	} );

    /**
	 Trades Report form submit
	 */
	$( "#trade_reports_form" ).submit( function( event ){
		event.preventDefault(); //Prevent the form from submitting normally
		$.post( 'junction.php', $( '#trade_reports_form' ).serialize(), function( data ){
			var json_obj = $.parseJSON( data );
			if( json_obj.status == true ){
				$( "#trade_reports_table" ).DataTable().destroy();
				$( "#trade_reports_table tbody" ).html( json_obj.data_arr['trade_reports_table'] );

				var currentDate = new Date();
				var top_massage = 'Created: ' + (currentDate.getMonth()+1) + '/' + currentDate.getDate() + '/' + currentDate.getFullYear() + ' ' + currentDate.getHours() + ':' + currentDate.getMinutes();
				const months_names = ["January", "February", "March", "April", "May", "June",
					"July", "August", "September", "October", "November", "December"
				];
				var file_name = 'Transaction On Hold ' + currentDate.getDate() + ' ' + months_names[currentDate.getMonth()] + ' ' + currentDate.getFullYear();
				var pdf_title = json_obj.data_arr.pdf_title_first_line + '\n\r' + json_obj.data_arr.pdf_title_second_line;
				var excel_title = json_obj.data_arr.pdf_title_first_line + ' - ' + json_obj.data_arr.pdf_title_second_line;
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

				// $( '#activity_table_filter label' ).after( '<small id="search_note" class="form-text text-muted" style="margin-top: -0.5em">Enter EXACTLY what you\'re looking for</small>' );


				$( "#trade_reports_boxes_container_div" ).html( json_obj.data_arr['trade_reports_boxes'] );
				$( ".server_response_div .alert" ).removeClass( 'alert-warning alert-danger' ).addClass( 'alert-success' ).text( 'Table generated successfully.' ).show();
			}else{ //If there is an error
				$( ".server_response_div .alert" ).text( json_obj.error_message ).show();
				if( json_obj.error_level == 0 ){
					$( ".server_response_div .alert" ).removeClass( 'alert-success alert-danger' ).addClass( 'alert-warning' );
				}else{
					$( ".server_response_div .alert" ).removeClass( 'alert-success alert-warning' ).addClass( 'alert-danger' );
				}
			}
			window.setTimeout(function() {
				$(".alert-success, .alert-danger, .alert-warning").slideUp();
			}, 4000);
		} );
	} );
    $( "#ytd_earnings_form" ).submit( function( event ){
		event.preventDefault(); //Prevent the form from submitting normally
		$.post( 'junction.php', $( '#ytd_earnings_form' ).serialize(), function( data ){
			var json_obj = $.parseJSON( data );
			if( json_obj.status == true ){
				$( "#ytd_earnings_table" ).DataTable().destroy();
				$( "#ytd_earnings_table tbody" ).html( json_obj.data_arr['ytd_earnings_table'] );

				var currentDate = new Date();
				var top_massage = 'Created: ' + (currentDate.getMonth()+1) + '/' + currentDate.getDate() + '/' + currentDate.getFullYear() + ' ' + currentDate.getHours() + ':' + currentDate.getMinutes();
				const months_names = ["January", "February", "March", "April", "May", "June",
					"July", "August", "September", "October", "November", "December"
				];
				var file_name = 'Year to Date Earnings' + currentDate.getDate() + ' ' + months_names[currentDate.getMonth()] + ' ' + currentDate.getFullYear();
				var pdf_title = json_obj.data_arr.pdf_title_first_line + '\n\r' + json_obj.data_arr.pdf_title_second_line;
				var excel_title = json_obj.data_arr.pdf_title_first_line + ' - ' + json_obj.data_arr.pdf_title_second_line;
				$( '#ytd_earnings_table' ).DataTable( {
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
				$( '#ytd_earnings_table_filter input' ).addClass( 'form-control' ).attr( "placeholder", "Search" ).css( 'margin', 0 );
				$( '#ytd_earnings_table_filter' ).width( 210 ).css( 'float', 'right' );
				$( '.dt-buttons' ).width( 200 ).css( 'float', 'left' );
				if( $( document ).width() < 992 ){
					$( '#ytd_earnings_table_filter' ).width( '100%' ).addClass( 'text-left mt-2' );
					$( '#ytd_earnings_table_filter input' ).width( '100%' );
				}

				// $( '#activity_table_filter label' ).after( '<small id="search_note" class="form-text text-muted" style="margin-top: -0.5em">Enter EXACTLY what you\'re looking for</small>' );


				$( "#ytd_earnings_boxes_container_div" ).html( json_obj.data_arr['ytd_earnings_boxes'] );
				$( ".server_response_div .alert" ).removeClass( 'alert-warning alert-danger' ).addClass( 'alert-success' ).text( 'Table generated successfully.' ).show();
			}else{ //If there is an error
				$( ".server_response_div .alert" ).text( json_obj.error_message ).show();
				if( json_obj.error_level == 0 ){
					$( ".server_response_div .alert" ).removeClass( 'alert-success alert-danger' ).addClass( 'alert-warning' );
				}else{
					$( ".server_response_div .alert" ).removeClass( 'alert-success alert-warning' ).addClass( 'alert-danger' );
				}
			}
			window.setTimeout(function() {
				$(".alert-success, .alert-danger, .alert-warning").slideUp();
			}, 4000);
		} );
	} );
    
    $( "#client_account_list_form" ).submit( function( event ){
		event.preventDefault(); //Prevent the form from submitting normally
		$.post( 'junction.php', $( '#client_account_list_form' ).serialize(), function( data ){
			var json_obj = $.parseJSON( data );
			if( json_obj.status == true ){
				$( "#client_account_list_table" ).DataTable().destroy();
				$( "#client_account_list_table tbody" ).html( json_obj.data_arr['client_account_list_table'] );

				var currentDate = new Date();
				var top_massage = 'Created: ' + (currentDate.getMonth()+1) + '/' + currentDate.getDate() + '/' + currentDate.getFullYear() + ' ' + currentDate.getHours() + ':' + currentDate.getMinutes();
				const months_names = ["January", "February", "March", "April", "May", "June",
					"July", "August", "September", "October", "November", "December"
				];
				var file_name = 'Client Account List' + currentDate.getDate() + ' ' + months_names[currentDate.getMonth()] + ' ' + currentDate.getFullYear();
				var pdf_title = json_obj.data_arr.pdf_title_first_line + '\n\r' + json_obj.data_arr.pdf_title_second_line;
				var excel_title = json_obj.data_arr.pdf_title_first_line + ' - ' + json_obj.data_arr.pdf_title_second_line;
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

				// $( '#activity_table_filter label' ).after( '<small id="search_note" class="form-text text-muted" style="margin-top: -0.5em">Enter EXACTLY what you\'re looking for</small>' );


				$( "#client_account_list_boxes_container_div" ).html( json_obj.data_arr['client_account_list_boxes'] );
				$( ".server_response_div .alert" ).removeClass( 'alert-warning alert-danger' ).addClass( 'alert-success' ).text( 'Table generated successfully.' ).show();
			}else{ //If there is an error
				$( ".server_response_div .alert" ).text( json_obj.error_message ).show();
				if( json_obj.error_level == 0 ){
					$( ".server_response_div .alert" ).removeClass( 'alert-success alert-danger' ).addClass( 'alert-warning' );
				}else{
					$( ".server_response_div .alert" ).removeClass( 'alert-success alert-warning' ).addClass( 'alert-danger' );
				}
			}
			window.setTimeout(function() {
				$(".alert-success, .alert-danger, .alert-warning").slideUp();
			}, 4000);
		} );
	} );


	/**
	 Dashboard time priod form - changed selection.
	 */
	$( '#dashboard_time_period_form #time_periods_select' ).change( function(event){//On change of drop down list
		event.preventDefault(); //Prevent the form from submitting normally
		$.post( 'junction.php', $( '#dashboard_time_period_form' ).serialize(), function( data ){
			var json_obj = $.parseJSON( data );
			if( json_obj.status == true ){
				var pie_chart_data = $.parseJSON( json_obj.data_arr.pie_chart_data );
				if( pie_chart_data.datasets[0].data != null ){
					$( "#dashboard_pie_chart_2" ).show();
					$( "#dashboard_line_chart" ).show();
					pie_charts_arr[0].data = pie_chart_data;
					pie_charts_arr[0].update();
					line_chart.data = $.parseJSON( json_obj.data_arr.line_chart_data );
					line_chart.update();
				}else{
					$( "#dashboard_pie_chart_2" ).hide();
					$( "#dashboard_line_chart" ).hide();
				}
				$( "#dashboard_time_period_form .server_response_div .alert" ).removeClass( 'alert-warning alert-danger' ).addClass( 'alert-success' ).text( 'Data generated successfully.' ).show();
			}else{ //If there is an error
				$( "#dashboard_time_period_form .server_response_div .alert" ).text( json_obj.error_message ).show();
				if( json_obj.error_level == 0 ){
					$( "#dashboard_time_period_form .server_response_div .alert" ).removeClass( 'alert-success alert-danger' ).addClass( 'alert-warning' );
				}else{
					$( "#dashboard_time_period_form .server_response_div .alert" ).removeClass( 'alert-success alert-warning' ).addClass( 'alert-danger' );
				}
			}
			window.setTimeout(function() {
				$(".alert").slideUp();
			}, 4000);
		} );
	} );
    
    /**
	 Dashboard time priod form for sponsor- changed selection.
	 */
	$( '#co_dashboard_time_period_form #co_time_periods_select' ).change( function(event){//On change of drop down list
		event.preventDefault(); //Prevent the form from submitting normally
		$.post( 'junction.php', $( '#co_dashboard_time_period_form' ).serialize(), function( data ){
			var json_obj = $.parseJSON( data );
			if( json_obj.status == true ){
			    $( "#sponsor_table" ).html( json_obj.data_arr['sponsor_table'] );
				$( "#co_dashboard_time_period_form .server_response_div .alert" ).removeClass( 'alert-warning alert-danger' ).addClass( 'alert-success' ).text( 'Data generated successfully.' ).show();
			}else{ //If there is an error
				$( "#co_dashboard_time_period_form .server_response_div .alert" ).text( json_obj.error_message ).show();
				if( json_obj.error_level == 0 ){
					$( "#co_dashboard_time_period_form .server_response_div .alert" ).removeClass( 'alert-success alert-danger' ).addClass( 'alert-warning' );
				}else{
					$( "#co_dashboard_time_period_form .server_response_div .alert" ).removeClass( 'alert-success alert-warning' ).addClass( 'alert-danger' );
				}
			}
			window.setTimeout(function() {
				$(".alert").slideUp();
			}, 4000);
		} );
	} );

	/**
	 Reports form - changed selection.
	 */
	$( '#reports_form #time_periods_select' ).change( function(){ //On change of drop down list
		var id_of_selected_option = $( this ).find( "option:selected" ).attr( "id" );
		if( id_of_selected_option == 'dates_form_option_all_dates' ){
			$( '#reports_form_dates_radios_div input' ).prop( "disabled", true );
			$( '.hidden_form_div' ).hide(); //Hide the hidden div with the dates input.
			$( '.hidden_form_div input' ).prop( "disabled", true );
		}else{
			$( '#reports_form_dates_radios_div input' ).prop( "disabled", false );
			if( id_of_selected_option == "dates_form_option_custom" ){ //Check if the selected option was 'Custom'
				$( '.hidden_form_div' ).show(); //If so - show the hidden div with the dates input.
				$( '.hidden_form_div input' ).prop( "disabled", false );
			}else{
				$( '.hidden_form_div' ).hide(); //Hide the hidden div with the dates input.
				$( '.hidden_form_div input' ).prop( "disabled", true );
			}

		}
	} );


	/**
	 Reports form submit
	 */
	$( "#reports_form" ).submit( function( event ){
		event.preventDefault(); //Prevent the form from submitting normally
		$.post( 'junction.php', $( '#reports_form' ).serialize(), function( data ){
			var json_obj = $.parseJSON( data );
			if( json_obj.status == true ){
				var pie_chart_data = $.parseJSON( json_obj.data_arr.pie_chart_data );
				if( pie_chart_data.datasets[0].data != null ){
					$( "#reports_pie_chart" ).show();
					$( "#reports_line_chart" ).show();
					pie_charts_arr[0].data = pie_chart_data;
					pie_charts_arr[0].update();
					line_chart.data = $.parseJSON( json_obj.data_arr.line_chart_data );
					line_chart.update();
				}else{
					$( "#reports_pie_chart" ).hide();
					$( "#reports_line_chart" ).hide();
				}
				$( "#reports_table" ).html( json_obj.data_arr['reports_table_html'] );
				$( ".server_response_div .alert" ).removeClass( 'alert-warning alert-danger' ).addClass( 'alert-success' ).text( 'Data generated successfully.' ).show();
			}else{ //If there is an error
				$( ".server_response_div .alert" ).text( json_obj.error_message ).show();
				if( json_obj.error_level == 0 ){
					$( ".server_response_div .alert" ).removeClass( 'alert-success alert-danger' ).addClass( 'alert-warning' );
				}else{
					$( ".server_response_div .alert" ).removeClass( 'alert-success alert-warning' ).addClass( 'alert-danger' );
				}
			}
			window.setTimeout(function() {
				$(".alert").slideUp();
			}, 4000);
		} );
	} );


	/**
	 Sign out link
	 */
	$( "#sign_out_fake_link" ).click( function(){
		$.post( "junction.php", {func: 'sign_out', class: 'no_class'}, function( data ){
			var json_obj = $.parseJSON( data );
			if( json_obj.status == true ){
				var get_params = '?company_name=' + json_obj.data_arr['company_name'];
				window.location.replace( "login.php" + get_params );
			}
		} );
	} );


	/**
	 * Drill down pie chart
	 * @param evt
	 */
	var current_page = location.href.split( "/" ).slice( -1 );
	if( current_page == 'dashboard.php' ){
		$( "#dashboard_pie_chart" )[0].onclick = function( event ){
			drill_down_pie_chart( 'dashboard_pie_chart', event, pie_charts_arr[0] );
		};

		/*$( "#dashboard_pie_chart_2" )[0].onclick = function( event ){
			drill_down_pie_chart( 'dashboard_pie_chart_2', event, pie_charts_arr[1] );
		};*/
	}else if( current_page == 'reports.php' ){
		$( "#reports_pie_chart" )[0].onclick = function( event ){
			drill_down_pie_chart( 'reports_pie_chart', event, pie_charts_arr[0] );
		};
	}

	function drill_down_pie_chart( chart_id, evt, pie_chart ){
		var activePoints = pie_chart.getElementsAtEvent( evt );
		var date_type = $( '#reports_form input[name=choose_date_radio]:checked' ).val();
		if( activePoints[0] ){
			var chartData = activePoints[0]['_chart'].config.data;
			var idx = activePoints[0]['_index'];

			var label = chartData.labels[idx];
			var value = chartData.datasets[0].data[idx];
			var color = chartData.datasets[0].backgroundColor[idx];

			if( chart_id == 'dashboard_pie_chart' ){
				if( $( ".server_response_div .alert" ).text() != 'No relevant records were found.' ){
					var dashboard_form_date = $( "#dashboard_form input[type=date]" ).val();
				}else{
					var dashboard_form_date = today;
				}
			}

			$.post( "junction.php", {
				func: 'drill_down_pie_chart',
				class: 'no_class',
				chart_id: chart_id,
				label: label,
				value: value,
				color: color,
				date_type: date_type,
				dashboard_form_date: dashboard_form_date
			}, function( server_response_data ){
				$( '#drill_down_pie_chart_modal' ).modal( 'show' );
				var json_obj = $.parseJSON( server_response_data );
				$( "#drill_down_table_div" ).html( json_obj.data_arr['drill_down_table'] );
			} );
		}
	}

	/**
	 * Check if window is small enough, and if so, move down Pie chart legend
	 */
	if( $( document ).width() < 992 ){
		var i;
		for( i = 0; i < pie_charts_arr.length; i++ ){
			pie_charts_arr[i].options.legend.position = 'bottom';
			pie_charts_arr[i].update();
		}
	}


	/**
	 Dashboard form submit
	 */
	$( "#dashboard_form" ).submit( function( event ){
		event.preventDefault(); //Prevent the form from submitting normally
		$.post( 'junction.php', $( '#dashboard_form' ).serialize(), function( data ){
			var json_obj = $.parseJSON( data );
			if( json_obj.status == true ){
				pie_charts_arr[0].data = $.parseJSON( json_obj.data_arr.pie_chart_data );
				pie_charts_arr[0].update();
				$( "#posted_commission_heading" ).text( json_obj.data_arr.posted_commission );
				$( "#dashboard_form .server_response_div .alert" ).removeClass( 'alert-warning alert-danger' ).addClass( 'alert-success' ).text( 'Data generated successfully.' ).show();
			}else{ //If there is an error
				$( "#dashboard_form .server_response_div .alert" ).text( json_obj.error_message ).show();
				if( json_obj.error_level == 0 ){
					$( "#dashboard_form .server_response_div .alert" ).removeClass( 'alert-success alert-danger' ).addClass( 'alert-warning' );
				}else{
					$( "#dashboard_form .server_response_div .alert" ).removeClass( 'alert-success alert-warning' ).addClass( 'alert-danger' );
				}
			}
			window.setTimeout(function() {
				$(".alert-success, .alert-danger, .alert-warning").slideUp();
			}, 4000);
		} );
	} );

} );

$( document ).on( {
	ajaxStart: function(){
		$( ".loader" ).show();
	},
	ajaxStop: function(){
		$( ".loader" ).hide();
	}
	// load: function() {  }
} );