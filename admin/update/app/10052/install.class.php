<?php
defined('IN_MET') or exit ('No permission');

load::sys_class('admin');
load::sys_func('file');

class install  {
    private $no;
    private $m_name;

    public function __construct() {
        global $_M;
        
        //全局应用信息
        $this->no       = '10052'; //正式上线NO为10052，本地测试NO为10011
        $this->m_name   = 'met_wechat';
        $this->ver     = '2.0';
    }
    public function dosql() {
        global $_M;
        //其它应用信息
        $m_class    = 'met_wechat';
        $m_action   = 'doindex';
        $appname    = '微信公众平台';
        $info       = '配置微信接口信息，微信回复，微信菜单管理等！';

        //判断安装还是更新
        $do = $this->checkapp();
        //安装应用
        $this->insertapp($do, $m_class, $m_action, $appname, $info);
        //安装数据库文件
        $this->insertsql($do, 'lc_config|nwechat_menu|nwechat_keywords|nwechat_reply|nwechat_news|nwechat_msg_tmp|nwechat_user|nwechat_log_points');
        //安装配置文件
        $this->config_list('metinfo');
        //建立文件
        $this->addfile($this->m_name);
        //删除用于的安装数据，正式上线，请去掉注释
        //$this->installok();

        //这判断要不要都行
        // if ($do == 'add') {
        //     echo "安装成功";
        // } else {
        //     echo "更新成功";
        // }
        //return 'complete';
    }
    /**
     * 以下内容是所有应用可通用的安装方法！！！
     * 不用往下看了！！
     */
    private function appdir() {
        global $_M;
        $dir = PATH_WEB."app/app/".$this->m_name;
        return $dir;
    }
    private function checkapp() {
        global $_M;
        $app = DB::get_one("SELECT * FROM {$_M['table']['applist']} WHERE no = '{$this->no}'");
        if ($app) {
            return 'update';
        } else {
            return 'add';
        }
    }
    private function insertapp($type, $m_class = '', $m_action = '', $appname = '', $info = '') {
        global $_M;
        $time = time();
        switch ($type) {
            case 'add':
                $query = "INSERT INTO  {$_M['table']['applist']} SET
                    id          = NULL,
                    no          = '{$this->no}',
                    ver         = '{$this->ver}',
                    m_name      = '{$this->m_name}',
                    m_class     = '{$m_class}',
                    m_action    = '{$m_action}',
                    appname     = '{$appname}',
                    info        = '{$info}',
                    addtime     = {$time},
                    updatetime  = {$time}
                ";
                break;
            case 'update':
                $query = "UPDATE {$_M['table']['applist']} SET
                    ver         = '{$this->ver}',
                    m_name      = '{$this->m_name}',
                    m_class     = '{$m_class}',
                    m_action    = '{$m_action}',
                    appname     = '{$appname}',
                    info        = '{$info}',
                    updatetime  = {$time}
                    WHERE no = '{$this->no}'
                ";
                break;
        }
        DB::query($query);
    }
    private function insertsql($sqldata, $name = '') {
        global $_M;
        if ($name) {
            $this->add_table($name);
        }
        $ver = $sqldata == 'add'?'':'.'.$this->ver;
        $file = $this->appdir().'/include/sqldata/'.$sqldata.$ver.'.sql';
        $_data = file_get_contents($file);
        if ($_data) {
            $_sql = str_replace("[TABLEPRE]", $_M['config']['tablepre'], file_get_contents($file));
            $_arr = explode(";", $_sql);
            foreach ($_arr as $key => $val) {
                DB::query($val.';');
            }
        }
    }
    private function addfile($name) {
        global $_M;
        $oldDir = $this->appdir().'/include/filedata/'.$name;
        $targetDir = '../'.$name;
        copydir($oldDir, $targetDir);
    }
    private function new_config() {
        global $_M;
        $file = $this->appdir().'/include/sqldata/config.sql';
        $config = json_decode(urldecode(file_get_contents($file)), true);
        return $config;
    }
    private function config_list($m_num = 'm1595') {
        global $_M;
        $data = $this->new_config();
        $config = DB::get_one("SELECT * FROM {$_M['table']['lc_config']} WHERE name = '{$this->m_name}' AND m_num = '{$m_num}'");
        $config_data = json_decode(urldecode($config['value']), true);
        if ($config) {
            $config = $config_data?array_replace_recursive($data, $config_data):$data;
            $config_json = urlencode(json_encode($config));
            DB::get_one("UPDATE {$_M['table']['lc_config']} SET value = '{$config_json}' WHERE name = '{$this->m_name}' AND m_num = '{$m_num}'");
        } else {
            $config = $data;
            $config_json = urlencode(json_encode($config));
            DB::query("INSERT INTO {$_M['table']['lc_config']} SET id = NULL, name = '{$this->m_name}', value = '{$config_json}', m_num = '{$m_num}'");
        }
        return $config;
    }
    private function add_table($tablenames) {
        global $_M;
        $list = explode('|', $tablenames);
        foreach($list as $key=>$val){
            $tablename = $val;
            if (strpos("|{$_M['config']['met_tablename']}|", "|{$tablename}|") === false) {
                $_M['config']['met_tablename'] = "{$_M['config']['met_tablename']}|{$tablename}";
                $query = "UPDATE {$_M['table']['config']} SET value = '{$_M['config']['met_tablename']}' WHERE name='met_tablename'";
                DB::query($query);
                $_M['table'][$tablename] = $_M['config']['tablepre'].$tablename;
            }
        }
    }
    private function installok() {
        global $_M;
        $filedata = $this->appdir().'/include/filedata/';
        $sqldata = $this->appdir().'/include/sqldata/';
        deldir($filedata);
        deldir($sqldata);
    }
}
?>