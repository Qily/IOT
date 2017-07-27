<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 

defined('IN_MET') or exit('No permission');

load::sys_class('admin');

class setedit extends admin {
	public function dosetedit() {
		global $_M;
		$query = "SELECT * FROM {$_M['table']['templates']} WHERE id='{$_M['form']['id']}'";
		$tem = DB::get_one($query);
		$tem['no_order_pre'] = $tem['no_order'] - 1;
		$query = "SELECT * FROM {$_M['table']['templates']} WHERE no='{$tem['no']}' and lang='{$tem['lang']}' and no_order={$tem['no_order_pre']}";
		$tempre = DB::get_one($query);
		$tem['preid'] = $tempre['id'];
		require $this->template('own/setedit');
	}
	public function doedit() {
		global $_M;
		$query = "SELECT * FROM {$_M['table']['templates']} WHERE id='{$_M['form']['id']}'";
		$tem = DB::get_one($query);
		$tem['no_order_pre'] = $tem['no_order'] - 1;
		$query = "SELECT * FROM {$_M['table']['templates']} WHERE no='{$tem['no']}' and lang='{$tem['lang']}' and no_order={$tem['no_order_pre']}";
		$tempre = DB::get_one($query);
		$no_order_now = $tem['no_order'];
		if ($tempre['id'] != $_M['form']['no_order'] || $_M['form']['id'] == -1) {
			$query = "SELECT * FROM {$_M['table']['templates']} where no='{$_M['form']['no']}' and lang='{$_M['form']['lang']}' ORDER BY no_order,id";
			$tems = DB::get_all($query);
			$no_order = 0;
			$update = 0;
			$item['id'] = 0;
			array_unshift($tems, $item);
			foreach($tems as $key=>$val){	
				if($val['id'] != $_M['form']['id']){
					$query = "UPDATE {$_M['table']['templates']} SET no_order='{$no_order}' WHERE id='{$val['id']}'";
					DB::query($query);
					if($val['id'] == $_M['form']['no_order']){
						$no_order_now = $no_order + 1;
						//$query = "UPDATE {$_M['table']['templates']} SET no_order='{$no_order_now}' WHERE id='{$_M['form']['id']}'";
						//DB::query($query);
						$no_order++;
					}
					$no_order++;
				}
			}	
		}
		$query = $_M['form']['id']>0 ? "UPDATE {$_M['table']['templates']} SET " : "INSERT INTO      {$_M['table']['templates']} SET ";
		$query .= "
						no       ='{$_M['form']['no']}',
						pos       ='{$_M['form']['pos']}',
						no_order  ='{$no_order_now}',
						type      ='{$_M['form']['type']}',
						style     ='{$_M['form']['style']}',
						selectd   ='{$_M['form']['selectd']}',
						name      ='{$_M['form']['name']}',
						value     ='{$_M['form']['value']}',
						valueinfo ='{$_M['form']['valueinfo']}',
						tips      ='{$_M['form']['tips']}',
						lang      ='{$_M['form']['lang']}'
		";
		if($_M['form']['id']>0)$query .= " where id='{$_M['form']['id']}'";
		DB::query($query);
		turnover("{$_M[url][own_name]}c=temset&a=doset&no={$_M['form']['no']}");
	}
	public function dosetin(){
		global $_M;
		$tem['no'] = $_M['form']['no'];
		$tem['id'] = -1;
		$tem['pos'] = 0;
		$tem['preid'] = 0;
		require $this->template('own/setedit');
	}
	public function dosetde(){
		global $_M;
		$query = "DELETE FROM {$_M['table']['templates']} WHERE id ={$_M['form']['id']}";
		DB::query($query);
		$query = "SELECT * FROM {$_M['table']['templates']} where no='{$_M['form']['no']}' AND lang='{$_M['form']['lang']}' ORDER BY no_order,id";
		$tems = DB::get_all($query);
		$no_order = 1;
		foreach($tems as $key=>$val){
			$query = "UPDATE {$_M['table']['templates']} SET no_order='{$no_order}' WHERE id='{$val['id']}'";
			DB::query($query);
			$no_order++;
		}
		turnover("{$_M[url][own_name]}c=temset&a=doset&no={$_M['form']['no']}");
	}	
	public function dotype() {
		$metinfo['citylist'][0]['p']='请选择';
		
		$metinfo['citylist'][1]['p']['name']='标题栏';
        $metinfo['citylist'][1]['p']['value']=1;
		$metinfo['citylist'][1]['c'][0]['n']['name']='分类设置';
		$metinfo['citylist'][1]['c'][0]['n']['value']=0;
		$metinfo['citylist'][1]['c'][1]['n']['name']='区块设置';
		$metinfo['citylist'][1]['c'][1]['n']['value']=1;
		
				
		$metinfo['citylist'][2]['p']['name']='简短文本';
        $metinfo['citylist'][2]['p']['value']=2;
		$metinfo['citylist'][2]['c'][0]['n']['name']='自定义';
		$metinfo['citylist'][2]['c'][0]['n']['value']=0;
		$metinfo['citylist'][2]['c'][1]['n']['name']='系统变量';
		$metinfo['citylist'][2]['c'][1]['n']['value']=1;
		
		$metinfo['citylist'][3]['p']['name']='文本输入框';
        $metinfo['citylist'][3]['p']['value']=3;
		$metinfo['citylist'][3]['c'][0]['n']['name']='自定义';
		$metinfo['citylist'][3]['c'][0]['n']['value']=0;
		$metinfo['citylist'][3]['c'][1]['n']['name']='系统变量';
		$metinfo['citylist'][3]['c'][1]['n']['value']=1;
		
		$metinfo['citylist'][4]['p']['name']='单选';
        $metinfo['citylist'][4]['p']['value']=4;

		$metinfo['citylist'][5]['p']['name']='多选';
        $metinfo['citylist'][5]['p']['value']=5;

		$metinfo['citylist'][6]['p']['name']='下拉';
        $metinfo['citylist'][6]['p']['value']=6;
		$metinfo['citylist'][6]['c'][0]['n']['name']='自定义';
		$metinfo['citylist'][6]['c'][0]['n']['value']=0;
		$metinfo['citylist'][6]['c'][1]['n']['name']='moudule小于6的一级栏目下拉';
		$metinfo['citylist'][6]['c'][1]['n']['value']=1;
		$metinfo['citylist'][6]['c'][2]['n']['name']='moudule小于7的三级栏目下拉';
		$metinfo['citylist'][6]['c'][2]['n']['value']=2;
		$metinfo['citylist'][6]['c'][3]['n']['name']='moudule为2,3,5的三级栏目下拉';
		$metinfo['citylist'][6]['c'][3]['n']['value']=3;
		$metinfo['citylist'][6]['c'][4]['n']['name']='三级栏目下拉，所有模块栏目';
		$metinfo['citylist'][6]['c'][4]['n']['value']=4;
		
		$metinfo['citylist'][7]['p']['name']='上传';
        $metinfo['citylist'][7]['p']['value']=7;
		$metinfo['citylist'][7]['c'][0]['n']['name']='自定义';
		$metinfo['citylist'][7]['c'][0]['n']['value']=0;
		$metinfo['citylist'][7]['c'][1]['n']['name']='系统变量';
		$metinfo['citylist'][7]['c'][1]['n']['value']=1;
		
		$metinfo['citylist'][8]['p']['name']='编辑器';
        $metinfo['citylist'][8]['p']['value']=8;
		$metinfo['citylist'][8]['c'][0]['n']['name']='自定义';
		$metinfo['citylist'][8]['c'][0]['n']['value']=0;
		$metinfo['citylist'][8]['c'][1]['n']['name']='系统变量';
		$metinfo['citylist'][8]['c'][1]['n']['value']=1;
		
		$metinfo['citylist'][9]['p']['name']='颜色选择器';
        $metinfo['citylist'][9]['p']['value']=9;

		$metinfo['citylist'][10]['p']['name']='日期';
        $metinfo['citylist'][10]['p']['value']=10;

		$metinfo['citylist'][11]['p']['name']='滑块';
        $metinfo['citylist'][11]['p']['value']=11;

		$metinfo['citylist'][12]['p']['name']='标签';
        $metinfo['citylist'][12]['p']['value']=12;

		$metinfo['citylist'][13]['p']['name']='特殊';
        $metinfo['citylist'][13]['p']['value']=13;		
		echo jsonencode($metinfo);
	}
	public function dopos() {
		global $_M;
		$metinfo['citylist'][0]['p']['name']='全局';
        $metinfo['citylist'][0]['p']['value']=0;
		$metinfo['citylist'][0]['c'][0]['n']['name']='首项';
		$metinfo['citylist'][0]['c'][0]['n']['value']=0;
		
		$metinfo['citylist'][1]['p']['name']='首页';
        $metinfo['citylist'][1]['p']['value']=1;
		$query = "SELECT * FROM {$_M['table']['templates']} WHERE no='{$_M['form']['no']}' AND lang='{$_M['form']['lang']}' AND pos = '0' ORDER BY no_order DESC,id DESC ";
		$max_order = DB::get_one($query);
		$max_order['id'] = $max_order['id'] ? $max_order['id'] : 0 ;
		$metinfo['citylist'][1]['c'][0]['n']['name']='首项';
		$metinfo['citylist'][1]['c'][0]['n']['value']= $max_order['id'];
		
		$metinfo['citylist'][2]['p']['name']='列表页';
        $metinfo['citylist'][2]['p']['value']=2;
		$query = "SELECT * FROM {$_M['table']['templates']} WHERE no='{$_M['form']['no']}' AND lang='{$_M['form']['lang']}' AND (pos='0' OR pos='1') ORDER BY no_order DESC,id DESC ";
		$max_order = DB::get_one($query);
		$max_order['id'] = $max_order['id'] ? $max_order['id'] : 0 ;
		$metinfo['citylist'][2]['c'][0]['n']['name']='首项';
		$metinfo['citylist'][2]['c'][0]['n']['value']=$max_order['id'];
		
		$metinfo['citylist'][3]['p']['name']='详细页';
        $metinfo['citylist'][3]['p']['value']=3;
		$query = "SELECT * FROM {$_M['table']['templates']} WHERE no='{$_M['form']['no']}' AND lang='{$_M['form']['lang']}' AND (pos='0' OR pos='1' OR pos='2') ORDER BY no_order DESC,id DESC ";
		$max_order = DB::get_one($query);
		$max_order['id'] = $max_order['id'] ? $max_order['id'] : 0 ;
		$metinfo['citylist'][3]['c'][0]['n']['name']='首项';
		$metinfo['citylist'][3]['c'][0]['n']['value']=$max_order['id'];
		
		$query = "SELECT * FROM {$_M['table']['templates']} where no='{$_M['form']['no']}' AND lang='{$_M['form']['lang']}' ORDER BY no_order,id";
		$tems = DB::get_all($query);
		foreach($tems as $key=>$val){
				$val['pos'] = $val['pos'];
				$tem = array();
				$tem['n']['name'] = $val['valueinfo'];
				$tem['n']['value'] = $val['id'];
				$metinfo['citylist'][$val['pos']]['c'][] = $tem;
		}
		echo jsonencode($metinfo);
	}
}

# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>