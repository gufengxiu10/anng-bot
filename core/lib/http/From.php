<?php

declare(strict_types=1);

namespace Anng\lib\http;

use Anng\lib\contract\http\Validate;
use Anng\lib\Request;

class From implements Validate
{
    public function __construct(private Request $request)
    {
        # code...
    }
}
