<?php

/**
 * Created by PhpStorm.
 * User: zuston
 * Date: 16/7/12
 * Time: 下午2:53
 */
class regularExpression
{
    public $infoContent;

    public function __construct($infoContent)
    {
        $this -> infoContent = $infoContent;
    }

    public function getOneFolloweeAndFollowerInfo(){
        preg_match_all('#<a title="(.*)"\ndata-tip="p\$t\$(.*)"\nclass="zm-item-link-avatar"\nhref="/people/\2">\n<img src="(.*)" class="zm-item-img-avatar">\n</a>\n<div class="zm-list-content-medium">\n<h2 class="zm-list-content-title"><a data-tip="p\$t\$\2" href="https://www.zhihu.com/people/\2" class="zg-link" title="\1"\n>\1</a></h2>\n\n<div class="zg-big-gray">(.*)</div>\n<div class="details zg-gray">\n<a target="_blank" href="/people/\2/followers" class="zg-link-gray-normal">(.*)关注者</a>\n\/\n<a target="_blank" href="/people/\2/asks" class="zg-link-gray-normal">(.*)提问</a>\n\/\n<a target="_blank" href="/people/\2/answers" class="zg-link-gray-normal">(.*)回答</a>\n\/\n<a target="_blank" href="/people/\2" class="zg-link-gray-normal">(.*)赞同</a>#',
            $this->infoContent,$res);
        return $res;
    }

    public function getOnePageByNumber(){
        preg_match_all('#<a title=".*"\ndata-tip="p\$t\$(.*)"#',$this->infoContent,$result);
        return $result;
    }

    public function getCurrentUserInfo(){
        $res = array();
        $res[] = self::getNameAndTitle();
        $res[] = self::getLocation();
        $res[] = self::getBusiness();
        $res[] = self::getSex();
        $res[] = self::getEmployment();
        $res[] = self::getPosition();
        $res[] = self::getEducation();
        $res[] = self::getEducationExtra();
        $res[] = self::getFolloweeNumber();
        $res[] = self::getFollowerNumber();
        $res[] = self::getHashId();
//        var_dump($res);exit;
        $reRes = array();
        foreach($res as $key=>$value){
            foreach($value as $k => $v){
                if($k!=0){
                    @$reRes[] = $v[0];
                }
            }
        }
//        var_dump($reRes);
//        exit;
        return $reRes;
    }

    private function getNameAndTitle(){
        preg_match_all('#<a class="name" href="/people/.*">(.*)</a>，<span class="bio" title="(.*)">\2</span>#',$this->infoContent,$res);
//        var_dump($res);exit;
        return $res;
    }


    private function getLocation(){
        preg_match_all('#<span class="location item" title="(.*)"><a href=".*" title="\1" class="topic-link" data-token=".*" data-topicid=".*">\1</a></span>#',$this->infoContent,$res);
        return $res;
    }

    private function getBusiness(){
        preg_match_all('#<span class="business item" title="(.*)"><a href=".*" title="\1" class="topic-link" data-token=".*" data-topicid=".*">\1</a></span>#',$this->infoContent,$res);

        return $res;
    }

    private function getSex(){
        preg_match_all('#<span class="item gender" ><i class="icon icon-profile-(.*)"></i></span>#',$this->infoContent,$res);
        return $res;
    }

    private function getEmployment(){
        preg_match_all('#<span class="employment item" title="(.*)"><a href=".*" title="\1" class="topic-link" data-token=".*" data-topicid=".*">\1</a></span>#',$this->infoContent,$res);
        return $res;
    }

    private function getPosition(){
        preg_match_all('#<span class="position item" title="(.*)">\1</span>#',$this->infoContent,$res);
        return $res;
    }

    private function getEducation(){
        preg_match_all('#<span class="education item" title="(.*)"><a href=".*" title="\1" class="topic-link" data-token=".*" data-topicid=".*">\1</a></span>#',$this->infoContent,$res);
        return $res;
    }

    private function getEducationExtra(){
        preg_match_all('#<span class="education-extra item" title=\'(.*)\'><a href="/topic/.*" title="\1" class="topic-link" data-token=".*" data-topicid=".*">\1</a></span>#',$this->infoContent,$res);
        return $res;
    }

    private function getFolloweeNumber(){
        preg_match_all('#<span class="zg-gray-normal">关注了</span><br />\n<strong>(.*)</strong><label> 人</label>#',$this->infoContent,$res);
        return $res;
    }

    private function getFollowerNumber(){
        preg_match_all('#<span class="zg-gray-normal">关注者</span><br />\n<strong>(.*)</strong><label> 人</label>#',$this->infoContent,$res);
        return $res;
    }

    private function getHashId(){
        preg_match_all('#<script type="text/json" class="json-inline" data-name="current_people">\[.*jpg"\,"(.*)"\]#',$this->infoContent,$res);
        return $res;
    }

}