var CMS = {};

// router
(function (cms) {
    var router = {};

    router.back = function (timeout) {
        if (timeout) {
            setTimeout(function () {
                router.back();
            }, timeout);
        }
        else {
            history.back();
        }
    };

    router.reload = function (timeout) {
        if (timeout) {
            setTimeout(function () {
                router.reload();
            }, timeout);
        }
        else {
            location.reload();
        }
    };

    router.jump = function (url, timeout) {
        if (timeout) {
            setTimeout(function () {
                router.jump(url);
            }, timeout);
        }
        else {
            if (url.lastIndexOf('javascript:') >= 0) {
                eval(url);
            } else if (url) {
                location.href = url;
            }
        }
    };

    cms.router = router;
}(CMS));

// util
(function (cms) {
    var util = {};

    util.base64Encode = function (text, callback) {
        require(['jquery-base64'], function () {
            callback && callback($.base64.encode(text));
        });
    };

    util.base64Decode = function (text, callback) {
        require(['jquery-base64'], function () {
            callback && callback($.base64.decode(text));
        });
    };

    util.md5 = function (content, callback, binary) {
        require(['spark-md5'], function (sparkMd5) {
            if (callback) {
                if (binary) {
                    callback(sparkMd5.hashBinary(content));
                }
                else {
                    callback(sparkMd5.hash(content));
                }
            }
        });
    };

    util.md5File = function (file, callback) {
        var fileReader = new FileReader();
        fileReader.onload = function (e) {
            util.md5(e.target.result, callback, true);
        };
        fileReader.readAsBinaryString(file);
    };

    cms.util = util;
}(CMS));

// loading
(function (cms) {
    var loading = {};

    loading.show = function (text) {
        loading.hide();

        (typeof text === 'undefined') && (text = '加载中...');
        loading.element = $('<div><p><i class="loading-overlay-icon fa fa-spinner fa-spin"></i></p><p class="loading-overlay-text">' + text + '</p></div>');
        require(['jquery-loading-overlay'], function () {
            $.LoadingOverlay('show', {
                image: null,
                custom: loading.element
            });
        });
    };

    loading.text = function (text) {
        loading.element.find('.loading-overlay-text').text(text);
    };

    loading.hide = function () {
        require(['jquery-loading-overlay'], function () {
            $.LoadingOverlay('hide');
        });
    };

    cms.loading = loading;
}(CMS));

// mixin
(function (cms) {
    var mixin = {};

    mixin.add = function () {
        return {
            data: {
                selector: '',
                action_add: '',
                form: {}
            },
            mounted: function () {
                cms.ui.showModal(this.selector);
            },
            methods: {
                addRecord: function () {
                    var $this = this;
                    if (!$this.action_add) {
                        return;
                    }

                    var data = $this.form;
                    cms.ajax.request($this.action_add, data);
                }
            }
        };
    };

    mixin.edit = function () {
        return {
            data: {
                selector: '',
                data_no: '',
                action_edit: '',
                form: {}
            },
            created: function () {
                var $this = this;
                if (!$this.action_edit) {
                    return;
                }

                var data = {
                    action: 'get',
                    data_no: $this.data_no
                };
                cms.ajax.request($this.action_edit, data, function (result) {
                    if (result.code === 1) {
                        $this.form = result.data;
                        cms.ui.showModal($this.selector);
                    }
                    else {
                        cms.alert.ajaxAlert(result);
                    }
                });
            },
            methods: {
                saveRecord: function () {
                    var $this = this;
                    if (!$this.action_edit) {
                        return;
                    }

                    var data = $this.form;
                    data.action = 'save';
                    data.data_no = $this.data_no;
                    cms.ajax.request($this.action_edit, data);
                }
            }
        };
    };

    cms.mixin = mixin;
}(CMS));

// app
(function (cms) {
    var app = {};

    app.selector = {
        addModal: '#modal-add',
        editModal: '#modal-edit'
    };

    app.recordAdd = function (actionAdd, form, selector) {
        form || (form = {});
        selector || (selector = app.selector.addModal);
        cms.ui.template(selector);
        new Vue({
            mixins: [cms.mixin.add()],
            el: selector,
            data: {
                selector: selector,
                action_add: actionAdd,
                form: form
            }
        });
    };

    app.recordEdit = function (actionEdit, dataNo, selector) {
        selector || (selector = app.selector.editModal);
        cms.ui.template(selector);
        new Vue({
            mixins: [cms.mixin.edit()],
            el: selector,
            data: {
                selector: selector,
                data_no: dataNo,
                action_edit: actionEdit
            }
        });
    };

    app.recordDelete = function (actionDelete, dataNo, dataTitle) {
        CMS.alert.confirm('确定要删除[' + dataTitle + ']吗？', function () {
            var data = {
                data_no: dataNo
            };
            CMS.ajax.request(actionDelete, data);
        });
    };

    app.getTreeDataNos = function (selector, key) {
        var nodes = $(selector).fancytree('getTree').getSelectedNodes();
        var i, nos = [];
        for (i in nodes) {
            nos.push(nodes[i]['data'][key]);
        }
        return nos.join(',');
    };

    cms.app = app;
}(CMS));

// alert
(function (cms) {
    var alert = {};

    alert.alert = function (text, type, time) {
        type || (type = 'warning');
        time || (time = 4);
        var html = '<p>' + text + '</p><p>&nbsp;</p>';
        swal({
            html: html,
            type: type,
            showConfirmButton: false,
            timer: time * 1000
        })
    };


    alert.confirm = function (text, success, error) {
        text || (text = '确定要继续操作？');
        swal({
            title: '',
            text: text,
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: '取消',
            cancelButtonText: '确定'
        }).then(function (result) {
            if (result.dismiss === 'cancel') {
                success && success();
            }
            else {
                error && error();
            }
        });
    };

    alert.prompt = function (text, value, success, error) {
        swal({
            text: text,
            input: 'textarea',
            inputValue: value,
            inputPlaceholder: '',
            showCancelButton: true,
            confirmButtonText: '确定',
            cancelButtonText: '取消'
        }).then(function (result) {
            if (result.dismiss) {
                error && error();
            }
            else {
                success && success(result.value);
            }
        });
    };

    alert.jump = function (text, type, url, time) {
        type || (type = 'warning');
        url || (url = '');
        time || (time = 3);
        var html = '<p>' + text + '</p><p><a style="font-size: 16px;" href="' + url + '">' + time + '秒后自动跳转链接</p></a>';
        swal({
            html: html,
            type: type,
            showConfirmButton: false,
            timer: time * 1000
        }).then(function () {
            cms.router.jump(url);
        });
    };

    alert.ajaxAlert = function (result) {
        var type = alert.alertType(result.code);
        alert.alert(result.msg, type);
    };

    alert.ajaxJump = function (result) {
        var type = alert.alertType(result.code);
        alert.jump(result.msg, type, result.url, result.wait);
    };

    alert.alertType = function (code) {
        if (code === 1) {
            return 'success';
        }
        else {
            return 'error';
        }
    };

    cms.alert = alert;
}(CMS));

// ui
(function (cms) {
    var ui = {};

    ui.template = function (selector) {
        $(selector).html($(selector + '-template').html());
    };

    ui.showModal = function (selector) {
        $(selector).modal('show');
    };

    ui.hideModal = function (selector) {
        $(selector).modal('hide');
    };

    ui.hideCurrentModal = function () {
        ui.hideModal('.modal.in');
    };

    cms.ui = ui;
}(CMS));

// ajax
(function (cms) {
    var ajax = {};

    ajax.request = function (url, data, success, error) {
        success || (success = ajax.success);
        error || (error = ajax.error);

        $.ajax({
            url: url,
            type: 'post',
            dataType: 'json',
            data: data,
            complete: ajax.complete,
            success: success,
            error: error
        });
    };

    ajax.none = function () {
    };

    ajax.success = function (result) {
        if (result.code === 1) {
            cms.ui.hideCurrentModal();
        }

        if (result.url) {
            cms.alert.ajaxJump(result);
        }
        else {
            if (result.msg) {
                cms.alert.ajaxAlert(result);
            }

            if (result.code === 1) {
                cms.router.reload(1500);
            }
        }
    };

    ajax.error = function () {
        cms.alert.alert('网络链接错误', 'error');
    };

    ajax.complete = function () {
        $('.ajax-disabled').removeClass('ajax-disabled');
    };

    ajax.uploadFile = function (option) {
        option.field || (option.field = 'upload_file');

        var formData = new FormData();
        formData.append(option.field, option.file);
        if (option.extra) {
            for (var key in option.extra) {
                formData.append(key, option.extra[key]);
            }
        }

        var ajaxOption = {
            url: option.url,
            type: 'post',
            data: formData,
            dataType: 'json',
            timeout: 0,
            processData: false,
            contentType: false,
            xhr: function () {
                var xhr = $.ajaxSettings.xhr();
                xhr.upload.onprogress = function (event) {
                    var percent = 0,
                        position = event.loaded || event.position,
                        total = event.total;
                    if (event.lengthComputable) {
                        percent = position / total * 100;
                        percent = percent > 100 ? 100 : percent;
                    }
                    option.progress && option.progress(position, total, percent.toFixed(2));
                };
                return xhr;
            },
            complete: function () {
                option.complete && option.complete();
            },
            success: function (res) {
                option.success && option.success(res);
            },
            error: function (xhr) {
                option.error && option.error(xhr, '网络链接错误');
            }
        };
        ajax.uploadObject = $.ajax(ajaxOption);
    };

    ajax.uploadCancel = function () {
        ajax.uploadObject && ajax.uploadObject.abort();
    };

    cms.ajax = ajax;
}(CMS));

// action
(function (cms) {
    var action = {};

    action.ajaxGet = function (selector) {
        var element = $(selector);

        if (element.hasClass('ajax-disabled')) {
            return false;
        } else {
            element.addClass('ajax-disabled');
        }

        var url = element.attr('href') || element.attr('url');
        if (url) {
            var ajaxFunc = function () {
                cms.ajax.request(url, {});
            };
            if (element.hasClass('ajax-confirm')) {
                cms.alert.confirm('确认要执行该操作吗?', function () {
                    ajaxFunc();
                }, function () {
                    cms.ajax.complete();
                });
            } else {
                ajaxFunc();
            }
        }

        return false;
    };

    action.ajaxPost = function (selector) {
        var element = $(selector);

        if (element.hasClass('ajax-disabled')) {
            return false;
        } else {
            element.addClass('ajax-disabled');
        }

        var targetForm = element.attr('target-form');
        targetForm || (targetForm = '.ajax-form');

        var url = element.attr('href') || element.attr('url');
        if (targetForm && !url) {
            url = $(targetForm).attr('action');
        }

        if (targetForm && url) {
            var ajaxFunc = function () {
                cms.ajax.request(url, $(targetForm).serialize());
            };
            if (element.hasClass('ajax-confirm')) {
                cms.alert.confirm('确认要执行该操作吗?', function () {
                    ajaxFunc();
                }, function () {
                    cms.ajax.complete();
                });
            } else {
                ajaxFunc();
            }
        }
        else {
            cms.ajax.complete();
        }
        return false;
    };

    action.search = function (selector) {
        var element = $(selector);

        var target = element.attr('search-form');
        target || (target = '.nd-search-form');

        var url = $(target).attr('action');
        if (url) {
            url = url.replace('.html', '');
            $(target).find('.nd-search-field').each(function () {
                var name = $(this).attr('name');
                var value = $(this).val();
                if (name && value && value !== '**') {
                    url += '/' + name + '/' + value;
                }
            });
            url += '.html';
            cms.router.jump(url);
        }
    };

    action.modify = function (selector) {
        var element = $(selector);

        var option = JSON.parse(element.attr('data-option'));
        if (option.field && option.url && option.data_no) {
            var value = element.val();
            var data = {
                data_no: option.data_no,
                field: option.field,
                value: value
            };
            cms.ajax.request(option.url, data, cms.ajax.none, cms.ajax.none);
        }
    };

    action.switch = function (selector) {
        var element = $(selector);

        var option = JSON.parse(element.attr('data-option'));
        if (option.field && option.url && option.data_no) {
            var value = element.is(':checked') ? option.on : option.off;
            var data = {
                data_no: option.data_no,
                field: option.field,
                value: value
            };
            cms.ajax.request(option.url, data, cms.ajax.none, cms.ajax.none);
        }
    };

    cms.action = action;
}(CMS));

// init
(function (cms) {
    var init = {};

    init.select2 = function (selector) {
        require(['jquery-select2'], function () {
            $(selector).select2();
        });
    };

    init.date = function (selector) {
        require(['bootstrap-date-picker'], function () {
            $(selector).datepicker({
                language: 'zh-CN',
                clearBtn: true,
                autoclose: true
            });
        });
    };

    init.dateRange = function (selector) {
        require(['bootstrap-date-range-picker'], function () {
            var element = $(selector);
            var format = element.attr('data-format');
            element.daterangepicker({
                    autoUpdateInput: false,
                    showDropdowns: true,
                    locale: {
                        format: format,
                        applyLabel: '应用',
                        cancelLabel: '取消',
                        resetLabel: '重置'
                    }
                },
                function () {
                    if (!this.startDate || !this.endDate) {
                        this.element.val('');
                    } else {
                        this.element.val(this.startDate.format(this.locale.format) + ' - ' + this.endDate.format(this.locale.format));
                    }
                }
            );
        });
    };

    init.color = function (selector) {
        require(['bootstrap-color-picker'], function () {
            var element = $(selector);
            var format = element.attr('data-format');
            format || (format = 'hex');
            element.colorpicker({
                format: format
            });
        });
    };

    init.mask = function (selector) {
        require(['jquery-input-mask'], function () {
            var element = $(selector);
            var option = JSON.parse(element.attr('data-option'));
            element.inputmask(option);
        });
    };

    init.tagInput = function (selector) {
        require(['bootstrap-tag-input'], function () {
            $(selector).tagsinput();
        });
    };

    init.jsonEditor = function (selector) {
        require(['codemirror', 'code-json'], function (CodeMirror) {
            var element = $(selector);
            element.val(JSON.stringify(JSON.parse(element.val()), null, 2));

            CodeMirror.fromTextArea(selector, {
                mode: 'application/json',
                theme: 'eclipse',
                lineNumbers: true,
                lint: true
            });
        });
    };

    init.htmlEditor = function (selector) {
        require(['codemirror', 'beautify-html', 'code-html'], function (CodeMirror, beautifyHtml) {
            var element = $(selector);
            element.val(beautifyHtml.html_beautify(element.val()));

            CodeMirror.fromTextArea(selector, {
                mode: 'text/html',
                theme: 'eclipse',
                lineNumbers: true,
                lint: true
            });
        });
    };

    init.fileInput = function (selector, type) {
        require(['bootstrap-file-input'], function () {
            require(['bootstrap-file-input-lang'], function () {
                var element = $(selector);
                var target = $(element.attr('data-target'));
                var isMulti = typeof element.attr('multiple') === 'undefined';
                var util = {
                    cleared: false,
                    clear: function () {
                        this.setValue('');
                    },
                    add: function (value) {
                        var valueArray = this.getValueArray();
                        valueArray.push(value);
                        this.setValueArray(valueArray);
                    },
                    update: function (index, url) {
                        var valueArray = this.getValueArray();
                        valueArray[index] = url;
                        this.setValueArray(valueArray);
                    },
                    remove: function (index) {
                        var valueArray = this.getValueArray();
                        valueArray[index] = '';
                        this.setValueArray(valueArray);
                    },
                    removeByValue: function (value) {
                        var index = this.getIndex(value);
                        if (index >= 0) {
                            this.remove(index);
                        }
                    },
                    getIndex: function (value) {
                        var valueArray = this.getValueArray();
                        return valueArray.indexOf(value);
                    },
                    getValue: function () {
                        return target.val();
                    },
                    getValueArray: function () {
                        value = this.getValue();
                        if (value) {
                            return value.split(',');
                        }
                        else {
                            return [];
                        }
                    },
                    setValue: function (value) {
                        target.val(value);
                    },
                    setValueArray: function (valueArray) {
                        this.setValue(valueArray.join(','));
                    }
                };

                var valueArray = util.getValueArray();
                var preview = [], config = [];
                var caption, previewData = type === 'image';
                for (var i in valueArray) {
                    if (previewData) {
                        preview.push(valueArray[i]);
                    }
                    else {
                        preview.push('<div class="file-preview-other"><span class="file-other-icon"><i class="fa fa-file"></i></span></div>');
                    }
                    config.push({
                        caption: valueArray[i],
                        key: valueArray[i],
                        url: cms.api.upload + '?source=file_delete'
                    });
                }

                element.fileinput({
                    theme: 'explorer-fa',
                    language: 'zh',
                    uploadUrl: cms.api.upload,
                    uploadExtraData: {
                        'source': 'file-input'
                    },
                    showUpload: false,
                    showRemove: false,
                    showDrag: false,
                    initialPreviewAsData: previewData,
                    initialPreview: preview,
                    initialPreviewConfig: config
                }).on('fileuploaded', function (event, data, previewId, index) {
                    var result = data.response;
                    if (result.code === 1) {
                        if (isMulti) {
                            util.update(index, result.data.url);
                        }
                        else {
                            util.setValue(result.data.url);
                        }
                    }
                    else {
                        cms.alert.alert(result.msg, 'error');
                    }
                }).on('fileselect', function (event, numFiles, label) {
                    if (isMulti) {
                        if (!util.cleared) {
                            util.cleared = true;
                            util.clear();
                        }

                        for (var i = 0; i < numFiles; i++) {
                            util.add('');
                        }
                    }
                    else {
                        util.clear();
                    }
                }).on('fileremoved', function (event, id, index) {
                    if (isMulti) {
                        util.remove(index);
                    }
                    else {
                        util.clear();
                    }
                }).on('filesuccessremove', function (event, id, index) {
                    if (isMulti) {
                        util.remove(index);
                    }
                    else {
                        util.clear();
                    }
                }).on('filedeleted', function (event, key, jqXHR, data, extra) {
                    if (isMulti) {
                        util.removeByValue(key);
                    }
                    else {
                        util.clear();
                    }
                }).on('filecleared', function () {
                    util.clear();
                });
            });
        });
    };

    init.summerNote = function (selector) {
        require(['beautify-html', 'summer-note'], function (beautifyHtml) {
            var element = $(selector);
            element.summernote({
                lang: 'zh-CN',
                height: 400,
                prettifyHtml: beautifyHtml.html_beautify,
                codemirror: {
                    mode: 'text/html',
                    theme: 'eclipse',
                    lineNumbers: true,
                    lint: true
                },
                callbacks: {
                    onImageUpload: function (files) {
                        cms.ajax.uploadFile({
                            url: cms.api.upload,
                            file: files[0],
                            extra: {
                                source: 'summer-note'
                            },
                            success: function (result) {
                                if (result.code === 1) {
                                    element.summernote('insertImage', result.data.url, result.data.name);
                                }
                                else {
                                    cms.alert.alert(result.msg, 'error');
                                }
                            },
                            error: cms.ajax.error
                        });
                    }
                }
            });
        });
    };

    init.umEditor = function (selector) {
        require(['um-editor'], function () {
            var element = $(selector);
            var id = element.attr('id');
            var um = UM.getEditor(id, {
                imageUrl: cms.api.upload + '?source=um-editor',
                imagePath: '',
                initialFrameHeight: 400
            });
            um.addListener('contentchange', function () {
                element.val(um.getContent());
            });
        });
    };

    cms.init = init;
}(CMS));

// ide
(function () {
    var ide = {};

    ide.result = {
        code: 1,
        msg: '',
        data: '',
        url: '',
        wait: ''
    };
}());

$(function () {
    // sidebar menu
    $('.sidebar-menu').tree();

    //////////////////////////////////////////////////

    // back
    $(document).on('click', '.nd-back', function () {
        CMS.router.back();
    });

    // reload
    $(document).on('click', '.nd-reload', function () {
        CMS.router.reload();
    });

    // jump
    $(document).on('click', '.nd-jump', function () {
        var url = $(this).attr('url');
        url && CMS.router.jump(url);
    });

    //////////////////////////////////////////////////

    // select2
    $('.nd-select2').each(function () {
        CMS.init.select2(this);
    });

    // date
    $('.nd-date').each(function () {
        CMS.init.date(this);
    });

    // date range
    $('.nd-date-range').each(function () {
        CMS.init.dateRange(this);
    });

    // color
    $('.nd-color').each(function () {
        CMS.init.color(this);
    });

    // mask
    $('.nd-mask').each(function () {
        CMS.init.mask(this);
    });

    // tagInput
    $('.nd-tag').each(function () {
        CMS.init.tagInput(this);
    });

    // uploadImage
    $('.nd-upload-image').each(function () {
        CMS.init.fileInput(this, 'image');
    });

    // uploadFile
    $('.nd-upload-file').each(function () {
        CMS.init.fileInput(this, 'file');
    });

    // summerNote
    $('.nd-summernote').each(function () {
        CMS.init.summerNote(this);
    });

    // umEditor
    $('.nd-umeditor').each(function () {
        CMS.init.umEditor(this);
    });

    // jsonEditor
    $('.nd-code-json').each(function () {
        CMS.init.jsonEditor(this);
    });

    // htmlEditor
    $('.nd-code-html').each(function () {
        CMS.init.htmlEditor(this);
    });

    //////////////////////////////////////////////////

    // radio|select
    $(document).on('change', '.nd-radio, .nd-select, .nd-input', function () {
        CMS.action.modify(this);
    });

    // switch
    $(document).on('change', '.nd-switch', function () {
        CMS.action.switch(this);
    });

    // search
    $(document).on('click', '.nd-search', function () {
        CMS.action.search(this);
    });

    // ajax get
    $(document).on('click', '.ajax-get', function () {
        CMS.action.ajaxGet(this);
    });

    // ajax post
    $(document).on('click', '.ajax-post', function () {
        CMS.action.ajaxPost(this);
    });
});