tinymce.PluginManager.add("page_generator_pro_creative_commons",(function(e,o){e.addButton("page_generator_pro_creative_commons",{title:"Insert Creative Commons Image",image:o+"../../../images/icons/creative-commons.svg",cmd:"page_generator_pro_creative_commons"}),e.addCommand("page_generator_pro_creative_commons",(function(){e.windowManager.open({id:"page-generator-pro-modal-body",title:"Insert Creative Commons Image",width:800,height:508,inline:1,buttons:[]}),jQuery.post(ajaxurl,{action:"page_generator_pro_output_tinymce_modal",shortcode:"creative-commons"},(function(e){jQuery("#page-generator-pro-modal-body-body").html(e),wp_zinc_tabs_init(),page_generator_pro_autocomplete_initialize()}))}))}));