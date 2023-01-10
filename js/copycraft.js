jQuery(document).ready(function($){

    $('#copycraft-open-modal-button').on('click',function() {
        // Trigger a WordPress autosave to ensure the backend has the latest product data.
        window.wp.autosave.server.triggerSave();

        // Load the modal via AJAX.
        let params = {
            'action': 'copycraft_modal',
            'post_id': parseInt(jQuery("#post_ID").val()).toString()
        }
        tb_show('CopyCraft', 'admin-ajax.php?' + $.param(params) + '#TB_iframe=true?width=900&height=900');
    });
});