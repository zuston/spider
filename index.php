<?php
/**
 * Created by PhpStorm.
 * User: zuston
 * Date: 16/7/12
 * Time: 下午2:54
 */

include './config/config.php';
include './core/curl.class.php';
include './core/regularExpression.function.php';

$curlInstance = new curl('tian-yuan-dong',$config);
$result = $curlInstance -> robotSpider('followees');

$regularExpress = new regularExpression($result);
$res = $regularExpress -> getCurrentUserInfo();
$followees = $regularExpress -> getOneFolloweeAndFollowerInfo();
var_dump($followees);exit;
var_dump($res);
