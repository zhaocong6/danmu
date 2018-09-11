<?php

class Server{

    private $serv;

    public function __construct()
    {
        $this->serv = new swoole_websocket_server('0.0.0.0', 9505);

        $this->serv->set([
            'reactor_num'   =>  8,
            'worker_num'    =>  8,
            'backlog'       =>  128,
            'max_request'   =>  1000
        ]);

        $this->serv->on('open', [$this, 'onOpen']);
        $this->serv->on('message', [$this, 'onMessage']);

        $this->serv->start();
    }

    public function onOpen(swoole_websocket_server $server, $request)
    {
        //ws链接开始
    }

    public function onMessage(swoole_websocket_server $server, $frame)
    {
        $redis = new Redis();
        $redis->connect('127.0.0.1', 6379);

        $redis->subscribe(['test'], function ($instance, $channelName, $message)use ($server, $frame){
            $server->push($frame->fd, $message);
        });
    }

}

(new Server());