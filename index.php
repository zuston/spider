<?php
/**
 * Created by PhpStorm.
 * User: zuston
 * Date: 16/7/12
 * Time: 下午2:54
 */

/**
 * 过程为先获取数据
 * 然后就为解析
 */
include './config/config.php';
include './core/curl.class.php';
include './core/regularExpression.function.php';
include './db/db.php';

while(1) {
    $pdo = new db();
    $pdo->getInstance();
    $queue = $pdo->getQueue();
    $queueNickName = $queue[0]['nick_name'];

//$pdo -> getQueueExsit('sha-ha-57');

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
//var_dump($savaRes);exit;


    $hashId = $res[11];
    $followees = $res[9];
    $followers = $res[10];

//为抓取用户关注表nick_name和被关注表的nick_name
    if ($followees > 19) {
        $queueSpiderContent = $curlInstance->robotSpider('followees', $res[9], $hashId);
        $followees = regularExpression::getOnePageByNumber($queueSpiderContent . $spiderFolloweeContent);
//    var_dump($followees[1]);exit;
    } else {
        $followees = regularExpression::getOnePageByNumber($spiderFolloweeContent);
//    var_dump($followees[1]);exit;
    }

    if ($pdo->saveQueue($followees[1])) {
        echo "============{$res[0]}的followees已经进入任务队列中===============================\n";
    } else {
        echo "============{$res[0]}的followees进入失败,怀疑为重复爬取===========================\n";
    }

//var_dump($followers);exit;
    if ($followers > 19) {
        $queueSpiderContent = $curlInstance->robotSpider('followers', $res[9], $hashId);
        $followers = regularExpression::getOnePageByNumber($queueSpiderContent . $spiderFolloweeContent);
    } else {
        $followers = regularExpression::getOnePageByNumber($spiderFollowerContent);
//    var_dump($followers[1]);exit;
    }

    if ($pdo->saveQueue($followers[1])) {
        echo "============{$res[0]}的followers已经进入任务队列中===============================\n";
    } else {
        echo "============{$res[0]}的followers进入失败,怀疑为重复爬取===========================\n";
    }

//一次爬取结束,更新queue的当前nickName的mark值
    if ($pdo->updateQueueMark($queueNickName)) {
        echo "============{$res[0]}的爬取成功结束=============================================\n";
    } else {
        echo "============{$res[0]}的爬取失败结束=============================================\n";

    }

}