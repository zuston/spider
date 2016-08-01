<?php
/**
 * Created by PhpStorm.
 * User: zuston
 * Date: 16/7/12
 * Time: 下午2:55
 */

//我的账号cookie
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


//ann的cookie
$user_cookie_array_default = array(
    '__utma' => '51854390.938105749.1469526692.1469526692.1470059884.2',
//    '__utmb' => '51854390.31.9.1470058646291',

    '__utmc' => '51854390',

    '__utmv' => '51854390.100--|2=registration_date=20160801=1^3=entry_date=20160325=1',

    '__utmz' => '51854390.1470059884.2.2.utmcsr=zhihu.com|utmccn=(referral)|utmcmd=referral|utmcct=/',

    '_xsrf' => 'c718a5e1b5f296490411843c665409c3',
//    '_ga' => 'GA1.2.95149355.1461290900',

    '_za' => '01534bea-12d6-4d5d-b0a0-3014c1eafe01',

    '_zap' => '1072b591-5cc8-4614-9161-e938c9c0c4d1',

    'a_t' => '"2.0AECA78anUQoXAAAAguLGVwBAgO_Gp1EKABDAuZ-HqwkXAAAAYQJVTYLixlcA13IpiVE4_Sd-EbbVpIevKDe1sfDYxLZM0dc6BF-WwOuV2q9I4_mPhw=="',


    'cap_id' => '"NjcxNTdlODY2MDA0NGI2NWIwNDU4MTk5MWMyNjJmOGQ=|1470059879|17711b891102fd5e775ca5dabd65333136cf1be4"',

    'd_c0' => '"ABDAuZ-HqwmPTsyG0GXqjD2ax85m-5ckpkE=|1458909991"',

    'l_cap_id' => '"NmRiNjgyNDU1MDMwNGY4ZjkwMTYxMWJlMTkyNGY2Nzg=|1470059879|637781e7cfb76859f95d4d2d8597d5740a3bb773"',

    'n_c' => '1',
    'login' => '"ZGVkNmZlNjUwOWEzNDBkYjg3NzY0MmVkZmU2MGU1ODQ=|1470059905|e8ca551ac34dfccc3a0d87c6d225c7b93b521a33"',

    'q_c1' => 'd4a4f99f796f4fc98bc835da74715dff|1468399630000|1458909991000',
    'z_c0' => 'Mi4wQUVDQTc4YW5VUW9BRU1DNW40ZXJDUmNBQUFCaEFsVk5ndUxHVndEWGNpbUpVVGo5SjM0UnR0V2toNjhvTjdXeDhB|1470059906|bdc9efea9e30f18867ea5ef4b07c542f447eb7bc'
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
        'hash_id' => '1ab5d5d3c2d8bdbf5504e7c53057d008'
    ),

    'user_config_default' => array(
        'user_config_cookie' => getCookie($user_cookie_array_default),
        '_xsrf' => $user_cookie_array['_xsrf'],
        'hash_id' => "2b8f368c7da8a8e775e30842672e0a13"
    ),

    'db_config' => array(
        'host' => 'localhost',
        'dbname' => 'spider',
        'username' => 'root',
        'pwd' => 'zuston',
    ),
);

$changeFlag = 1;


function changeConfig(){
    global $changeFlag;
    $changeFlag += 1;
    if($changeFlag%2==0){
        global $config;
        global $user_cookie_array_default;
        $config['user_config'] = array(
            'user_config_cookie' => getCookie($user_cookie_array_default),
            '_xsrf' => $user_cookie_array_default['_xsrf'],
            'hash_id' => "2b8f368c7da8a8e775e30842672e0a13"
        );
    }else{
        global $config;
        global $user_cookie_array;
        $config['user_config'] = array(
            'user_config_cookie' => getCookie($user_cookie_array),
            '_xsrf' => $user_cookie_array['_xsrf'],
            'hash_id' => "2b8f368c7da8a8e775e30842672e0a13"
        );
    }

}