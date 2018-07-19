function conf(url){
    bootbox.confirm({
        message: "Are you sure?", 
        backdrop: true,
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i> Yes',
                className: 'btn-success'
            },
            cancel: {
                label: '<i class="fa fa-warning"></i> No',
                className: 'btn-warning'
            }
        },
        callback: function(result) {
            if (result) {
                window.location.href = url;
            }else{
                //return false;
            };
        }
    });
}
function conf_save(url){
    bootbox.confirm({
        message: "Confirm that the data has been save?", 
        backdrop: true,
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i> Yes',
                className: 'btn-success'
            },
            cancel: {
                label: '<i class="fa fa-warning"></i> No',
                className: 'btn-warning'
            }
        },
        callback: function(result) {
            if (result) {
                window.location.href = url;
            }else{
                //return false;
            };
        }
    });
}
$(document).ready(function(){
    $('.form-validate').validate ({
        // validation rules for registration form
       
        errorClass: "text-red",
        validClass: "text-green",
        errorElement: 'div',
        errorPlacement: function(error, element) {
            if(element.parent('.input-group').length) {
                error.insertAfter(element.parent());
            }
			else if (element.hasClass('select2')) {     
				error.insertAfter(element.next('span'));  // select2
			} else {
                error.insertAfter(element);
            }
        },
        onError : function(){
            $('.input-group.error-class').find('.help-block.form-error').each(function() {
                $(this).closest('.form-group').addClass('error-class').append($(this));
            });
        },
    
    });
    
    $('.form-validate-system').validate ({
        // validation rules for registration form
        rules: {
            /*'sipc': {
                minlength: 6
            }*/
        },
        errorClass: "text-red",
        validClass: "text-green",
        errorElement: 'div',
        errorPlacement: function(error, element) {
            if(element.parent('.input-group').length) {
                error.insertAfter(element.parent());
            }
			else if (element.hasClass('select2')) {     
				error.insertAfter(element.next('span'));  // select2
			} else {
                error.insertAfter(element);
            }
        },
        onError : function(){
            $('.input-group.error-class').find('.help-block.form-error').each(function() {
                $(this).closest('.form-group').addClass('error-class').append($(this));
            });
        },
    
    });
});
function isFloatNumber(item,evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode==46)
    {
        var regex = new RegExp(/\./g)
        var count = $(item).val().match(regex).length;
        if (count > 1)
        {
            return false;
        }
    }
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}