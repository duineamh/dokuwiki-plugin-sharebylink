/**
 * SharePage dialog for end users
 *
 * @author Peter Rohmann <dokuwiki@razupaltuff.com>
 */

if(JSINFO && JSINFO.sharebylink_sharePageDialogOkay) {
    jQuery('.plugin_sharebylink_sharepage')
        .show()
        .click(function(e) {
            e.preventDefault();


            // basic dialog template
            var $dialog = jQuery(
                '<div>' + LANG.plugins.sharebylink.active + '</div>'
            );

            // set up the dialog
            $dialog.dialog({
                title: LANG.plugins.sharebylink.sharepage+' '+JSINFO.id,
                width: 800,
                height: 180,
                dialogClass: 'plugin_sharebylink_dialog',
                modal: true,
                buttons: [
                    {
                        text: LANG.plugins.sharebylink.close,
                        click: function () {
                            $dialog.dialog("close");
                        }
                    }
                ],
                // remove HTML from DOM again
                close: function () {
                    jQuery(this).remove();
                }
            })
        });
}
