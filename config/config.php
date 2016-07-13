<?php
/**
 * Created by PhpStorm.
 * User: zuston
 * Date: 16/7/12
 * Time: 下午2:55
 */

$user_cookie_array = array(
    '__utma' => '51854390.95149355.1461290900.1467301310.1468161996.20',
    '__utmb' => '51854390.14.10.1468161996',
    '__utmc' => '51854390',
    '__utmv' => '51854390.100-1|2=registration_date=20131021=1^3=entry_date=20131021=1',
    '__utmz' => '51854390.1468161996.20.17.utmcsr=google|utmccn=(organic)|utmcmd=organic|utmctr=(not%20provided)',
    '_xsrf' => '241ee0343b4e40cb6d6bd1c2489e0c8d',
    '_ga' => 'GA1.2.95149355.1461290900',
    '_za' => 'f4301e21-7db7-4e41-92f2-ad61c184d478',
    '_zap' => '4e9b5da1-5c60-4a4c-834a-8067187909e2',
    'a_t' => '"2.0AAAAmHsfAAAXAAAAWfSpVwAAAJh7HwAAABDAv_UBzwkXAAAAYQJVTWHzqVcAIJeIXdt8RIlrnMD0qLFjYapy-ZkPiJYttW0LMsmRdhBpoWVvPAUYRQ=="',
    'cap_id' => '"Y2ZmOTNkOTE3Y2RlNDBkNmIwNTNkZDhlYTNmN2NkNWE=|1468163663|1e2c41d7590fa805d365394c6ece5914cc3f4f78"',
    'd_c0' => '"ABDAv_UBzwmPTr1jglrSMQDVtr88FrNVpXo=|1461290871"',
    'l_cap_id' => '"NGIxMWJlOTkwNzhjNGNhYjliOTliYjQ0OTM0Y2I4ZjY=|1468163663|f253784b436a6f9372c60d4a0eefad22324d628d"',

    'n_c' => '1',
    'login' => '"MmFjYzFiZTVmYzhjNDExYjljOTllOGRjYmU1NGQwMmU=|1468163667|5c7a628255ba1bd75dc1376b289c13547e154d76"',

    'q_c1' => '21b90dbc324e402aa6ce895519e58b5d|1467170201000|1464361687000',
    'z_c0' => 'Mi4wQUFBQW1Ic2ZBQUFBRU1DXzlRSFBDUmNBQUFCaEFsVk5ZZk9wVndBZ2w0aGQyM3hFaVd1Y3dQU29zV05ocW5MNW1R|1468163681|74f432909262baa565c6be77d38759ec2509eeca'
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