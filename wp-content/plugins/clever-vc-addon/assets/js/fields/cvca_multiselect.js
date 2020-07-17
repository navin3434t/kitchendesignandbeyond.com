!function($) {
    var $CvcaMS = $('select.cvca-multiselect');

    $CvcaMS.each(function(){
        var $CvcaMSValue = $(this).parent().find('input.wpb_vc_param_value'),
            values = [],
            allValues = [];

        $(this).find("option").each(function() {
            allValues.push($(this).val());
        });

        
        //console.log(allResults);
        $(this).multipleSelect({
            placeholder: "Select an option",
            onCheckAll: function() {
                allResults = $.map(allValues, function(vl){
                    return vl;
                }).join(", ");
                $CvcaMSValue.val(allResults);
            },
            onUncheckAll: function() {
                $CvcaMSValue.val("");
            },
            onClick: function(view) {
                if (view.checked) {
                    values.push(view.value);
                } else {
                    values = jQuery.grep(values, function(value) {
                        return value != view.value;
                    });
                }

                results = $.map(values, function(vl){
                    return vl;
                }).join(", ");

                $CvcaMSValue.val(results);
            }
        });
    });
    
}(window.jQuery);