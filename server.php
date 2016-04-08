<?php
$mac        = $argv[1];                         //MAC地址
$script     = $argv[2];                         //MAC地址
$pub_topic  = "{$mac}/exec/cmd";                //订阅topic
$sub_topic  = "{$mac}/exec/result";             //订阅topic
$id             = time() . rand(0001, 9999);    //随机ID
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
    $payload = $message->payload;
    $payload = json_decode($payload);

   // $id      = $message->id;
    $data  = $payload->data;
    echo $data;

    //打印提示
    // printf('Got a subscrib result id  '.$id.' | ');

    //存储数据
    // $redis = new Redis();
    // $redis->connect('localhost', '6379');
    // $redis->set($id, $data);
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

