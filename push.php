<?php

set_time_limit(0);

$redis = new Redis();
$redis->connect('127.0.0.1', 6379);

while (true){
    $redis->publish('test', 'hello world! '.uniqid());
    usleep(500000);
}
