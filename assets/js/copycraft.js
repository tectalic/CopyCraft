jQuery(document).ready(function ($) {

    const copyCraft = {
        activeEditor: null,
        registerListeners() {
            $('a.copycraft-open-modal-button').on('click', function () {
                copyCraft.modalClick(this);
            });

            // Register event listeners for elements that are loaded via AJAX into the Modal.
            $(document).on('click', '#copycraft-modal-contents #replace', function () {
                console.log('Replace');
                tinymce.editors[copyCraft.activeEditor].setContent(copyCraft.getDescription());
                copyCraft.close();
            });
            $(document).on('click', '#copycraft-modal-contents #insert', function () {
                console.log('Insert');
                tinymce.editors[copyCraft.activeEditor].setContent(tinymce.editors[copyCraft.activeEditor].getContent() + '\n' + copyCraft.getDescription());
                copyCraft.close();
            });
            $(document).on('click', '#copycraft-modal-contents #refresh', function () {
                console.log('Refresh click');
                copyCraft.loadModal();
            });
            $(document).on('click', '#copycraft-modal-contents #discard', function () {
                console.log('Discard click');
                copyCraft.close();
            });
        },
        modalClick(elem) {
            // Trigger a WordPress autosave to ensure the backend has the latest product data.
            window.wp.autosave.server.triggerSave();

            // Set the active Editor.
            copyCraft.activeEditor = $(elem).closest('.wp-editor-wrap').find('.wp-editor-container textarea').attr('id');

            if (false == $('#copycraft-modal-contents').data('initialised')) {
                copyCraft.loadModal();
            }
            tb_show('CopyCraft', '#TB_inline?inlineId=copycraft-modal&width=600&height=370');
        },
        close() {
            $('#TB_closeWindowButton').click();
        },
        loadModal() {
            // Load the modal via AJAX.
            $('#copycraft-modal-contents').html('<p class="loading">' + copycraft.loading + '</p>');

            let ajaxParams = {
                'action': 'copycraft_modal',
                'post_id': parseInt(jQuery("#post_ID").val()).toString()
            };

            $.get(copycraft.ajaxurl, ajaxParams, function (response) {
                $('#copycraft-modal-contents').attr('data-initialised', true).html(response).fadeIn();
            });
        },
        getDescription() {
            return $('#copycraft-modal-contents #description').val();
        }
    };

    copyCraft.registerListeners();
});