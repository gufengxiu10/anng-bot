<?php

namespace Anng\lib\contract\db;

use Anng\lib\db\Query;

interface Connect
{
    public function table(string $table): Query;

    public function send(mixed $query, mixed $sql = null);
}
