<div class="container">
<h1>Data Interfaces</h1>
<div class="col-lg-12 well">
<?php require_once(DIR_FS_INCLUDES."alerts.php"); ?>
        <ul class="nav nav-pills nav-stacked col-md-2">
          <li <?php if(isset($_GET['dim'])&& $_GET['dim']=='1'){ ?> class="active"<?php }else if(!isset($_GET['dim'])){?> class="active"<?php }?>><a href="<?php echo CURRENT_PAGE; ?>?dim=1">DST IDC</a></li>
          <li <?php if(isset($_GET['dim'])&& $_GET['dim']=='2'){ ?> class="active"<?php }?>><a href="<?php echo CURRENT_PAGE; ?>?dim=2">DST FANMail</a></li>
          <li <?php if(isset($_GET['dim'])&& $_GET['dim']=='3'){ ?> class="active"<?php }?>><a href="<?php echo CURRENT_PAGE; ?>?dim=3">DAZL Daily</a></li>
          <li <?php if(isset($_GET['dim'])&& $_GET['dim']=='4'){ ?> class="active"<?php }?>><a href="<?php echo CURRENT_PAGE; ?>?dim=4">DAZL Commissions</a></li>
          <li <?php if(isset($_GET['dim'])&& $_GET['dim']=='5'){ ?> class="active"<?php }?>><a href="<?php echo CURRENT_PAGE; ?>?dim=5">NFS/Fidelity</a></li>
          <li <?php if(isset($_GET['dim'])&& $_GET['dim']=='6'){ ?> class="active"<?php }?>><a href="<?php echo CURRENT_PAGE; ?>?dim=6">Pershing</a></li>
          <li <?php if(isset($_GET['dim'])&& $_GET['dim']=='7'){ ?> class="active"<?php }?>><a href="<?php echo CURRENT_PAGE; ?>?dim=7">Raymond James</a></li>
          <li <?php if(isset($_GET['dim'])&& $_GET['dim']=='8'){ ?> class="active"<?php }?>><a href="<?php echo CURRENT_PAGE; ?>?dim=8">RBC Dain</a></li>
        </ul>
        <div class="tab-content col-md-10">
                <div class="tab-pane <?php if(isset($_GET['dim'])&& $_GET['dim']=='1'){?>active<?php }else if(!isset($_GET['dim'])){?>active<?php }?>" id="tab_a">
                    <form name="frm" method="POST" enctype="multipart/form-data">
                        <div class="panel-heading">
                            <h2 class="panel-title" style="color:#ef7623;"><i class="fa fa-pencil-square-o"></i> DST IDC</h2>
    					</div>
                        <br />
    					<div class="col-md-12" style="background-color: #E9EEF2 !important;">				
    						<div class="row" style="margin-top: 5px;">
    							<div class="col-md-6 form-group">
    								<label>UserName</label>
    								<input type="text" name="uname" class="form-control" value="<?php echo $uname;?>" />
    							</div>		
    							<div class="col-md-6 form-group">
    								<label>Password</label>
    								<input type="text" name="password" class="form-control" value="<?php echo $instance->decryptor($password); ?>"/>
    							</div>	
                            </div>
                            <div class="row"> 
                                <div class="col-md-6 form-group">
                                    <label>Exclude Non-Commissionable Trade Activity </label>
                                </div>
                                <div class="col-md-6 form-group">
                                    <input type="checkbox" name="trade_activity" value="1" onclick="chk_all_class(this.checked)" <?php if($trade_activity>0){ echo "checked='checked'";}?>  class="checkbox" />
                                </div>
                            </div>
                            <div class="row"> 
                                <div class="col-md-6 form-group">
                                    <label>Add Clients if Not Found </label>
                                </div>
                                <div class="col-md-6 form-group">
                                    <input type="checkbox" name="add_client" value="1" onclick="chk_all_class(this.checked)" <?php if($add_client>0){ echo "checked='checked'";}?>  class="checkbox" />
                                </div>
                            </div>	
                            <div class="row"> 
                                <div class="col-md-6 form-group">
                                    <label>Update Existing Clients </label>
                                </div>
                                <div class="col-md-6 form-group">
                                    <input type="checkbox" name="update_client" value="1" onclick="chk_all_class(this.checked)" <?php if($update_client>0){ echo "checked='checked'";}?>  class="checkbox" />
                                </div>
                            </div>
                            <div class="row"> 
                                <div class="col-md-6 form-group">
                                    <label>Local Folder </label>
                                </div>
                                <div class="col-md-6 form-group">
                                    <input type="file" name="local_folder" class="form-control" />
                                    <!--input type="file" name="file1"   /-->
                                </div>
                            </div>	
                            <div class="selectwrap">
                                <input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
                                <input type="hidden" name="dim_id" id="dim_id" value="1" />
                                <input type="hidden" name="is_authorized" id="is_authorized" value="1" />
            					<input type="button" name="clear" value="Clear" style="float: right;"/>
                                <input type="submit" onclick="waitingDialog.show();" name="submit" value="Save" style="float: right;"/>	
                            </div>
                            <br />				
    					</div>
				    </form> 
                </div>
                <div class="tab-pane <?php if(isset($_GET['dim'])&& $_GET['dim']=='2'){?>active<?php }?>" id="tab_b">
                     <form name="frm" method="POST" enctype="multipart/form-data">
                        <div class="panel-heading">
                            <h2 class="panel-title" style="color:#ef7623;"><i class="fa fa-pencil-square-o"></i> DST FANMail</h2>
    					</div>
                        <br />
    					<div class="col-md-12">				
    						<div class="row">
    							<div class="col-md-6 form-group">
    								<label>UserName</label>
    								<input type="text" name="uname" class="form-control" value="<?php echo $uname;?>"/>
    							</div>		
    							<div class="col-md-6 form-group">
    								<label>Password</label>
    								<input type="text" name="password" class="form-control" value="<?php echo $instance->decryptor($password); ?>" />
    							</div>	
                            </div>
                            <div class="row"> 
                                <div class="col-md-6 form-group">
                                    <label>Exclude Non-Commissionable Trade Activity </label>
                                </div>
                                <div class="col-md-6 form-group">
                                    <input type="checkbox" name="trade_activity" value="1" onclick="chk_all_class(this.checked)" <?php if($trade_activity>0){ echo "checked='checked'";}?>  class="checkbox" />
                                </div>
                            </div>
                            <div class="row"> 
                                <div class="col-md-6 form-group">
                                    <label>Add Clients if Not Found </label>
                                </div>
                                <div class="col-md-6 form-group">
                                    <input type="checkbox" name="add_client" value="1" onclick="chk_all_class(this.checked)" <?php if($add_client>0){ echo "checked='checked'";}?>  class="checkbox" />
                                </div>
                            </div>	
                            <div class="row"> 
                                <div class="col-md-6 form-group">
                                    <label>Update Existing Clients </label>
                                </div>
                                <div class="col-md-6 form-group">
                                    <input type="checkbox" name="update_client" value="1" onclick="chk_all_class(this.checked)" <?php if($update_client>0){ echo "checked='checked'";}?>  class="checkbox" />
                                </div>
                            </div>
                            <div class="row"> 
                                <div class="col-md-6 form-group">
                                    <label>Local Folder </label>
                                </div>
                                <div class="col-md-6 form-group">
                                    <input type="file" name="local_folder" class="form-control" />
                                    <!--input type="file" name="file1"   /-->
                                </div>
                            </div>	
                            <div class="selectwrap">
                                <input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
                                <input type="hidden" name="dim_id" id="dim_id" value="2" />
                                <input type="hidden" name="is_authorized" id="is_authorized" value="1" />
            					<input type="button" name="clear" value="Clear" style="float: right;"/>
                                <input type="submit" onclick="waitingDialog.show();" name="submit" value="Save" style="float: right;"/>	
                            </div>				
    					</div>
				    </form> 
                </div>
                <div class="tab-pane <?php if(isset($_GET['dim'])&& $_GET['dim']=='3'){?>active<?php }?>" id="tab_c">
                    <form name="frm" method="POST" enctype="multipart/form-data">
                        <div class="panel-heading">
                            <h2 class="panel-title" style="color:#ef7623;"><i class="fa fa-pencil-square-o"></i> DAZL Daily</h2>
    					</div>
                        <br />
    					<div class="col-md-12" style="background-color: #E9EEF2 !important;">				
    						<div class="row" style="margin-top: 5px;">
    							<div class="col-md-6 form-group">
    								<label>UserName</label>
    								<input type="text" name="uname" class="form-control" value="<?php echo $uname;?>" />
    							</div>		
    							<div class="col-md-6 form-group">
    								<label>Password</label>
    								<input type="text" name="password" class="form-control" value="<?php echo $instance->decryptor($password); ?>" />
    							</div>	
                            </div>
                            <div class="row"> 
                                <div class="col-md-6 form-group">
                                    <label>Exclude Non-Commissionable Trade Activity </label>
                                </div>
                                <div class="col-md-6 form-group">
                                    <input type="checkbox" name="trade_activity" value="1" onclick="chk_all_class(this.checked)" <?php if($trade_activity>0){ echo "checked='checked'";}?>  class="checkbox" />
                                </div>
                            </div>
                            <div class="row"> 
                                <div class="col-md-6 form-group">
                                    <label>Add Clients if Not Found </label>
                                </div>
                                <div class="col-md-6 form-group">
                                    <input type="checkbox" name="add_client" value="1" onclick="chk_all_class(this.checked)" <?php if($add_client>0){ echo "checked='checked'";}?>  class="checkbox" />
                                </div>
                            </div>	
                            <div class="row"> 
                                <div class="col-md-6 form-group">
                                    <label>Update Existing Clients </label>
                                </div>
                                <div class="col-md-6 form-group">
                                    <input type="checkbox" name="update_client" value="1" onclick="chk_all_class(this.checked)" <?php if($update_client>0){ echo "checked='checked'";}?> class="checkbox" />
                                </div>
                            </div>
                            <div class="row"> 
                                <div class="col-md-6 form-group">
                                    <label>Local Folder </label>
                                </div>
                                <div class="col-md-6 form-group">
                                    <input type="file" name="local_folder" class="form-control" />
                                    <!--input type="file" name="file1"   /-->
                                </div>
                            </div>	
                            <div class="selectwrap">
                                <input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
                                <input type="hidden" name="dim_id" id="dim_id" value="3" />
                                <input type="hidden" name="is_authorized" id="is_authorized" value="1" />
            					<input type="button" name="clear" value="Clear" style="float: right;"/>
                                <input type="submit" onclick="waitingDialog.show();" name="submit" value="Save" style="float: right;"/>	
                            </div>	<br />			
    					</div>
				    </form> 
                </div>
                <div class="tab-pane <?php if(isset($_GET['dim'])&& $_GET['dim']=='4'){?>active<?php }?>" id="tab_d">
                     <form name="frm" method="POST" enctype="multipart/form-data">
                        <div class="panel-heading">
                            <h2 class="panel-title" style="color:#ef7623;"><i class="fa fa-pencil-square-o"></i> DAZL Commissions</h2>
    					</div>
                        <br />
    					<div class="col-md-12">				
    						<div class="row">
    							<div class="col-md-6 form-group">
    								<label>UserName</label>
    								<input type="text" name="uname" class="form-control" value="<?php echo $uname;?>" />
    							</div>		
    							<div class="col-md-6 form-group">
    								<label>Password</label>
    								<input type="text" name="password" class="form-control" value="<?php echo $instance->decryptor($password); ?>" />
    							</div>	
                            </div>
                            <div class="row"> 
                                <div class="col-md-6 form-group">
                                    <label>Exclude Non-Commissionable Trade Activity </label>
                                </div>
                                <div class="col-md-6 form-group">
                                    <input type="checkbox" name="trade_activity" value="1" onclick="chk_all_class(this.checked)" <?php if($trade_activity>0){ echo "checked='checked'";}?> class="checkbox" />
                                </div>
                            </div>
                            <div class="row"> 
                                <div class="col-md-6 form-group">
                                    <label>Add Clients if Not Found </label>
                                </div>
                                <div class="col-md-6 form-group">
                                    <input type="checkbox" name="add_client" value="1" onclick="chk_all_class(this.checked)" <?php if($add_client>0){ echo "checked='checked'";}?> class="checkbox" />
                                </div>
                            </div>	
                            <div class="row"> 
                                <div class="col-md-6 form-group">
                                    <label>Update Existing Clients </label>
                                </div>
                                <div class="col-md-6 form-group">
                                    <input type="checkbox" name="update_client" value="1" onclick="chk_all_class(this.checked)" <?php if($update_client>0){ echo "checked='checked'";}?> class="checkbox" />
                                </div>
                            </div>
                            <div class="row"> 
                                <div class="col-md-6 form-group">
                                    <label>Local Folder </label>
                                </div>
                                <div class="col-md-6 form-group">
                                    <input type="file" name="local_folder" class="form-control" />
                                    <!--input type="file" name="file1"   /-->
                                </div>
                            </div>	
                            <div class="selectwrap">
                                <input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
                                <input type="hidden" name="dim_id" id="dim_id" value="4" />
                                <input type="hidden" name="is_authorized" id="is_authorized" value="1" />
            					<input type="button" name="clear" value="Clear" style="float: right;"/>
                                <input type="submit" onclick="waitingDialog.show();" name="submit" value="Save" style="float: right;"/>	
                            </div>				
    					</div>
				    </form> 
                </div>
                <div class="tab-pane <?php if(isset($_GET['dim'])&& $_GET['dim']=='5'){?>active<?php }?>" id="tab_e">
                    <form name="frm" method="POST" enctype="multipart/form-data">
                        <div class="panel-heading">
                            <h2 class="panel-title" style="color:#ef7623;"><i class="fa fa-pencil-square-o"></i> NFS/Fidelity</h2>
    					</div>
                        <br />
    					<div class="col-md-12" style="background-color: #E9EEF2 !important;">				
    						<div class="row" style="margin-top: 5px;">
                                <div class="col-md-6 form-group">
                                    <label>Exclude Non-Commissionable Trade Activity </label>
                                </div>
                                <div class="col-md-6 form-group">
                                    <input type="checkbox" name="trade_activity" value="1" onclick="chk_all_class(this.checked)" <?php if($trade_activity>0){ echo "checked='checked'";}?> class="checkbox" />
                                </div>
                            </div>
                            <div class="row"> 
                                <div class="col-md-6 form-group">
                                    <label>Add Clients if Not Found </label>
                                </div>
                                <div class="col-md-6 form-group">
                                    <input type="checkbox" name="add_client" value="1" onclick="chk_all_class(this.checked)" <?php if($add_client>0){ echo "checked='checked'";}?> class="checkbox" />
                                </div>
                            </div>	
                            <div class="row"> 
                                <div class="col-md-6 form-group">
                                    <label>Update Existing Clients </label>
                                </div>
                                <div class="col-md-6 form-group">
                                    <input type="checkbox" name="update_client" value="1" onclick="chk_all_class(this.checked)" <?php if($update_client>0){ echo "checked='checked'";}?> class="checkbox" />
                                </div>
                            </div>
                            <div class="row"> 
                                <div class="col-md-6 form-group">
                                    <label>Local Folder </label>
                                </div>
                                <div class="col-md-6 form-group">
                                    <input type="file" name="local_folder" class="form-control" />
                                    <!--input type="file" name="file1"   /-->
                                </div>
                            </div>	
                            <div class="selectwrap">
                                <input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
                                <input type="hidden" name="dim_id" id="dim_id" value="5" />
                                <input type="hidden" name="is_authorized" id="is_authorized" value="0" />
            					<input type="button" name="clear" value="Clear" style="float: right;" />
                                <input type="submit" onclick="waitingDialog.show();" name="submit" value="Save" style="float: right;"/>	
                            </div><br />				
    					</div>
				    </form> 
                </div>
                <div class="tab-pane <?php if(isset($_GET['dim'])&& $_GET['dim']=='6'){?>active<?php }?>" id="tab_f">
                     <form name="frm" method="POST" enctype="multipart/form-data">
                        <div class="panel-heading">
                            <h2 class="panel-title" style="color:#ef7623;"><i class="fa fa-pencil-square-o"></i> Pershing</h2>
    					</div>
                        <br />
    					<div class="col-md-12">				
                            <div class="row"> 
                                <div class="col-md-6 form-group">
                                    <label>Exclude Non-Commissionable Trade Activity </label>
                                </div>
                                <div class="col-md-6 form-group">
                                    <input type="checkbox" name="trade_activity" onclick="chk_all_class(this.checked)" value="1" <?php if($trade_activity>0){ echo "checked='checked'";}?> class="checkbox" />
                                </div>
                            </div>
                            <div class="row"> 
                                <div class="col-md-6 form-group">
                                    <label>Add Clients if Not Found </label>
                                </div>
                                <div class="col-md-6 form-group">
                                    <input type="checkbox" name="add_client" onclick="chk_all_class(this.checked)" value="1" <?php if($add_client>0){ echo "checked='checked'";}?> class="checkbox" />
                                </div>
                            </div>	
                            <div class="row"> 
                                <div class="col-md-6 form-group">
                                    <label>Update Existing Clients </label>
                                </div>
                                <div class="col-md-6 form-group">
                                    <input type="checkbox" name="update_client" onclick="chk_all_class(this.checked)" value="1" <?php if($update_client>0){ echo "checked='checked'";}?> class="checkbox" />
                                </div>
                            </div>
                            <div class="row"> 
                                <div class="col-md-6 form-group">
                                    <label>Local Folder </label>
                                </div>
                                <div class="col-md-6 form-group">
                                    <input type="file" name="local_folder" class="form-control"  />
                                    <!--input type="file" name="file1"   /-->
                                </div>
                            </div>	
                            <div class="selectwrap">
                                <input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
                                <input type="hidden" name="dim_id" id="dim_id" value="6" />
                                <input type="hidden" name="is_authorized" id="is_authorized" value="0" />
            					<input type="button" name="clear" value="Clear" style="float: right;"/>
                                <input type="submit" onclick="waitingDialog.show();" name="submit" value="Save" style="float: right;"/>	
                            </div>								
    					</div>
				    </form> 
                </div>
                <div class="tab-pane <?php if(isset($_GET['dim'])&& $_GET['dim']=='7'){?>active<?php }?>" id="tab_g">
                     <form name="frm" method="POST" enctype="multipart/form-data">
                        <div class="panel-heading">
                            <h2 class="panel-title" style="color:#ef7623;"><i class="fa fa-pencil-square-o"></i> Raymond James</h2>
    					</div>
                        <br />
    					<div class="col-md-12" style="background-color: #E9EEF2 !important;">				
    						<div class="row" style="margin-top: 5px;"> 
                                <div class="col-md-6 form-group">
                                    <label>Exclude Non-Commissionable Trade Activity </label>
                                </div>
                                <div class="col-md-6 form-group">
                                    <input type="checkbox" name="trade_activity" onclick="chk_all_class(this.checked)" value="1" <?php if($trade_activity>0){ echo "checked='checked'";}?> class="checkbox" />
                                </div>
                            </div>
                            <div class="row"> 
                                <div class="col-md-6 form-group">
                                    <label>Add Clients if Not Found </label>
                                </div>
                                <div class="col-md-6 form-group">
                                    <input type="checkbox" name="add_client" onclick="chk_all_class(this.checked)" value="1" <?php if($add_client>0){ echo "checked='checked'";}?> class="checkbox" />
                                </div>
                            </div>	
                            <div class="row"> 
                                <div class="col-md-6 form-group">
                                    <label>Update Existing Clients </label>
                                </div>
                                <div class="col-md-6 form-group">
                                    <input type="checkbox" name="update_client" onclick="chk_all_class(this.checked)" value="1" <?php if($update_client>0){ echo "checked='checked'";}?> class="checkbox" />
                                </div>
                            </div>
                            <div class="row"> 
                                <div class="col-md-6 form-group">
                                    <label>Local Folder </label>
                                </div>
                                <div class="col-md-6 form-group">
                                    <input type="file" name="local_folder" class="form-control" />
                                    <!--input type="file" name="file1"   /-->
                                </div>
                            </div>	
                            <div class="selectwrap">
                                <input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
                                <input type="hidden" name="dim_id" id="dim_id" value="7" />
                                <input type="hidden" name="is_authorized" id="is_authorized" value="0" />
            					<input type="button" name="clear" value="Clear" style="float: right;"/>
                                <input type="submit" onclick="waitingDialog.show();" name="submit" value="Save" style="float: right;"/>	
                            </div><br />								
    					</div>
				    </form> 
                </div>
                <div class="tab-pane <?php if(isset($_GET['dim'])&& $_GET['dim']=='8'){?>active<?php }?>" id="tab_h">
                     <form name="frm" method="POST" enctype="multipart/form-data">
                        <div class="panel-heading">
                            <h2 class="panel-title" style="color:#ef7623;"><i class="fa fa-pencil-square-o"></i> RBC Dain</h2>
    					</div>
                        <br />
    					<div class="col-md-12">			
                            <div class="row"> 
                                <div class="col-md-6 form-group">
                                    <label>Exclude Non-Commissionable Trade Activity </label>
                                </div>
                                <div class="col-md-6 form-group">
                                    <input type="checkbox" name="trade_activity" onclick="chk_all_class(this.checked)" value="1" <?php if($trade_activity>0){ echo "checked='checked'";}?> class="checkbox" />
                                </div>
                            </div>
                            <div class="row"> 
                                <div class="col-md-6 form-group">
                                    <label>Add Clients if Not Found </label>
                                </div>
                                <div class="col-md-6 form-group">
                                    <input type="checkbox" name="add_client" onclick="chk_all_class(this.checked)" value="1" <?php if($add_client>0){ echo "checked='checked'";}?> class="checkbox" />
                                </div>
                            </div>	
                            <div class="row"> 
                                <div class="col-md-6 form-group">
                                    <label>Update Existing Clients </label>
                                </div>
                                <div class="col-md-6 form-group">
                                    <input type="checkbox" name="update_client" onclick="chk_all_class(this.checked)" value="1" <?php if($update_client>0){ echo "checked='checked'";}?> class="checkbox" />
                                </div>
                            </div>
                            <div class="row"> 
                                <div class="col-md-6 form-group">
                                    <label>Local Folder </label>
                                </div>
                                <div class="col-md-6 form-group">
                                    <input type="file" name="local_folder" class="form-control"/>
                                    <!--input type="file" name="file1"   /-->
                                </div>
                            </div>	
                            <div class="selectwrap">
                                <input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
                                <input type="hidden" name="dim_id" id="dim_id" value="8" />
                                <input type="hidden" name="is_authorized" id="is_authorized" value="0" />
            					<input type="button" name="clear" value="Clear" style="float: right;"/>
                                <input type="submit" onclick="waitingDialog.show();" name="submit" value="Save" style="float: right;"/>	
                            </div>				
    					</div>
				    </form> 
                </div>
        </div>
   </div>
</div>

<script type="text/javascript">

var waitingDialog = waitingDialog || (function ($) {
    'use strict';

	// Creating modal dialog's DOM
	var $dialog = $(
		'<div class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true" style="padding-top:15%; overflow-y:visible;">' +
		'<div class="modal-dialog modal-m">' +
		'<div class="modal-content">' +
			'<div class="modal-header"><h3 style="margin:0;"></h3></div>' +
			'<div class="modal-body">' +
				'<div class="progress progress-striped active" style="margin-bottom:0;"><div class="progress-bar" style="width: 100%"></div></div>' +
			'</div>' +
		'</div></div></div>');

	return {
		/**
		 * Opens our dialog
		 * @param message Custom message
		 * @param options Custom options:
		 * 				  options.dialogSize - bootstrap postfix for dialog size, e.g. "md", "m";
		 * 				  options.progressType - bootstrap postfix for progress bar type, e.g. "success", "warning".
		 */
		show: function (message, options) {
			// Assigning defaults
			if (typeof options === 'undefined') {
				options = {};
			}
			if (typeof message === 'undefined') {
				message = 'Saving...';
			}
			var settings = $.extend({
				dialogSize: 'm',
				progressType: '',
				onHide: null // This callback runs after the dialog was hidden
			}, options);

			// Configuring dialog
			$dialog.find('.modal-dialog').attr('class', 'modal-dialog').addClass('modal-' + settings.dialogSize);
			$dialog.find('.progress-bar').attr('class', 'progress-bar');
			if (settings.progressType) {
				$dialog.find('.progress-bar').addClass('progress-bar-' + settings.progressType);
			}
			$dialog.find('h3').text(message);
			// Adding callbacks
			if (typeof settings.onHide === 'function') {
				$dialog.off('hidden.bs.modal').on('hidden.bs.modal', function (e) {
					settings.onHide.call($dialog);
				});
			}
			// Opening dialog
			$dialog.modal();
		},
		/**
		 * Closes dialog
		 */
	
	};

})(jQuery);

</script>