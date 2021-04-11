<?php

use Anng\event\Request;

return [
    'server'    => 'websocket',
    'ip'        => "0.0.0.0",
    'prot'      => 9502,
    'work_num'  => 3,
    'event'     => [
        'request'   => Request::class
    ]
];
