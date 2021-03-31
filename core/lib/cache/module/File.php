<?php

declare(strict_types=1);

namespace Anng\lib\cache\module;

use Anng\lib\Cache as LibCache;
use Anng\lib\facade\App;
use Doctrine\Common\Cache\FilesystemCache;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class File extends LibCache
{
    public function create()
    {
        if (!$this->client) {
            $this->client = new FilesystemCache(App::getRootPath('runtime/cache'));
        }

        return $this;
    }

    public function clear()
    {
        $finder = new Filesystem;
        $finder->remove(App::getRootPath('runtime/cache'));
    }
}
