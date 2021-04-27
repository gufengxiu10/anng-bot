<?php

declare(strict_types=1);

namespace Anng\lib\file;

use Anng\lib\File;

class UploadFile extends File
{
    public function __construct(private string $path, private string $originName, private int $error = 0)
    {
        parent::__construct($path);
    }

    public static function make(string $path, string $originName, int $error): static
    {
        return new static($path, $originName, $error);
    }

    public function move($path, $fileName = '')
    {
        $originName = $this->originName;
        if (!empty($fileName)) {
            $originName = $fileName . '.' . $this->getExtension();
        }
        $path = rtrim($path, '/') . '/' . $originName;
        parent::move($path);
    }

    public function getExtension()
    {
        return substr($this->originName, strrpos($this->originName, '.') + 1);
    }
}
