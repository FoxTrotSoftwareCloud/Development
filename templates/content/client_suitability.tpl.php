<div class="container">
<h1>Client Suitability</h1>
<div class="col-lg-12 well">
<?php require_once(DIR_FS_INCLUDES."alerts.php"); ?>
        <ul class="nav nav-pills nav-stacked col-md-2">
          <li <?php if((isset($_GET['action'])&& $_GET['action']=='add_new_objective') || (isset($_GET['action'])&& $_GET['action']=='view_objective') || (isset($_GET['action'])&& $_GET['action']=='edit_objective')){ ?> class="active"<?php }if($action=='view_objective'){?>class="active" <?php }?>><a href="client_suitability.php?action=view_objective" >Objective</a></li>
          <li <?php if((isset($_GET['action'])&& $_GET['action']=='add_new_income') || (isset($_GET['action'])&& $_GET['action']=='view_income') || (isset($_GET['action'])&& $_GET['action']=='edit_income')){ ?> class="active"<?php }if($action=='view_income'){?>class="active" <?php }?>><a href="client_suitability.php?action=view_income" >Income</a></li>
          <li <?php if((isset($_GET['action'])&& $_GET['action']=='add_new_horizon') || (isset($_GET['action'])&& $_GET['action']=='view_horizon') || (isset($_GET['action'])&& $_GET['action']=='edit_horizon')){ ?> class="active"<?php }?>><a href="client_suitability.php?action=view_horizon" >Goal Horizon</a></li>
          <li <?php if((isset($_GET['action'])&& $_GET['action']=='add_new_networth') || (isset($_GET['action'])&& $_GET['action']=='view_networth') || (isset($_GET['action'])&& $_GET['action']=='edit_networth')){ ?> class="active"<?php }?>><a href="client_suitability.php?action=view_networth">Net Worth</a></li>
          <li <?php if((isset($_GET['action'])&& $_GET['action']=='add_new_risk_tolerance') || (isset($_GET['action'])&& $_GET['action']=='view_risk_tolerance') || (isset($_GET['action'])&& $_GET['action']=='edit_risk_tolerance')){ ?> class="active"<?php }?>><a href="client_suitability.php?action=view_risk_tolerance">Risk Tolerance</a></li>
          <li <?php if((isset($_GET['action'])&& $_GET['action']=='add_new_annual_expenses') ||(isset($_GET['action'])&& $_GET['action']=='view_annual_expenses') || (isset($_GET['action'])&& $_GET['action']=='edit_annual_expenses')){ ?> class="active"<?php }?>><a href="client_suitability.php?action=view_annual_expenses">Annual Expenses</a></li>
          <li <?php if((isset($_GET['action'])&& $_GET['action']=='add_new_liqudity_needs') ||(isset($_GET['action'])&& $_GET['action']=='view_liqudity_needs') || (isset($_GET['action'])&& $_GET['action']=='edit_liqudity_needs')){ ?> class="active"<?php }?>><a href="client_suitability.php?action=view_liqudity_needs">Liquidity Needs</a></li>
          <li <?php if((isset($_GET['action'])&& $_GET['action']=='add_new_liquid_net_worth') ||(isset($_GET['action'])&& $_GET['action']=='view_liquid_net_worth') || (isset($_GET['action'])&& $_GET['action']=='edit_liquid_net_worth')){ ?> class="active"<?php }?>><a href="client_suitability.php?action=view_liquid_net_worth">Liquid Net Worth</a></li>
          <li <?php if((isset($_GET['action'])&& $_GET['action']=='add_new_special_expenses') ||(isset($_GET['action'])&& $_GET['action']=='view_special_expenses') || (isset($_GET['action'])&& $_GET['action']=='edit_special_expenses')){ ?> class="active"<?php }?>><a href="client_suitability.php?action=view_special_expenses">Special Expenses</a></li>
          <li <?php if((isset($_GET['action'])&& $_GET['action']=='add_new_portfolio') ||(isset($_GET['action'])&& $_GET['action']=='view_portfolio') || (isset($_GET['action'])&& $_GET['action']=='edit_portfolio')){ ?> class="active"<?php }?>><a href="client_suitability.php?action=view_portfolio">% of Portfolio</a></li>
          <li <?php if((isset($_GET['action'])&& $_GET['action']=='add_new_time_for_exp') ||(isset($_GET['action'])&& $_GET['action']=='view_time_for_exp') || (isset($_GET['action'])&& $_GET['action']=='edit_time_for_exp')){ ?> class="active"<?php }?>><a href="client_suitability.php?action=view_time_for_exp">Timeframe for Special Exp</a></li>
          <li <?php if((isset($_GET['action'])&& $_GET['action']=='add_new_account_use') ||(isset($_GET['action'])&& $_GET['action']=='view_account_use') || (isset($_GET['action'])&& $_GET['action']=='edit_account_use')){ ?> class="active"<?php }?>><a href="client_suitability.php?action=view_account_use">Account Use</a></li>
        </ul>
        <div class="tab-content col-md-10">
                <div class="tab-pane <?php if((isset($_GET['action'])&& $_GET['action']=='add_new_objective') || (isset($_GET['action'])&& $_GET['action']=='view_objective')|| $action=='view_objective' || (isset($_GET['action'])&& $_GET['action']=='edit_objective')){?>active<?php }?>" id="tab_l">
                    
                    <?php 
                        if($action=='add_new_objective' || ($action=='edit_objective' && $id>0)){
                            ?>
                            <form method="post" enctype="multipart/form-data">
                                <div class="panel-overlay-wrap">
                                    <div class="panel">
                    					<div class="panel-heading">
                                            <div class="panel-control" style="float: right;">
                    							<div class="btn-group dropdown">
                    								<button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>
                    								<ul class="dropdown-menu dropdown-menu-right" style="">
                    									<li><a href="<?php echo CURRENT_PAGE; ?>?action=view_objective"><i class="fa fa-eye"></i> View List</a></li>
                    								</ul>
                    			 				</div>
                    						</div>
                                            <h3 class="panel-title"><i class="fa fa-pencil-square-o"></i> <?php echo $action=='add_new_objective'?'Add':'Edit'; ?> Client Suitability Objective</h3>
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Option <span class="text-red">*</span></label>
                                                            <input type="text" name="option" id="type" value="<?php if($action=='edit_objective'){echo $option ;} ?>" class="form-control" />
                                                        </div>
                                                    </div>
                                               </div>
                                            </div>
                                            <div class="panel-overlay">
                                                <div class="panel-overlay-content pad-all unselectable"><span class="panel-overlay-icon text-dark"><i class="demo-psi-repeat-2 spin-anim icon-2x"></i></span><h4 class="panel-overlay-title"></h4><p></p></div>
                                            </div>
                                            
                                            <div class="panel-footer">
                                                <div class="selectwrap">
                                                    <label></label>
                                                    <input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
                                                    <a href="<?php echo CURRENT_PAGE;?>?action=view_objective"><input type="button" name="cancel" value="Cancel" style="float: right;" /></a>
                                					<input type="submit"  onclick="waitingDialog.show();" name="submit_objective" value="Save" style="float: right;"/>	
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                                            
                           <?php } if(isset($_GET['action'])=='' || $_GET['action']=='view_objective' || $action=='view_objective' ){ ?> 
                        <div class="panel">
                            <!--<div class="panel-heading">
                                <div class="panel-control">
                                    <div class="btn-group dropdown" style="float: right;">
                    					<button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>
                    					<ul class="dropdown-menu dropdown-menu-right" style="">
                    						<li><a href="<?php echo CURRENT_PAGE; ?>?action=add_new_objective"><i class="fa fa-plus"></i> Add New</a></li>
                    					</ul>
                    				</div>
                    			</div>
                                <h3 class="panel-title">Objective</h3>
                    		</div>-->
                            <div class="panel-body">
                                <div class="table-responsive" id="register_data">
                        			<table id="data-table1" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        	            <thead>
                        	                <tr>
                                                <th>OPTIONS</th>
                                                <th class="text-center">ACTION</th>
                                            </tr>
                        	            </thead>
                        	            <tbody>
                        	                <?php 
                                                $count=0  ;
                                                foreach($return_objective as $key=>$val){
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $val['option']; ?></td>
                                                        <td class="text-center">
                                                            <a href="<?php echo CURRENT_PAGE; ?>?action=edit_objective&id=<?php echo $val['id']; ?>" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</a>
                                                            <a onclick="return conf('<?php echo CURRENT_PAGE; ?>?action=delete_objective&id=<?php echo $val['id']; ?>');" class="btn btn-sm btn-danger confirm"><i class="fa fa-trash"></i> delete</a>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                                
                                            ?>
                                        </tbody>
                                    </table>
                                    </div>
                        		</div>
                          </div>
                          <?php  }?>
                </div>
                <div class="tab-pane <?php if((isset($_GET['action'])&& $_GET['action']=='add_new_income') || (isset($_GET['action'])&& $_GET['action']=='view_income')|| $action=='view_income' || (isset($_GET['action'])&& $_GET['action']=='edit_income')){?>active<?php }?>" id="tab_a">
                    
                    <?php 
                        if($action=='add_new_income' || ($action=='edit_income' && $id>0)){
                            ?>
                            <form method="post" enctype="multipart/form-data">
                                <div class="panel-overlay-wrap">
                                    <div class="panel">
                    					<div class="panel-heading">
                                            <div class="panel-control" style="float: right;">
                    							<div class="btn-group dropdown">
                    								<button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>
                    								<ul class="dropdown-menu dropdown-menu-right" style="">
                    									<li><a href="<?php echo CURRENT_PAGE; ?>?action=view_income"><i class="fa fa-eye"></i> View List</a></li>
                    								</ul>
                    			 				</div>
                    						</div>
                                            <h3 class="panel-title"><i class="fa fa-pencil-square-o"></i> <?php echo $action=='add_new_income'?'Add':'Edit'; ?> Client Suitability Income</h3>
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Option <span class="text-red">*</span></label>
                                                            <input type="text" name="option" id="type" value="<?php if($action=='edit_income'){echo $option ;} ?>" class="form-control" />
                                                        </div>
                                                    </div>
                                               </div>
                                            </div>
                                            <div class="panel-overlay">
                                                <div class="panel-overlay-content pad-all unselectable"><span class="panel-overlay-icon text-dark"><i class="demo-psi-repeat-2 spin-anim icon-2x"></i></span><h4 class="panel-overlay-title"></h4><p></p></div>
                                            </div>
                                            
                                            <div class="panel-footer">
                                                <div class="selectwrap">
                                                    <label></label>
                                                    <input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
                                					<a href="<?php echo CURRENT_PAGE;?>?action=view_income"><input type="button" name="cancel" value="Cancel" style="float: right;"/></a>
                                                    <input type="submit"  onclick="waitingDialog.show();" name="submit_income" value="Save" style="float: right;"/>	
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                                            
                           <?php } if(isset($_GET['action'])=='' || $_GET['action']=='view_income' || $action=='view_income' ){ ?> 
                        <div class="panel">
                            <!--<div class="panel-heading">
                                <div class="panel-control">
                                    <div class="btn-group dropdown" style="float: right;">
                    					<button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>
                    					<ul class="dropdown-menu dropdown-menu-right" style="">
                    						<li><a href="<?php echo CURRENT_PAGE; ?>?action=add_new_income"><i class="fa fa-plus"></i> Add New</a></li>
                    					</ul>
                    				</div>
                    			</div>
                                <h3 class="panel-title">Income</h3>
                    		</div>-->
                            <div class="panel-body">
                                <div class="table-responsive" id="register_data">
                        			<table id="data-table2" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        	            <thead>
                        	                <tr>
                                                <th>OPTIONS</th>
                                                <th class="text-center">ACTION</th>
                                            </tr>
                        	            </thead>
                        	            <tbody>
                        	                <?php 
                                                $count=0  ;
                                                foreach($return_income as $key=>$val){
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $val['option']; ?></td>
                                                        <td class="text-center">
                                                            <a href="<?php echo CURRENT_PAGE; ?>?action=edit_income&id=<?php echo $val['id']; ?>" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</a>
                                                            <a onclick="return conf('<?php echo CURRENT_PAGE; ?>?action=delete_income&id=<?php echo $val['id']; ?>');" class="btn btn-sm btn-danger confirm"><i class="fa fa-trash"></i> delete</a>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                                
                                            ?>
                                        </tbody>
                                    </table>
                                    </div>
                        		</div>
                          </div>
                          <?php  }?>
                </div>
                <div class="tab-pane <?php if((isset($_GET['action'])&& $_GET['action']=='add_new_horizon') || (isset($_GET['action'])&& $_GET['action']=='view_horizon') || (isset($_GET['action'])&& $_GET['action']=='edit_horizon')){?>active<?php }?>" id="tab_b">
                     <?php
                        if($action=='add_new_horizon' || ($action=='edit_horizon' && $id>0)){
                            ?>
                            <form method="post" enctype="multipart/form-data">
                                <div class="panel-overlay-wrap">
                                    <div class="panel">
                    					<div class="panel-heading">
                                            <div class="panel-control" style="float: right;">
                    							<div class="btn-group dropdown">
                    								<button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>
                    								<ul class="dropdown-menu dropdown-menu-right" style="">
                    									<li><a href="<?php echo CURRENT_PAGE; ?>?action=view_horizon"><i class="fa fa-eye"></i> View List</a></li>
                    								</ul>
                    							</div>
                    						</div>
                                            <h3 class="panel-title"><i class="fa fa-pencil-square-o"></i> <?php echo $action=='add_new_horizon'?'Add':'Edit'; ?> Client Suitability Horizon</h3>
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Option <span class="text-red">*</span></label>
                                                            <input type="text" name="option" id="type" value="<?php if($action=='edit_horizon'){echo $option ;} ?>" class="form-control" />
                                                        </div>
                                                    </div>
                                               </div>
                                            </div>
                                            <div class="panel-overlay">
                                                <div class="panel-overlay-content pad-all unselectable"><span class="panel-overlay-icon text-dark"><i class="demo-psi-repeat-2 spin-anim icon-2x"></i></span><h4 class="panel-overlay-title"></h4><p></p></div>
                                            </div>
                                            
                                            <div class="panel-footer">
                                                <div class="selectwrap">
                                                    <label></label>
                                                    <input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
                                					<a href="<?php echo CURRENT_PAGE;?>?action=view_horizon"><input type="button" name="cancel" value="Cancel" style="float: right;"/></a>
                                                    <input type="submit"  onclick="waitingDialog.show();" name="submit_horizon" value="Save" style="float: right;"/>	
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                                            
                           <?php } if(isset($_GET['action'])=='' || $_GET['action']=='view_horizon') { ?> 
                        <div class="panel">
                            <!--<div class="panel-heading">
                                <div class="panel-control">
                                    <div class="btn-group dropdown" style="float: right;">
                    					<button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>
                    					<ul class="dropdown-menu dropdown-menu-right" style="">
                    						<li><a href="<?php echo CURRENT_PAGE; ?>?action=add_new_horizon"><i class="fa fa-plus"></i> Add New</a></li>
                    					</ul>
                    				</div>
                    			</div>
                                <h3 class="panel-title">Horizon</h3>
                    		</div>-->
                            <div class="panel-body">
                                <div class="table-responsive" id="register_data">
                        			<table id="data-table3" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        	            <thead>
                        	                <tr>
                                                <th>OPTIONS</th>
                                                <th class="text-center">ACTION</th>
                                            </tr>
                        	            </thead>
                        	            <tbody>
                        	                <?php 
                                                $count=0  ;
                                                foreach($return_horizon as $key=>$val){
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $val['option']; ?></td>
                                                        <td class="text-center">
                                                            <a href="<?php echo CURRENT_PAGE; ?>?action=edit_horizon&id=<?php echo $val['id']; ?>" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</a>
                                                            <a onclick="return conf('<?php echo CURRENT_PAGE; ?>?action=delete_horizon&id=<?php echo $val['id']; ?>');" class="btn btn-sm btn-danger confirm"><i class="fa fa-trash"></i> delete</a>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                                
                                            ?>
                                        </tbody>
                                    </table>
                                    </div>
                        		</div>
                          </div>
                          <?php  }?>
                </div>
                <div class="tab-pane <?php if((isset($_GET['action'])&& $_GET['action']=='add_new_networth') || (isset($_GET['action'])&& $_GET['action']=='view_networth') || (isset($_GET['action'])&& $_GET['action']=='edit_networth')){?>active<?php }?>" " id="tab_c">
                 <?php 
                        if($action=='add_new_networth' || ($action=='edit_networth' && $id>0)){
                            ?>
                            <form method="post" enctype="multipart/form-data">
                                <div class="panel-overlay-wrap">
                                    <div class="panel">
                    					<div class="panel-heading">
                                            <div class="panel-control" style="float: right;">
                    							<div class="btn-group dropdown">
                    								<button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>
                    								<ul class="dropdown-menu dropdown-menu-right" style="">
                    									<li><a href="<?php echo CURRENT_PAGE; ?>?action=view_networth"><i class="fa fa-eye"></i> View List</a></li>
                    								</ul>
                    			 				</div>
                    						</div>
                                            <h3 class="panel-title"><i class="fa fa-pencil-square-o"></i> <?php echo $action=='add_new_networth'?'Add':'Edit'; ?> Client Suitability Networth</h3>
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Option <span class="text-red">*</span></label>
                                                            <input type="text" name="option" id="type" value="<?php if($action=='edit_networth'){echo $option ;} ?>" class="form-control" />
                                                        </div>
                                                    </div>
                                               </div>
                                            </div>
                                            <div class="panel-overlay">
                                                <div class="panel-overlay-content pad-all unselectable"><span class="panel-overlay-icon text-dark"><i class="demo-psi-repeat-2 spin-anim icon-2x"></i></span><h4 class="panel-overlay-title"></h4><p></p></div>
                                            </div>
                                            
                                            <div class="panel-footer">
                                                <div class="selectwrap">
                                                    <label></label>
                                                    <input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
                                					<a href="<?php echo CURRENT_PAGE;?>?action=view_networth"><input type="button" name="cancel" value="Cancel" style="float: right;" /></a>
                                                    <input type="submit"  onclick="waitingDialog.show();" name="submit_networth" value="Save" style="float: right;"/>	
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                                            
                           <?php } if(isset($_GET['action'])=='' || $_GET['action']=='view_networth') { ?> 
                        <div class="panel">
                            <!--<div class="panel-heading">
                                <div class="panel-control">
                                    <div class="btn-group dropdown" style="float: right;">
                    					<button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>
                    					<ul class="dropdown-menu dropdown-menu-right" style="">
                    						<li><a href="<?php echo CURRENT_PAGE; ?>?action=add_new_networth"><i class="fa fa-plus"></i> Add New</a></li>
                    					</ul>
                    				</div>
                    			</div>
                                <h3 class="panel-title">Networth</h3>
                    		</div>-->
                            <div class="panel-body">
                                <div class="table-responsive" id="register_data">
                        			<table id="data-table4" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        	            <thead>
                        	                <tr>
                                                <th>OPTIONS</th>
                                                <th class="text-center">ACTION</th>
                                            </tr>
                        	            </thead>
                        	            <tbody>
                        	                <?php 
                                                $count=0  ;
                                                foreach($return_networth as $key=>$val){
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $val['option']; ?></td>
                                                        <td class="text-center">
                                                            <a href="<?php echo CURRENT_PAGE; ?>?action=edit_networth&id=<?php echo $val['id']; ?>" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</a>
                                                            <a onclick="return conf('<?php echo CURRENT_PAGE; ?>?action=delete_networth&id=<?php echo $val['id']; ?>');" class="btn btn-sm btn-danger confirm"><i class="fa fa-trash"></i> delete</a>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                                
                                            ?>
                                        </tbody>
                                    </table>
                                    </div>
                        		</div>
                          </div>
                          <?php  }?>
                </div>
                <div class="tab-pane <?php if((isset($_GET['action'])&& $_GET['action']=='add_new_risk_tolerance') || (isset($_GET['action'])&& $_GET['action']=='view_risk_tolerance') || (isset($_GET['action'])&& $_GET['action']=='edit_risk_tolerance')){?>active<?php }?>" id="tab_d">
                <?php 
                        if($action=='add_new_risk_tolerance' || ($action=='edit_risk_tolerance' && $id>0)){
                            ?>
                            <form method="post" enctype="multipart/form-data">
                                <div class="panel-overlay-wrap">
                                    <div class="panel">
                    					<div class="panel-heading">
                                            <div class="panel-control" style="float: right;">
                    							<div class="btn-group dropdown">
                    								<button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>
                    								<ul class="dropdown-menu dropdown-menu-right" style="">
                    									<li><a href="<?php echo CURRENT_PAGE; ?>?action=view_risk_tolerance"><i class="fa fa-eye"></i> View List</a></li>
                    								</ul>
                    			 				</div>
                    						</div>
                                            <h3 class="panel-title"><i class="fa fa-pencil-square-o"></i> <?php echo $action=='add_new_risk_tolerance'?'Add':'Edit'; ?> Client Suitability Risk Tolerance</h3>
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Option <span class="text-red">*</span></label>
                                                            <input type="text" name="option" id="type" value="<?php if($action=='edit_risk_tolerance'){echo $option ;} ?>" class="form-control" />
                                                        </div>
                                                    </div>
                                               </div>
                                            </div>
                                            <div class="panel-overlay">
                                                <div class="panel-overlay-content pad-all unselectable"><span class="panel-overlay-icon text-dark"><i class="demo-psi-repeat-2 spin-anim icon-2x"></i></span><h4 class="panel-overlay-title"></h4><p></p></div>
                                            </div>
                                            
                                            <div class="panel-footer">
                                                <div class="selectwrap">
                                                    <label></label>
                                                    <input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
                                					<a href="<?php echo CURRENT_PAGE;?>?action=view_risk_tolerance"><input type="button" name="cancel" value="Cancel" style="float:right;"/></a>
                                                    <input type="submit"  onclick="waitingDialog.show();" name="submit_risk_tolerance" value="Save" style="float:right;"/>	
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                                            
                           <?php } if(isset($_GET['action'])=='' || $_GET['action']=='view_risk_tolerance') { ?> 
                        <div class="panel">
                            <!--<div class="panel-heading">
                                <div class="panel-control">
                                    <div class="btn-group dropdown" style="float: right;">
                    					<button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>
                    					<ul class="dropdown-menu dropdown-menu-right" style="">
                    						<li><a href="<?php echo CURRENT_PAGE; ?>?action=add_new_risk_tolerance"><i class="fa fa-plus"></i> Add New</a></li>
                    					</ul>
                    				</div>
                    			</div>
                                <h3 class="panel-title">Risk Tolerance</h3>
                    		</div>-->
                            <div class="panel-body">
                                <div class="table-responsive" id="register_data">
                        			<table id="data-table5" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        	            <thead>
                        	                <tr>
                                                <th>OPTIONS</th>
                                                <th class="text-center">ACTION</th>
                                            </tr>
                        	            </thead>
                        	            <tbody>
                        	                <?php 
                                                $count=0  ;
                                                foreach($return_risk_tolerance as $key=>$val){
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $val['option']; ?></td>
                                                        <td class="text-center">
                                                            <a href="<?php echo CURRENT_PAGE; ?>?action=edit_risk_tolerance&id=<?php echo $val['id']; ?>" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</a>
                                                            <a onclick="return conf('<?php echo CURRENT_PAGE; ?>?action=delete_risk_tolerance&id=<?php echo $val['id']; ?>');" class="btn btn-sm btn-danger confirm"><i class="fa fa-trash"></i> delete</a>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                                
                                            ?>
                                        </tbody>
                                    </table>
                                    </div>
                        		</div>
                          </div>
                          <?php  }?>
                </div>
                <div class="tab-pane <?php if((isset($_GET['action'])&& $_GET['action']=='add_new_annual_expenses') || (isset($_GET['action'])&& $_GET['action']=='view_annual_expenses') || (isset($_GET['action'])&& $_GET['action']=='edit_annual_expenses')){?>active<?php }?>" id="tab_e">
                <?php 
                        if($action=='add_new_annual_expenses' || ($action=='edit_annual_expenses' && $id>0)){
                            ?>
                            <form method="post" enctype="multipart/form-data">
                                <div class="panel-overlay-wrap">
                                    <div class="panel">
                    					<div class="panel-heading">
                                            <div class="panel-control" style="float: right;">
                    							<div class="btn-group dropdown">
                    								<button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>
                    								<ul class="dropdown-menu dropdown-menu-right" style="">
                    									<li><a href="<?php echo CURRENT_PAGE; ?>?action=view_annual_expenses"><i class="fa fa-eye"></i> View List</a></li>
                    								</ul>
                    			 				</div>
                    						</div>
                                            <h3 class="panel-title"><i class="fa fa-pencil-square-o"></i> <?php echo $action=='add_new_annual_expenses'?'Add':'Edit'; ?> Client Suitability Annual Expenses</h3>
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Option <span class="text-red">*</span></label>
                                                            <input type="text" name="option" id="type" value="<?php if($action=='edit_annual_expenses'){echo $option ;} ?>" class="form-control" />
                                                        </div>
                                                    </div>
                                               </div>
                                            </div>
                                            <div class="panel-overlay">
                                                <div class="panel-overlay-content pad-all unselectable"><span class="panel-overlay-icon text-dark"><i class="demo-psi-repeat-2 spin-anim icon-2x"></i></span><h4 class="panel-overlay-title"></h4><p></p></div>
                                            </div>
                                            
                                            <div class="panel-footer">
                                                <div class="selectwrap">
                                                    <label></label>
                                                    <input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
                                					<a href="<?php echo CURRENT_PAGE;?>?action=view_annual_expenses"><input type="button" name="cancel" value="Cancel" style="float: right;" /></a>
                                                    <input type="submit"  onclick="waitingDialog.show();" name="submit_annual_expenses" value="Save" style="float: right;"/>	
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                                            
                           <?php } if(isset($_GET['action'])=='' || $_GET['action']=='view_annual_expenses') { ?> 
                        <div class="panel">
                            <!--<div class="panel-heading">
                                <div class="panel-control">
                                    <div class="btn-group dropdown" style="float: right;">
                    					<button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>
                    					<ul class="dropdown-menu dropdown-menu-right" style="">
                    						<li><a href="<?php echo CURRENT_PAGE; ?>?action=add_new_annual_expenses"><i class="fa fa-plus"></i> Add New</a></li>
                    					</ul>
                    				</div>
                    			</div>
                                <h3 class="panel-title">Annual Expenses</h3>
                    		</div>-->
                            <div class="panel-body">
                                <div class="table-responsive" id="register_data">
                        			<table id="data-table6" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        	            <thead>
                        	                <tr>
                                                <th>OPTIONS</th>
                                                <th class="text-center">ACTION</th>
                                            </tr>
                        	            </thead>
                        	            <tbody>
                        	                <?php 
                                                $count=0  ;
                                                foreach($return_annual_expenses as $key=>$val){
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $val['option']; ?></td>
                                                        <td class="text-center">
                                                            <a href="<?php echo CURRENT_PAGE; ?>?action=edit_annual_expenses&id=<?php echo $val['id']; ?>" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</a>
                                                            <a onclick="return conf('<?php echo CURRENT_PAGE; ?>?action=delete_annual_expenses&id=<?php echo $val['id']; ?>');" class="btn btn-sm btn-danger confirm"><i class="fa fa-trash"></i> delete</a>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                                
                                            ?>
                                        </tbody>
                                    </table>
                                    </div>
                        		</div>
                          </div>
                          <?php  }?>
                </div>
                <div class="tab-pane <?php if((isset($_GET['action'])&& $_GET['action']=='add_new_liqudity_needs') || (isset($_GET['action'])&& $_GET['action']=='view_liqudity_needs') || (isset($_GET['action'])&& $_GET['action']=='edit_liqudity_needs')){?>active<?php }?>" id="tab_f">
                <?php 
                        if($action=='add_new_liqudity_needs' || ($action=='edit_liqudity_needs' && $id>0)){
                            ?>
                            <form method="post" enctype="multipart/form-data">
                                <div class="panel-overlay-wrap">
                                    <div class="panel">
                    					<div class="panel-heading">
                                            <div class="panel-control" style="float: right;">
                    							<div class="btn-group dropdown">
                    								<button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>
                    								<ul class="dropdown-menu dropdown-menu-right" style="">
                    									<li><a href="<?php echo CURRENT_PAGE; ?>?action=view_liqudity_needs"><i class="fa fa-eye"></i> View List</a></li>
                    								</ul>
                    			 				</div>
                    						</div>
                                            <h3 class="panel-title"><i class="fa fa-pencil-square-o"></i> <?php echo $action=='add_new_liqudity_needs'?'Add':'Edit'; ?> Client Suitability Liqudity Needs</h3>
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Option <span class="text-red">*</span></label>
                                                            <input type="text" name="option" id="type" value="<?php if($action=='edit_liqudity_needs'){echo $option ;} ?>" class="form-control" />
                                                        </div>
                                                    </div>
                                               </div>
                                            </div>
                                            <div class="panel-overlay">
                                                <div class="panel-overlay-content pad-all unselectable"><span class="panel-overlay-icon text-dark"><i class="demo-psi-repeat-2 spin-anim icon-2x"></i></span><h4 class="panel-overlay-title"></h4><p></p></div>
                                            </div>
                                            
                                            <div class="panel-footer">
                                                <div class="selectwrap">
                                                    <label></label>
                                                    <input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
                                					<a href="<?php echo CURRENT_PAGE;?>?action=view_liqudity_needs"><input type="button" name="cancel" value="Cancel" style="float: right;"/></a>
                                                    <input type="submit"  onclick="waitingDialog.show();" name="submit_liqudity_needs" value="Save" style="float: right;"/>	
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                                            
                           <?php } if(isset($_GET['action'])=='' || $_GET['action']=='view_liqudity_needs') { ?> 
                        <div class="panel">
                            <!--<div class="panel-heading">
                                <div class="panel-control">
                                    <div class="btn-group dropdown" style="float: right;">
                    					<button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>
                    					<ul class="dropdown-menu dropdown-menu-right" style="">
                    						<li><a href="<?php echo CURRENT_PAGE; ?>?action=add_new_liqudity_needs"><i class="fa fa-plus"></i> Add New</a></li>
                    					</ul>
                    				</div>
                    			</div>
                                <h3 class="panel-title">Liqudity Needs</h3>
                    		</div>-->
                            <div class="panel-body">
                                <div class="table-responsive" id="register_data">
                        			<table id="data-table7" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        	            <thead>
                        	                <tr>
                                                <th>OPTIONS</th>
                                                <th class="text-center">ACTION</th>
                                            </tr>
                        	            </thead>
                        	            <tbody>
                        	                <?php 
                                                $count=0  ;
                                                foreach($return_liqudity_needs as $key=>$val){
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $val['option']; ?></td>
                                                        <td class="text-center">
                                                            <a href="<?php echo CURRENT_PAGE; ?>?action=edit_liqudity_needs&id=<?php echo $val['id']; ?>" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</a>
                                                            <a onclick="return conf('<?php echo CURRENT_PAGE; ?>?action=delete_liqudity_needs&id=<?php echo $val['id']; ?>');" class="btn btn-sm btn-danger confirm"><i class="fa fa-trash"></i> delete</a>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                                
                                            ?>
                                        </tbody>
                                    </table>
                                    </div>
                        		</div>
                          </div>
                          <?php  }?>
                </div>
                <div class="tab-pane <?php if((isset($_GET['action'])&& $_GET['action']=='add_new_liquid_net_worth') || (isset($_GET['action'])&& $_GET['action']=='view_liquid_net_worth') || (isset($_GET['action'])&& $_GET['action']=='edit_liquid_net_worth')){?>active<?php }?>"  id="tab_g">
                <?php 
                        if($action=='add_new_liquid_net_worth' || ($action=='edit_liquid_net_worth' && $id>0)){
                            ?>
                            <form method="post" enctype="multipart/form-data">
                                <div class="panel-overlay-wrap">
                                    <div class="panel">
                    					<div class="panel-heading">
                                            <div class="panel-control" style="float: right;">
                    							<div class="btn-group dropdown">
                    								<button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>
                    								<ul class="dropdown-menu dropdown-menu-right" style="">
                    									<li><a href="<?php echo CURRENT_PAGE; ?>?action=view_liquid_net_worth"><i class="fa fa-eye"></i> View List</a></li>
                    								</ul>
                    			 				</div>
                    						</div>
                                            <h3 class="panel-title"><i class="fa fa-pencil-square-o"></i> <?php echo $action=='add_new_liquid_net_worth'?'Add':'Edit'; ?> Client Suitability Liquid Net Worth</h3>
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Option <span class="text-red">*</span></label>
                                                            <input type="text" name="option" id="type" value="<?php if($action=='edit_liquid_net_worth'){echo $option ;} ?>" class="form-control" />
                                                        </div>
                                                    </div>
                                               </div>
                                            </div>
                                            <div class="panel-overlay">
                                                <div class="panel-overlay-content pad-all unselectable"><span class="panel-overlay-icon text-dark"><i class="demo-psi-repeat-2 spin-anim icon-2x"></i></span><h4 class="panel-overlay-title"></h4><p></p></div>
                                            </div>
                                            
                                            <div class="panel-footer">
                                                <div class="selectwrap">
                                                    <label></label>
                                                    <input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
                                					<a href="<?php echo CURRENT_PAGE;?>?action=view_liquid_net_worth"><input type="button" name="cancel" value="Cancel" style="float: right;" /></a>
                                                    <input type="submit"  onclick="waitingDialog.show();" name="submit_liquid_net_worth" value="Save" style="float: right;" />	
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                                            
                           <?php } if(isset($_GET['action'])=='' || $_GET['action']=='view_liquid_net_worth') { ?> 
                        <div class="panel">
                            <!--<div class="panel-heading">
                                <div class="panel-control">
                                    <div class="btn-group dropdown" style="float: right;">
                    					<button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>
                    					<ul class="dropdown-menu dropdown-menu-right" style="">
                    						<li><a href="<?php echo CURRENT_PAGE; ?>?action=add_new_liquid_net_worth"><i class="fa fa-plus"></i> Add New</a></li>
                    					</ul>
                    				</div>
                    			</div>
                                <h3 class="panel-title">Liqid Net Worth</h3>
                    		</div>-->
                            <div class="panel-body">
                                <div class="table-responsive" id="register_data">
                        			<table id="data-table8" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        	            <thead>
                        	                <tr>
                                                <th>OPTIONS</th>
                                                <th class="text-center">ACTION</th>
                                            </tr>
                        	            </thead>
                        	            <tbody>
                        	                <?php 
                                                $count=0  ;
                                                foreach($return_liquid_net_worth as $key=>$val){
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $val['option']; ?></td>
                                                        <td class="text-center">
                                                            <a href="<?php echo CURRENT_PAGE; ?>?action=edit_liquid_net_worth&id=<?php echo $val['id']; ?>" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</a>
                                                            <a onclick="return conf('<?php echo CURRENT_PAGE; ?>?action=delete_liquid_net_worth&id=<?php echo $val['id']; ?>');" class="btn btn-sm btn-danger confirm"><i class="fa fa-trash"></i> delete</a>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                                
                                            ?>
                                        </tbody>
                                    </table>
                                    </div>
                        		</div>
                          </div>
                          <?php  }?>
                </div>
                <div class="tab-pane <?php if((isset($_GET['action'])&& $_GET['action']=='add_new_special_expenses') || (isset($_GET['action'])&& $_GET['action']=='view_special_expenses') || (isset($_GET['action'])&& $_GET['action']=='edit_special_expenses')){?>active<?php }?>" " id="tab_h">
                <?php 
                        if($action=='add_new_special_expenses' || ($action=='edit_special_expenses' && $id>0)){
                            ?>
                            <form method="post" enctype="multipart/form-data">
                                <div class="panel-overlay-wrap">
                                    <div class="panel">
                    					<div class="panel-heading">
                                            <div class="panel-control" style="float: right;">
                    							<div class="btn-group dropdown">
                    								<button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>
                    								<ul class="dropdown-menu dropdown-menu-right" style="">
                    									<li><a href="<?php echo CURRENT_PAGE; ?>?action=view_special_expenses"><i class="fa fa-eye"></i> View List</a></li>
                    								</ul>
                    			 				</div>
                    						</div>
                                            <h3 class="panel-title"><i class="fa fa-pencil-square-o"></i> <?php echo $action=='add_new_special_expenses'?'Add':'Edit'; ?> Client Suitability Special Expenses</h3>
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Option <span class="text-red">*</span></label>
                                                            <input type="text" name="option" id="type" value="<?php if($action=='edit_special_expenses'){echo $option ;} ?>" class="form-control" />
                                                        </div>
                                                    </div>
                                               </div>
                                            </div>
                                            <div class="panel-overlay">
                                                <div class="panel-overlay-content pad-all unselectable"><span class="panel-overlay-icon text-dark"><i class="demo-psi-repeat-2 spin-anim icon-2x"></i></span><h4 class="panel-overlay-title"></h4><p></p></div>
                                            </div>
                                            
                                            <div class="panel-footer">
                                                <div class="selectwrap">
                                                    <label></label>
                                                    <input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
                                					<a href="<?php echo CURRENT_PAGE;?>?action=view_special_expenses"><input type="button" name="cancel" value="Cancel" style="float: right;" /></a>
                                                    <input type="submit"  onclick="waitingDialog.show();" name="submit_special_expenses" value="Save" style="float: right;"/>	
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                                            
                           <?php } if(isset($_GET['action'])=='' || $_GET['action']=='view_special_expenses') { ?> 
                        <div class="panel">
                            <!--<div class="panel-heading">
                                <div class="panel-control">
                                    <div class="btn-group dropdown" style="float: right;">
                    					<button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>
                    					<ul class="dropdown-menu dropdown-menu-right" style="">
                    						<li><a href="<?php echo CURRENT_PAGE; ?>?action=add_new_special_expenses"><i class="fa fa-plus"></i> Add New</a></li>
                    					</ul>
                    				</div>
                    			</div>
                                <h3 class="panel-title">Special Expenses</h3>
                    		</div>-->
                            <div class="panel-body">
                                <div class="table-responsive" id="register_data">
                        			<table id="data-table9" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        	            <thead>
                        	                <tr>
                                                <th>OPTIONS</th>
                                                <th class="text-center">ACTION</th>
                                            </tr>
                        	            </thead>
                        	            <tbody>
                        	                <?php 
                                                $count=0  ;
                                                foreach($return_special_expenses as $key=>$val){
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $val['option']; ?></td>
                                                        <td class="text-center">
                                                            <a href="<?php echo CURRENT_PAGE; ?>?action=edit_special_expenses&id=<?php echo $val['id']; ?>" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</a>
                                                            <a onclick="return conf('<?php echo CURRENT_PAGE; ?>?action=delete_special_expenses&id=<?php echo $val['id']; ?>');" class="btn btn-sm btn-danger confirm"><i class="fa fa-trash"></i> delete</a>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                                
                                            ?>
                                        </tbody>
                                    </table>
                                    </div>
                        		</div>
                          </div>
                          <?php  }?>
                </div>
                <div class="tab-pane <?php if((isset($_GET['action'])&& $_GET['action']=='add_new_portfolio') || (isset($_GET['action'])&& $_GET['action']=='view_portfolio') || (isset($_GET['action'])&& $_GET['action']=='edit_portfolio')){?>active<?php }?>" id="tab_i">
                <?php 
                        if($action=='add_new_portfolio' || ($action=='edit_portfolio' && $id>0)){
                            ?>
                            <form method="post" enctype="multipart/form-data">
                                <div class="panel-overlay-wrap">
                                    <div class="panel">
                    					<div class="panel-heading">
                                            <div class="panel-control" style="float: right;">
                    							<div class="btn-group dropdown">
                    								<button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>
                    								<ul class="dropdown-menu dropdown-menu-right" style="">
                    									<li><a href="<?php echo CURRENT_PAGE; ?>?action=view_portfolio"><i class="fa fa-eye"></i> View List</a></li>
                    								</ul>
                    			 				</div>
                    						</div>
                                            <h3 class="panel-title"><i class="fa fa-pencil-square-o"></i> <?php echo $action=='add_new_portfolio'?'Add':'Edit'; ?> Client Suitability Portfolio</h3>
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Option <span class="text-red">*</span></label>
                                                            <input type="text" name="option" id="type" value="<?php if($action=='edit_portfolio'){echo $option ;} ?>" class="form-control" />
                                                        </div>
                                                    </div>
                                               </div>
                                            </div>
                                            <div class="panel-overlay">
                                                <div class="panel-overlay-content pad-all unselectable"><span class="panel-overlay-icon text-dark"><i class="demo-psi-repeat-2 spin-anim icon-2x"></i></span><h4 class="panel-overlay-title"></h4><p></p></div>
                                            </div>
                                            
                                            <div class="panel-footer">
                                                <div class="selectwrap">
                                                    <label></label>
                                                    <input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
                                					<a href="<?php echo CURRENT_PAGE;?>?action=view_portfolio"><input type="button" name="cancel" value="Cancel" style="float: right;"/></a>
                                                    <input type="submit"  onclick="waitingDialog.show();" name="submit_portfolio" value="Save" style="float: right;"/>	
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                                            
                           <?php } if(isset($_GET['action'])=='' || $_GET['action']=='view_portfolio') { ?> 
                        <div class="panel">
                            <!--<div class="panel-heading">
                                <div class="panel-control">
                                    <div class="btn-group dropdown" style="float: right;">
                    					<button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>
                    					<ul class="dropdown-menu dropdown-menu-right" style="">
                    						<li><a href="<?php echo CURRENT_PAGE; ?>?action=add_new_portfolio"><i class="fa fa-plus"></i> Add New</a></li>
                    					</ul>
                    				</div>
                    			</div>
                                <h3 class="panel-title">Portfolio</h3>
                    		</div>-->
                            <div class="panel-body">
                                <div class="table-responsive" id="register_data">
                        			<table id="data-table10" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        	            <thead>
                        	                <tr>
                                                <th>OPTIONS</th>
                                                <th class="text-center">ACTION</th>
                                            </tr>
                        	            </thead>
                        	            <tbody>
                        	                <?php 
                                                $count=0  ;
                                                foreach($return_portfolio as $key=>$val){
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $val['option']; ?></td>
                                                        <td class="text-center">
                                                            <a href="<?php echo CURRENT_PAGE; ?>?action=edit_portfolio&id=<?php echo $val['id']; ?>" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</a>
                                                            <a onclick="return conf('<?php echo CURRENT_PAGE; ?>?action=delete_portfolio&id=<?php echo $val['id']; ?>');" class="btn btn-sm btn-danger confirm"><i class="fa fa-trash"></i> delete</a>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                                    </div>
                        		</div>
                          </div>
                          <?php  }?>
                </div>
                <div class="tab-pane <?php if((isset($_GET['action'])&& $_GET['action']=='add_new_time_for_exp') || (isset($_GET['action'])&& $_GET['action']=='view_time_for_exp') || (isset($_GET['action'])&& $_GET['action']=='edit_time_for_exp')){?>active<?php }?>" id="tab_j">
                 <?php 
                    if($action=='add_new_time_for_exp' || ($action=='edit_time_for_exp' && $id>0)){
                        ?>
                        <form method="post" enctype="multipart/form-data">
                            <div class="panel-overlay-wrap">
                                <div class="panel">
                					<div class="panel-heading">
                                        <div class="panel-control" style="float: right;">
                							<div class="btn-group dropdown">
                								<button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>
                								<ul class="dropdown-menu dropdown-menu-right" style="">
                									<li><a href="<?php echo CURRENT_PAGE; ?>?action=view_time_for_exp"><i class="fa fa-eye"></i> View List</a></li>
                								</ul>
                			 				</div>
                						</div>
                                        <h3 class="panel-title"><i class="fa fa-pencil-square-o"></i> <?php echo $action=='add_new_time_for_exp'?'Add':'Edit'; ?> Client Suitability Time for Special Exp</h3>
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Option <span class="text-red">*</span></label>
                                                        <input type="text" name="option" id="type" value="<?php if($action=='edit_time_for_exp'){echo $option ;} ?>" class="form-control" />
                                                    </div>
                                                </div>
                                           </div>
                                        </div>
                                        <div class="panel-overlay">
                                            <div class="panel-overlay-content pad-all unselectable"><span class="panel-overlay-icon text-dark"><i class="demo-psi-repeat-2 spin-anim icon-2x"></i></span><h4 class="panel-overlay-title"></h4><p></p></div>
                                        </div>
                                        
                                        <div class="panel-footer">
                                            <div class="selectwrap">
                                                <label></label>
                                                <input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
                            					<a href="<?php echo CURRENT_PAGE;?>?action=view_time_for_exp"><input type="button" name="cancel" value="Cancel" style="float: right;"/></a>
                                                <input type="submit"  onclick="waitingDialog.show();" name="submit_time_for_exp" value="Save" style="float: right;"/>	
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                                        
                       <?php } if(isset($_GET['action'])=='' || $_GET['action']=='view_time_for_exp') { ?> 
                    <div class="panel">
                        <!--<div class="panel-heading">
                            <div class="panel-control">
                                <div class="btn-group dropdown" style="float: right;">
                					<button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>
                					<ul class="dropdown-menu dropdown-menu-right" style="">
                						<li><a href="<?php echo CURRENT_PAGE; ?>?action=add_new_time_for_exp"><i class="fa fa-plus"></i> Add New</a></li>
                					</ul>
                				</div>
                			</div>
                            <h3 class="panel-title">Timeframe For Special Exp</h3>
                		</div>-->
                        <div class="panel-body">
                            <div class="table-responsive" id="register_data">
                    			<table id="data-table11" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    	            <thead>
                    	                <tr>
                                            <th>OPTIONS</th>
                                            <th class="text-center">ACTION</th>
                                        </tr>
                    	            </thead>
                    	            <tbody>
                    	                <?php 
                                            $count=0  ;
                                            foreach($return_time_for_exp as $key=>$val){
                                                ?>
                                                <tr>
                                                    <td><?php echo $val['option']; ?></td>
                                                    <td class="text-center">
                                                        <a href="<?php echo CURRENT_PAGE; ?>?action=edit_time_for_exp&id=<?php echo $val['id']; ?>" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</a>
                                                        <a onclick="return conf('<?php echo CURRENT_PAGE; ?>?action=delete_time_for_exp&id=<?php echo $val['id']; ?>');" class="btn btn-sm btn-danger confirm"><i class="fa fa-trash"></i> delete</a>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        ?>
                                    </tbody>
                                </table>
                                </div>
                    		</div>
                      </div>
                  <?php  }?>
                </div>
                <div class="tab-pane <?php if((isset($_GET['action'])&& $_GET['action']=='add_new_account_use') || (isset($_GET['action'])&& $_GET['action']=='view_account_use') || (isset($_GET['action'])&& $_GET['action']=='edit_account_use')){?>active<?php }?>" id="tab_k">
                 <?php 
                    if($action=='add_new_account_use' || ($action=='edit_account_use' && $id>0)){
                        ?>
                        <form method="post" enctype="multipart/form-data">
                            <div class="panel-overlay-wrap">
                                <div class="panel">
                					<div class="panel-heading">
                                        <div class="panel-control" style="float: right;">
                							<div class="btn-group dropdown">
                								<button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>
                								<ul class="dropdown-menu dropdown-menu-right" style="">
                									<li><a href="<?php echo CURRENT_PAGE; ?>?action=view_account_use"><i class="fa fa-eye"></i> View List</a></li>
                								</ul>
                			 				</div>
                						</div>
                                        <h3 class="panel-title"><i class="fa fa-pencil-square-o"></i> <?php echo $action=='add_new_account_use'?'Add':'Edit'; ?> Client Suitability Acoount Use</h3>
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Option <span class="text-red">*</span></label>
                                                        <input type="text" name="option" id="type" value="<?php if($action=='edit_account_use'){echo $option ;} ?>" class="form-control" />
                                                    </div>
                                                </div>
                                           </div>
                                        </div>
                                        <div class="panel-overlay">
                                            <div class="panel-overlay-content pad-all unselectable"><span class="panel-overlay-icon text-dark"><i class="demo-psi-repeat-2 spin-anim icon-2x"></i></span><h4 class="panel-overlay-title"></h4><p></p></div>
                                        </div>
                                        
                                        <div class="panel-footer">
                                            <div class="selectwrap">
                                                <label></label>
                                                <input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
                            					<a href="<?php echo CURRENT_PAGE;?>?action=view_account_use"><input type="button" name="cancel" value="Cancel" style="float: right;" /></a>
                                                <input type="submit"  onclick="waitingDialog.show();" name="submit_account_use" value="Save" style="float: right;"/>	
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                                        
                       <?php } if(isset($_GET['action'])=='' || $_GET['action']=='view_account_use') { ?> 
                    <div class="panel">
                        <!--<div class="panel-heading">
                            <div class="panel-control">
                                <div class="btn-group dropdown" style="float: right;">
                					<button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>
                					<ul class="dropdown-menu dropdown-menu-right" style="">
                						<li><a href="<?php echo CURRENT_PAGE; ?>?action=add_new_account_use"><i class="fa fa-plus"></i> Add New</a></li>
                					</ul>
                				</div>
                			</div>
                            <h3 class="panel-title">Account Use</h3>
                		</div>-->
                        <div class="panel-body">
                            <div class="table-responsive" id="register_data">
                    			<table id="data-table12" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    	            <thead>
                    	                <tr>
                                            <th>OPTIONS</th>
                                            <th class="text-center">ACTION</th>
                                        </tr>
                    	            </thead>
                    	            <tbody>
                    	                <?php 
                                            $count=0  ;
                                            foreach($return_account_use as $key=>$val){
                                                ?>
                                                <tr>
                                                    <td><?php echo $val['option']; ?></td>
                                                    <td class="text-center">
                                                        <a href="<?php echo CURRENT_PAGE; ?>?action=edit_account_use&id=<?php echo $val['id']; ?>" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</a>
                                                        <a onclick="return conf('<?php echo CURRENT_PAGE; ?>?action=delete_account_use&id=<?php echo $val['id']; ?>');" class="btn btn-sm btn-danger confirm"><i class="fa fa-trash"></i> delete</a>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        ?>
                                    </tbody>
                                </table>
                                </div>
                    		</div>
                      </div>
                  <?php  }?>
                </div>
        </div>
    </div>
</div>
<style>
.btn-primary {
    color: #fff;
    background-color: #337ab7 !important;
    border-color: #2e6da4 !important;
}
</style>
<script type="text/javascript">
    $(document).ready(function() {
        
        $('#data-table1').DataTable({
        "pageLength": 25,
        "bLengthChange": false,
        "bFilter": true,
        "bInfo": false,
        "bAutoWidth": false,
        "dom": '<"toolbar1">frtip',
        "aoColumnDefs": [{ "bSortable": false, "aTargets": [ 1 ] }, 
                        { "bSearchable": false, "aTargets": [ 1 ] }]
        });
        
        $("div.toolbar1").html('<div class="panel-control">'+
                    '<div class="btn-group dropdown" style="float: right;">'+
                        '<button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>'+
    					'<ul class="dropdown-menu dropdown-menu-right" style="">'+
    						'<li><a href="<?php echo CURRENT_PAGE; ?>?action=add_new_objective"><i class="fa fa-plus"></i> Add New</a></li>'+
                        '</ul>'+
    				'</div>'+
    			'</div>');
                
       $('#data-table2').DataTable({
        "pageLength": 25,
        "bLengthChange": false,
        "bFilter": true,
        "bInfo": false,
        "bAutoWidth": false,
        "dom": '<"toolbar2">frtip',
        "aoColumnDefs": [{ "bSortable": false, "aTargets": [ 1 ] }, 
                        { "bSearchable": false, "aTargets": [ 1 ] }]
        });
        
        $("div.toolbar2").html('<div class="panel-control">'+
                    '<div class="btn-group dropdown" style="float: right;">'+
                        '<button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>'+
    					'<ul class="dropdown-menu dropdown-menu-right" style="">'+
    						'<li><a href="<?php echo CURRENT_PAGE; ?>?action=add_new_income"><i class="fa fa-plus"></i> Add New</a></li>'+
                        '</ul>'+
    				'</div>'+
    			'</div>');
                
       $('#data-table3').DataTable({
        "pageLength": 25,
        "bLengthChange": false,
        "bFilter": true,
        "bInfo": false,
        "bAutoWidth": false,
        "dom": '<"toolbar3">frtip',
        "aoColumnDefs": [{ "bSortable": false, "aTargets": [ 1 ] }, 
                        { "bSearchable": false, "aTargets": [ 1 ] }]
        });
        
        $("div.toolbar3").html('<div class="panel-control">'+
                    '<div class="btn-group dropdown" style="float: right;">'+
                        '<button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>'+
    					'<ul class="dropdown-menu dropdown-menu-right" style="">'+
    						'<li><a href="<?php echo CURRENT_PAGE; ?>?action=add_new_horizon"><i class="fa fa-plus"></i> Add New</a></li>'+
                        '</ul>'+
    				'</div>'+
    			'</div>');
                
       $('#data-table4').DataTable({
        "pageLength": 25,
        "bLengthChange": false,
        "bFilter": true,
        "bInfo": false,
        "bAutoWidth": false,
        "dom": '<"toolbar4">frtip',
        "aoColumnDefs": [{ "bSortable": false, "aTargets": [ 1 ] }, 
                        { "bSearchable": false, "aTargets": [ 1 ] }]
        });
        
        $("div.toolbar4").html('<div class="panel-control">'+
                    '<div class="btn-group dropdown" style="float: right;">'+
                        '<button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>'+
    					'<ul class="dropdown-menu dropdown-menu-right" style="">'+
    						'<li><a href="<?php echo CURRENT_PAGE; ?>?action=add_new_networth"><i class="fa fa-plus"></i> Add New</a></li>'+
                        '</ul>'+
    				'</div>'+
    			'</div>');
                
       $('#data-table5').DataTable({
        "pageLength": 25,
        "bLengthChange": false,
        "bFilter": true,
        "bInfo": false,
        "bAutoWidth": false,
        "dom": '<"toolbar5">frtip',
        "aoColumnDefs": [{ "bSortable": false, "aTargets": [ 1 ] }, 
                        { "bSearchable": false, "aTargets": [ 1 ] }]
        });
        
        $("div.toolbar5").html('<div class="panel-control">'+
                    '<div class="btn-group dropdown" style="float: right;">'+
                        '<button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>'+
    					'<ul class="dropdown-menu dropdown-menu-right" style="">'+
    						'<li><a href="<?php echo CURRENT_PAGE; ?>?action=add_new_risk_tolerance"><i class="fa fa-plus"></i> Add New</a></li>'+
                        '</ul>'+
    				'</div>'+
    			'</div>');
                
       $('#data-table6').DataTable({
        "pageLength": 25,
        "bLengthChange": false,
        "bFilter": true,
        "bInfo": false,
        "bAutoWidth": false,
        "dom": '<"toolbar6">frtip',
        "aoColumnDefs": [{ "bSortable": false, "aTargets": [ 1 ] }, 
                        { "bSearchable": false, "aTargets": [ 1 ] }]
        });
        
        $("div.toolbar6").html('<div class="panel-control">'+
                    '<div class="btn-group dropdown" style="float: right;">'+
                        '<button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>'+
    					'<ul class="dropdown-menu dropdown-menu-right" style="">'+
    						'<li><a href="<?php echo CURRENT_PAGE; ?>?action=add_new_annual_expenses"><i class="fa fa-plus"></i> Add New</a></li>'+
                        '</ul>'+
    				'</div>'+
    			'</div>');
       
       $('#data-table7').DataTable({
        "pageLength": 25,
        "bLengthChange": false,
        "bFilter": true,
        "bInfo": false,
        "bAutoWidth": false,
        "dom": '<"toolbar7">frtip',
        "aoColumnDefs": [{ "bSortable": false, "aTargets": [ 1 ] }, 
                        { "bSearchable": false, "aTargets": [ 1 ] }]
        });
        
        $("div.toolbar7").html('<div class="panel-control">'+
                    '<div class="btn-group dropdown" style="float: right;">'+
                        '<button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>'+
    					'<ul class="dropdown-menu dropdown-menu-right" style="">'+
    						'<li><a href="<?php echo CURRENT_PAGE; ?>?action=add_new_liqudity_needs"><i class="fa fa-plus"></i> Add New</a></li>'+
                        '</ul>'+
    				'</div>'+
    			'</div>');
                
       $('#data-table8').DataTable({
        "pageLength": 25,
        "bLengthChange": false,
        "bFilter": true,
        "bInfo": false,
        "bAutoWidth": false,
        "dom": '<"toolbar8">frtip',
        "aoColumnDefs": [{ "bSortable": false, "aTargets": [ 1 ] }, 
                        { "bSearchable": false, "aTargets": [ 1 ] }]
        });
        
        $("div.toolbar8").html('<div class="panel-control">'+
                    '<div class="btn-group dropdown" style="float: right;">'+
                        '<button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>'+
    					'<ul class="dropdown-menu dropdown-menu-right" style="">'+
    						'<li><a href="<?php echo CURRENT_PAGE; ?>?action=add_new_liquid_net_worth"><i class="fa fa-plus"></i> Add New</a></li>'+
                        '</ul>'+
    				'</div>'+
    			'</div>');
       
       $('#data-table9').DataTable({
        "pageLength": 25,
        "bLengthChange": false,
        "bFilter": true,
        "bInfo": false,
        "bAutoWidth": false,
        "dom": '<"toolbar9">frtip',
        "aoColumnDefs": [{ "bSortable": false, "aTargets": [ 1 ] }, 
                        { "bSearchable": false, "aTargets": [ 1 ] }]
        });
        
        $("div.toolbar9").html('<div class="panel-control">'+
                    '<div class="btn-group dropdown" style="float: right;">'+
                        '<button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>'+
    					'<ul class="dropdown-menu dropdown-menu-right" style="">'+
    						'<li><a href="<?php echo CURRENT_PAGE; ?>?action=add_new_special_expenses"><i class="fa fa-plus"></i> Add New</a></li>'+
                        '</ul>'+
    				'</div>'+
    			'</div>');
                
       $('#data-table10').DataTable({
        "pageLength": 25,
        "bLengthChange": false,
        "bFilter": true,
        "bInfo": false,
        "bAutoWidth": false,
        "dom": '<"toolbar10">frtip',
        "aoColumnDefs": [{ "bSortable": false, "aTargets": [ 1 ] }, 
                        { "bSearchable": false, "aTargets": [ 1 ] }]
        });
        
        $("div.toolbar10").html('<div class="panel-control">'+
                    '<div class="btn-group dropdown" style="float: right;">'+
                        '<button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>'+
    					'<ul class="dropdown-menu dropdown-menu-right" style="">'+
    						'<li><a href="<?php echo CURRENT_PAGE; ?>?action=add_new_portfolio"><i class="fa fa-plus"></i> Add New</a></li>'+
                        '</ul>'+
    				'</div>'+
    			'</div>');
                
       $('#data-table11').DataTable({
        "pageLength": 25,
        "bLengthChange": false,
        "bFilter": true,
        "bInfo": false,
        "bAutoWidth": false,
        "dom": '<"toolbar11">frtip',
        "aoColumnDefs": [{ "bSortable": false, "aTargets": [ 1 ] }, 
                        { "bSearchable": false, "aTargets": [ 1 ] }]
        });
        
        $("div.toolbar11").html('<div class="panel-control">'+
                    '<div class="btn-group dropdown" style="float: right;">'+
                        '<button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>'+
    					'<ul class="dropdown-menu dropdown-menu-right" style="">'+
    						'<li><a href="<?php echo CURRENT_PAGE; ?>?action=add_new_time_for_exp"><i class="fa fa-plus"></i> Add New</a></li>'+
                        '</ul>'+
    				'</div>'+
    			'</div>');
                
       $('#data-table12').DataTable({
        "pageLength": 25,
        "bLengthChange": false,
        "bFilter": true,
        "bInfo": false,
        "bAutoWidth": false,
        "dom": '<"toolbar12">frtip',
        "aoColumnDefs": [{ "bSortable": false, "aTargets": [ 1 ] }, 
                        { "bSearchable": false, "aTargets": [ 1 ] }]
        });
        
        $("div.toolbar12").html('<div class="panel-control">'+
                    '<div class="btn-group dropdown" style="float: right;">'+
                        '<button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>'+
    					'<ul class="dropdown-menu dropdown-menu-right" style="">'+
    						'<li><a href="<?php echo CURRENT_PAGE; ?>?action=add_new_account_use"><i class="fa fa-plus"></i> Add New</a></li>'+
                        '</ul>'+
    				'</div>'+
    			'</div>');
} );
</script>
<style type="text/css">
.toolbar1 {
    float: right;
    padding-left: 5px;
}
.toolbar2 {
    float: right;
    padding-left: 5px;
}
.toolbar3 {
    float: right;
    padding-left: 5px;
}
.toolbar4 {
    float: right;
    padding-left: 5px;
}
.toolbar5 {
    float: right;
    padding-left: 5px;
}
.toolbar6 {
    float: right;
    padding-left: 5px;
}
.toolbar7 {
    float: right;
    padding-left: 5px;
}
.toolbar8 {
    float: right;
    padding-left: 5px;
}
.toolbar9 {
    float: right;
    padding-left: 5px;
}
.toolbar10 {
    float: right;
    padding-left: 5px;
}
.toolbar11 {
    float: right;
    padding-left: 5px;
}
.toolbar12 {
    float: right;
    padding-left: 5px;
}
</style>
<script>
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
		 * 				  options.dialogSize - bootstrap postfix for dialog size, e.g. "sm", "m";
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