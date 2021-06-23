<?php

declare(strict_types=1);

namespace Anng\lib;

use Anng\lib\contract\FacadeInterface;
use Anng\lib\facade\App;
use Exception;
use ReflectionMethod;

abstract class Reflection
{
    abstract public function instance();
    abstract public function make();

    /**
     * @name: 参数解析
     * @param {*} Type
     * @author: ANNG
     * @todo: 
     * @Date: 2021-02-03 09:29:11
     */
    public function parseData(ReflectionMethod $refl, array $args = []): array
    {
        $params = $refl->getParameters();
        if (empty($params)) {
            return [];
        }

        $data = [];
        //重置数组指针
        //用于判断数组键值是以自然数为键,如果是则按顺序赋值
        $type = key($args) === 0 ? 1 : 0;
        foreach ($params as $value) {
            $paramName = $value->getName();
            if (!is_null($value->getType())) {
                $name = $value->getType()->getName();
                //TODO::未处理匿名数据的回调
                if (isset($args[$paramName])) {
                    $data[] = $args[$paramName];
                } else {
                    if (App::getInstance()->has($name)) {
                        $data[] = App::getInstance()->get($name)(App::getInstance());
                    } else {
                        $data[] = $this->instance($name, $args);
                    }
                }
            } else {
                if ($type == 1 && !empty($args)) {
                    $data[] = array_shift($args);
                } elseif ($type == 0 && isset($args[$paramName])) {
                    $data[] = $args[$paramName];
                } elseif ($value->isDefaultValueAvailable()) {
                    $data[] = $value->getDefaultValue();
                } else {
                    throw new Exception('method param miss:' . $value->getName());
                }
            }
        }

        return $data;
    }
}
