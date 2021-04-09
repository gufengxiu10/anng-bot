<?php

declare(strict_types=1);

namespace Anng\lib\route;

use Anng\lib\facade\Route as FacadeRoute;
use Anng\lib\Route;
use Closure;

class Group
{

    private array $groupName = [];

    public function __construct(private Route $route, private $parent = null, private $name = null, private $rule = null)
    {
        $this->setGroupName();
        $this->setRouteGroup();
    }

    /**
     * @name: 
     * @param {*}
     * @author: ANNG
     * @return {*}
     */
    public  function getGroup(): array
    {
        return $this->groupName;
    }

    private function setGroupName(): void
    {
        if ($this->parent) {
            $this->groupName = $this->parent->getGroup();
        }

        if (!is_null($this->name)) {
            array_push($this->groupName, $this->name);
        }
    }

    /**
     * @name: 获得当前分组的名
     * @param {*}
     * @author: ANNG
     * @return {*}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @name: 设置当前组(比较重要)
     * @param {*}
     * @author: ANNG
     * @return {*}
     */
    public function setRouteGroup()
    {
        $origin = $this->route->getGroup();
        $this->route->setGroup($this);
        if ($this->rule instanceof Closure) {
            call_user_func($this->rule);
        }

        if ($origin) {
            $this->route->setGroup($origin);
        }
    }

    /**
     * @name: 设置规则
     * @param {*}
     * @author: ANNG
     * @return {*}
     */
    public function setRule($rule, $route, $method): FacadeRoute|Route
    {
        $this->route->addRoutes(new RuleItem($this->route, $this, $rule, $route, $method));
        return $this->route;
    }
}
