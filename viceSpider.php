<?php
/**
 * Created by PhpStorm.
 * User: zuston
 * Date: 16/7/13
 * Time: 下午12:12
 */

include './config/config.php';
include './core/curl.class.php';
include './core/regularExpression.function.php';
include './db/db.php';


/**
 * 鉴于抓取关注者速度过慢,只针对queue中的请求进行处理,一次爬取个人信息
 */

while(1){
    $pdo = new db();
    $pdo->getInstance();
    $queueList = $pdo->getQueueList();
//    $queueNickName = $queue[0]['nick_name'];
    $nickNameArray = array();
    foreach($queueList as $key => $value){
        $pdo -> updateQueueMark($value["nick_name"],2);
        $nickNameArray[] = $value["nick_name"];
    }

    $curlInstance = new curl('', $config);
    //返回为数组
    $res = $curlInstance -> viceSpider($nickNameArray);

    foreach($res as $key => $user){
        if(!is_null($user)){
            $returnRes = regularExpression::getCurrentUserInfo($user);
            $returnRes[] = $nickNameArray[$key];
            if ($pdo->saveUser($returnRes)) {
                echo "============{$returnRes[0]}的个人信息已经录入============\n";
            } else {
                echo "============{$returnRes[0]}的个人信息录入失败============\n";
            }

            //一次爬取结束,更新queue的当前nickName的mark值
            if ($pdo->updateQueueMark($nickNameArray[$key],3)) {
                echo "============{$returnRes[0]}的爬取成功结束============\n";

            } else {
                echo "============{$returnRes[0]}的爬取失败结束============\n";

            }
        }else{
            continue;
        }
    }
}