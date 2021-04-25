<?php

declare(strict_types=1);

namespace Anng\lib\db;

use Anng\lib\db\biluder\sql\Insert;
use Anng\lib\db\biluder\sql\Select;
use Anng\lib\db\biluder\sql\Update;

abstract class Biluder
{

    public function __construct(private Parse $parse)
    {
        # code...
    }

    use Insert;
    use Update;
    use Select;

    protected array $option = [];

    protected $selectFindSql = "SELECT %FIELD% FROM %TABLE% %WHERE% %LIMIT%";

    /**
     * @name: 
     * @param {*}
     * @author: ANNG
     * @todo: 
     * @Date: 2021-02-02 16:07:58
     * @return {*}
     */
    public function find()
    {
        $sql = str_replace(["%TABLE%", "%FIELD%", "%WHERE%", "%LIMIT%"], [
            $this->parse->table(),
            $this->parse->field(),
            $this->parse->where(),
            'limit 1',
        ], $this->selectFindSql);
        return $sql;
    }

    /**
     * @name: 
     * @param {*}
     * @author: ANNG
     * @todo: 
     * @Date: 2021-02-02 16:07:58
     * @return {*}
     */
    public function select()
    {
        return str_replace(["%TABLE%", "%FIELD%", "%WHERE%", "%LIMIT%"], [
            $this->parse->table(),
            $this->parse->field(),
            $this->parse->where(),
            $this->parse->limit(),
        ], $this->selectFindSql);
    }
}
