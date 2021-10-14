<?php  
  require_once("include/config.php");
require_once(DIR_FS."islogin.php");
    
$instance = new product_master();  
$instance1 = new product_maintenance();
$get_sponsor = $instance1->select_sponsor();
$product_category = $instance1->select_category();
if(isset($_GET['move_cat_id']) && $_GET['move_cat_id']>0){
    $move_cat_id=$_GET['move_cat_id'];
    }
if(isset($_GET['id']) && $_GET['id']>0){ 
$get_product_category = $instance->edit($_GET['id']);


?>
 <form method="post" name="add_new_category_form" id="add_new_category_form" onsubmit="return formsubmit_category();">
        <div class="inputpopup">
            <label>Product Category <span class="text-red">*</span></label>
            <input type="text" name="type" id="type"  value="<?php echo $get_product_category['type']; ?>" class="form-control" maxlength="30" />
        </div>
        
        </div>
        <div class="inputpopup"  style="visibility:hidden;">
            <label>Type Code </label>
            <input type="text" name="type_code" id="type_code" value="<?php echo $get_product_category['type_code']; ?>" class="form-control" maxlength="2" />            
        </div>

        
      
        <div class="col-md-12">
            <div id="msg">
            </div>
        </div>
        <div class="inputpopup">
        <label class="labelblank">&nbsp;</label>
            <input type="hidden" name="id" value="<?php echo $get_product_category['id']; ?>" />
            <input type="hidden" name="submit_account" value="Ok"  />&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="submit" value="Ok" onclick="parent.window.close();" name="submit_product_category" />
        </div>
    </form> 
 <?php }
 else if(isset($_GET['move_cat_id']) && $_GET['move_cat_id']>0)
 {$get_product_category = $instance->edit($_GET['move_cat_id']);
?> <form method="post" name="move_category_form" id="move_category_form" onsubmit="return formsubmit_movecategory();">
     <div class="inputpopup">
            <label>Product Category <span class="text-red">*</span></label>
            <input type="text" name="type" id="type" disabled="true" value="<?php echo $get_product_category['type']; ?>" class="form-control" maxlength="30" />
        </div>
                    <div class="inputpopup">
                        <label>Product Category To Move </label>
                     
                                <select class="form-control" id="to_category"  name="to_category" style="display: inline !important;">
                                    <option value="0" selected="true">Select Category</option>
                                    <?php foreach($product_category as $key=>$val){if($get_product_category['id']!=$val['id']){?>
                                    <option value="<?php echo $val['id'];?>" ><?php echo $val['type'];?></option>
                                    <?php }} ?>
                                </select>
                    </div>
                    <div class="inputpopup">
                    <label class="labelblank">&nbsp;</label>
                        <input type="hidden" name="id" value="<?php echo $get_product_category['id']; ?>" />
                        <input type="hidden" name="submit_move_category" value="Ok"  />&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="submit" value="Ok" onclick="parent.window.close();" name="submit_move_category" />
                    </div>
</form>
 <?php }
  else{ ?>    
    
    
    
    <form method="post" name="add_new_category_form" id="add_new_category_form" onsubmit="return formsubmit_category();">
        <div class="inputpopup">
            <label>Product Category <span class="text-red">*</span></label>
            <input type="text" name="tyipe" id="type"  class="form-control" maxlength="30" />
        </div>
        <div class="inputpopup">
            <label>Sponsor </label>
            <select class="form-control" name="sponsor" id="sponsor">
                <option value="">Select Sponsor</option>
                <?php foreach($get_sponsor as $key=>$val){?>
                <option value="<?php echo $val['id'];?>"><?php echo $val['name'];?></option>
                <?php } ?>
            </select>
        </div>
        <div class="inputpopup">
            <label>Type Code </label>
            <input type="text" name="type_code" id="type_code" class="form-control" maxlength="2" />
            
        </div>
        <div class="col-md-12">
            <div id="msg">
            </div>
        </div>
        <div class="inputpopup">
        <label class="labelblank">&nbsp;</label>
            <input type="hidden" name="id" value="0" />
            <input type="hidden" name="submit_account" value="Ok"  />&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="submit"  value="Ok"  name="submit_product_category" />
        </div>
    </form> 
 <?php } ?>