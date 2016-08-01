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

while (1) {
    $pdo = new db();
    $pdo->getInstance();
    $queue = $pdo->getQueue();
    $queueNickName = $queue[0]['nick_name'];
    $pdo -> updateQueueMark($queueNickName,2);
    $curlInstance = new curl($queueNickName, $config);
    //获取当前爬取用户信息,并且存入user表中
    $spiderFolloweeContent = $curlInstance->robotSpider('followees');
//    var_dump($spiderFolloweeContent);exit;
    $spiderFollowerContent = $curlInstance->robotSpider('followers');
    $res = regularExpression::getCurrentUserInfo($spiderFolloweeContent);
    $res[] = $queueNickName;

    if($res[0]==null){
        changeConfig();
        echo "\n\n\n***************************已经切换配置*******************************\n\n\n";
        continue;
    }
    if ($pdo->saveUser($res)) {
        echo "============{$res[0]}的个人信息已经录入============\n";
    } else {
        echo "============{$res[0]}的个人信息录入失败============\n";
    }


    $hashId = $res[11];
    $followeesNumber = $res[9];
    $followersNumber = $res[10];

    //为抓取用户关注表nick_name和被关注表的nick_name
    if ($followeesNumber > 19) {
        $queueSpiderContent = $curlInstance->robotSpider('followees', $followeesNumber, $hashId);
//        var_dump($queueSpiderContent);exit;
        $followees = regularExpression::getOnePageByNumber($queueSpiderContent . $spiderFolloweeContent);
    } else {
        $followees = regularExpression::getOnePageByNumber($spiderFolloweeContent);
    }


    echo "============{$res[0]}的应有{$res[9]}进入任务队列============\n";
    if ($pdo->saveQueue($followees[1])) {
        echo "============{$res[0]}的followees已经进入任务队列中============\n";
    } else {
        echo "============{$res[0]}的followees进入失败,怀疑为重复爬取============\n";
    }


    if ($followersNumber > 19) {
        $queueSpiderContent = $curlInstance->robotSpider('followers', $followersNumber, $hashId);
        $followers = regularExpression::getOnePageByNumber($queueSpiderContent . $spiderFollowerContent);
    } else {
        $followers = regularExpression::getOnePageByNumber($spiderFollowerContent);
    }

    //存储用户关系
    if($pdo->saveRelationT($queueNickName,$followees[1])){
        echo "============{$res[0]}的关注的用户已经进入relation表============\n";
    }else{
        echo "============{$res[0]}的关注的用户进入relation表失败============\n";
    }

    if ($pdo->saveQueue($followers[1])) {
        echo "============{$res[0]}的followers已经进入任务队列中============\n";
    } else {
        echo "============{$res[0]}的followers进入失败,怀疑为重复爬取============\n";
    }

    //一次爬取结束,更新queue的当前nickName的mark值
    if ($pdo->updateQueueMark($queueNickName)) {
        echo "============{$res[0]}的爬取成功结束============\n\n";
    } else {
        echo "============{$res[0]}的爬取失败结束============\n\n";

    }

}