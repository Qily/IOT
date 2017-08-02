define(function(require, exports, module) {
	var common = require('common');
	$('body').append("<style>.js-picture-list .upnow{display:none;}</style>");
	if ($('.LC-help').length > 0) {
		var body_h = $(window).height();
		var ifarme_top = $('.LC-help').offset().top;
		$('.LC-help').css('height',body_h-ifarme_top-50+'px');
	}
	if ($('.lc_get_all_user_btn').length > 0) {
		var btn = $('.lc_get_all_user_btn');
		var data_alt = btn.attr("data-alt");
		
		var save_all_user = function(page = 0, next_openid = '') {
			btn.addClass("disabled");
			btn.html('导入中...');
			var url = adminurl+'n=met_wechat&c=met_wechat&a=doget_all_user';
			if (next_openid) {
				url = url+'&page='+page+'&next_openid='+next_openid;
			}
			$.ajax({
                type:'GET',
                url :url,
                dataType:'json',
                cache:false,
                success:function(data){
                	console.log(data);
                	if (data.errcode === 0) {
                		if (data.next_openid) {
                			var text = '导入中...';
                			save_all_user(data.page, data.next_openid);
                		} else {
                			var text = '导入完成';
                			btn.removeClass("disabled");
                		}
                		text = text+' 总用户：'+data.total+' 已导入：'+data.get_count;
                		btn.html(text);
                	} else {
                		btn.html('导入失败，错误代码：'+data.errmsg);
                		btn.removeClass("disabled");
                	}
                }
            });
		}
		btn.on('click', function () {
			save_all_user();
		});	
	};
})