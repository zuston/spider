<?php

/**
 * Created by PhpStorm.
 * User: zuston
 * Date: 16/7/13
 * Time: 上午7:53
 */

class db
{

    public $config = array(
        'db_config' => array(
        'host' => 'localhost',
        'dbname' => 'spider',
        'username' => 'root',
        'pwd' => 'zuston',
        ),
    );


    public static $dbConnection=null;

    public function getInstance(){
        if(is_null(self::$dbConnection)){
            $dsn = 'mysql:host='.$this->config['db_config']['host'].';dbname='.$this->config['db_config']['dbname'];
            $username = $this->config['db_config']['username'];
            $pwd = $this->config['db_config']['pwd'];
            self::$dbConnection = new PDO($dsn,$username,$pwd);
        }
        self::$dbConnection->exec("SET names utf8");
        return self::$dbConnection;
    }

    public function getQueue(){
        $sql = 'select * from queue where mark = 0 order by id limit 1';
        $res = self::$dbConnection->query($sql);
//        var_dump($res->fetchAll());exit;
        return $res -> fetchAll();
    }

    public function saveUser($res){

        $name = '"'.$res[0].'"';
        $title = '"'.$res[1].'"';
        $location = '"'.$res[2].'"';
        $bussiness = '"'.$res[3].'"';
        $sex = ($res[4]=='male')?1:0;
        $employment = '"'.$res[5].'"';
        $position = '"'.$res[6].'"';
        $education = '"'.$res[7].'"';
        $educationExtra = '"'.$res[8].'"';
        $hashId = '"'.$res[11].'"';
        $followees = $res[9];
        $followers = $res[10];
        $nickName = '"'.$res[12].'"';
//
//        `id` bigint(11) unsigned NOT NULL AUTO_INCREMENT,
//  `nick_name` char(20) NOT NULL DEFAULT '',
//  `name` char(40) DEFAULT NULL,
//  `title` varchar(200) DEFAULT NULL,
//  `location` char(40) DEFAULT NULL,
//  `bussiness` char(20) DEFAULT NULL,
//  `sex` tinyint(4) NOT NULL DEFAULT '0',
//  `employment` varchar(40) DEFAULT NULL,
//  `position` char(40) DEFAULT NULL,
//  `education` char(40) DEFAULT NULL,
//  `education_extra` char(40) DEFAULT NULL,
//  `followee_number` int(11) NOT NULL DEFAULT '0',
//  `follower_number` int(11) NOT NULL DEFAULT '0',
//  `hash_id` char(40) DEFAULT NULL,

        $sql = "insert into
USER(nick_name,name,title,location,bussiness,sex,employment,position,
education,education_extra,followee_number,follower_number,hash_id)
VALUES({$nickName},{$name},{$title},{$location},{$bussiness},
{$sex},{$employment},{$position},{$education},{$educationExtra},
{$followees},{$followers},{$hashId})";
//        var_dump($sql);exit;
        $returnRes = self::$dbConnection -> exec($sql);
        return ($returnRes!=0)?true:false;
    }


    /**
     * @param $queueList
     * 要进行queue检查,为重复检查
     * 合法性检查
     */
    public function saveQueue($queueList){
        $sql = "insert into queue(nick_name,mark) VALUES";
        foreach($queueList as $key => $value){
            $value = '"'.$value.'"';
            if(self::getQueueNoExsit($value)){
                echo "---------{$value}=====可以进入任务队列------------\n";
                $sql .= "($value,0),";
            }else{
                echo "---------{$value}====不可以进入任务队列------------\n";
                continue;
            }

        }
        $returRes = self::$dbConnection -> exec(rtrim($sql,","));
        return $returRes==false?false:true;
        var_dump($returRes);exit;
        var_dump(rtrim($sql,","));exit;
    }

    public function updateQueueMark($nickName){
        $nickName = '"'.$nickName.'"';
        $sql = "update queue set mark = 1 where nick_name = {$nickName}";
        $returnRes = self::$dbConnection->exec($sql);
        return $returnRes==1?true:false;
    }

    private function getQueueNoExsit($nickName){
//        $nickName = '"'.$nickName.'"';
        $sql = "select count(id) from queue where nick_name = {$nickName}";
        $res = self::$dbConnection->query($sql)->fetch();
//        var_dump($res);exit;
        if($res[0]==0){
            return true;
        }
        return false;
    }



}