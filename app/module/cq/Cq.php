<?php

declare(strict_types=1);

namespace app\cq;


class Cq
{

    public function face(int $id)
    {
        return $this->set('face', [
            'id' => $id
        ]);
    }

    public function at(int $id): array
    {
        return $this->set('at', [
            'qq' => $id
        ]);
    }

    public function atAll(): array
    {
        return $this->set('at', [
            'qq' => 'all'
        ]);
    }

    public function share(string $url, string $title)
    {
        return $this->set('share', [
            'url' => $url,
            'title' => $title
        ]);
    }

    public function music(string $type, int|string $id)
    {
        return $this->set('musci', [
            'type' => $type,
            'id' => $id
        ]);
    }

    public function musicCustom(string $url, string $audio, string $title = '未定义')
    {
        return $this->set('musci', [
            'type' => 'custom',
            "url" => $url,
            "audio" => $audio,
            "title" =>  $title
        ]);
    }

    public function image($file, $url = '')
    {
        return $this->set('image', [
            'file' => $file,
            'url' => $url
        ]);
    }


    private function set(string $type, array $data): array
    {
        return [
            'type' => $type,
            'data' => $data
        ];
    }
}
