<div class="container">
    <h1>Rules Engine</h1>
    
    <div class="col-lg-12 well">
        <?php require_once(DIR_FS_INCLUDES."alerts.php"); ?>
        <form method="post">
            <div class="table-responsive">
    			<table id="data-table" class="table table-striped table-bordered" cellspacing="0" width="100%">
    	            <thead>
    	                <tr>
                            <th>In Force</th>
                            <th>Rule</th>
                            <th>Action</th>
                            <th>Parameter1(Minimum)</th>
                            <th>Parameter2(Maximum)</th>
                        </tr>
    	            </thead>
    	            <tbody>
                    <?php 
                    if(isset($return) && !empty($return)){                   
                        $count = 0;
                        foreach($return as $get_key=>$get_val){
                            foreach($get_rules as $key=>$val){
                                if($get_val['rule']==$val['id']){
                                ?>
            	                   <tr>
                                        <td><input type="checkbox" class="checkbox" <?php if(isset($get_val['in_force']) && $get_val['in_force']==1){?>checked="true"<?php } ?> value="1" name="data[<?php echo $key; ?>][in_force]"/></td>
                                        <td><?php echo $val['rule'];?></td><input type="hidden" value="<?php echo $val['id'];?>" name="data[<?php echo $key; ?>][rule]"/>
                                        <td style="width: 20%;">
                                            <select class="form-control" id="select_action_<?php echo $key;?>" onchange="open_other(<?php echo $key;?>)" name="data[<?php echo $key; ?>][action]">
                                                <option value="0">Select Action</option>
                                                <?php foreach($get_rules_action as $key1=>$val1){?>
                                                <option value="<?php echo $val1['id'];?>" <?php if(isset($get_val['action']) && $get_val['action']==$val1['id']){ ?>selected="true"<?php } ?>><?php echo $val1['action'];?></option>
                                                <?php } ?>
                                            </select>
                                        </td>
                                        <td>
                                            <?php if($val['id'] == 3){?>
                                            <div id="other_div<?php echo $key;?>" class="form-group" <?php if(isset($get_val['action']) && $get_val['action']=='5'){?>style="display: none;"<?php }?>>
                                                 <div class="input-group">
                                                  <input type="text" class="form-control decimal" onchange="handleChange(this);" value="<?php if(isset($get_val['parameter_1']) && $get_val['parameter_1']!=''){echo $get_val['parameter_1'];}?>" name="data[<?php echo $key; ?>][parameter_1]" maxlength="13" />
                                                  <span class="input-group-addon">%</span>
                                                </div>
                                            </div>
                                            <?php } else if($val['id'] == 16){?>
                                            <div id="other_div<?php echo $key;?>" class="form-group" <?php if(isset($get_val['action']) && $get_val['action']=='5'){?>style="display: none;"<?php }?>>
                                                 <div class="input-group">
                                                  <input type="text" class="form-control" value="<?php if(isset($get_val['parameter_1']) && $get_val['parameter_1']!=''){echo $get_val['parameter_1'];}?>" name="data[<?php echo $key; ?>][parameter_1]" maxlength="4" />
                                                  <span class="input-group-addon">$</span>
                                                </div>
                                            </div>
                                            <?php } else { ?>
                                            <div id="other_div<?php echo $key;?>" class="form-group" <?php if(isset($get_val['action']) && $get_val['action']=='5'){?>style="display: none;"<?php }?>>
                                                <input type="text" class="form-control" value="<?php if(isset($get_val['parameter_1']) && $get_val['parameter_1']!=''){echo $get_val['parameter_1'];}?>" name="data[<?php echo $key; ?>][parameter_1]" maxlength="13" onkeypress='return (event.charCode >= 48 && event.charCode <= 57) || event.charCode==46 || event.charCode==45' />
                                            </div>
                                            <?php } ?>
                                            <div id="other_div_1<?php echo $key;?>" class="form-group" <?php if(isset($get_val['action']) && $get_val['action']!='5'){?>style="display: none;"<?php } ?>>
                                                <select class="form-control" name="data[<?php echo $key; ?>][parameter1]">
                                                    <option value="0">Select Broker</option>
                                                    <?php foreach($get_broker as $key2=>$val2){?>
                                                    <option value="<?php echo $val2['id'];?>" <?php if(isset($get_val['parameter1']) && $get_val['parameter1']==$val2['id']){ ?>selected="true"<?php } ?>><?php echo $val2['first_name'].' '.$val2['last_name'];?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <?php if($val['id'] != 3 && $val['id'] != 16){?>
                                            <div id="other_div_2<?php echo $key;?>" class="form-group" <?php if(isset($get_val['action']) && $get_val['action']=='5'){?>style="display: none;"<?php }?>>
                                                <input type="text" class="form-control" value="<?php if(isset($get_val['parameter_2']) && $get_val['parameter_2']!=''){echo $get_val['parameter_2'];}?>" name="data[<?php echo $key; ?>][parameter_2]" maxlength="13" onkeypress='return (event.charCode >= 48 && event.charCode <= 57) || event.charCode==46 || event.charCode==45'/>
                                            </div>
                                            <?php } ?>
                                            <!--<div id="other_div_3<?php echo $key;?>"  class="form-group" <?php if(isset($get_val['action']) && $get_val['action']!='5'){?>style="display: none;"<?php } ?>>
                                                <select class="form-control" name="data[<?php echo $key; ?>][parameter2]">
                                                    <option value="0">Select Broker</option>
                                                    <?php foreach($get_broker as $key2=>$val2){?>
                                                    <option value="<?php echo $val2['id'];?>" <?php if(isset($get_val['parameter2']) && $get_val['parameter2']==$val2['id']){ ?>selected="true"<?php } ?>><?php echo $val2['first_name'].' '.$val2['last_name'];?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>-->
                                        </td>
                                    </tr>
                            <?php } } } } else {
                                $count = 0;
                    foreach($get_rules as $key=>$val){
                        ?>
    	                   <tr>
                                <td><input type="checkbox" class="checkbox" value="1" name="data[<?php echo $key; ?>][in_force]"/></td>
                                <td><?php echo $val['rule'];?></td><input type="hidden" value="<?php echo $val['id'];?>" name="data[<?php echo $key; ?>][rule]"/>
                                <td>
                                    <select class="form-control" id="select_action_<?php echo $key;?>" onchange="open_other(<?php echo $key;?>)" name="data[<?php echo $key; ?>][action]">
                                        <option value="0">Select Action</option>
                                        <?php foreach($get_rules_action as $key1=>$val1){?>
                                        <option value="<?php echo $val1['id'];?>" <?php /*if(isset($pro_category) && $pro_category==$val['id']){ ?>selected="true"<?php } */?>><?php echo $val1['action'];?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                                <td>
                                    <?php if($val['id'] == 3){?>
                                    <div id="other_div<?php echo $key;?>" class="form-group">
                                         <div class="input-group">
                                          <input type="text" class="form-control decimal" onchange="handleChange(this);" name="data[<?php echo $key; ?>][parameter_1]" maxlength="13" onkeypress='return (event.charCode >= 48 && event.charCode <= 57) || event.charCode==46 || event.charCode==45' />
                                          <span class="input-group-addon">%</span>
                                        </div>
                                    </div>
                                    <?php } else if($val['id'] == 16){?>
                                    <div id="other_div<?php echo $key;?>" class="form-group">
                                         <div class="input-group">
                                          <input type="text" class="form-control" name="data[<?php echo $key; ?>][parameter_1]" maxlength="4" onkeypress='return (event.charCode >= 48 && event.charCode <= 57) || event.charCode==46 || event.charCode==45' />
                                          <span class="input-group-addon">$</span>
                                        </div>
                                    </div>
                                    <?php } else { ?>
                                    <div id="other_div<?php echo $key;?>" class="form-group" >
                                        <input type="text" class="form-control" name="data[<?php echo $key; ?>][parameter_1]" maxlength="13" onkeypress='return (event.charCode >= 48 && event.charCode <= 57) || event.charCode==46 || event.charCode==45' />
                                    </div>
                                    <?php } ?>
                                    <div id="other_div_1<?php echo $key;?>" class="form-group" style="display: none;">
                                        <select class="form-control" name="data[<?php echo $key; ?>][parameter1]">
                                            <option value="0">Select Broker</option>
                                            <?php foreach($get_broker as $key2=>$val2){?>
                                            <option value="<?php echo $val2['id'];?>" <?php /*if(isset($pro_category) && $pro_category==$val2['id']){ ?>selected="true"<?php }*/ ?>><?php echo $val2['first_name'].' '.$val2['last_name'];?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    <?php if($val['id'] != 3 && $val['id'] != 16){?>
                                    <div id="other_div_2<?php echo $key;?>" class="form-group" >
                                        <input type="text" class="form-control" name="data[<?php echo $key; ?>][parameter_2]" maxlength="13" onkeypress='return (event.charCode >= 48 && event.charCode <= 57) || event.charCode==46 || event.charCode==45'/>
                                    </div>
                                    <?php } ?>
                                    <!--<div id="other_div_3<?php echo $key;?>"  class="form-group" style="display: none;">
                                        <select class="form-control" name="data[<?php echo $key; ?>][parameter2]">
                                            <option value="0">Select Broker</option>
                                            <?php foreach($get_broker as $key2=>$val2){?>
                                            <option value="<?php echo $val2['id'];?>" <?php /*if(isset($pro_category) && $pro_category==$val2['id']){ ?>selected="true"<?php }*/ ?>><?php echo $val2['first_name'].' '.$val2['last_name'];?></option>
                                            <?php } ?>
                                        </select>
                                    </div>-->
                                </td>
                                
                            </tr>
                    
                            <?php } }?>
                    </tbody>
                </table>
            </div>
            <div class="panel-footer fixedbtmenu">
                <!--<div class="col-md-12">
                    <div class="form-group "><br />-->
                    <div class="selectwrap">
                        <a href="<?php echo SITE_URL.'branch_maintenance.php';?>"><input type="button" name="cancel" value="Cancel" style="float: right;" /></a>
                        <input type="submit" name="rule" onclick="waitingDialog.show();" value="Save" style="float: right;" />	
                    </div>
                   <!-- </div>
                 </div>-->
            </div>
        </form>
    
    </div>
</div>

<script>
(function($) {
$.fn.decimalFormat = function() {
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
$('.decimal').decimalFormat();
});
function handleChange(input) {
    if (input.value < 0) input.value = 0;
    if (input.value > 100) input.value = 100;
}
function open_other(tag)
{
    var selectVal = $('#select_action_'+tag).val();
    if(selectVal == '5')
    {
        $('#other_div'+tag).css('display','none');
        $('#other_div_1'+tag).css('display','block');
        $('#other_div_2'+tag).css('display','none');
        $('#other_div_3'+tag).css('display','block');
    }
    else
    { 
        $('#other_div'+tag).css('display','block');
        $('#other_div_1'+tag).css('display','none');
        $('#other_div_2'+tag).css('display','block');
        $('#other_div_3'+tag).css('display','none');
    }
}

</script>