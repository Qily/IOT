<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 

defined('IN_MET') or exit('No permission');

load::sys_class('admin');

class temset extends admin {
	public function __construct() {
		global $_M;
		parent::__construct();
		//nav::set_nav(4,'模板列表',$_M[url][own_name].'c=temtool&a=dotemlist');
		nav::set_nav(0,'全局',"{$_M['url']['own_name']}c=temset&a=doset&no={$_M['form']['no']}&pos=0");
		nav::set_nav(1,'首页',"{$_M['url']['own_name']}c=temset&a=doset&no={$_M['form']['no']}&pos=1");
		nav::set_nav(2,'列表页',"{$_M['url']['own_name']}c=temset&a=doset&no={$_M['form']['no']}&pos=2");
		nav::set_nav(3,'详情页',"{$_M['url']['own_name']}c=temset&a=doset&no={$_M['form']['no']}&pos=3");
	}
	public function doset() {
		global $_M;
		$_M[form][pos] = $_M[form][pos]?$_M[form][pos]:0;
		nav::select_nav($_M[form][pos]);
		require $this->template('own/temset');
	}
	
	public function dosetlist(){
		global $_M;
		require $this->template('own/temsetlist');
	}
	
	function dotable_add(){
		global $_M;
		$id = 'new-'.$_M[form][ai];
		$metinfo ="<tr class=\"even newlist\">
					<td class=\"met-center\"><input name=\"id\" type=\"checkbox\" value=\"{$id}\" /></td>
					<td class=\"met-center\"><i class=\"fa fa-caret-right\" style='display:none;'></i></td>
					<td class=\"met-center\">".$this->select(2,$id)."</td>
					<td><input type=\"text\" name=\"name-{$id}\" class=\"ui-input\" placeholder=\"变量名\" data-norepeat='namenopt' value=\"\" ></td>
					<td><input type=\"text\" name=\"defaultvalue-{$id}\" class=\"ui-input\" placeholder=\"默认值\" value=\"\" ></td>
					<td><input type=\"text\" name=\"valueinfo-{$id}\" class=\"ui-input\" value=\"\" placeholder=\"标题\" data-required=\"1\"></td>
					<td><input type=\"text\" name=\"tips-{$id}\" class=\"ui-input\" placeholder=\"说明\" value=\"\" ></td>
					<td class=\"met-center\">
						<select name='pos-{$id}' data-checked='{$_M['form']['pos']}'>
							<option value='0'>全局</option>
							<option value='1'>首页</option>
							<option value='2'>列表页</option>
							<option value='3'>详细页</option>
						</select>
					</td>
					<td>
						<a href=\"{$_M[url][own_form]}a=dosetlist\" class='selectd'>设置选项</a><span class=\"line selectd\">|</span>
				<a href=\"{$_M[url][own_form]}a=dotable_add&&pos={$_M['form']['pos']}\" class='nowaddlist' style=\"display:none;\">添加子选项</a>
				<span class=\"line nowaddlist\" style=\"display:none;\">|</span><a href=\"\" class=\"delet\">撤销</a>
						<input type='hidden' name='selectd-{$id}' value='' />
						<input type='hidden' name='style-{$id}' value='3' />
					</td>
				</tr>";
		echo $metinfo;
	}
	
	public function dotable_temset_json() {
		global $_M;
		
		$table = load::sys_class('tabledata', 'new'); //加载表格数据获取类
		$where = "no='{$_M['form']['no']}' and pos='{$_M['form']['pos']}' and lang='{$_M['form']['lang']}'"; 
		$order = "no_order"; //排序方式
		$array = $table->getdata($_M['table']['templates'], '*', $where, $order); 
		foreach($array as $key => $val) {
			$list = array();
			$list[] = "<input name=\"id\" type=\"checkbox\" value=\"{$val[id]}\" />";
			$list[] = '<i class="fa fa-caret-right"></i>';
			$list[] = $this->select($val['type'],$val[id]);
			$list[] = "<input type=\"text\" name=\"name-{$val[id]}\" class=\"ui-input\" placeholder=\"变量名\" data-norepeat='namenopt' value=\"{$val['name']}\" >";
			$list[] = "<input type=\"text\" name=\"defaultvalue-{$val[id]}\" class=\"ui-input\"  placeholder=\"默认值\" value=\"{$val['defaultvalue']}\" >";
			$list[] = "<input type=\"text\" name=\"valueinfo-{$val[id]}\" class=\"ui-input\" value=\"{$val['valueinfo']}\" placeholder=\"标题\" data-required=\"1\">";
			$list[] = "<input type=\"text\" name=\"tips-{$val[id]}\" class=\"ui-input\" placeholder=\"说明\" value=\"{$val['tips']}\" >";
			$list[] = "
				<select name='pos-{$val[id]}' data-checked='{$val[pos]}'>
					<option value='0'>全局</option>
					<option value='1'>首页</option>
					<option value='2'>列表页</option>
					<option value='3'>详细页</option>
				</select>
			";
			$list[] = "
				<a href=\"{$_M[url][own_form]}a=dosetlist\" class='selectd'>设置选项</a>
				<span class=\"line selectd\">|</span>
				<a href=\"{$_M[url][own_form]}a=dotable_add&pos={$_M['form']['pos']}\" class='nowaddlist'>添加子选项</a>
				<span class=\"line nowaddlist\">|</span>
				<input type='hidden' name='selectd-{$val[id]}' value='{$val['selectd']}' />
				<input type='hidden' name='style-{$val[id]}' value='{$val['style']}' />
				<a href=\"{$_M['url']['own_form']}a=dosetsave&allid={$val[id]},&submit_type=del&no={$_M['form']['no']}&pos={$_M['form']['pos']}\" data-confirm=\"您确定要删除该信息吗？删除之后无法再恢复。<br/>如果删除分区，分区下的子选项不会被删除。\">删除</a>";
				//{$_M['url']['own_name']}c=setedit&a=dosetedit&id={$val[id]}
			if($val['type']==1){
				$list['toclass'] = 'fenqu';
			}else{
				$list['toclass'] = 'xuanxiang';
			}
			$rarray[] = $list;
		}	
		$table->rdata($rarray);
	}
	
	public function pos($pos) {
		switch($pos) {
			case 0:
				$s = "全局";
			break;
			case 1:
				$s = "首页";
			break;
			case 2:
				$s = "列表页";
			break;
			case 3:
				$s = "详细页";
			break;
		}
		return $s;
	}
	
	public function type($type) {
		switch($type) {
			case 1:
				$s = "分区";
			break;
			case 2:
				$s = "简短文本";
			break;
			case 3:
				$s = "多行文本";
			break;
			case 4:
				$s = "单选按钮";
			break;
			case 6:
				$s = "栏目选择";
			break;
			case 7:
				$s = "上传组件";
			break;
			case 8:
				$s = "编辑器";
			break;
			case 9:
				$s = "颜色选择器";
			break;
		}
		return $s;
	}
	
	public function select($type,$id) {
		$select = "<select name='type-{$id}' class='temset_select' data-checked='{$type}'>";
		for($i = 1; $i <= 12; $i++){
			$txt = $this->type($i);
			if($txt){
				$select.= "<option value='{$i}'>{$txt}</option>";
			}
		}
		$select.= "</select>";
		return $select;
	}
	
	public function dosetsave(){
		global $_M;
		$list = explode(",",$_M[form][allid]);
		$type = $_M[form][submit_type];
		$i=0;
		foreach($list as $id){
			if($id){
				$i++;
				if($type=='save' || !$type){
					$name      = $_M['form']['name-'.$id];
					$defaultvalue = $_M['form']['defaultvalue-'.$id];
					$valueinfo = $_M['form']['valueinfo-'.$id];
					$type1 	   = $_M['form']['type-'.$id];
					$tips 	   = $_M['form']['tips-'.$id];
					$selectd   = $_M['form']['selectd-'.$id];
					$style 	   = $_M['form']['style-'.$id];
					$no 	   = $_M['form']['no'];
					$pos       = $_M['form']['pos-'.$id];
					$no_order  = $i;
					if($pos!=$_M['form']['pos']){
						$counter   = DB::counter($_M['table']['templates'], " WHERE no='{$_M['form']['no']}' and pos='{$pos}'  and lang='{$_M['form']['lang']}'", '*');
						$no_order  = $no_order+$counter;
					}    
					$query = "
						no           = '{$no}',
						pos          = '{$pos}',
						no_order     = '{$no_order}',
						name         = '{$name}',
						defaultvalue = '{$defaultvalue}',
						valueinfo    = '{$valueinfo}',
						type	     = '{$type1}',
						tips	     = '{$tips}',
						selectd	     = '{$selectd}',
						style	     = '{$style}',
						lang         = '{$_M['form']['lang']}'
					";
					if(is_number($id)){//修改
						$query = "UPDATE {$_M['table']['templates']} SET {$query} WHERE id = '{$id}' ";
					}else{//新增
						$query = "INSERT INTO {$_M['table']['templates']} SET value = '{$defaultvalue}', {$query} ";
					}
				}elseif($type=='del'){//删除
					if(is_number($id)){
						$query = "DELETE FROM {$_M['table']['templates']} WHERE id='{$id}' and pos = '{$_M['form']['pos']}' and lang='{$_M['form']['lang']}' ";
					}
				}
				DB::query($query);
			}
		}
		
		/*同步到其它语言*/
		$query = "SELECT * FROM {$_M['table']['templates']} where no='{$_M['form']['no']}' AND lang='{$_M['form']['lang']}' ORDER BY no_order,id";
		$tems = DB::get_all($query);
		foreach ($_M['langlist']['web'] as $key=>$val) {
			if($key != $_M['form']['lang']){
				$query = "DELETE FROM {$_M['table']['templates']} WHERE no='{$_M['form']['no']}' AND lang='{$key}'";
				DB::query($query);
				foreach ($tems as $keytems=>$valtems) {
					$query = "INSERT INTO {$_M['table']['templates']} SET no='{$valtems['no']}',pos ='{$valtems['pos']}',no_order='{$valtems['no_order']}',type='{$valtems['type']}',style='{$valtems['style']}',selectd='{$valtems['selectd']}',name ='{$valtems['name']}',value='{$valtems['value']}',defaultvalue='{$valtems['defaultvalue']}',valueinfo ='{$valtems['valueinfo']}',tips='{$valtems['tips']}',lang='{$key}'";
					DB::query($query);
				}
			}
		}
		
		/*生成安装文件*/
		load::sys_func('file');
		$file = "templates/{$_M['form']['no']}/install/install.class.php";
		makefile($file);
		$query = "SELECT * FROM {$_M['table']['skin_table']} where skin_file='{$_M['form']['no']}'";
		$tem = DB::get_one($query);
		$query = "SELECT * FROM {$_M['table']['templates']} where no='{$_M['form']['no']}' AND lang='{$_M['form']['lang']}' ORDER BY no_order,id";
		$tems = DB::get_all($query);
		foreach($tems as $keytems=>$valtems){
			$sql[] = "pos ='{$valtems['pos']}',no_order='{$valtems['no_order']}',type='{$valtems['type']}',style='{$valtems['style']}',selectd='{$valtems['selectd']}',name ='{$valtems['name']}',value='{$valtems['defaultvalue']}',defaultvalue='{$valtems['defaultvalue']}',valueinfo ='{$valtems['valueinfo']}',tips='{$valtems['tips']}'";
		}
		$sql_info = var_export($sql, true);
		$info .= "\n\$sql = {$sql_info};\n\$no='{$_M['form']['no']}';\n\$devices='{$tem['devices']}';";
		$str = file_get_contents(PATH_OWN_FILE.'file/install.class.php');
		$str = str_replace('/*<!--sql-->*/', $info, $str);
		file_put_contents(PATH_WEB.$file, $str);
		
		turnover("{$_M[url][own_form]}a=doset&no={$_M['form']['no']}&pos={$_M['form']['pos']}", '操作成功');
	}
	
}

# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>