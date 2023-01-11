/* global copycraft, tinymce, tb_show, jQuery */

jQuery(document).ready(function ($) {
  copycraft.events = {
    activeEditor: null,
    ajaxParams: null,
    registerListeners () {
      $('a.copycraft-open-modal-button').on('click', function () {
        copycraft.events.modalClick(this);
      });

      // Register event listeners for elements that are loaded via AJAX into the Modal.
      $(document).on('click', '#copycraft-modal-contents #replace', function () {
        tinymce.editors[copycraft.events.activeEditor].setContent(copycraft.events.getDescription());
        copycraft.events.close();
      });
      $(document).on('click', '#copycraft-modal-contents #insert', function () {
        tinymce.editors[copycraft.events.activeEditor].setContent(
          tinymce.editors[copycraft.events.activeEditor].getContent() + '\n' + copycraft.events.getDescription()
        );
        copycraft.events.close();
      });
      $(document).on('click', '#copycraft-modal-contents #refresh', function () {
        copycraft.events.loadModal();
      });
      $(document).on('click', '#copycraft-modal-contents #discard', function () {
        copycraft.events.close();
      });
    },
    modalClick (elem) {
      // Trigger a WordPress autosave to ensure the backend has the latest product data.
      window.wp.autosave.server.triggerSave();

      // Set the active Editor.
      copycraft.events.activeEditor = $(elem)
        .closest('.wp-editor-wrap')
        .find('.wp-editor-container textarea')
        .attr('id');

      copycraft.events.loadModal();
      tb_show('CopyCraft', '#TB_inline?inlineId=copycraft-modal&width=600&height=370');
    },
    close () {
      $('#TB_closeWindowButton').click();
    },
    loadModal () {
      // Load the modal via AJAX.
      $('#copycraft-modal-contents').html('<p class="loading">' + copycraft.loading + '</p>');

      copycraft.events.ajaxParams = {
        action: 'copycraft_modal',
        post_id: parseInt(jQuery('#post_ID').val()).toString()
      };

      $.get(copycraft.ajaxUrl, copycraft.events.ajaxParams, function (response) {
        $('#copycraft-modal-contents').html(response).fadeIn();
      });
    },
    getDescription () {
      return $('#copycraft-modal-contents #description').val();
    }
  };

  copycraft.events.registerListeners();
});
