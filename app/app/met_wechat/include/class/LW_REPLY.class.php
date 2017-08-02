<?php
defined('IN_MET') or exit('No permission');

class LW_REPLY{
    public function __construct() {
        global $_M;
        $this->LW_BASE = load::own_class('LW_BASE', 'new');
    }
    /**
     * 校验是否是微信正确post！！
     */
    public function checkSign() {
        $config = $this->LW_BASE->config_list('get', '', 'met_wechat', 'metinfo');
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        $token = $config['wechat_token'];
        $signkey = array($token, $timestamp, $nonce);

        sort($signkey, SORT_STRING);
        $signString = implode($signkey);
        $signString = sha1($signString);

        if ($signString == $signature) {
            echo $_GET["echostr"];
        } else {
            echo '';
        }
    }
    public function reply($postStr) {
        global $_M;
        //xml转array
        libxml_disable_entity_loader(true);
        $post_data = json_decode(json_encode(simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA)),true);
        //消息排重
        $openid = $post_data['FromUserName'];
        $msgid = $post_data['MsgId'];
        $usetime = $post_data['CreateTime'];
        $openid_session = $openid.$usetime;
        if ($post_data['Event'] && $_SESSION['event'] == ($openid_session)) {
            die;
        } elseif ($post_data['Event']) {
            $_SESSION['event'] = ($openid_session);
        } elseif ($_SESSION['msgid'] == $msgid) {
            die;
        } else {
            $_SESSION['msgid'] = $msgid;
        }
        switch ($post_data['MsgType']) {
            case 'event':
                switch ($post_data['Event']) {
                    case 'subscribe':
                        //关注微信
                        $config = $this->LW_BASE->config_list('get', '', 'met_wechat', 'metinfo');
                        if ($config['auto_reply_gz']) {
                            $this->post_reply($config['auto_reply_gz'], $openid);
                        }
                        $userinfo = $this->LW_BASE->get_userinfo_sub($openid);
                        if ($config['backurl_gz']) {
                            $this->post_url($config['backurl_gz'], $post_data);
                        }
                        break;
                    case 'unsubscribe':
                        //取消关注
                        DB::query("UPDATE {$_M['table']['nwechat_user']} SET subscribe = 0 WHERE openid = '{$openid}'");
                        break;
                    case 'LOCATION':
                        //更新地理位置
                        $this->update_user_location($post_data);
                        break;
                    case 'CLICK':
                        //点击菜单关键词
                        $this->post_keyword(trim($post_data['EventKey']), $post_data);
                        break;
                }
                break;
            case 'text':
                //文字内容
                $this->post_keyword(trim($post_data['Content']), $post_data);
                break;
            case 'voice':
                //语音转文字处理
                if ($post_data['Recognition']) {
                    $this->post_keyword(str_replace(array("。","？","！"), "", trim($post_data['Recognition'])), $post_data);
                }
                break;
            case 'location':
                //推送用户位置信息
                //暂时无操作
                break;
        }
        $this->LW_BASE->save_usetime($usetime, $openid);
    }
    /**
     * 处理关键词数据
     */
    public function post_keyword($keyword, $post_data) {
        global $_M;
        $keyword = explode("#", $keyword);
        $keyword = $keyword[0];
        $list = DB::get_all("SELECT * FROM {$_M['table']['nwechat_keywords']} WHERE word LIKE '%{$keyword}%' ORDER BY level DESC,id DESC");
        if ($list) {
            //如果有关键词
            foreach ($list as $key => $val) {
                $words = explode("|", $val['word']);
                if ($val['type'] == '1') {
                    //精确匹配关键词
                    foreach ($words as $key => $val2) {
                        if ($val2 && $val2 == $keyword) {
                            $replyid = $val['m_name']?$this->post_app($val, $post_data):$val['replyid'];
                            break;
                        }
                    }
                    break;
                } elseif ($val['type'] == '2') {
                    //模糊匹配关键词
                    $replyid = $val['m_name']?$this->post_app($val, $post_data):$val['replyid'];
                    break;
                }
            }
        } else {
            //如果没有关键词
            $no_word = DB::get_all("SELECT * FROM {$_M['table']['nwechat_keywords']} WHERE type = 3 AND is_own = 1 ORDER BY level DESC,id DESC");
            if ($no_word) {
                //如果有应用接收无匹配内容，推送给应用
                foreach ($no_word as $key => $val) {
                    $this->post_app($val, $post_data);
                    break;
                }
            } else {
                //没有任何关键词匹配，使用自带无关键词返回内容
                $config = $this->LW_BASE->config_list('get', '', 'met_wechat', 'metinfo');
                $replyid = $config['auto_reply_no'];
            }
        }
        if ($replyid) {
            $this->post_reply($replyid, $post_data['FromUserName']);
        }
    }
    /**
     * 批量发送给用户
     */
    public function post_reply ($replyid, $openid) {
        global $_M;
        $replyid = explode("|", $replyid);
        foreach ($replyid as $key => $val) {
            if ($val) {
                $this->reply_self($val, $openid);
            }
        }
    }
    /**
     * 把关键词post给应用
     */
    public function post_app ($app_data, $post_data) {
        global $_M;
        $app_url = $this->LW_BASE->self_url($app_data['m_name'], $app_data['m_class'], $app_data['m_action']).$app_data['own_url'];
        $wx_key = "&timestamp=".$_GET['timestamp']."&nonce=".$_GET['nonce']."&signature=".$_GET['signature'];
        $reply_data = $this->LW_BASE->curl_post_json($app_url.$wx_key, json_encode($post_data), 3, 1);
        $reply_data = $reply_data?$reply_data:'';
        echo $reply_data;
    }
    /**
     * 把关键词post给应用
     */
    public function post_url ($url_data, $post_data) {
        global $_M;
        $url_data = explode("|", $url_data);
        foreach ($url_data as $key => $val) {
            if ($val) {
                $this->LW_BASE->curl_post_json($val, json_encode($post_data));
            }
        }
    }
    /**
     * 处理自带回复内容
     */
    public function reply_self($replyid, $openid) {
        global $_M;
        $reply_data = DB::get_one('SELECT * FROM '.$_M['table']['nwechat_reply'].' WHERE id = '.$replyid);
        if ($reply_data) {
            $resultStr = array();
            $resultStr['touser'] = $openid;
            switch ($reply_data['type']) {
                case 'text':
                    $resultStr['msgtype'] = "text";
                    $resultStr['text']['content'] = str_replace("\r\n", "\n", urldecode($reply_data['text']));
                    break;
                case 'image':
                    $resultStr['msgtype'] = "image";
                    $resultStr['image']['media_id'] = $reply_data['mediaid'];
                    break;
                case 'voice':
                    $resultStr['msgtype'] = "voice";
                    $resultStr['voice']['media_id'] = $reply_data['mediaid'];
                    break;
                case 'news':
                    $resultStr['msgtype'] = "news";
                    $resultStr['news']['articles'] = $this->get_wechat_news($reply_data['msg_list']);
                    break;
                case 'column':
                    $resultStr['msgtype'] = "news";
                    $resultStr['news']['articles'] = $this->get_met_columns($reply_data['columns']);
                    break;
            }
            $resultStr = $this->LW_BASE->json_encode_ex($resultStr);
            $this->LW_BASE->send_custom($resultStr);
        }
    }
    /**
     * 处理自带图文消息数组
     */
    public function get_wechat_news($news_id) {
        global $_M;
        $news_allid = implode(",", array_filter(explode("|", $news_id)));
        $news_data = DB::get_all("SELECT * FROM ".$_M['table']['nwechat_news']." WHERE id in (".$news_allid.") ORDER BY FIELD (id,".$news_allid.")");
        $config = $this->LW_BASE->config_list('get', '', 'met_wechat', 'metinfo');
        $tmp = DB::get_one("SELECT * FROM {$_M[table][nwechat_msg_tmp]} WHERE id = '{$config[wechat_msg_tmp]}'");
        $i = 0;
        foreach ($news_data as $key => $val) {
            $reply_data[$i]['title'] = $val['title'];
            $reply_data[$i]['description'] = substr($val['description'],0,300);
            $reply_data[$i]['url'] = $val['link']?$val['link']:$this->LW_BASE->self_url($tmp['m_name'], $tmp['m_class'], $tmp['m_action']).'&action=detail&id='.$val['id'];
            $reply_data[$i]['picurl'] = $_M['url']['site'].str_replace("../", "", $val['img']);
            $i++;
            if($i>7){break;}
        }
        return $reply_data;
    }
    /**
     * 处理网站栏目文章数组
     */
    public function get_met_columns($columns) {
        global $_M;
        $columns = explode("-", $columns);
        switch ($columns[0]) {
            case '3':
                $table_column = $_M['table']['product'];
                $filename = 'product';
                break;
            case '4':
                $table_column = $_M['table']['download'];
                $filename = 'download';
                break;
            case '5':
                $table_column = $_M['table']['img'];
                $filename = 'img';
                break;
            default:
                $table_column = $_M['table']['news'];
                $filename = 'news';
                break;
        }
        $columns[2] = $columns[2]?" AND class2 = ".$columns[2]:'';
        $columns[3] = $columns[3]?" AND class3 = ".$columns[3]:'';
        $com_ok = $columns[4] == '1'?" AND com_ok = 1":'';
        $news_data = DB::get_all("SELECT * FROM ".$table_column." WHERE class1 = ".$columns[1].$columns[2].$columns[3].$com_ok." ORDER BY no_order,id DESC LIMIT 0,".$columns[5]);
        $foldername = DB::get_one("SELECT * FROM ".$_M['table']['column']." WHERE id = ".$columns[1]);
        $i = 0;
        foreach ($news_data as $key => $val) {
            $url = $val['link']?$val['link']:$_M['url']['site'].$foldername['foldername'].'/show'.$filename.'.php?lang='.$val['lang'].'&id='.$val['id'];
            $picurl = $val['imgurl']?$_M['url']['site'].str_replace("../", "", $val['imgurl']):"";
            $reply_data[$i]['title'] = iconv("UTF-8","UTF-8//IGNORE",$val['title']);
            $reply_data[$i]['description'] = iconv("UTF-8","UTF-8//IGNORE",substr($val['description'],0,300));
            $reply_data[$i]['url'] = iconv("UTF-8","UTF-8//IGNORE",$url);
            $reply_data[$i]['picurl'] = iconv("UTF-8","UTF-8//IGNORE",$picurl);
            $i++;
            if($i>7){break;}
        }
        return $reply_data;
    }
    /**
     * 更新用户地理位置
     */
    public function update_user_location ($post_data) {
        global $_M;
        $data = json_encode($post_data);
        DB::query("UPDATE {$_M[table][nwechat_user]} SET location = '{$data}' WHERE openid = '{$post_data[FromUserName]}'");
    }
}