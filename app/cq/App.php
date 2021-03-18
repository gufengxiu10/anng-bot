<?php

declare(strict_types=1);

namespace app\cq;

use Anng\lib\facade\Reflection;
use app\cq\module\Message;
use Reflector;

class App
{
    private $server;
    private $frame;
    private $data;

    private array $moduel = [
        Message::class
    ];

    private $instanters = [];

    public function run($server, $frame)
    {
        $this->server = $server;
        $this->frame = $frame;
        $this->mount();
        $this->conversion();
        $this->init();
    }

    public function init()
    {
        $this->event();
    }

    public function event()
    {
        if (array_key_exists('post_type', $this->data) && $this->data['post_type'] == 'meta_event') {
            switch ($this->data['meta_event_type']) {
                case 'lifecycle':
                    break;
                case 'heartbeat':
                    $this->checkHeartbeat();
                    break;
            }
        } else {
            Reflection::setDefaultMethod('run', ['server' => $this->server, 'frame' => [
                'frame' => $this->frame,
                'data'  => $this->data
            ]])->instance('\\app\\event\\Message');
        }
    }

    /**
     * @name: 心跳检测
     * @param {Type} $var
     * @author: ANNG
     * @todo: 
     * @Date: 2021-03-18 09:36:29
     * @return {*}
     */
    public function checkHeartbeat()
    {
        //表示无法查询到在线状态
        if ($this->data['status']['online'] == null) {
        }
        // if($this->data['app_good'])
    }


    private function conversion()
    {
        $this->data = json_decode($this->frame->data, true);
    }

    public function mount()
    {
        foreach ($this->moduel as $value) {
            $this->instances[$value] = Reflection::instance($value);
        }
    }
}
