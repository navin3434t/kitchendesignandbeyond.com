jQuery(document).ready((function(n){var t;(function(){n("body").unbind("change.wpzinc-tags").on("change.wpzinc-tags","select.wpzinc-tags",(function(t){var a=n(this).val(),e=n(this).data("element"),s=n(e).val();if(n(e).hasClass("tmce-active"));else{var i=n(e)[0].selectionStart;i>0&&(a=" "+a),n(e).val(s.substring(0,i)+a+s.substring(i))}}))})()}));