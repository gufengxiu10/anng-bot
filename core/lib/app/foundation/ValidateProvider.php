<?php

declare(strict_types=1);

namespace Anng\lib\app\foundation;

use Anng\lib\Provider;

class ValidateProvider extends Provider
{

    public function register()
    {
        dump($this->app);
    }

    public function boot()
    {
        dump(10);
    }
}
