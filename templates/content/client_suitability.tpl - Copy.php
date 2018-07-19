<div class="container">
<h1>Client Suitability</h1>
    <div class="col-lg-12 well">
        <ul class="nav nav-pills nav-stacked col-md-2">
          <li class="active"><a href="#tab_a" data-toggle="pill">Income</a></li>
          <li><a href="#tab_b" data-toggle="pill">Goal Horizon</a></li>
          <li><a href="#tab_c" data-toggle="pill">Net Worth</a></li>
          <li><a href="#tab_d" data-toggle="pill">Risk Tolerance</a></li>
          <li><a href="#tab_e" data-toggle="pill">Annual Expenses</a></li>
          <li><a href="#tab_f" data-toggle="pill">Liquidity Needs</a></li>
          <li><a href="#tab_g" data-toggle="pill">Liquid Net Worth</a></li>
          <li><a href="#tab_h" data-toggle="pill">Special Expenses</a></li>
          <li><a href="#tab_i" data-toggle="pill">% of Portfolio</a></li>
          <li><a href="#tab_j" data-toggle="pill">Timeframe for Special Exp</a></li>
          <li><a href="#tab_k" data-toggle="pill">Account Use</a></li>
        </ul>
        <div class="tab-content col-md-10">
                <div class="tab-pane active" id="tab_a">
                    <?php
                        if($action=='add_new'|| $action=='import' ||($action=='edit' && $id>0)){
                            ?>
                            <!--form method="post" enctype="multipart/form-data">
                                <div class="panel-overlay-wrap">
                                    <div class="panel">
                    					<div class="panel-heading">
                                            <div class="panel-control" style="float: right;">
                    							<div class="btn-group dropdown">
                    								<button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>
                    								<ul class="dropdown-menu dropdown-menu-right" style="">
                    									<li><a href="<?php echo CURRENT_PAGE; ?>"><i class="fa fa-eye"></i> View List</a></li>
                    								</ul>
                    							</div>
                    						</div>
                                            <?php if($action == 'import'){ ?>
                                                <div class="panel-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Import CSV File: <span class="text-red">*</span></label>
                                                                <input type="file" name="file_csv" id="type"  class="form-control" />
                                                            </div>
                                                        </div>
                                                   </div>
                                                </div>
                                            
                                            <?php } else {?>
                                            <h3 class="panel-title"><i class="ti-pencil-alt"></i> <?php echo $action=='add_new'?'Add':'Edit'; ?> Client Suitability</h3>
                    					</div>
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Option <span class="text-red">*</span></label>
                                                        <input type="text" name="option" id="type" value="<?php echo $type; ?>" class="form-control" />
                                                    </div>
                                                </div>
                                           </div>
                                        </div>
                                        <?php } ?>
                                        <div class="panel-overlay">
                                            <div class="panel-overlay-content pad-all unselectable"><span class="panel-overlay-icon text-dark"><i class="demo-psi-repeat-2 spin-anim icon-2x"></i></span><h4 class="panel-overlay-title"></h4><p></p></div>
                                        </div>
                                        <div class="panel-footer">
                                            <div class="selectwrap">
                                                <input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
                            					<input type="submit" name="submit" value="Save"/>	
                                                <a href="<?php echo CURRENT_PAGE;?>"><input type="button" name="cancel" value="Cancel" /></a>
                                            </div>
                                       </div>
                                    </div>
                                </div>
                            </form>
                            <?php
                        }
                        else{?>
                        <div class="panel">
                            <div class="panel-heading">
                                <div class="panel-control">
                                    <div class="btn-group dropdown" style="float: right;">
                    					<button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>
                    					<ul class="dropdown-menu dropdown-menu-right" style="">
                    						<li><a href="<?php echo CURRENT_PAGE; ?>?action=add_new"><i class="fa fa-plus"></i> Add New</a></li>
                                            <li><a href="<?php echo CURRENT_PAGE; ?>?action=import"><i class="fa fa-plus"></i> Import CSV FILE</a></li>
                    					</ul>
                    				</div>
                    			</div>
                                <h3 class="panel-title">List</h3>
                    		</div>
                            <div class="panel-body">
                                <div class="table-responsive" id="register_data">
                        			<table id="data-table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        	            <thead>
                        	                <tr>
                                                <th class="text-center">#NO</th>
                                                <th>OPTIONS</th>
                                                <th class="text-center">ACTION</th>
                                            </tr>
                        	            </thead>
                        	            <tbody>
                        	                <?php
                                                $count = 0;
                                                foreach($return as $key=>$val){
                                                    ?>
                                                    <tr>
                                                        <td class="text-center"><?php echo ++$count; ?></td>
                                                        <td></td> <?php //echo $val['type']; ?></td>
                                                        <td class="text-center">
                                                            <a href="<?php echo CURRENT_PAGE; ?>?action=edit&id=<?php echo $val['id']; ?>" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</a>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                                    </div>
                        		</div>
                          </div-->
                          <?php } ?>
                          A
                </div>
                <div class="tab-pane" id="tab_b">
                D
                </div>
                <div class="tab-pane" id="tab_c">
                F
                </div>
                <div class="tab-pane" id="tab_d">
                ss
                </div>
                <div class="tab-pane" id="tab_e">
                
                </div>
                <div class="tab-pane" id="tab_f">
                
                </div>
                <div class="tab-pane" id="tab_g">
                
                </div>
                <div class="tab-pane" id="tab_h">
                
                </div>
                <div class="tab-pane" id="tab_i">
                
                </div>
                <div class="tab-pane" id="tab_j">
                
                </div>
                <div class="tab-pane" id="tab_k">
                
                </div>
        </div>
    </div>
</div>
