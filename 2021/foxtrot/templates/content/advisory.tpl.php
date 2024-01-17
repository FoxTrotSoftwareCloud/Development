<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.12/sorting/datetime-moment.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.5.1/chosen.jquery.min.js"></script>
<script type="text/javascript" src="<?php echo SITE_PLUGINS; ?>autocomplete/jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo SITE_PLUGINS; ?>autocomplete/jquery.ui.autocomplete.scroll.min.js"></script>
<link rel="stylesheet" href="<?php echo SITE_PLUGINS; ?>autocomplete/jquery-ui.css?1">

<style type="text/css">
    .autocomplete {
        /*the container must be positioned relative:*/
        position: relative;
        display: inline-block;
    }

    .autocomplete-items {
        position: absolute;
        border: 1px solid #d4d4d4;
        border-bottom: none;
        border-top: none;
        z-index: 99;
        /*position the autocomplete items to be the same width as the container:*/
        top: 100%;
        left: 0;
        right: 0;
    }

    .autocomplete-items div {
        padding: 10px;
        cursor: pointer;
        background-color: #fff;
        border-bottom: 1px solid #d4d4d4;
    }

    .autocomplete-items div:hover {
        /*when hovering an item:*/
        background-color: #e9e9e9;
    }

    .autocomplete-active {
        /*when navigating through the items using the arrow keys:*/
        background-color: DodgerBlue !important;
        color: #ffffff;
    }

    input[type=radio] {
        border: 0px;
        width: 50%;
        height: 1.2em;
    }
</style>

<script type="text/javascript">
    function autocomplete(inp, arr) {
        /*the autocomplete function takes two arguments,
        the text field element and an array of possible autocompleted values:*/
        var currentFocus;
        /*execute a function when someone writes in the text field:*/
        inp.addEventListener("input", function(e) {
            var a, b, i, val = this.value;
            /*close any already open lists of autocompleted values*/
            closeAllLists();
            if (!val) {
                return false;
            }

            currentFocus = -1;
            /*create a DIV element that will contain the items (values):*/
            a = document.createElement("DIV");
            a.setAttribute("id", this.id + "autocomplete-list");
            a.setAttribute("class", "autocomplete-items");
            /*append the DIV element as a child of the autocomplete container:*/
            this.parentNode.appendChild(a);
            /*for each item in the array...*/
            for (i = 0; i < arr.length; i++) {
                /*check if the item starts with the same letters as the text field value:*/
                // if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
                if (arr[i].includes(val)) {
                    /*create a DIV element for each matching element:*/
                    b = document.createElement("DIV");
                    /*make the matching letters bold:*/
                    b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
                    b.innerHTML += arr[i].substr(val.length);
                    /*insert a input field that will hold the current array item's value:*/
                    b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
                    /*execute a function when someone clicks on the item value (DIV element):*/
                    b.addEventListener("click", function(e) {
                        /*insert the value for the autocomplete text field:*/
                        inp.value = this.getElementsByTagName("input")[0].value;
                        get_client_id(inp.value);
                        /*close the list of autocompleted values,
                        (or any other open lists of autocompleted values:*/
                        closeAllLists();
                    });
                    a.appendChild(b);
                }
            }
        });
        /*execute a function presses a key on the keyboard:*/
        inp.addEventListener("keydown", function(e) {
            var x = document.getElementById(this.id + "autocomplete-list");
            if (x) x = x.getElementsByTagName("div");

            if (e.keyCode == 40) {
                /*If the arrow DOWN key is pressed,
                increase the currentFocus variable:*/
                currentFocus++;
                /*and and make the current item more visible:*/
                addActive(x);
            } else if (e.keyCode == 38) { //up
                /*If the arrow UP key is pressed,
                decrease the currentFocus variable:*/
                currentFocus--;
                /*and and make the current item more visible:*/
                addActive(x);
            } else if (e.keyCode == 13) {
                /*If the ENTER key is pressed, prevent the form from being submitted,*/
                e.preventDefault();
                if (currentFocus > -1) {
                    /*and simulate a click on the "active" item:*/
                    if (x) x[currentFocus].click();
                }
            }
        });

        function addActive(x) {
            /*a function to classify an item as "active":*/
            if (!x) return false;
            /*start by removing the "active" class on all items:*/
            removeActive(x);

            if (currentFocus >= x.length) currentFocus = 0;
            if (currentFocus < 0) currentFocus = (x.length - 1);
            /*add class "autocomplete-active":*/
            x[currentFocus].classList.add("autocomplete-active");

        }

        function removeActive(x) {
            /*a function to remove the "active" class from all autocomplete items:*/
            for (var i = 0; i < x.length; i++) {
                x[i].classList.remove("autocomplete-active");
            }
        }

        function closeAllLists(elmnt) {
            /*close all autocomplete lists in the document,
            except the one passed as an argument:*/
            var x = document.getElementsByClassName("autocomplete-items");
            for (var i = 0; i < x.length; i++) {
                if (elmnt != x[i] && elmnt != inp) {
                    x[i].parentNode.removeChild(x[i]);
                }
            }
        }
        /*execute a function when someone clicks in the document:*/
        document.addEventListener("click", function(e) {
            closeAllLists(e.target);
        });
    }
</script>
<div id="new_transcation_wrap" class="container">
    <h1>Advisory Fees</h1>
    <div class="col-lg-12 well">
        <?php require_once(DIR_FS_INCLUDES . "alerts.php"); ?>

        <?php if ((isset($_GET['action']) && $_GET['action'] == 'add') || (isset($_GET['action']) && ($_GET['action'] == 'edit_transaction' && $id > 0))) {
        ?>
            <form name="frm2" method="POST">
                <div class="panel">
                    <div class="panel-heading">
                        <div class="panel-control" style="float: right;">
                            <div class="btn-group dropdown">
                                <!-- <button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li><a href="<?php echo CURRENT_PAGE; ?>?action=view"><i class="fa fa-eye"></i> View List</a></li>
                                </ul> -->
                            </div>
                        </div>
                        <h3 class="panel-title"><i class="fa fa-pencil-square-o"></i><?php echo $action == 'add' ? 'Add' : 'Edit'; ?> Advisory Fees</h3>
                    </div>
                    <div class="panel-body">
                        <input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Commission Received Date <span class="text-red">*</span></label><br />
                                    <div id="demo-dp-range">
                                        <div class="input-daterange input-group">
                                            <input name="commission_received_date" id="commission_received_date" type="text" class="form-control" data-required="true" value="<?php if (isset($commission_received_date) && $commission_received_date != '0000-00-00 00:00:00') {
                                                                                                                                                                                    echo date('m/d/Y', strtotime($commission_received_date));
                                                                                                                                                                                }else{
                                                                                                                                                                                    echo date('m/d/Y');
                                                                                                                                                                                } ?>" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Client Search by Name/Number </label><br />
                                    <div class="autocomplete" style="width:100%">
                                        <input type="text" autocomplete="on" class="form-control" name="search_client_number" id="search_client_number" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Client Name <span class="text-red">* </span> </label><a href="#" onclick="return redirect_url('client_maintenance.php?redirect=add_client_from_trans&action=add_new','client');" class="btn btn-sm btn-default"><i class="fa fa-plus"></i> Add New client</a><br />
                                    <select class="livesearch form-control" data-required="true" id="client_name" name="client_name">
                                        <option value="0">Select Client</option>
                                        <?php foreach ($get_client as $key => $val) { ?>
                                            <option data-brokername="<?php echo $val['broker_name'] ?>" value="<?php echo $val['id']; ?>" <?php if (isset($client_name) && $client_name == $val['id']) { ?>selected="true" <?php } ?>><?php echo $val['last_name'] . (empty($val['first_name']) || empty($val['last_name']) ? "" : ", ") . $val['first_name'] . ' ' . $val['mi']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Account No</label>
                                    <select class="form-control" data-required="false" name="client_number" id="client_number">
                                        <option value=""> Please Select </option>
                                        <option value="-1"> Add New </option>
                                        <?php foreach ($get_accounts_no as $no) : ?>
                                            <option value="<?php echo trim($no); ?>" "selected" data-test="datatest"><?php echo $no; ?></option>
                                        <?php endforeach;  ?>

                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Broker Name <span class="text-red">*</span></label><br />
                                    <select class="livesearch form-control" data-required="true" name="broker_name" id="broker_name">
                                        <option value="0">Select Broker</option>
                                        <?php foreach ($get_broker as $key => $val) { ?>
                                            <option value="<?php echo $val['id']; ?>" <?php if (isset($broker_name) && $broker_name == $val['id']) { ?>selected="true" <?php } ?>><?php echo $val['last_name'] . ', ' . $val['first_name'] . ' ' . $val['middle_name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row" id="account_no_row" style="display:none">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Account No's </label><br>
                                    <input type="text" name="c_account_no" onkeypress="return event.charCode >= 48 &amp; event.charCode <= 57" id="c_account_no" class="form-control" value="">
                                </div>
                            </div>
                            <div class="col-md-4 company_sponsor" id="company_sponsor">
                                <div class="form-group">
                                    <label>Sponsor Company </label><br>

                                    <select class="form-control" name="c_sponsor" id="c_sponsor">
                                        <option value="">Select Sponsor</option>
                                        <?php foreach ($get_sponsor as $key => $val) { ?>
                                            <option value="<?php echo $val['id']; ?>"><?php echo $val['name']; ?></option>
                                        <?php } ?>
                                    </select>

                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Batch <span class="text-red">*</span><a id="add_new_batch" href="#" onclick="return redirect_url('batches.php?action=add_batches_from_trans','batch');" class="btn btn-sm btn-default"><i class="fa fa-plus"></i> Add New Batch</a></label><br />
                                    <select class="form-control" data-required="true" name="batch">
                                        <option value="0">Select Batch</option>
                                        <?php foreach ($get_batch as $key => $val) { ?>
                                            <option value="<?php echo $val['id']; ?>" <?php if (isset($batch) && $batch == $val['id']) { ?> selected="true" <?php }  ?>><?php echo $val['id'] . ' ' . $val['batch_desc']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Product <span class="text-red">*</span><a id="add_new_prod" href="#" onclick="return redirect_url('product_cate.php?action=add_product&redirect=add_product_from_trans&transaction_id='+<?php echo $id ?>,'product');" class="btn btn-sm btn-default"><i class="fa fa-plus"></i> Add New Product</a></label><br />
                                    <select class="form-control" data-required="true" name="product" id="product">
                                        <option value="0">Select Product</option>
                                        <?php foreach($get_ria_products as $key=>$val){?>
                                            <option value="<?php echo $val['id'];?>" <?php echo $selected==$val['id']?'selected="selected"':''; ?>><?php echo $val['name'];?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Investment Amount</label><br />
                                    <div id="demo-dp-range">
                                        <input type="text" maxlength="12" onChange="setnumber_format(this)" class="form-control" onkeypress='return (event.charCode >= 45 && event.charCode <= 57 && event.charCode != 47) || event.charCode == 46 ' name="invest_amount" id="invest_amount" value="<?php if (isset($invest_amount)) {
                                                                                                                                                                                                                                                                                echo $invest_amount;
                                                                                                                                                                                                                                                                            } ?>" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Total Gross Advisory Fee Received <span class="text-red">*</span></label><br />
                                    <input type="text" maxlength="12" data-required="true" onChange="setnumber_format(this)" class="form-control" onkeypress='return (event.charCode >= 45 && event.charCode <= 57 && event.charCode != 47) || event.charCode == 46 ' name="commission_received" id="commission_received" value="<?php if (isset($commission_received)) {
                                                                                                                                                                                                                                                                                                            echo $commission_received;
                                                                                                                                                                                                                                                                                                        } ?>" />
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Number of Payrolls to Pay <span class="text-red">*</span></label><br />
                                    <input type="number" class="form-control" name="payroll_count" id="payroll_count" onchange="changeNumber()" onkeypress='return (event.charCode != 45)' min=1 max=50/>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Start Payroll Date:<span class="text-red">*</span></label><br />
                                    <div id="demo-dp-range">
                                        <div class="input-daterange input-group" id="datepicker">
                                            <input type="text" autocomplete="off" data-required="true" name="trade_date" id="trade_date" value="<?php if (isset($trade_date) && $trade_date != '0000-00-00') {
                                                                                                                                                    echo date('m/d/Y', strtotime($trade_date));
                                                                                                                                                } ?>" class="form-control" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <label>Payroll Frequency<span class="text-red">*</span></label><br />
                        <div class="row multi-checkbox-row">
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="radio-inline">
                                                <input type="radio" class="radio" data-required="true" onclick="toggleCustomDateFields()" name="payroll_period" id="split_yes" value="Weekly" checked/>&nbsp;&nbsp;Weekly
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" class="radio" data-required="true" onclick="toggleCustomDateFields()" name="payroll_period" id="split_no" value="Bi-Monthly" />&nbsp;&nbsp;&nbsp;&nbsp;Bi-Monthly
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" class="radio" data-required="true" onclick="toggleCustomDateFields()" name="payroll_period" id="split_no" value="Monthly" />&nbsp;&nbsp;Monthly
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" class="radio" data-required="true" onclick="toggleCustomDateFields()" name="payroll_period" id="split_no" value="Custom" />&nbsp;&nbsp;Custom
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        
                                        <div class="selectwrap container">
                                            <input type="button" onclick="generateTrade();" name="genrate_trade" id="genrate_trade" value="Generate Trades"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4" id="demo-dp-range">
                                <div class="input-daterange form-group datename-container">
                                </div>
                            </div>
                        </div>

                        <div class="row" id="future_payroll">
                        </div>

                        <div class="panel-footer fixedbtmenu">
                            <div class="selectwrap container">
                                <input type="submit" name="transaction" onclick="return waitingDialog.show();" id="save" value="Save" style="float: right;display:none" />
                                <input type="hidden" name="rule_engine_warning_action" id="rule_engine_warning_action" value="0" />
                                <!-- <a id="anchor_cancel" href="<?php echo CURRENT_PAGE . '?action=cancel'; ?>"><input type="button" name="cancel" id="cancel" value="Cancel" style="float: right;" /></a> -->.
                                <a id="anchor_cancel" href="<?php echo SITE_URL . 'transaction.php?action=cancel'; ?>"><input type="button" name="cancel" id="cancel" value="Cancel" style="float: right;" /></a>
                            </div>
                        </div>
                    </div>
            </form>



    </div><!-- End of Modal content -->
</div><!-- End of Modal dialog -->
</div><!-- End of Modal -->

</form>
<?php
        }
        if ((isset($_GET['action']) && $_GET['action'] == 'view') || $action == 'view') { ?>
    <div class="panel">
        <div class="panel-body">
            <div class="table-responsive">
                <table id="data-table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center" colspan="2">ACTION</th>
                            <th>Trade Number</th>
                            <th>Trade Date</th>
                            <th>Client Name</th>
                            <th>Client Account Number</th>
                            <th>Broker Name</th>
                            <th>Batch</th>
                            <th>Investment Amount</th>
                            <th>Commission Received</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $count = 0;
                        foreach ($return as $key => $val) {
                            $commissionEditLink = CURRENT_PAGE . "?action=edit_transaction&id=" . $val['id'];
                        ?>
                            <tr>
                                <td class="text-center">
                                    <a href="<?php echo CURRENT_PAGE; ?>?action=edit_transaction&id=<?php echo $val['id']; ?>" class="btn btn-md btn-primary"><i class="fa fa-edit"></i> Edit</a>
                                </td>
                                <td class="text-center">
                                    <a onclick="return conf('<?php echo CURRENT_PAGE; ?>?action=transaction_delete&id=<?php echo $val['id']; ?>');" class="btn btn-md btn-danger confirm"><i class="fa fa-trash"></i> Delete</a>
                                </td>
                                <td onclick="window.location.href='<?php echo $commissionEditLink ?>'" style="cursor:pointer"><?php echo $val['id']; ?></td>
                                <td onclick="window.location.href='<?php echo $commissionEditLink ?>'" style="cursor:pointer"><?php echo date('m/d/Y', strtotime($val['trade_date'])); ?></td>
                                <td onclick="window.location.href='<?php echo $commissionEditLink ?>'" style="cursor:pointer"><?php if (isset($val['client_lastname']) && $val['client_lastname'] != '') {
                                                                                                                                    echo $val['client_lastname'] . ',' . $val['client_firstname'];
                                                                                                                                } ?></td>
                                <td onclick="window.location.href='<?php echo $commissionEditLink ?>'" style="cursor:pointer"><?php echo $val['client_number']; ?></td>
                                <td onclick="window.location.href='<?php echo $commissionEditLink ?>'" style="cursor:pointer"><?php echo $val['broker_last_name'] . ', ' . $val['broker_firstname']; ?></td>
                                <td onclick="window.location.href='<?php echo $commissionEditLink ?>'" style="cursor:pointer"><?php echo $val['batch_desc']; ?></td>
                                <td onclick="window.location.href='<?php echo $commissionEditLink ?>'" style="cursor:pointer;text-align: right;"><?php echo '$' . number_format($val['invest_amount'], 2); ?></td>
                                <td onclick="window.location.href='<?php echo $commissionEditLink ?>'" style="cursor:pointer;text-align: right;"><?php echo '$' . number_format($val['commission_received'], 2); ?></td>
                                <!--td class="text-center">
                                    <?php
                                    if ($val['status'] == 1) {
                                    ?>
                                            <a href="<?php echo CURRENT_PAGE; ?>?action=batches_status&id=<?php echo $val['id']; ?>&status=0" class="btn btn-sm btn-success"><i class="fa fa-check-square-o"></i> Enabled</a>
                                            <?php
                                        } else {
                                            ?>
                                            <a href="<?php echo CURRENT_PAGE; ?>?action=batches_status&id=<?php echo $val['id']; ?>&status=1" class="btn btn-sm btn-warning"><i class="fa fa-warning"></i> Disabled</a>
                                            <?php
                                        }
                                            ?>
                                </td-->

                            </tr>
                        <?php }  ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php } ?>
<?php if (isset($_GET['action']) && $_GET['action'] == 'view_report') { ?>
    <div id="view_report">
        <form method="post" target="_blank">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Batch </label><br />
                        <select class="form-control" name="view_batch">
                            <option value="">All Batches</optionf>
                                <?php foreach ($get_batch as $key => $val) { ?>
                            <option value="<?php echo $val['id']; ?>" <?php if (isset($batch) && $batch == $val['id']) { ?> selected="true" <?php } ?>><?php echo $val['id']; ?></option>
                        <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group "><br />
                        <div class="selectwrap">
                            <input type="submit" name="view_report" value="View Report" />
                            <a href="<?php echo CURRENT_PAGE . '?action=view'; ?>"><input type="button" name="cancel" value="Cancel" /></a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
<?php } ?>
</div>

<!-- 06/07/22 Rule Engine Modal Window -->
<div id="resolve_rule_engine_modal" class="modal fade inputpopupwrap" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="margin-bottom: 0px !important;">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
                <h4 class="modal-title">Rule Engine Warning - Failed Tests</h4>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <div id="msg_exception" class="text-red"></div>
                    <br>
                </div>
                <br>
                <div class="col-md-12">
                    <!-- 06/30/22 Try removing the "onsubmit=...." -->
                    <!-- <form method="post" id="resolve_rule_engine_form" name="resolve_rule_engine_form" onsubmit="return resolve_rule_engine_submit();"> -->
                    <form method="post" id="resolve_rule_engine_form" name="resolve_rule_engine_form">
                        <div class="row" style="display: block;" id="resolve_rule_engine_row">
                            <div class="col-md-5">
                                <div class="inputpopup">
                                    <label id="resolve_rule_engine_label" class="pull-right" style="display:inline-block; margin-top:5px;">Select Option: </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <input type="radio" class="radio" name="resolve_rule_engine_action" id="resolve_rule_engine_hold_commission" style="display:inline-block; vertical-align:middle; margin-top:-1px" value="1" checked />
                                <label for="resolve_rule_engine_hold_commission" style="display:inline-block">Hold Commission</label><br />
                                <input type="radio" class="radio" name="resolve_rule_engine_action" id="resolve_rule_engine_ignore" style="display:inline-block; vertical-align:middle; margin-top:-1px" value="2" />
                                <label id="lbl_resolve_rule_engine_ignore" for="resolve_rule_engine_ignore" style="display:inline-block">Ignore Exception(s) / Enter Trade</label><br />
                                <input type="radio" class="radio" name="resolve_rule_engine_action" id="resolve_rule_engine_cancel" style="display:inline-block; vertical-align:middle; margin-top:-1px" value="3" />
                                <label id="lbl_resolve_rule_engine_cancel" for="resolve_rule_engine_cancel" style="display:inline-block"> Cancel & Delete Trade</label><br />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5"></div>
                            <div class="col-md-5">
                                <div class="inputpopup">
                                    <button type="submit" class="btn btn-sm btn-warning" name="resolve_rule_engine_submit_button" value="Resolve Exception" style="display:inline-block; vertical-align: top; text-align:center;"><i class="fa fa-save"></i> Proceed</button>
                                    <button type="button" class="btn btn-sm btn-danger" name="resolve_rule_engine_edit" value="Resolve Exception" data-dismiss="modal" aria-hidden="true" style="display:inline-block; vertical-align: top; text-align:center"><i class="fa fa-save"></i> Edit Trade</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <br />
            </div><!-- End of Modal body -->
        </div><!-- End of Modal content -->
    </div><!-- End of Modal dialog -->
</div>
</div>


<script type="text/javascript">

    function generateTrade() {
        var showSaveButton = document.getElementById("save");
        showSaveButton.style.display = "block";
        var futureContainer = document.getElementById("future_payroll");
        var payrollCount = parseInt(document.getElementById("payroll_count").value);
        var tradeDate = document.getElementById("trade_date").value;
        var grossCommission = parseInt((document.getElementById("commission_received").value).replace(/,/g, ''));
        var selectedValue = getCheckedRadioButtonValue();

        var splitCommission = parseFloat((grossCommission/payrollCount).toFixed(2));

        futureContainer.innerHTML = "";

        var tableHTML = '<label style="padding:0px 50px">Breakdown of future Payrolls</label>';
        tableHTML += `<table class='table table-striped table-bordered' style="padding:0px 50px">
                        <thead>
                            <tr>
                                <th></th>
                                <th>PAYROLL DATE</th>
                                <th>GROSS ADVISORY FEE</th>
                            </tr>
                        </thead>
                        <tbody>`;
        if (selectedValue === "Custom") {
            for (var j = 0; j < payrollCount; j++) {
                // var getDate = document.getElementsByName("custom-payroll-date-"+j)[0];
                // getDate = getDate.value;
                var getDate = '';
                tableHTML +=  `<tr>
                                <td>Payroll #`+(j+1)+`</td>
                                <td><input type="text" name="split-trade-date[]" class="form-control table-trade-date-`+j+`" value="`+getDate+`"/></td>
                                <td><input type="text" name="split-commission-recived[]" class="form-control compare-value" onchange="comparevalue()" value="`+(splitCommission)+`"/></td>
                            </tr>`;
            }
        }
        if (selectedValue === "Weekly") {
            for (var j = 0; j < payrollCount; j++) {
                var newDate = j==0 ? tradeDate : newDate.setDate(newDate.getDate() + 7);
                newDate = new Date(newDate);
                tableHTML +=  `<tr>
                                <td>Payroll #`+(j+1)+`</td>
                                <td><input type="text" name="split-trade-date[]" class="form-control table-trade-date-`+j+`" value="`+newDate.toLocaleDateString()+`"/></td>
                                <td><input type="text" name="split-commission-recived[]" class="form-control compare-value" onchange="comparevalue()" value="`+(splitCommission)+`"/></td>
                            </tr>`;
            }
        }
        if (selectedValue === "Bi-Monthly") {
            for (var j = 0; j < payrollCount; j++) {
                var newDate = j==0 ? tradeDate : newDate.setDate(newDate.getDate() + 15);
                newDate = new Date(newDate);
                tableHTML +=  `<tr>
                                <td>Payroll #`+(j+1)+`</td>
                                <td><input type="text" name="split-trade-date[]" class="form-control table-trade-date-`+j+`" value="`+newDate.toLocaleDateString()+`"/></td>
                                <td><input type="text" name="split-commission-recived[]" class="form-control compare-value" onchange="comparevalue()" value="`+(splitCommission)+`"/></td>
                            </tr>`;
            }
        }
        if (selectedValue === "Monthly") {
            for (var j = 0; j < payrollCount; j++) {
                var newDate = j==0 ? tradeDate : newDate.setMonth(newDate.getMonth() + 1);
                newDate = new Date(newDate);
                tableHTML +=  `<tr>
                                <td>Payroll #`+(j+1)+`</td>
                                <td><input type="text" name="split-trade-date[]" class="form-control table-trade-date-`+j+`" value="`+newDate.toLocaleDateString()+`"/></td>
                                <td>
                                <input type="text" name="split-commission-recived[]" class="form-control compare-value" onchange="comparevalue()" value="`+(splitCommission)+`"/></td>
                            </tr>`;
            }
        }
        
        tableHTML += `<tr>
                        <td></td>
                        <td><b>TOTAL PAID</b></td>
                        <td><b><div id="totalvalueupdate">`+grossCommission+`</div></b></td>
                        </tr>
                    </tbody>
                        </table>`;
        var tempDiv = document.createElement("div");
        tempDiv.innerHTML = tableHTML;
        futureContainer.appendChild(tempDiv);

        for (var j = 0; j < payrollCount; j++) {
            $('.table-trade-date-'+j).datepicker({
                    format: "mm/dd/yyyy",
                    todayBtn: "linked",
                    autoclose: true,
                    todayHighlight: true
                })
        }
    }

    function comparevalue(){
        var sum = 0;
        $('.compare-value').each(function() {
            sum += parseFloat($(this).val()) || 0;
        });
        var totalValueUpdate = document.getElementById("totalvalueupdate");
        // totalValueUpdate.textContent = parseFloat(sum).toFixed(2);
        totalValueUpdate.textContent =Math.round(sum).toString();
    }

    function changeNumber(){
        toggleCustomDateFields();
    }

    function toggleCustomDateFields() {
        var numberOfFields = document.getElementById("payroll_count").value;
        var selectedValue = getCheckedRadioButtonValue();

        if(numberOfFields == 0 || numberOfFields == ''){
            alert("Enter valid Number of Payrolls to pay");
        }

        var datenameContainer = document.getElementsByClassName("datename-container")[0];
            datenameContainer.innerHTML = "";
        if (selectedValue === "Custom") {
            // for (var i = 0; i < numberOfFields; i++) {
            //     var inputElement = document.createElement("input");
            //     inputElement.type = "text";
            //     inputElement.name = "custom-payroll-date-"+i;
            //     inputElement.className = "form-control custom-date" + i;
            //     inputElement.placeholder = "Enter date "+(i+1);
            //     datenameContainer.appendChild(inputElement);
            //     if (i < numberOfFields - 1) {
            //         datenameContainer.appendChild(document.createElement("br"));
            //     }
                
            //     $('.custom-date'+i).datepicker({
            //         format: "mm/dd/yyyy",
            //         todayBtn: "linked",
            //         autoclose: true,
            //         todayHighlight: true
            //     });
            // } 
        }else {
            datenameContainer.innerHTML = "";
        }   
    }

    function getCheckedRadioButtonValue() {
        var radioButtons = document.getElementsByName('payroll_period');

        for (var i = 0; i < radioButtons.length; i++) {
            if (radioButtons[i].checked) {
                return radioButtons[i].value;
            }
        }
        return null;
    }

    $(document).ready(function() {
        $.fn.chargeFormat = function() {
            this.each(function(i) {
                $(this).change(function(e) {
                    if (isNaN(parseFloat(this.value))) return;
                    this.value = parseFloat(this.value).toFixed(2);
                });
            });
            return this; //for chaining
        }

        $.fn.dataTable.moment('MM/DD/YYYY');
        $('#data-table').DataTable({
            "pageLength": 25,
            "bLengthChange": false,
            "bFilter": true,
            order: [
                [1, 'desc']
            ],
            /*"order": [[ 1, "desc" ]],*/
            "bInfo": false,
            "bAutoWidth": false,
            "dom": '<"toolbar">frtip',
            "columnDefs": [{
                type: 'date',
                'targets': [1]
            }],
            "aoColumnDefs": [{
                    "bSortable": false,
                    "aTargets": [8, 9]
                },
                {
                    "bSearchable": false,
                    "aTargets": [8, 9]
                }
            ],
            "order": [
                [0, "desc"]
            ]
        });

        $("#data-table_filter [type='search']").focus();
        $("div.toolbar").html('<a href="<?php echo CURRENT_PAGE; ?>?action=add" class="btn btn-sm btn-default"><i class="fa fa-plus"></i> Add New</a>' +
            '<div class="panel-control" style="padding-left:5px;display:inline;">' +
            '<div class="btn-group dropdown" style="float: right;">' +
            '<button type="button" class="dropdown-toggle btn btn-default" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>' +
            '<ul class="dropdown-menu dropdown-menu-right" style="">' +
            /*'<li><a href="<?php echo CURRENT_PAGE; ?>?action=add"><i class="fa fa-plus"></i> Add New</a></li>'+*/
            '<li><a href="<?php echo CURRENT_PAGE; ?>?action=view_report"><i class="fa fa-minus"></i> Report</a></li>' +
            '</ul>' +
            '</div>' +
            '</div>');


        var client_ac_number = <?php echo empty(json_encode($client_account_array)) ? '' : json_encode($client_account_array); ?>;

        // 05/15/22 Commented out to see if this is triggering client change and subsequent "get_broker_hold_commission($broker_id) call
        if (localStorage.getItem('transcation_form_data')) {
            for (var key in transcation_form_data) {
                if (transcation_form_data[key]["value"] != '' && transcation_form_data[key]['name'] != 'product_cate' && transcation_form_data[key]['name'] != 'product') {
                    $("[name='" + transcation_form_data[key]["name"] + "']").trigger("chosen:updated")
                }
            }
        }

        // 07/05/22 Reinstated - part of the "Search By Number" text box
        $(".livesearch").chosen();
        if ($("#search_client_number").length > 0) {
            $("#search_client_number").autocomplete({
                    source: "ajax_get_client_account.php?_type=query",
                    minLength: 1,
                    maxShowItems: 20,
                    select: function(event, ui) {
                        $('select[id="client_name"]').val(ui.item.value).trigger("chosen:updated").trigger("change");
                    }
                })
                .autocomplete("instance")._renderItem = function(ul, item) {
                    return $("<li>")
                        .append('<div><span><strong>Client Name:</strong>' + item.name + '</span><br/><span><strong>Cloudfox ID:</strong>' + item.value + '</span><br/>' +
                            (item.account_number === '' ? '' : '<span><strong>Account No:</strong>' + item.account_number + '</span><br/>') +
                            '<span><strong>Client SSN:</strong>' + item.client_ssn + '</span><br/><span><strong>Client File Number:</strong>' + item.client_file_number + '</span></div>')
                        .appendTo(ul);
                };
        }

        $('[data-required="true"]').each(function() {
            $(this).on("change blur", function() {
                if ($(this).prop("type") == "text" || $(this).prop("type") == "select-one") {
                    if ($.trim($(this).val()) == '' || $.trim($(this).val()) == '0') {
                        isErrorFound = true;

                        if ($(this).next("div").find("a.chosen-single").length) {
                            $(this).next("div").find("a.chosen-single").addClass("error");
                        } else
                            $(this).addClass("error");
                    } else {
                        if ($(this).next("div").find("a.chosen-single").length) {
                            $(this).next("div").find("a.chosen-single").removeClass("error");
                        } else
                            $(this).removeClass("error");
                    }
                }

                if ($(this).prop("type") == "radio") {}
            });
        });

        // Default dropdowns after the load()
        <?php if (!empty($client_name) and ((int)$client_name > 0)) { ?>
            $('#client_name').val(<?php echo $client_name; ?>).trigger("chosen:updated").trigger("change");
            get_client_account_no('<?php echo $client_name; ?>', '<?php echo $client_number; ?>', 1);
        <?php } ?>

        <?php if (isset($_GET['batch_id']) && ($_GET['batch_id'] != '' || $batch > 0)) { ?>
            $('[name="batch"]').val(<?php echo $batch; ?>);
            get_commission_date(<?php echo $batch; ?>);
        <?php } ?>

        // 5/13/22 Put Broker -> Company -> Branch last, because the Client change() will revert these back to what's in CLIENT_MASTER
        // AND NOT, what's stored in TRANSACTION_MASTER
        <?php if ($broker_name > 0) { ?>
            var currentdate = new Date();
            var datetime = Date.now().toString().substr(7);

            $("#broker_name").val("");
            $("#broker_name").val(<?php echo $broker_name; ?>).trigger("chosen:updated").trigger("change");
            // get_broker_hold_commission(<?php echo $broker_name; ?>);
        <?php } ?>

        // Call this BEFORE #company, because this will change the #company to the "company" field in BRANCH_MASTER table
        <?php if ($branch > 0) { ?>
            var currentdate = new Date();
            var datetime = Date.now().toString().substr(7);

            $("#branch").val("");
            $("#branch").val(<?php echo $branch; ?>).trigger("chosen:updated").trigger("change");
        <?php } ?>

        // 06/02/22 Bind the "change()" functions to the elements AFTER the page is loaded, so it doesn't change the default values from TRANSACTION MASTER -> (if $action="edit_transaction")
        $("#broker_name").change(function(e) {
            get_broker_hold_commission($(this).val());
        });
        $("#client_name").change(function(e) {
            get_client_account_no($(this).val(), '<?php echo $client_number; ?>');
        });
        $("#client_number").change(function(e) {
            add_new_client_no($(this));
        });
        $("select[name='batch']").change(function(e) {
            get_commission_date($(this).val());
        });
        $("#trade_date").change(function(e) {
            get_client_split_rates($("#client_name").val());
        });

        // 06/07/22 Rule Engine Modal Window
        <?php if (!empty($_SESSION['transaction_rule_engine']['warnings'])) { ?>
            resolve_rule_engine("<?php echo $_SESSION['transaction_rule_engine']['warnings']; ?>");
            load_split_commission_content(0, 0, 1);
        <?php } ?>

        $('#commission-received-date').datepicker({
            format: "mm/dd/yyyy",
            todayBtn: "linked",
            autoclose: true,
            todayHighlight: true
        });

        // 06/22/22 Hold Reason text area adding left spaces. Don't know why
        if ($("#hold_reason").length != 0) {
            var reasonText = $("#hold_reason").html().trim();
            $("#hold_reason").html(reasonText);
        }

        // 06/30/22 Don't submit the Rule Warning back to transaction.php
        $("#resolve_rule_engine_modal").on('submit', function(event) {
            event.preventDefault();
            var data = $("#resolve_rule_engine_modal :input").serializeArray();
            resolve_rule_engine_submit(data);
        });

        if (!isNaN($("#trade_number").val())) {
            <?php if (empty($_SESSION['transaction_rule_engine']['warnings'])) { ?>
                load_split_commission_content(0, $("#trade_number").val());
            <?php } ?>
        }

    })

    function hide_hold_reason() {
        // 06/29/22 Don't delete the Hold Reason may by needed for Compliance reports
        // $("#hold_reason").val("");
        $("#div_hold_reason").css('display', 'none');
    }

    $.fn.regexMask = function(mask) {
        $(this).keypress(function(event) {
            if (!event.charCode) return true;

            var part1 = this.value.substring(0, this.selectionStart);
            var part2 = this.value.substring(this.selectionEnd, this.value.length);

            if (!mask.test(part1 + String.fromCharCode(event.charCode) + part2))
                return false;
        });
    };

    function handleChange(input) {
        // 07/13/22 Set the "value" attribute so the spinner buttons will increment the latest value
        input.setAttribute('value', input.value);

        if (input.value < 0) input.value = 0.00;
        if (input.value > 100) input.value = 100.00;
    }

    $(function() {
        $('.decimal').chargeFormat();
    });

    // 05/14/22 Commented out. Not sure if this is necessary
    var client_number_ = "<?php echo $client_number; ?>";
    var transcation_form_data = "";

    if (localStorage.getItem('transcation_form_data')) {
        var transcation_form_data = JSON.parse(localStorage.getItem('transcation_form_data'));

        for (var key in transcation_form_data) {
            if (transcation_form_data[key]["value"] != '' && transcation_form_data[key]['name'] != 'product' || transcation_form_data[key]['name'] == 'product_cate') {
                document.querySelector("[name='" + transcation_form_data[key]["name"] + "']").value = transcation_form_data[key]["value"];
            }
        }
    }
    $(document).ready(function() {
        var client_ac_number = <?php echo empty(json_encode($client_account_array)) ? '' : json_encode($client_account_array); ?>;

        if (localStorage.getItem('transcation_form_data')) {
            for (var key in transcation_form_data) {
                if (transcation_form_data[key]["value"] != '' && transcation_form_data[key]['name'] != 'product_cate' && transcation_form_data[key]['name'] != 'product') {
                    $("[name='" + transcation_form_data[key]["name"] + "']").trigger("chosen:updated")
                }
            }
        }
        $('#ch_no').mask("999999");
    });

    function add_new_client_no(element) {
        if (element.val() == -1) {
            document.getElementById("account_no_row").style.display = "block";
        } else {
            document.getElementById("account_no_row").style.display="none";
        }
    }

    //get client account no on client select
    function get_client_account_no(client_id, selected, skipBroker = 0) {
        document.getElementById("client_number").innerHTML = "<option value=''>Please Wait...</option>";
        var dropdown = '';
        var xmlhttp = new XMLHttpRequest();

        // Skip on the page load
        if (!skipBroker) {
            var broker_name = $('select[name="client_name"]').find("option[value='" + client_id + "']").data("brokername");
            $('select[name="broker_name"]').val(broker_name).trigger("chosen:updated").trigger("change");
        }

        if (client_id && client_id != "0") {
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var options = JSON.parse(this.responseText);

                    dropdown += '<option value=""> Please Select  </option><option value="-1"> Add New </option>';
                    options.forEach(function(item) {
                        is_selected = (selected.trim() == item.trim() ? "selected" : "");
                        dropdown += "<option value='" + item.trim() + "' " + is_selected + ">" + item + "</option>";
                    })
                    document.getElementById("client_number").innerHTML = dropdown;
                }
            };
            xmlhttp.open("GET", "ajax_get_client_account.php?action=all&client_id=" + client_id, true);
            xmlhttp.send();
        } else {
            dropdown += '<option value=""> Please Select Client</option>';
            document.getElementById("client_number").innerHTML = dropdown;
        }

    }

    function get_client_id(client_number) {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200 && this.responseText != '0' && this.responseText != '') {
                $('#client_name').val(this.responseText).trigger("chosen:updated");
                //   alert($('#client_name').val());
            }
        };
        xmlhttp.open("GET", "ajax_get_client_account.php?client_number=" + client_number, true);
        xmlhttp.send();
    }

    //get default commission date on batch date
    function get_commission_date(batch_id) {
        var xmlhttp = new XMLHttpRequest();

        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var data = jQuery.parseJSON(this.responseText);

                $("#commission_received_date").val(data[0].batch_date);
                // 06/17/22  Pulling up the prior date, have to initialize the Datepicker value as well.
                $("#commission_received_date").datepicker('setDate', $("#commission_received_date").val());
                $("#sponsor").val(data[0].sponsor);

                $("#product_cate").val(data[0].pro_category);
                if (data[0].pro_category != '' && data[0].pro_category != '0') {
                    get_product(data[0].pro_category, data[0].sponsor);
                }
            }
        };
        xmlhttp.open("GET", "ajax_get_client_account.php?batch_id=" + batch_id, true);
        xmlhttp.send();
    }

    function setnumber_format(inputtext) {
        var number = inputtext.value;
        // var roundedNumber = Number((Math.floor(number * 100) / 100).toFixed(2))
        var roundedNumber = Number(number).toFixed(2);
        var options = {
            style: 'currency',
            currency: 'USD'
        };
        inputtext.value = (new Intl.NumberFormat(options).format(roundedNumber));
    }

    //get client split rate on client select
    // 07/14/22 Populate the Split Modal i/o of the split <div>
    function get_client_split_rates(client_id) {
        const trade_date = $("#trade_date").val();
        const product_category = $("#product_cate").val();
        // Remove current splits
        const clientSplits = $("input[name^='split_type[]'][value*='client=']");
        clientSplits.each((index, elem) => {
            $(elem).closest('tr').remove();
        })

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                const jsonResponse = JSON.parse(this.responseText);
                const split_broker = jsonResponse.split_broker;
                const split_rate = jsonResponse.split_rate;

                if (split_broker > 0 && split_rate > 0) {
                    // If the "add split" row is populated, add a blank row beneath to store the client split
                    if (parseFloat($('#add_split_broker').val()) > 0 && parseFloat($('#add_split_rate').val()) > '0') {
                        add_split_row(0);
                    }
                    $("#add_split_broker").val(split_broker);
                    $("#add_split_rate").val(split_rate);
                    $("#add_split_type").val('client=' + client_id);
                    add_split_row(0);
                }
                update_split_yes('get-client-split-rates()');
            }
        };
        xmlhttp.open("GET", "ajax_get_split_rates.php?client_id=" + client_id + "&trade_date=" + trade_date + "&product_category=" + product_category, true);
        xmlhttp.send();

    }

    // get broker split rate on broker select
    function get_broker_split_rates(broker_id) {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                open_split_modal();
                document.getElementById("broker_split_row").innerHTML = this.responseText;

                update_split_yes('get_broker_split_rates');
            }
        };
        xmlhttp.open("GET", "ajax_get_split_rates.php?broker_id=" + broker_id, true);
        xmlhttp.send();

    }

    //get broker override rate on broker select
    function get_broker_override_rates(broker_id) {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                $('.broker_override_class').remove();
                $(this.responseText).insertAfter('#add_override');
            }
        };
        xmlhttp.open("GET", "ajax_get_override_rates.php?broker_id=" + broker_id, true);
        xmlhttp.send();
    }

    function redirect_url(url, selector) {
        if (selector == "product") {
            url = url + "&category=12";
            // 07/07/22 Store user inputs so the form can be repopulated when redirected back here from product_cate.php
            const formdata = $('form[name="frm2"] :input').serializeArray();
            $.ajax({
                type: "POST",
                url: "ajax_transaction_tpl.php",
                data: {
                    addProdFromTrans: formdata
                },
                success: function(value) {
                    // console.log('Form Data posted...');
                }
            })           
        }
        
        url = url + "&advisory_url=1";
            $.ajax({
                type: "POST",
                url:url,
                data: {},
                success: function(value) {
                    // console.log('Form Data posted...');
                }
            })
        // localStorage.setItem("transcation_form_data",  JSON.stringify($("form[name='frm2']").serializeArray()));
        //-- 07/06/22 DELETE ME Uncomment the code below to redirect to the Add Product page
        setTimeout(function() {
            window.location.href = url
        }, 100);
        return false;
    }

    //get broker hold commission on broker select
    // 05/03/22 Add branch & company fetch from BROKER_BRANCHES as well
    function get_broker_hold_commission(broker_id) {
        load_split_commission_content(broker_id);

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                if (this.responseText) {
                    var jsonResponse = JSON.parse(this.responseText);
                    const hold_commissions = jsonResponse.hold_commission;

                    if (hold_commissions == 1) {
                        $("#hold_commission_1").prop("checked", true);
                        $("#div_hold_reason").css('display', 'block');
                        $("#hold_reason").val("HOLD COMMISSION BY BROKER");
                    } else {
                        $("#hold_commission_1").prop("checked", false);
                        $("#hold_commission_2").prop("checked", true);
                        $("#div_hold_reason").css('display', 'none');
                        $("#hold_reason").val("");
                    }

                    if (jsonResponse.branch > 0) {
                        $("#branch").val("");
                        $("#branch option[value='" + jsonResponse.branch + "']").prop("selected", true).trigger("change");
                        get_branch_company(jsonResponse.branch);

                        var currentdate = new Date();
                        var datetime = Date.now().toString().substr(7);
                    }
                } else {
                    $("#hold_commission_1").prop("checked", false);
                    $("#hold_commission_2").prop("checked", true);
                    $("#div_hold_reason").css('display', 'none');
                    $("#hold_reason").val("");
                }
                // 07/14/22 Update client splits - skip initial "Maintain Transaction" load
                get_client_split_rates($("#client_name").val());
            }
        };
        xmlhttp.open("GET", "ajax_transaction_tpl.php?broker_id=" + broker_id, true);
        xmlhttp.send();
    }

    function get_branch_company(branch_id) {
        var currentdate = new Date();
        var datetime = Date.now().toString().substr(7);

        var jsonResponse = '';
        var company_id = company_name = 0
        var xmlhttp = new XMLHttpRequest();

        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                jsonResponse = JSON.parse(this.responseText);

                if (jsonResponse.id > 0) {
                    $("#company").val("");
                    $("#company option[value='" + jsonResponse.company + "']").prop("selected", true).trigger("chosen:updated").trigger("change");
                }
            }
        };
        xmlhttp.open("GET", "ajax_transaction_tpl.php?action=branch_company&branch=" + branch_id, true);
        xmlhttp.send();
    }

    function load_split_commission_content(broker_id = 0, transaction_id = 0, use_rule_data = 0) {
        var xmlhttp = new XMLHttpRequest();

        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                $("#split_commission_modal").find(".modal-body tbody").html(this.responseText);
                $('#demo-dp-range .input-daterange').datepicker({
                    format: "mm/dd/yyyy",
                    todayBtn: "linked",
                    autoclose: true,
                    todayHighlight: true
                });

                update_split_yes('load_split_commission_content');
            }
        };
        const client_id = $("select[name='client_name']").val();
        const trade_date = $("#trade_date").val();
        //-- 07/11/22 Transaction ID / Trade number should be passed with the parameter call
        // transaction_id = $("#id").val();
        xmlhttp.open("GET", "ajax_transaction_tpl.php?action=split_commission&broker_id=" + broker_id + "&transaction_id=" + transaction_id + "&trade_date=" + trade_date + "&use_rule_data=" + use_rule_data, true);
        xmlhttp.send();

        return false;
    }

    function open_split_modal() {
        $("#split_commission_modal").modal();
        if ($("select[name='broker_name']").val() == '' || $("select[name='broker_name']").val() == 0) {
            $("#split_commission_modal").find(".modal-body tbody").html("<tr><td colspan='6'>Please select broker first</td> </td>")
        } else {
            //$("#split_commission_modal").find(".modal-body tbody").html("<tr><td colspan='6'>Please Wait....</td> </td>")
        }
    }

    function update_split_yes(calledFrom = '') {
        //   <select name="split_broker[]"  class="form-control" style="padding-right: 30px;">
        const splitBrokers = document.getElementsByName('split_broker[]');
        const splitRates = document.getElementsByName('split_rate[]');

        if (splitBrokers.length == 0 || splitBrokers[0]['value'] == '' || parseFloat(splitRates[0]['value']) == 0) {
            $("#split_no").prop('checked', true);
        } else {
            $("#split_yes").prop('checked', true);
        }
    }

    function close_other(callFrom = '') {
        $("#split_commission_modal").modal("hide");
    }

    // 05/14/22 Removed - messing up opening page values
    // 06/02/22 Reinstated - causing an error on "Save" submit
    var waitingDialog = waitingDialog || (function($) {
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
            show: function(message, options) {
                var isErrorFound = false;
                var trade_date = $("#trade_date");
                var client_name = $("#client_name");
                // var client_name_dropdown = $("#client_name_chosen");
                var client_number = $("#client_number");
                var broker_name = $("select[name='broker_name']");
                var batch = $("select[name='batch']");
                // var product_cate = $("#product_cate");
                var product = $("#product");
                var commission_received_date = $("#commission_received_date");
                var commission_received = $("input[name='commission_received']");
                // var split = $("input[name='split']");
                // var hold_commission = $("input[name='hold_commission']");
                var idName = "#id";
                var missingFields = "";

                var grossCommission = parseInt((document.getElementById("commission_received").value).replace(/,/g, ''));
                var getUpdateCommissionTotal = document.getElementById("totalvalueupdate").textContent;

                if (grossCommission != getUpdateCommissionTotal) {
                    alert('Error: The sum is not equal to ' + grossCommission);
                    return false;
                }

                if ($.trim(trade_date.val()) == '') {
                    isErrorFound = true;
                    trade_date.addClass("error");
                    idName = "#trade_date";
                    missingFields += (missingFields == "" ? "" : ", ") + "Trade Date";
                } else {
                    trade_date.removeClass("error");
                }
                if ($.trim(client_name.val()) == '' || $.trim(client_name.val()) == '0') {
                    isErrorFound = true;
                    client_name.next("div").find("a.chosen-single").addClass("error");
                    idName = "#client_name";
                    missingFields += (missingFields == "" ? "" : ", ") + "Client Name";

                } else {
                    client_name.next("div").find("a.chosen-single").removeClass("error");
                }
                
                if ($.trim(broker_name.val()) == '' || broker_name.val() == '0') {
                    isErrorFound = true;
                    broker_name.next("div").find("a.chosen-single").addClass("error");
                    idName = "select[name='broker_name']";
                    missingFields += (missingFields == "" ? "" : ", ") + "Broker";
                } else {
                    broker_name.next("div").find("a.chosen-single").removeClass("error");
                }

                if ($.trim(batch.val()) == '' || $.trim(batch.val()) == '0') {
                    isErrorFound = true;
                    batch.addClass("error");
                    idName = "select[name='batch']";
                    missingFields += (missingFields == "" ? "" : ", ") + "Batch";
                } else {
                    batch.removeClass("error");
                }

                if ($.trim(product.val()) == '' || $.trim(product.val()) == '0') {
                    isErrorFound = true;
                    product.addClass("error");
                    idName = "#product";
                    missingFields += (missingFields == "" ? "" : ", ") + "Product";
                } else {
                    product.removeClass("error");
                }

                if ($.trim(commission_received_date.val()) == '') {
                    isErrorFound = true;
                    commission_received_date.addClass("error");
                    idName = "#commission_received_date";
                    missingFields += (missingFields == "" ? "" : ", ") + "Received Date";
                } else {
                    commission_received_date.removeClass("error");
                }

                if ($.trim(commission_received.val()) == '') {
                    isErrorFound = true;
                    commission_received.addClass("error");
                    idName = "input[name='commission_received']";
                    missingFields += (missingFields == "" ? "" : ", ") + "Commission Received";
                } else {
                    commission_received.removeClass("error");
                }

                if (isErrorFound) {
                    alert('Please enter required field(s): ' + missingFields);
                    // 7/15/22 Scroll doesn't go to element. Tried $(window)..., focus(), new #overflow wrapper - didn't work. Have to move on. If you have a solution, please fix!
                    $("html,body").animate({
                        scrollTop: $(idName).offset().top
                    }, 200);
                    return false;
                }

                localStorage.setItem('transcation_form_data', "");

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
                    $dialog.off('hidden.bs.modal').on('hidden.bs.modal', function(e) {
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


    $('#demo-dp-range .input-daterange').datepicker({
        format: "mm/dd/yyyy",
        todayBtn: "linked",
        autoclose: true,
        todayHighlight: true
    });

    function get_investment_amount() {
        var units = $("#units").val();
        var shares = $("#shares").val();

        if ((units > 0) && (shares > 0)) {
            var invest_amount = units * shares;
            $("#invest_amount").val(invest_amount);
        }
    }

    var flag1 = 0;

    function add_split_row(doc, split_type = 'user_added', split_broker = '', split_rate = '0.00') {
        //-- 07/11/22 SPLIT REP/RATE Modal window dlements - removed Start/Until dates. Not needed for Split Trades table
        if (flag1 == 0) {
            const flag1 = doc + 1;
        } else {
            flag1++;
        }

        const html = '<tr class="tr">' +
            '<input type="hidden" name="split_type[]" value="' + split_type + '" />' +
            '<td>' +
            '<select name="split_broker[]"  class="form-control" >' +
            '<option value="">Select Broker</option>' +
            <?php foreach ($get_broker as $key => $val) { ?> "<option value='<?php echo $val['id'] ?>'><?php echo strtoupper($val['last_name'] . (($val['last_name'] == '' || $val['first_name'] == '') ? '' : ', ') . $val['first_name']) ?></option>" +
            <?php } ?> '</select>' +
            '</td>' +
            '<td>' + '<div class="input-group">' +
            '<input type="number" name="split_rate[]" step="1.00" onchange="handleChange(this);" onblur="test" value="" class="form-control" />' + '<span class="input-group-addon">%</span>' + '</div>' +
            '</td>' +
            '<td>' +
            '<button type="button" tabindex="-1" class="btn remove-row btn-icon btn-circle"><i class="fa fa-minus"></i></button>' +
            '</td>' +
            '</tr>';

        // 07/12/22 Populate the new row with the "Add Split" row values, so the blank row(#add_split_row) will be at the bottom
        $(html).insertBefore('#add_split_row');
        const addRowRep = $('#add_split_broker').val();
        const addRowRate = $('#add_split_rate').val();
        const addRowType = $('#add_split_type').val();
        const splitBrokers = document.getElementsByName("split_broker[]");
        const splitRates = document.getElementsByName("split_rate[]");
        const splitTypes = document.getElementsByName("split_type[]");
        splitBrokers[splitBrokers.length - 2].value = addRowRep;
        splitRates[splitRates.length - 2].value = addRowRate != "" ? addRowRate : "0.00";
        splitTypes[splitTypes.length - 2].value = addRowType;
        splitBrokers[splitBrokers.length - 1].value = split_broker;
        splitRates[splitRates.length - 1].value = split_rate;
        splitTypes[splitTypes.length - 1].value = split_type;
    }

    $(document).on('click', '.remove-row', function() {
        var deleteRows = []
        deleteRows.push($(this).closest('.tr').data("rowid"));
        $("#deleted_rows").val(deleteRows.join(","));
        $(this).closest('.tr').remove();
    });

    $(document).on('click', '#add_cheque_info .submit_account', function() {
        var modalSelector = $('#add_cheque_info');
        var isErrorFound = false;
        modalSelector.find('input.required').each(function() {
            var selector = $(this);
            var value = selector.val();

            if (value == '') {
                selector.addClass('error');
                isErrorFound = true;
            } else selector.removeClass('error');
        })

        if (!isErrorFound) {
            modalSelector.modal('hide');
        }
    });

    function resolve_rule_engine(msg = '') {
        $("#msg_exception").html(msg);
        $("#resolve_rule_engine_modal").modal("show");
    }

    // 06/30/22 Keep processing on this page, and trigger the Save/Save&Copy/Cancel buttons programmitically
    function resolve_rule_engine_submit(posts) {
        $('#msg_exception').html('<div class="alert alert-info"><i class="fa fa-spinner fa-spin"></i> Please wait...</div>');
        $("#rule_engine_warning_action").val(posts[0]['value']);

        var action = '';
        <?php if (isset($_SESSION['transaction_rule_engine']['data']['transaction'])) { ?>
            action = "<?php echo $_SESSION['transaction_rule_engine']['data']['transaction'] ?>";
        <?php } ?>
        if ($("#rule_engine_warning_action").val() == '3') {
            action = 'Cancel';
        }

        $("#resolve_rule_engine_modal").modal("hide");
        $(".alert").remove();

        switch (action) {
            case 'Save':
                $("#save").trigger("click");
                break;
            case 'Save & Copy':
                $("#transaction").trigger("click");
                break;
            default:
                $("#cancel").click();
        }

        return false;
    }

</script>

<style type="text/css">
    .toolbar {
        float: right;
        padding-left: 5px;
    }

    .chosen-container-single .chosen-single {
        height: 34px !important;
    }

    #new_transcation_wrap label {
        min-height: 30px;
    }

    .btn-primary {
        color: #fff;
        background-color: #337ab7 !important;
        border-color: #2e6da4 !important;
    }

    #table-scroll {
        height: 400px;
        overflow: auto;
        margin-top: 20px;
    }

    .multi-checkbox-row {
        font-size: 12px;
    }
</style>