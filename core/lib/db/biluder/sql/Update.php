<?php

declare(strict_types=1);

namespace Anng\lib\db\biluder\sql;

use Predis\Command\KeySort;

trait Update
{
    protected $updateSql = "UPDATE %TABLE% SET %SET% %WHERE%";
    // protected $installAllSql = "INSERT INTO %TABLE%(%FIELD%) %DATA% %COMMENT%";

    public function update()
    {
        return str_replace(["%TABLE%", "%SET%", "%WHERE%"], [
            $this->parse->table(),
            $this->parse->set(),
            $this->parse->where(),
        ], $this->updateSql);
    }
}
