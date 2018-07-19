<?php
	if(isset($error) && $error !=''){
		$dbins->error_message($error);
	}
	else if(isset($_SESSION['success'])){
		$dbins->success_message($_SESSION['success']);
		unset($_SESSION['success']);
	}
	else if(isset($_SESSION['error'])){
		$dbins->error_message($_SESSION['error']);
		unset($_SESSION['error']);
	}
	else if(isset($_SESSION['warning'])){
		$dbins->warning_message($_SESSION['warning']);
		unset($_SESSION['warning']);
	}
	else if(isset($_SESSION['info'])){
		$dbins->info_message($_SESSION['info']);
		unset($_SESSION['info']);
	}
?>