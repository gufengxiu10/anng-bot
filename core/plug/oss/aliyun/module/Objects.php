<?php

declare(strict_types=1);

namespace Anng\plug\oss\aliyun\module;

use Anng\plug\oss\contract\AuthInterface;
use Anng\plug\oss\contract\ObjectsInterface;
use OSS\Core\OssException;
use OSS\OssClient;
use Symfony\Component\Finder\Finder;

class Objects implements ObjectsInterface
{
    public function __construct(private OssClient $client, private AuthInterface $auth)
    {
        # code...
    }


    public function upload(array|string $files)
    {
        $arr = [];
        if (is_string($files)) {
            if (is_dir($files)) {
                $finder = new Finder;
                $finder->in($files);
                foreach ($finder->files() as $value) {
                    array_push($arr, [
                        'name'  => $value->getFilename(),
                        'path'  => $value->getPathname(),
                    ]);
                }
            } elseif (is_file($files)) {
            }
        } elseif (is_array($files)) {
        }


        for ($i = 0; $i < $this->auth->getNum(); $i++) {
            go(function () use (&$arr) {
                while (true) {
                    try {
                        if (empty($arr)) {
                            break;
                        }
                        $fileInfo = array_shift($arr);
                        $info = $this->client->putObject($this->auth->getBucket(), $fileInfo['name'], file_get_contents($fileInfo['path']));
                        dump(array_merge($fileInfo, [
                            'oss_url'   => $info['oss-request-url'],
                            'oss_md5'   => $info['oss-requestheaders']['Content-Md5'],
                            'oss_size'  => $info['oss-requestheaders']['Content-Length'],
                            'oss_type'  => $info['oss-requestheaders']['Content-Type'],
                            'oss_date'  => $info['oss-requestheaders']['Date'],
                        ]));
                    } catch (OssException $th) {
                        dump($th);
                    }
                }
            });
        }
    }
}
