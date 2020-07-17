(function ($) {
    'use strict';
    jQuery(document).ready(function ($) {
        /*tooltip*/
        if (typeof tippy !== 'undefined') {
            tippy('.cw-type-color.inline .zoo-filter-item, .cw-type-image.inline .zoo-filter-item', {
                arrow: true,
                animation: 'fade'
            });
        }
        /*End tooltip*/
        /*Scroll bar*/
        if ($('.zoo-ln-set-max-height')[0]) {
            jQuery('.zoo-ln-set-max-height .zoo-list-filter-item').scrollbar();
        }
        /*End Scroll bar*/

        $('.zoo-ln-filter-form.apply_ajax').each(function () {
            let config = $(this).data('ln-config');
            $(config.jquery_selector_paging).addClass('zoo_ln_ajax_pagination');
            $(config.jquery_selector_paging).attr('data-ln-preset', config.filter_preset);
        });
        /*Control view functions*/
        $(document).on('click', '.zoo-toggle-filter-visible', function () {
            $(this).parents('.zoo-ln-filter-form').find('.zoo-ln-wrap-col').slideToggle();
        });
        $(document).on('click', '.zoo-ln-toggle-view', function () {
            $(this).next('ul.zoo-wrap-child-item').slideToggle();
            $(this).toggleClass('active');
        });

        $('.zoo-filter-has-child .selected').each(function () {
            $(this).parents('ul.zoo-wrap-child-item').slideDown().prev('.zoo-ln-toggle-view').addClass('active');
        });

        $(document).on('click', '.zoo-ln-toggle-block-view', function () {
            $(this).parents('.zoo-filter-block').toggleClass('visible');
            $(this).parents('.zoo-filter-block').find('.zoo-list-filter-item').slideToggle();
        });

        $(document).on('click', '.zoo-ln-rating-item span', function () {
            let parent = $(this).parents('.zoo-filter-block');
            parent.find('.selected').removeClass('selected');
            if ($(this).data('zoo-ln-star') != parent.find('input').val()) {
                $(this).parent().addClass('selected');
                parent.find('input').val($(this).data('zoo-ln-star')).trigger('change');
            } else {
                parent.find('input').val(0).trigger('change');
            }
        });
        //number pagination;
        $(document).on('click', '.zoo_ln_ajax_pagination a', function (e) {
            e.preventDefault();
            let pagination = $(this).closest('.zoo_ln_ajax_pagination');
            let form = $('.' + pagination.data('ln-preset'));
            let paged;
            if ($(this).hasClass('next')) {
                paged = parseInt(pagination.find('.page-numbers.current').text()) + 1;
            } else if ($(this).hasClass('prev')) {
                paged = parseInt(pagination.find('.page-numbers.current').text()) - 1;
            } else {
                paged = parseInt($(this).text());
            }

            form.find('input[name="paged"]').val(paged);
            zoo_ln_do_filter(form);
        });
        /*Ajax functions*/
        var filter_form = $('[id*=zoo_ln_form]');
        filter_form.find('input,select').not('.zoo_ln_uneffective').change(function () {
            $(this).parent().parent('.zoo-filter-item').toggleClass('selected');
        });
        if (filter_form.hasClass('instant_filtering')) {
            filter_form.find('input,select').not('.zoo_ln_uneffective').change(function () {
                var current_form = $(this).parents('form.zoo-ln-filter-form');
                zoo_ln_do_filter(current_form);
            });
        }
        $(document).on('click', '.zoo-ln-filter-form input[type=submit]',function (e) {
            e.preventDefault();
            var current_form = $(this).parents('form.zoo-ln-filter-form');
            zoo_ln_do_filter(current_form);
        })

        $(document).on('click', '.zoo-ln-remove-filter-item', function (e) {
            var current_form = $(this).parents('form.zoo-ln-filter-form');
            e.preventDefault();
            let val = $(this).val();
            let name = $(this).attr('name').matchAll(/\[(.*?)\]/g);
            if (!!name) {
                //For case remove filter
                if (name[0][1] == 'categories') {
                    if ($('.zoo-filter-by-categories ul')[0]) {
                        let selected_item = $('.zoo-filter-by-categories input').filter(function () {
                            return this.value == val
                        });
                        selected_item.prop('checked', false);
                        selected_item.closest('.selected').removeClass('selected');
                    } else {
                        $('.zoo-filter-by-categories option').filter(function () {
                            return this.value == val
                        }).prop('selected', false);
                    }
                }
                else if (name[0][1] == 'attribute') {
                    let selected_item = $('.zoo-filter-by-' + name[1][1] + ' input').filter(function () {
                        return this.value == val
                    });
                    selected_item.prop('checked', false);
                    selected_item.closest('.selected').removeClass('selected');
                } else if (name[0][1] == 'tags') {
                    const removingTag = $('.zoo-filter-by-tags input.filter-tag-' + name[1][1]);
                    removingTag.prop('checked', false);
                    removingTag.closest('.zoo-filter-item').removeClass('selected');
                }
                else if (name[0][1] == 'price') {
                    current_form.find('input.price-to, input.price-from').attr('value', '');
                    current_form.find(".zoo-ln-slider-range").slider("values", [current_form.find(".zoo-filter-by-price.slider-price .price-min").val(), current_form.find(".zoo-filter-by-price.slider-price .price-max").val()]);
                    current_form.find(".zoo-filter-by-price.slider-price .zoo-price-form .price.amount").text(current_form.find(".zoo-filter-by-price.slider-price .price-min").val());
                    current_form.find(".zoo-filter-by-price.slider-price .zoo-price-to .price.amount").text(current_form.find(".zoo-filter-by-price.slider-price .price-max").val());
                } else if (0 === name[0][1].indexOf('range_')) {
                    let currentTax = name[0][1].replace('range_', ''),
                        minField = 'input[name=range-min-' + currentTax + ']',
                        maxField = 'input[name=range-max-' + currentTax + ']';

                    current_form.find(minField).attr('value', '');
                    current_form.find(maxField).attr('value', '');
                    let control = current_form.find(".range-slider-"+currentTax+" .zoo-ln-range-slider-control"),
                        config = control.data('config');

                    control.slider({
                        values: [config.min, config.max]
                    }).slider('pips', {
                        first: 'pip',
                        last: 'pip'
                    }).slider('float');
                }
                else if (name[0][1] == 'on-sale') {
                    current_form.find('input[name*=on-sale]').prop('checked', false);
                }
                else if (name[0][1] == 'in-stock') {
                    current_form.find('input[name*=in-stock]').prop('checked', false);
                }
                else if (name[0][1] == 'rating-from') {
                    current_form.find('input[name*=rating-from]').attr('value', 0);
                    current_form.find('.zoo-list-rating .selected').removeClass('selected');
                }
            } else {

                //For reset all
                current_form.find('input').prop('checked', false);
                current_form.find('.selected').removeClass('selected');
                current_form.find('.zoo-filter-item input[type="hidden"]').val('');
                current_form.find('input[name="relation"]').val('');
                current_form.find('input[name="rating-from"]').val(0);
                current_form.find('input.attr-from').val('');
                current_form.find('input.attr-to').val('');

                // Reset all range sliders.
                const rangeSliders = current_form.find('.zoo_ln_range_slider');
                $.each(rangeSliders, function(index, slider)
                {
                    const control = $('.zoo-ln-range-slider-control', slider),
                        config = control.data('config');

                    control.slider({
                        values: [config.min, config.max]
                    }).slider('pips', {
                        first: 'pip',
                        last: 'pip'
                    }).slider('float');
                });

                //Reset price slider
                current_form.find('input.price-to, input.price-from').attr('value', '');
                current_form.find(".zoo-ln-slider-range").slider("values", [current_form.find(".zoo-filter-by-price.slider-price .price-min").val(), current_form.find(".zoo-filter-by-price.slider-price .price-max").val()]);
                current_form.find(".zoo-filter-by-price.slider-price .zoo-price-form .price.amount").text(current_form.find(".zoo-filter-by-price.slider-price .price-min").val());
                current_form.find(".zoo-filter-by-price.slider-price .zoo-price-to .price.amount").text(current_form.find(".zoo-filter-by-price.slider-price .price-max").val());
            }
            zoo_ln_do_filter(current_form);
        });


        //price slider function
        filter_form.find('.zoo_ln_price.slider-price').each(function () {
            var current_form = $(this).parents('form.zoo-ln-filter-form');
            var $this = $(this);
            var min = parseInt($(this).find('.price-min').val());
            var max = parseInt($(this).find('.price-max').val());
            var from = min;
            var to = max;
            if ($this.find('.price-from').val() != '') {
                from = parseInt($this.find('.price-from').val());
            }
            if ($this.find('.price-to').val() != '') {
                to = parseInt($this.find('.price-to').val());
            }

            $this.find(".zoo-ln-slider-range").slider({
                range: true,
                min: min,
                max: max,
                values: [from, to],
                slide: function (event, ui) {
                    $this.find(".price-from").val(ui.values[0]);
                    $this.find('.zoo-price-form .price.amount').html(ui.values[0]);
                    $this.find(".price-to").val(ui.values[1]);
                    $this.find('.zoo-price-to .price.amount').html(ui.values[1]);
                },
                stop: function (event, ui) {
                    if (ui.values[0] == min && ui.values[1] == max) {
                        $this.find(".price-from").val('');
                        $this.find(".price-to").val('');
                    }
                    if (current_form.hasClass('instant_filtering')) {
                        zoo_ln_do_filter(current_form);
                    }
                }
            });

            $("input.amount").val("$" + $(".slider-range").slider("values", 0) +
                " - $" + $(".slider-range").slider("values", 1));
        });
        //price ratio function
        filter_form.find('.zoo_ln_price .price_radio').click(function () {
            var current_form = $(this).parents('form.zoo-ln-filter-form');
            var value = $(this).val().split("-");
            current_form.find('.zoo_ln_price .price-from').val(value[0]);
            current_form.find('.zoo_ln_price .price-to').val(value[1]);
            if (current_form.hasClass('instant_filtering')) {
                zoo_ln_do_filter(current_form);
            }
        });

        //Slider range function
        filter_form.find('.zoo_ln_range_slider').each(function () {
            var current_form = $(this).parents('form.zoo-ln-filter-form');
            var $this = $(this);
            var data =$this.find('.zoo-ln-range-slider-control').data('config');
            var min=parseFloat(data.min);
            var max=parseFloat(data.max);
            var from,to;
            from=$this.find('.attr-from').val();
            from= from == ''? min:from;
            to=$this.find('.attr-to').val();
            to= to == ''? max:to;
            var list_attr=data.val.split(",");
            $this.find('.zoo-ln-range-slider-control').slider({
                range: true,
                min:  min,
                max:  max,
                values: [from,to],
                slide: function (event, ui) {
                    $this.find(".attr-from").val(ui.values[0]);
                    $this.find(".attr-to").val(ui.values[1]);
                },
                stop: function (event, ui) {
                    if (current_form.hasClass('instant_filtering')) {
                        zoo_ln_do_filter(current_form);
                    }
                }
            }).slider("pips", {
                first: "pip",
                last: "pip"
            }).slider("float");
        });
        function zoo_ln_do_filter(form)
        {
            var url = window.location.href,
                formParams = get_url_params(form.serializeArray());

            if (-1 !== url.indexOf('?')) {
                if (-1 !== url.indexOf('post_type') || -1 !== url.indexOf('product_cat') || -1 !== url.indexOf('product_tag')) {
                    url = url.replace(/&.+/, '');
                    formParams = formParams.replace('?', '&');
                } else {
                    url = url.replace(/\/\?.+/, '/');
                }
            }

            // Remove `relation` param if there's less than 3 params, including itself.
            if (formParams.split('&').length < 3) {
                formParams = formParams.replace(/&relation=[^&]+/, '');
            }

            if ('1' === zoo_ln_params.isCat) {
                var newUrl = zoo_ln_params.shopUrl + formParams;
            } else {
                var newUrl = url + formParams;
            }

            window.history.pushState({path: newUrl}, '', newUrl);

            if (form.hasClass('apply_ajax') && '1' !== zoo_ln_params.isCat) {
                zoo_ln_ajax(form);
            } else {
                form.attr('action', newUrl);
                form.submit();
            }
        }

        function zoo_ln_ajax(current_form) {
            var ajax_url = zoo_ln_params.ajax_url;
            var ajax_params = current_form.data('ln-config');
            var jquery_selector_products = ajax_params.jquery_selector_products;
            var jquery_selector_products_count = ajax_params.jquery_selector_products_count;
            var jquery_selector_paging = ajax_params.jquery_selector_paging;
            var shop_page_info = ajax_params.shop_page_info;
            var data = {
                action: 'zoo_ln_get_product_list',
                zoo_ln_form_data: current_form.serialize()
            };
            $(document).trigger('zoo_ln_before_filter', {
                "form": current_form,
                "selector": jquery_selector_products,
                "selector_count": jquery_selector_products_count,
                "selector_paging": jquery_selector_paging
            });
            $.post(ajax_url, data, function (result) {
                current_form.find('input[name="paged"]').val('');
                $(jquery_selector_products).html(result['html_ul_products_content']);
                $(jquery_selector_products_count).replaceWith(result['html_result_count_content']);
                var html_active_list_item = $.trim(result['html_active_list_item']);
                if (html_active_list_item.length == 0 || html_active_list_item == '') {
                    $('.zoo-active-filter').hide();
                } else {
                    $('.zoo-active-filter').show();
                    $('.zoo-ln-wrap-activated-filter').html(html_active_list_item);
                }

                var html_pagination_content = result['html_pagination_content'];
                if (typeof html_pagination_content != 'undefined') {
                    if (html_pagination_content.length == 0) {
                        $(jquery_selector_paging).hide();
                    } else {
                        var wcPagiNav = $(jquery_selector_paging);
                        if (wcPagiNav.length) {
                            wcPagiNav.show().html(html_pagination_content);
                            wcPagiNav.addClass('zoo_ln_ajax_pagination');
                            wcPagiNav.attr('data-ln-preset', ajax_params.filter_preset);
                        } else {
                            $(jquery_selector_products).after('<nav class="woocommerce-pagination zoo_ln_ajax_pagination" data-ln-preset="'+ajax_params.filter_preset+'">'+html_pagination_content+'</nav>');
                        }
                    }
                }
                window.history.pushState(shop_page_info.title, "Title");
                document.title = decodeEntities(shop_page_info.title);

                $(document).trigger('zoo_ln_after_filter', {
                    "form": current_form,
                    "selector": jquery_selector_products,
                    "selector_count": jquery_selector_products_count,
                    "selector_paging": jquery_selector_paging,
                    "result": result
                });
            });
        }

        function decodeEntities(encodedString) {
            var textArea = document.createElement('textarea');
            textArea.innerHTML = encodedString;
            return textArea.value;
        }

        //Url params
        function get_url_params(form_data) {
            var output = [];
            //merger value filter
            form_data.forEach(function (value) {
                var existing = output.filter(function (v, i) {
                    return v.name == value.name;
                });
                if (existing.length) {
                    var existingIndex = output.indexOf(existing[0]);
                    output[existingIndex].value = output[existingIndex].value.concat(value.value);
                } else {
                    if (typeof value.value == 'string')
                        value.value = [value.value];
                    output.push(value);
                }
            });
            var url_params = '', arg_name = '', arg_type = '', arg_val = '', temp;
            output.forEach(function (data) {

                arg_name = data.name;
                arg_type = arg_name.matchAll(/^.*?(?=\[)/g);
                if (arg_type == null) {
                    arg_type = arg_name;
                }
                arg_val = data.value.toString();
                if (url_params == '') {
                    temp = '?';
                } else {
                    temp = '&';
                }
                if (!!arg_val && !!arg_type) {
                    if (arg_type[0][0] == 'categories') {
                        url_params += temp + 'categories=' + arg_val;
                    } else if (arg_type[0][0] == 'tags') {
                        url_params += temp + 'tags=' + arg_val;
                    }else if (arg_type[0][0] == 'paged') {
                        url_params += temp + 'paged=' + arg_val;
                    }
                    else if (arg_type[0][0] == 'attribute') {
                        url_params += temp + arg_name.matchAll(/\[(.*?)\]/g)[0][1] + '=' + arg_val;
                    } else if (arg_type[0][0] == 'price') {
                        url_params += temp + arg_name.matchAll(/\[(.*?)\]/g)[0][1] + '=' + arg_val;
                    } else if (-1 !== arg_type.indexOf('range-m')) {
                        const rangeVal = parseFloat(arg_val);
                        if ($.isNumeric(rangeVal)) {
                            url_params += temp + arg_name + '=' + rangeVal;
                        }
                    }
                    else {
                        if (arg_val != '0' && !isNaN(arg_val)) {
                            if (arg_type != 'zoo_ln_nonce_setting' && arg_type != 'filter_list_id' && arg_type != '_wp_http_referer' && arg_type != 'posts_per_page' && arg_type != 'pagination_link' && arg_type != 'order' && arg_type != 'order_by')
                                url_params += temp + arg_name + '=' + arg_val.split(' ');
                        }
                    }
                }
            });
            return url_params;
        }

        /*Binding functions*/
        //Binding event before return filter data
        $(document).bind('zoo_ln_before_filter', function (event, response) {
            let form = response.form;
            let selector = response.selector;
            form.parents('.zoo-layer-nav').addClass('zoo-ln-filtering');
            $(selector).addClass('zoo-ln-loading');
        });
        //Binding event after replace filter data
        $(document).bind('zoo_ln_after_filter', function (event, response) {
            let selector = response.selector;
            $('.zoo-layer-nav').removeClass('zoo-ln-filtering');
            $(selector).removeClass('zoo-ln-loading');
        });

        //For search text
        String.prototype.matchAll = function (regexp) {
            var matches = [];
            this.replace(regexp, function () {
                var arr = ([]).slice.call(arguments, 0);
                var extras = arr.splice(-2);
                arr.index = extras[0];
                arr.input = extras[1];
                matches.push(arr);
            });
            return matches.length ? matches : null;
        };
    });
})(jQuery);

(function ($, undefined) {
    '$:nomunge'; // Used by YUI compressor.

    $.fn.serializeObject = function () {
        var obj = {};

        $.each(this.serializeArray(), function (i, o) {
            var n = o.name,
                v = o.value;

            obj[n] = obj[n] === undefined ? v
                : $.isArray(obj[n]) ? obj[n].concat(v)
                    : [obj[n], v];
        });

        return obj;
    };

})(jQuery);
