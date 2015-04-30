$(function(){


    $('.petun-form').ptmForm(
        {
            'renderTo': '.form-result',
            'onSuccess': function(form) {
                window.setTimeout(function(){$('#callbackForm').modal('toggle')} , 2000);
            }
        }
    );

});