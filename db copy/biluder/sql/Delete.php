<?php

declare(strict_types=1);

namespace Anng\lib\db\biluder\sql;

use Predis\Command\KeySort;

trait Delete
{
    protected $deleteSql = "DELETE FROM %TABLE% %WHERE%";
    // protected $installAllSql = "INSERT INTO %TABLE%(%FIELD%) %DATA% %COMMENT%";

    public function delete()
    {
        return str_replace(["%TABLE%", "%WHERE%"], [
            $this->parse->table(),
            $this->parse->where(),
        ], $this->deleteSql);
    }
}
