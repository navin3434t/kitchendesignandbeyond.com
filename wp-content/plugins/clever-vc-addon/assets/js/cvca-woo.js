(function ($) {
    'use strict';
    jQuery(document).ready(function () {
        $('.products').cvca_WooSmartLayout();
        //Ajax change layout
        jQuery('.cvca-products-wrap').bind('DOMNodeInserted DOMNodeRemoved', function (event) {
            $('.products').cvca_WooSmartLayout();
        });
        //For resize window
        $(window).resize(function () {
            setTimeout(function () {
                $('.products').cvca_WooSmartLayout();
            }, 400);
            if ($('.products:not(.products-carousel)')[0]) {
                setTimeout(function () {
                    $('.products:not(.products-carousel)').isotope({layoutMode: 'fitRows'});
                }, 800);
            }
        });
    });
    $(window).load(function () {
        if ($('.products:not(.products-carousel)')[0]) {
            $('.products:not(.products-carousel)').isotope({layoutMode: 'fitRows'});
        }
    });
    jQuery.fn.extend({
        cvca_WooSmartLayout: function () {
            if (jQuery(this)[0]) {
                if (!$(this).hasClass('carousel')) {
                    jQuery(this).each(function () {
                        var col;
                        var $this = jQuery(this);
                        var wrap_w = $this.outerWidth();
                        var res = '';
                        if ($this.find('.lazy-img')[0]) {
                            res = $this.find('.lazy-img').parent().data('resolution');
                        }
                        var item_w = jQuery(this).data('width');

                        if ($this.hasClass('grid')) {
                            if (item_w) {
                                if (jQuery(window).width() > 481) {
                                    col = Math.floor(wrap_w / item_w);
                                } else {
                                    col = 1;
                                }
                                var col_w = wrap_w / col;
                                $this.find('.product').outerWidth(col_w - 0.5);
                            }
                            if (res != '') {
                                var w = $this.find('.product').width();
                                $this.find('.lazy-img').parent().outerWidth(w).height(w / res);
                            }
                        }

                        if ($this.hasClass('list')) {
                            $this.find('.product').outerWidth(wrap_w);
                            if (res != '') {
                                var w = $this.find('.product').width() * 0.25;
                                $this.find('.lazy-img').parent().outerWidth(w).height(w / res);
                            }
                        }
                        if (item_w) {
                            $this.isotope({
                                layoutMode: 'fitRows',
                                masonry: {
                                    columns: col
                                }
                            });
                        }
                        $this.find('.lazy-img').cvca_lazyImg();
                    })
                }
                else {
                    $(this).find('.lazy-img').each(function () {
                        $(this).attr('src', $(this).data('original'));
                    });
                }
            }
        },
        //Lazy Img Config
        cvca_lazyImg: function () {
            if ($(this)[0]) {
                $(this).not('.loaded').parent().addClass('loading');
                $(this).not('.loaded').lazyload({
                    effect: 'fadeIn',
                    threshold: $(window).height(),
                    load: function () {
                        $(this).parent().removeClass('loading');
                        $(this).addClass('loaded');
                    }
                });
            }
        },
    });
})(jQuery)
