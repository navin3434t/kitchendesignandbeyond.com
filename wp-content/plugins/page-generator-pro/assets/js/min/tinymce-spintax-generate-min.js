tinymce.PluginManager.add("page_generator_pro_spintax_generate",(function(e,t){e.addButton("page_generator_pro_spintax_generate",{title:"Generate Spintax from selected Text",image:t+"../../../images/icons/spintax.png",cmd:"page_generator_pro_spintax_generate"}),e.addCommand("page_generator_pro_spintax_generate",(function(){var e=tinyMCE.activeEditor.selection.getContent();jQuery.post(ajaxurl,{action:"page_generator_pro_tinymce_spintax_generate",content:e},(function(e){e.success?tinyMCE.activeEditor.selection.setContent(e.data):alert(e.data)}))}))}));