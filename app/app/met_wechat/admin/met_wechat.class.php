<?php

defined('IN_MET') or exit('No permission');
load::sys_class('admin');
load::sys_func('file');

class met_wechat extends admin {
    public function __construct() {
        global $_M;
        parent::__construct();
        require_once PATH_APP_FILE.'lang/lang.php';
        $this->L_cn = $_L['lang']['cn'];
        $this->LW_BASE = load::own_class('LW_BASE', 'new');
        $this->LW_REPLY = load::own_class('LW_REPLY', 'new');
        $this->LC = load::own_class('LC', 'new');
        nav::set_nav(1, "公众号设置", $_M['url']['own_form'].'a=doindex');
        nav::set_nav(2, "其它设置", $_M['url']['own_form'].'a=doconfig');
        nav::set_nav(3, "菜单设置", $_M['url']['own_form'].'a=domenu');
        nav::set_nav(4, "关键词管理", $_M['url']['own_form'].'a=dokeywords');
        nav::set_nav(5, "回复管理", $_M['url']['own_form'].'a=doreply');
        nav::set_nav(6, "图文素材", $_M['url']['own_form'].'a=donews');
        nav::set_nav(7, "用户列表", $_M['url']['own_form'].'a=douser');
        nav::set_nav(8, "积分详情", $_M['url']['own_form'].'a=dopoints');
        nav::set_nav(9, "使用帮助", $_M['url']['own_form'].'a=dohelp');
    }
    /**
     * 公众号设置
     */
    public function doindex() {
        global $_M;
        nav::select_nav(1);
        $action = $_M['form']['action'];
        $config = $this->LW_BASE->config_list('get', '', 'met_wechat', 'metinfo');

        switch ($action) {
            case 'save':
                $this->LW_BASE->config_list('save', $_M['form']['LW'], 'met_wechat', 'metinfo');
                $oldfile = PATH_WEB.$config['wechat_dir'];
                $newfile = PATH_WEB.$_M['form']['LW']['wechat_dir'];
                if ($oldfile != $newfile) {
                   movedir($oldfile, $newfile);
                }
                turnover("{$_M['url']['own_form']}a=doindex","保存成功");
                break;

            default:
                $link[0]['name'] = '积分详情';
                $link[0]['url'] = $this->LW_BASE->self_url('jf');
                $link[1]['name'] = '扫码关注页面';
                $link[1]['url'] = $this->LW_BASE->self_url('qrcode');
                $token_url = $this->LW_BASE->self_url('met_wechat', 'met_wechat', 'doent');
                require_once $this -> template('own/index');
                break;
        }
    }
    /**
     * 其它设置
     */
    public function doconfig() {
        global $_M;
        nav::select_nav(2);
        $action = $_M['form']['action'];
        $table_reply = $_M['table']['nwechat_reply'];
        $table_msg_tmp = $_M['table']['nwechat_msg_tmp'];

        switch ($action) {
            case 'save':
                $this->LW_BASE->config_list('save', $_M['form']['LW'], 'met_wechat', 'metinfo');
                turnover("{$_M['url']['own_form']}a=doconfig","保存成功");
                break;
            default:
                $sub_data = DB::get_one("SELECT * FROM {$table_reply} WHERE id = '1'");
                $auto_data = DB::get_one("SELECT * FROM {$table_reply} WHERE id = '2'");
                $msg_tmp = DB::get_all("SELECT * FROM {$table_msg_tmp} WHERE id != 0 ORDER BY id ASC");
                $config = $this->LW_BASE->config_list('get', '', 'met_wechat', 'metinfo');
                require_once $this -> template('own/config');
                break;
        }
    }
    /**
     * 菜单设置
     */
    public function domenu() {
        global $_M;
        nav::select_nav(3);
        $action = $_M['form']['action'];
        $table_menu = $_M['table']['nwechat_menu'];

        switch ($action) {
            case 'ajax_table':
                $table = load::sys_class('tabledata', 'new');
                $array = $table->getdata($table_menu, '*', "topid=0", "order_no ASC");

                foreach($array as $key => $val){
                    $menudata = DB::get_all("SELECT * FROM {$table_menu} WHERE topid = ".$val[id]." ORDER BY order_no ASC");
                    $select = $menudata?"一级菜单":"
                            <select name='LC[{$val[id]}][type]' data-checked='{$val[type]}'>
                                <option value='click'>关键词</option>
                                <option value='view'>URL链接</option>
                                <option value='miniprogram'>小程序</option>
                            </select>
                        ";
                    $value = $menudata?"不可填值":"
                            <input type='text' name='LC[{$val[id]}][value]' class='ui-input' value='{$val[value]}'>
                        ";
                    $list = array();
                    $list[] = "<input name='id' type='checkbox' value='{$val[id]}'>";
                    $list[] = "
                            <div class='input-group'>
                                <span class='input-group-addon' style='background:#565555;color:#fff'>一级菜单</span>
                                <input type='text' name='LC[{$val[id]}][order_no]' class='ui-input met-center' value='{$val[order_no]}'>
                            </div>
                        ";
                    $list[] = "<input type='text' name='LC[{$val[id]}][name]' class='ui-input' value='".urldecode($val[name])."'>";
                    $list[] = $select;
                    $list[] = $value;
                    $list[] = '
                            <div class="btn-group">
                                <a href="javascript:0;" class="btn btn-info" data-href="'.$_M[url][own_form].'a=domenu&action=ajax_add_tr&topid='.$val[id].'" onclick="L_get_ajax($(this));">添加二级菜单</a>
                                <a href="'.$_M[url][own_form].'a=domenu&action=table&submit_type=del&allid='.$val[id].'" class="btn btn-danger" data-confirm="确认删除？">删除</a>
                                <script>function L_get_ajax(obj) {var url = obj.attr("data-href");obj.html("请稍后...");$.ajax({type: "get",url: url,dataType: "html",success: function(data){obj.parent("div").parent("td").parent("tr").after(data);obj.html("添加二级菜单");},error: function (XMLHttpRequest, textStatus, errorThrown) {}});}</script>
                            </div>
                        ';
                    $rarray[] = $list;
                    foreach($menudata as $key => $val2){
                        $list = array();
                        $list[] = '<input name="id" type="checkbox" value="'.$val2[id].'">';
                        $list[] = '<div class="input-group"><span class="input-group-addon"><img src="'.$_M[url][site_admin].'templates/met/images/bg_columnx.gif"> 二级菜单</span><input type="text" name="LC['.$val2[id].'][order_no]" class="ui-input met-center" value="'.$val2[order_no].'"></div>';
                        $list[] = '<input type="text" name="LC['.$val2[id].'][name]" class="ui-input" value="'.urldecode($val2[name]).'">';
                        $list[] = '<select name="LC['.$val2[id].'][type]" data-checked="'.$val2[type].'"><option value="click">关键词</option><option value="view">URL链接</option><option value="miniprogram">小程序</option></select>';
                        $list[] = '<input type="text" name="LC['.$val2[id].'][value]" class="ui-input" value="'.$val2[value].'">';
                        $list[] = '
                            <div class="btn-group">
                                <a href="'.$_M[url][own_form].'a=domenu&action=table&submit_type=del&allid='.$val2[id].'" class="btn btn-danger" data-confirm="确认删除？">删除</a>
                            </div>
                        ';
                        $rarray[] = $list;
                    }
                }

                $table->rdata($rarray);
                break;

            case 'table':
                $this->LC->save_table($table_menu);
                turnover("{$_M[url][own_form]}a=domenu","");
                break;

            case 'ajax_add_tr':
                if ($_M[form][topid]) {
                    $id = 'new-'.time();
                    $html = '<tr class="even newlist">'
                          . '<td><input name="id" type="checkbox" value="'.$id.'" checked><input name="LC['.$id.'][topid]" type="hidden" value="'.$_M[form][topid].'"></td>'
                          . '<td><div class="input-group"><span class="input-group-addon"><img src="'.$_M[url][site_admin].'templates/met/images/bg_columnx.gif"> 二级菜单</span><input type="text" name="LC['.$id.'][order_no]" class="ui-input met-center" value=""></div></td>'
                          . '<td><input type="text" name="LC['.$id.'][name]" class="ui-input" value=""></td>'
                          . '<td><select name="LC['.$id.'][type]"><option value="click">关键词</option><option value="view">URL链接</option><option value="miniprogram">小程序</option></select></td>'
                          . '<td><input type="text" name="LC['.$id.'][value]" class="ui-input" value=""></td>'
                          . '<td><a href="" class="btn btn-warning delet">撤销</a></td>'
                          . '</tr>';
                } else {
                    $id = 'new-'.$_M[form][ai];
                    $menu_counter = DB::counter($table_menu, "WHERE topid = 0");
                    if ($menu_counter > '2' || ($menu_counter+$_M[form][ai]) > '2') {
                        echo "<script>alert('最多只能设置3个一级菜单');</script>";
                        die;
                    }
                    $html = '<tr class="even newlist">'
                          . '<td><input name="id" type="checkbox" value="'.$id.'" checked></td>'
                          . '<td><div class="input-group"><span class="input-group-addon">一级菜单</span><input type="text" name="LC['.$id.'][order_no]" class="ui-input met-center" value=""></div></td>'
                          . '<td><input type="text" name="LC['.$id.'][name]" class="ui-input" value=""></td>'
                          . '<td><select name="LC['.$id.'][type]"><option value="">一级菜单</option><option value="click">关键词</option><option value="view">URL链接</option><option value="miniprogram">小程序</option></select></td>'
                          . '<td><input type="text" name="LC['.$id.'][value]" class="ui-input" value=""></td>'
                          . '<td><a href="" class="btn btn-warning delet">撤销</a></td>'
                          . '</tr>';
                }
                echo $html;
                break;

            case 'fabu':
                $menudata = DB::get_all("SELECT * FROM {$table_menu} WHERE topid = 0 ORDER BY order_no ASC");
                $menu = array();
                $i=0;
                foreach ($menudata as $key => $val) {
                    $val['name'] = urlencode($val['name']);
                    $menu2data = DB::get_all("SELECT * FROM {$table_menu} WHERE topid = ".$val[id]." ORDER BY order_no ASC");
                    if ($menu2data) {
                        $menu['button'][$i]['name'] = $val['name'];
                        $k=0;
                        foreach ($menu2data as $key => $val2) {
                            $val2['name'] = urlencode($val2['name']);
                            $menu['button'][$i]['sub_button'][$k]['type'] = $val2['type'];
                            $menu['button'][$i]['sub_button'][$k]['name'] = $val2['name'];
                            switch ($val2['type']) {
                                case 'click':
                                    $menu['button'][$i]['sub_button'][$k]['key'] = urlencode($val2['value']);
                                    break;
                                case 'view':
                                    $menu['button'][$i]['sub_button'][$k]['url'] = $val2['value'];
                                    break;
                                case 'miniprogram':
                                	$minidata = explode("||", $val2['value']);
                                	$menu['button'][$i]['sub_button'][$k]['url'] = $minidata[0];
                                	$menu['button'][$i]['sub_button'][$k]['appid'] = $minidata[1];
                                	$menu['button'][$i]['sub_button'][$k]['pagepath'] = $minidata[2];
                                	break;
                                default:
                                    $menu['button'][$i]['sub_button'][$k]['url'] = $val2['value'];
                                    break;
                            }
                            $k++;
                        }
                    } else {
                        $menu['button'][$i]['type'] = $val['type'];
                        $menu['button'][$i]['name'] = $val['name'];
                        switch ($val['type']) {
                            case 'click':
                                $menu['button'][$i]['key'] = urlencode($val['value']);
                                break;
                            case 'view':
                                $menu['button'][$i]['url'] = $val['value'];
                                break;
                            default:
                                $menu['button'][$i]['url'] = $val['value'];
                                break;
                        }
                    }
                    $i++;
                }
                $data = $this->LW_BASE->post_menu($menu);
                if ($data['errcode'] == '0') {
                    turnover("{$_M[url][own_form]}a=domenu");
                } else {
                    turnover("{$_M[url][own_form]}a=domenu", '发布失败，错误码：'.$data[errcode]);
                }
                break;
            case 'getmenu':
                $data = $this->LW_BASE->default_menu();
                if ($data == '1') {
                    turnover("{$_M[url][own_form]}a=domenu");
                } else {
                    turnover("{$_M[url][own_form]}a=domenu", '获取失败，错误码：'.$data[errcode]);
                }
                break;

            default:
                require_once $this -> template('own/menu');
                break;
        }
    }
    /**
     * 关键词管理--列表
     */
    public function dokeywords() {
        global $_M;
        nav::select_nav(4);
        $action = $_M['form']['action'];
        $table_keywords = $_M['table']['nwechat_keywords'];
        $table_reply = $_M['table']['nwechat_reply'];

        switch ($action) {
            case 'ajax_table':
                $key1 = $_M['form']['search_word'];
                $search = $key1?" AND word LIKE '%".$key1."%' ":"";
                $table = load::sys_class('tabledata', 'new');
                $array = $table->getdata($table_keywords, '*', "is_own!=1".$search, "id DESC");

                foreach($array as $key => $val){
                    $val['type'] = $val['type'] == '1'?'精确匹配':'模糊匹配';
                    $list = array();
                    $list[] = '<input name="id" type="checkbox" value="'.$val[id].'">';
                    $list[] = $val['type'];
                    $list[] = $val['replyid'];
                    $list[] = $val['word'];
                    $list[] = '
                            <div class="btn-group">
                                <a href="'.$_M[url][own_form].'a=dokeywords&action=edit&id='.$val[id].'" class="btn btn-primary">编辑</a>
                                <a href="'.$_M[url][own_form].'a=dokeywords&action=save_table&submit_type=del&allid='.$val[id].'" class="btn btn-danger" data-confirm="确认删除？">删除</a>
                            </div>
                        ';
                    $rarray[] = $list;
                }

                $table->rdata($rarray);
                break;

            case 'edit':
                $id = $_M['form']['id'];
                $word_data = DB::get_one("SELECT * FROM {$table_keywords} WHERE id = {$id} AND is_own != 1");
                require_once $this -> template('own/keywords_edit');
                break;
            case 'ajax_table_reply':
                $key1 = $_M['form']['search_reply'];
                $search = $key1?" AND name LIKE '%".$key1."%' ":"";
                $table = load::sys_class('tabledata', 'new');
                $array = $table->getdata($table_reply, '*', "isown!=1".$search, "id DESC");

                foreach($array as $key => $val){
                    $list = array();
                    $list[] = $val['id'];
                    $list[] = $this->L_cn[$val['type']];
                    $list[] = $val['name'];
                    $list[] = "
                        <a href='javascript:;' onclick='L_add_reply(".$val[id].");' class='edit'>选择</a>
                        <script>
                            function L_add_reply (id) {
                                var old_list = $('#reply_list').val();
                                var new_list = old_list+'|'+id;
                                $('#reply_list').val(new_list);
                                $('.replys .tags_tj').before('<li class=tags_list><span>'+id+'</span><a></a></li>');
                            }
                        </script>";
                    $rarray[] = $list;
                }

                $table->rdata($rarray);
                break;
            case 'save':
                $id = $_M['form']['keyid'];
                $type = $_M['form']['type'];
                $replyid = $_M['form']['replyid'];
                $word = $_M['form']['word'];
                $level = $_M['form']['level'];
                if ($id) {
                    $query = "UPDATE {$table_keywords} SET
                        replyid     = '{$replyid}',
                        word        = '{$word}',
                        type        = '{$type}',
                        level       = '{$level}'
                        WHERE id = '{$id}'
                    ";
                } else {
                    $query = "INSERT INTO {$table_keywords} SET
                        id          = NULL,
                        replyid     = '{$replyid}',
                        word        = '{$word}',
                        type        = '{$type}',
                        level       = '{$level}'
                    ";
                }
                DB::query($query);
                turnover("{$_M[url][own_form]}a=dokeywords");
                break;
            case 'save_table':
                $this->LC->save_table($table_keywords);
                turnover("{$_M[url][own_form]}a=dokeywords","");
                break;

            default:
                require_once $this -> template('own/keywords');
                break;
        }
    }
    /**
     * 关键词管理--内容
     */
    public function doreply() {
        global $_M;
        nav::select_nav(5);
        $action = $_M['form']['action'];
        $table_keywords = $_M['table']['nwechat_keywords'];
        $table_reply = $_M['table']['nwechat_reply'];

        switch ($action) {
            case 'ajax_table':
                $key1 = $_M['form']['search_name'];
                $search = $key1?" AND name LIKE '%".$key1."%' ":"";
                $table = load::sys_class('tabledata', 'new');
                $array = $table->getdata($table_reply, '*', "isown!=1".$search, "id DESC");

                foreach($array as $key => $val){
                    $list = array();
                    $list[] = '<input name="id" type="checkbox" value="'.$val[id].'">';
                    $list[] = $val['id'];
                    $list[] = $this->L_cn[$val['type']];
                    $list[] = $val['name'];
                    $list[] = '
                            <div class="btn-group">
                                <a href="'.$_M[url][own_form].'a=doreply&action=edit&id='.$val[id].'" class="btn btn-primary">编辑</a>
                                <a href="'.$_M[url][own_form].'a=doreply&action=save_table&submit_type=del&allid='.$val[id].'" class="btn btn-danger" data-confirm="确认删除？">删除</a>
                            </div>
                        ';
                    $rarray[] = $list;
                }

                $table->rdata($rarray);
                break;
            case 'save_table':
                $this->LC->save_table($table_reply);
                turnover("{$_M[url][own_form]}a=doreply","");
                break;
            case 'edit':
                $id = $_M['form']['id'];
                $data = DB::get_one("SELECT * FROM {$table_reply} WHERE id = {$id} AND isown != 1");
                $columns = explode("-", $data[columns]);
                require_once $this -> template('own/reply_edit');
                break;
            case 'ajax_column':
                $column_c = array(
                    "2",
                    "3",
                    "4",
                    "5"
                );
                $array = column_sorting(2);//获取栏目数组
                $metinfo = array();
                $i=0;
                $metinfo['citylist'][$i]['p']='请选择';
                foreach($array['class1'] as $key=>$val){ //一级级栏目
                    if (in_array($val[module], $column_c)) {
                        $i++;
                        $metinfo['citylist'][$i]['p']['name']=$val[name];
                        $metinfo['citylist'][$i]['p']['value']=$val[module]."-".$val[id];
                        if(count($array['class2'][$val[id]])){ //二级栏目
                            $k=0;
                            $metinfo['citylist'][$i]['c'][$k]['n']['name']='二级栏目所有内容';
                            $metinfo['citylist'][$i]['c'][$k]['n']['value']=' ';
                            foreach($array['class2'][$val[id]] as $key=>$val2){
                                $k++;
                                $metinfo['citylist'][$i]['c'][$k]['n']['name']=$val2[name];
                                $metinfo['citylist'][$i]['c'][$k]['n']['value']=$val2[id];
                                if(count($array['class3'][$val2[id]])){ //三级栏目
                                    $j=0;
                                    $metinfo['citylist'][$i]['c'][$k]['a'][$j]['s']['name']='三级栏目所有内容';
                                    $metinfo['citylist'][$i]['c'][$k]['a'][$j]['s']['value']=' ';
                                    foreach($array['class3'][$val2[id]] as $key=>$val3){
                                        $j++;
                                        $metinfo['citylist'][$i]['c'][$k]['a'][$j]['s']['name']=$val3[name];
                                        $metinfo['citylist'][$i]['c'][$k]['a'][$j]['s']['value']=$val3[id];
                                    }
                                }
                            }
                        }
                    }
                }
                echo json_encode($metinfo);

                break;
            case 'save':
                $id = $_M['form']['replyid'];
                $name = $_M['form']['name'];
                $msgtype = $_M['form']['msgtype'];
                $level = $_M['form']['level'];
                //回复内容更新
                switch ($msgtype) {
                    case 'text':
                        $text = urlencode($_M['form']['text']);
                        $text = str_replace("%5C", "", $text);
                        if ($id) {
                            $query = "UPDATE {$table_reply} SET
                                name        = '{$name}',
                                type        = '{$msgtype}',
                                text        = '{$text}'
                                WHERE id = '{$id}'
                            ";
                        } else {
                            $query = "INSERT INTO {$table_reply} SET
                                id          = NULL,
                                name        = '{$name}',
                                type        = '{$msgtype}',
                                text        = '{$text}'
                            ";
                        }
                        break;
                    case 'image':
                        $filename = str_replace("../", "", $_M['form']['image_url']);
                        $filename = str_replace("/", "\\", $filename);
                        if ($_M['form']['image_url_old'] == $_M['form']['image_url']) {
                            $data['media_id'] = $_M['form']['image_mediaid'];
                        } else {
                            $data = $this->LW_BASE->add_material($filename, 'image', $_M['form']['material']);
                        }
                        if ($id) {
                            $query = "UPDATE {$table_reply} SET
                                name        = '{$name}',
                                type        = '{$msgtype}',
                                url         = '{$_M['form']['image_url']}',
                                mediaid     = '{$data['media_id']}'
                                WHERE id = '{$id}'
                            ";
                        } else {
                            $query = "INSERT INTO {$table_reply} SET
                                id          = NULL,
                                name        = '{$name}',
                                type        = '{$msgtype}',
                                url         = '{$_M['form']['image_url']}',
                                mediaid     = '{$data['media_id']}'
                            ";
                        }
                        break;
                     case 'voice':
                        $filename = str_replace("../", "", $_M['form']['voice_url']);
                        $filename = str_replace("/", "\\", $filename);
                        if ($_M['form']['voice_url_old'] == $_M['form']['voice_url']) {
                            $data['media_id'] = $_M['form']['voice_mediaid'];
                        } else {
                            $data = $this->LW_BASE->add_material($filename, 'voice', $_M['form']['material']);
                        }
                        if ($id) {
                            $query = "UPDATE {$table_reply} SET
                                name        = '{$name}',
                                type        = '{$msgtype}',
                                url         = '{$_M['form']['voice_url']}',
                                mediaid     = '{$data['media_id']}'
                                WHERE id = '{$id}'
                            ";
                        } else {
                            $query = "INSERT INTO {$table_reply} SET
                                id          = NULL,
                                name        = '{$name}',
                                type        = '{$msgtype}',
                                url         = '{$_M['form']['voice_url']}',
                                mediaid     = '{$data['media_id']}'
                            ";
                        }
                        break;
                    case 'news':
                        $msg_list = $_M['form']['msg_list'];
                        if ($id) {
                            $query = "UPDATE {$table_reply} SET
                                name        = '{$name}',
                                type        = '{$msgtype}',
                                msg_list    = '{$msg_list}'
                                WHERE id = '{$id}'
                            ";
                        } else {
                            $query = "INSERT INTO {$table_reply} SET
                                id          = NULL,
                                name        = '{$name}',
                                type        = '{$msgtype}',
                                msg_list    = '{$msg_list}'
                            ";
                        }
                        break;
                    case 'column':
                        $column_1 = $_M['form']['column_1'];
                        $column_2 = $_M['form']['column_2'];
                        $column_3 = $_M['form']['column_3'];
                        $column_4 = $_M['form']['column_4'];
                        $column_5 = $_M['form']['column_5'];
                        if ($id) {
                            $query = "UPDATE {$table_reply} SET
                                name        = '{$name}',
                                type        = '{$msgtype}',
                                columns     = '{$column_1}-{$column_2}-{$column_3}-{$column_4}-{$column_5}'
                                WHERE id = '{$id}'
                            ";
                        } else {
                            $query = "INSERT INTO {$table_reply} SET
                                id          = NULL,
                                name        = '{$name}',
                                type        = '{$msgtype}',
                                columns     = '{$column_1}-{$column_2}-{$column_3}-{$column_4}-{$column_5}'
                            ";
                        }
                        break;

                    default:
                        # code...
                        break;
                }
                DB::query($query);
                turnover("{$_M[url][own_form]}a=doreply");
                break;
            default:
                require_once $this -> template('own/reply');
                break;
        }
    }
    /**
     * 图文素材
     */
    public function donews() {
        global $_M;
        nav::select_nav(6);
        $action = $_M['form']['action'];
        $table_news = $_M['table']['nwechat_news'];
        $news_url = $this->LW_BASE->self_url('met_wechat', 'met_wechat', 'domsg_tmp');

        switch ($action) {
            case 'ajax_table':
                $key1 = $_M['form']['search_title'];
                $search = $key1?"title LIKE '%".$key1."%'":"id!=0";
                $table = load::sys_class('tabledata', 'new');
                $array = $table->getdata($table_news, '*', $search, "id DESC");

                foreach($array as $key => $val){
                    $list = array();
                    $list[] = '<input name="id" type="checkbox" value="'.$val[id].'">';
                    $list[] = '<img src="'.$val[img].'" width="100%" height="50px">';
                    $list[] = '<a href="'.$this->LW_BASE->self_url('met_wechat', 'met_wechat', 'domsg_tmp').'&action=detail&id='.$val[id].'" target="_blank">'.$val['title'].'</a>';
                    $list[] = $val['all_read'];
                    $list[] = date("Y-m-d H:i:s", $val[addtime]);
                    $list[] = '
                            <div class="btn-group">
                                <a href="'.$_M[url][own_form].'a=donews&action=edit&id='.$val[id].'" class="btn btn-primary">编辑</a>
                                <a href="'.$_M[url][own_form].'a=donews&action=del&allid='.$val[id].'" class="btn btn-danger" data-confirm="确认删除？">删除</a>
                            </div>
                        ';
                    $rarray[] = $list;
                }
                $table->rdata($rarray);
                break;
            case 'edit':
                $id = $_M['form']['id'];
                if ($id) {
                    $news_data = DB::get_one("SELECT * FROM {$table_news} WHERE id = {$id}");
                }
                require_once $this -> template('own/news_edit');
                break;
            case 'save':
                $id = $_M['form']['id'];
                $title = $_M['form']['title'];
                $img = $_M['form']['img'];
                $isshow = $_M['form']['isshow'];
                $description = $_M['form']['description'];
                $content = $_POST['content'];
                $url = $_M['form']['url'];
                $link = $_M['form']['link'];
                $time = time();
                if(!$description){
                    $description=strip_tags($content);
                    $description=str_replace("\n", '', $description);
                    $description=str_replace("\r", '', $description);
                    $description=str_replace("\t", '', $description);
                    $description=str_replace(" ", '', $description);
                    $description=str_replace("　", '', $description);
                    $description=str_replace("&nbsp;", '', $description);
                    $description=mb_substr($description,0,200,'utf-8');
                }
                if ($id) {
                    $query = "UPDATE {$table_news} SET
                        title       = '{$title}',
                        img         = '{$img}',
                        isshow      = '{$isshow}',
                        description = '{$description}',
                        content     = '{$content}',
                        url         = '{$url}',
                        link        = '{$link}'
                        WHERE id = '{$id}'
                    ";
                } else {
                    $query = "INSERT INTO {$table_news} SET
                        id          = NULL,
                        title       = '{$title}',
                        img         = '{$img}',
                        isshow      = '{$isshow}',
                        description = '{$description}',
                        content     = '{$content}',
                        url         = '{$url}',
                        link        = '{$link}',
                        addtime     = '{$time}'
                    ";
                }
                DB::query($query);
                turnover("{$_M[url][own_form]}a=donews");
                break;
            case 'del':
                $idlist = explode(",",$_M['form']['allid']) ;
                foreach($idlist as $val){
                    if (is_number($val)) {
                        $query = "DELETE FROM {$table_news} WHERE id = ".$val;
                    }
                    DB::query($query);
                }
                turnover("{$_M[url][own_form]}a=donews");
                break;
            case 'ajax_table_reply':
                $key1 = $_M['form']['search_reply'];
                $search = $key1?"title LIKE '%".$key1."%'":"id!=0";
                $table = load::sys_class('tabledata', 'new');
                $array = $table->getdata($table_news, '*', $search, "id DESC");

                foreach($array as $key => $val){
                    $list = array();
                    $list[] = $val[id].'<input type="hidden" value="'.$val[id].'" name="id" checked>';
                    $list[] = '<img src="'.$val[img].'" width="100%" height="50px">';
                    $list[] = '<a href="'.$this->LW_BASE->self_url('met_wechat', 'met_wechat', 'domsg_tmp').'&action=detail&id='.$val[id].'" target="_blank">'.$val['title'].'</a>';
                    $list[] = $val['all_read'];
                    $list[] = date("Y-m-d H:i:s", $val[addtime]);
                    $list[] = "<a href='javascript:;' onclick='L_add_msg(".$val[id].");' class='edit'>选择</a><script>function L_add_msg (id) {var old_msg_list = $('#msg_list').val();var new_msg_list = old_msg_list+'|'+id;$('#msg_list').val(new_msg_list);$('.news .tags_tj').before('<li class=tags_list><span>'+id+'</span><a></a></li>');}</script>";
                    $rarray[] = $list;
                }
                $table->rdata($rarray);
                break;

            default:
                require_once $this -> template('own/news');
                break;
        }
    }
    /**
     * 用户列表
     */
    public function douser () {
        global $_M;
        nav::select_nav(7);
        $action = $_M['form']['action'];
        $table_wx_user = $_M['table']['nwechat_user'];
        $table_user = $_M['table']['user'];

        switch ($action) {
            case 'ajax_table':
                $openid = $_M['form']['openid'];
                $nickname = urlencode($openid);
                $search = $openid?"(nickname like '%{$nickname}%' OR openid = '{$openid}' OR id = '{$openid}') ":'id != 0 ';
                //$search .= "AND subscribe != ''";
                $table = load::sys_class('tabledata', 'new');
                $array = $table->getdata($table_wx_user, '*', $search, "id DESC");

                foreach($array as $key => $val){
                	if (!$val['nickname'] && !$val['subscribe']) {
                		$username = '【未关注】';
                	} else {
                		$username = $val['nickname']?urldecode($val['nickname']):'【近期未使用】';
                	}
                    if ($val['sex'] == '0' || $val['sex'] == '') {
                        $val['sex'] = '未知';
                    } elseif ($val['sex'] == '1') {
                        $val['sex'] = '男';
                    } else {
                        $val['sex'] = '女';
                    }
                    if ($val['subscribe'] == '0' || $val['subscribe'] == '') {
                        $val['subscribe'] = '<a style="color:red">否</a>';
                    } else {
                        $val['subscribe'] = '是';
                    }
                    $val['headimgurl'] = $val['headimgurl']?'<img src="'.$val['headimgurl'].'" width="100%">':'';
                    $val['province'] = $val['province']?'-'.$val['province']:'';
                    $val['city'] = $val['city']?'-'.$val['city']:'';
                    $val['subscribe_time'] = date("m-d H:i", $val['subscribe_time']);
                    $list = array();
                    $list[] = '<input name="id" type="checkbox" value="'.$val[id].'"><input name="openid-'.$val[id].'" type="hidden" value="'.$val[openid].'">';
                    $list[] = $val[id];
                    $list[] = $val['headimgurl'];
                    $list[] = $username;
                    $list[] = '<input name="points-'.$val[id].'" type="text" class="ui-input" value="'.$val['points'].'">';
                    $list[] = $val['openid'];
                    $list[] = $val['sex'];
                    $list[] = $val['subscribe'];
                    $list[] = $val['country'].$val['province'].$val['city'];
                    $list[] = $val['subscribe_time'];
                    $list[] = '
                            <div class="btn-group">
                                <a href="'.$_M[url][own_form].'a=dopoints&openid='.$val[openid].'" class="btn btn-primary">查看积分详情</a>
                                <a href="'.$_M[url][own_form].'a=douser&action=edit&submit_type=del&allid='.$val[id].'" class="btn btn-danger" data-confirm="确认删除？">删除</a>
                            </div>
                        ';
                    $rarray[] = $list;
                }
                $table->rdata($rarray);
                break;
            case 'edit':
                $idlist = explode(",", $_M['form']['allid']);
                switch ($_M['form']['submit_type']) {
                    case 'del':
                        foreach($idlist as $val){
                            $query = "DELETE FROM {$table_wx_user} WHERE id = {$val}";
                            DB::query($query);
                        }
                        break;
                    case 'points':
                        foreach($idlist as $val){
                            $points = $_M['form']['points-'.$val];
                            $openid = $_M['form']['openid-'.$val];
                            if(is_number($val)){
                                $this->LW_BASE->user_points($openid, 'new', $points, '管理员设置');
                            }
                        }
                        break;
                }
                turnover("{$_M[url][own_form]}a=douser", "");
                break;
            default:
                require_once $this -> template('own/user');
                break;
        }
    }
    public function doget_all_user() {
        global $_M;
        $page = $_M['form']['page']?$_M['form']['page']:1;
        $next_openid = $_M['form']['next_openid']?$_M['form']['next_openid']:'';
        $r = $this->LW_BASE->get_all_user_openid($page, $next_openid);
        echo $this->LW_BASE->json_encode_ex($r);
    }
    public function dopoints() {
        global $_M;
        nav::select_nav(8);
        $action = $_M['form']['action'];
        $table_points = $_M['table']['nwechat_log_points'];

        switch ($action) {
            case 'ajax_table':
                $openid = $_M['form']['openid'];
                $search = $openid?"openid = '{$openid}'":"id!=0";
                $search .= $_M['form']['type']?" AND type = '{$_M[form][type]}'":"";
                $table = load::sys_class('tabledata', 'new');
                $array = $table->getdata($table_points, '*', $search, "id DESC");
                foreach($array as $key => $val){
                    $val['date'] = date("Y-m-d H:i:s", $val['date']);
                    $val['points'] = $val['points']>0?$val['points']:'-';
                    switch ($val['type']) {
                        case 'add':
                            $type = '<span style="color:green">增加</span>';
                            break;
                        case 'del':
                            $type = '<span style="color:red">减少</span>';
                            break;
                        case 'new':
                            $type = '<span style="color:gray">设置</span>';
                            break;
                    }
                    $list = array();
                    $list[] = $val['openid'];
                    $list[] = $type;
                    $list[] = $val['points'];
                    $list[] = $val['text'];
                    $list[] = $val['date'];
                    $rarray[] = $list;
                }
                $table->rdata($rarray);
                break;
            default:
                require_once $this -> template('own/points');
                break;
        }
    }
    public function dohelp() {
        global $_M;
        nav::select_nav(9);
        $url = "http://blog.luckymoke.cn/myapp/met_wechat.html";
        require_once $this -> template('own/help');
    }
}
?>