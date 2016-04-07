<?php

$mac = '111';                   //MAC地址
$topic = "{$mac}/exec/shell";   //订阅topic

$client = new Mosquitto\Client();

//回调函数, 接受订阅消息
$message = $client->onMessage(function($message){

    //获取服务器发送来的变量值
    $payload = $message->payload;
    $payload = json_decode($payload);
    $id      = $payload->id;
    $script  = $payload->script;

    //打印提示
    printf('Got a subscrib id  '.$id.' | ');

    //发送结果给MQTT
    $client = new Mosquitto\Client();
    $client->connect("localhost", 1883, 5);
    $msg_json = array('mac'=>'111', 'result'=>'result_content | '.$script, 'id'=>$id);
    $msg_json = json_encode($msg_json);
    $client->publish('111/exec/shell/result', $msg_json, 1, 0);
});

//链接
$client->connect("localhost", 1883, 5);

//订阅
$client->subscribe($topic, 1);

//循环请求
while (true) {
    $client->loop();
}

//断开链接
$client->disconnect();
unset($client);

