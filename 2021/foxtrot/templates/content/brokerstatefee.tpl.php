<div class="container">
<h1>Broker State Licensing Fees</h1>
    <div class="col-md-12 well">
        <form method="POST" onsubmit="return validateForm(this)">
        <div class="row">
            <?php  if(isset($_GET['msg']) && $_GET['msg']=='success'): ?>
                <div class="alert alert-success"> fees has been updated successfully. </div>
            <?php endif; ?>
           <!--  <div class="col-md-6">
                <div class="form-group">
                    <label>Brokers </label>
                    <select class="form-control" name="broker" id="broker_dropdown" onchange="loadStateFee(this)">
                                <option value="0">All Brokers</option>
                                <?php foreach($get_brokers as $brokerN): ?>
                                    <option value="<?php echo $brokerN['id']; ?>" <?php echo isset($broker_id) && $broker_id==$brokerN['id'] ? 'selected' : '' ?>><?php echo $brokerN['last_name'].', '.$brokerN['first_name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                </div>
             </div> -->
        </div>
        <br />
        <div class="panel" id="report_filters">
       
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12">
                        
                            <label>States </label><br/>
                           
                           <div id="state-dynamic-content">
                            <?php
                              
                             // print_r($feeData);
                             foreach($get_states as $state):

                                $state_fee = isset($feeData[$state['id']]) ? $feeData[$state['id']] : 0.00;
                              ?>
                                <div class="col-md-3">
                                   <div class="form-group">
                                        <input type="hidden" name="state_id[]" value="<?php echo $state['id']; ?>">
                                         <input maxlength="8"  style="WIDTH: 50%;FLOAT: LEFT;MARGIN-RIGHT: 10PX;" type="text" class="currency-input form-control" name="state_fee[]" value="<?php echo $state_fee; ?>"> 
                                          <label><?php  echo $state['name']; ?></label>
                                    </div>
                                </div>    
                             <?php endforeach; ?>
                            </div> 
                       
                    </div>
                </div>
                
            </div>
        </div>
        </div>
        <div class="panel-footer">
            <div class="selectwrap">
                <div class="selectwrap">
                   
                    <input type="submit" name="submit"  value="Save" style="float: right;"/> 
                </div>
            </div>
        </div>
        
        </form>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js" integrity="sha512-Rdk63VC+1UYzGSgd3u2iadi0joUrcwX0IWp2rTh6KXFoAmgOjRS99Vynz1lJPT8dLjvo6JZOqpAHJyfCEZ5KoA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript">
    jQuery(function($){
         $('.currency-input').maskMoney();
    })
    function loadStateFee(broker){
          $.ajax({
                     url:"ajax_broker_state_fee.php",
                     data:{broker_id:broker.value},
                     type:"post",
                     dataType:"json",
                     beforeSend:function(){
                             
                                $("#state-dynamic-content").html("<div class='col-md-12 text-center'><i style='font-size:60px;' class='fa fa-spinner fa-spin'></i></div>");
                     },
                     success:function(res){
                        if(res.status){
                            data = res.data;
                          
                            $("#state-dynamic-content").html(data);
                             $('.currency-input').maskMoney();
                        }
                        else{
                              alert(res.message);
                        }
                     }
          })
    }
    function validateForm(form){
        var brokerName= $('select[name="broker"]');
            if(brokerName.val()=='' || brokerName.val() == 0){
                alert("Please Select Broker first");
                 return false;
            }
        return true
    }
</script>
