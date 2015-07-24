$.fn.ptmForm = function (options) {

    function supportAjaxUploadProgressEvents() {
        var xhr = new XMLHttpRequest();
        return !!(xhr && ('upload' in xhr) && ('onprogress' in xhr.upload));
    }

    var settings = $.extend({
        'renderTo': '.form-result',
        'successClass': 'form-result__success',
        'errorClass': 'form-result__error',
        'loadingClass': 'form-result__loading',
        'handler': 'handler.php',
        'onSuccess': function (form) {
        }
    }, options);

    //console.log(settings);

    return this.each(function () {

        var resultDiv = $(settings.renderTo, this);

        $(this).on('submit', function (e) {

            var form = this;


            // remove previous validation class and messages
            // remove form-group has-error
            $('.form-group', form).removeClass('has-error');
            $('*[data-error-message]').text('');

            e.preventDefault();

            resultDiv
                .html("")
                .removeClass(settings.successClass)
                .removeClass(settings.errorClass)
                .addClass(settings.loadingClass);


            $.ajax({
                url: settings.handler,
                method: 'post',
                data: supportAjaxUploadProgressEvents() ? new FormData($(this)[0]) : $(this).serialize(),
                dataType: 'json',
                contentType: supportAjaxUploadProgressEvents() ? false : 'application/x-www-form-urlencoded; charset=UTF-8',
                processData: !supportAjaxUploadProgressEvents(),

                success: function (r) {
                    //console.log(r);
                    resultDiv.removeClass(settings.loadingClass);
                    resultDiv.html("<p>" + r.message + "</p>");
                    if (r.r) {
                        resultDiv.addClass(settings.successClass);
                        form.reset();

                        settings.onSuccess(form);

                        if (r.redirect) {
                            window.location.replace(r.redirect);
                        }

                    } else {
                        resultDiv.addClass(settings.errorClass);
                        if (r.errors) {
                            // result div
                            var html = resultDiv.html() + '<ul>';
                            for (i in r.errors) {
                                //console.log(i);
                                html += "<li>" + r.errors[i] + "</li>";

                                // if we use bootstrap forms
                                if ($('*[name=' + i + ']', form).parent().hasClass('form-group')) {
                                    $('*[name=' + i + ']', form).parent().addClass('has-error');
                                }

                                // if we have elements to render error message
                                if ($('*[data-error-message=' + i + ']', form).size() > 0) {
                                    $('*[data-error-message=' + i + ']', form).text(r.errors[i]);
                                }


                            }
                            html += '</ul>';
                            resultDiv.html(html);

                            // bootstrap errors support


                        }
                    }
                }
            });

            return false;
        });


        // plugin block


    });
};