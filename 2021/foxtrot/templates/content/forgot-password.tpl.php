<!-- RESET FORM -->
<div class="cls-content">
	<div class="cls-content-sm panel login-panel">
		<div class="panel-body">
			<div class="mar-ver pad-btm text-center">
				<h1 class="h3">Foxtrot</h1>
				<p>Reset your account</p>
			</div>
            <?php require_once(DIR_FS_INCLUDES."alerts.php"); ?>
			<form class="form-validate" action="<?php echo CURRENT_PAGE; ?>" method="post">
				<div class="form-group">
					<input type="email" name="email" id="email" class="form-control" placeholder="Email" value="<?php echo $email; ?>" autofocus="true" required="required" />
				</div>
				<button class="btn btn-warning bg-orange-active" type="submit" name="submit" value="submit"><i class="ti-email"></i> Send Email</button>
                <a href="<?php echo SITE_URL; ?>sign-in" class="btn btn-warning bg-orange-active"><i class="ti-share-alt"></i> Cancel</a>
			</form>
		</div>

	</div>
</div>