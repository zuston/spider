# spider
php spider of zhihu


# USAGE
1. 采用sql文件中的db.sql进行建表
2. 修改db文件中的数据库连接配置
3. terminal中运行php index.php 和 php viceSpider.php(实在是太简单了)

# 爬虫思路
采用知乎的cookie进行模拟抓取，根据当前用户抓取自己的个人信息，关注者和被关注者。个人信息放入user表中，用户关系放入relation表中，关注者和被关注者的nick_name放入queue表中（任务队列中）。下一个用户从queue队列中取出未爬取过的用户，继续上一步骤。

### 出现问题
因为开始写的时候是curl单线程爬取，又因为关注列表需要多次ajax爬取，所以网络io开销很大，queue表填充很快，但是user信息抓取的很慢

#### 解决思路
开启curl_multi抓取，针对queue队列重新进行副进程进行单用户数据抓取


# TODO
1. 对网络io进行超时暂停、记录时长
2. 对抓取数据进行图表分析
3. 代码零零散散加起来有一天时间写完。很乱，结构需改进。
