<?php

//system("/usr/bin/php /var/www/wifibox/server.php 111", $a);
//print_r($a);

$file = popen("php /var/www/wifibox/server.php 111", 'r');
pclose($file);
