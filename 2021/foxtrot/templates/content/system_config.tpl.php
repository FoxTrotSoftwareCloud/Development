<div class="container">
<h1>System Configuration</h1> 
 <div class="col-lg-12 well">
 <?php require_once(DIR_FS_INCLUDES."alerts.php"); ?>
        <div class="panel">        
        <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-pencil-square-o"></i> Add System Configuration</h3>
		</div>
        <form class="form-validate-system" name="frm" method="POST" enctype="multipart/form-data">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Company Name </label>
                        <input type="text" class="form-control"  name="company_name" value="<?php echo $company_name; ?>" required="required" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Address 1 </label>
                        <input type="text" class="form-control" name="address1" value="<?php echo $address1; ?>" required="required" />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Address 2 </label>
                        <input type="text" class="form-control" name="address2" value="<?php echo $address2 ;?>"  required="required"/>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>City </label>
                        <input type="text" class="form-control" name="city" value="<?php echo $city; ?>" required="required"/>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>State </label>
                        <select name="state" id="state" class="form-control">
                            <option value="">Select State</option>
                            <?php foreach($get_state as $key=>$val){ ?>
                            <option value="<?php echo $val['id'];?>" <?php if($state != '' && $state==$val['id']){echo "selected='selected'";} ?>><?php echo $val['name'];?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Zip code </label>
                        <input type="text" class="form-control" name="zip" value="<?php echo $zip; ?>" required="required" />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Minimum Check Amount </label>
                        <input type="text" class="currency form-control" required="required" onkeypress='return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46' value="<?php echo $minimum_check_amount;?>" maxlength="8"  name="minimum_check_amount" id="minimum_check_amount"  />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group"> 
                        <label>FINRA Assessment </label>
                        <input type="number" value="<?php echo $finra;?>" required="required" min="0" max=" 9.99999999" class="form-control" name="finra" id="finra"  />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>SIPC Assessment </label>
                        <input type="number" value="<?php echo $sipc;?>"  required="required" min="0" max=" 9.99999999" class="form-control" name="sipc"  />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Logo </label>
                        <input type="file" class="form-control" name="logo" value="<?php echo $logo;?>" onchange="readURL(this);" />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label> <img src="<?php echo SITE_URL."upload/logo/"."thumb_".$logo;?>" id="logo_img" class="img img-thumbnail img-lg" height="100" width="100"/></label>
                    </div>
				</div>
                
            </div><br />
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <input type="checkbox" class="checkbox" name="brocker_pick_lists" value="1" style="display: inline;" <?php if($brocker_pick_lists==1){?>checked="true"<?php } ?> id="brocker_pick_lists" />&nbsp;
                        <label>Display Terminated Brokers on Pick-Lists</label>&nbsp;&nbsp;&nbsp;&nbsp;
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <input type="checkbox" class="checkbox" name="branch_pick_lists" value="1" style="display: inline;" <?php if($branch_pick_lists==1){?>checked="true"<?php } ?> id="branch_pick_lists" />&nbsp;
                        <label>Display Terminated Branches of Pick-lists</label>&nbsp;&nbsp;&nbsp;&nbsp;
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">       
                        <input type="checkbox" class="checkbox" name="brocker_statement" value="1" style="display: inline;" <?php if($brocker_statement==1){?>checked="true"<?php } ?> id="brocker_statement" />&nbsp;
                        <label>Exclude Terminated Brokers on Statements</label>&nbsp;&nbsp;&nbsp;&nbsp;
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <input type="checkbox" class="checkbox" name="firm_does_not_participate" value="1" style="display: inline;" <?php if($firm_does_not_participate==1){?>checked="true"<?php } ?> id="firm_does_not_participate" />&nbsp;
                        <label>Firm does not participate in commission loss</label>&nbsp;&nbsp;&nbsp;&nbsp;
                    </div>
                </div>
            </div>
            </div>
            <div class="panel-footer fixedbtmenu">
                <div class="selectwrap">
					<input type="button" name="cancel" class="btn btn-warning btn-lg btn3d " onclick="<?php echo CURRENT_PAGE;?>" value="Cancel" style="float: right;"/>
                    <input type="submit" name="submit" class="btn btn-warning btn-lg btn3d " value="Save" style="float: right;"/>
                    <!-- <input type="button" name="proceed" onclick="waitingDialog.show();" class="btn btn-warning btn-lg btn3d " value="Proceed"/> -->
				</div>
            </div>
        </form>
        </div>
    </div>
</div>
<script type="text/javascript">
$('input#minimum_check_amount').blur(function() {
    var amt = parseFloat(this.value);
    $(this).val(amt.toFixed(2));
});

function round(feerate)
{
    if(feerate>100)
    {
        var rounded = 99.9;
    }
    else
    {
        var round = Math.round( feerate * 10 ) / 10;
        var rounded = round.toFixed(8);
    }
    document.getElementById("finra").value = rounded;
    
}/*
$( function() {
        $('.currency1').currencyFormat1();
    });
(function($) {
        $.fn.currencyFormat = function() {
            this.each( function( i ) {
                $(this).change( function( e ){
                    if( isNaN( parseFloat( this.value ) ) ) return;
                    this.value = parseFloat(this.value).toFixed(2);
                });
            });
            return this; //for chaining
        }
    })( jQuery );

  
    $( function() {
        $('.currency').currencyFormat();
    });
    
(function($) {
        $.fn.currencyFormat1 = function() {
            this.each( function( i ) {
                $(this).change( function( e ){
                    if( isNaN( parseFloat( this.value ) ) ) return;
                    this.value = parseFloat(this.value).toFixed(8);
                });
            });
            return this; //for chaining
        }
    })( jQuery );

  
    $( function() {
        $('.currency1').currencyFormat1();
    }); */    
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
function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#logo_img')
                .attr('src', e.target.result)
                .width(150)
                .height(200);
        };

        reader.readAsDataURL(input.files[0]);
    }
}
</script>