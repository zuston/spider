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

$curlInstance = new curl('sha-cha-57',$config);
$result = $curlInstance -> robotSpider('followees');
$regularExpress = new regularExpression($result);
$res = $regularExpress -> getCurrentUserInfo();
var_dump($res);
$hashID = $res[11];
$result = $curlInstance -> robotSpider('followees',$res[9],$hashID);
$regularExpress = new regularExpression($result);
$followees = $regularExpress -> getOnePageByNumber();
var_dump($followees);exit;


//echo "-------------------正则解析共消耗".$expressAllTime."s----------------\n";
var_dump($followees);exit;
//var_dump($res);
