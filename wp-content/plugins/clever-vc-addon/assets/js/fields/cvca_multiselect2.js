!function($) {
    var $CvcaMS = $('select.cvca-multiselect2');

    $CvcaMS.each(function(){
        var that = $(this),
            $CvcaMSValue = $(this).parent().find('input.wpb_vc_param_value'),
            allValues = [],
            results = '';

        $(this).find("option").each(function() {
            allValues.push($(this).val());
        });

        $(this).select2({
            placeholder: "Select an option"
        });

        var values = $(this).select2("val") || [];

        $(this).parent().find('.cvca-multiselect2-all').on("click", function (e) {
            e.preventDefault();
            that.val(allValues).trigger("change");
            values = allValues;
            $CvcaMSValue.val(values);
        });

        $(this).parent().find('.cvca-multiselect2-clear').on('click', function (e) {
            e.preventDefault();
            that.val(null).trigger('change');
            values = [];
            $CvcaMSValue.val(values);
        });

        $(this).on("select2:select", function (e) {

            if (!e) {
                var args = '{}';
            } else {
                var args = e.params;
            }

            values.push(args.data.id);

            results = $.map(values, function(vl){
                return vl;
            }).join(", ");

            $CvcaMSValue.val(results);

        });

        $(this).on("select2:unselect", function (e) {

            if (!e) {
                var args = "{}";
            } else {
                var args = e.params;
            }

            values = $.grep(values, function(value) {
                return value != args.data.id;
            });

            results = $.map(values, function(vl){
                return vl;
            }).join(", ");

            $CvcaMSValue.val(results);
        });

    });

}(window.jQuery);
