<?php 
require_once("include/config.php");
require_once(DIR_FS."islogin.php");?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
<title>Foxtrot</title>

<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet" />
<!-- font-awesome -->
<link href="css/font-awesome.min.css" rel="stylesheet" />
<link href="css/style.css" rel="stylesheet" />
<link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico" />
<link href="<?php echo SITE_CSS; ?>bootstrap-datepicker.min.css" rel="stylesheet"/>

<!-- search with selection box-->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.5.1/chosen.min.css"/>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>


<!--<link href="<?php echo SITE_CSS; ?>datatables.css" rel="stylesheet"/>
<link href="<?php echo SITE_CSS; ?>datatables.min.css" rel="stylesheet"/>-->
<script src="js/jquery.min.js"></script>
<script src="<?php echo SITE_JS; ?>bootstrap-datepicker.min.js"></script>
<!--<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js"></script>-->
<!--<script src="<?php echo SITE_JS; ?>datatables.js"></script>
<script src="<?php echo SITE_JS; ?>datatables.min.js"></script>-->

<!-- Datatables-->
<link rel="stylesheet" href="<?php echo SITE_PLUGINS; ?>datatables/dataTables.bootstrap.min.css" />
<link rel="stylesheet" href="<?php echo SITE_PLUGINS; ?>datatables/buttons.dataTables.min.css" />
<script src="<?php echo SITE_PLUGINS; ?>datatables/jquery.dataTables.js"></script>
<script type="text/javascript" src="<?php echo SITE_PLUGINS; ?>datatables/dataTables.bootstrap.min.js"></script>
<script src="<?php echo SITE_PLUGINS; ?>datatables/dataTables.buttons.min.js"></script>
<script src="<?php echo SITE_PLUGINS; ?>datatables/jszip.min.js"></script>
<script src="<?php echo SITE_PLUGINS; ?>datatables/pdfmake.min.js"></script>
<script src="<?php echo SITE_PLUGINS; ?>datatables/vfs_fonts.js"></script>        
<script src="<?php echo SITE_PLUGINS; ?>datatables/buttons.html5.min.js"></script>  
<script src="<?php echo SITE_PLUGINS; ?>datatables/buttons.colVis.min.js"></script>      
  
     
<script src="<?php echo SITE_JS; ?>validator.js"></script>
<script src="<?php echo SITE_JS; ?>multipleselection.js"></script>
<script src="<?php echo SITE_JS; ?>custom.js"></script>
<script src="<?php echo SITE_PLUGINS; ?>bootbox/bootbox.min.js"></script>
<script src="<?php echo SITE_PLUGINS; ?>masked-input/jquery.maskedinput.min.js"></script>
<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
      <script src="js/html5shiv.min.js"></script>
      <script src="js/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<?php 
$instance_header = new header_class();
?>
<header style="<?php if(isset($_GET['action']) && ($_GET['action'] == 'edit' || $_GET['action'] == 'add_new' || $_GET['action'] == 'add_sponsor' || $_GET['action'] == 'edit_sponsor' || $_GET['action'] == 'add_product' || $_GET['action'] == 'edit_product' || $_GET['action'] == 'add' || $_GET['action'] == 'edit_transaction' || $_GET['action'] == 'add_batches' || $_GET['action'] == 'edit_batches')){ echo 'display : none !important';} ?> ">
<div class="sectionwrapper headerwrapper">
  <div class="container">
    <div class="headertop">
      <div class="sitelogo"><a href="home.php" title="Foxtrot"><img src="images/sitelogo.png" alt="Foxtrot" /></a></div>
      <div class="headertopright">
		<a href="#" class="userinfo"><img src="images/Help-desk.png" alt="Chat/Help" title="Chat/Help" height="40" width="60" /></a>
                
		<div class="userlogin">
			<ul class="nav navbar-nav">
                 <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php if(isset($_SESSION['user_name'])){echo 'Hello '.$_SESSION['user_name']." ";}?>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo SITE_URL; ?>user_profile.php?action=edit&id=<?php echo $_SESSION['user_id'];?>">User Profile</a></li>
						<li><a href="sign-out.php">Logout</a></li>
                    </ul>
                 </li>              
             </ul>
             <!--<a href="<?php echo SITE_URL; ?>user_profile.php?action=edit&id=<?php echo $_SESSION['user_id'];?>" class="dropdown-toggle" >User Profile
                    
             <?php $user_header_image = $instance_header->get_user_image($_SESSION['user_id']); ?>
             <div class="userimg"><img src="<?php echo SITE_URL."upload/".$user_header_image['image'];?>" height="30" width="50" /></div>-->
		</div>
	  </div>
    </div>
	<div class="headermenu">
		<nav class="navbar navbar-inverse">
		  <div class="container-fluid">
			<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
			</div>
			<div class="collapse navbar-collapse" id="myNavbar">
			<ul class="nav navbar-nav">
			  <li class="active menuhome"><a href="home.php"><i class="fa fa-home"></i></a></li>
			  <?php  
					
					$menu = $instance_header->menu_select();
					//echo '<pre>';print_r($menu);exit;
			  ?>
			  <?php 
                        foreach($menu as $menukey=>$menudata)
                        { 
                    ?>   	<li class="dropdown"> 
								<a <?php if(!empty($menudata['submenu'])){  ?> class="dropdown-toggle"  data-toggle="dropdown"  <?php } ?>href="<?php echo $menudata['link_page']; ?>"><?php echo $menudata['link_text']; ?> <i class="<?php echo $menudata['class']; ?>"></i></a>
								<?php if(!empty($menudata['submenu'])){  ?>
									<ul class="dropdown-menu">
									<?php 
										foreach($menudata['submenu'] as $subkey=>$subdata)
										{ 
										?>    
											<li><a href="<?php echo $subdata['link_page'] ?>"><?php echo $subdata['link_text']; ?></a></li>
										<?php 
                                        } 
										?>
									</ul>
								<?php } 
									?>
							</li>
                    <?php }?>
			  <!--li>
                <a class="dropdown-toggle" href="<?php echo SITE_URL; ?>import.php">Import <i class="fa fa-download"></i></a>
              </li>
			  <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">Commissions <i class="fa fa-inr"></i></a>
                <ul class="dropdown-menu">
				  <li><a href="<?php echo SITE_URL; ?>transaction.php">Enter Commissions</a></li>
				  <li><a href="<?php echo SITE_URL; ?>batches.php">Batches</a></li>
				  <li><a href="#">Post Commission</a></li>
                  <li><a href="#">Report</a></li>
				</ul>
              </li>
			  <li>
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">Payroll <i class="fa fa-list-alt"></i></a>
                <ul class="dropdown-menu">
				  <li><a href="#">Upload</a></li>
				  <li><a href="#">Calculate</a></li>
				  <li><a href="#">Review</a></li>
                  <li><a href="#">Publish</a></li>
                  <li><a href="#">Closed Out</a></li>
				</ul>
              </li>
			  
			  <li>
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">Reporting <i class="fa fa-comments-o"></i></a>
                <ul class="dropdown-menu">
				  <li><a href="#">Sales Reporting</a></li>
				  <li><a href="#">Transaction History</a></li>
				  <li><a href="#">Reporting Designer</a></li>
				</ul>
              </li>	
              <li>
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">Administration <i class="fa fa-user-plus"></i></a>
                <ul class="dropdown-menu">
				  <li><a href="<?php echo SITE_URL; ?>manage_multicompany.php">Mulit-Company Maintenance</a></li>
				  <li><a href="<?php echo SITE_URL; ?>branch_maintenance.php">Branch Maintenance</a></li>
				  <li><a href="<?php echo SITE_URL; ?>manage_broker.php">Broker Maintenance</a></li>
                  <li><a href="<?php echo SITE_URL; ?>manage_sponsor.php">Sponsor Maintenance</a></li>
                  <li><a href="<?php echo SITE_URL; ?>product_cate.php">Product Maintenance</a></li>
                  <li><a href="<?php echo SITE_URL; ?>client_maintenance.php">Client Maintenance</a></li>
				</ul>
              </li>		  
			  <li>
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">Compilance <i class="fa fa-file-code-o"></i></a>
                <ul class="dropdown-menu">
				  <li><a href="#">Rules Engine</a></li>
				  <li><a href="#">Licensing</a></li>
				  <li><a href="#">Report</a></li>
				</ul>
              </li>
			  <li class="dropdown">
				<a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">Supervisor
				<i class="fa fa-angle-down"></i></a>
				<ul class="dropdown-menu">
				  <li><a href="<?php echo SITE_URL; ?>user_profile.php">User Profiles</a></li>
				  <li><a href="<?php echo SITE_URL; ?>data_interface.php">Data Interfaces</a></li>
				  <li><a href="<?php echo SITE_URL; ?>of_fi.php">OFAC / FINCEN</a></li>
                  <li><a href="<?php echo SITE_URL; ?>client_ress.php">Client Reassignment</a></li>
                  <li><a href="<?php echo SITE_URL; ?>client_suitability.php">Client Suitability</a></li>
				  <li><a href="<?php echo SITE_URL; ?>account_type.php">Account Type Maintenance</a></li>
				  <li><a href="<?php echo SITE_URL; ?>product_category_maintenance.php">Product Category Maintenance</a></li>
                  <li><a href="<?php echo SITE_URL; ?>payroll_adjustment.php">Payroll Adjustment Category Maintenance</a></li>
                  <li><a href="<?php echo SITE_URL; ?>system_config.php">System Configuration</a></li>
				</ul>
			  </li>-->
			</ul>
		  </div>
		  </div>
		</nav>
	</div>
  </div>
</div>
</header>
<div class="contentmain" style="<?php if(isset($_GET['action']) && ($_GET['action'] == 'edit' || $_GET['action'] == 'add_new' || $_GET['action'] == 'add_sponsor' || $_GET['action'] == 'edit_sponsor' || $_GET['action'] == 'add_product' || $_GET['action'] == 'edit_product' || $_GET['action'] == 'add' || $_GET['action'] == 'edit_transaction' || $_GET['action'] == 'add_batches' || $_GET['action'] == 'edit_batches')){ echo 'padding : 0px !important';} ?>">
<script type="text/javascript">
$(document).ready(function() {
    $('input:text:visible:first:not(#from_date,#beginning_date)', this).focus();
});
</script>
