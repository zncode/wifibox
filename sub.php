<?php
$sub_topic  = "006688000000/exec/cmd";
$client = new Mosquitto\Client();
$client->onMessage(function($message) {
    $payload        = $message->payload;
    $payload        = json_decode($payload);
    $data           = $payload->data;
    echo $data;
});
$client->setCredentials('php', '');
$client->connect("cloud.big.openfin.com", 1883, 5);
$client->subscribe($sub_topic, 1);
while (true) {
	$client->loop(10);
}
