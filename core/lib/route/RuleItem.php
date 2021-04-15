<?php

declare(strict_types=1);

namespace Anng\lib\route;

use Anng\lib\facade\Route;
use Anng\lib\Route as LibRoute;

class RuleItem
{
    private $fullName;
    private $groupName;
    private $nameArray = [];
    private $param = [];

    public function __construct(private Route|LibRoute $route, private Group $group, private $name, private $class, private $method)
    {
        $this->nameArray = array_filter(explode('/', $this->name));
        $this->setGroupName();
        $this->parseName();
    }

    public function setGroupName()
    {
        $this->groupName = $this->group->getGroup();
    }

    /**
     * @name: 路由名分析 
     * @param {*}
     * @author: ANNG
     * @return {*}
     */
    public function parseName(): void
    {
        $this->fullName = $this->name == '/' ? array_merge($this->groupName) : array_merge($this->groupName, $this->nameArray);
    }

    public function fullNameToString()
    {
        return implode('/', $this->fullName);
    }

    /**
     * @name: 规则分析
     * @param {*}
     * @author: ANNG
     * @return {*}
     */
    private function ruleAnalysis($rule): bool
    {
        $url = explode('/', ltrim($rule, '/'));
        if (count($url) != count($this->fullName)) {
            return false;
        }

        foreach ($this->fullName as $key => $value) {
            if ($value == $url[$key]) {
                continue;
            }

            if (!str_contains($value, ':')) {
                $this->param = [];
                return false;
            }

            $this->param[ltrim($value, ':')] = $url[$key];
        }
        return true;
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
        return $this->ruleAnalysis($rule);
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

    public function getParam()
    {
        return $this->param;
    }
}
