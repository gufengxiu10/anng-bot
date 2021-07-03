<?php

declare(strict_types=1);

namespace Anng\lib\file;

use Anng\lib\facade\App;
use Anng\lib\File;

class UploadFile extends File
{
    public function __construct(string $path, $name)
    {
        $newPath = '/tmp/' . $name;
        rename($path, $newPath);
        parent::__construct($newPath);
    }
}
