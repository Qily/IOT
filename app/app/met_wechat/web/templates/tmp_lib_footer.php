<!--<?php
defined('IN_MET') or exit('No permission');
$news_data['description'] = str_replace(array(PHP_EOL, "\r\n", "\r", "\n"),array("","","",""),$news_data['description']);
echo <<<EOT
-->
				<p class="am-text-center"><small>&copy {$year} {$_M['config']['met_webname']} 版权所有</small></p>
			</div>
	</div>
    <script src="//cdn.bootcss.com/jquery/1.10.1/jquery.min.js"></script>
    <script>
    	$(".am-article img").each(function() {
    		$(this).attr('data-rel',$(this).attr('src'));
    		$(this).attr('alt','');
    		$(this).wrap('<figure data-am-widget="figure" class="am am-figure am-figure-default"   data-am-figure="{pureview:\'true\'}"></figure>');
    	});
    </script>
    <style>
    	.am-pureview-actions a{top:10px;}
    	.am-figure-default{margin:0;}
    </style>
    <script src="//cdn.bootcss.com/amazeui/2.7.2/js/amazeui.min.js"></script>
    <script src="//res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <script>
	    wx.config({
	        debug: false,
	        appId: '{$signpackage[appId]}',
	        timestamp: {$signpackage[timestamp]},
	        nonceStr: '{$signpackage[nonceStr]}',
	        signature: '{$signpackage[signature]}',
	        jsApiList: [
	            // 所有要调用的 API 都要加到这个列表中
	            'checkJsApi',
	            'openLocation',
	            'getLocation',
	            'onMenuShareTimeline',
	            'onMenuShareAppMessage',
	            'onMenuShareQQ',
	            'onMenuShareQZone'
	          ]
	    });
	    wx.checkJsApi({
            jsApiList: [
                'getLocation',
                'onMenuShareTimeline',
                'onMenuShareAppMessage',
                'onMenuShareQQ',
                'onMenuShareQZone'
            ],
            success: function (res) {
                //alert(JSON.stringify(res));
            }
        });
	    wx.ready(function () {
	    	//分享到朋友圈
	    	wx.onMenuShareTimeline({
			    title: '{$news_data[page_title]}',
			    link: '{$signpackage[url]}',
			    imgUrl: '{$news_data[img]}',
			    success: function () {
			        // 用户确认分享后执行的回调函数
			    },
			    cancel: function () {
			        // 用户取消分享后执行的回调函数
			    }
			});
			//分享给微信好友
	    	wx.onMenuShareAppMessage({
				title: '{$news_data[page_title]}',
				desc: '{$news_data[description]}',
				link: '{$signpackage[url]}',
				imgUrl: '{$news_data[img]}',
				trigger: function (res) {
					//alert('用户点击发送给朋友');
				},
				success: function (res) {
					//alert('已分享');
				},
				cancel: function (res) {
					//alert('已取消');
				},
				fail: function (res) {
					//alert(JSON.stringify(res));
				}
			});
			//分享给QQ好友
			wx.onMenuShareQQ({
			    title: '{$news_data[page_title]}',
			    desc: '{$news_data[description]}',
			    link: '{$signpackage[url]}',
			    imgUrl: '{$news_data[img]}',
			    success: function () {
			       // 用户确认分享后执行的回调函数
			    },
			    cancel: function () {
			       // 用户取消分享后执行的回调函数
			    }
			});
			//分享给QQ空间
			wx.onMenuShareQZone({
			    title: '{$news_data[page_title]}',
			    desc: '{$news_data[description]}',
			    link: '{$signpackage[url]}',
			    imgUrl: '{$news_data[img]}',
			    success: function () {
			       // 用户确认分享后执行的回调函数
			    },
			    cancel: function () {
			        // 用户取消分享后执行的回调函数
			    }
			});
		});
	</script>
</body>
</html>
<!--
EOT;
?>-->