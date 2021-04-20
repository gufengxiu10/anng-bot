<?php

use Anng\event\Request;
use Anng\lib\app\foundation\ValidateProvider;
use Anng\lib\contract\http\Validate;

return [
    'server'    => 'websocket',
    'ip'        => "0.0.0.0",
    'prot'      => 9502,
    'work_num'  => 3,
    'provider'  => [
        ValidateProvider::class
    ]
];
