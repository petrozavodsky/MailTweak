var $ = jQuery.noConflict();

var MailTweakEmailTestScript = {
    action: false,
    run: function () {
        this.action = $('[data-action]').attr('data-action');
        this.click();
    },
    click: function () {
        var this_class = this;
        $("[data-id='button']").on('click', function (e) {
            e.preventDefault();
            if (!$(this).hasClass('progress')) {
                $(this).addClass('progress');
                $("[data-id='alert']").text($("[data-text]").attr('data-text'));
                this_class.ajax();
            }
        });

    },
    ajax: function () {
        var this_class = this;
        $.post(
            this_class.action,
            {
                email: $("[data-id='email']").val(),
                subject: $("[data-id='subject']").val(),
                message: $("[data-id='message']").val()
            },

            function (result) {
                $("[data-id='button']").removeClass('progress');

                if (false === result.success) {
                    if (false === result.data.validate) {
                        $("[data-id]").removeClass('error');
                        for (var key in result.data.errors) {
                            this_class.erros_higliter(key);
                        }
                    }
                } else {
                    $("[data-id='alert']").text(result.data.message);
                }
            }
        );
    },
    erros_higliter: function (key) {
        $("[data-id='" + key + "']").addClass('error');
    }
};

$(document).ready(function () {
    MailTweakEmailTestScript.run();
});