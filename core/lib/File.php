<?php

declare(strict_types=1);

namespace Anng\lib;

use SplFileInfo;

class File extends SplFileInfo
{
    private SplFileInfo $file;

    public function __construct(string $path)
    {
        parent::__construct($path);
    }

    public function move($path)
    {
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        move_uploaded_file($this->getPathname(), $path);
    }
}
