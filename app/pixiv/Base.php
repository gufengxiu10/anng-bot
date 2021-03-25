<?php

declare(strict_types=1);

namespace app\pixiv;

use Swlib\Saber;

abstract class Base
{
    protected function send($method, $uri, $data = [])
    {
        $saber = Saber::create([
            // 'base_uri' => $this->getBaseUri(),
            'headers'  => array_merge([], $this->getHeaders()),
        ]);

        if ($method == 'get') {
            $uri .= '?' . http_build_query($data);
            $data = null;
        }

        $uri = $this->getBaseUri() . $uri;
        return $saber->request(['method' => $method, 'uri' => $uri, $data]);
    }

    abstract function getBaseUri();

    abstract function getHeaders();
}
