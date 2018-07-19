<!-- LOGIN FORM -->
<div class="cls-content">
	<div class="cls-content-sm panel login-panel">
		<div class="panel-body">
			<div class="mar-ver pad-btm text-center">
				<h1 class="h3">Foxtrot</h1>
				<p>Sign In to your account</p>
			</div>
            <?php require_once(DIR_FS_INCLUDES."alerts.php"); ?>
			<form class="form-validate" action="<?php echo CURRENT_PAGE; ?>" method="post">
				<div class="form-group">
					<input type="text" name="username" id="username" autocomplete="off" class="form-control" placeholder="Username" value="<?php echo $username; ?>" autofocus="true" required="required" />
				</div>
				<div class="form-group">
					<input type="password" name="password" id="password" autocomplete="off" class="form-control" placeholder="Password" required="required" />
				</div>
				<button class="btn btn-warning bg-orange-active btn-block" type="submit" name="submit" value="submit"><i class="ti-unlock"></i> Sign In</button>
			</form>
            <div class="pad-top">
				<a href="<?php echo SITE_URL; ?>forgot-password" class="btn-link mar-rgt">Forgot Username / Password?</a>
			</div>
		</div>

	</div>
</div>