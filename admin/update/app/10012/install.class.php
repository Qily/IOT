<?php

class install {
	public function dosql() {
		global $_M;
		$query = "select * from {$_M['table']['applist']} where no='10012'";
		$stall = DB::get_one($query);
		if(!$stall){
			$time = time();
			$query="INSERT INTO {$_M['table']['applist']} set no='10012',ver='1.4',m_name='temtool',m_class='temtool',m_action='dotemlist',appname='模板制作助手',info='模板制作必备工具！能够在线配置自定义标签，实现灵活且体验好的模板。能够在线添加自己制作的模板（除应用市场获取外唯一添加模板的渠道）。能够提供模板制作相关资源、指引甚至培训。',updatetime='{$time}'";
			DB::query($query);
			//return '安装完成';
		}else{
			$query="update {$_M['table']['applist']} set ver='1.4',updateime='{$time}' where no='10012'";
			DB::query($query);
			$query="update {$_M['table']['applist']} set ver='1.4',updatetime='{$time}' where no='10012'";
			DB::query($query);
			//return '已经安装';
		}
		return 'complete';
	}
}

?>