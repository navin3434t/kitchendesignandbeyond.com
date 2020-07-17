(function ($, w) {
    "use strict";
    /*Ajax search*/
    $('.wrap-list-cat-search select').on('change', function () {
        $(this).parent().find('span').text($(this).find("option:selected").text());
    });


    $("form.zoo-live-search").each(function () {
        var inputtingTimer = null;
        var zooSearchForm = $(this),
            zooSearchFormInputField = $(this).find(".search-field"),
            zooSearchFormSelectField = $(".zoo-product-cat-options", zooSearchForm);
        zooSearchFormInputField.on("keypress", function (e) {
            if (e.which === 13) // Abort enter action.
                e.preventDefault();
            zoo_ajax_search();
        });
        zooSearchFormInputField.on("input", function (e) {
            zoo_ajax_search();
        });
        zooSearchFormSelectField.on('change', function () {
            zoo_ajax_search();
        });

        function zoo_ajax_search() {
            // Clear delayed timer before another key press.
            clearTimeout(inputtingTimer);
            // Delay 1s before handling user input.
            inputtingTimer = setTimeout(function () {
                var zooSearchFormInputFieldVal = zooSearchFormInputField.val();
                if (zooSearchFormInputFieldVal.length >= 3) {
                    var queryData = {
                        queryString: zooSearchFormInputFieldVal
                    };
                    if (zooSearchFormSelectField.length) {
                        queryData.productCat = zooSearchFormSelectField.val();
                    }
                    zooSearchForm.addClass('searching');
                    $.ajax({
                        url: ajaxurl,
                        type: "POST",
                        data: {
                            action: "zoo_get_live_search_results",
                            searchQuery: JSON.stringify(queryData)
                        }
                    }).done(function (result) {
                        if ($('.wrap-search-result')[0]) {
                            $('.wrap-search-result').replaceWith(result);
                        } else {
                            zooSearchForm.append(result);
                        }
                        zooSearchForm.removeClass('searching');
                    }).fail(function (result) {
                        zooSearchForm.removeClass('searching');
                        console.log(result);
                    });
                } else {
                    if ($('.wrap-search-result')[0]) {
                        $('.wrap-search-result').fadeOut();
                    }
                }
            }, 500);
        }
    });
    //Hide result form when click out site form
    $(document).on('click', function () {
        if ($('.wrap-search-result')[0] || !$(this).hasClass('zoo-live-search')) {
            $('.wrap-search-result').fadeOut();
        }
    });
    /*End Ajax search*/
    jQuery(function ($) {
        var current_width = 0;
        $(window).resize(function () {
            //Fix menu out of screen.
            if (current_width != $(window).width()) {
                current_width = $(window).width();
                var window_w = $(window).width();
                $('.pos-left').removeClass('pos-left');
                $('.primary-menu .sub-menu, .primary-menu .dropdown-submenu, .primary-menu .cmm-sub-container, .element-top-menu .sub-menu').each(function () {
                    if (window_w < parseInt($(this).offset()['left'] + $(this).width())) {
                        $(this).addClass('pos-left');
                    }
                });
                //Remove class active of off canvas when window resize.
                $('.mask-off-canvas').removeClass('active');
                $('.header-off-canvas-sidebar').removeClass('active');

            }
        }).resize();
        //Search form light box control
        $(document).on('click', '.btn-lb-search', function (e) {
            e.preventDefault();
            $('.wrap-lb-search').addClass('active');
            setTimeout(function () {
                $('.wrap-lb-search input').focus()
            }, 300)
        });
        $(document).on('click', '.wrap-lb-search.active', function (e) {
            if (e.target !== this)
                return;
            else {
                $(this).removeClass('active')
            }
        });
        $(document).on('click', '.btn-close-lb-search', function (e) {
            e.preventDefault();
            $('.wrap-lb-search').removeClass('active');
        });
        $(document).on("keyup", function (e) {
            if (e.which === 27) {
                // Abort enter action.
                $('.wrap-lb-search.active').removeClass('active');
                $('.header-off-canvas-sidebar.active, .mask-off-canvas.active').removeClass('active')
            }

        });
        //Off canvas control
        $(document).on('click', '.off-canvas-toggle', function (e) {
            e.preventDefault();
            var $target = $('.header-off-canvas-sidebar.show-on-mobile');
            if ($(this).closest('.wrap-site-header-desktop')[0]) {
                $target = $('.header-off-canvas-sidebar.show-on-desktop');
            }
            if (!!$target.not('.off-canvas-dropdown')[0]) {
                $('.mask-off-canvas').toggleClass('active');
                $target.toggleClass('active');
            } else {
                $target.slideToggle();
            }
        });
        $(document).on('click', '.off-canvas-close, .mask-off-canvas.active', function (e) {
            e.preventDefault();
            $('.mask-off-canvas.active').removeClass('active');
            $('.header-off-canvas-sidebar.active').removeClass('active');
        });
        //Control My Account Off canvas
        $(document).on('click', '.element-header-account.off-canvas .account-element-link', function (e) {
            e.preventDefault();
            $('.zoo-account-block.login-form-off-canvas').addClass('active');
        });
        $(document).on('click', '.zoo-account-block.login-form-off-canvas .overlay', function (e) {
            e.preventDefault();
            $('.zoo-account-block.login-form-off-canvas').removeClass('active');
        });
        //Control My Account popup
        $(document).on('click', '.control-login-popup .account-element-link', function (e) {
            e.preventDefault();
            $('.login-form-popup').toggleClass('active');
        });
        $(document).on('click', '.login-form-popup .overlay,.login-form-popup  .close-login', function (e) {
            e.preventDefault();
            $('.login-form-popup').removeClass('active');
        });
        //Offcanvas cart control
        $(document).on('click', '.element-cart-icon.off-canvas .element-cart-link', function (e) {
            e.preventDefault();
            $('.wrap-element-cart-off-canvas').toggleClass('off-canvas-cart-active');
        });
        $(document).on('click', '.off-canvas-cart-active .mask-close,.off-canvas-cart-active .close-cart', function (e) {
            e.preventDefault();
            $('.off-canvas-cart-active').removeClass('off-canvas-cart-active');
        });
        //Offcavas mobile ajax;
        /* Remove Class cart empty with site install vanish cache after loaded*/
        if (typeof  Cookies != 'undefined') {
            if (Cookies.get('woocommerce_items_in_cart') > 0) {
                $('.element-cart-icon').removeClass('cart-empty');
            }
        }
        //Update mini top cart ajax
        $(document).on('added_to_cart', function (event, fragments) {
            $('.element-cart-icon.loading').removeClass('loading');
            $('.wrap-element-cart-off-canvas.loading').removeClass('loading');
        });
        $(document).on('zoo_single_product_cart_added', function (event, response) {
            $('.element-cart-link').html($(response['response']).find('.element-cart-link').html());
            $('.element-cart-icon.loading').removeClass('loading');
            $('.wrap-element-cart-off-canvas.loading').removeClass('loading');
        });
        //Open cart when user click to button add to cart
        $(document).on('zoo_starting_add_to_cart', function () {
            $('.wrap-element-cart-off-canvas').addClass('loading off-canvas-cart-active');
        });
        $(document).on('adding_to_cart', function () {
            $('.wrap-element-cart-off-canvas').addClass('loading off-canvas-cart-active');
        });

        $(document).on('zoo_after_remove_product_item', function (event, response) {
            var fragments = response.fragments;
            $('.element-cart-icon .element-cart-count').html(fragments['cart_count']);
            $('.element-cart-icon .total-element-cart').html(fragments['cart_subtotal']);
        });
        $(document).on('zoo_after_restore_product_item', function (event, response) {
            var fragments = response.fragments;
            $('.element-cart-icon .element-cart-count').html(fragments['cart_count']);
            $('.element-cart-icon .total-element-cart').html(fragments['cart_subtotal']);
        });
        //Offcanvas language switcher
        $(".header-off-canvas-sidebar .language-options").on('click', function () {
            $(this).next().slideToggle();
        });
        $('.header-off-canvas-sidebar .list-languages').on('click', function () {
            $(this).slideUp();
        });
        //Sticky Header
        if (typeof $.fn.stick_in_parent) {
            var to_top = 0, to_top_mobile;
            if ($(window).width() > 600) {
                to_top = to_top_mobile = !!$('#wpadminbar')[0] ? $('#wpadminbar').height() : 0;
            }

            let current_width = 0;
            $(window).resize(function () {
                //Fix menu out of screen.
                if (current_width != $(window).width()) {
                    current_width = $(window).width();
                    $('.site-header').height('auto');
                    $('.site-header').height( $('.site-header').height());
                }
            }).resize();
            $('.site-header .sticker').each(function () {
                var $this = $(this);
                var this_to_top = $this.closest('.wrap-site-header-desktop')[0] ? to_top : to_top_mobile;
                $this.data('to-top', this_to_top);
                if (!$this.hasClass('jump-down-animation')) {
                    $this.stick_in_parent({bottoming:false,
                        parent: 'body',spacer: false,sticky_class:'is-sticky',
                        offset_top:this_to_top
                    }).on("sticky_kit:unstick", function(e) {
                        console.log("has unstuck!", e.target);
                    });
                }
                if (!!$this.data('sticky-height')) {
                    this_to_top += $this.data('sticky-height');
                } else {
                    this_to_top += $this.height();
                }
                !!$this.closest('.wrap-site-header-desktop')[0] ? to_top = this_to_top : to_top_mobile = this_to_top;
            });
            if ($('.site-header .sticker.jump-down-animation')[0]) {
                $(window).on("scroll", function () {
                    $('.site-header .sticker.jump-down-animation').each(function () {
                        var $this = $(this);
                        if ($(window).scrollTop() > $('.site-header').height() && !$this.parents('.is-sticky')[0]) {
                            $this.stick_in_parent({bottoming:false,parent: 'body',sticky_class:'is-sticky',spacer: false,offset_top:$this.data('to-top')});
                            $this.addClass('deactive');
                        }
                        if ($(window).scrollTop() > parseInt($('.site-header').height() + 100) && $this.hasClass('deactive')) {
                            $this.removeClass('deactive');
                        }
                        if ($this.parents('.sticky-wrapper:not(.is-sticky)')[0] && $(window).scrollTop() < $('.site-header').height()) {
                            $this.trigger("sticky_kit:detach");
                        }
                    });
                });
            }
            window.onscroll = function (e) {
                if ($('html').scrollTop() == 0 && window.navigator.userAgent.indexOf("Edge")=='-1') {
                    $(document.body).trigger("sticky_kit:recalc");
                }
            }
        }
        //Mobile Menu
        $(document).on('click', '.wrap-content-header-off-canvas .menu-item .zoo-icon-down', function (e) {
            if (e.target !== this)
                return;
            else {
                e.preventDefault();
                $(this).toggleClass('active');
                $(this).closest('.menu-item').children('ul').slideToggle();
                if (!$(this).hasClass('active')) {
                    $(this).closest('.menu-item').find('ul').slideUp();
                    $(this).closest('.menu-item').find('.active').removeClass('active');
                }
            }
        });

        /**
         * EDD cart information in the header
         */
        $('body').on('edd_cart_item_added', function(event, response)
        {
            $('.edd-cart').removeClass('empty');

            $('.edd-cart-total').html(response.total);
        });
    });
}(jQuery, window));
