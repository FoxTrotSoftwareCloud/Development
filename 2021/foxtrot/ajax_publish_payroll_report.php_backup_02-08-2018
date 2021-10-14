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

if(isset($_GET['report_name']) && $_GET['report_name'] == '1'){
?>
<div class="titlebox">Payroll Commission Statements</div><br />
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>Company </label>
            <select class="form-control" name="company">
                <option value="0">All Companies</option>
                <?php foreach($get_multi_company as $key=>$val){?>
                <option value="<?php echo $val['id'];?>"><?php echo $val['company_name'];?></option>
                <?php } ?>
            </select>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>Payroll Date </label>
            <div id="demo-dp-range">
                <div class="input-daterange input-group" id="datepicker">
                    <input type="text" name="payroll_date" id="payroll_date" class="form-control" value="<?php if(isset($payroll_date) && $payroll_date != ''){ echo $payroll_date;} else {echo date('m/01/Y');} ?>"/>
                </div>
            </div>
        </div>
    </div>
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
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>Broker </label>
            <select class="form-control" name="broker">
                <option value="0">All Brokers</option>
                <?php foreach($get_broker as $key=>$val){?>
                <option value="<?php echo $val['id'];?>" ><?php echo $val['first_name'].' '.$val['last_name'];?></option>
                <?php } ?>
            </select>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <input type="radio" class="radio" name="print_type" id="print_alphabetically" style="display: inline;" value="1" checked='checked'/> Print Alphabetically&nbsp;&nbsp;&nbsp;
            <input type="radio" class="radio" name="print_type" id="print_numerically" style="display: inline;" value="2" /> Print Numerically&nbsp;&nbsp;&nbsp;
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <input type="radio" class="radio" name="output" id="output_to_screen" style="display: inline;" value="1" checked='checked'/> Output to Screen&nbsp;&nbsp;&nbsp;
            <input type="radio" class="radio" name="output" id="output_to_printer" style="display: inline;" value="2" /> Output to Printer&nbsp;&nbsp;&nbsp;
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <input type="checkbox" class="checkbox" name="pdf_for_broker" id="pdf_for_broker" style="display: inline;" value="1" /> Create PDF Files For Brokers&nbsp;&nbsp;&nbsp;
        </div>
    </div>
</div>
<?php }
else if(isset($_GET['report_name']) && $_GET['report_name'] == '2'){ ?>
<div class="titlebox">Company Commission Statements</div><br />
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>Company </label>
            <select class="form-control" name="company">
                <option value="0">All Companies</option>
                <?php foreach($get_multi_company as $key=>$val){?>
                <option value="<?php echo $val['id'];?>" ><?php echo $val['company_name'];?></option>
                <?php } ?>
            </select>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <input type="radio" class="radio" name="sort_by" id="sort_by_name" style="display: inline;" value="1" checked='checked'/> Sort by Name&nbsp;&nbsp;&nbsp;
            <input type="radio" class="radio" name="sort_by" id="sort_by_rep_number" style="display: inline;" value="2" /> Sort by Fund/Clear No&nbsp;&nbsp;&nbsp;
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <input type="radio" class="radio" name="output" id="output_to_screen" style="display: inline;" value="1" checked='checked'/> Output to Screen&nbsp;&nbsp;&nbsp;
            <input type="radio" class="radio" name="output" id="output_to_printer" style="display: inline;" value="2"/> Output to Printer&nbsp;&nbsp;&nbsp;
            <input type="radio" class="radio" name="output" id="output_to_excel" style="display: inline;" value="3"/> Output to Excel&nbsp;&nbsp;&nbsp;
            <input type="radio" class="radio" name="output" id="output_to_pdf" style="display: inline;" value="4"/> Output to PDF
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
                <option value="<?php echo $val['id'];?>" ><?php echo $val['company_name'];?></option>
                <?php } ?>
            </select>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>Payroll Date </label>
            <div id="demo-dp-range">
                <div class="input-daterange input-group" id="datepicker">
                    <input type="text" name="payroll_date" id="payroll_date" class="form-control" value="<?php if(isset($payroll_date) && $payroll_date != ''){ echo $payroll_date;} else {echo date('m/01/Y');} ?>"/>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <input type="radio" class="radio" name="sort_by" id="sort_by_1" style="display: inline;" value="1" checked='checked'/> Sort by Name&nbsp;&nbsp;&nbsp;
            <input type="radio" class="radio" name="sort_by" id="sort_by_2" style="display: inline;" value="2"/> Sort by Number&nbsp;&nbsp;&nbsp;
            <input type="radio" class="radio" name="sort_by" id="sort_by_3" style="display: inline;" value="3"/> Sort by Category&nbsp;&nbsp;&nbsp;
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <input type="radio" class="radio" name="output_type" id="output_type_1" style="display: inline;" value="1" checked='checked'/> Detail&nbsp;&nbsp;&nbsp;
            <input type="radio" class="radio" name="output_type" id="output_type_2" style="display: inline;" value="2"/> Summary Only&nbsp;&nbsp;&nbsp;
            <input type="radio" class="radio" name="output_type" id="output_type_3" style="display: inline;" value="3"/> Recurring Only&nbsp;&nbsp;&nbsp;
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <input type="radio" class="radio" name="output" id="output_to_screen" style="display: inline;" value="1" checked='checked'/> Output to Screen&nbsp;&nbsp;&nbsp;
            <input type="radio" class="radio" name="output" id="output_to_printer" style="display: inline;" value="2" /> Output to Printer&nbsp;&nbsp;&nbsp;
            <input type="radio" class="radio" name="output" id="output_to_excel" style="display: inline;" value="3" /> Output to Excel&nbsp;&nbsp;&nbsp;
            <input type="radio" class="radio" name="output" id="output_to_pdf" style="display: inline;" value="4" /> Output to PDF
        </div>
    </div>
</div>
<?php } 
else if(isset($_GET['report_name']) && $_GET['report_name'] == '4'){ ?>
<div class="titlebox">Payroll Batch Report</div><br />
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <select class="form-control" name="company">
                <option value="0">All Batch Groups</option>
                <?php foreach($get_product_category as $key=>$val){?>
                <option value="<?php echo $val['id'];?>" ><?php echo $val['type'];?></option>
                <?php } ?>
            </select>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <input type="radio" class="radio" name="output" id="output_to_screen" style="display: inline;" value="1" checked='checked'/> Output to Screen&nbsp;&nbsp;&nbsp;
            <input type="radio" class="radio" name="output" id="output_to_printer" style="display: inline;" value="2" /> Output to Printer&nbsp;&nbsp;&nbsp;
            <input type="radio" class="radio" name="output" id="output_to_excel" style="display: inline;" value="3" /> Output to Excel&nbsp;&nbsp;&nbsp;
            <input type="radio" class="radio" name="output" id="output_to_pdf" style="display: inline;" value="4" /> Output to PDF
        </div>
    </div>
</div>
<?php } ?>