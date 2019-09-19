/**
 * by 游侠
 * QQ 243802688
 */
(function ($) {
    /**
     * bootstrap 弹出层
     * @param array options = {
            title: '网页提示', // 标题
            html: '', // 内容
            size: '', // 弹出层尺寸
            closeBtnText: '关闭', // 关闭按钮文字
            btn: [function($btn) {
                $btn.text('确定').click(function() {
                    console.log('点击了自定义按钮');
                })
            }], // 添加自定义按钮
            close: function() {} // 关闭触发事件
        }
     */
    var _Modal = function(options) {
        options = $.extend({}, _Modal.options, options);

    	var size = {
            'lg': ['bs-example-modal-lg', 'modal-lg'],
            'sm': ['bs-example-modal-sm', 'modal-sm']
        }
        var modal = $('<div class="modal fade"></div>');

        var modal_dialog = $('<div class="modal-dialog" style="box-shadow: 0 0 10px #444;z-index:1050"></div>').appendTo(modal);
        if (size[options.size]) {
            modal.addClass(size[options.size][0]);
            modal_dialog.addClass(size[options.size][1]);
        }

        var modal_dialog_content = $('<div class="modal-content"></div>').appendTo(modal_dialog);

        //标题
        var modal_dialog_header = $('<div class="modal-header"><h4 class="modal-title">' + options.title + '</h4><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>').appendTo(modal_dialog_content);

        //内容
        var modal_dialog_body = $('<div class="modal-body"></div>').appendTo(modal_dialog_content);
        modal_dialog_body.html(options.html);
        //按钮-底部
        var modal_dialog_footer = $('<div class="modal-footer"><button type="button" class="btn btn-default btn-light" data-dismiss="modal">' + options.closeBtnText + '</button></div>').appendTo(modal_dialog_content);

        if (options.btn && typeof options.btn == 'object') {
            for (var p in options.btn) {
                var button = $('<button type="button" class="btn btn-primary">按钮</button>').appendTo(modal_dialog_footer);
                options.btn[p](button);
            }
        }

        modal.appendTo('body');
        modal.modal('show');
        modal.on('hidden.bs.modal', function() {
            if (options.close) options.close();
            modal.remove();
        });
        return {
            close: function() {
                modal_dialog_header.find('.close').click();
            }
        }
    }
    /**
     * 
     * @param string text 内容
     * @param function yes 确认按钮事件
     * @param function no  关闭按钮事件
     */
    _Modal.confirm = function(text, yes, no) {
        return _Modal({
			html: text,
			size: 'sm',
			btn: [function($btn) {
				$btn.text('确认').click(yes);
			}],
			close: no
		});
    }

    /**
     * 
     * @param string text 提示内容
     * @param function no 确认按钮事件
     */
    _Modal.alert = function(text, no) {
        return _Modal({
			html: text,
			size: 'sm',
			closeBtnText: '确认',
			close: no
		});
    }

    /**
     * 
     * @param string text 提示内容
     * @param function no 确认按钮事件
     */
    _Modal.tips = function(text) {
        return _Modal({
			html: text,
			size: 'sm'
		});
    }

    _Modal.options = {
        title: '网页提示',
        html: '',
        size: '',
        closeBtnText: '关闭'
    }
    $.modal = _Modal;
})(jQuery);
