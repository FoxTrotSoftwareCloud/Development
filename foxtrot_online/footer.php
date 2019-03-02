<?php
echo '<footer class="text-center bg-dark">
		<div class="container">
			<span class="text-muted">2018 Copyright &copy; FoxTrot, LLC</span>
		</div>
	</footer>';
if($GLOBALS['db_conn']){
	$GLOBALS['db_conn']->close(); //close DB connection
}

if(isset($GLOBALS['thrown_exception'])){
	$GLOBALS['thrown_exception'] = addslashes($GLOBALS['thrown_exception']);
	echo "<script type='text/javascript'>
			$( document ).ready( function(){
				$( \".server_response_div .alert\" ).removeClass('alert-warning').addClass( 'alert-danger' ).text('Please contact an administrator. An error occurred: {$GLOBALS['thrown_exception']}').show();
			});
	</script>";
	unset($GLOBALS['thrown_exception']);
}