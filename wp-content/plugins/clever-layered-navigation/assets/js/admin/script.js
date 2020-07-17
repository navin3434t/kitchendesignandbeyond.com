(function ($) {
    'use strict';
    $(document).ready(function ($) {
        $('#filter_view_style').on('change',function () {
            if($(this).find(":selected").val()=='horizontal'){
                $('.horizontal-options').removeClass('hidden')
            }else{
                $('.horizontal-options').addClass('hidden')
            }
        });
    });
})(jQuery);