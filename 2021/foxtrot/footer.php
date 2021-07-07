</div>
<footer style="<?php if(CURRENT_FILE == 'home.php'){ echo 'display : block !important';}else { echo 'display : none !important';} ?> ">
<div class="sectionwrapper footerwrapp">
	<div class="container">
		<div class="footerwrapper">
			<div class="copyrights">2017 Copyright &copy; FoxTrot, LLC</div>
			<div class="footersocial">
				<a href="#" target="_blank"><i class="fa fa-facebook"></i></a>
				<a href="#" target="_blank"><i class="fa fa-twitter"></i></a>
				<a href="#" target="_blank"><i class="fa fa-google-plus"></i></a>
				<a href="#" target="_blank"><i class="fa fa-linkedin"></i></a>
			</div>
		</div>
	</div>
</div>
</footer>

<!--
<div class="sectionwrapper">
  <div class="container">
    <div class="row">
    	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 pull-left"></div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 pull-right"></div>
    </div>
  </div>
</div>
 --> 


<!-- Include all compiled plugins (below), or include individual files as needed --> 
<script src="js/bootstrap.min.js"></script>
<script>
$(".alert-success").fadeTo(1000, 500).slideUp(500, function(){
    $(".alert-success").slideUp(500);
});
</script>
</body>
</html>