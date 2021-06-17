<?php

declare(strict_types=1);


namespace Anng\lib\db\pool;

use Anng\lib\db\Pool;
use PDO;
use Swoole\Database\PDOPool;
use Swoole\Database\PDOConfig;

class Mysql extends Pool
{
    public function create()
    {
        $this->pool = new PDOPool((new PDOConfig)
                ->withHost($this->db->config->get('host'))
                ->withPort($this->db->config->get('port'))
                ->withDbName($this->db->config->get('name'))
                ->withCharset($this->db->config->get('char'))
                ->withUsername($this->db->config->get('username'))
                ->withPassword($this->db->config->get('password'))
                ->withOptions([
                    PDO::ATTR_STRINGIFY_FETCHES => false,
                    PDO::ATTR_EMULATE_PREPARES => false
                ])
        );

        return $this;
    }
}
