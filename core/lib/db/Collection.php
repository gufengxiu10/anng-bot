<?php

declare(strict_types=1);


namespace Anng\lib\db;

use Anng\lib\Collection as BaseCollection;

class Collection extends BaseCollection
{

    public function isEmpty()
    {
        return empty($this->items);
    }

    protected function convertToArray($data): array
    {
        if (empty($data)) {
            return [];
        }

        return (array)$data;
    }
}
