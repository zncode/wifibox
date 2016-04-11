<?php
$mac        = $argv[1];                         //MAC地址
$script     = $argv[2];                         //Message
$id         = $argv[3];                         //ID
//$id             = time() . rand(0001, 9999);
$pub_topic  = "{$mac}/exec/cmd";                //订阅topic
$sub_topic  = "{$mac}/exec/result/{$id}";             //订阅topic
$msg_array      = array(
    'id'        => $id,
    'type'      => 'script',
    'data'      => $script,
);
$msg_json = json_encode($msg_array);

$client = new Mosquitto\Client();

//回调函数, 接受订阅消息
$client->onMessage(function($message){
    //获取服务器发送来的变量值
    $payload        = $message->payload;
    $payload        = json_decode($payload);
    $topic          = $message->topic;
    $topic_array    = explode('/', $topic);
    $id             = $topic_array[3];
    $data           = $payload->data;
    
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
for($i=0;$i<3;$i++)
{
    $client->loop();
    $client->publish($pub_topic, $msg_json, 1, 0);
    $client->loop();
    sleep(1);
}

//断开链接
$client->disconnect();
unset($client);
   // $redis = new Redis();
   // $redis->connect('localhost', '6379');
   // echo $redis->get($id);
