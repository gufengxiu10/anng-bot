<?php

declare(strict_types=1);

namespace app\module\controller;

use Anng\lib\contract\RequestInterface;
use app\BaseController;

class Common extends BaseController
{
    public function upload(RequestInterface $request)
    {
        $file = $request->file();
        $info = $file->move('public');

        return $this->success([
            'path' => $info->getRelativePath(),
            'name' => $info->getFileName()
        ]);
    }
}
