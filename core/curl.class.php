<?php
/**
 * Created by PhpStorm.
 * User: zuston
 * Date: 16/7/12
 * Time: 下午2:57
 */

class curl{

    public $name;
    public $config = array();

    public function __construct($name,$config=array())
    {
        $this -> name = $name;
        $this -> config = $config;
    }

    public function robotSpider($topicUrl=null){
        if(is_null($topicUrl)){
            $url = 'https://www.zhihu.com/people/'.$this->name;
        }else{
            $url = 'https://www.zhihu.com/people/'.$this->name.'/'.$topicUrl;
        }
        return self::curlCore($url);
    }


    private function curlCore($url){
        $http_user_agent = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36';
        $ch = curl_init($url); //初始化会话
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_COOKIE, $this->config['user_config']['user_config_cookie']);
        curl_setopt($ch, CURLOPT_USERAGENT, $http_user_agent);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //将curl_exec()获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}
