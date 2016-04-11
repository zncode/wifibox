<?php
$mac        = $argv[1];                         //MAC地址
$script     = $argv[2];                         //Message
$id         = $argv[3];                         //ID
$pub_topic  = "{$mac}/exec/cmd";                //订阅topic
$msg_array      = array(
    'id'        => $id,
    'type'      => 'script',
    'data'      => $script,
);
$msg_json = json_encode($msg_array);

$client = new Mosquitto\Client();

//链接
//$client->connect("localhost", 1883, 5);
$client->setCredentials('php', '');
$client->connect("cloud.big.openfin.com", 1883, 5);

//for($i=0;$i<3;$i++)
//{
    $client->publish($pub_topic, $msg_json, 1, 0);
//    sleep(1);
//}
$client->disconnect();
unset($client);
