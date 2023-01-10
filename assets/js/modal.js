jQuery(document).ready(function($){

    // When clicking the description, select it.
    jQuery("#description").on('focus', function() {
        var $this = jQuery(this);
        $this.select();
        $this.mouseup(function() {
            $this.unbind("mouseup");
            return false;
        });
    });

    $('#refresh').on('click',function() {
       window.location.reload();
    });
    $('#discard').on('click',function() {
       tb_close();
    });
});