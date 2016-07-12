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

$curlInstance = new curl('tian-yuan-dong',$config);
$result = $curlInstance -> robotSpider('followees',67);
var_dump($result);exit;
$resultOrigin = json_decode($result)->msg;

$result = '';
foreach($resultOrigin as $key => $value){
    $result .= $value;
}

$regularExpress = new regularExpression($result);
//$res = $regularExpress -> getCurrentUserInfo();
//$followees = $regularExpress -> getOneFolloweeAndFollowerInfo();
$followees = $regularExpress -> getOnePageByNumber(20);
var_dump($followees);exit;
//var_dump($res);
