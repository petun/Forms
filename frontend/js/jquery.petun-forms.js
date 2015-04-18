$.fn.ptmForm = function (options) {

    function supportAjaxUploadProgressEvents() {
        var xhr = new XMLHttpRequest();
        return !! (xhr && ('upload' in xhr) && ('onprogress' in xhr.upload));
    }

    var settings = $.extend({
        'renderTo': '.formResult',
        'successClass': 'text-success',
        'errorClass': 'text-danger',
        'loadingClass': 'text-info',
        'handler': 'handler.php'
    }, options);

    //console.log(settings);

    return this.each(function () {

        var resultDiv = $(settings.renderTo);

        $(this).on('submit', function(e){

            var form = this;

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

                success: function(r) {
                    resultDiv.removeClass(settings.loadingClass);
                    resultDiv.html("<p>" + r.message + "</p>");
                    if (r.r) {
                        resultDiv.addClass(settings.successClass);
                        form.reset();
                    } else {
                        resultDiv.addClass(settings.errorClass);
                        if (r.errors) {
                            resultDiv.html(resultDiv.html() + "<ul>");
                            for (i in r.errors) {
                                resultDiv.html(resultDiv.html() + "<li>" + r.errors[i] + "</li>");
                            }
                            resultDiv.html(resultDiv.html() + "</ul>");
                        }
                    }
                }
            });

            return false;
        });


        // plugin block


    });
};