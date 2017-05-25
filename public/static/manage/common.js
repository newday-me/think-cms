// 警告弹窗
window.alert = window.commonAlert = function(text, code) {
	if(code == 1) {
		class_name = 'am-text-success';
		icon_name = 'am-icon-check-square';
	} else {
		class_name = 'am-text-danger';
		icon_name = 'am-icon-times-circle';
	}

	message = '<p class="alert-icon"><span class="' + icon_name + ' am-icon-lg ' + class_name + '"></span></p>';
	message += '<p class="' + class_name + '">' + text + '</p>';
	alertify.alert(message).setting({
		basic: true,
		transition: 'fade',
	});
};
// commonAlert('登录名称不能为空');

// 确认弹窗
window.confirmAlert = function(text, success, error) {
	var onSuccess = function() {
		success && success();
	};
	var onError = function() {
		error && error();
	};
	alertify.confirm(text).setHeader('温馨提醒').setting({
		labels: {
			ok: '确定',
			cancel: '取消',
		},
		transition: 'fade',
		onok: onSuccess,
		oncancel: onError,
		onclose: onError
	});
};
// confirmAlert('确定要删除吗?');

// 输入弹窗
window.promptAlert = function(text, value, success, error) {
	var onSuccess = function(event, value) {
		success && success(value);
	};
	var onError = function() {
		error && error();
	};
	alertify.prompt('', value).setHeader(text).setting({
		labels: {
			ok: '确定',
			cancel: '取消',
		},
		transition: 'fade',
		onok: onSuccess,
		oncancel: onError,
		onclose: onError
	});
};
// promptAlert('请输入姓名', '张三');

// 跳转弹窗
window.jumpAlert = function(text, code, url, wait) {

	wait || (wait = 30);
	url || (url = '');
	var class_name, icon_name;
	if(code == 1) {
		class_name = 'am-text-success';
		icon_name = 'am-icon-check-square';
	} else {
		class_name = 'am-text-danger';
		icon_name = 'am-icon-times-circle';
	}

	var onJump = function() {
		if(url.lastIndexOf('javascript:') >= 0) {
			eval(url);
		} else {
			location.href = url;
		}
	};

	message = '<p class="alert-icon"><span class="' + icon_name + ' am-icon-lg ' + class_name + '"></span></p>';
	message += '<p class="' + class_name + '">' + text + '</p>';
	message += '<p class="alert-link"><a href="' + url + '">' + wait + ' 秒后自动跳转链接</a></p>';
	alertify.alert(message).setting({
		basic: true,
		transition: 'fade',
		oncancel: onJump,
		onclose: onJump
	});

	setTimeout(function() {
		onJump();
	}, wait * 1000);
};
// jumpAlert('添加记录成功!', 1);

// Ajax成功
window.ajaxSuccess = function(data) {
	ajaxDone();
	if(data.url) {
		jumpAlert(data.msg, data.code, data.url, data.wait);
	} else {
		commonAlert(data.msg, data.code);
	}
};

// Ajax错误
window.ajaxError = function() {
	ajaxDone();
	commonAlert('网络链接错误');
};

// Ajax完成
window.ajaxDone = function() {
	// remove ajax-disabled
	$('.ajax-disabled').removeClass('ajax-disabled');
};

// 上传文件
window.uploadFile = function(option) {
	var form_data = new FormData();
	form_data.append('upload_option', option.option);
	form_data.append('upload_file', option.file);
	var ajax_option = {
		url: CMS.api.upload,
		type: 'post',
		data: form_data,
		dataType: 'json',
		timeout: 0,
		processData: false,
		contentType: false,
		xhr: function() {
			var xhr = $.ajaxSettings.xhr();
			xhr.upload.onprogress = function(event) {
				var percent = 0,
					position = event.loaded || event.position,
					total = event.total;
				if(event.lengthComputable) {
					percent = position / total * 100;
				}
				if(percent > 100) {
					percent = 100;
				}
				option.progress && option.progress(position, total, percent.toFixed(2));
			};
			return xhr;
		},
		complete: function() {
			option.complete && option.complete();
		},
		success: function(res) {
			option.success && option.success(res);
		},
		error: function(xhr) {
			option.error && option.error(xhr, '网络链接错误');
		}
	};
	window.uploadObject = $.ajax(ajax_option);
};

// 取消上传
window.uploadCancel = function() {
	window.uploadObject && window.uploadObject.abort();
};

$(function() {

	// 刷新页面
	$('.nd-refresh').on('click', function() {
		history.go(0);
	});

	// 返回上一页
	$('.nd-backward').on('click', function() {
		history.go(-1);
	});

	// 跳转链接
	$('.nd-jump').on('click', function() {
		var url = $(this).attr('url');
		url && (location.href = url);
	});

	// 颜色选择
	$('.nd-color').length && require(['color-picker'], function() {
		$('.nd-color').colorPicker && $('.nd-color').colorPicker();
	});

	// 标签编辑
	$('.nd-tag').length && require(['tag-editor'], function() {
		$('.nd-tag').tagEditor && $('.nd-tag').tagEditor({
			forceLowercase: false
		});
	});

	// 输入框
	$('.nd-input').on('change', function() {
		var url = $(this).attr('url'),
			value = $(this).val();
		if(url) {
			url = url.replace('.html', '');
			url = url + '/value/' + value + '.html';
			$.ajax({
				url: url,
				type: 'get',
				dataType: 'json',
				success: ajaxSuccess,
				error: ajaxError
			});
		}
	});

	// 搜索框
	$('.nd-search').on('click', function() {
		var target = $(this).attr('target-form'),
			url = $(this).attr('url');
		if(!url && target) {
			url = $('.' + target).attr('action');
		}
		if(url) {
			url = url.replace('.html', '');
			$('.nd-search-field').each(function() {
				var name = $(this).attr('name');
				var value = $(this).val();
				if(name && value && value != '**') {
					url += '/' + name + '/' + value;
				}
			});
			url += '.html';
			location.href = url;
		}
	});
	
	// 排序
	$('.nd-sorted-table').length && require(['jquery-sortable'], function(){
		$('.nd-sorted-table').sortable({
			containerSelector: 'table',
			itemPath: '> tbody',
			handle: '.nd-sorted-handle',
			itemSelector: '.nd-sorted-item',
			placeholder: '<tr class="placeholder"/>',
			onDrop: function($item, container, _super, event){
				_super($item, container, _super, event);
				
				var table = $(container['el'][0]);
				var sortAction = table.attr('sort-action'), idx = '';
				table.find('.nd-sorted-item').each(function(){
					idx && (idx += ',');
					idx += $(this).attr('data-id');
				});
				sortAction && $.ajax({
					url: sortAction,
					type: 'post',
					dataType: 'json',
					data: {
						idx: idx
					},
					complete: function(){
						history.go(0);
					}
				});
			}
		});
	});

	// 上传文件
	$('.nd-upload-file').on('change', function() {
		var $this = $(this),
			target = $this.attr('nd-target'),
			$target_input,
			preview = $this.attr('nd-preview'),
			$preview_div,
			upload_file = $this.get(0).files[0],
			upload_option = $this.attr('nd-option'),
			$upload_span = $($this.parent().find('span')[0]);

		if(typeof upload_file == 'undefined') {
			return false;
		}

		if(target) {
			$target_input = $('#' + target);
		}

		if(preview) {
			$preview_div = $('#' + preview);
		}

		var option = {
			file: upload_file,
			option: upload_option ? upload_option : '{}',
			progress: function(position, total, percent) {
				$upload_span && $upload_span.html('<span class="am-text-warning">' + percent + '%</span>');
			},
			complete: function() {
				setTimeout(function() {
					$upload_span && $upload_span.html('选择文件');
				}, 3000);
			},
			success: function(res) {
				if(res.code == 1) {
					$target_input && $target_input.val(res.data.url);
					$preview_div && $preview_div.css('background-image', 'url(' + res.data.url + ')');
				} else {
					$upload_span && $upload_span.html('<span class="am-text-danger">' + res.msg + '</span>');
				}
			},
			error: function(xhr, info) {
				$upload_span && $upload_span.html('<span class="am-text-danger">' + info + '</span>');
			}
		};
		uploadFile(option);
	});

	// Json编辑
	$('.nd-editor-json').each(function() {
		var $this = $(this),
			target = $this.attr('nd-target'),
			value = $this.html();
		require(['json-editor'], function(jsoneditor) {
			var $pre = $('<pre id="' + target + '"></pre>');
			$pre.insertAfter($this);

			var options = {
				mode: 'text',
				modes: ['text', 'view'],
				onChange: function() {
					$this.val(editor.getText());
				}
			};
			var editor = new jsoneditor($pre.get(0), options);
			editor.setText(JSON.stringify(JSON.parse(value), null, 2));
		});
	});

	// Html编辑
	$('.nd-editor-html').each(function() {
		var $this = $(this),
			target = $this.attr('nd-target');
		if(CMS.editor == 'wang') {
			createWangEditor($this, target);
		} else {
			createUeditor($this, target);
		}
	});

	// 创建Ueditor
	function createUeditor($this, target) {
		require(['ZeroClipboard', 'baiduEditor'], function(ZeroClipboard) {

			// 剪切板
			window.ZeroClipboard = ZeroClipboard;

			var ue = UE.getEditor($this.get(0), {
				UEDITOR_HOME_URL: CMS.path.lib + '/ueditor/1.4.3.3/',
				serverUrl: CMS.api.upload_editor,
				zIndex: 10000,
				autoHeightEnabled: false,
				initialFrameHeight: 520,
				toolbars: [
					[
						'source', 'selectall', 'undo', 'redo', '|',
						'removeformat', 'formatmatch', 'autotypeset', '|',
						'print', 'preview', 'searchreplace', 'drafts', 'help', 'fullscreen'
					],
					[
						'justifyleft', 'justifycenter', 'justifyright', 'justifyjustify', '|',
						'bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'blockquote', '|',
						'forecolor', 'backcolor', '|',
						'insertorderedlist', 'insertunorderedlist', '|',
						'rowspacingtop', 'rowspacingbottom', 'lineheight', '|',
						'customstyle', 'paragraph', 'fontfamily', 'fontsize'
					],
					[
						'imagenone', 'imageleft', 'imageright', 'imagecenter', '|',
						'simpleupload', 'insertimage', 'emotion', 'scrawl', 'snapscreen', '|',
						'horizontal', 'spechars', 'link', 'unlink',
						'template', 'attachment', 'map', 'insertframe', 'insertcode', '|',
						'inserttable', 'deletetable', 'insertparagraphbeforetable', 'insertrow', 'deleterow', 'insertcol', 'deletecol', 'mergecells', 'mergeright', 'mergedown', 'splittocells', 'splittorows', 'splittocols', 'charts'
					]
				],
				xssFilterRules: true,
				inputXssFilter: true,
				outputXssFilter: true,
				whitList: {
					a: ['target', 'href', 'title', 'class', 'style'],
					abbr: ['title', 'class', 'style'],
					address: ['class', 'style'],
					area: ['shape', 'coords', 'href', 'alt'],
					article: [],
					aside: [],
					audio: ['autoplay', 'controls', 'loop', 'preload', 'src', 'class', 'style'],
					b: ['class', 'style'],
					bdi: ['dir'],
					bdo: ['dir'],
					big: [],
					blockquote: ['cite', 'class', 'style'],
					br: [],
					caption: ['class', 'style'],
					center: [],
					cite: [],
					code: ['class', 'style'],
					col: ['align', 'valign', 'span', 'width', 'class', 'style'],
					colgroup: ['align', 'valign', 'span', 'width', 'class', 'style'],
					dd: ['class', 'style'],
					del: ['datetime'],
					details: ['open'],
					div: ['class', 'style'],
					dl: ['class', 'style'],
					dt: ['class', 'style'],
					em: ['class', 'style'],
					font: ['color', 'size', 'face'],
					footer: [],
					h1: ['class', 'style'],
					h2: ['class', 'style'],
					h3: ['class', 'style'],
					h4: ['class', 'style'],
					h5: ['class', 'style'],
					h6: ['class', 'style'],
					header: [],
					hr: [],
					i: ['class', 'style'],
					img: ['src', 'alt', 'title', 'width', 'height', 'id', '_src', 'loadingclass', 'class', 'data-latex'],
					ins: ['datetime'],
					li: ['class', 'style'],
					mark: [],
					nav: [],
					ol: ['class', 'style'],
					p: ['class', 'style'],
					pre: ['class', 'style'],
					s: [],
					section: [],
					small: [],
					span: ['class', 'style'],
					sub: ['class', 'style'],
					sup: ['class', 'style'],
					strong: ['class', 'style'],
					table: ['width', 'border', 'align', 'valign', 'class', 'style'],
					tbody: ['align', 'valign', 'class', 'style'],
					td: ['width', 'rowspan', 'colspan', 'align', 'valign', 'class', 'style'],
					tfoot: ['align', 'valign', 'class', 'style'],
					th: ['width', 'rowspan', 'colspan', 'align', 'valign', 'class', 'style'],
					thead: ['align', 'valign', 'class', 'style'],
					tr: ['rowspan', 'align', 'valign', 'class', 'style'],
					tt: [],
					u: [],
					ul: ['class', 'style'],
					video: ['autoplay', 'controls', 'loop', 'preload', 'src', 'height', 'width', 'class', 'style']
				}
			});
		});
	}

	// 创建wangEditor
	function createWangEditor($this, target) {

		require(['beautify-html', 'wangEditor', 'codemirror', 'codemirror/mode/htmlmixed/htmlmixed'], function(beautify_html, wangEditor, CodeMirror) {

			// 关闭调试
			wangEditor.config.printLog = false;

			var editor = new wangEditor($this.get(0));

			// 上传设置
			editor.config.uploadImgUrl = CMS.api.upload_editor;
			editor.config.uploadImgFileName = 'upload_file';
			editor.config.uploadParams = {
				action: 'wang',
			};

			// 不过滤JS
			editor.config.jsFilter = false;

			// 创建编辑器
			editor.create();

			// 插入图片
			editor.config.uploadImgFns.onload = function(resultText, xhr) {
				editor.command(null, 'insertHtml', '<img src="' + resultText + '" />');
			};

			// 查看源代码
			var source = editor.menus.source;
			source.updateSelectedEvent = function() {
				if(source.isShowCode == true) {
					if(!source.isShow) {
						source.isShow = true;

						// 美化查看源代码
						var mixedMode = {
							name: "htmlmixed",
							scriptTypes: [{
								matches: /\/x-handlebars-template|\/x-mustache/i,
								mode: null
							}, {
								matches: /(text|application)\/(x-)?vb(a|script)/i,
								mode: "vbscript"
							}]
						};
						var textarea = source.$codeTextarea[0];
						textarea.value = beautify_html.html_beautify(textarea.value);
						var codeMirror = CodeMirror.fromTextArea(textarea, {
							mode: mixedMode,
							lineWrapping: true,
							lineNumbers: true,
							styleActiveLine: true,
							matchBrackets: true,
							selectionPointer: true
						});
						codeMirror.setSize('auto', $(textarea).height() + 'px');
						codeMirror.on('change', function() {
							textarea.value = codeMirror.getValue();
						});

					}
				} else {
					$this.parent().find('.CodeMirror').remove();
					source.isShow = false;
				}
				return source.isShowCode;
			}

			// 全屏处理
			var fullscreen = editor.menus.fullscreen;
			fullscreen.updateSelectedEvent = function() {
				if(editor.isFullScreen == true) {
					$('body').addClass('editor-fullscreen');
				} else {
					$('body').removeClass('editor-fullscreen');
				}
				return editor.isFullScreen;
			}

		});
	}

	// Ajax Get
	$('.ajax-get').click(function() {

		// add ajax-disabled
		if($(this).hasClass('ajax-disabled')) {
			return false;
		}
		$(this).addClass('ajax-disabled');

		var that = this;
		var target = $(that).attr('href') || $(that).attr('url');
		if(target) {
			var ajax_func = function() {
				$.ajax({
					url: target,
					type: 'get',
					dataType: 'json',
					success: ajaxSuccess,
					error: ajaxError
				});
			};
			if($(that).hasClass('ajax-confirm')) {
				confirmAlert('确认要执行该操作吗?', function() {
					ajax_func();
				}, function() {
					ajaxDone();
				});
			} else {
				ajax_func();
			}
		}
		return false;
	});

	// Ajax Post
	$('.ajax-post').click(function() {

		// add ajax-disabled
		if($(this).hasClass('ajax-disabled')) {
			return false;
		}
		$(this).addClass('ajax-disabled');

		var that = this;
		var target_form = $(this).attr('target-form');
		var target = $(this).attr('href') || $(this).attr('url');
		if(target_form && !target) {
			target = $('.' + target_form).attr('action');
		}
		if(target_form && target) {
			var ajax_func = function() {
				$.ajax({
					url: target,
					type: 'post',
					dataType: 'json',
					data: $('.' + target_form).serialize(),
					success: ajaxSuccess,
					error: ajaxError
				});
			};
			if($(that).hasClass('ajax-confirm')) {
				confirmAlert('确认要执行该操作吗?', function() {
					ajax_func();
				}, function() {
					ajaxDone();
				});
			} else {
				ajax_func();
			}
		}
		return false;
	});

});

$(function() {

	// 侧边菜单
	$('.sidebar-nav-list-title').on('click', function() {
		$(this).next().slideToggle(150).end().find('.sidebar-nav-sub-ico').toggleClass('sidebar-nav-sub-ico-rotate');
	});

	// 调整侧边栏
	ajustSidebar();
	$(window).resize(function() {
		ajustSidebar();
	});

	// 隐藏图标按钮
	$('.tpl-header-switch-button').on('click', function() {
		if($('.left-sidebar').is('.active')) {
			if($(window).width() > 1024) {
				$('.tpl-content-wrapper').removeClass('active');
			}
			$('.left-sidebar').removeClass('active');
		} else {

			$('.left-sidebar').addClass('active');
			if($(window).width() > 1024) {
				$('.tpl-content-wrapper').addClass('active');
			}
		}
	})

	// 调整侧边栏
	function ajustSidebar() {
		if($(window).width() < 1024) {
			$('.left-sidebar').addClass('active');
		} else {
			$('.left-sidebar').removeClass('active');
		}
	}
});