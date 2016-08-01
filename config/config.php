<?php
/**
 * Created by PhpStorm.
 * User: zuston
 * Date: 16/7/12
 * Time: 下午2:55
 */

$user_cookie_array = array(
    '__utma' => '51854390.1246899832.1469700345.1470040148.1470058448.10',
    '__utmb' => '51854390.31.9.1470058646291',
    '__utmc' => '51854390',
    '__utmv' => '51854390.100-1|2=registration_date=20131021=1^3=entry_date=20131021=1',
    '__utmz' => '51854390.1470058448.10.15.utmcsr=zhihu.com|utmccn=(referral)|utmcmd=referral|utmcct=/',
    '_xsrf' => '205ba8e98bdc6981ee2222c10f31de12',
    '_ga' => 'GA1.2.95149355.1461290900',
    '_za' => 'f4301e21-7db7-4e41-92f2-ad61c184d478',
    '_zap' => '4e9b5da1-5c60-4a4c-834a-8067187909e2',
    'a_t' => '"2.0AAAAmHsfAAAXAAAAOuDGVwAAAJh7HwAAABDAv_UBzwkXAAAAYQJVTTrgxlcACxqgb0mkBPZ6w_j8XbQ28aiZAXk1ZgpcjZA3qIX5UWmQsSBHcx4r5Q=="',
    'cap_id' => '"ZmQ1MjAwOGRkMWMwNGM4NTk4NDkyMTk0NTc2OTcyYzE=|1470059294|ad87b87dc526f059d0f60676ae50feb36937608f"',
    'd_c0' => '"ABDAv_UBzwmPTr1jglrSMQDVtr88FrNVpXo=|1461290871"',
    'l_cap_id' => '"ZTZkNTU5MTBmZmZjNGEzMGFiNmM4ZDM5MTgyZDcyMGQ=|1470059294|6352932c75f60254e13138ba33748fb2bfbd2d67"',

    'n_c' => '1',
    'login' => '"NzVmMDhhMWYyYTlkNGQ4NDk4ZDExMjU5YTYyYTk0ZmI=|1470059310|2919140c6443eea7949ef66192c2377e3b506ce2"',

    'q_c1' => '21b90dbc324e402aa6ce895519e58b5d|1469802528000|1464361687000',
    'z_c0' => 'Mi4wQUFBQW1Ic2ZBQUFBRU1DXzlRSFBDUmNBQUFCaEFsVk5PdURHVndBTEdxQnZTYVFFOW5yRC1QeGR0RGJ4cUprQmVR|1470059322|c42af22b90105395a7c032539f519fe0edaaf26b'
);

function getCookie($user_cookie_array){
    $cookie = "";
    foreach($user_cookie_array as $key => $value){
        if($key != 'z_c0'){
            $cookie .= $key .'=' .$value.';';
        }else{
            $cookie .= $key .'=' .$value;
        }
    }
    return $cookie;
}

$config = array(
    'user_config' => array(
        'user_config_cookie' => getCookie($user_cookie_array),
        '_xsrf' => $user_cookie_array['_xsrf'],
    ),

    'db_config' => array(
        'host' => 'localhost',
        'dbname' => 'spider',
        'username' => 'root',
        'pwd' => 'zuston',
    ),
);