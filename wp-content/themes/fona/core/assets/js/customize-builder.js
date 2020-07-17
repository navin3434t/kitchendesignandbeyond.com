/**
 * customize.js
 *
 * @supported  IE10+, Chrome, Safari
 *
 * Licensed under the GPL-v2+ license.
 */
(function($, exports)
{
    "use strict"; // Let's see if we can migrate to ES6.

    if (!window.wp || !window.wp.customize) {
        throw new ReferenceError('WordPress Customizer Javascript API is not accessible!');
    }

    /**
    * @namespace  zoo
    */
    exports.zoo = exports.zoo || {};

    var api = wp.customize,
        $document = $(document);

    var ZooMedia = {
        setAttachment: function(attachment) {
            this.attachment = attachment;
        },
        addParamsURL: function(url, data) {
            if (!$.isEmptyObject(data)) {
                url += (url.indexOf('?') >= 0 ? '&' : '?') + $.param(data);
            }
            return url;
        },
        getThumb: function(attachment) {
            var control = this;
            if (typeof attachment !== "undefined") {
                this.attachment = attachment;
            }
            var t = new Date().getTime();
            if (typeof this.attachment.sizes !== "undefined") {
                if (typeof this.attachment.sizes.medium !== "undefined") {
                    return control.addParamsURL(this.attachment.sizes.medium.url, {
                        t: t
                    });
                }
            }
            return control.addParamsURL(this.attachment.url, {
                t: t
            });
        },
        getURL: function(attachment) {
            if (typeof attachment !== "undefined") {
                this.attachment = attachment;
            }
            var t = new Date().getTime();
            return this.addParamsURL(this.attachment.url, {
                t: t
            });
        },
        getID: function(attachment) {
            if (typeof attachment !== "undefined") {
                this.attachment = attachment;
            }
            return this.attachment.id;
        },
        getInputID: function(attachment) {
            $('.attachment-id', this.preview).val();
        },
        setPreview: function($el) {
            this.preview = $el;
        },
        insertImage: function(attachment) {
            if (typeof attachment !== "undefined") {
                this.attachment = attachment;
            }

            var url = this.getURL();
            var id = this.getID();
            var mime = this.attachment.mime;
            $('.zoo-image-preview', this.preview).addClass('zoo-has-file').html('<img src="' + url + '" alt="">');
            $('.attachment-url', this.preview).val(this.toRelativeUrl(url));
            $('.attachment-mime', this.preview).val(mime);
            $('.attachment-id', this.preview).val(id).trigger('change');
            this.preview.addClass('attachment-added');
            this.showChangeBtn();

        },
        toRelativeUrl: function(url) {
            return url;
        },
        showChangeBtn: function() {
            $('.zoo-add', this.preview).addClass('zoo-hide');
            $('.zoo-change', this.preview).removeClass('zoo-hide');
            $('.zoo-remove', this.preview).removeClass('zoo-hide');
        },
        insertVideo: function(attachment) {
            if (typeof attachment !== "undefined") {
                this.attachment = attachment;
            }

            var url = this.getURL();
            var id = this.getID();
            var mime = this.attachment.mime;
            var html = '<video width="100%" height="" controls><source src="' + url + '" type="' + mime + '">Your browser does not support the video tag.</video>';
            $('.zoo-image-preview', this.preview).addClass('zoo-has-file').html(html);
            $('.attachment-url', this.preview).val(this.toRelativeUrl(url));
            $('.attachment-mime', this.preview).val(mime);
            $('.attachment-id', this.preview).val(id).trigger('change');
            this.preview.addClass('attachment-added');
            this.showChangeBtn();
        },
        insertFile: function(attachment) {
            if (typeof attachment !== "undefined") {
                this.attachment = attachment;
            }
            var url = attachment.url;
            var mime = this.attachment.mime;
            var basename = url.replace(/^.*[\\\/]/, '');

            $('.zoo-image-preview', this.preview).addClass('zoo-has-file').html('<a href="' + url + '" class="attachment-file" target="_blank">' + basename + '</a>');
            $('.attachment-url', this.preview).val(this.toRelativeUrl(url));
            $('.attachment-mime', this.preview).val(mime);
            $('.attachment-id', this.preview).val(this.getID()).trigger('change');
            this.preview.addClass('attachment-added');
            this.showChangeBtn();
        },
        remove: function($el) {
            if (typeof $el !== "undefined") {
                this.preview = $el;
            }
            $('.zoo-image-preview', this.preview).removeAttr('style').html('').removeClass('zoo-has-file');
            $('.attachment-url', this.preview).val('');
            $('.attachment-mime', this.preview).val('');
            $('.attachment-id', this.preview).val('').trigger('change');
            this.preview.removeClass('attachment-added');

            $('.zoo-add', this.preview).removeClass('zoo-hide');
            $('.zoo-change', this.preview).addClass('zoo-hide');
            $('.zoo-remove', this.preview).addClass('zoo-hide');
        }

    };

    ZooMedia.controlMediaImage = wp.media({
        title: wp.media.view.l10n.addMedia,
        multiple: false,
        library: {
            type: 'image'
        }
    });

    ZooMedia.controlMediaImage.on('select', function() {
        var attachment = ZooMedia.controlMediaImage.state().get('selection').first().toJSON();
        ZooMedia.insertImage(attachment);
    });

    ZooMedia.controlMediaVideo = wp.media({
        title: wp.media.view.l10n.addMedia,
        multiple: false,
        library: {
            type: 'video'
        }
    });

    ZooMedia.controlMediaVideo.on('select', function() {
        var attachment = ZooMedia.controlMediaVideo.state().get('selection').first().toJSON();
        ZooMedia.insertVideo(attachment);
    });

    ZooMedia.controlMediaFile = wp.media({
        title: wp.media.view.l10n.addMedia,
        multiple: false
    });

    ZooMedia.controlMediaFile.on('select', function() {
        var attachment = ZooMedia.controlMediaFile.state().get('selection').first().toJSON();
        ZooMedia.insertFile(attachment);
    });

    var zoo_controls_list = {};
    //---------------------------------------------------------------------------

    var zooField = {
        devices: ['desktop', 'mobile'],
        allDevices: ['desktop', 'mobile'],
        type: 'zoo',
        getTemplate: _.memoize(function() {
            var field = this;
            var compiled,
                options = {
                    evaluate: /<#([\s\S]+?)#>/g,
                    interpolate: /\{\{\{([\s\S]+?)\}\}\}/g,
                    escape: /\{\{([^\}]+?)\}\}(?!\})/g,
                    variable: 'data'
                };

            return function(data, id, data_variable_name) {
                if (_.isUndefined(id)) {
                    id = 'tmpl-zoo-customize-control-' + field.type;
                }
                compiled = _.template($('#' + id).html(), null, options);
                return compiled(data);
            };
        }),

        getFieldValue: function(name, fieldSetting, $field) {
            var control = this;
            var type = undefined;
            var support_devices = false;

            if (!_.isUndefined(fieldSetting)) {
                type = fieldSetting.type;
                support_devices = fieldSetting.device_settings;
            }

            var value = '';
            switch (type) {
                case 'media':
                case 'image':
                case 'video':
                case 'attachment':
                case 'audio':
                    if (support_devices) {
                        value = {};
                        _.each(control.allDevices, function(device) {
                            var _name = name + '-' + device;
                            value[device] = {
                                id: $('input[data-name="' + _name + '"]', $field).val(),
                                url: $('input[data-name="' + _name + '-url"]', $field).val(),
                                mime: $('input[data-name="' + _name + '-mime"]', $field).val()
                            };
                        });
                    } else {
                        value = {
                            id: $('input[data-name="' + name + '"]', $field).val(),
                            url: $('input[data-name="' + name + '-url"]', $field).val(),
                            mime: $('input[data-name="' + name + '-mime"]', $field).val()
                        };
                    }

                    break;
                case 'css_rule':

                    if (support_devices) {
                        value = {};
                        _.each(control.allDevices, function(device) {
                            var _name = name + '-' + device;
                            value[device] = {
                                unit: $('input[data-name="' + _name + '-unit"]:checked', $field).val(),
                                top: $('input[data-name="' + _name + '-top"]', $field).val(),
                                right: $('input[data-name="' + _name + '-right"]', $field).val(),
                                bottom: $('input[data-name="' + _name + '-bottom"]', $field).val(),
                                left: $('input[data-name="' + _name + '-left"]', $field).val(),
                                link: $('input[data-name="' + _name + '-link"]', $field).is(':checked') ? 1 : ''
                            };
                        });
                    } else {
                        value = {
                            unit: $('input[data-name="' + name + '-unit"]:checked', $field).val(),
                            top: $('input[data-name="' + name + '-top"]', $field).val(),
                            right: $('input[data-name="' + name + '-right"]', $field).val(),
                            bottom: $('input[data-name="' + name + '-bottom"]', $field).val(),
                            left: $('input[data-name="' + name + '-left"]', $field).val(),
                            link: $('input[data-name="' + name + '-link"]', $field).is(':checked') ? 1 : ''
                        };
                    }

                    break;
                case 'shadow':
                    if (support_devices) {
                        value = {};
                        _.each(control.allDevices, function(device) {
                            var _name = name + '-' + device;
                            value[device] = {
                                color: $('input[data-name="' + _name + '-color"]', $field).val(),
                                x: $('input[data-name="' + _name + '-x"]', $field).val(),
                                y: $('input[data-name="' + _name + '-y"]', $field).val(),
                                blur: $('input[data-name="' + _name + '-blur"]', $field).val(),
                                spread: $('input[data-name="' + _name + '-spread"]', $field).val(),
                                inset: $('input[data-name="' + _name + '-inset"]', $field).is(':checked') ? 1 : false
                            };
                        });
                    } else {
                        value = {
                            color: $('input[data-name="' + name + '-color"]', $field).val(),
                            x: $('input[data-name="' + name + '-x"]', $field).val(),
                            y: $('input[data-name="' + name + '-y"]', $field).val(),
                            blur: $('input[data-name="' + name + '-blur"]', $field).val(),
                            spread: $('input[data-name="' + name + '-spread"]', $field).val(),
                            inset: $('input[data-name="' + name + '-inset"]', $field).is(':checked') ? 1 : false,
                        };
                    }

                    break;
                case 'font_style':

                    if (support_devices) {
                        value = {};
                        _.each(control.allDevices, function(device) {
                            var _name = name + '-' + device;
                            value[device] = {
                                b: $('input[data-name="' + _name + '-b"]', $field).is(':checked') ? 1 : '',
                                i: $('input[data-name="' + _name + '-i"]', $field).is(':checked') ? 1 : '',
                                u: $('input[data-name="' + _name + '-u"]', $field).is(':checked') ? 1 : '',
                                s: $('input[data-name="' + _name + '-s"]', $field).is(':checked') ? 1 : '',
                                t: $('input[data-name="' + _name + '-t"]', $field).is(':checked') ? 1 : ''
                            };
                        });
                    } else {
                        value = {
                            b: $('input[data-name="' + name + '-b"]', $field).is(':checked') ? 1 : '',
                            i: $('input[data-name="' + name + '-i"]', $field).is(':checked') ? 1 : '',
                            u: $('input[data-name="' + name + '-u"]', $field).is(':checked') ? 1 : '',
                            s: $('input[data-name="' + name + '-s"]', $field).is(':checked') ? 1 : '',
                            t: $('input[data-name="' + name + '-t"]', $field).is(':checked') ? 1 : ''
                        };
                    }

                    break;
                case 'font':

                    if (support_devices) {
                        value = {};
                        _.each(control.allDevices, function(device) {
                            var _name = name + '-' + device;
                            var subsets = {};
                            $('.list-subsets[data-name="' + _name + '-subsets"] input', $field).each(function() {
                                if ($(this).is(':checked')) {
                                    var _v = $(this).val();
                                    subsets[_v] = _v;
                                }
                            });
                            value[device] = {
                                font: $('select[data-name="' + _name + '-font"]', $field).val(),
                                type: $('input[data-name="' + _name + '-type"]', $field).val(),
                                variant: $('select[data-name="' + _name + '-variant"]', $field).val(), // variant
                                subsets: subsets
                            };
                        });
                    } else {
                        var subsets = {};
                        $('.list-subsets[data-name="' + name + '-subsets"] input', $field).each(function() {
                            if ($(this).is(':checked')) {
                                var _v = $(this).val();
                                subsets[_v] = _v;
                            }
                        });
                        value = {
                            font: $('select[data-name="' + name + '-font"]', $field).val(),
                            type: $('input[data-name="' + name + '-type"]', $field).val(),
                            variant: $('select[data-name="' + name + '-variant"]', $field).val(),
                            subsets: subsets
                        };
                    }

                    break;
                case 'slider':

                    if (support_devices) {
                        value = {};
                        _.each(control.allDevices, function(device) {
                            var _name = name + '-' + device;
                            value[device] = {
                                unit: $('input[data-name="' + _name + '-unit"]:checked', $field).val(),
                                value: $('input[data-name="' + _name + '-value"]', $field).val()
                            };
                        });
                    } else {
                        value = {
                            unit: $('input[data-name="' + name + '-unit"]:checked', $field).val(),
                            value: $('input[data-name="' + name + '-value"]', $field).val()
                        };
                    }

                    break;
                case 'icon':

                    if (support_devices) {
                        value = {};
                        _.each(control.allDevices, function(device) {
                            var _name = name + '-' + device;
                            value[device] = {
                                type: $('input[data-name="' + _name + '-type"]', $field).val(),
                                icon: $('input[data-name="' + _name + '"]', $field).val()
                            };
                        });
                    } else {
                        value = {
                            type: $('input[data-name="' + name + '-type"]', $field).val(),
                            icon: $('input[data-name="' + name + '"]', $field).val()
                        };
                    }
                    break;
                case 'radio':
                case 'text_align':
                case 'text_align_no_justify':

                    if (support_devices) {
                        value = {};
                        _.each(control.allDevices, function(device) {
                            var input = $('input[data-name="' + name + '-' + device + '"]:checked', $field);
                            value[device] = input.length ? input.val() : '';
                        });
                    } else {
                        value = $('input[data-name="' + name + '"]:checked', $field).val();
                    }

                    break;
                case 'checkbox':

                    if (support_devices) {
                        value = {};
                        _.each(control.allDevices, function(device) {
                            value[device] = $('input[data-name="' + name + '-' + device + '"]', $field).is(':checked') ? 1 : '';
                        });
                    } else {
                        value = $('input[data-name="' + name + '"]', $field).is(':checked') ? 1 : '';
                    }

                    break;

                case 'checkboxes':
                    value = {};
                    if (support_devices) {
                        _.each(control.allDevices, function(device) {
                            value[device] = {};
                            $('input[data-name="' + name + '-' + device + '"]', $field).each(function() {
                                var v = $(this).val();
                                if ($(this).is(':checked')) {
                                    value[v] = v;
                                }
                            });

                        });
                    } else {
                        $('input[data-name="' + name + '"]', $field).each(function() {
                            var v = $(this).val();
                            if ($(this).is(':checked')) {
                                value[v] = v;
                            }
                        });
                    }

                    break;
                case 'typography':
                case 'modal':
                case 'styling':
                    if (support_devices) {
                        value = {};
                        _.each(control.allDevices, function(device) {
                            value[device] = $('[data-name="' + name + '-' + device + '"]', $field).val();
                        });
                    } else {
                        value = $('[data-name="' + name + '"]', $field).val();
                    }

                    try {
                        value = JSON.parse(value);
                    } catch (e) {

                    }
                    break;
                default:
                    if (support_devices) {
                        value = {};
                        _.each(control.allDevices, function(device) {
                            value[device] = $('[data-name="' + name + '-' + device + '"]', $field).val();
                        });
                    } else {
                        value = $('[data-name="' + name + '"]', $field).val();
                    }
                    break;
            }

            return value;

        },
        getValue: function(field, container) {
            var control = this;
            var value = '';

            switch (field.type) {
                case 'group':
                    value = {};

                    if (field.device_settings) {
                        _.each(control.allDevices, function(device) {
                            var $area = $('.zoo-group-device-fields.zoo-for-' + device, container);
                            value[device] = {};
                            var _value = {};
                            _.each(field.fields, function(f) {
                                var $_field = $('.zoo-group-field[data-field-name="' + f.name + '"]', $area);
                                _value[f.name] = control.getFieldValue(f.name, f, $_field);
                            });
                            value[device] = _value;
                            control.initConditional($area, _value);

                        });
                    } else {
                        _.each(field.fields, function(f) {
                            var $_field = $('.zoo-group-field[data-field-name="' + f.name + '"]', container);
                            value[f.name] = control.getFieldValue(f.name, f, $_field);
                        });
                        control.initConditional(container, value);
                    }

                    break;
                case 'repeater':
                    value = [];
                    $('.zoo-repeater-item', container).each(function(index) {
                        var $item = $(this);
                        var _v = {};
                        _.each(field.fields, function(f) {
                            var inputField = $('[data-field-name="' + f.name + '"]', $item);
                            var _fv = control.getFieldValue(f.name, f, $item);
                            _v[f.name] = _fv;
                            if (field.live_title_field == f.name) {
                                if (inputField.prop("tagName") == 'select') {
                                    _fv = $('option[value="' + _fv + '"]').first().text();
                                } else if (_.isUndefined(_fv) || _fv == '') {
                                    _fv = ZooCustomizeBuilderData.untitled;
                                }
                                control.updateRepeaterLiveTitle(_fv, $item, f);
                            }
                        });

                        control.initConditional($item, _v);

                        value[index] = _v;
                        value[index]['_visibility'] = 'visible';

                        if ($('input.r-visible-input', $item).length) {
                            if (!$('input.r-visible-input', $item).is(':checked')) {
                                value[index]['_visibility'] = 'hidden';
                            }
                        }

                    });
                    break;
                default:
                    value = this.getFieldValue(field.name, field, container);
                    break;
            }

            return value;
        },
        encodeValue: function(value) {
            return encodeURI(JSON.stringify(value));
        },
        decodeValue: function(value) {
            return JSON.parse(decodeURI(value));
        },
        updateRepeaterLiveTitle: function(value, $item, field) {
            $('.zoo-repeater-live-title', $item).text(value);
        },
        compare: function(value1, cond, value2) {
            var equal = false;
            switch (cond) {
                case '==':
                    equal = (value1 == value2) ? true : false;
                    break;
                case '===':
                    equal = (value1 === value2) ? true : false;
                    break;
                case '>':
                    equal = (value1 > value2) ? true : false;
                    break;
                case '<':
                    equal = (value1 < value2) ? true : false;
                    break;
                case '!=':
                    equal = (value1 != value2) ? true : false;
                    break;
                case '!==':
                    equal = (value1 !== value2) ? true : false;
                    break;
                case 'empty':
                    var _v = _.clone(value1);
                    if (_.isObject(_v) || _.isArray(_v)) {
                        _.each(_v, function(v, i) {
                            if (_.isEmpty(v)) {
                                delete _v[i];
                            }
                        });

                        equal = _.isEmpty(_v) ? true : false;
                    } else {
                        equal = _.isNull(_v) || _v == '' ? true : false;
                    }
                    break;
                case 'not_empty':
                    var _v = _.clone(value1);
                    if (_.isObject(_v) || _.isArray(_v)) {
                        _.each(_v, function(v, i) {
                            if (_.isEmpty(v)) {
                                delete _v[i];
                            }
                        })
                    }
                    equal = _.isEmpty(_v) ? false : true;
                    break;
                default:
                    if (_.isArray(value2)) {
                        if (!_.isEmpty(value2) && !_.isEmpty(value1)) {
                            equal = _.contains(value2, value1);
                        } else {
                            equal = false;
                        }
                    } else {
                        equal = (value1 == value2) ? true : false;
                    }
            }

            return equal;
        },
        multiple_compare: function(list, values, decodeValue) {
            if (_.isUndefined(decodeValue)) {
                decodeValue = false;
            }
            var control = this;
            var check = false;
            try {
                var test = list[0];
                if (_.isString(test)) {
                    check = false;
                    var cond = list[1];
                    var cond_val = list[2];
                    var cond_device = false;
                    if (!_.isUndefined(list[3])) {
                        cond_device = list[3];
                    } else {
                        cond_device = api.previewedDevice.get();
                    }
                    var value;
                    if (!_.isUndefined(values[test])) {
                        value = values[test];
                        if (decodeValue)
                            value = control.decodeValue(value);
                        if (_.isObject(value) && !_.isUndefined(value[cond_device]))
                            value = value[cond_device];
                        check = control.compare(value, cond, cond_val);
                    }
                } else if (_.isArray(test)) {
                    check = true;
                    _.each(list, function(req) {

                        var cond_key = req[0];
                        var cond_cond = req[1];
                        var cond_val = req[2];
                        var cond_device = false;
                        if (!_.isUndefined(req[3])) {
                            cond_device = req[3];
                        }
                        var t_val = values[cond_key];
                        if (_.isUndefined(t_val)) {
                            t_val = '';
                        }
                        if (decodeValue && _.isString(t_val)) {
                            try {
                                t_val = control.decodeValue(t_val)
                            } catch (e) {

                            }
                        }
                        if (cond_device) {
                            if (_.isObject(t_val) && !_.isUndefined(t_val[cond_device])) {
                                t_val = t_val[cond_device];
                            }
                        }

                        if (!control.compare(t_val, cond_cond, cond_val)) {
                            check = false;
                        }
                    });

                }
            }catch (e) {
                return;
            }

            return check;
        },
        initConditional: function($el, values) {
            var control = this;
            var $fields = $('.zoo-customize-control', $el);
            $fields.each(function() {
                var $field = $(this);
                var check = true;
                var req = $field.attr('data-required') || false;
                if (!_.isUndefined(req) && req) {
                    req = JSON.parse(req);
                    check = control.multiple_compare(req, values);
                    if (!check) {
                        $field.addClass('zoo-hide');
                    } else {
                        $field.removeClass('zoo-hide');
                    }
                }
            });
        },

        addRepeaterItem: function(field, value, $container, cb) {
            if (!_.isObject(value)) {
                value = {};
            }

            var control = this;
            var template = control.getTemplate();
            var fields = field.fields;
            var addable = true;
            var title_only = field.title_only;
            if (field.addable === false) {
                addable = false;
            }

            var $itemWrapper = $(template(field, 'tmpl-customize-control-repeater-layout'));
            $container.find('.zoo-settings-fields').append($itemWrapper);
            _.each(fields, function(f, index) {
                f.value = '';
                f.addable = addable;
                if (!_.isUndefined(value[f.name])) {
                    f.value = value[f.name];
                }
                var $fieldArea;
                $fieldArea = $('<div class="zoo-repeater-field"></div>');
                $('.zoo-repeater-item-inner', $itemWrapper).append($fieldArea);
                control.add(f, $fieldArea, function() {
                    if (_.isFunction(cb)) {
                        cb();
                    }
                });


                var live_title = f.value;
                // Update Live title
                if (field.live_title_field === f.name) {
                    if (f.type === 'select') {
                        live_title = f.choices[f.value];
                    } else if (_.isUndefined(live_title) || live_title == '') {
                        live_title = ZooCustomizeBuilderData.untitled;
                    }
                    control.updateRepeaterLiveTitle(live_title, $itemWrapper, f);
                }

            });

            if (!_.isUndefined(value._visibility) && value._visibility === 'hidden') {
                $itemWrapper.addClass('item-hidden');
                $itemWrapper.find('input.r-visible-input').removeAttr('checked');
            } else {
                $itemWrapper.find('input.r-visible-input').prop('checked', 'checked');
            }

            if (title_only) {
                $('.zoo-repeater-item-settings, .zoo-repeater-item-toggle', $itemWrapper).hide();
            }

            $document.trigger('zoo/customizer/repeater/add', [$itemWrapper, control]);
            return $itemWrapper;
        },
        limitRepeaterItems: function(field, $container) {
            return;
            var control = this;
            var addButton = $('.zoo-repeater-add-new', $container);
            var c = $('.zoo-settings-fields .zoo-repeater-item', $container).length;

            if (control.params.limit > 0) {
                if (c >= control.params.limit) {
                    addButton.addClass('zoo-hide');
                    if (control.params.limit_msg) {
                        if ($('.zoo-limit-item-msg', control.container).length === 0) {
                            $('<p class="zoo-limit-item-msg">' + control.params.limit_msg + '</p>').insertBefore(addButton);
                        } else {
                            $('.zoo-limit-item-msg', control.container).removeClass('zoo-hide');
                        }
                    }
                } else {
                    $('.zoo-limit-item-msg', control.container).addClass('zoo-hide');
                    addButton.removeClass('zoo-hide');
                }
            }

            if (c > 0) {
                $('.zoo-repeater-reorder', control.container).removeClass('zoo-hide');
            } else {
                $('.zoo-repeater-reorder', control.container).addClass('zoo-hide');
            }

        },
        initRepeater: function(field, $container, cb) {
            var control = this;
            field = _.defaults(field, {
                addable: null,
                title_only: null,
                limit: null,
                live_title_field: null,
                fields: null,
            });
            field.limit = parseInt(field.limit);
            if (isNaN(field.limit)) {
                field.limit = 0;
            }

            // Sortable
            $container.find('.zoo-settings-fields').sortable({
                handle: '.zoo-repeater-item-heading',
                containment: "parent",
                update: function(event, ui) {
                    // control.getValue();
                    if (_.isFunction(cb)) {
                        cb();
                    }
                }
            });

            // Toggle Move
            $container.on('click', '.zoo-repeater-reorder', function(e) {
                e.preventDefault();
                $('.zoo-repeater-items', $container).toggleClass('reorder-active');
                $('.zoo-repeater-add-new', $container).toggleClass('disabled');
                if ($('.zoo-repeater-items', $container).hasClass('reorder-active')) {
                    $(this).html($(this).data('done'));
                } else {
                    $(this).html($(this).data('text'));
                }
            });

            // Move Up
            $container.on('click', '.zoo-repeater-item .zoo-up', function(e) {
                e.preventDefault();
                var i = $(this).closest('.zoo-repeater-item');
                var index = i.index();
                if (index > 0) {
                    var up = i.prev();
                    i.insertBefore(up);
                    if (_.isFunction(cb)) {
                        cb();
                    }
                }
            });

            // Move Down
            $container.on('click', '.zoo-repeater-item .zoo-down', function(e) {
                e.preventDefault();
                var n = $('.zoo-repeater-items .zoo-repeater-item', $container).length;
                var i = $(this).closest('.zoo-repeater-item');
                var index = i.index();
                if (index < n - 1) {
                    var down = i.next();
                    i.insertAfter(down);
                    if (_.isFunction(cb)) {
                        cb();
                    }
                }
            });


            // Add item when customizer loaded
            if (_.isArray(field.value)) {
                _.each(field.value, function(itemValue) {
                    control.addRepeaterItem(field, itemValue, $container, cb);
                });
                //control.getValue(false);
            }
            control.limitRepeaterItems();

            // Toggle visibility
            $container.on('change', '.zoo-repeater-item .r-visible-input', function(e) {
                e.preventDefault();
                var p = $(this).closest('.zoo-repeater-item');
                if ($(this).is(':checked')) {
                    p.removeClass('item-hidden');
                } else {
                    p.addClass('item-hidden');
                }
            });

            // Toggle
            if (!field.title_only) {
                $container.on('click', '.zoo-repeater-item-toggle, .zoo-repeater-live-title', function(e) {
                    e.preventDefault();
                    var p = $(this).closest('.zoo-repeater-item');
                    p.toggleClass('zoo-open');
                });
            }

            // Remove
            $container.on('click', '.zoo-remove', function(e) {
                e.preventDefault();
                var p = $(this).closest('.zoo-repeater-item');
                p.remove();
                $document.trigger('zoo/customizer/repeater/remove', [control]);
                if (_.isFunction(cb)) {
                    cb();
                }
                control.limitRepeaterItems();
            });


            var defaultValue = {};
            _.each(field.fields, function(f, k) {
                defaultValue[f.name] = null;
                if (!_.isUndefined(f.default)) {
                    defaultValue[f.name] = f.default;
                }
            });

            // Add Item
            $container.on('click', '.zoo-repeater-add-new', function(e) {
                e.preventDefault();
                if (!$(this).hasClass('disabled')) {
                    control.addRepeaterItem(field, defaultValue, $container, cb);
                    if (_.isFunction(cb)) {
                        cb();
                    }
                    control.limitRepeaterItems();
                }
            });
        },

        add: function(field, $fieldsArea, cb) {
            var control = this;
            var template = control.getTemplate();
            var template_id = 'tmpl-zoo-customize-control-' + field.type;
            if ($('#' + template_id).length == 0) {
                template_id = 'tmpl-zoo-customize-control-default';
            }

            if (field.device_settings) {
                var fieldItem = null;
                _.each(control.devices, function(device, index) {
                    var _field = _.clone(field);
                    _field.original_name = field.name;
                    if (_.isObject(field.value)) {
                        if (!_.isUndefined(field.value[device])) {
                            _field.value = field.value[device];
                        } else {
                            _field.value = '';
                        }
                    } else {
                        _field.value = '';
                        if (index === 0) {
                            _field.value = field.value;
                        }
                    }
                    _field.name = field.name + '-' + device;
                    _field._current_device = device;

                    var $deviceFields = $(template(_field, template_id, 'field'));
                    var deviceFieldItem = $deviceFields.find('.zoo-customize-control-settings-inner').first();

                    if (!fieldItem) {
                        $fieldsArea.append($deviceFields).addClass('zoo-multiple-devices');
                    }

                    deviceFieldItem.addClass('zoo-for-' + device);
                    deviceFieldItem.attr('data-for-device', device);

                    if (fieldItem) {
                        deviceFieldItem.insertAfter(fieldItem);
                        fieldItem = deviceFieldItem;
                    }
                    fieldItem = deviceFieldItem;

                });
            } else {
                field.original_name = field.name;
                var $fields = template(field, template_id, 'field');
                $fieldsArea.html($fields);
            }

            // Repeater
            if (field.type === 'repeater') {
                var $rf_area = $(template(field, 'tmpl-customize-control-repeater-inner'));
                $fieldsArea.find('.zoo-customize-control-settings-inner').replaceWith($rf_area);
                control.initRepeater(field, $rf_area, cb);
            }

            if (field.css_format && _.isString(field.css_format)) {
                if (field.css_format.indexOf('value_no_unit') > 0) {
                    $fieldsArea.find('.zoo-slider-input').addClass('no-unit');
                    $('.zoo-css-unit .zoo-label-active', $fieldsArea).hide();
                }
            }

            // Add unility
            switch (field.type) {
                case 'color':
                case 'shadow':
                    control.initColor($fieldsArea);
                    break;
                case 'image':
                case 'video':
                case 'audio':
                case 'attchment':
                case 'file':
                    control.initMedia($fieldsArea);
                    break;
                case 'slider':
                    control.initSlider($fieldsArea);
                    break;
                case 'css_rule':
                    control.initCSSRuler($fieldsArea, cb);
                    break;
            }

        },

        addFields: function(fields, values, $fieldsArea, cb) {
            var control = this;
            if (!_.isObject(values)) {
                values = {};
            }
            _.each(fields, function(f, index) {
                if (_.isUndefined(f.class)) {
                    f.class = '';
                }
                var $fieldArea = $('<div class="zoo-group-field ft--' + f.type + ' ' + f.class + '" data-field-name="' + f.name + '"></div>');
                $fieldsArea.append($fieldArea);
                f.original_name = f.name;
                if (!_.isUndefined(values[f.name])) {
                    f.value = values[f.name];
                } else if (!_.isUndefined(f.default)) {
                    f.value = f.default;
                } else {
                    f.value = null;
                }
                control.add(f, $fieldArea, cb);
            });
        },

        initSlider: function($el) {
            if ($('.zoo-input-slider', $el).length > 0) {
                $('.zoo-input-slider', $el).each(function() {
                    var slider = $(this);
                    var p = slider.parent();
                    var input = $('.zoo-slider-input', p);
                    var min = slider.data('min') || 0;
                    var max = slider.data('max') || 300;
                    var step = slider.data('step') || 1;
                    if (!_.isNumber(min)) {
                        min = 0;
                    }

                    if (!_.isNumber(max)) {
                        max = 300;
                    }

                    if (!_.isNumber(step)) {
                        step = 1;
                    }

                    var current_val = input.val();
                    slider.slider({
                        range: "min",
                        value: current_val,
                        step: step,
                        min: min,
                        max: max,
                        slide: function(event, ui) {
                            input.val(ui.value).trigger('data-change');
                        }
                    });

                    input.on('change', function() {
                        slider.slider("value", $(this).val());
                    });

                    // Reset
                    var wrapper = slider.closest('.zoo-input-slider-wrapper');
                    wrapper.on('click', '.reset', function(e) {
                        e.preventDefault();
                        var d = slider.data('default');
                        if (!_.isObject(d)) {
                            d = {
                                'unit': 'px',
                                'value': ''
                            }
                        }

                        $('.zoo-slider-input', wrapper).val(d.value);
                        slider.slider("option", "value", d.value);
                        $('.zoo-css-unit input.zoo-input[value="' + d.unit + '"]', wrapper).trigger('click');
                        $('.zoo-slider-input', wrapper).trigger('change');

                    });

                });
            }
        },

        initMedia: function($el) {

            // When add/Change
            $el.on('click', '.zoo-media .zoo-add, .zoo-media .zoo-change, .zoo-media .zoo-image-preview', function(e) {
                e.preventDefault();
                var p = $(this).closest('.zoo-media');
                ZooMedia.setPreview(p);
                ZooMedia.controlMediaImage.open();
            });

            // When add/Change
            $el.on('click', '.zoo-media .zoo-remove', function(e) {
                e.preventDefault();
                var p = $(this).closest('.zoo-media');
                ZooMedia.remove(p);
            });
        },

        initCSSRuler: function($el, change_cb) {
            // When toggle value change
            $el.on('change', '.zoo-label-parent', function() {
                if ($(this).attr('type') == 'radio') {
                    var name = $(this).attr('name');
                    $('input[name="' + name + '"]', $el).parent().removeClass('zoo-label-active');
                }
                var checked = $(this).is(':checked');
                if (checked) {
                    $(this).parent().addClass('zoo-label-active');
                } else {
                    $(this).parent().removeClass('zoo-label-active');
                }
                if (_.isFunction(change_cb)) {
                    change_cb();
                }
            });

            $el.on('change keyup', '.zoo-css-ruler .zoo-input-css', function() {
                var p = $(this).closest('.zoo-css-ruler');
                var link_checked = $('.zoo-css-ruler-link input', p).is(':checked');
                if (link_checked) {
                    var v = $(this).val();
                    $('.zoo-input-css', p).not($(this)).each(function() {
                        if (!$(this).is(':disabled')) {
                            $(this).val(v);
                        }
                    });
                }
                if (_.isFunction(change_cb)) {
                    change_cb();
                }
            });

        },

        initColor: function($el) {

            $('.zoo-input-color', $el).each(function() {
                var colorInput = $(this);
                var df = colorInput.data('default') || '';
                var current_val = $('.zoo-input--color', colorInput).val();
                // data-alpha="true"
                $('.zoo-color-panel', colorInput).attr('data-alpha', 'true');
                $('.zoo-color-panel', colorInput).wpColorPicker({
                    defaultColor: df,
                    change: function(event, ui) {
                        var new_color = ui.color.toString();
                        $('.zoo-input--color', colorInput).val(new_color);
                        if (ui.color.toString() !== current_val) {
                            current_val = new_color;
                            $('.zoo-input--color', colorInput).trigger('change');
                        }
                    },
                    clear: function(event, ui) {
                        $('.zoo-input--color', colorInput).val('');
                        $('.zoo-input--color', colorInput).trigger('data-change');
                    }

                });
            });
        },

    };

    //-------------------------------------------------------------------------

    var zoo_controlConstructor = {
        devices: ['desktop', 'mobile'],
        // When we're finished loading continue processing
        type: 'zoo',
        settingField: null,

        getTemplate: _.memoize(function() {
            var control = this;
            var compiled,
                options = {
                    evaluate: /<#([\s\S]+?)#>/g,
                    interpolate: /\{\{\{([\s\S]+?)\}\}\}/g,
                    escape: /\{\{([^\}]+?)\}\}(?!\})/g,
                    variable: 'data'
                };

            return function(data, id, data_variable_name) {
                if (_.isUndefined(id)) {
                    id = 'tmpl-zoo-customize-control-' + control.type;
                }
                compiled = _.template($('#' + id).html(), null, options);
                return compiled(data);
            };

        }),
        // addDeviceSwitchers: zooField.addDeviceSwitchers,
        init: function() {

            var control = this;

            if (_.isArray(control.params.devices) && !_.isEmpty(control.params.devices)) {
                control.devices = control.params.devices;
            }

            // The hidden field that keeps the data saved (though we never update it)
            control.settingField = control.container.find('[data-customize-setting-link]').first();

            switch (control.params.setting_type) {
                case 'group':
                    control.initGroup();
                    break;
                case 'repeater':
                    control.initRepeater();
                    break;
                default:
                    control.initField();
                    break;
            }

            control.container.on('change keyup data-change', 'input:not(.change-by-js), select:not(.change-by-js), textarea:not(.change-by-js)', function() {
                control.getValue();
            });

        },
        addParamsURL: function(url, data) {
            if (!$.isEmptyObject(data)) {
                url += (url.indexOf('?') >= 0 ? '&' : '?') + $.param(data);
            }
            return url;
        },

        compare: zooField.compare,
        multiple_compare: zooField.multiple_compare,
        initConditional: zooField.initConditional,

        getValue: function(save) {
            var control = this;
            var value = '';
            var field = {
                type: control.params.setting_type,
                name: control.id,
                value: control.params.value,
                default: control.params.default,
                devices: control.params.devices,
            };

            if (field.type === 'slider') {
                field.min = control.params.min;
                field.max = control.params.max;
                field.step = control.params.step;
                field.unit = control.params.unit;
            }

            if (field.type === 'css_rule') {
                field.fields_disabled = control.params.fields_disabled;
            }

            if (field.type === 'group' || field.type === 'repeater') {
                field.fields = control.params.fields;
                field.live_title_field = control.params.live_title_field;
            }

            if (control.params.setting_type === 'select' || control.params.setting_type === 'radio') {
                field.choices = control.params.choices;
            }
            if (control.params.setting_type === 'checkbox') {
                field.checkbox_label = control.params.checkbox_label;
            }

            field.device_settings = control.params.device_settings;

            value = zooField.getValue(field, $('.zoo-settings-fields', control.container));

            if (_.isUndefined(save) || save) {
                control.setting.set(control.encodeValue(value));


                // Need improve next version
                if (_.isArray(control.params.reset_controls)) {
                    _.each(control.params.reset_controls, function(_cid) {
                        try {
                            var c = api.control(_cid);
                            c.setting.set(control.encodeValue(c.params.default));
                        } catch (e) {

                        }
                    });
                }

                $document.trigger('zoo/customizer/value_changed', [control, value]);
            } else {

            }

            return value;
        },
        encodeValue: function(value) {
            return encodeURI(JSON.stringify(value));
        },
        decodeValue: function(value) {
            var decoded = false;
            try {
                decoded = JSON.parse(decodeURI(value));
            } catch (e) {

            }
            if (decoded) {
                return decoded;
            } else {
                return value;
            }
        },
        updateRepeaterLiveTitle: function(value, $item, field) {
            $('.zoo-repeater-live-title', $item).text(value);
        },
        initGroup: function() {
            var control = this;
            if (control.params.device_settings) {
                control.container.find('.zoo-settings-fields').addClass('zoo-multiple-devices');
                if (!_.isObject(control.params.value)) {
                    control.params.value = {};
                }

                _.each(control.devices, function(device, device_index) {
                    var $group_device = $('<div class="zoo-group-device-fields zoo-customize-control-settings-inner zoo-for-' + device + '"></div>');
                    control.container.find('.zoo-settings-fields').append($group_device);
                    var device_value = {};
                    if (!_.isUndefined(control.params.value[device])) {
                        device_value = control.params.value[device];
                    }
                    if (!_.isObject(device_value)) {
                        device_value = {};
                    }

                    zooField.addFields(control.params.fields, device_value, $group_device, function() {
                        control.getValue();
                    });

                });

            } else {
                zooField.addFields(control.params.fields, control.params.value, control.container.find('.zoo-settings-fields'), function() {
                    control.getValue();
                });
            }

            control.getValue(false);
        },
        addField: function(field, $fieldsArea, cb) {
            zooField.devices = _.clone(this.devices);
            zooField.add(field, $fieldsArea, cb);
        },
        initField: function() {
            var control = this;
            var field = _.clone(control.params);

            field = _.extend(field, {
                type: control.params.setting_type,
                name: control.id,
                value: control.params.value,
                default: control.params.default,
                devices: control.params.devices,
                unit: control.params.unit,
                title: null,
                label: null,
                description: null
            });

            if (field.type == 'slider') {
                field.min = control.params.min;
                field.max = control.params.max;
                field.step = control.params.step;
            }

            if (field.type == 'css_rule') {
                field.fields_disabled = control.params.fields_disabled;
            }

            if (control.params.setting_type == 'select' || control.params.setting_type == 'radio') {
                field.choices = control.params.choices;
            }
            if (control.params.setting_type == 'checkbox') {
                field.checkbox_label = control.params.checkbox_label;
            }

            field.device_settings = control.params.device_settings;
            var $fieldsArea = control.container.find('.zoo-settings-fields');

            control.addField(field, $fieldsArea, function() {
                control.getValue();
            });

        },
        addRepeaterItem: function(value) {
            if (!_.isObject(value)) {
                value = {};
            }

            var control = this;
            var template = control.getTemplate();
            var fields = control.params.fields;
            var addable = true;
            var title_only = control.params.title_only;
            if (control.params.addable === false) {
                addable = false;
            }

            var $itemWrapper = $(template(control.params, 'tmpl-customize-control-repeater-item'));
            control.container.find('.zoo-settings-fields').append($itemWrapper);
            _.each(fields, function(f, index) {
                f.value = '';
                f.addable = addable;
                if (!_.isUndefined(value[f.name])) {
                    f.value = value[f.name];
                }
                var $fieldArea;
                $fieldArea = $('<div class="zoo-repeater-field"></div>');
                $('.zoo-repeater-item-inner', $itemWrapper).append($fieldArea);
                control.addField(f, $fieldArea, function() {
                    control.getValue();
                });

            });

            if (!_.isUndefined(value._visibility) && value._visibility === 'hidden') {
                $itemWrapper.addClass('item-hidden');
                $itemWrapper.find('input.r-visible-input').removeAttr('checked');
            } else {
                $itemWrapper.find('input.r-visible-input').prop('checked', 'checked');
            }

            if (title_only) {
                $('.zoo-repeater-item-settings, .zoo-repeater-item-toggle', $itemWrapper).hide();
            }

            $document.trigger('zoo/customizer/repeater/add', [$itemWrapper, control]);
            return $itemWrapper;
        },
        limitRepeaterItems: function() {
            var control = this;

            var addButton = $('.zoo-repeater-add-new', control.container);
            var c = $('.zoo-settings-fields .zoo-repeater-item', control.container).length;

            if (control.params.limit > 0) {
                if (c >= control.params.limit) {
                    addButton.addClass('zoo-hide');
                    if (control.params.limit_msg) {
                        if ($('.zoo-limit-item-msg', control.container).length === 0) {
                            $('<p class="zoo-limit-item-msg">' + control.params.limit_msg + '</p>').insertBefore(addButton);
                        } else {
                            $('.zoo-limit-item-msg', control.container).removeClass('zoo-hide');
                        }
                    }
                } else {
                    $('.zoo-limit-item-msg', control.container).addClass('zoo-hide');
                    addButton.removeClass('zoo-hide');
                }
            }

            if (c > 0) {
                $('.zoo-repeater-reorder', control.container).removeClass('zoo-hide');
            } else {
                $('.zoo-repeater-reorder', control.container).addClass('zoo-hide');
            }

        },
        initRepeater: function() {
            var control = this;
            control.params.limit = parseInt(control.params.limit);
            if (isNaN(control.params.limit)) {
                control.params.limit = 0;
            }

            // Sortable
            control.container.find('.zoo-settings-fields').sortable({
                handle: '.zoo-repeater-item-heading',
                containment: "parent",
                update: function(event, ui) {
                    control.getValue();
                }
            });

            // Toggle Move
            control.container.on('click', '.zoo-repeater-reorder', function(e) {
                e.preventDefault();
                $('.zoo-repeater-items', control.container).toggleClass('reorder-active');
                $('.zoo-repeater-add-new', control.container).toggleClass('disabled');
                if ($('.zoo-repeater-items', control.container).hasClass('reorder-active')) {
                    $(this).html($(this).data('done'));
                } else {
                    $(this).html($(this).data('text'));
                }
            });

            // Move Up
            control.container.on('click', '.zoo-repeater-item .zoo-up', function(e) {
                e.preventDefault();
                var i = $(this).closest('.zoo-repeater-item');
                var index = i.index();
                if (index > 0) {
                    var up = i.prev();
                    i.insertBefore(up);
                    control.getValue();
                }
            });

            // Move Down
            control.container.on('click', '.zoo-repeater-item .zoo-down', function(e) {
                e.preventDefault();
                var n = $('.zoo-repeater-items .zoo-repeater-item', control.container).length;
                var i = $(this).closest('.zoo-repeater-item');
                var index = i.index();
                if (index < n - 1) {
                    var down = i.next();
                    i.insertAfter(down);
                    control.getValue();
                }
            });


            // Add item when customizer loaded
            if (_.isArray(control.params.value)) {
                _.each(control.params.value, function(itemValue) {
                    control.addRepeaterItem(itemValue);
                });
                control.getValue(false);
            }
            control.limitRepeaterItems();

            // Toggle visibility
            control.container.on('change', '.zoo-repeater-item .r-visible-input', function(e) {
                e.preventDefault();
                var p = $(this).closest('.zoo-repeater-item');
                if ($(this).is(':checked')) {
                    p.removeClass('item-hidden');
                } else {
                    p.addClass('item-hidden');
                }
            });

            // Toggle
            if (!control.params.title_only) {
                control.container.on('click', '.zoo-repeater-item-toggle, .zoo-repeater-live-title', function(e) {
                    e.preventDefault();
                    var p = $(this).closest('.zoo-repeater-item');
                    p.toggleClass('zoo-open');
                });
            }

            // Remove
            control.container.on('click', '.zoo-remove', function(e) {
                e.preventDefault();
                var p = $(this).closest('.zoo-repeater-item');
                p.remove();
                $document.trigger('zoo/customizer/repeater/remove', [control]);
                control.getValue();
                control.limitRepeaterItems();
            });


            var defaultValue = {};
            _.each(control.params.fields, function(f, k) {
                defaultValue[f.name] = null;
                if (!_.isUndefined(f.default)) {
                    defaultValue[f.name] = f.default;
                }
            });

            // Add Item
            control.container.on('click', '.zoo-repeater-add-new', function(e) {
                e.preventDefault();
                if (!$(this).hasClass('disabled')) {
                    control.addRepeaterItem(defaultValue);
                    control.getValue();
                    control.limitRepeaterItems();
                }
            });
        }

    };

    var zoo_control = function(control) {
        control = _.extend(control, zoo_controlConstructor);
        control.init();
    };
    //---------------------------------------------------------------------------

    api.controlConstructor.zoo = api.Control.extend({
        ready: function() {
            zoo_controls_list[this.id] = this;
        }
    });

    var IconPicker = {
        pickingEl: null,
        listIcons: null,
        render: function(list_icons) {
            var that = this;
            if (!_.isUndefined(list_icons) && !_.isEmpty(list_icons)) {
                _.each(list_icons, function(icon_config, font_type) {
                    $('#zoo-sidebar-icon-type').append(' <option value="' + font_type + '">' + icon_config.name + '</option>');
                    that.addCSS(icon_config, font_type);
                    that.addIcons(icon_config, font_type);
                });
            }
        },

        addCSS: function(icon_config, font_type) {
            $('head').append("<link rel='stylesheet' id='font-icon-" + font_type + "'  href='" + icon_config.url + "' type='text/css' media='all' />")
        },

        addIcons: function(icon_config, font_type) {
            var icon_html = '<ul class="zoo-list-icons icon-' + font_type + '" data-type="' + font_type + '">';
            _.each(icon_config.icons, function(icon_class, i) {
                var class_name = '';
                if (icon_config.class_config) {
                    class_name = icon_config.class_config.replace(/__icon_name__/g, icon_class);
                } else {
                    class_name = icon_class;
                }

                icon_html += '<li title="' + icon_class + '" data-type="' + font_type + '" data-icon="' + class_name + '"><span class="icon-wrapper"><i class="' + class_name + '"></i></span></li>';

            });
            icon_html += '</ul>';

            $('#zoo-icon-browser').append(icon_html);
        },
        changeType: function() {
            $document.on('change', '#zoo-sidebar-icon-type', function() {
                var type = $(this).val();
                if (!type || type == 'all') {
                    $('#zoo-icon-browser .zoo-list-icons').show();
                } else {
                    $('#zoo-icon-browser .zoo-list-icons').hide();
                    $('#zoo-icon-browser .zoo-list-icons.icon-' + type).show();
                }
            });
        },
        show: function() {
            var controlWidth = $('#customize-controls').width();
            if (!ZooCustomizeBuilderData.isRtl) {
                $('#zoo-sidebar-icons').css('left', controlWidth).addClass('zoo-active');
            } else {
                $('#zoo-sidebar-icons').css('right', controlWidth).addClass('zoo-active');
            }

        },
        close: function() {
            if (!ZooCustomizeBuilderData.isRtl) {
                $('#zoo-sidebar-icons').css('left', -300).removeClass('zoo-active');
            } else {
                $('#zoo-sidebar-icons').css('right', -300).removeClass('zoo-active');
            }
            $('.zoo-icon-picker').removeClass('zoo-icon-picking');
            this.pickingEl = null;
        },
        autoClose: function() {
            var that = this;
            $document.on('click', function(event) {
                if (!$(event.target).closest('.zoo-icon-picker').length) {
                    if (!$(event.target).closest('#zoo-sidebar-icons').length) {
                        that.close();
                    }
                }
            });

            $('#zoo-sidebar-icons .customize-controls-icon-close').on('click', function() {
                that.close();
            });

            $document.on('keyup', function(event) {
                if (event.keyCode === 27) {
                    that.close();
                }
            });
        },
        picker: function() {
            var that = this;

            var open = function($el) {
                if (that.pickingEl) {
                    that.pickingEl.removeClass('zoo-icon-picking');
                }
                that.pickingEl = $el.closest('.zoo-icon-picker');
                that.pickingEl.addClass('zoo-picking-icon');
                that.show();
            };

            $document.on('click', '.zoo-icon-picker .zoo-pick-icon', function(e) {
                e.preventDefault();
                var button = $(this);
                if (_.isNull(that.listIcons)) {
                    that.ajaxLoad(function() {
                        open(button);
                    });
                } else {
                    open(button);
                }
            });

            $document.on('click', '#zoo-icon-browser li', function(e) {
                e.preventDefault();
                var li = $(this);
                var icon_preview = li.find('i').clone();
                var icon = li.attr("data-icon") || '';
                var type = li.attr('data-type') || '';
                $('.zoo-input-icon-type', that.pickingEl).val(type);
                $('.zoo-input-icon-name', that.pickingEl).val(icon).trigger('change');
                $('.zoo-icon-preview-icon', that.pickingEl).html(icon_preview);

                that.close();
            });

            // remove
            $document.on('click', '.zoo-icon-picker .zoo-icon-remove', function(e) {
                e.preventDefault();
                if (that.pickingEl) {
                    that.pickingEl.removeClass('zoo-icon-picking');
                }
                that.pickingEl = $(this).closest('.zoo-icon-picker');
                that.pickingEl.addClass('zoo-picking-icon');

                $('.zoo-input-icon-type', that.pickingEl).val('');
                $('.zoo-input-icon-name', that.pickingEl).val('').trigger('change');
                $('.zoo-icon-preview-icon', that.pickingEl).html('');

            });
        },

        ajaxLoad: function(cb) {
            var that = this;
            $.get(ZooCustomizeBuilderData.ajax, {
                action: 'zoo_customize__load_font_icons'
            }, function(res) {
                if (res.success) {
                    that.listIcons = res.data;
                    that.render(res.data);
                    that.changeType();
                    that.autoClose();
                    if (_.isFunction(cb)) {
                        cb();
                    }
                }
            });
        },
        init: function() {
            var that = this;
            that.ajaxLoad();
            that.picker();
            // Search icon
            $document.on('keyup', '#zoo-icon-search', function(e) {
                var v = $(this).val();
                v = v.trim();
                if (v) {
                    $("#zoo-icon-browser li").hide();
                    $("#zoo-icon-browser li[data-icon*='" + v + "']").show();
                } else {
                    $("#zoo-icon-browser li").show();
                }
            });
        }
    };

    var FontSelector = {
        fonts: null,
        optionHtml: '',
        $el: null,
        values: {},
        config: {}, // Config to disable fields
        container: null,
        fields: {},
        load: function() {
            if (!FontSelector.fonts) {
                $.get(ZooCustomizeBuilderData.ajax, {
                    action: 'zoo_customize_load_fonts'
                }).done(function(res) {
                    if (res.success) {
                        FontSelector.fonts = res.data;
                        $document.trigger('typoFontsLoaded', [FontSelector.fonts]);
                    }
                });
            }
        },
        toSelectOptions: function(options, v, type) {
            var html = '';
            if (_.isUndefined(v)) {
                v = '';
            }

            if (type === 'google') {

                _.each(options, function(value) {
                    var selected = '';
                    if (value === v) {
                        selected = ' selected="selected" ';
                    }
                    html += '<option' + selected + ' value="' + value + '">' + value + '</option>';
                });
            } else {

                _.each(ZooCustomizeBuilderData.list_font_weight, function(value, key) {
                    var selected = '';
                    if (value === v) {
                        selected = ' selected="selected" ';
                    }
                    html += '<option' + selected + ' value="' + key + '">' + value + '</option>';
                });

                var value, selected, i;

                for (i = 1; i <= 9; i++) {
                    value = i * 100;
                    selected = '';
                    if (value === v) {
                        selected = ' selected="selected" ';
                    }
                    html += '<option' + selected + ' value="' + value + '">' + value + '</option>';
                }
            }

            return html;
        },
        toCheckboxes: function(options, v) {
            var html = '<div class="list-subsets">';
            if (!_.isObject(v)) {
                v = {};
            }
            _.each(options, function(value) {
                var checked = '';
                if (!_.isUndefined(v[value])) {
                    checked = ' checked="checked" ';
                }
                html += '<p><label><input ' + checked + 'type="checkbox" class="zoo-typo-input change-by-js" data-name="languages" name="_n-' + (new Date().getTime()) + '" value="' + value + '"> ' + value + '</label></p>';
            });
            html += '</div>';
            return html;
        },
        ready: function() {
            var that = this;
            zooField.devices = _.clone(zooField.allDevices);
            if (!_.isObject(that.values)) {
                that.values = {};
            }

            that.fields = {};

            //ZooCustomizeBuilderData.typo_fields
            if (!_.isEmpty(that.config)) {
                _.each(ZooCustomizeBuilderData.typo_fields, function(_f, _key) {
                    var show = true;
                    if (!_.isUndefined(that.config[_f.name])) {
                        if (that.config[_f.name] === false) {
                            show = false;
                        }
                    }

                    if (show) {
                        that.fields[_f.name] = _f;
                    }

                });

            } else {
                that.fields = ZooCustomizeBuilderData.typo_fields;
            }

            $('.zoo-modal-settings--fields', that.container).append('<input type="hidden" class="zoo-font-type">');
            zooField.addFields(that.fields, that.values, $('.zoo-modal-settings--fields', that.container), function() {
                that.get();
            });

            $('input, select, textarea', $('.zoo-modal-settings--fields')).removeClass('zoo-input').addClass('zoo-typo-input change-by-js');
            that.optionHtml += '<option value="">' + ZooCustomizeBuilderData.theme_default + '</option>';
            _.each(that.fonts, function(group, type) {
                that.optionHtml += '<optgroup label="' + group.title + '">';
                _.each(group.fonts, function(font, font_name) {
                    that.optionHtml += '<option value="' + font_name + '">' + font_name + '</option>';
                });
                that.optionHtml += '</optgroup>';
            });

            $('.zoo-typo-input[data-name="font"]', that.container).html(that.optionHtml);

            if (!_.isUndefined(that.values['font']) && _.isString(that.values['font'])) {
                $('.zoo-typo-input[data-name="font"] option[value="' + that.values['font'] + '"]', that.container).attr('selected', 'selected');
            }

            that.container.on('change init-change', '.zoo-typo-input[data-name="font"]', function() {
                var font = $(this).val();
                that.setUpFont(font);
            });

            $('.zoo-typo-input[data-name="font"]', that.container).trigger('init-change');

            that.container.on('change data-change', 'input, select', function() {
                that.get();
            });

        },

        setUpFont: function(font) {
            var that = this;
            var font_settings, variants, subsets, type;

            if (_.isEmpty(font)) {
                type = 'normal';
            }

            if (_.isString(font)) {
                if (!_.isUndefined(that.fonts.google.fonts[font])) {
                    type = 'google';
                } else {
                    type = 'normal';
                }
                font_settings = that.fonts.google.fonts[font];
            } else {
                font_settings = that.fonts.google.fonts[font.font];
            }

            if (!_.isUndefined(font_settings) && !_.isEmpty(font_settings)) {
                variants = font_settings.variants;
                subsets = font_settings.subsets;
            }

            $('.zoo-typo-input[data-name="font_weight"]', that.container).html(that.toSelectOptions(variants, _.isObject(that.values) ? that.values.font_weight : '', type));
            $('.zoo-font-type', that.container).val(type);

            if (type == 'normal') {
                $('.zoo-group-field[data-field-name="languages"]', that.container).addClass('zoo-hide').find('.zoo-customize-control-settings-inner').html('');
            } else {
                $('.zoo-group-field[data-field-name="languages"]', that.container).removeClass('zoo-hide');
                $('.zoo-group-field[data-field-name="languages"]', that.container).removeClass('zoo-hide').find('.zoo-customize-control-settings-inner').html(that.toCheckboxes(subsets, _.isObject(that.values) ? that.values.languages : ''));
            }

        },

        open: function() {
            //this.$el = $el;
            var that = this;
            var $el = that.$el;

            var status = $el.attr('data-opening') || false;
            if (status !== 'opening') {
                $el.attr('data-opening', 'opening');
                that.values = $('.zoo-typography-input', that.$el).val();
                that.values = JSON.parse(that.values);
                $el.addClass('zoo-modal--inside');
                if (!$('.zoo-modal-settings', $el).length) {
                    var $wrap = $($('#tmpl-zoo-modal-settings').html());
                    that.container = $wrap;
                    that.container.hide();
                    this.$el.append($wrap);
                    that.ready();
                } else {
                    that.container = $('.zoo-modal-settings', $el);
                    that.container.hide();
                }
                that.container.slideDown(300, function() {
                    that.$el.addClass('modal--opening');
                    $('.action--reset', that.$el).show();
                });

            } else {
                $('.zoo-modal-settings', $el).slideUp(300, function() {
                    $el.attr('data-opening', '');
                    $el.removeClass('modal--opening');
                    $('.action--reset', $el).hide();
                });

            }
        },

        reset: function() {
            //this.$el = $el;
            var that = this;
            var $el = that.$el;

            $('.zoo-modal-settings', $el).remove();
            that.values = $('.zoo-typography-input', that.$el).attr('data-default') || '{}';
            try {
                that.values = JSON.parse(that.values);
            } catch (e) {}

            $el.addClass('zoo-modal--inside');
            if (!$('.zoo-modal-settings', $el).length) {
                var $wrap = $($('#tmpl-zoo-modal-settings').html());
                that.container = $wrap;
                this.$el.append($wrap);
                that.ready();
            } else {
                that.container = $('.zoo-modal-settings', $el);
            }
            that.get();
        },

        get: function() {
            var data = {};
            var that = this;
            _.each(this.fields, function(f) {
                if (f.name === 'languages') {
                    f.type = 'checkboxes';
                }
                data[f.name] = zooField.getValue(f, $('.zoo-group-field[data-field-name="' + f.name + '"]', that.container));
            });


            data.variant = {};
            if (!_.isUndefined(that.fonts.google.fonts[data.font])) {
                data.variant = that.fonts.google.fonts[data.font].variants;
            }

            data.font_type = $('.zoo-font-type', that.container).val();
            $('.zoo-typography-input', this.$el).val(JSON.stringify(data)).trigger('change');
            return data;
        },

        init: function() {
            this.load();
        }
    };

    FontSelector.load();
    var intTypoControls = {};
    var intTypos = function() {
        $document.on('click', '.customize-control-zoo-typography .action--edit, .customize-control-zoo-typography .action--reset', function() {
            var controlID = $(this).attr('data-control') || '';
            if (_.isUndefined(intTypoControls[controlID])) {
                var c = api.control(controlID);
                if (controlID && !_.isUndefined(c)) {
                    var m = _.clone(FontSelector);
                    m.config = c.params.fields;
                    m.$el = $(this).closest('.customize-control-zoo-typography').eq(0);
                    intTypoControls[controlID] = m;
                }
            }

            if (!_.isUndefined(intTypoControls[controlID])) {
                if ($(this).hasClass('action--reset')) {
                    intTypoControls[controlID].reset();
                } else {
                    intTypoControls[controlID].open();
                }
            }

        });
    };

    //---------------------------------------------------------------------------
    var zooModal = {
        tabs: {
            normal: 'Normal',
            hover: 'Hover'
        },
        config: {},
        $el: null,
        container: null,
        controlID: '',
        addFields: function(values) {
            var that = this;
            if (!_.isObject(that.values)) {
                that.values = {};
            }
            that.values = _.defaults(that.values, {});
            var fieldsArea = $('.zoo-modal-settings--fields', that.container);
            fieldsArea.html('');

            that.config = _.defaults(that.config, {
                tabs: {}
            });

            var tabsHTML = $('<div class="modal--tabs"></div>');
            var c = 0;
            _.each(that.config.tabs, function(label, key) {
                if (label && _.isObject(that.config[key + '_fields'])) {
                    c++;
                    tabsHTML.append('<div><span data-tab="' + key + '" class="modal--tab modal-tab--' + key + '">' + label + '</span></div>');
                }
            });

            fieldsArea.append(tabsHTML);
            if (c <= 1) {
                tabsHTML.addClass('zoo-hide');
            }
            zooField.devices = ZooCustomizeBuilderData.devices;
            _.each(that.config.tabs, function(label, key) {
                if (_.isObject(that.config[key + '_fields']) && !_.isEmpty(key + '_fields')) {
                    var content = $('<div class="modal-tab-content modal-tab--' + key + '"></div>');
                    fieldsArea.append(content);
                    zooField.addFields(that.config[key + '_fields'], that.values[key], content, function() {
                        that.get(_.clone(that.config));
                    });
                    var fv;
                    if (_.isUndefined(that.values[key]) || _.isEmpty(that.values[key])) {
                        fv = {};
                        _.each(that.config[key + '_fields'], function(f) {
                            fv[f.name] = _.isUndefined(f.default) ? null : f.default;
                        });
                    } else {
                        fv = that.values[key];
                    }
                    zooField.initConditional(content, fv);
                }
            });

            $('input, select, textarea', that.container).removeClass('zoo-input').addClass('zoo-modal-input change-by-js');
            fieldsArea.on('change data-change', 'input, select, textarea', function() {
                that.get(_.clone(that.config));
            });

            that.container.on('click', '.modal--tab', function() {
                var id = $(this).attr('data-tab') || '';
                $('.modal--tabs .modal--tab', that.container).removeClass('tab--active');
                $(this).addClass('tab--active');
                $('.modal-tab-content', that.container).removeClass('tab--active');
                $('.modal-tab-content.modal-tab--' + id, that.container).addClass('tab--active');
            });
            $('.modal--tabs .modal--tab', that.container).eq(0).trigger('click');

            this.container.slideUp(0);
        },

        close: function() {
            var that = this;
            that.container.slideUp(300, function() {
                that.$el.removeClass('modal--opening');
                that.$el.attr('data-opening', '');
                $('.action--reset', that.$el).hide();
            });
        },

        reset: function() {
            var that = this;
            $('.zoo-modal-settings', that.$el).remove();
            try {
                var _default = api.control(that.controlID).params.default;
                that.values = _default;
            } catch (e) {
                that.values = {};
            }
            if (!$('.zoo-modal-settings', that.$el).length) {
                var $wrap = $($('#tmpl-zoo-modal-settings').html());
                that.container = $wrap;
                this.$el.append($wrap);
                that.addFields();
            } else {
                that.container = $('.zoo-modal-settings', that.$el);
            }

            that.$el.addClass('zoo-modal--inside');
            that.$el.addClass('modal--opening');
            that.container.show(0);
            $('.zoo-hidden-modal-input', that.$el).val(JSON.stringify(that.values)).trigger('change');

        },

        get: function(config) {
            var data = {};
            var that = this;
            that.config = config;
            _.each(that.config.tabs, function(label, key) {
                var subdata = {};
                var content = $('.modal-tab-content.modal-tab--' + key, that.container);
                if (_.isObject(that.config[key + '_fields'])) {
                    _.each(that.config[key + '_fields'], function(f) {
                        subdata[f.name] = zooField.getValue(f, $('.zoo-group-field[data-field-name="' + f.name + '"]', content));
                    });
                }
                data[key] = subdata;
                zooField.initConditional(content, subdata);
            });
            $('.zoo-hidden-modal-input', this.$el).val(JSON.stringify(data)).trigger('change');
            return data;
        },

        open: function() {
            var that = this;
            var status = that.$el.attr('data-opening') || false;
            if (status !== 'opening') {
                that.$el.attr('data-opening', 'opening');
                that.values = $('.zoo-hidden-modal-input', that.$el).val();
                try {
                    that.values = JSON.parse(that.values);
                } catch (e) {}
                that.$el.addClass('zoo-modal--inside');
                if (!$('.zoo-modal-settings', that.$el).length) {
                    var $wrap = $($('#tmpl-zoo-modal-settings').html());
                    $wrap.hide();
                    that.container = $wrap;
                    that.$el.append($wrap);
                    that.addFields();
                } else {
                    that.container = $('.zoo-modal-settings', that.$el);
                }

                this.container.slideDown(300);
                this.$el.addClass('modal--opening');
                $('.action--reset', this.$el).show();

            } else {
                this.container.slideUp(300, function() {
                    that.$el.attr('data-opening', '');
                    $('.zoo-modal-settings', that.$el).hide();
                    that.$el.removeClass('modal--opening');
                    $('.action--reset', that.$el).hide();
                });

            }
        },
    };

    var initModalControls = {};
    var initModal = function() {
        $document.on('click', '.customize-control-zoo-modal .action--edit, .customize-control-zoo-modal .action--reset, .customize-control-zoo-modal .zoo-control-field-header', function(e) {
            e.preventDefault();
            var controlID = $(this).attr('data-control') || '';
            if (_.isUndefined(initModalControls[controlID])) {
                var c = api.control(controlID);
                if (controlID && !_.isUndefined(c)) {
                    var m = _.clone(zooModal);
                    m.config = c.params.fields;
                    m.$el = $(this).closest('.customize-control-zoo-modal').eq(0);
                    m.controlID = controlID;
                    initModalControls[controlID] = m;
                }
            }

            if (!_.isUndefined(initModalControls[controlID])) {
                if ($(this).hasClass('action--reset')) {
                    initModalControls[controlID].reset();
                } else {
                    initModalControls[controlID].open();
                }
            }
        });
    };

    //---------------------------------------------------------------------------
    var zooStyling = {
        tabs: {
            normal: 'Normal',
            hover: 'Hover'
        },
        fields: {},
        normal_fields: {},
        hover_fields: {},
        controlID: '',
        $el: '',
        contailner: '',
        setupFields: function(fields, list) {
            var newfs;
            var i;
            var newList = [];
            if (fields === -1) {
                newList = list;
            } else if (fields === false) {
                newList = null;
            } else {
                if (_.isObject(fields)) {
                    newfs = {};
                    i = 0;
                    _.each(list, function(f) {
                        if (_.isUndefined(fields[f.name]) || fields[f.name]) {
                            newfs[i] = f;
                            i++;
                        }

                    });

                    newList = newfs;
                }
            }
            return newList;
        },
        setupConfig: function(tabs, normal_fields, hover_fields) {
            var that = this;
            that.tabs = {};
            that.normal_fields = {};
            that.hover_fields = {};

            that.tabs = _.clone(ZooCustomizeBuilderData.styling_config.tabs);
            if (tabs === false) {
                that.tabs['hover'] = false;
            } else if (_.isObject(tabs)) {
                that.tabs = tabs;
            }

            that.normal_fields = that.setupFields(normal_fields, ZooCustomizeBuilderData.styling_config.normal_fields);
            that.hover_fields = that.setupFields(hover_fields, ZooCustomizeBuilderData.styling_config.hover_fields);

        },
        addFields: function(values) {
            var that = this;
            if (!_.isObject(that.values)) {
                that.values = {};
            }
            that.values = _.defaults(that.values, {
                hover: {},
                normal: {}
            });
            var fieldsArea = $('.zoo-modal-settings--fields', that.container);
            fieldsArea.html('');

            var tabsHTML = $('<div class="modal--tabs"></div>');
            var c = 0;
            _.each(that.tabs, function(label, key) {
                if (label && !_.isEmpty(that[key + '_fields'])) {
                    c++;
                    tabsHTML.append('<div><span data-tab="' + key + '" class="modal--tab modal-tab--' + key + '">' + label + '</span></div>');
                }
            });

            fieldsArea.append(tabsHTML);
            if (c <= 1) {
                tabsHTML.addClass('zoo-hide');
            }
            zooField.devices = ZooCustomizeBuilderData.devices;
            _.each(that.tabs, function(label, key) {
                if (_.isObject(that[key + '_fields']) && !_.isEmpty(key + '_fields')) {

                    var content = $('<div class="modal-tab-content modal-tab--' + key + '"></div>');
                    fieldsArea.append(content);
                    zooField.addFields(that[key + '_fields'], that.values[key], content, function() {
                        that.get();
                    });
                    zooField.initConditional(content, that.values[key]);

                }
            });

            $('input, select, textarea', that.container).removeClass('zoo-input').addClass('zoo-modal-input change-by-js');

            fieldsArea.on('change data-change', 'input, select, textarea', function() {
                that.get();
            });

            that.container.on('click', '.modal--tab', function() {
                var id = $(this).attr('data-tab') || '';
                $('.modal--tabs .modal--tab', that.container).removeClass('tab--active');
                $(this).addClass('tab--active');
                $('.modal-tab-content', that.container).removeClass('tab--active');
                $('.modal-tab-content.modal-tab--' + id, that.container).addClass('tab--active');
            });
            $('.modal--tabs .modal--tab', that.container).eq(0).trigger('click');

            this.container.slideUp(0);

        },

        close: function() {
            var that = this;
            that.container.slideUp(300, function() {
                that.$el.removeClass('modal--opening');
                that.$el.attr('data-opening', '');
                $('.action--reset', that.$el).hide();
            });
        },

        reset: function() {
            var that = this;

            $('.zoo-modal-settings', that.$el).remove();
            try {
                var _default = api.control(that.controlID).params.default;
                that.values = _default;
            } catch (e) {
                that.values = {};
            }
            if (!$('.zoo-modal-settings', that.$el).length) {
                var $wrap = $($('#tmpl-zoo-modal-settings').html());
                that.container = $wrap;
                that.$el.append($wrap);
                that.addFields();
            } else {
                that.container = $('.zoo-modal-settings', that.$el);
            }

            that.$el.addClass('zoo-modal--inside');
            that.$el.addClass('modal--opening');
            that.container.show(0);
            $('.zoo-hidden-modal-input', that.$el).val(JSON.stringify(that.values)).trigger('change');

        },

        get: function() {
            var data = {};
            var that = this;
            _.each(that.tabs, function(label, key) {
                var subdata = {};
                var content = $('.modal-tab-content.modal-tab--' + key, that.container);
                if (_.isObject(that[key + '_fields'])) {
                    _.each(that[key + '_fields'], function(f) {
                        subdata[f.name] = zooField.getValue(f, $('.zoo-group-field[data-field-name="' + f.name + '"]', content));
                    });
                }
                data[key] = subdata;
                zooField.initConditional(content, subdata);
            });

            $('.zoo-hidden-modal-input', this.$el).val(JSON.stringify(data)).trigger('change');
            return data;
        },

        open: function() {
            var that = this;
            var status = that.$el.attr('data-opening') || false;
            if (status !== 'opening') {
                that.$el.attr('data-opening', 'opening');

                that.values = $('.zoo-hidden-modal-input', that.$el).val();
                try {
                    that.values = JSON.parse(that.values);
                } catch (e) {}
                that.$el.addClass('zoo-modal--inside');
                if (!$('.zoo-modal-settings', that.$el).length) {
                    var $wrap = $($('#tmpl-zoo-modal-settings').html());
                    $wrap.hide();
                    that.container = $wrap;
                    that.$el.append($wrap);
                    that.addFields();
                } else {
                    that.container = $('.zoo-modal-settings', that.$el);
                }

                this.container.slideDown(300);
                that.$el.addClass('modal--opening');
                $('.action--reset', that.$el).show();

            } else {
                that.container.slideUp(300, function() {
                    that.$el.attr('data-opening', '');
                    $('.zoo-modal-settings', that.$el).hide();
                    that.$el.removeClass('modal--opening');
                    $('.action--reset', that.$el).hide();
                });

            }
        },
    };

    var initStylingControls = {};
    var initStyling = function() {
        $document.on('click', '.customize-control-zoo-styling .action--edit, .customize-control-zoo-styling .action--reset', function(e) {
            e.preventDefault();
            var controlID = $(this).attr('data-control') || '';
            if (_.isUndefined(initStylingControls[controlID])) {
                var c = api.control(controlID);
                var s = _.clone(zooStyling);
                var tabs = null,
                    normal_fields = -1,
                    hover_fields = -1;
                if (controlID && !_.isUndefined(c)) {
                    if (!_.isUndefined(c.params.fields) && _.isObject(c.params.fields)) {
                        if (!_.isUndefined(c.params.fields.tabs)) {
                            tabs = c.params.fields.tabs;
                        }
                        if (!_.isUndefined(c.params.fields.normal_fields)) {
                            normal_fields = c.params.fields.normal_fields;
                        }
                        if (!_.isUndefined(c.params.fields.hover_fields)) {
                            hover_fields = c.params.fields.hover_fields;
                        }
                    }
                }
                s.$el = $(this).closest('.customize-control-zoo-styling').eq(0);
                s.setupConfig(tabs, normal_fields, hover_fields);
                s.controlID = controlID;
                initStylingControls[controlID] = s;
            }

            if (!_.isUndefined(initStylingControls[controlID])) {
                if ($(this).hasClass('action--reset')) {
                    initStylingControls[controlID].reset();
                } else {
                    initStylingControls[controlID].open();
                }
            }
        });
    };

    //---------------------------------------------------------------------------

    api.bind('ready', function(e, b) {

        if($('.zoo-range-slider')[0]){
            $('.zoo-range-slider').wrap('<div class="wrap-zoo-range-slider"></div>');
            $('.wrap-zoo-range-slider').prepend('<div class="zoo-range-slider-block zoo-input-slider"></div>');
            $('.wrap-zoo-range-slider').each(function () {

                var current_val=$(this).find('.zoo-range-slider').val();
                var input=$(this).find('.zoo-range-slider');
                var min=input.attr('min');
                var max=input.attr('max');
                var slider=$(this).find('.zoo-range-slider-block');
                slider.slider({
                    range: "min",
                    value: parseInt(current_val),
                    step: 1,
                    min: parseInt(min),
                    max: parseInt(max),
                    slide: function(event, ui) {
                        input.val(ui.value).trigger('data-change');
                        input.trigger('change');
                    }
                });

                input.on('change', function() {
                    slider.slider("value", $(this).val());
                });
            });
        }

        $document.on('zoo/customizer/device/change', function(e, device) {
            $('.zoo-device-select a').removeClass('zoo-active');
            if (device != 'mobile') {
                $('.zoo-device-mobile').addClass('zoo-hide');
                $('.zoo-device-general').removeClass('zoo-hide');
                $('.zoo-tab-device-general').addClass('zoo-active');
            } else {
                $('.zoo-device-general').addClass('zoo-hide');
                $('.zoo-device-mobile').removeClass('zoo-hide');
                $('.zoo-tab-device-mobile').addClass('zoo-active');
            }
        });

        $document.on('click', '.zoo-tab-device-mobile', function(e) {
            e.preventDefault();
            $document.trigger('zoo/customizer/device/change', ['mobile']);
        });

        $document.on('click', '.zoo-tab-device-general', function(e) {
            e.preventDefault();
            $document.trigger('zoo/customizer/device/change', ['general']);
        });

        $('.accordion-section').each(function() {
            var s = $(this);
            var t = $('.zoo-device-select', s).first();
            $('.customize-section-title', s).append(t);
        });


        // Devices Switcher
        $document.on('click', '.zoo-devices button', function(e) {
            e.preventDefault();
            var device = $(this).attr('data-device') || '';
            $('#customize-footer-actions .devices button[data-device="' + device + '"]').trigger('click');
        });

        // Devices Switcher
        $document.on('change', '.zoo-customize-control input:checkbox', function(e) {
            if ($(this).is(':checked')) {
                $(this).parent().addClass('zoo-checked');
            } else {
                $(this).parent().removeClass('zoo-checked');
            }
        });

        // Setup conditional
        var ControlConditional = function(decodeValue) {
            if (_.isUndefined(decodeValue)) {
                decodeValue = false;
            }
            var allValues = api.get();
            _.each(allValues, function(value, id) {
                var control = api.control(id);
                if (!_.isUndefined(control)) {
                    if (control.params.type == 'zoo') {
                        if (!_.isEmpty(control.params.required)) {
                            var check = false;
                            check = control.multiple_compare(control.params.required, allValues, decodeValue);
                            if (!check) {
                                control.container.addClass('zoo-hide');
                            } else {
                                control.container.removeClass('zoo-hide');
                            }
                        }
                    }
                }

            });
        };

        $document.ready(function() {
            _.each(zoo_controls_list, function(c, k) {
                new zoo_control(c);
            });

            ControlConditional(false);
            $document.on('zoo/customizer/value_changed', function() {
                ControlConditional(true);
            });

            IconPicker.init();
            initStyling();
            initModal();
            intTypos();

            $document.on('typoFontsLoaded', function(e, data) {
                var editBtns = $('.zoo-actions .action--edit');
                if (editBtns.length) {
                    editBtns.each(function(i) {
                        var controlID = $(this).attr('data-control') || '',
                            c = api.control(controlID);
                        if (_.isUndefined(intTypoControls[controlID])) {
                            if (c.params.setting_type === 'typography') {
                                var m = _.clone(FontSelector);
                                m.config = c.params.fields;
                                m.$el = c.container;
                                if (!m.fonts) m.fonts = data;
                                intTypoControls[controlID] = m;
                                if (c.container.hasClass('no-hide'))
                                    intTypoControls[controlID].open();
                            }
                            if (c.params.setting_type === 'styling') {
                                var s = _.clone(zooStyling);
                                var tabs = null,
                                    normal_fields = -1,
                                    hover_fields = -1;
                                if (!_.isUndefined(c.params.fields) && _.isObject(c.params.fields)) {
                                    if (!_.isUndefined(c.params.fields.tabs))
                                        tabs = c.params.fields.tabs;
                                    if (!_.isUndefined(c.params.fields.normal_fields))
                                        normal_fields = c.params.fields.normal_fields;
                                    if (!_.isUndefined(c.params.fields.hover_fields))
                                        hover_fields = c.params.fields.hover_fields;
                                }
                                s.$el = c.container;
                                s.setupConfig(tabs, normal_fields, hover_fields);
                                s.controlID = controlID;
                                initStylingControls[controlID] = s;
                                if (c.container.hasClass('no-hide'))
                                    initStylingControls[controlID].open();
                            }
                        }
                    });
                }
            });
        });

        // Add reset button to sections
        api.section.each(function(section) {
            if (section.params.type == 'section' || section.params.type == 'zoo_section') {
                section.container.find('.customize-section-description-container .customize-section-title').append('<button data-section="' + section.id + '" type="button" title="' + ZooCustomizeBuilderData.reset + '" class="customize--reset-section" aria-expanded="false"><span class="screen-reader-text">' + ZooCustomizeBuilderData.reset + '</span></button>');
            }
        });

        // Remove checked align
        $document.on('dblclick', '.zoo-text-align label', function(e) {
            var input = $(this).find('input[type="radio"]');
            if (input.length) {
                if (input.is(':checked')) {
                    input.removeAttr('checked');
                    input.trigger('data-change');
                }
            }
        });

        $document.on('click', '.customize--reset-section', function(e) {
            e.preventDefault();
            if ($(this).hasClass('loading')) {
                return;
            }

            if (!confirm(ZooCustomizeBuilderData.confirm_reset)) {
                return;
            }

            $(this).addClass('loading');
            var section = $(this).attr('data-section') || '';
            var urlParser = _.clone(window.location);

            if (section) {
                var setting_keys = [];
                var controls = api.section(section).controls();
                _.each(controls, function(c, index) {
                    wpcustomize(c.id).set('');
                    setting_keys[index] = c.id;
                });

                $.post(ajaxurl, {
                    action: 'zoo__reset_section',
                    section: section,
                    settings: setting_keys
                }, function() {
                    $(window).off('beforeunload.customize-confirm');
                    top.location.href = urlParser.origin + urlParser.pathname + '?autofocus[section]=' + section + '&url=' + encodeURIComponent(api.previewer.previewUrl.get());
                });

            }
        });


    }); // end customize ready

    var CustomizeBuilder = function(options, id) {

        var Builder = {
            id: id,
            controlId: '',
            cols: 12,
            cellHeight: 45,
            items: [],
            container: null,
            ready: false,
            devices: {
                'desktop': 'Desktop',
                'mobile': 'Mobile/Tablet'
            },
            activePanel: 'desktop',
            panels: {},
            activeRow: 'main',
            draggingItem: null,
            getTemplate: _.memoize(function() {
                var control = this;
                var compiled,
                    options = {
                        evaluate: /<#([\s\S]+?)#>/g,
                        interpolate: /\{\{\{([\s\S]+?)\}\}\}/g,
                        escape: /\{\{([^\}]+?)\}\}(?!\})/g,
                        variable: 'data'
                    };

                return function(data, id, data_variable_name) {
                    if (_.isUndefined(id)) {
                        id = 'tmpl-customize-control-' + control.type;
                    }
                    if (!_.isUndefined(data_variable_name) && _.isString(data_variable_name)) {
                        options.variable = data_variable_name;
                    } else {
                        options.variable = 'data';
                    }
                    compiled = _.template($('#' + id).html(), null, options);
                    return compiled(data);
                };

            }),
            drag_drop: function() {
                var that = this;

                $('.zoo-device-panel', that.container).each(function() {
                    var panel = $(this);
                    var device = panel.data('device');
                    var sortable_ids = [];
                    that.panels[device] = {};
                    $('.zoo-customize-builder-items', panel).each(function(index) {
                        var data_name = $(this).attr('data-id') || '';
                        var id;
                        if (!data_name) {
                            id = '_sid_' + device + index;
                        } else {
                            id = '_sid_' + device + '-' + data_name;
                        }
                        $(this).attr('id', id);
                        sortable_ids[index] = '#' + id;
                    });
                    $('.grid-stack, .zoo-customize-builder-sidebar-items', panel).each(function() {
                        var _id = $(this).attr('data-id') || '';
                        that.panels[device][_id] = $(this);
                        $(this).droppable({
                            out: function(event, ui) {

                            },
                            over: function(event, ui) {

                            },
                            drop: function(event, ui) {
                                var $wrapper = $(this);
                                that.gridster($wrapper, ui, event);
                                that.save();
                            }
                        });

                    });

                    $('.zoo-available-items .grid-stack-item', panel).draggable({
                        revert: 'invalid',
                        connectToSortable: false,
                        start: function(event, ui) {
                            $('body').addClass('builder-item-moving');
                            $('.zoo-customize-builder-items', panel).css('z-index', '');
                            ui.helper.parent().css('z-index', 9999);
                        },
                        stop: function(event, ui) {
                            $('body').removeClass('builder-item-moving');
                            $('.zoo-customize-builder-items', panel).css('z-index', '');
                            ui.helper.parent().css('z-index', '');
                        }

                    });

                    $('.zoo-available-items .grid-stack-item', panel).resizable({
                        handles: 'w, e',
                        stop: function(event, ui) {
                            that.setGridWidth(ui.element.parent(), ui);
                            that.save();
                        }
                    });


                });
            },
            sortGrid: function($wrapper) {
                $(".grid-stack-item", $wrapper).each(function() {
                    var el = $(this);
                    var x = el.attr('data-gs-x') || 0;
                    x = parseInt(x);
                    var next = el.next();
                    if (next.length > 0) {
                        var nx = next.attr('data-gs-x') || 0;
                        nx = parseInt(nx);
                        if (x > nx) {
                            el.insertAfter(next);
                        }
                    }
                });

            },
            getX: function($item) {
                var x = $item.attr('data-gs-x') || 0;
                return parseInt(x);
            },
            getW: function($item, df) {
                if (_.isUndefined(df)) {
                    df = false;
                }
                var w;
                if (df) {
                    w = $item.attr('data-df-width') || 1;
                } else {
                    w = $item.attr('data-gs-width') || 1;
                }
                return parseInt(w);
            },
            gridGetItemInfo: function($item, flag, $wrapper) {
                var that = this;
                var x = that.getX($item);
                var w = that.getW($item);
                var slot_before = 0;
                var slot_after = 0;
                var i;

                var br = false;
                // Get empty slots before
                i = x - 1;
                while (i >= 0 && !br) {
                    if (flag[i] === 0) {
                        slot_before++;
                    } else {
                        br = true;
                    }
                    i--;
                }

                // Get empty slots after
                br = false;
                i = x + w;
                while (i < that.cols && !br) {
                    if (flag[i] === 0) {
                        slot_after++;
                    } else {
                        br = true;
                    }
                    i++;
                }

                return {
                    flag: flag,
                    x: x,
                    w: w,
                    item: $item,
                    before: slot_before, // empty before
                    after: slot_after, // empty after
                    id: $item.attr('data-id') || '',
                    wrapper: $wrapper
                }
            },
            updateItemsPositions: function(flag) {
                var maxCol = this.cols;
                for (var i = 0; i <= maxCol; i++) {
                    if (typeof flag[i] === 'object' || typeof flag[i] === 'function') {
                        flag[i].attr('data-gs-x', i);
                    }
                }
            },
            gridster: function($wrapper, ui, event) {
                if ($wrapper.context.id == '_sid_desktop-sidebar' || $wrapper.context.id == '_sid_mobile-sidebar') {
                    this.cols = 64;
                }
                var flag = [],
                    backupFlag = [],
                    that = this;
                var maxCol = this.cols;

                var addItemToFlag = function(node) {
                    var x = node.x,
                        w = node.w;
                    var el = node.el;

                    for (var i = x; i < x + w; i++) {
                        if (i === x) {
                            flag[i] = el; // mean start item item
                        } else {
                            flag[i] = 1;
                        }
                    }
                };

                var removeNode = function(node) {
                    var x = node.x,
                        w = node.w;
                    var el = node.el;
                    for (var i = x; i < x + w; i++) {
                        flag[i] = 0;
                    }
                };

                var getEmptySlots = function() {
                    var emptySlots = 0;
                    for (var i = 0; i < maxCol; i++) {
                        if (flag[i] === 0) {
                            emptySlots++;
                        }
                    }

                    return emptySlots;
                };

                var getRightEmptySlotFromX = function(x, stopWhenNotEmpty) {
                    var emptySlots = 0;
                    for (var i = x; i < maxCol; i++) {
                        if (flag[i] === 0) {
                            emptySlots++;
                        } else {
                            if (stopWhenNotEmpty) {
                                return emptySlots;
                            }
                        }
                    }
                    return emptySlots;
                };

                var getLeftEmptySlotFromX = function(x, stopWhenNotEmpty) {
                    var emptySlots = 0;
                    if (typeof stopWhenNotEmpty === "undefined") {
                        stopWhenNotEmpty = false;
                    }
                    for (var i = x; i >= 0; i--) {
                        if (flag[i] === 0) {
                            emptySlots++;
                        } else {
                            if (stopWhenNotEmpty) {
                                return emptySlots;
                            }
                        }
                    }
                    return emptySlots;
                };

                var isEmptyX = function(x) {
                    if (flag[x] === 0) {
                        return true;
                    }
                    return false;
                };

                var checkEnoughSpaceFromX = function(x, w) {
                    var check = true;
                    var i = x;
                    var j;
                    while (i < x + w && check) {
                        if (flag[i] !== 0) {
                            return false;
                        }
                        i++;
                    }
                    return check;
                };

                var getPrevBlock = function(x) {
                    if (x < 0) {
                        return {
                            x: -1,
                            w: 1
                        }
                    }

                    var i, _x = -1,
                        _xw, found;

                    if (flag[x] <= 1) {
                        i = x;
                        found = false;
                        while (i >= 0 && !found) {
                            if (flag[i] !== 1 && flag[i] !== 0) {
                                _x = i;
                                found = true;
                            }
                            i--;
                        }
                    } else {
                        _x = x;
                    }

                    i = _x + 1;
                    _xw = _x;

                    while (flag[i] === 1) {
                        _xw++;
                        i++;
                    }
                    return {
                        x: _x,
                        w: (_xw + 1) - _x
                    }
                };

                var getNextBlock = function(x) {
                    var i, _x = -1,
                        _xw, found;

                    if (flag[x] < maxCol) {
                        i = x;
                        found = false;
                        while (i < maxCol && !found) {
                            if (flag[i] !== 1 && flag[i] !== 0) {
                                _x = i;
                                found = true;
                            }
                            i++;
                        }
                    } else {
                        _x = x;
                    }
                    // Calculate the width of this item
                    i = _x + 1;
                    _xw = _x; // the min width is 1

                    while (flag[i] === 1) {
                        _xw++;
                        i++;
                    }
                    return {
                        x: _x,
                        w: (_xw + 1) - _x
                    }
                };


                /**
                 *  Move all Items form x to left
                 * @param x
                 * @param number position left need to move
                 * @returns {*}
                 */
                var moveAllItemsFromXToLeft = function(x, number) {
                    var backupFlag = flag.slice();
                    var maxNumber = getLeftEmptySlotFromX(x);

                    if (maxNumber === 0) {
                        return number;
                    }
                    var prev = getPrevBlock(x);
                    var newX = prev.x >= 0 ? prev.x + prev.w - 1 : x;
                    var nMove = number;
                    if (number > maxNumber) {
                        nMove = maxNumber;
                    } else {
                        nMove = number;
                    }

                    // Find empty positions from x to left;
                    // xE is new empty position from x
                    var xE = 0,
                        c = 0,
                        i = newX;
                    while (c <= nMove && i >= 0) {
                        if (flag[i] === 0) {
                            c++;
                            xE = i;
                        }
                        i--;
                    }

                    // Move item from x to xE and we need empty flag from x to xE
                    var flagNoEmpty = [],
                        j = 0;
                    for (i = xE; i <= newX; i++) {
                        flag[i] = 0;
                        if (backupFlag[i] !== 0) {
                            flagNoEmpty[j] = backupFlag[i];
                            j++;
                        }
                    }

                    j = 0;
                    for (i = xE; i <= newX; i++) {
                        if (typeof flagNoEmpty[j] !== "undefined") {
                            flag[i] = flagNoEmpty[j];
                        } else {
                            flag[i] = 0;
                        }
                        j++;
                    }

                    // Return the number positions need to move
                    var left = number - nMove;
                    return left;

                };

                var moveAllItemsFromXToRight = function(x, number) {
                    var backupFlag = flag.slice();
                    var maxNumber = getRightEmptySlotFromX(x);
                    if (maxNumber === 0) {
                        return number;
                    }

                    var prev = getPrevBlock(x);
                    var newX = prev.x >= 0 ? prev.x : x;
                    var nMove = number;
                    if (number <= maxNumber) {
                        nMove = number;
                    } else {
                        nMove = maxNumber;
                    }

                    // Find empty positions from x to right, stop when see any item while finding.
                    var xE = x,
                        c = 0,
                        i = newX;
                    while (c < nMove && i < maxCol) {
                        if (flag[i] === 0) {
                            c++;
                            xE = i;
                        }
                        i++;
                    }

                    // The new position is x, and need empty flag from x to xE
                    var flagNoEmpty = [],
                        j = 0;

                    for (i = newX; i <= xE; i++) {
                        flag[i] = 0;
                        if (backupFlag[i] !== 0) {
                            flagNoEmpty[j] = backupFlag[i];
                            j++;
                        }
                    }

                    j = flagNoEmpty.length - 1;
                    for (i = xE; i >= newX; i--) {
                        if (typeof flagNoEmpty[j] !== "undefined") {
                            flag[i] = flagNoEmpty[j];
                        } else {
                            flag[i] = 0;
                        }
                        j--;
                    }

                    // Return the number positions need to move
                    var left = number - nMove;
                    return left;

                };

                var updateItemsPositions = function() {
                    that.updateItemsPositions(flag);
                };


                /**
                 * Insert to Flag an item with the width is x and position is x
                 *
                 * @param node object Item node
                 * @param swap boolean swap items or not
                 * @returns {boolean}
                 */
                var insertToFlag = function(node, swap) {
                    var x = node.x,
                        w = node.w;

                    // get Empty slots
                    var emptySlots = getEmptySlots();

                    // Not enough empty slots, fallback.
                    if (emptySlots <= 0) {
                        return false;
                    }

                    if (_.isUndefined(swap)) {
                        swap = false;
                    }

                    var _x;
                    var _re;
                    var _le;
                    var _w;

                    if (!swap) {
                        if (isEmptyX(x)) {
                            _w = w;

                            if (checkEnoughSpaceFromX(x, _w)) {
                                addItemToFlag(node);
                                node.el.attr('data-gs-x', x);
                                node.el.attr('data-gs-width', _w);
                                return true;
                            }

                            _re = getRightEmptySlotFromX(x, true);
                            _le = getLeftEmptySlotFromX(x - 1, true);

                            if (_re + _le >= w && (w - _re) <= _le) {
                                _x = x - (w - _re);
                            } else {
                                _x = x - _le;
                            }

                            if (_x < 0) {
                                _x = 0;
                            }
                            while (_w >= 1) {
                                if (checkEnoughSpaceFromX(_x, _w)) {
                                    node.x = _x;
                                    node.w = _w;
                                    addItemToFlag(node);
                                    node.el.attr('data-gs-x', _x);
                                    node.el.attr('data-gs-width', _w);
                                    return true;
                                }
                                _w--;
                            }

                        }

                        if (flag[x] === 1) {
                            var prev = getPrevBlock(x);
                            if (prev.x >= 0) {

                                if (x > prev.x + Math.floor(prev.w / 2) && x > prev.x) {
                                    _x = prev.x + prev.w;
                                    _re = getRightEmptySlotFromX(_x, true);
                                    if (_re >= w) {
                                        addItemToFlag({
                                            el: node.el,
                                            x: _x,
                                            w: w
                                        });
                                        node.el.attr('data-gs-x', _x);
                                        node.el.attr('data-gs-width', w);
                                        return true;
                                    }
                                }

                            }
                        }
                    }


                    var remain = 0;

                    var _move_to_swap = function(node, _x) {

                        var _block_prev;
                        var _block_next;
                        var _empty_slots = 0;
                        var found = false;
                        var i, el, er;
                        if (isEmptyX(_x)) {
                            _block_prev = getPrevBlock(_x);
                            _block_next = getNextBlock(_x);
                            if (_block_prev.x > -1) {
                                _empty_slots = getRightEmptySlotFromX(_block_prev.x);
                                if (_empty_slots >= node.w) {
                                    if (checkEnoughSpaceFromX(_x, node.w)) {
                                        x = _x;
                                        found = true;
                                    } else if (node.ox > _x) {
                                        i = _block_prev.x + _block_prev.w;
                                        el = getLeftEmptySlotFromX(i);
                                        if (el <= node.w) {
                                            el = node.w - el;
                                        } else {
                                            el = node.w;
                                        }
                                        moveAllItemsFromXToRight(i + 1, el);
                                        _empty_slots = getRightEmptySlotFromX(i);
                                        found = false;
                                        while (i > _block_prev.x + _block_prev.w && !found) {
                                            if (checkEnoughSpaceFromX(i, node.w)) {
                                                x = i;
                                                found = true;
                                            }
                                            i--;
                                        }
                                    }
                                }

                                if (!found && node.ox < _x) {
                                    i = _block_prev.x + _block_prev.w - 1;
                                    el = getLeftEmptySlotFromX(_block_prev.x);
                                    if (el > node.w) {
                                        el = node.w;
                                    }
                                    el -= 2;
                                    moveAllItemsFromXToLeft(_block_prev.x, el);
                                    _empty_slots = getRightEmptySlotFromX(i);
                                    i -= _empty_slots;
                                    _block_next = getNextBlock(_x);
                                    var max = _block_prev.x + _block_prev.w;
                                    if (_block_next.x > -1) {
                                        max = _block_next.x;
                                    }
                                    while (i < max && !found) {
                                        if (checkEnoughSpaceFromX(i, node.w)) {
                                            x = i;
                                            found = true;
                                        }
                                        i++;
                                    }


                                }


                                if (!found) {
                                    x = _block_prev.x + _block_prev.w;
                                    node.w = _empty_slots;
                                    node.x = x;
                                }

                            } else if (_block_next.x > -1) {
                                _block_next = getNextBlock(_x);
                                _empty_slots = getRightEmptySlotFromX(_x, false);
                                var n_move = _empty_slots >= node.w ? node.w : _empty_slots;
                                moveAllItemsFromXToRight(_x, n_move);
                                i = _block_next.x;
                                while (i >= 0 && !found) {
                                    if (checkEnoughSpaceFromX(i, node.w)) {
                                        x = i;
                                        node.x = x;
                                        found = true;
                                    }
                                    i--;
                                }

                                if (!found) {
                                    x = _x;
                                    node.w = _empty_slots;
                                    node.x = x;
                                }

                            } else {
                            }

                        } else {
                            _block_prev = getPrevBlock(_x);

                            if (node.ox < _block_prev.x) {
                                moveAllItemsFromXToLeft(_x, node.w);
                                if (isEmptyX(_x)) {
                                    x = _x;
                                } else {
                                    while (!isEmptyX(_x) && _x <= that.cols - 1) {
                                        _x++;
                                    }
                                    x = _x;
                                }
                            } else {
                                moveAllItemsFromXToRight(_x, node.w);
                                if (isEmptyX(_x)) {
                                    x = _x;
                                } else {
                                    while (!isEmptyX(_x) && _x >= 0) {
                                        _x--;
                                    }
                                    x = _x;
                                }
                            }
                        }

                        if (x > that.cols) {
                            x = that.cols - 1;
                        }
                        node.x = x;

                    };

                    _move_to_swap(node, _.clone(x));
                    var newX = x;
                    var i;
                    var found = false;
                    var le = 0;
                    if (x + w > that.cols - 1) {
                        le = getLeftEmptySlotFromX(x, true);
                    }


                    updateItemsPositions();
                    le = 0;
                    while (w >= 1) {
                        if (emptySlots >= w) {
                            if (checkEnoughSpaceFromX(x, w)) {
                                node.w = w;
                                addItemToFlag(node);
                                node.el.attr('data-gs-x', x);
                                node.el.attr('data-gs-width', w);
                                return true;
                            }

                            found = false;
                            le = getLeftEmptySlotFromX(x, true);
                            newX = x - le;
                            i = newX;
                            while (i < maxCol && !found) {
                                if (checkEnoughSpaceFromX(i, w)) {
                                    node.w = w;
                                    addItemToFlag({
                                        el: node.el,
                                        x: i,
                                        w: w
                                    });
                                    node.el.attr('data-gs-x', i);
                                    node.el.attr('data-gs-width', w);
                                    found = true;
                                    return true;
                                }
                                i++;
                            }
                        }
                        w--;
                    }

                    w = node.w;
                    found = false;
                    while (w >= 1) {
                        i = 0;
                        while (i < maxCol && !found) {
                            if (checkEnoughSpaceFromX(i, w)) {
                                addItemToFlag({
                                    el: node.el,
                                    x: i,
                                    w: w
                                });
                                node.el.attr('data-gs-x', i);
                                node.el.attr('data-gs-width', w);
                                found = true;
                                return true;
                            }
                            i++;
                        }
                        w--;
                    }

                    return false;
                };

                var swap = function(node, newX) {
                    var x = node.x;
                    var w = node.w;

                    removeNode(node);

                    var block2 = getPrevBlock(newX);


                    var block2_right = 0;
                    if (block2.x > -1) {
                        block2_right = (block2.x + block2.w);
                    }
                    if (checkEnoughSpaceFromX(newX, w)) {
                        addItemToFlag({
                            el: node.el,
                            x: newX,
                            w: w
                        });
                        return true;
                    } else if (block2_right > 0 && checkEnoughSpaceFromX(block2_right, w) && newX >= block2_right) {
                        var block3 = getNextBlock(newX);
                        if (block3.x > -1) {
                            if (node.w + newX >= block3.x) {
                                var _newX = _.clone(newX);
                                while (_newX > block2_right) {
                                    if (checkEnoughSpaceFromX(_newX, w)) {
                                        addItemToFlag({
                                            el: node.el,
                                            x: _newX,
                                            w: w
                                        });
                                        return true;
                                    }
                                    _newX--;
                                }
                            }
                        }

                        if (newX + w > that.cols) {
                            var _x = that.cols - w;
                            if (checkEnoughSpaceFromX(_x, w)) {
                                addItemToFlag({
                                    el: node.el,
                                    x: _x,
                                    w: w
                                });
                                return true;
                            }
                        }
                        addItemToFlag({
                            el: node.el,
                            x: block2_right,
                            w: w
                        });
                        return true;
                    }

                    node.x = newX;

                    insertToFlag(node, true);
                };

                var that = this;
                flag = that.getFlag($wrapper);
                backupFlag = flag.slice();
                var wOffset = $wrapper.offset();
                that.draggingItem = ui.draggable;
                var width = $wrapper.width();
                var colWidth = width / that.cols;
                var x = 0;
                var iOffset = ui.offset;
                var w, cw, itemWidth, in_this_row;
                cw = that.getW(ui.draggable, false);
                w = that.getW(ui.draggable, true);
                itemWidth = ui.draggable.width();

                var ox = that.getX(ui.draggable);
                if (ZooCustomizeBuilderData.isRtl) {
                    removeNode({
                        el: ui.draggable,
                        x: ox,
                        w: w
                    });
                }

                var xc = 0,
                    xi = 0,
                    found = false;

                if (!ui.draggable.parent().is($wrapper)) {
                    in_this_row = false;
                    if (w < cw) {
                        w = cw;
                    }
                } else {
                    in_this_row = true;
                    w = cw;
                }

                if (!ZooCustomizeBuilderData.isRtl) {
                    xc = Math.round((event.clientX - wOffset.left) / colWidth);
                    xi = Math.round((iOffset.left - wOffset.left - 10) / colWidth);
                    if (xi < 0) {
                        xi = 0;
                    }
                } else {
                    xc = Math.round(((wOffset.left + width + 10) - event.clientX) / colWidth);
                    xi = Math.round(((wOffset.left + width) - (iOffset.left + itemWidth + 10)) / colWidth);
                    if (xi < 0) {
                        xi = 0;
                    }

                }
                if (xc > that.cols) {
                    xc = that.cols;
                }

                x = xi;
                var _i;
                _i = xi;

                if (ZooCustomizeBuilderData.isRtl) {
                    if (!isEmptyX(_i)) {
                        while (_i < that.cols && !found) {
                            if (isEmptyX(_i)) {
                                found = true;
                            } else {
                                _i++;
                            }
                        }
                    } else {
                        x = xi;
                        found = true;
                    }
                } else {

                    if (!isEmptyX(x)) {
                        while (x <= xc && !found) {
                            if (isEmptyX(x)) {
                                found = true;
                            } else {
                                x++;
                            }
                        }
                        if (x > xc) {
                            x = xc;
                        }
                    } else {
                        x = xi;
                        found = true;
                    }

                }

                if (!found) {
                    if (in_this_row) {
                        x = xi;
                    } else {
                        x = xc;
                    }
                }

                if (x < 0) {
                    x = 0;
                }

                if (x + w >= that.cols) {
                    found = true;
                    _i = x;
                    while (_i + w > that.cols && found) {
                        if (!isEmptyX(_i)) {
                            _i++;
                            found = false;
                        } else {
                            _i--;
                        }

                    }

                    x = _i;
                }

                found = undefined;

                var node = {
                    el: ui.draggable,
                    x: x,
                    w: w,
                    ox: ox,
                    ow: cw
                };

                if (node.x <= 0) {
                    node.x = 0;
                }

                var did = false;
                if (in_this_row) {
                    node.x = parseInt(ui.draggable.attr('data-gs-x') || 0);
                    node.w = parseInt(ui.draggable.attr('data-gs-width') || 1);
                    swap(node, x);
                    did = true;
                } else {
                    did = insertToFlag(node);
                }

                if (!did) {
                    ui.draggable.removeAttr('style');
                    flag = backupFlag;
                } else {
                    ui.draggable.removeClass('item-from-list');

                    $wrapper.append(ui.draggable);
                    ui.draggable.removeAttr('style');
                    that.draggingItem = null;
                }

                updateItemsPositions();
                that.updateAllGrids();
            },
            updateAllGrids: function() {
                var that = this;
                _.each(that.panels[that.activePanel], function(row, row_id) {
                    that.updateGridFlag(row);
                });
            },
            setGridWidth: function($wrapper, ui) {
                var that = this;
                var $item = ui.element;
                var width = $wrapper.width();
                var itemWidth = ui.size.width;
                var originalElementWidth = ui.originalSize.width;
                var colWidth = Math.ceil(width / that.cols) - 1;
                var isShiftLeft, isShiftRight;

                if (!ZooCustomizeBuilderData.isRtl) {
                    isShiftLeft = ui.originalPosition.left > ui.position.left;
                    isShiftRight = ui.originalPosition.left < ui.position.left;
                } else {
                    isShiftLeft = ui.originalPosition.left > ui.position.left;
                    isShiftRight = originalElementWidth !== itemWidth;
                }

                var ow = ui.originalElement.attr('data-gs-width') || 1;
                var ox = ui.originalElement.attr('data-gs-x') || 0;
                ow = parseInt(ow);
                ox = parseInt(ox);

                var addW;
                var newX;
                var newW;
                var flag = that.getFlag($wrapper);
                var itemInfo = that.gridGetItemInfo(ui.originalElement, flag, $wrapper);
                var diffLeft, diffRight;

                if (isShiftLeft) {

                    if (!ZooCustomizeBuilderData.isRtl) {
                        newX = Math.floor((ui.position.left - 1) / colWidth);
                        addW = ox - newX;
                        if (addW > itemInfo.before) {
                            addW = itemInfo.before;
                        }

                        newX = ox - addW;
                        newW = ow + addW;
                        $item.attr('data-gs-x', newX).removeAttr('style');
                        $item.attr('data-gs-width', newW).removeAttr('style');
                    } else {
                        newX = Math.floor((ui.position.left - 1) / colWidth);
                        newX = that.cols - newX;
                        addW = (newX - ox) - ow;
                        if (addW > itemInfo.after) {
                            addW = itemInfo.after;
                        }
                        newW = ow + addW;
                        $item.attr('data-gs-x', ox).removeAttr('style');
                        $item.attr('data-gs-width', newW).removeAttr('style');
                    }

                    that.updateGridFlag($wrapper);
                    return;

                } else if (isShiftRight) {

                    if (!ZooCustomizeBuilderData.isRtl) {
                        newX = Math.round((ui.position.left - 1) / colWidth);
                        addW = newX - ox;
                        newW = ow - addW;
                        if (newW <= 0) {
                            newW = 1;
                            addW = 0;
                        }
                        newX = ox + addW;
                        $item.attr('data-gs-x', newX).removeAttr('style');
                        $item.attr('data-gs-width', newW).removeAttr('style');

                    } else {

                        if (ui.originalPosition.left !== ui.position.left) {
                            newX = Math.floor((ui.position.left - 1) / colWidth);
                            newX = that.cols - newX;
                            addW = (ow + ox) - newX;
                            if (addW > ow) {
                                addW = 0;
                            }
                            newX = ox;
                            newW = ow - addW;
                            if (newX <= 0) {
                                newX = 0;
                            }
                        } else {
                            newX = Math.ceil((ui.position.left + ui.size.width - 11) / colWidth);
                            newX = that.cols - newX;
                            addW = ox - newX;
                            if (addW > itemInfo.before) {
                                addW = itemInfo.before;
                            }
                            newX = ox - addW;
                            newW = ow + addW;
                        }
                        $item.attr('data-gs-x', newX).removeAttr('style');
                        $item.attr('data-gs-width', newW).removeAttr('style');
                    }

                    that.updateGridFlag($wrapper);
                    return;
                }

                var w;
                var x = itemInfo.x;
                var x_c;

                if (itemWidth < ui.originalSize.width) {
                    x_c = Math.round((ui.position.left + ui.size.width - 11) / colWidth);
                    if (x_c <= x) {
                        x_c = x + 1;
                    }
                    w = itemInfo.w - ((x + itemInfo.w) - x_c);
                } else {
                    x_c = Math.ceil((ui.position.left + ui.size.width - 11) / colWidth);
                    w = itemInfo.w + (x_c - (x + itemInfo.w));
                    if (itemInfo.x + w > itemInfo.x + itemInfo.w + itemInfo.after) {
                        w = itemInfo.w + itemInfo.after;
                    }
                }

                if (w <= 0) {
                    w = 1;
                }

                $item.attr('data-gs-width', w).removeAttr('style');
                that.updateGridFlag($wrapper);

            },
            getFlag: function($row) {
                var flag = $row.data('gridRowFlag') || [];
                if (_.isEmpty(flag)) {
                    for (var i = 0; i < this.cols; i++) {
                        flag[i] = 0;
                    }
                    $row.data('gridRowFlag', flag);
                }

                return flag;
            },
            updateGridFlag: function($row) {
                var that = this;
                var rowFlag = [];
                var i;
                for (i = 0; i < that.cols; i++) {
                    rowFlag[i] = 0;
                }
                var items;
                items = $('.grid-stack-item', $row);
                items.each(function(index) {
                    $(this).removeAttr('style');
                    var x = that.getX($(this));
                    var w = that.getW($(this));

                    for (i = x; i < x + w; i++) {
                        if (i === x) {
                            rowFlag[i] = $(this);
                        } else {
                            rowFlag[i] = 1;
                        }
                    }

                });

                $row.data('gridRowFlag', rowFlag);
                that.updateItemsPositions(rowFlag);
                that.sortGrid($row);
                return rowFlag;
            },
            addNewWidget: function($item, row) {

                var that = this;
                var panel = that.container.find('.zoo-device-panel.zoo-panel-' + that.activePanel);
                var el = row;
                if (!_.isObject(el)) {
                    el = panel.find('.zoo-customize-builder-items').first();
                }

                var elItem = $item;
                elItem.draggable({
                    revert: "invalid",
                    appendTo: panel,
                    scroll: false,
                    zIndex: 99999,
                    handle: '.grid-stack-item-content',
                    start: function(event, ui) {
                        $('body').addClass('builder-item-moving');
                        $('.zoo-customize-builder-items', panel).css('z-index', '');
                        ui.helper.parent().css('z-index', 9999);
                    },
                    stop: function(event, ui) {
                        $('body').removeClass('builder-item-moving');
                        $('.zoo-customize-builder-items', panel).css('z-index', '');
                        that.save();
                    },
                    drag: function(event, ui) {

                    }
                }).resizable({
                    handles: 'w, e',
                    start: function(event, ui) {
                        ui.originalElement.css({
                            'right': 'auto',
                            left: ui.position.left
                        });

                    },
                    stop: function(event, ui) {
                        that.setGridWidth(ui.element.parent(), ui);
                        that.save();
                    }
                });

                el.append(elItem);
                that.updateGridFlag(el);

            },
            addPanel: function(device) {
                var that = this;
                var template = that.getTemplate();
                var template_id = 'tmpl-zoo-customize-builder-rows';
                if ($('#' + template_id).length == 0) {
                    return;
                }
                if (!_.isObject(options.rows)) {
                    options.rows = {};
                }
                var html = template({
                    device: device,
                    id: options.id,
                    rows: options.rows
                }, template_id);
                return '<div class="zoo-device-panel zoo-vertical-panel zoo-panel-' + device + '" data-device="' + device + '">' + html + '</div>';
            },
            addDevicePanels: function() {
                var that = this;
                _.each(that.devices, function(device_name, device) {
                    var panelHTML = that.addPanel(device);
                    $('.zoo-customize-builder-devices-switcher', that.container).append('<a href="#" class="switch-to switch-to-' + device + '" data-device="' + device + '" data-builder="'+options.id+'">' + device_name + '</a>');
                    $('.zoo-customize-builder-body', that.container).append(panelHTML);
                });

            },
            addItem: function(node) {
                var that = this;
                var template = that.getTemplate();
                var template_id = 'tmpl-zoo-customize-builder-item';
                if ($('#' + template_id).length == 0) {
                    return;
                }
                var html = template(node, template_id);
                return $(html);
            },
            addAvailableItems: function() {
                var that = this;

                _.each(that.devices, function(device_name, device) {
                    var $itemWrapper = $('<div class="zoo-available-items" data-device="' + device + '"></div>');
                    $('.zoo-panel-' + device, that.container).append($itemWrapper);
                    _.each(that.items, function(node) {
                        var _d = true;
                        if (!_.isUndefined(node.devices) && !_.isEmpty(node.devices)) {
                            if (_.isString(node.devices)) {
                                if (node.devices != device) {
                                    _d = false;
                                }
                            } else {
                                var _has_d = false;
                                _.each(node.devices, function(_v) {
                                    if (device == _v) {
                                        _has_d = true;
                                    }
                                });
                                if (!_has_d) {
                                    _d = false;
                                }
                            }
                        }

                        if (_d) {
                            var item = that.addItem(node);
                            $itemWrapper.append(item);
                        }

                    });
                });

            },
            switchToDevice: function(device, toggle_button) {
                var that = this,
                    numberDevices = _.size(that.devices),
                    builderId = $(that.container).find('.zoo-customize-builder-devices-switcher a').data('builder');

                $('.zoo-customize-builder-devices-switcher a', that.container).removeClass('zoo-tab-active');
                $('.zoo-customize-builder-devices-switcher .switch-to-' + device, that.container).addClass('zoo-tab-active');
                $('.zoo-device-panel', that.container).addClass('zoo-panel-hide');
                $('.zoo-device-panel', that.container).addClass('zoo-panel-builder-'+builderId);
                $('.zoo-device-panel.zoo-panel-' + device, that.container).removeClass('zoo-panel-hide');

                that.activePanel = device;

                if (_.isUndefined(toggle_button) || toggle_button) {
                    if (device == 'desktop') {
                        $('#customize-footer-actions .preview-desktop').trigger('click');
                    } else {
                        $('#customize-footer-actions .preview-tablet').trigger('click');
                    }
                }
            },
            addExistingRowsItems: function() {
                var that = this;

                var data = api.control(that.controlId).params.value;
                if (!_.isObject(data)) {
                    data = {};
                }
                _.each(that.panels, function($rows, device) {
                    var device_data = {};
                    if (_.isObject(data[device])) {
                        device_data = data[device];
                    }
                    _.each(device_data, function(items, row_id) {
                        if (!_.isUndefined(items)) {
                            _.each(items, function(node, index) {
                                var item = $('.zoo-available-items[data-device="' + device + '"] .grid-stack-item[data-id="' + node.id + '"]').first();
                                item.attr('data-gs-width', node.width);
                                item.attr('data-gs-x', node.x);
                                item.removeClass('item-from-list');
                                that.addNewWidget(item, $rows[row_id]);
                            });
                        }
                    });
                });

                that.ready = true;
            },
            focus: function() {
                this.container.on('click', '.zoo-customize-builder-item-setting, .zoo-customize-builder-item-name, .item-tooltip', function(e) {
                    e.preventDefault();
                    var section = $(this).data('section') || '';
                    var control = $(this).attr('data-control') || '';
                    var did = false;
                    if (control) {
                        if (!_.isUndefined(api.control(control))) {
                            api.control(control).focus();
                            did = true;
                        }
                    }
                    if (!did) {
                        if (section && !_.isUndefined(api.section(section))) {
                            api.section(section).focus();
                            did = true;
                        }
                    }
                });

                // Focus rows
                this.container.on('click', '.zoo-customize-builder-row-settings', function(e) {
                    e.preventDefault();
                    var id = $(this).attr('data-id') || '';

                    var section = options.id + '_' + id;
                    if (!_.isUndefined(api.section(section))) {
                        api.section(section).focus();
                    }

                });

            },
            remove: function() {
                var that = this;
                $document.on('click', '.zoo-device-panel .zoo-customize-builder-item-remove', function(e) {
                    e.preventDefault();
                    var item = $(this).closest('.grid-stack-item');
                    var panel = item.closest('.zoo-device-panel');
                    item.attr('data-gs-width', 1);
                    item.attr('data-gs-x', 0);
                    item.removeAttr('style');
                    $('.zoo-available-items', panel).append(item);
                    that.updateAllGrids();
                    that.save();
                });

            },
            encodeValue: function(value) {
                return encodeURI(JSON.stringify(value));
            },
            decodeValue: function(value) {
                return JSON.parse(decodeURI(value));
            },
            save: function() {
                var that = this;
                if (!that.ready) {
                    return;
                }

                var data = {};
                _.each(that.panels, function($rows, device) {
                    data[device] = {};
                    _.each($rows, function(row, row_id) {
                        var rowData = _.map($(' > .grid-stack-item', row), function(el) {
                            el = $(el);
                            return {
                                x: that.getX(el),
                                y: 1,
                                width: that.getW(el),
                                height: 1,
                                id: el.data('id') || ''
                            };

                        });
                        data[device][row_id] = rowData;
                    });
                });

                api.control(that.controlId).setting.set(that.encodeValue(data));
            },
            showPanel: function() {
                var that = this;
                this.container.removeClass('zoo-builder--hide').addClass('zoo-builder-show');
                setTimeout(function() {
                    var h = that.container.height();
                    $('#customize-preview').addClass('cb--preview-panel-show').css({
                        'bottom': h - 1,
                        'margin-top': '0px'
                    });
                }, 100);
            },
            hidePanel: function() {
                this.container.removeClass('zoo-builder-show');
                $('#customize-preview').removeClass('cb--preview-panel-show').removeAttr('style');
            },
            togglePanel: function() {
                var that = this;
                api.state('expandedPanel').bind(function(paneVisible) {
                    if (api.panel(options.panel).expanded()) {
                        top._current_builder_panel = id;
                        that.showPanel();
                    } else {
                        that.hidePanel();
                    }
                });

                that.container.on('click', '.zoo-customize-builder-panel-minimize', function(e) {
                    e.preventDefault();
                    that.container.toggleClass('zoo-builder--hide');
                    if (that.container.hasClass('zoo-builder--hide')) {
                        $('#customize-preview').removeClass('cb--preview-panel-show');
                    } else {
                        $('#customize-preview').addClass('cb--preview-panel-show');
                    }
                });

            },
            panelLayoutCSS: function() {
                //api.state( 'paneVisible' ).get()
                var sidebarWidth = $('#customize-controls').width();
                if (!api.state('paneVisible').get()) {
                    sidebarWidth = 0;
                }
                if (ZooCustomizeBuilderData.isRtl) {
                    this.container.find('.zoo-customize-builder-inner').css({
                        'margin-right': sidebarWidth
                    });
                } else {
                    this.container.find('.zoo-customize-builder-inner').css({
                        'margin-left': sidebarWidth
                    });
                }

            },
            init: function(controlId, items, devices) {
                var that = this;


                var template = that.getTemplate();
                var template_id = 'tmpl-zoo-customize-builder-panel';
                var html = template(options, template_id);
                that.container = $(html);
                $('body .wp-full-overlay').append(that.container);
                that.controlId = controlId;
                that.items = items;
                that.devices = devices;

                if (options.section) {
                    api.section(options.section).container.addClass('zoo-hide');
                }

                that.addDevicePanels();
                that.switchToDevice(that.activePanel);
                that.addAvailableItems();
                that.switchToDevice(that.activePanel);
                that.drag_drop();
                that.focus();
                that.remove();
                that.addExistingRowsItems();

                if (api.panel(options.panel).expanded()) {
                    that.showPanel();
                } else {
                    that.hidePanel();
                }

                api.previewedDevice.bind(function(newDevice) {
                    if (newDevice === 'desktop') {
                        that.switchToDevice('desktop', false);
                    } else {
                        that.switchToDevice('mobile', false);
                    }
                });

                that.togglePanel();
                if (api.state('paneVisible').get()) {
                    that.panelLayoutCSS();
                }
                api.state('paneVisible').bind(function() {
                    that.panelLayoutCSS();
                });

                $(window).resize(_.throttle(function() {
                    that.panelLayoutCSS();
                }, 100));

                // Switch panel
                that.container.on('click', '.zoo-customize-builder-devices-switcher a.switch-to', function(e) {
                    e.preventDefault();
                    var device = $(this).data('device');
                    that.switchToDevice(device);
                });

                $document.trigger('zoo_builder_panel_loaded', [id, that]);

            }
        };

        Builder.init(options.control_id, options.items, options.devices);
        return Builder;
    };

    api.bind('ready', function(e, b) {
        _.each(ZooCustomizeBuilderData.builders, function(opts, id) {
            new CustomizeBuilder(opts, id);
        });

        // When focus section
        api.state('expandedSection').bind(function(section) {
            $('.zoo-device-panel .grid-stack-item').removeClass('item-active');
            $('.zoo-customize-builder-row').removeClass('row-active');
            if (section) {
                $('.zoo-customize-builder-row[data-id="' + section.id + '"]').addClass('row-active');
                $('.zoo-device-panel .grid-stack-item.for-s-' + section.id).addClass('item-active');
            }
        });
    });

    // Focus
    $document.on('click', '.zoo-customize-focus-button', function(e) {
        e.preventDefault();
        var id = $(this).attr('data-id') || '';
        if (!id) {
            id = $(this).attr('href') || '';
            id = id.replace('#', '');
        }

        if (id) {
            if (api.section(id)) {
                api.section(id).focus();
            }
        }
    });

    $document.on('click', '.focus-control', function(e) {
        e.preventDefault();
        var id = $(this).attr('data-id') || '';
        if (!id) {
            id = $(this).attr('href') || '';
            id = id.replace('#', '');
        }
        if (id) {
            if (api.control(id)) {
                api.control(id).focus();
            }
        }
    });

    $document.on('click', '.focus-panel', function(e) {
        e.preventDefault();
        var id = $(this).attr('data-id') || '';
        if (!id) {
            id = $(this).attr('href') || '';
            id = id.replace('#', '');
        }
        if (id) {

            if (api.panel(id)) {
                api.panel(id).focus();
            }
        }
    });

    // Save Template
    $document.on('click', '.save-template-form .save-builder-template', function(e) {
        e.preventDefault();
        var form = $(this).closest('.customize-control');
        var input = $('.template-input-name', form);
        var template_name = input.val();
        if (template_name && template_name !== '') {
            $.post(ajaxurl, {
                action: 'zoo_builder_save_template',
                name: input.val(),
                builder: input.attr('data-builder-id') || '',
                panel: input.attr('data-builder-panel') || ''
            }, function(res) {
                if (res.success) {
                    input.val('');
                    form.find('.list-saved-templates .li-boxed').removeClass('active-builder-template');
                    form.find('.list-saved-templates').prepend(res.data.li);
                    form.find('.list-saved-templates').addClass('has-templates');
                } else {
                    console.log('Failed');
                }
            });
        }
    });

    // Import Template
    $document.on('click', '.save-template-form .zoo-customize-import-template-button', function(e)
    {
        e.preventDefault();

        var data = new FormData(),
            form = $(this).closest('.customize-control'),
            input = form.find('.zoo-customize-import-template-file')[0],
            $input = $(input);

        if (input.files.length) {
            var panelId = $input.data('builderPanel'),
                builderId = $input.data('builderId');
            data.append('panel', panelId);
            data.append('builder', builderId);
            data.append('action', 'zoo_builder_save_template');
            data.append('zooImportTemplate', 'OK');
            data.append('zooCustomizeTemplateFile', input.files[0]);
            $.ajax({
                url: ajaxurl,
                data: data,
                method: 'POST',
                contentType: false,
                processData: false
            }).done(function(r) {
                if (r.success) {
                    var savedTemplates = $('.'+builderId+'_settings-saved-templates');
                    $input.val('');
                    savedTemplates.prepend(r.data.li);
                    savedTemplates.addClass('has-templates');
                } else {
                    console.log('Failed');
                }
            });
        }
    });

    // Everytime thememods are published, update the active template data.
    api.bind('saved', function(response)
    {
        var activePanel = api.state('expandedPanel').get().id;

        if (!activePanel) {
            console.log('Invalid saved data. Panel not found.');
            return;
        }

        var activeBuilder = activePanel.replace('_settings', ''),
            activeTemplate = $('.'+activePanel+'-saved-templates .active-builder-template')[0];

        if (!activeTemplate) {
            console.log('No active builder template found.');
            return;
        }

        var activeTemplateId = $(activeTemplate).data('tplId');

        if (!activeTemplateId) {
            console.log('No active builder template ID found.');
            return;
        }

        $.post(ajaxurl, {
            action: 'zoo_builder_update_template',
            builderId: activeBuilder,
            templateId: activeTemplateId
        }, function(res) {
            console.log(res);
        });
    });

    $document.on('click', '.list-saved-templates .saved_template .delete-tpl', function(e) {
        e.preventDefault();
        var item = $(this).parent(),
            form = $(this).closest('.customize-control'),
            input = $('.template-input-name', form);

        if (item.hasClass('active-builder-template')) {
            alert('WARNING: You can not delete active template. Switch to another template before deleting it.');
            return false;
        }

        var areUSure = confirm('Are you sure to delete this template?');

        if (!areUSure) {
            return false;
        }

        $.post(ajaxurl, {
            action: 'zoo_builder_save_template',
            remove: item.data('tplId'),
            builder: input.attr('data-builder-id') || '',
            panel: input.attr('data-builder-panel') || ''
        }, function(res) {
            item.remove();
            if (form.find('.list-saved-templates li.saved_template').length <= 0) {
                form.find('.list-saved-templates').removeClass('has-templates');
            }
        });
    });

    var encodeValue = function(value) {
        return encodeURI(JSON.stringify(value));
    };

    // Install templates
    $document.on('click', '.list-saved-templates .saved_template .import-tpl, .list-saved-templates .prebuilt-tpl-item .tpl-thumbnail', function(e)
    {
        e.preventDefault();

        var url = top.location.href,
            item = $(this).parent(),
            builder = item.data('builderId'),
            areUSure = confirm('Are you sure to install this template?');

        if (!areUSure) return false;

        $.post(ajaxurl, {
            action: 'zoo_builder_install_template',
            tplId: item.data('tplId'),
            panelId: item.data('builderPanel'),
            builderId: builder
        }, function(r) {
            if (r.success) {
                if (-1 === url.indexOf('?')) {
                    top.location.href = url+'?autofocus[section]='+builder+'_templates';
                } else {
                    if (-1 !== url.indexOf('autofocus[section]=')) {
                        url = url.replace(/autofocus\[section\]=[^&]+/i, 'autofocus[section]='+builder+'_templates');
                    } else {
                        url = url+'&autofocus[section]='+builder+'_templates';
                    }
                    top.location.href = url;
                }
            } else {
                console.log(r);
            }
        });
    });

    $document.on('mouseover', '.zoo-customize-builder-row .grid-stack-item', function(e) {
        var item = $(this);
        var nameW = $('.zoo-customize-builder-item-remove', item).outerWidth() + $('.zoo-customize-builder-item-setting', item).outerWidth();
        var itemW = $('.grid-stack-item-content', item).innerWidth();
        if (nameW > itemW - 50) {
            item.addClass('show-tooltip');
        }
    });

    $document.on('mouseleave', '.zoo-customize-builder-row .grid-stack-item', function(e) {
        $(this).removeClass('show-tooltip');
    });
})(jQuery, window);
