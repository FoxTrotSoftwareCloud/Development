<?php 
require_once("include/config.php");
require_once(DIR_FS."islogin.php");
$selected = isset($_GET['selected'])?$_GET['selected']:'';    
$instance = new transaction();  
if(isset($_GET['product_category_id']) && $_GET['product_category_id'] > 0)
{
    $category_id = isset($_GET['product_category_id'])?$instance->re_db_input($_GET['product_category_id']):'';
    $sponsor = isset($_GET['sponsor'])?$instance->re_db_input($_GET['sponsor']):'';
    $get_product = $instance->get_product($category_id,$sponsor);
    
?>
    
    console.log("ajax_get_product: product_category_id: " + $_GET['product_category_id'] + ", " + $_GET['sponsor'] + ", selected:" + $_GET['selected']); // TEST only. Delete Me 
    
    <select name="product" class="form-control"  id="product">
        <option value="0">Select Product</option>
        <?php foreach($get_product as $key=>$val){?>
        <option value="<?php echo $val['id'];?>" <?php echo $selected==$val['id']?'selected="selected"':''; ?>><?php echo $val['name'];?></option>
        <?php } ?>
    </select>
<?php
}
?>
    