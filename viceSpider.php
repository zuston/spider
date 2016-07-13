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
    $queue = $pdo->getQueue();
    $queueNickName = $queue[0]['nick_name'];
    $pdo -> updateQueueMark($queueNickName,2);

    $curlInstance = new curl($queueNickName, $config);
    //获取当前爬取用户信息,并且存入user表中
    $spiderFolloweeContent = $curlInstance->robotSpider('followees');
    $spiderFollowerContent = $curlInstance->robotSpider('followers');
    $res = regularExpression::getCurrentUserInfo($spiderFolloweeContent);
    $res[] = $queueNickName;
    if ($pdo->saveUser($res)) {
        echo "============{$res[0]}的个人信息已经录入======================================\n";
    } else {
        echo "============{$res[0]}的个人信息录入失败===========================\n";
    }

    //一次爬取结束,更新queue的当前nickName的mark值
    if ($pdo->updateQueueMark($queueNickName,3)) {
        echo "============{$res[0]}的爬取成功结束=============================================\n";
    } else {
        echo "============{$res[0]}的爬取失败结束=============================================\n";

    }
}