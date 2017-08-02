<?php

defined('IN_MET') or exit('No permission');

load::sys_class('web');

class met_wechat extends web {
    public function __construct() {
        global $_M;
        parent::__construct();
        $this->LW_BASE = load::own_class('LW_BASE', 'new');
        $this->LW_REPLY = load::own_class('LW_REPLY', 'new');
    }
    public function doent() {
        global $_M;
        $config = $this->LW_BASE->config_list('get', '', 'met_wechat', 'metinfo');

        switch ($_SERVER['REQUEST_METHOD']) {
            case 'POST':
                $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
                $this->LW_REPLY->reply($postStr);
                break;
            default:
                $this->LW_REPLY->checkSign();
                break;
        }
    }
    public function domsg_tmp() {
        global $_M;
        $action = $_M['form']['action'];
        $signpackage = $this->LW_BASE->getSignPackage();
        $config = $this->LW_BASE->config_list('get', '', 'met_wechat', 'metinfo');

        switch ($action) {
            case 'detail':
                $r = $this->wx_user();
                $news_data = $this->LW_BASE->get_msg_detail($_M['form']['id']);
                if (!$news_data) {
                    echo "<pre>";
                    echo "{'errorcode':'404','errormsg':'not found'}";
                    die;
                }
                $news_data['web_title'] = "详细内容";
                $news_data['page_title'] = $news_data['title']." - ".$_M['config']['met_webname'];
                require_once $this -> template('own/tmp_msg_detail');
                break;
            case 'qrcode':
                $news_data['web_title'] = "微信扫码关注";
                $news_data['page_title'] = $news_data['web_title']." - ".$_M['config']['met_webname'];
                $news_data['description'] = $_M['config']['met_description'];
                $news_data['url'] = $this->LW_BASE->self_url('met_wechat', 'met_wechat', 'domsg_tmp')."&action=qrcode";
                $news_data['img'] = $_M['url']['site'].'a/'.$config['wechat_qrcode'];
                require_once $this -> template('own/tmp_qrcode');
                break;
            case 'jf':
                $openid = $this->LW_BASE->get_useropenid();
                $user = $this->LW_BASE->get_wx_user($openid['openid']);
                $this->LW_BASE->is_subscribe($user);
                $news_data['web_title'] = "用户积分";
                $news_data['page_title'] = $news_data['web_title']." - ".$_M['config']['met_webname'];
                $news_data['description'] = $_M['config']['met_description'];
                $news_data['url'] = $this->LW_BASE->self_url('met_wechat', 'met_wechat', 'domsg_tmp')."&action=jf";
                $news_data['img'] = $_M['url']['site'].'a/'.$_M['config']['met_logo'];
                $points = $this->LW_BASE->user_points($openid['openid'], 'view');
                $list = $this->LW_BASE->get_points_list($openid['openid'], $_M['form']['page'], '10');
                require_once $this -> template('own/tmp_jf');
                break;
            default:
                $r = $this->wx_user();
                $news_data['web_title'] = "图文列表";
                $news_data['page_title'] = $news_data['web_title']." - ".$_M['config']['met_webname'];
                $news_data['description'] = $_M['config']['met_description'];
                $news_data['url'] = $this->LW_BASE->self_url('met_wechat', 'met_wechat', 'domsg_tmp');
                $news_data['img'] = $_M['url']['site'].'a/'.$_M['config']['met_logo'];
                $list = $this->LW_BASE->get_msg_list($_M['form']['page'], '10');
                require_once $this -> template('own/tmp_msg_list');
                break;
        }
    }
    private function wx_user() {
        global $_M;
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        if (strpos($user_agent, 'MicroMessenger') == true) {
            $openid = $this->LW_BASE->get_useropenid();
            $openid = $openid['openid'];
            $r = $this->LW_BASE->get_userinfo_sub($openid);
        }
        return $r;
    }
}
?>