<?php

declare(strict_types=1);

namespace Anng\lib\route;

use Anng\lib\Route;

class Group
{

    public $gropuName = null;

    public function __construct(private Route $route, private $parent = null, private $name = null, callable $rule = null)
    {
        if ($this->parent) {
            $name = !empty($this->gropuName) ? $this->gropuName . '/' . $name : $name;
            $this->parent->gropuName = $name;
            if (!is_null($rule)) {
                $rule();
            }
        }
    }

    /**
     * @name: 设置规则
     * @param {*}
     * @author: ANNG
     * @return {*}
     */
    public function setRule($rule, $route, $method)
    {
        if (is_null($this->gropuName)) {
            $this->route->addGroup($rule, $route, $method);
        } else {
            $this->route->addGroup($this->gropuName . '/' . trim($rule, '/'), $route, $method);
        }
    }
}
