<?php
defined('IN_MET') or exit ('No permission');

load::sys_class('admin');
load::sys_func('file');

class uninstall extends admin {
    private $no;
    private $m_name;

    public function __construct() {
        global $_M;
        parent::__construct();
        $this->no       = $_M['form']['no'];
        $this->m_name   = 'met_wechat';
    }
    public function dodel() {
        global $_M;

        //卸载应用数据
        $this->delapp();

        //删除配置数据和文件
        $config = $this->config_list('metinfo');
        if ($config['wechat_dir']) {
            deldir('../'.$config['wechat_dir']);
        }
        //删除自定义数据表
        $this->deltable('nwechat_menu|nwechat_keywords|nwechat_reply|nwechat_news|nwechat_msg_tmp|nwechat_user|nwechat_log_points');

        // //删除应用文件
        // $this->delfile();
    }
    /**
     * 以下内容是所有应用可通用的安装方法！！！
     * 不用往下看了！！
     */
    private function config_list($m_num = 'm1595') {
        global $_M;
        $config = DB::get_one("SELECT * FROM {$_M['table']['lc_config']} WHERE name = '{$this->m_name}' AND m_num = '{$m_num}'");
        $config_data = json_decode(urldecode($config['value']), true);
        DB::query("DELETE FROM {$_M['table']['lc_config']} WHERE name = '{$this->m_name}' AND m_num = '{$m_num}'");
        return $config_data;
    }
    private function delapp() {
        global $_M;
        //删除栏目接口表
        $this->delsql('ifcolumn');
        //删除应用生成文件所调用事件的信息表
        $this->delsql('ifcolumn_addfile');
        //删除会员侧导航信息表
        $this->delsql('ifmember_left');
        //删除应用插件表
        $this->delsql('app_plugin');
        //删除网站后台栏目信息表
        $where  = "field='{$this->no}'";
        $this->delsql('admin_column',$where);
        //删除网站栏目信息表
        $where  = "module='{$this->no}'";
        $this->delsql('column',$where);
        //删除语言表
        $where  = "app='{$this->no}'";
        $this->delsql('language',$where);
        //删除应用注册表
        $this->delsql('applist');
    }
    //删除应用文件夹
    private function delfile() {
        if($this->file){
            deldir('../'.$this->file);
        }
        if($this->m_name){
            deldir('../app/app/'.$this->m_name);
        }
    }
    public function deltable($name) {
        global $_M;
        $name_arr = explode("|", $name);
        foreach ($name_arr as $key => $val) {
            if ($val) {
                $table = $_M['config']['tablepre'].$val;
                DB::query("DROP TABLE {$table}");
            }
        }
        del_table($name);
    }
    //公共删除数据
    private function delsql($tname,$where = '') {
        global $_M;
        $table  = $_M['table'][$tname];
        if(!$where){
            $where  = "no='{$this->no}'";
        }
        DB::query("DELETE FROM {$table} WHERE {$where}");
    }
}
?>