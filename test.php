<?php
/**
 * Created by PhpStorm.
 * User: zuston
 * Date: 16/7/12
 * Time: 下午11:37
 */


include './config/config.php';
include './core/curl.class.php';
include './core/regularExpression.function.php';
include './db/db.php';

/**
 * db_test
 */

$pdo = new db();
$pdo->getInstance();
//var_dump($conn);exit;
$pdo->getQueues();


$curlInstance = new curl('tian-yuan-dong',$config);
$result = $curlInstance -> robotSpider('followees');
//$regularExpress = new regularExpression($result);
$res = regularExpression::getCurrentUserInfo($result);
var_dump($res);
$hashID = $res[11];
$result = $curlInstance -> robotSpider('followees',$res[9],$hashID);
//$regularExpress = new regularExpression($result);
$followees = regularExpression::getOnePageByNumber($result);
var_dump($followees);exit;


//echo "-------------------正则解析共消耗".$expressAllTime."s----------------\n";
var_dump($followees);exit;
//var_dump($res);
