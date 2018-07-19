function conf(url){
    bootbox.confirm({
        message: "Are you sure?", 
        backdrop: true,
        buttons: {
            confirm: {
                label: '<i class="ion-android-done-all"></i> Yes',
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
    $('.overlay').css('display','none');
    $('form').submit(function(){
        $('.overlay').css('display','block');
    });
    $(document).on('click','a[href]:not([href^="mailto\\:"], [href$="\\#"])',function(){
        $('.overlay').css('display','block');
    });
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
            $('.overlay').css('display','none');
        },
        onError : function(){
            $('.input-group.error-class').find('.help-block.form-error').each(function() {
                $(this).closest('.form-group').addClass('error-class').append($(this));
            });
            $('.overlay').css('display','none');
        },
    
    });
    
});