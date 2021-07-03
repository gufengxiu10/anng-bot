<?php

declare(strict_types=1);

namespace Anng\lib;

use Anng\lib\facade\App;
use SplFileInfo;

class File extends SplFileInfo
{
    private $bashPath = '';
    public function __construct(private string $path)
    {
        $this->bashPath = App::storagePath();
        parent::__construct($this->path);
    }

    public function getRelativePath()
    {
        return str_replace($this->bashPath, '', (string)$this);
    }

    public function move($path, $name = null)
    {
        $target = $this->getTargetFile($path, $name);
        rename($this->getPathname(), (string)$target);
        @chmod((string) $target, 0666 & ~umask());
        return $target;
    }

    public function getTargetFile(string $directory, $name = null): File
    {
        $directory = $this->bashPath . $directory;
        if (!is_dir($directory)) {
            if (false === @mkdir($directory, 0777, true)) {
                throw new \Exception(sprintf('Unable to create the "%s" directory', $directory));
            }
        }

        $target = rtrim($directory, "/\\") . DIRECTORY_SEPARATOR . (null === $name ? (md5((uniqid() . time())) . "." . $this->getExtension()) : $this->getName($name));
        return new self($target);
    }

    /**
     * 获取文件名
     * @param string $name
     * @return string
     */
    protected function getName(string $name): string
    {
        $originalName = str_replace('\\', '/', $name);
        $pos          = strrpos($originalName, '/');
        $originalName = false === $pos ? $originalName : substr($originalName, $pos + 1);

        return $originalName;
    }
}
