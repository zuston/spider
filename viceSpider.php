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

//    var_dump($res[0]);exit;
    foreach($res as $key => $user){
        if(!is_null($user)){
            $returnRes = regularExpression::getCurrentUserInfo($user);
//            var_dump($returnRes);exit;
            if(is_null($returnRes[0])){
//                $returnRes[] = $nickNameArray[$key];
//                var_dump($returnRes);
//                echo "============此处内存泄露============\n";
                continue;
            }
            $returnRes[] = $nickNameArray[$key];
            if ($pdo->saveUser($returnRes)) {
                echo "============{$returnRes[0]}的个人信息已经录入============\n";
            } else {
                echo "============{$returnRes[0]}的个人信息录入失败============\n";
            }

            //一次爬取结束,更新queue的当前nickName的mark值
            if ($pdo->updateQueueMark($nickNameArray[$key],3)) {
                echo "============{$returnRes[0]}的爬取成功结束============\n\n\n";

            } else {
                echo "============{$returnRes[0]}的爬取失败结束============\n\n\n";

            }
        }else{
            changeConfig();
            echo "\n\n\n***************************已经切换配置*******************************\n\n\n";
            continue;
        }
    }

    unset($res);
    $res = null;
}