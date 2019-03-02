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
			<div class="row">
				<form class="col-md-3">
					<div class="server_response_div mt-2">
						<div class="alert" role="alert"></div>
					</div>
					<label class="h6">Available Statements</label><br>
					<select id="statements_select" class="form-control" name="statements_select">
						<?php
						echo statement::statements_list("{$_SESSION['company_name']}/data");
						?>
					</select>
					<div style="margin-top: 20px;">
						<a class="statement_toolbar" href="none" download><button class="btn btn-sm btn-outline-secondary" type="button">Download</button></a>
						<a class="statement_toolbar" href="none" target="_blank"><button class="btn btn-sm btn-outline-secondary" type="button">Open</button></a>
					</div>
				</form>


				<object id="statement_pdf_object" class="col-md-9" data="none" type="application/pdf" height="450">
					<!--For wider browser compatibility-->
<!--					<iframe class="col-md-9" data="data/hello_world.pdf?#view=Fit" type="application/pdf" height="450">-->
<!--					</iframe>-->
				</object>
				<?php
				echo statement::statement_buttons_pdf_url_changer();
				?>
			</div>
		</main>
	</div>
</div>
<?php
require_once 'footer.php';
?>
<script type="text/javascript">
	/*
	If there's nothing in the dropdown list than disable the buttons.
	 */
	var select_size = $( '#statements_select option' ).length;
	if( select_size == 0 ){
		$( '#statements_select' ).prop( "disabled", true );
		$( '.statement_toolbar button' ).prop( "disabled", true );
		$( ".server_response_div .alert" ).text( "No available PDFs." ).addClass( 'alert-warning' ).show();
	}
</script>
</body>
</html>