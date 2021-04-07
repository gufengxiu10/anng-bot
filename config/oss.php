<?php

use Anng\lib\facade\Env;

return [
    'drive'     => 'aliyun',
    'ak'        => Env::get('ak'),
    'sk'        => Env::get('sk'),
    'bucket'    => Env::get('bucket')
];
