<?php

declare(strict_types=1);

namespace Anng\lib\route;

use Anng\lib\facade\Route;
use Anng\lib\Route as LibRoute;

class RuleItem
{
    private $fullName;

    public function __construct(private Route|LibRoute $route, private Group $group, private $name, private $class, private $method)
    {
        $this->parseName();
    }

    /**
     * @name: 路由名分析 
     * @param {*}
     * @author: ANNG
     * @return {*}
     */
    public function parseName(): void
    {
        if ($this->name == '/') {
            $this->fullName = implode('/', array_merge($this->group->getGroup()));
        } else {
            $this->fullName = implode('/', array_merge($this->group->getGroup(), [ltrim($this->name, '/')]));
        }
    }

    /**
     * @name: 获得当前规则的全名
     * @param {*}
     * @author: ANNG
     * @return {*}
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * @name: 判断当前规则的方式
     * @param {*} $method
     * @author: ANNG
     * @return {*}
     */
    public function checkMethod($method)
    {
        return $this->method == $method ? true : false;
    }

    /**
     * @name: 判断当前规则是否一样
     * @param {*} $rule
     * @author: ANNG
     * @return {*}
     */
    public function checkRule($rule)
    {
        return $this->fullName == trim($rule, '/') ? true : false;
    }

    /**
     * @name: 获得当前规则的方法
     * @param {*}
     * @author: ANNG
     * @return {*}
     */
    public function getClass()
    {
        return $this->class;
    }
}
