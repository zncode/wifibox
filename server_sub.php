<?php
$sub_topic  = "+/exec/result/+";       //订阅topic

$client = new Mosquitto\Client();

//回调函数, 接受订阅消息
$client->onMessage(function($message){
    //获取服务器发送来的变量值

    $payload        = $message->payload;
    $payload        = json_decode($payload);
    $topic          = $message->topic;
    $topic_array    = explode('/', $topic);
    $mac            = $topic_array[0];
    $data           = $payload->data;
    $id           = $payload->id;
//var_dump($message);
    echo $id.' | ';

    //存储数据
    $redis = new Redis();
    $redis->connect('localhost', '6379');
    $redis->set($id, $data);

});

//链接
//$client->connect("localhost", 1883, 5);
$client->setCredentials('php', '');
$client->connect("cloud.big.openfin.com", 1883, 5);

//订阅
$client->subscribe($sub_topic, 1);
$client->loopForever();
$client->disconnect();
unset($client);

