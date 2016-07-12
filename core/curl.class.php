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
    //多页关注者的唯一id值
    public $hashId;

    public function __construct($name,$config=array())
    {
        $this -> name = $name;
        $this -> config = $config;
//        $this -> hashId = $hashId;
    }

    //获取关注者和被关注者
    //有第二个参数为多页获取
    public function robotSpider($topicUrl,$followeeOrFollowerNumber=null,$hashId=null){
        echo "--------------正在努力爬取---------------\n";
        if(is_null($followeeOrFollowerNumber)&&is_null($hashId)){
            $url = 'https://www.zhihu.com/people/'.$this->name.'/'.$topicUrl;
            $mainConent = self::curlCore($url);
            return $mainConent;
        }

        //设置开始值为67
        //1-19         20-38   39-57 58-76(67)
        //times = 2
        //remainder = 1
        $this -> hashId = $hashId;
        if($followeeOrFollowerNumber>19){
            if($topicUrl=="followees"){
                $topic = 1;
            }elseif($topicUrl=="followers"){
                $topic = 0;
            }

            $pagesRes = '';
            //除法注意,踩坑了
            $times = floor($followeeOrFollowerNumber/19)-1;
            echo "---------------分为".$times."次----------------\n";
            echo "---------------关注".$followeeOrFollowerNumber."人次----------------\n";
//            $remainder = $followeeOrFollowerNumber - ($times+1)*19;
            if($times>1){
                for($i=1;$i<=$times;$i++){
                    $pagesRes .= self::getRemainInfo($i*19+1,$topic);
                }
            }
            $pagesRes .= self::getRemainInfo(($times+1)*19+1,$topic);
            return $pagesRes;
        }
//        echo "-------------------共消耗".$allTime."s----------------\n";

    }


    private function curlCore($url,$method=null,$fields=array()){
        $http_user_agent = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36';
        $ch = curl_init($url); //初始化会话
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_COOKIE, $this->config['user_config']['user_config_cookie']);
        curl_setopt($ch, CURLOPT_USERAGENT, $http_user_agent);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //将curl_exec()获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        if($method=='POST'&&is_array($fields)){
            curl_setopt($ch, CURLOPT_POST, true );
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        }
        $result = curl_exec($ch);
//        curl_close($ch);
        return $result;
    }

    //测试多页获取ajax
    public function getPagesDemo($startNumber){
        $fields = array(
            'method' => 'next',
            'params' => '{"offset":'.$startNumber.',"order_by":"created","hash_id":"1ab5d5d3c2d8bdbf5504e7c53057d008"}',
            '_xsrf' => '241ee0343b4e40cb6d6bd1c2489e0c8d'
            );
        $content =  self::curlCore('http://www.zhihu.com/node/ProfileFolloweesListV2','POST',$fields);
        $result = '';
        foreach(json_decode($content)->msg as $key => $value){
            $result .= $value;
        }
        return $result;
    }

    private function getRemainInfo($startNumber,$topic){
        $hash_id = '"'.$this->hashId.'"';
        $fields = array(
            'method' => 'next',
            'params' => '{"offset":'.$startNumber.',"order_by":"created","hash_id":'.$hash_id.'}',
            '_xsrf' => $this->config['user_config']['_xsrf'],
        );
        if($topic==1){
            $content =  self::curlCore('http://www.zhihu.com/node/ProfileFolloweesListV2','POST',$fields);
        }elseif($topic==0){
            $content =  self::curlCore('http://www.zhihu.com/node/ProfileFollowersListV2','POST',$fields);

        }
//        $content =  self::curlCore('http://www.zhihu.com/node/ProfileFolloweesListV2','POST',$fields);
        $result = '';
        foreach(json_decode($content)->msg as $key => $value){
            $result .= $value;
        }
        return $result;
    }
}
