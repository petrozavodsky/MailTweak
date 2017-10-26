var $ = jQuery.noConflict();

var MailTweakEmailTestScript = {
    action: false,
    run: function () {
        this.action = $('[data-action]').attr('data-action');
        this.click();
    },
    click: function () {
        var this_class= this;
      $("[data-id='button']").on('click', function (e) {
          e.preventDefault();
          alert('4445445');
          this_class.ajax();
      })  ;
    },
    ajax: function () {
        var this_class= this;
        $.post(
            this_class.action,
            {
                email: $("[data-id='email']").val(),
                subject: $("[data-id='subject']").val(),
                message: $("[data-id='message']").val()

            },
            function (result) {
                console.log(result)
            }
        );
    }
};

$(document).ready(function () {
    MailTweakEmailTestScript.run();
});