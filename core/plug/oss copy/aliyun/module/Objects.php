<?php

declare(strict_types=1);

namespace Anng\plug\oss\aliyun\module;

use Anng\plug\oss\Auth;
use OSS\Core\OssException;
use Symfony\Component\Finder\Finder;

class Objects
{

    public Auth $auth;
    private array $files = [];
    private array $errorFile = [];
    private array $resData = [];
    private array $filedError = [];

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }


    public function setFile($val)
    {

        if (is_file($val)) {
            array_push($this->files, [
                'name'  => substr($val, strrpos($val, '/') + 1),
                'path'  => $val
            ]);
        } elseif (is_dir($val)) {
            $finder = (new Finder)->in($val);
            foreach ($finder as $file) {
                array_push($this->files, [
                    'name'  => $file->getFilename(),
                    'path'  => $file->getRealPath()
                ]);
            }
        } else {
            return false;
        }

        return $this;
    }

    /**
     * @name: 单文件上传
     * @param {*}
     * @author: ANNG
     * @todo: 
     * @Date: 2021-01-22 17:31:28
     * @return {*}
     */
    public function upload()
    {
        foreach ($this->files as $file) {
            try {
                $res = $this->auth->client()->putObject($this->auth->getBucket(), $file['name'], file_get_contents($file['path']));
                return $res['oss-request-url'];
            } catch (OssException $e) {
                array_push($this->errorFile, $file);
                return false;
            }
        }
    }


    private function retransmission()
    {
        if (!empty($this->errorFile)) {
            $i = 0;
            while ($i > 5) {
                foreach ($this->errorFile as $file) {
                    try {
                        if (is_array($file)) {
                            $res = $this->auth->client()->uploadFile($this->auth->getBucket(), $file['name'], $file['path']);
                        } else {
                            $res = $this->auth->client()->uploadFile($this->auth->getBucket(), '1.png', $file);
                        }

                        array_push($this->resData, $res);
                    } catch (OssException $e) {
                        if ($i == 5) {
                            array_push($this->filedError, [
                                'name' => $file['name'],
                                'path' => $file['path'],
                                'error' => $e->getMessage(),
                            ]);
                        }
                    }
                }
            }

            //重置
            $this->errorFile = [];
        }

        if (empty($this->errorFile)) {
            return count($this->resData) > 1 ? $this->resData : $this->resData[0];
        }

        return $this->errorFile;
    }

    public function error()
    {
        # code...
    }
}
