<?php
defined('IN_MET') or exit('No permission');

class LW_BASE{
    public function __construct() {
        global $_M;
        load::sys_class('session', 'new');
        //echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    }
    /**
     * [del_session 定时清除session]
     * @return [type] [6小时清除一次用]
     */
    private function del_session() {
        global $_M;
        $s_time = (int)$_SESSION['LW_BASE_s_time'];
        $timenow = (int)time();
        $time_unset = 21600;
        if ($s_time < $timenow) {
            unset($_SESSION['LW_BASE_useropenid_true']);
            unset($_SESSION['LW_BASE_useropenid']);
            unset($_SESSION['LW_BASE_userinfo']);
            unset($_SESSION['LW_BASE_userinfo_sub']);
            $_SESSION['LW_BASE_s_time'] = $timenow+$time_unset;
        } else {
            $_SESSION['LW_BASE_s_time'] = $timenow+$time_unset;
        }
    }
    /**
     * [config_appid 微信公众帐号的APPIID和APPSECRET]
     * @return [type] [数组]
     */
    public function config_appid(){
        global $_M;
        $data = $_SESSION['LW_BASE_appid'];
        if (!$data) {
            $config = $this->config_list('get', '', 'met_wechat', 'metinfo');
            $data = array (
                'APPID' => $config['wechat_appid'],
                'APPSECRET' => $config['wechat_appsecret'],
            );
            $_SESSION['LW_BASE_appid'] = $data;
        }
        return ($data);
    }
    /**
     * [getOpenidFromMp 非前台调用]
     * @param  [type] $code [code值]
     * @return [type]       [数组]
     */
    public function getOpenidFromMp($code) {
        global $_M;
        $APPID = $this -> config_appid();
        $sns_url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . $APPID[APPID] . "&secret=" . $APPID[APPSECRET] . "&code=" . $code . "&grant_type=authorization_code";
        $openid = $this -> curl_get_contents($sns_url);
        $openid = json_decode($openid,true);
        return $openid;
    }
    /**
     * [get_useropenid 获取微信用户的唯一openid]
     * @param  [type] $scope [空值为静默获取，true为续用户确认]
     * @return [type]        [数组]
     */
     public function get_useropenid($scope) {
        $this->del_session();
        if ($scope) {
            $openid = $_SESSION['LW_BASE_useropenid_true'];
            $scope = 'snsapi_userinfo';
        } else {
            $openid = $_SESSION['LW_BASE_useropenid'];
            $scope = 'snsapi_base';
        }
        if ($openid) {
            return $openid;
        }
        //通过code获得openid
        if (!isset($_GET['code'])){
            //触发微信返回code码
            $APPID = $this -> config_appid();
            $redirect_uri = urlencode('http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']);
            $state = 'wechat';
            $oauth_url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $APPID[APPID] . '&redirect_uri=' . $redirect_uri . '&response_type=code&scope=' . $scope . '&state=' . $state . '#wechat_redirect';
            //Header("Location: $oauth_url");
            $this->goheader($oauth_url);
            exit();
        } else {
            //获取code码，以获取openid
            $code = $_GET['code'];
            $openid = $this->getOpenidFromMp($code);
            if ($openid['openid']) {
                $this->save_wx_user($openid['openid']);
            }
            if ($scope == 'snsapi_userinfo') {
                $_SESSION['LW_BASE_useropenid_true'] = $openid;
                $_SESSION['LW_BASE_useropenid'] = $openid;
            } else {
                $_SESSION['LW_BASE_useropenid'] = $openid;
            }
            return $openid;
        }
    }
    /**
     * [get_userinfo 获取微信用户的详细信息]
     * @return [type] [数组]
     */
    public function get_userinfo() {
        global $_M;
        $this->del_session();
        $useropenid_data = $this->get_useropenid('true');
        $useropenid = $useropenid_data['openid'];
        $userinfo_sub = $this->get_userinfo_sub($useropenid);
        if ($userinfo_sub['subscribe'] == '1') {
            return $userinfo_sub;
        } else {
            $userinfo = $_SESSION['LW_BASE_userinfo'][$useropenid];
            if (!$userinfo||$userinfo['errcode']) {
                $access_token = $this -> get_access_token();
                $url = "https://api.weixin.qq.com/sns/userinfo?access_token=".$useropenid_data['access_token']."&openid=".$useropenid."&lang=zh_CN";
                $userinfo = $this -> curl_get_contents($url);
                $userinfo = json_decode($userinfo,true);
                $_SESSION['LW_BASE_userinfo'][$useropenid] = $userinfo;
                if ($useropenid && $userinfo) {
                    $this->save_wx_user($useropenid, $userinfo);
                }
            }
        }
        return ($userinfo);
    }
    /**
     * [get_userinfo_sub 关注后用openid获取用户详细信息]
     * @param  [type] $useropenid [用户openid]
     * @return [type]             [数组]
     */
    public function get_userinfo_sub($useropenid) {
        global $_M;
        $userinfo = $_SESSION['LW_BASE_userinfo_sub'][$useropenid];
        if (!$userinfo||$userinfo['errcode']) {
            $access_token = $this -> get_access_token();
            if ($useropenid) {
                $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$access_token."&openid=".$useropenid."&lang=zh_CN";
                $userinfo = $this -> curl_get_contents($url);
                $userinfo = json_decode($userinfo,true);
                $_SESSION['LW_BASE_userinfo_sub'][$useropenid] = $userinfo;
                if ($useropenid && $userinfo['subscribe'] == '1') {
                    $this->save_wx_user($useropenid, $userinfo);
                }
            }
        }
        return ($userinfo);
    }
    /**
     * [get_wx_user 获取数据库中的微信用户信息]
     * @param  [type] $openid [openid]
     * @return [type]         [数组]
     */
    public function get_wx_user($openid) {
        global $_M;
        if ($openid) {
            $userinfo = DB::get_one("SELECT * FROM {$_M['table']['nwechat_user']} WHERE openid = '{$openid}'");
            $userinfo['nickname'] = urldecode($userinfo['nickname']);
            return $userinfo;
        }
    }
    /**
     * [get_access_token 公众平台基础支持access_token，1小时内不会重复从微信请求该参数]
     * @return [type] [字符串]
     */
    public function get_access_token() {
        global $_M;
        $config = $this->config_list('get', '', 'met_wechat', 'metinfo');
        $data = explode("|", $config['wechat_access_token']);
        if ($data[0] == '' || intval($data[1]) < time()) {
            $APPID = $this -> config_appid();
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$APPID[APPID] ."&secret=".$APPID[APPSECRET];
            $access_token = $this -> curl_get_contents($url);
            $access_token = json_decode($access_token,true);
            $access_token = $access_token['access_token'];
            $expires_in = time()+3600;
            if ($access_token) {
                $data = $access_token."|".(time()+3600);
                $data = array (
                    "wechat_access_token" => $data
                );
                $config = $this->config_list('save', $data, 'met_wechat', 'metinfo');
                return $access_token;
            }
        } else {
            return $data[0];
        }
    }
    /**
     * [get_all_user_openid 获取公众平台所有用户openid]
     * @param  integer $c          [页码]
     * @param  string  $nextopenid [下个openid]
     * @return [type]              []
     */
    public function get_all_user_openid($c = 1, $nextopenid = '') {
        global $_M;
        $url = "https://api.weixin.qq.com/cgi-bin/user/get?access_token=".$this->get_access_token();;
        $url = $nextopenid?$url."&next_openid=".$nextopenid:$url;
        $data = $this -> curl_get_contents($url);
        $data = json_decode($data,true);
        if ($data['errcode']) {
            $r = array (
                "errcode" => $data['errcode'],
                "errmsg" => $data['errmsg']
            );
        } else {
            $get_count = $c <= 1?1 * $data['count']:($c - 1) * 10000 + $data['count'];
            if ($get_count < $data['total']) {
                $r = array (
                    "errcode"       => 0,
                    "errmsg"        => 'success',
                    "total"         => $data['total'],
                    "count"         => $data['count'],
                    "get_count"     => $get_count,
                    "page"          => $c+1,
                    "next_openid"   => $data['next_openid']
                );
            } else {
                $r = array (
                    "errcode"       => 0,
                    "errmsg"        => 'success',
                    "total"         => $data['total'],
                    "count"         => $data['count'],
                    "get_count"     => $get_count
                );
            }
        }
        if ($r[errcode] == 0) {
            $this->save_all_wx_user($data['data']['openid']);
        }
        return $r;
    }
    /**
     * [save_all_wx_user 保存获取到的所有openid]
     * @param  [type] $data [openid数组]
     * @return [type]       []
     */
    public function save_all_wx_user ($data) {
        global $_M;
        $i=0;
        $n=0;
        foreach ($data as $val) {
            $arr[$n][$i] = "(1,'".$val."')";
            $i++;
            if ($i >= 2000) { $n++; $i=0; }
        }
        foreach ($arr as $val) {
            $insert = implode(",", $val);
            $query = "INSERT IGNORE INTO {$_M[table][nwechat_user]} (subscribe, openid) VALUES {$insert};";
            DB::query($query);
        }
    }
    /**
     * [post_template_msg 推送模板消息给用户]
     * @param  [type] $openid    [openid]
     * @param  [type] $tem_id    [模板编号]
     * @param  [type] $msg_url   [消息链接，可为空]
     * @param  [type] $post_data [发送内容]
     * @return [type]            [返回数组，errcode为0，代表发送成功]
     */
    public function post_template_msg($openid, $tem_id, $msg_url, $post_data) {
        $data = array (
            "touser" => $openid,
            "template_id" => $tem_id,
            "url" => $msg_url,
            "data" => $post_data,
        );
        $data = json_encode($data);
        $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$this->get_access_token();
        $r = $this -> curl_post_contents($url,$data);
        $r = json_decode($r,true);
        return ($r);
    }
    /**
     * [post_menu 设置公众号菜单]
     * @param  [type] $data [description]
     * @return [type]       [数组，errcode为0代表成功]
     */
    public function post_menu($data) {
        $data = urldecode(json_encode($data));
        $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$this->get_access_token();
        $r = $this -> curl_post_contents($url,$data);
        $r = json_decode($r,true);
        return ($r);
    }
    /**
     * [get_menu 获取公众号菜单！]
     * @return [type] [基本没什么用]
     */
    public function get_menu() {
        $url = "https://api.weixin.qq.com/cgi-bin/menu/get?access_token=".$this->get_access_token();
        $r = $this -> curl_get_contents($url);
        $r = json_decode($r,true);
        return ($r);
    }
    public function default_menu() {
        global $_M;
        $menu = $this->get_menu();
        if ($menu['errcode'] != '0') {
            return $menu;
        }
        foreach ($menu['menu']['button'] as $key => $val) {
            if ($val['type']) {
                $key = $val['type'] == 'view'?$val['url']:$val['key'];
                $new_menu[] = array (NULL,0,"'".$val[type]."'","'".$val[name]."'","'".$key."'",0);
            } else {
                $new_menu[] = array (NULL,0,"''","'".$val[name]."'","'#'",0);
            }
        }
        $i=0;
        foreach ($menu['menu']['button'] as $key => $val) {
            $i++;
            if (!$val['type']) {
                foreach ($val['sub_button'] as $key => $val2) {
                    $key = $val2['type'] == 'view'?$val2['url']:$val2['key'];
                    $new_menu[] = array (NULL,$i,"'".$val2[type]."'","'".$val2[name]."'","'".$key."'",0);
                }
            }
        }
        $sql_menu = '';
        foreach ($new_menu as $val) {
            $sql_menu[] = "(NULL".implode(',', $val).")";
        }
        $sql_menu = implode(",", $sql_menu);
        $sql = "INSERT INTO {$_M['table']['nwechat_menu']} (`id`, `topid`, `type`, `name`, `value`, `order_no`) VALUES {$sql_menu};";
        DB::query("TRUNCATE TABLE {$_M['table']['nwechat_menu']}");
        DB::query($sql);
        if (DB::error()) {
            return DB::error();
        } else {
            return true;
        }
    }
    /**
     * [add_material 添加素材]
     * @param [type] $filename [相对于网站根目录文件位置]
     * @param [type] $type     [素材类型 图片（image）、语音（voice）、视频（video）和缩略图（thumb）]
     * @param string $tmp      [永久或临时素材 1为永久，空为临时]
     * @return [type]          [数组，errcode为0代表成功]
     */
    public function add_material($filename, $type, $tmp = '') {
        $real_path = PATH_WEB.$filename;
        $pathinfo = pathinfo($real_path);
        $filelength = filesize($real_path);
        $materialname = time().$pathinfo['extension'];
        $mime = array (
            'png' => 'image/png',
            'gif' => 'image/gif',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'bmp' => 'image/x-ms-bmp',
            'mp3' => 'audio/mp3',
            'wma' => 'audio/x-ms-wma',
            'wav' => 'audio/wav'
        );
        $file_info = array (
            'filename' => $filename,
            'content-type' => $mime[$pathinfo['extension']],
            'filelength' => $filelength
        );
        if ($tmp) {
            //永久素材
            $url="https://api.weixin.qq.com/cgi-bin/material/add_material?access_token=".$this->get_access_token()."&type={$type}";
        } else {
            //临时素材
            $url="https://api.weixin.qq.com/cgi-bin/media/upload?access_token=".$this->get_access_token()."&type={$type}";
        }
        $data = $this->is_php()?array("media"=>new CURLFile($real_path),'form-data'=>$file_info):array("media"=>"@{$real_path}",'form-data'=>$file_info);
        $r = $this -> curl_post_contents($url,$data);
        $r = json_decode($r,true);
        return ($r);
    }
    /**
     * [send_custom 发送客服消息]
     * @param  [type] $data [发动的json数据]
     * @return [type]       [数组，errcode为0代表成功]
     */
    public function send_custom($data) {
        global $_M;
        $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=".$this->get_access_token();
        $r = $this->curl_post_contents($url, $data);
        $r = json_decode($r,true);
        return $r;
    }
    /**
     * [save_wx_user 存储微信用户信息到表中]
     * @param  [type] $openid   [openid]
     * @param  string $userinfo [用户数据]
     * @return [type]           [布尔值]
     */
    public function save_wx_user($openid, $userinfo = '') {
        global $_M;
        $wx_user = DB::get_one("SELECT * FROM {$_M[table][nwechat_user]} WHERE openid = '{$openid}'");
        $nickname = urlencode($userinfo[nickname]);
        if (!$wx_user) {
            $query = "INSERT INTO {$_M[table][nwechat_user]} SET
                subscribe   = '{$userinfo[subscribe]}',
                openid      = '{$openid}',
                nickname    = '{$nickname}',
                sex         = '{$userinfo[sex]}',
                language    = '{$userinfo[language]}',
                city        = '{$userinfo[city]}',
                province    = '{$userinfo[province]}',
                country     = '{$userinfo[country]}',
                headimgurl  = '{$userinfo[headimgurl]}',
                subscribe_time = '{$userinfo[subscribe_time]}',
                unionid     = '{$userinfo[unionid]}',
                remark      = '{$userinfo[remark]}',
                groupid     = '{$userinfo[groupid]}'
            ";
        } elseif ($wx_user && $userinfo) {
            $query = "UPDATE {$_M[table][nwechat_user]} SET
                subscribe   = '{$userinfo[subscribe]}',
                nickname    = '{$nickname}',
                sex         = '{$userinfo[sex]}',
                language    = '{$userinfo[language]}',
                city        = '{$userinfo[city]}',
                province    = '{$userinfo[province]}',
                country     = '{$userinfo[country]}',
                headimgurl  = '{$userinfo[headimgurl]}',
                subscribe_time = '{$userinfo[subscribe_time]}',
                unionid     = '{$userinfo[unionid]}',
                remark      = '{$userinfo[remark]}',
                groupid     = '{$userinfo[groupid]}'
                WHERE id = '{$wx_user[id]}'
            ";
        }
        if ($query) {
            DB::query($query);
            return true;
        } else {
            return false;
        }
    }
    /**
     * [user_points 操作微信用户积分]
     * @param  [type] $openid [openid]
     * @param  string $type   [add增加，del减少，new直接设置]
     * @param  string $points [操作的积分数]
     * @param  string $text   [操作说明文字]
     * @return [type]         [返回操作后的新积分数]
     */
    public function user_points($openid, $type = 'view', $points = '0', $text = '') {
        global $_M;
        $userinfo = $this->get_wx_user((string)$openid);
        switch ($type) {
            case 'add':
                $new_points = $userinfo['points']+$points;
                break;
            case 'del':
                $new_points = $userinfo['points']-$points;
                break;
            case 'new':
                $new_points = $points;
                break;
            default:
                $get_points = $userinfo['points'];
                break;
        }
        if ($type == 'view') {
            return $get_points;
        } else {
            DB::query("UPDATE {$_M['table']['nwechat_user']} SET points = {$new_points} WHERE openid = '{$openid}'");
            $time = time();
            DB::query("INSERT INTO {$_M['table']['nwechat_log_points']} SET openid = '{$openid}', type = '{$type}', points = {$points}, text = '{$text}', date = {$time}");
            return $new_points;
        }
    }
    /**
     * [save_usetime 保存用户最后使用时间]
     * @param  [type] $usetime [时间]
     * @param  [type] $openid  [openid]
     * @return [type]          []
     */
    public function save_usetime($usetime, $openid) {
        global $_M;
        if ($usetime && $openid) {
            $query = "UPDATE {$_M[table][nwechat_user]} SET
                usetime  = '{$usetime}'
                WHERE openid = '{$openid}'
            ";
            DB::query($query);
        }
    }
    /**
     * [getsignpackage 获取 前台 JSSDK 签名，用于页面分享js的调用]
     * @return [type] [description]
     */
    public function getsignpackage() {
        global $_M;

        $jsapiTicket = $this->getjsapiticket();
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $timestamp = time();
        $nonceStr = $this->createNonceStr();

        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
        $signature = sha1($string);

        $APPID = $this -> config_appid();

        $signPackage = array(
            "appId"     => $APPID[APPID],
            "nonceStr"  => $nonceStr,
            "timestamp" => $timestamp,
            "url"       => $url,
            "signature" => $signature,
            "rawString" => $string
        );
        return $signPackage;
    }
    /**
     * [createNonceStr 生成随机参数]
     * @param  integer $length [生成长度]
     * @return [type]          [字符串]
     */
    private function createNonceStr($length = 16) {
        global $_M;
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }
    /**
     * [getjsapiticket 获取全局 jsapi_ticket]
     * @return [type] [字符串]
     */
    public function getjsapiticket() {
        global $_M;
        $config = $this->config_list('get', '', 'met_wechat', 'metinfo');
        $data = explode("|", $config['wechat_jsapi_ticket']);
        if ($data[0] == '' || $data[1] < time()) {
            $accessToken = $this->get_access_token();
            $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
            $res = json_decode($this->curl_get_contents($url));
            $ticket = $res->ticket;
            if ($ticket) {
                $data = $ticket."|".(time()+7000);
                $data = array (
                    "wechat_jsapi_ticket" => $data
                );
                $config = $this->config_list('save', $data, 'met_wechat', 'metinfo');
            }
        } else {
          $ticket = $data[0];
        }
        return $ticket;
    }
    /**
     * [get_msg_detail 获取一条自带图文内容]
     * @param  [type] $msgid [图文内容id]
     * @return [type]        [数组]
     */
    public function get_msg_detail($msgid) {
        global $_M;
        $news_data = DB::get_one("SELECT * FROM {$_M[table][nwechat_news]} WHERE id = {$msgid}");
        if ($news_data) {
            $news_data['img'] = $_M['url']['site'].'a/'.$news_data['img'];
            $new_read = intval($news_data['all_read'])+1;
            DB::query("UPDATE {$_M[table][nwechat_news]} SET all_read = '{$new_read}' WHERE id = {$msgid}");
            return $news_data;
        } else {
            return false;
        }
    }
    /**
     * [get_msg_list 获取图文列表]
     * @param  string $page  [页数]
     * @param  string $count [每页显示数量]
     * @return [type]        [数组]
     */
    public function get_msg_list($page = '1', $count = '10') {
        global $_M;
        $page = $page?$page:'1';
        $where = " WHERE id != 0";
        $order = ' ORDER BY addtime DESC';
        $first  = $count * ($page-1);
        $limit  = ' limit '.$first.','.$count;
        $count_max = DB::counter($_M['table']['nwechat_news'],$where);
        $page_max = ceil($count_max/$count);
        $query = "SELECT * FROM ". $_M['table']['nwechat_news'] . $where . $order .$limit;
        $list = DB::get_all($query);
        $page_prev = $page>1?$page-1:'0';
        $page_next = $page<$page_max?$page+1:'0';
        if ($list) {
            $news_data['list'] = $list;
            $news_data['count_max'] = $count_max;
            $news_data['page_max'] = $page_max;
            $news_data['page_prev'] = $page_prev;
            $news_data['page_next'] = $page_next;
            return $news_data;
        } else {
            return false;
        }
    }
    /**
     * [get_points_list 获取积分列表]
     * @param  [type] $openid [openid]
     * @param  string $page   [页码]
     * @param  string $count  [每页显示数量]
     * @return [type]         [数组]
     */
    public function get_points_list($openid, $page = '1', $count = '10') {
        global $_M;
        $page = $page?$page:'1';
        $where = " WHERE id != 0 AND openid = '{$openid}'";
        $order = ' ORDER BY date DESC';
        $first  = $count * ($page-1);
        $limit  = ' limit '.$first.','.$count;
        $count_max = DB::counter($_M['table']['nwechat_log_points'],$where);
        $page_max = ceil($count_max/$count);
        $query = "SELECT * FROM ". $_M['table']['nwechat_log_points'] . $where . $order .$limit;
        $list = DB::get_all($query);
        $page_prev = $page>1?$page-1:'0';
        $page_next = $page<$page_max?$page+1:'0';
        if ($list) {
            $news_data['list'] = $list;
            $news_data['count_max'] = $count_max;
            $news_data['page_max'] = $page_max;
            $news_data['page_prev'] = $page_prev;
            $news_data['page_next'] = $page_next;
            return $news_data;
        } else {
            return false;
        }
    }
    /**
     * [config_list 应用配置文件保存]
     * @param  [type] $type   [save保存，get读取]
     * @param  [type] $data   [需要保存的数组]
     * @param  string $m_name [m_name]
     * @param  string $m_num  [默认m1595]
     * @return [type]         [description]
     */
    public function config_list($type, $data, $m_name = '', $m_num = 'm1595') {
        global $_M;

        $config = DB::get_one("SELECT * FROM {$_M['table']['lc_config']} WHERE name = '{$m_name}' AND m_num = '{$m_num}'");
        $config_data = json_decode(urldecode($config['value']), true);
        switch ($type) {
            case 'save':
                if ($config) {
                    $config = $config_data?array_replace_recursive($config_data, $data):$data;
                    $config_json = urlencode(json_encode($config));
                    $query = "UPDATE {$_M['table']['lc_config']} SET value = '{$config_json}' WHERE name = '{$m_name}' AND m_num = '{$m_num}'";
                } else {
                    $config = $data;
                    $config_json = urlencode(json_encode($config));
                    $query = "INSERT INTO {$_M['table']['lc_config']} SET id = NULL, name = '{$m_name}', value = '{$config_json}', m_num = '{$m_num}'";
                }
                if ($config_json) {
                    DB::query($query);
                }
                return $config;
                break;
            case 'get':
                return $config_data;
                break;
        }
    }
    /**
     * [save_keywords 关键词保存更新删除方法]
     * @param  [type]  $on       [开关 1 为添加或更新关键词，0 为删除关键词]
     * @param  [type]  $word     [关键词 组成格式为 “|关键词1|关键词2|关键词3”]
     * @param  [type]  $type     [关键词类型 1 为完全匹配，2 为模糊匹配，3 为完全接管]
     * @param  integer $is_own   [默认1]
     * @param  [type]  $m_name   [应用m_name]
     * @param  [type]  $m_class  [应用m_class]
     * @param  [type]  $m_action [应用m_action]
     * @param  string  $own_url  [你还需要自定义的参数 &key=123 , 必须以 & 开头]
     * @param  string  $m_num    [你一个应用要写多条的话，m_num帮助分辨是哪一条，自定义字符串即可]
     * @param  integer $level    [关键词级别 0-10， 默认为0]
     * @return [type]            [description]
     */
    public function save_keywords($on, $word, $type, $is_own = 1, $m_name, $m_class, $m_action, $own_url = '', $m_num = '', $level = 0) {
        global $_M;
        $keywords = DB::get_one("SELECT * FROM {$_M['table']['nwechat_keywords']} WHERE m_name = '{$m_name}' AND m_num = '{$m_num}'");
        if ($on == '1') {
            if ($keywords) {
                $query = "UPDATE {$_M['table']['nwechat_keywords']} SET
                    word        = '{$word}',
                    type        = {$type},
                    is_own      = {$is_own},
                    m_name      = '{$m_name}',
                    m_class     = '{$m_class}',
                    m_action    = '{$m_action}',
                    own_url     = '{$own_url}',
                    level       = {$level}
                    WHERE id = $keywords[id] AND m_num = '{$m_num}'
                ";
            } else {
                $query = "INSERT INTO {$_M['table']['nwechat_keywords']} SET
                    id          = NULL,
                    word        = '{$word}',
                    type        = {$type},
                    is_own      = {$is_own},
                    m_name      = '{$m_name}',
                    m_class     = '{$m_class}',
                    m_action    = '{$m_action}',
                    own_url     = '{$own_url}',
                    m_num       = '{$m_num}',
                    level       = {$level}
                ";
            }
            DB::query($query);
        } else {
            DB::query("DELETE FROM {$_M['table']['nwechat_keywords']} WHERE id = {$keywords['id']} AND m_num = '{$m_num}'");
        }
        if (!DB::query()) {
            return true;
        } else {
            return false;
        }
    }
    /**
     * [is_subscribe 判断是否关注]
     * @param  [type]  $user [获取到的用户数据]
     * @return boolean       [没关注就跳转]
     */
    public function is_subscribe($user) {
        global $_M;
        if ($user['subscribe'] != '1') {
            okinfo($this->self_url('qrcode'));
        }
    }
    /**
     * [json_encode_ex 不转义中文的json]
     * @param  [type] $value [需要转换的数组]
     * @return [type]        [json]
     */
    public function json_encode_ex($value) { 
        if (version_compare(PHP_VERSION,'5.4.0','<')) { 
            $str = json_encode( $value); 
            $str =  preg_replace_callback( 
                    "#\\\u([0-9a-f]{4})#i", 
                    function( $matchs) { 
                        return  iconv('UCS-2BE', 'UTF-8',  pack('H4',  $matchs[1])); 
                    }, 
                    $str 
                ); 
            return  $str; 
        } else { 
            return json_encode( $value, JSON_UNESCAPED_UNICODE); 
        }
    }
    /**
     * [self_url 获取自己的链接地址]
     * @param  string $n [应用n]
     * @param  string $c [应用c]
     * @param  string $a [应用a]
     * @return [type]    [返回链接]
     */
    public function self_url ($n='', $c='', $a='') {
        global $_M;
        $config = $this->config_list('get', '', 'met_wechat', 'metinfo');
        $url = $_M['url']['site'].$config['wechat_dir']."/?";
        switch ($n) {
            case 'jf':
                $url = $url.'n=met_wechat&c=met_wechat&a=domsg_tmp&action=jf';
                break;
            case 'qrcode':
                $url = $url.'n=met_wechat&c=met_wechat&a=domsg_tmp&action=qrcode';
                break;
            default:
                $url = $n?$url.'n='.$n.'&c='.$c.'&a='.$a:'';
                break;
        }
        return $url;
    }
    /**
     **下边的内容看不懂就不要看了，反正不是很重要！！！！！
     **但是不能没有！！！！！
    */
    /**
     * [is_php 判断php版本]
     * @param  [type] $version [需要判断的中间值]
     * @return [type]       [0代表小于，1代表大于]
     */
    public function is_php($version = '5.6.0' ) {  
        $php_version = explode( '-', phpversion() );  
        $is_pass = strnatcasecmp( $php_version[0], $version ) >= 0 ? true : false;  
        return $is_pass;  
    }
    public function goheader($oauth_url){
        header('Expires: 0');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: no-store, no-cahe, must-revalidate');
        header('Cache-Control: post-chedk=0, pre-check=0', false);
        header('Pragma: no-cache');
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: $oauth_url");
        exit;
    }
    public function curl_get_contents($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        //curl_setopt($ch, CURLOPT_TIMEOUT, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, _USERAGENT_);
        curl_setopt($ch, CURLOPT_REFERER, _REFERER_);
        @curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $r = curl_exec($ch);
        curl_close($ch);
        return $r;
    }
    public function curl_post_contents($url,$data,$ctimeout = 10,$timeout = 60){
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        //curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $ctimeout);
        //curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        $r = curl_exec($ch);
        curl_close($ch);
        return $r;
    }
    public function curl_post_xml($url,$data){
        $header[] = "Content-type: text/xml";
        $ch = curl_init ($url);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $data);
        $r = curl_exec($ch);
        curl_close($ch);
        return $r;
    }
    public function curl_post_json($url, $data,$ctimeout = 5,$timeout = 30) {
        $header[] = "Content-Type: application/json";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        //curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $ctimeout);
        //curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $r = curl_exec($ch);
        curl_close($ch);
        return $r;
    }
}