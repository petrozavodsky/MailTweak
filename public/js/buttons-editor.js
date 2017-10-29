(function () {
    tinymce.PluginManager.add(MailTweakButton.button_slug, function (editor, url) {

        var menu_items = [];


        if (undefined !== MailTweakButton.info[editor.id]) {

            var index = 0;
            for (var i in  MailTweakButton.info[editor.id]) {
                menu_items[index] = {
                    text: MailTweakButton.info[editor.id][i],
                    value: "["+MailTweakButton.shortcode+" type='" + [i][0] + "']",
                    onclick: function () {
                        editor.insertContent('["'+MailTweakButton.shortcode+'" type="'+ [i][0] + '"]');
                    }
                };

                index++;
            }

            editor.addButton(MailTweakButton.button_slug, {
                title: MailTweakButton.title,
                type: 'menubutton',
                icon: "icon mail-tweak",
                menu: menu_items
            });
        }

    });


})();