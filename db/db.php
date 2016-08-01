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


    public static $dbConnection = null;

    public function getInstance()
    {
        if (is_null(self::$dbConnection)) {
            $dsn = 'mysql:host=' . $this->config['db_config']['host'] . ';dbname=' . $this->config['db_config']['dbname'];
            $username = $this->config['db_config']['username'];
            $pwd = $this->config['db_config']['pwd'];
            self::$dbConnection = new PDO($dsn, $username, $pwd);
        }
        self::$dbConnection->exec("SET names utf8");
        return self::$dbConnection;
    }

    public function getQueue()
    {
        $sql = 'select * from queue where mark = 0 order by id limit 1';
        $res = self::$dbConnection->query($sql);
//        var_dump($res->fetchAll());exit;
        return $res->fetchAll();
    }

    public function getQueueList(){
        $sql = 'select * from queue where mark = 0 order by id limit 4';
        $res = self::$dbConnection->query($sql);
//        var_dump($res->fetchAll());exit;
        return $res->fetchAll();
    }

    public function saveUser($res)
    {

        $name = '"' . $res[0] . '"';
        $title = '"' . $res[1] . '"';
        $location = '"' . $res[2] . '"';
        $bussiness = '"' . $res[3] . '"';
        $sex = ($res[4] == 'male') ? 1 : 0;
        $employment = '"' . $res[5] . '"';
        $position = '"' . $res[6] . '"';
        $education = '"' . $res[7] . '"';
        $educationExtra = '"' . $res[8] . '"';
        $hashId = '"' . $res[11] . '"';
        $followees = $res[9];
        $followers = $res[10];
        $nickName = '"' . $res[12] . '"';

        $sql = "insert into
                user(nick_name,name,title,location,bussiness,sex,employment,position,
                     education,education_extra,followee_number,follower_number,hash_id)
                VALUES({$nickName},{$name},{$title},{$location},{$bussiness},
                        {$sex},{$employment},{$position},{$education},{$educationExtra},
                        {$followees},{$followers},{$hashId}
                       );";
        $returnRes = self::$dbConnection->exec($sql);
        if($returnRes==0){
            var_dump($res);exit;
        }
        return ($returnRes != 0) ? true : false;
    }


    /**
     * @param $queueList
     * @return bool
     * 合法性检查
     */
    public function saveQueue($queueList)
    {
        $sql = "insert into queue(nick_name,mark) VALUES";
        $count = 0;
        foreach ($queueList as $key => $value) {
            $value = '"' . $value . '"';
            if (self::getQueueNoExsit($value)) {
//                echo "---------[{$name}]可以进入任务队列------------\n";
                $count++;
                $sql .= "($value,0),";
            } else {
                continue;
            }
        }
//        echo rtrim($sql,",");exit;
        echo "============{$count}进入任务队列===========\n";
        $returRes = self::$dbConnection->exec(rtrim($sql, ","));
        return $returRes == false ? false : true;
    }

    //status:0=>未抓取,1=>抓取完成,2=>正在抓取,3=>只是完成了当前用户的个人信息部分(未抓取关注和被关注的)
    public function updateQueueMark($nickName, $status = 1)
    {
        $nickName = '"' . $nickName . '"';
        $sql = "update queue set mark = {$status} where nick_name = {$nickName}";
        $returnRes = self::$dbConnection->exec($sql);
        return $returnRes == 1 ? true : false;
    }


    /**
     * @param $followee_nick_name
     * @param $queueList
     * @return bool
     * 此处为currentUser关注的人的关系
     */
    public function saveRelationT($followee_nick_name,$queueList)
    {
        $followee_nick_name = '"' . $followee_nick_name . '"';
        $sql = "insert into relation(followee_nick_name,follower_nick_name) VALUES ";
        foreach($queueList as $key => $value){
            $value = '"' . $value . '"';
            $sql .= "({$followee_nick_name},{$value}),";
        }
        $sql = rtrim($sql,",");
//        var_dump($sql);exit;
        $returnRes = self::$dbConnection -> exec($sql);
        return $returnRes==0?false:true;
    }

    /**
     * @param $followee_nick_name
     * @param $queueList
     * @return bool
     * 此处为cyrrentUser被关注的人的关系
     */
    public function saveRelationF($followee_nick_name,$queueList)
    {
        $followee_nick_name = '"' . $followee_nick_name . '"';
        $sql = "insert into relation(followee_nick_name,follower_nick_name) VALUES ";
        foreach($queueList as $key => $value){
            $sql .= "({$followee_nick_name},{$value}),";
        }
        $sql = rtrim($sql,",");
        var_dump($sql);exit;
        $returnRes = self::$dbConnection -> exec($sql);
        return $returnRes==0?false:true;
    }

    private function getQueueNoExsit($nickName)
    {
        $sql = "select count(id) from queue where nick_name = {$nickName}";
        $res = self::$dbConnection->query($sql)->fetch();
        if ($res[0] == 0) {
            return true;
        }
        return false;
    }
}
