(function ($) {
    "use strict";
    jQuery(document).ready(function () {
        var wrap = $('.cvca-products-wrap');
        //Search Function
        $(document).on('click', '.cvca-products-wrap .cvca_search_button', function () {
            var search = $(this).prev().val();
            var search_button = $(this);
            $.ajax({
                url: wrap.data('url'),
                data: {action: 'cvca_ajax_product_filter', cvca_search: search},
                type: 'POST',
            }).success(function (response) {
                search_button.parent().next().html(response);
            });
        });
        wrap.find('.cvca-list-product-category a').on('click',function (e) {
            e.preventDefault();
            wrap.find('.cat-selected').html($(this).text()+'<i class="cs-font clever-icon-down"></i>');
        });
        wrap.find('.cvca-ajax-load a, .cvca-remove-attribute').on('click', function (e) {
            e.preventDefault();
            var $this = $(this);
            wrap = $this.parents('.cvca-products-wrap');
            wrap.addClass('loading');
            var link = $this.attr('href');
            var title = $this.attr('title');
            var data = wrap.data('args');
            data['action'] = 'cvca_ajax_product_filter';
            if ($this.hasClass('cvca-product-attribute')) {
                if (typeof data['product_attribute'] == 'object' && !$this.hasClass('active')) {
                    data['product_attribute'].push($this.data('value'));
                    data['attribute_value'].push($this.data('attribute_value'));
                } else {
                    data['product_attribute'] = [];
                    data['attribute_value'] = [];
                }
            } else {
                data[$this.data('type')] = $this.data('value');
            }
            data['paged'] = 1;
            if ($this.data('type') == 'product_cat') {
                data['product_attribute'] = [];
                data['attribute_value'] = [];
                data['product_tag'] = '';
                data['filter_categories'] = $this.data('value');
                data['show'] = '';
            }
            if ($this.data('type') == 'cvca-reset-filter') {
                data['product_attribute'] = [];
                data['attribute_value'] = [];
                data['product_tag'] = '';
                data['filter_categories'] = wrap.data('categories');
                data['show'] = '';
                data['price_filter'] = 0;
                $('.wrap-content-product-filter ').find('.active').removeClass('active');
            }

            if ($this.data('type') == 'cvca-remove-attr') {
                var product_attribute = $this.next().data('value');
                var attribute_value = $this.next().data('attribute_value');
                var index = data['attribute_value'].indexOf(attribute_value);
                if (index > -1) {
                    data['attribute_value'].splice(index, 1);
                    data['product_attribute'].splice(index, 1);
                }
            }

            if ($this.data('type') == 'cvca-remove-price') {
                data['price_filter'] = 0;
            }
            var keyword = $('input[name="s"]').val();
            if (keyword != '') {
                data['s'] = keyword;
            }
            if ($this.hasClass('active') && $this.data('type') != 'cvca-reset-filter') {
                if ($this.data('type') == 'product_cat') {
                    data['filter_categories'] = wrap.data('categories');
                    $this.parents('.cvca-list-product-category').find('li:first-child a').addClass('active');
                } else {
                    data[$this.data('type')] = '';
                }
                $this.removeClass('active');
            } else {
                $this.parents('.cvca-ajax-load ').find('.active').removeClass('active');
                $this.addClass('active');
            }
            wrap.data('original', data);
            wrap.data('args', data);
            $.ajax({
                url: wrap.data('url'),
                data: data,
                type: 'POST',
            }).success(function (response) {
                $(document.body).trigger('cvca_woo_after_filter', {
                    "response": response,
                    "wrap": wrap,
                    "max_page": $(response).find('.cvca_ajax_load_more_button').data('maxpage'),
                    'current_page':data['paged']
                });
            }).error(function (ex) {
                console.log(ex);
            });
        });
        //Ajax loadmore
        $(document).on('click', '.cvca-products-wrap .cvca_ajax_load_more_button', function (e) {
            e.preventDefault();
            if (!$(this).hasClass('disable')) {
                var base = $(this).parents('.cvca-products-wrap');
                var wrap = base;
                var data = base.data('args');
                $(this).addClass('cvca-loading');
                var max_page = $(this).data('maxpage');
                if (data['paged'] < max_page) {
                    data['action'] = 'cvca_ajax_product_filter';
                    data['paged'] = parseInt(data['paged']) + parseInt(1);
                    $.ajax({
                        url: $(this).attr('href'),
                        data: data,
                        type: 'POST',
                    }).success(function (response) {
                        $(document.body).trigger('cvca_woo_append_product', {
                            "response": $(response).find('.products').html(),
                            "wrap": wrap,
                            "max_page": max_page,
                            'current_page':data['paged']
                        });
                    }).error(function (ex) {
                        console.log(ex);
                    });
                }
            }
        });
        $('.wrap-head-product-filter .cvca-toogle-filter').on('click', function () {
            $(this).toggleClass('active');
            $('.cvca-wrap-adv-filter').slideToggle();
        })
        //Mobile control
        $('.cvca-title-filter-item').on('click',function (e) {
            e.preventDefault();
            if($(window).width()<769) {
                $(this).next().slideToggle();
            }
        });
    });
    //Add new item after click loadmore
    $(document.body).bind('cvca_woo_append_product', function (event, data) {
        var wrap = data.wrap,
            max_page = data.max_page,
            product_w = wrap.find('.product').outerWidth(),
            item_w= wrap.find('.product').width(),
            $products_res = $(data.response).outerWidth(product_w);
        var res = '';
        $products_res.each(function () {
            var $this = $(this);
            if ($this.find('.lazy-img')[0]) {
                res = $this.find('.lazy-img').parent().data('resolution');
                if (res != '') {
                    $this.find('.lazy-img').parent().outerWidth(item_w).height(item_w / res);
                }
            }
        });
        wrap.find('.products').append($products_res).isotope('appended', $products_res);
        setTimeout(function () {
            wrap.find('.products').isotope({layoutMode: 'fitRows'});
        }, 300);
        if (wrap.find('.lazy-img:not(.loaded)')[0]) {
            wrap.find('.lazy-img:not(.loaded)').lazyload({
                effect: 'fadeIn',
                threshold: $(window).height(),
                load: function () {
                    $(this).parent().removeClass('loading');
                    $(this).addClass('loaded');
                }
            });
            var img_srcset = '';
            wrap.find('.lazy-img:not(.loaded)').each(function () {
                img_srcset = $(this).data('srcset');
                if (!!img_srcset) {
                    $(this).attr('srcset', img_srcset);
                }
            });
        }
        if (max_page == data.current_page) {
            wrap.find('.cvca_ajax_load_more_button').addClass('disable').html(wrap.find('.cvca_ajax_load_more_button').data('empty'));
        } else {
            wrap.find('.cvca_ajax_load_more_button').show();
        }
        wrap.find('.cvca_ajax_load_more_button').removeClass('cvca-loading');
    });
    //Replace old html by new data after filter
    $(document.body).bind('cvca_woo_after_filter', function (event, data) {
        var wrap=data.wrap,
            max_page = data.max_page,
            product_w = wrap.find('.product').outerWidth(),
            item_w= wrap.find('.product').width();
        var $products = $(data.response).find('.product');
        var res = '';
        $products.outerWidth(product_w);
        $products.each(function () {
            var $this = $(this);
            if ($this.find('.lazy-img')[0]) {
                res = $this.find('.lazy-img').parent().data('resolution');
                if (res != '') {
                    $this.find('.lazy-img').parent().outerWidth(item_w).height(item_w / res);
                }
            }
        });
        //Update button loadmore
        if (!$products[0]) {
            wrap.find('.products').html('<h3 class="products-emt">' + wrap.data('empty') + '</h3>')
        }else{
            wrap.find('.products').isotope( 'remove', wrap.find('.product')).append($products).isotope('appended', $products).isotope('layout');
            if (wrap.find('.lazy-img:not(.loaded)')[0]) {
                wrap.find('.lazy-img:not(.loaded)').lazyload({
                    effect: 'fadeIn',
                    threshold: $(window).height(),
                    load: function () {
                        $(this).parent().removeClass('loading');
                        $(this).addClass('loaded');
                    }
                });
                var img_srcset = '';
                wrap.find('.lazy-img:not(.loaded)').each(function () {
                    img_srcset = $(this).data('srcset');
                    if (!!img_srcset) {
                        $(this).attr('srcset', img_srcset);
                    }
                });
            }
        }
        //Update button loadmore
        if (wrap.find('.cvca_ajax_load_more_button')[0]) {
            if (max_page== data.current_page||!(!!max_page)) {
                wrap.find('.cvca_ajax_load_more_button').addClass('disable').html(wrap.find('.cvca_ajax_load_more_button').data('empty'));
            } else {
                wrap.find('.cvca_ajax_load_more_button').data('maxpage', max_page);
            }
        }
        wrap.removeClass('loading');
    });
})(jQuery);