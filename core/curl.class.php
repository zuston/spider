<?php

/**
 * Created by PhpStorm.
 * User: zuston
 * Date: 16/7/12
 * Time: 下午2:57
 */
class curl
{

    public $name;
    public $config = array();
    //多页关注者的唯一id值
    public $hashId;
    public $_xsrf;
    public $cookie;

    public $changeId = 1;

    public function __construct($name, $config = array())
    {
        $this->name = $name;
        $this->config = $config;
        $this->hashId = $this->config['user_config']['hash_id'];
        $this->_xsrf = $this->config['user_config']['_xsrf'];
        $this->cookie = $this->config['user_config']['user_config_cookie'];
    }

    public function changeConfig(){
        $this->changeId += 1;
        if($this->changeId%2==0){
            $this->hashId = $this->config['user_config_default']['hash_id'];
            $this->_xsrf = $this->config['user_config_default']['_xsrf'];
            $this->cookie = $this->config['user_config_default']['user_config_cookie'];
        }else{
            $this->hashId = $this->config['user_config']['hash_id'];
            $this->_xsrf = $this->config['user_config']['_xsrf'];
            $this->cookie = $this->config['user_config']['user_config_cookie'];
        }
    }

    //获取关注者和被关注者
    //有第二个参数为多页获取
    public function robotSpider($topicUrl, $followeeOrFollowerNumber = null, $hashId = null)
    {
        echo "============正在努力爬取===============\n";
        if (is_null($followeeOrFollowerNumber) && is_null($hashId)) {
            $url = 'https://www.zhihu.com/people/' . $this->name . '/' . $topicUrl;
            $mainConent = self::curlCore($url);
            return $mainConent;
        }

        $this->hashId = $hashId;
        if ($followeeOrFollowerNumber > 19) {
            $topic = 1;
            if ($topicUrl == "followees") {
                $topic = 1;
            } elseif ($topicUrl == "followers") {
                $topic = 0;
            }
            return self::getRemainInfoMulti($followeeOrFollowerNumber,$topic);
        }
    }

    public function viceSpider($nickNameArray){
        $urlArray = array();
        foreach($nickNameArray as $value){
            $urlArray[] = 'https://www.zhihu.com/people/' . $value . '/followees';
        }
        $mainContent = self::curlMulti($urlArray);
        return $mainContent;
    }



    private function curlCore($url, $method = null, $fields = array())
    {
        $http_user_agent = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36';
        $ch = curl_init($url); //初始化会话
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_COOKIE, $this->cookie);
        curl_setopt($ch, CURLOPT_USERAGENT, $http_user_agent);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //将curl_exec()获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Expect:"));
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
        curl_setopt($ch,CURLOPT_ENCODING, 'gzip');
        if ($method == 'POST' && is_array($fields)) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        }
        usleep(10000);
        $result = curl_exec($ch);
//        curl_close($ch);
        return $result;
    }

    //测试多页获取ajax
    public function getPagesDemo($startNumber)
    {
        $fields = array(
            'method' => 'next',
            'params' => '{"offset":' . $startNumber . ',"order_by":"created","hash_id":"'.$this->hashId.'"}',
            '_xsrf' => $this->_xsrf,
        );
        $content = self::curlCore('http://www.zhihu.com/node/ProfileFolloweesListV2', 'POST', $fields);
        $result = '';
        foreach (json_decode($content)->msg as $key => $value) {
            $result .= $value;
        }
        return $result;
    }

    private function getRemainInfo($startNumber, $topic)
    {
        $hash_id = '"' . $this->hashId . '"';
        $fields = array(
            'method' => 'next',
            'params' => '{"offset":' . $startNumber . ',"order_by":"created","hash_id":' . $hash_id . '}',
            '_xsrf' => $this->_xsrf,
        );
        if ($topic == 1) {
            $content = self::curlCore('http://www.zhihu.com/node/ProfileFolloweesListV2', 'POST', $fields);
        } elseif ($topic == 0) {
            $content = self::curlCore('http://www.zhihu.com/node/ProfileFollowersListV2', 'POST', $fields);

        }
        $result = '';
        foreach (json_decode($content)->msg as $key => $value) {
            $result .= $value;
        }
        return $result;
    }

    private function getRemainInfoMulti($count,$topic){
        $times = floor($count / 19) - 1;
        $hash_id = '"' . $this->hashId . '"';

        echo "============" . $times . "次============\n";
        echo "============" . $count . "人============\n";

        $fieldsArray = array();
        if($times>10){
            $times = 10;
        }
        if($times>=1){
            for ($i = 1; $i <= $times; $i++) {
                $fieldsArray[] = array(
                    'method' => 'next',
                    'params' => '{"offset":' . ($i * 19 + 1) . ',"order_by":"created","hash_id":' . $hash_id . '}',
                    '_xsrf' => $this->_xsrf,
                );
            }
        }


        if(($times+1)*19<$count){
            $fieldsArray[] = array(
                'method' => 'next',
                'params' => '{"offset":' . (($times+1) * 19 + 1) . ',"order_by":"created","hash_id":' . $hash_id . '}',
                '_xsrf' => $this->_xsrf,
            );
        }
//        var_dump($fieldsArray);exit;

        if ($topic == 1) {
            $content = self::curlMultiAjax('http://www.zhihu.com/node/ProfileFolloweesListV2', 'POST', $fieldsArray);
        } elseif ($topic == 0) {
            $content = self::curlMultiAjax('http://www.zhihu.com/node/ProfileFollowersListV2', 'POST', $fieldsArray);

        }
        return $content;
    }

    //多进程curl
    private function curlMultiAjax($url,$method,$fieldArray){

        $curlArray = array();

        foreach($fieldArray as $key => $value){
            $http_user_agent = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36';
            $ch = curl_init($url); //初始化会话
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_COOKIE, $this->config['user_config']['user_config_cookie']);
            curl_setopt($ch, CURLOPT_USERAGENT, $http_user_agent);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //将curl_exec()获取的信息以文件流的形式返回，而不是直接输出。
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Expect:"));
            curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
            curl_setopt($ch,CURLOPT_ENCODING, 'gzip');
            if ($method == 'POST' && is_array($value)) {
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $value);
            }
            $curlArray[] = $ch;
        }

        $multiInstance = curl_multi_init();
        foreach($curlArray as $curlInstance){
            curl_multi_add_handle($multiInstance,$curlInstance);
        }

//        do {
//            $mrc = curl_multi_exec($multiInstance,$status);
//        } while ($mrc == CURLM_CALL_MULTI_PERFORM);
//
//        while ($status and $mrc == CURLM_OK) {
//            if (curl_multi_select($multiInstance) != -1) {
//                do {
//                    $mrc = curl_multi_exec($multiInstance, $status);
//                } while ($mrc == CURLM_CALL_MULTI_PERFORM);
//            }
//        }

        $active = null;
        do { $n=curl_multi_exec($multiInstance,$active); } while ($active);

        foreach($curlArray as $curlInstance){
            curl_multi_remove_handle($multiInstance,$curlInstance);
        }
        curl_multi_close($multiInstance);

        $res = array();
        foreach($curlArray as $curlRes){
           $res[] = curl_multi_getcontent($curlRes);
        }

        //处理数据,直接将数据拼接
        $string = "";
        foreach($res as $v){
            if(is_null(json_decode($v)->msg)){
                continue;
            }
            foreach(json_decode($v)->msg as $value){
                $string .= $value;
            }
        }
        return $string;
    }


    //多进程curl
    private function curlMulti($urlArray){

        $curlArray = array();

        foreach($urlArray as $key => $value){
            $http_user_agent = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36';
            $ch = curl_init($value); //初始化会话
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_COOKIE, $this->config['user_config']['user_config_cookie']);
            curl_setopt($ch, CURLOPT_USERAGENT, $http_user_agent);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //将curl_exec()获取的信息以文件流的形式返回，而不是直接输出。
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Expect:"));
            curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
            curl_setopt($ch,CURLOPT_ENCODING, 'gzip');
            $curlArray[] = $ch;
        }

        $multiInstance = curl_multi_init();
        foreach($curlArray as $curlInstance){
            curl_multi_add_handle($multiInstance,$curlInstance);
        }

        $active = null;
        do { $n=curl_multi_exec($multiInstance,$active); } while ($active);

        foreach($curlArray as $curlInstance){
            curl_multi_remove_handle($multiInstance,$curlInstance);
        }
        curl_multi_close($multiInstance);

        $res = array();
//        echo "***********初始内存容量为".memory_get_usage()."********************\n";
        foreach($curlArray as $key => $curlRes){
            $res[] = curl_multi_getcontent($curlRes);
//            echo "***********{$key}的当前内存容量为".memory_get_usage()."********************\n";

        }
//        echo "***********存储内存容量为".memory_get_usage()."********************\n";

//        var_dump($res[0]);exit;
        return $res;
    }



}
