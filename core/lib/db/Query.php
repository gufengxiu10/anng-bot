<?php

declare(strict_types=1);

namespace Anng\lib\db;

use Anng\lib\db\concern\WhereQuery;

class Query
{
    use WhereQuery;

    protected array $option = [];
    protected string $prefix;

    public function __construct(protected Connect $connect)
    {
        $this->prefix = $this->connect->getConfig('prefix');
    }

    public function getOption($name = '')
    {
        if (empty($name)) {
            return $this->option;
        }

        return $this->option[$name] ?? '';
    }

    public function table(string $table): static
    {
        $this->option['table'] = $table;
        return $this;
    }

    public function name(string $table): static
    {
        $this->option['table'] = $this->prefix . $table;
        return $this;
    }

    public function limit($start, $end = null)
    {
        $this->option['limit'] = [$start, $end];
        return $this;
    }

    public function select()
    {
        $param = func_get_args();
        if (isset($this->option['select'])) {
            $this->option['select'] = array_merge($this->option['select'], $param);
        } else {
            $this->option['select'] =  $param;
        }
        return $this;
    }

    /**
     * @name: 获得一条数据
     * @param {*}
     * @author: ANNG
     * @return {*}
     */
    public function first($id = null)
    {
        if (!is_null($id)) {
            $this->where($this->connect->getPk($this->option['table']), $id);
        }

        return $this->connect->get($this, true);
    }

    /**
     * @name: 获得数据列表
     * @param {*}
     * @author: ANNG
     * @return {*}
     */
    public function get()
    {
        return $this->connect->get($this);
    }

    /**
     * @name: 判断记录是否存在
     * @param {*}
     * @author: ANNG
     * @return {*}
     */
    public function exists()
    {
        return $this->connect->exists($this) > 0 ? true : false;
    }

    /**
     * @name: 添加
     * @param {*} $data
     * @author: ANNG
     * @return {*}
     */
    public function insert($data = [])
    {
        if (isset($this->option['data']) && !empty($this->option['data'])) {
            $data = array_merge($this->option['data'], $data);
        }

        $this->option['data'] = $data;
        $id = $this->connect->insert($this);
        $pk = $this->connect->getPk($this->option['table']);
        return array_merge($this->option['data'], [$pk => $id]);
    }

    public function insertGetId()
    {
    }

    /**
     * @name: 更新
     * @param {*}
     * @author: ANNG
     * @return {*}
     */
    public function update($data = [])
    {
        if (isset($this->option['data']) && !empty($this->option['data'])) {
            $data = array_merge($this->option['data'], $data);
        }

        $this->option['data'] = $data;
        $this->connect->update($this);
    }


    public function __get($name)
    {
        return $this->option[$name] ?? null;
    }
}
