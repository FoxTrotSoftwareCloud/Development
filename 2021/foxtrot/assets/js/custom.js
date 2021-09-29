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

/**
 * AJAX request.
 * @param {string} url
 * @param {array} pars
 * @param {function} success
 * @param {runction} error
 * @returns {undefined}
 */
function AjaxRequest(url, pars, success, error)
{
  $.ajax({
    type: 'POST',
    url: url,
    data : pars,
    dataType: 'json',
    timeout: 300000,
    jsonp: 'jsonp_callback',
    xhrFields: {
      withCredentials: true
    },
    success: function(data)
    {
      var code = data.code;
      var message = data.message;
      switch(code)
      {
        case 200:
          if (data.cookies)
          {
            var cookies = data.cookies;
            for (var index in cookies)
            {
              $.cookie(index, cookies[index], {path    : '/', secure  : false});
            }
          }
          if (typeof(success) === "function")
            success(data);
          if (data.redirectUrl)
            window.location = data.redirectUrl;
          if (data.reload)
            location.reload();
          break;
        case 400:
          if (typeof(error) === "function")
            error(message);
          break;
        default:
          break;
      }
    },
    error: function(jqXHR)
    {
      let jsonValue = jQuery.parseJSON( jqXHR.responseText );
      if (typeof(error) === "function")
        error(jsonValue.message);
    }
  });
}