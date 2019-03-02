<?php
require_once 'header.php';
?>

<html lang="en">
<head>
	<?php echo HEAD; ?>
	<link href="login_stylesheet.css" rel="stylesheet">
</head>
<body>
<div class='loader'></div>
<div class="container">
	<div class="row justify-content-center mt-3 d-none d-md-flex">
		<div class="col-3">
			<?php
			$comapny_name = (isset($_SESSION['company_name'])) ? $_SESSION['company_name'] : 'demo';
			echo "<img src='lib/logos/{$comapny_name}.png' alt='logo' class='logo'>";
			?>
		</div>
	</div>

	<div class="card card-container">
		<h4 class="mb-3" style="text-align: center;">FoxTrot Online</h4>
		<form id="log_in_form" class="form-signin mb-0">
			<div class="server_response_div mt-2">
				<div class="alert" role="alert"></div>
			</div>
			<input name="username_or_email" type="text" class="form-control" placeholder="Username or E-mail"
			       autocomplete="username" required>
			<input name="password" type="password" class="form-control" placeholder="Password"
			       autocomplete="current-password" required>
			<div class="custom-control custom-checkbox">
				<input type="checkbox" name="remember_me" class="custom-control-input" id="remember_me_checkbox"
				       checked>
				<label class="custom-control-label" for="remember_me_checkbox">Remember Me</label>
			</div>
			<input name="class" value="permrep" hidden>
			<input name="func" value="log_in" hidden>
			<input class="btn btn-lg btn-primary btn-block btn-signin" type="submit" value="Sign in">
		</form><!-- /form -->
		<a href="#" class="forgot-password" data-toggle="modal" data-target="#forgot_password_modal">
			Forgot Username / Password?
		</a>
	</div><!-- /card-container -->
</div><!-- /container -->

<!-- Modal -->
<div class="modal fade" id="forgot_password_modal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="forgot_password_modal_title">Forgot Username or Password?</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="forgot_password_form">
					<div class="server_response_div mt-2">
						<div class="alert" role="alert"></div>
					</div>
					<input name="username_or_email" type="email" class="form-control mb-3"
					       placeholder="Email address"
					       autocomplete="email" autofocus required>
					<div class="text-center">
						<input class="btn btn-lg btn-primary btn-block btn-signin" type="submit"
						       value="Send my password to my E-mail">
					</div>

					<input name="class" value="permrep" hidden>
					<input name="func" value="forgot_password" hidden>
				</form>
			</div>
		</div>
	</div>
</div>

<?php
require_once 'footer.php';

//If try to log in without GET parameters, disable the log in button and show a danger alert.
//Show a modal with a grid showing all the logos of all the companies. to choose from.
if($_GET["company_name"] == '' || !isset($_SESSION['db_name'])){
	$script = '
	<script>
		$(".btn-signin").prop( "disabled", true );
		$( ".server_response_div .alert" ).addClass( "alert-danger" ).text("Add or correct company name in the URL address").show();
	</script>
	';
	echo $script;
	echo logo_html_modal();
}
?>
</body>
</html>