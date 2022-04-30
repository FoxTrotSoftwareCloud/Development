<?php
require_once("include/config.php");
require_once(DIR_FS."islogin.php"); 

$instance = new payroll();
$instance_broker = new broker_master();
$get_broker = $instance_broker->select();
$instance_multi_company = new manage_company();
$get_multi_company = $instance_multi_company->select_company();
$instance_product_type = new product_master();
$get_product_category =$instance_product_type->select_product_type();

$company = isset($_SESSION['publish_payroll']['company'])?$_SESSION['publish_payroll']['company']:0;
$payroll_id = isset($_SESSION['publish_payroll']['payroll_id'])?$_SESSION['publish_payroll']['payroll_id']:0;
$broker = isset($_SESSION['publish_payroll']['broker'])?$_SESSION['publish_payroll']['broker']:0;
$product_category = isset($_SESSION['publish_payroll']['product_category'])?$_SESSION['publish_payroll']['product_category']:0;
$print_type = isset($_SESSION['publish_payroll']['print_type'])?$_SESSION['publish_payroll']['print_type']:0;
$output = isset($_SESSION['publish_payroll']['output'])?$_SESSION['publish_payroll']['output']:0;

if(isset($_GET['report_name']) && $_GET['report_name'] == '1'){
?>
<div class="titlebox">Payroll Commission Statement</div><br />
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>Company </label>
            <select class="form-control" name="company">
                <option value="0">All Companies</option>
                <?php foreach($get_multi_company as $key=>$val){?>
                <option value="<?php echo $val['id'];?>" <?php echo ($val['id']==$company)?'selected':''?>><?php echo $val['company_name'];?></option>
                <?php } ?>
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>Payroll Date </label>
            <select class="form-control" name="payroll_id">
                <?php $get_payroll_uploads = $instance->get_payroll_uploads(0,1,1); foreach($get_payroll_uploads as $key=>$val){?>
                    <option value="<?php echo $val['id'];?>" <?php echo ($val['id']==$payroll_id)?'selected':''?> ><?php echo date('m/d/Y', strtotime($val['payroll_date']));?></option>
                <?php } ?>
            </select>
        </div>
    </div>
</div>
<!--<div class="row">
    
    <div class="col-md-6">
        <label>&nbsp; </label>
        <div class="form-group">
            <select class="form-control" name="payroll_name">
                <option value="1">First Payroll(A)</option>
                <option value="2">Second Payroll(A)</option>
                <option value="3">Third Payroll(A)</option>
                <option value="4">Fourth Payroll(A)</option>
                <option value="5">Fifth Payroll(A)</option>
            </select>
        </div>
    </div>
</div>-->
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>Broker </label>
            <select class="form-control" name="broker">
                <option value="0">All Brokers</option>
                <?php foreach($get_broker as $key=>$val){?>
                <option value="<?php echo $val['id'];?>" <?php echo ($val['id']==$broker)?'selected':''?> ><?php echo $val['first_name'].' '.$val['last_name'];?></option>
                <?php } ?>
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>Sort Order </label>
            <select class="form-control" name="print_type">
                <option value="1" <?php echo ($print_type==1)?'selected':''?> >By Name</option>
                <option value="2" <?php echo ($print_type==2)?'selected':''?> >By Fund/Clear No.</option>
                <option value="3" <?php echo ($print_type==3)?'selected':''?> >By Internal Broker ID Number</option>
            </select>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>Output Destination </label>
            <select class="form-control" name="output">
                <option value="1" <?php echo ($output==1)?'selected':''?> >Screen</option>
                <option value="2" <?php echo ($output==2)?'selected':''?> >Print Preview</option>
                <option value="3" <?php echo ($output==3)?'selected':''?> >Excel</option>
                <option value="4" <?php echo ($output==4)?'selected':''?> >PDF</option>
            </select>
        </div>
    </div>
</div>
<!--<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <input type="checkbox" class="checkbox" name="pdf_for_broker" id="pdf_for_broker" style="display: inline;" value="1" /> Create PDF Files For Brokers&nbsp;&nbsp;&nbsp;
        </div>
    </div>
</div>-->
<?php }
else if(isset($_GET['report_name']) && in_array((int)$_GET['report_name'], [2,5])){ ?>
<div class="titlebox"><?php echo (int)$_GET['report_name']==2?"Company Commission Statement":"Payroll Summary Report" ?></div><br />
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>Company </label>
            <select class="form-control" name="company">
                <option value="0">All Companies</option>
                <?php foreach($get_multi_company as $key=>$val){?>
                <option value="<?php echo $val['id'];?>" <?php echo ($val['id']==$company)?'selected':''?> ><?php echo $val['company_name'];?></option>
                <?php } ?>
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>Payroll Date </label>
            <!-- 11/21/21 Removed input box, and replace with a dropdown of available Payroll Dates
            <!-- <div id="demo-dp-range">
                <div class="input-daterange input-group" id="datepicker">
                    <input type="text" name="payroll_date" id="payroll_date" class="form-control" value="<?php if(isset($payroll_date) && $payroll_date != ''){ echo $payroll_date;} else {echo date('m/01/Y');} ?>"/>
                </div>
            </div> -->
            <select class="form-control" name="payroll_id">
                <?php $get_payroll_uploads = $instance->get_payroll_uploads(0,1,1); foreach($get_payroll_uploads as $key=>$val){?>
                    <option value="<?php echo $val['id'];?>" <?php echo ($val['id']==$payroll_id)?'selected':''?> ><?php echo date('m/d/Y', strtotime($val['payroll_date']));?></option>
                <?php } ?>
            </select>
        </div>
    </div>
</div>

<div class="row">
    <?php if ((int)$_GET['report_name'] != 5){ ?>
        <div class="col-md-6">
            <div class="form-group">
                <label>Sort Order </label>
                <select class="form-control" name="sort_by">
                    <option value="1">By Name</option>
                    <option value="2">By Fund/Clear No.</option>
                </select>
            </div>
        </div>
    <?php } ?>
    <div class="col-md-6">
        <div class="form-group">
            <label>Output Destination </label>
            <select class="form-control" name="output">
                <option value="1" <?php echo ($output==1)?'selected':''?> >Screen</option>
                <option value="2" <?php echo ($output==2)?'selected':''?> >Print Preview</option>
                <option value="3" <?php echo ($output==3)?'selected':''?> >Excel</option>
                <option value="4" <?php echo ($output==4)?'selected':''?> >PDF</option>
            </select>
        </div>
    </div>
</div>

<?php }
else if(isset($_GET['report_name']) && $_GET['report_name'] == '3'){ ?>
<div class="titlebox">Commission Adjustments Log</div><br />
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>Company </label>
            <select class="form-control" name="company">
                <option value="0">All Companies</option>
                <?php foreach($get_multi_company as $key=>$val){?>
                <option value="<?php echo $val['id'];?>" <?php echo ($val['id']==$company)?'selected':''?>><?php echo $val['company_name'];?></option>
                <?php } ?>
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>Payroll Date </label>
            <!-- 11/23/21 Update to Dropdown Box<select>, instead of <input> box -->
            <!-- <div id="demo-dp-range">
                <div class="input-daterange input-group" id="datepicker">
                    <input type="text" name="payroll_date" id="payroll_date" class="form-control" value="<?php if(isset($payroll_date) && $payroll_date != ''){ echo $payroll_date;} else {echo date('m/01/Y');} ?>"/>
                </div>
            </div> -->
            <select class="form-control" name="payroll_id">
                <?php $get_payroll_uploads = $instance->get_payroll_uploads(0,1,1); foreach($get_payroll_uploads as $key=>$val){ ?>
                    <option value="<?php echo $val['id'];?>" <?php echo ($val['id']==$payroll_id)?'selected':''?> ><?php echo date('m/d/Y', strtotime($val['payroll_date']));?></option>
                <?php } ?>
            </select>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>Sort Order </label>
            <select class="form-control" name="sort_by">
                <option value="1">By Name</option>
                <option value="2">By Fund/Clear No.</option>
                <option value="3">By Category</option>
                <option value="4">By G/L Account</option>
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>Report Type </label>
            <select class="form-control" name="output_type">
                <option value="1">Detail</option>
                <option value="2">Summary Only</option>
                <option value="3">Recurring Only</option>
            </select>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>Output Destination </label>
            <select class="form-control" name="output">
                <option value="1" <?php echo ($output==1)?'selected':''?> >Screen</option>
                <option value="2" <?php echo ($output==2)?'selected':''?> >Print Preview</option>
                <option value="3" <?php echo ($output==3)?'selected':''?> >Excel</option>
                <option value="4" <?php echo ($output==4)?'selected':''?> >PDF</option>
            </select>
        </div>
    </div>
</div>
<?php } 
else if(isset($_GET['report_name']) && $_GET['report_name'] == '4'){ ?>
<div class="titlebox">Payroll Batch Report</div><br />
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>&nbsp;</label>
            <select class="form-control" name="product_category">
                <option value="0">All Product Categories</option>
                <?php foreach($get_product_category as $key=>$val){?>
                    <option value="<?php echo $val['id'];?>" <?php echo ($val['id']==$product_category)?'selected':''?> ><?php echo $val['type'];?></option>
                <?php } ?>
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>Payroll Date </label>
            <!-- 11/23/21 Update to Dropdown Box<select>, instead of <input> box -->
            <!-- <div id="demo-dp-range">
                <div class="input-daterange input-group" id="datepicker">
                    <input type="text" name="payroll_date" id="payroll_date" class="form-control" value="<?php if(isset($payroll_date) && $payroll_date != ''){ echo $payroll_date;} else {echo date('m/01/Y');} ?>"/>
                </div>
            </div> -->
            <select class="form-control" name="payroll_id">
                <?php $get_payroll_uploads = $instance->get_payroll_uploads(0,1,1); foreach($get_payroll_uploads as $key=>$val){ ?>
                    <option value="<?php echo $val['id'];?>" <?php echo ($val['id']==$payroll_id)?'selected':''?> ><?php echo date('m/d/Y', strtotime($val['payroll_date']));?></option>
                <?php } ?>
            </select>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>Output Destination </label>
            <select class="form-control" name="output">
                <option value="1" <?php echo ($output==1)?'selected':''?> >Screen</option>
                <option value="2" <?php echo ($output==2)?'selected':''?> >Print Preview</option>
                <option value="3" <?php echo ($output==3)?'selected':''?> >Excel</option>
                <option value="4" <?php echo ($output==4)?'selected':''?> >PDF</option>
            </select>
        </div>
    </div>
</div>
<?php } ?>