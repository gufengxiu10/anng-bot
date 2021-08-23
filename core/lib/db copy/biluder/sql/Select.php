<?php

declare(strict_types=1);

namespace Anng\lib\db\biluder\sql;


trait Select
{
    protected $countSql = "SELECT count(*) FROM %TABLE% %WHERE% ";

    public function count()
    {
        return str_replace(["%TABLE%", "%WHERE%"], [
            $this->parse->table(),
            $this->parse->where(),
        ], $this->countSql);
    }
}
