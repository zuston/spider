<?php
/**
 * Created by PhpStorm.
 * User: zuston
 * Date: 16/7/12
 * Time: 下午11:37
 */

/**
 * db_test
 */

include './config/config.php';
include './core/curl.class.php';
include './core/regularExpression.function.php';
include './db/db.php';

//$pdo = new db();
//$pdo->getInstance();
//$queue = $pdo->getQueue();
//$queueNickName = $queue[0]['nick_name'];
//$pdo->updateQueueMark($queueNickName, 2);

$curlInstance = new curl('cai-xiao', $config);
//获取当前爬取用户信息,并且存入user表中
$spiderFolloweeContent = $curlInstance->robotSpider('followees');
$res = regularExpression::getCurrentUserInfo($spiderFolloweeContent);
$res[] = 'xuxiaoteng';

var_dump($res);exit;

$pdo = new db();
$pdo->getInstance();
var_dump($pdo->saveUser($res));exit;

$hashId = $res[11];
$followees = $res[9];
$followers = $res[10];

//为抓取用户关注表nick_name和被关注表的nick_name
if ($followees > 19) {
    $queueSpiderContent = $curlInstance->robotSpider('followees', $followees, $hashId);
    $followees = regularExpression::getOnePageByNumber($queueSpiderContent . $spiderFolloweeContent);
} else {
    $followees = regularExpression::getOnePageByNumber($spiderFolloweeContent);
}
var_dump($followees);


