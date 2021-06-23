<?php

declare(strict_types=1);

namespace Anng\lib\reflection;

use Anng\lib\facade\Reflection as FacadeReflection;
use Anng\lib\Reflection;
use ReflectionClass as GlobalReflectionClass;
use ReflectionMethod;

class ReflectionClass extends Reflection
{
    private GlobalReflectionClass $refltion;
    private array $constructParam = [];
    private $instance;
    private array $defaultMethod = [];
    private array $method = [];

    public function __construct(private string $className)
    {
        $this->refltion = new GlobalReflectionClass($this->className);
    }

    /**
     * @name: 设置构造函数的参数
     * @param {array} $param
     * @author: ANNG
     * @return {*}
     */
    public function setConstructParam(array $param): static
    {
        $this->constructParam = $param;
        return $this;
    }

    /**
     * @name: 实例化并运行相关操作并返回实例
     * @param {*}
     * @author: ANNG
     * @return {*}
     */
    public function make(): object
    {
        $object = $this->instance();
        $this->defaultMethodRun();
        $this->methodRun();
        return $object;
    }

    /**
     * @name: 获得实例
     * @param {*}
     * @author: ANNG
     * @return {*}
     */
    public function instance(): object
    {
        if ($param = $this->getConstructorParam()) {
            $this->instance = $this->refltion->newInstance($param);
        } else {
            $this->instance = $this->refltion->newInstance();
        }

        return $this->instance;
    }

    public function getRefltion(): GlobalReflectionClass
    {
        return $this->refltion;
    }

    /**
     * @name: 获得构造函数
     * @param {*}
     * @author: ANNG
     * @return {*}
     */
    public function getConstructor(): ReflectionMethod|null
    {
        return $this->refltion->getConstructor();
    }

    /**
     * @name: 获得注入构造的参数
     * @param {*}
     * @author: ANNG
     * @return {*}
     */
    public function getConstructorParam()
    {
        if ($construct = $this->getConstructor()) {
            return $this->parseData($construct, $this->constructParam);
        }

        return false;
    }

    /**
     * @name: 设置默认的调用方法
     * @param {*}
     * @author: ANNG
     * @todo: 
     * @Date: 2021-02-03 10:53:22
     * @return {*}
     */
    public function setDefaultMethod(string $method, array $args = []): static
    {
        $this->defaultMethod = [
            'method' => $method,
            'args' => $args
        ];
        return $this;
    }

    /**
     * @name: 运行默认方法
     * @param {*}
     * @author: ANNG
     * @return {*}
     */
    private function defaultMethodRun()
    {
        if (!empty($this->defaultMethod)) {
            if ($this->refltion->hasMethod($this->defaultMethod['method'])) {
                $args = $this->defaultMethod['args'] ?? [];
                $methodArgs = $this->parseData($this->refltion->getMethod($this->defaultMethod['method']), (array)$args);
                call_user_func_array([$this->instance(), $this->defaultMethod['method']], $methodArgs);
            }
        }
    }

    /**
     * @name: 实例化后自动调用的方法
     * @param string|array $method 调用的方法
     * @author: ANNG
     * @todo: 
     * @Date: 2021-02-03 10:44:29
     */
    public function setMethod(string $method, array $args = []): static
    {
        $this->method[] = [
            'method' => $method,
            'args' => $args
        ];

        return $this;
    }

    /**
     * @name: 运行相关的方法
     * @param {*}
     * @author: ANNG
     * @return {*}
     */
    private function methodRun()
    {
        foreach ($this->method as $value) {
            if ($this->refltion->hasMethod($value['method'])) {
                $args = $value['args'] ?? [];
                $methodArgs = $this->parseData($this->refltion->getMethod($value['method']), (array)$args);
                call_user_func_array([$this->instance(), $value['method']], $methodArgs);
            }
        }
    }

    /**
     * @name: 运行方法
     * @param {*} $method
     * @param {*} $args
     * @author: ANNG
     * @return {*}
     */
    public function sendMethod($method, $args = []): mixed
    {
        if ($this->refltion->hasMethod($method)) {
            $methodArgs = $this->parseData($this->refltion->getMethod($method), (array)$args);
            return call_user_func_array([$this->instance(), $method], $methodArgs);
        }

        return false;
    }
}
