tinymce.PluginManager.add("page_generator_pro_google_map",(function(e,o){e.addButton("page_generator_pro_google_map",{title:"Insert Google Map",image:o+"../../../../_modules/dashboard/feather/map-pin.svg",cmd:"page_generator_pro_google_map"}),e.addCommand("page_generator_pro_google_map",(function(){e.windowManager.open({id:"page-generator-pro-modal-body",title:"Insert Google Map",width:600,height:385,inline:1,buttons:[]}),jQuery.post(ajaxurl,{action:"page_generator_pro_output_tinymce_modal",shortcode:"google-map"},(function(e){jQuery("#page-generator-pro-modal-body-body").html(e),jQuery('form.wpzinc-tinymce-popup select[name="maptype"]').trigger("change.page-generator-pro"),page_generator_pro_autocomplete_initialize()}))}))}));