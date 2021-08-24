<?php

declare(strict_types=1);

namespace Anng\lib;

use Closure;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionMethod;

class Container implements ContainerInterface
{

    protected static $instance;

    /**
     * @name: 容器中的对象实例
     * @var array
     */
    protected array $instances = [];

    protected array $alias = [];
    /**
     * @name 容器绑定标识
     * @var array
     */
    protected array $bind = [];

    public function __construct()
    {
        //设置容器当前实例
        $this->setInstance($this);
        //把当当前实例添加到容器
        $this->instance(static::class, $this);
    }

    /**
     * @name: 绑定
     * @param {*}
     * @author: ANNG
     * @todo: 
     * @Date: 2021-01-09 15:17:20
     * @return {*}
     */
    public function bind($abstract, $concrete = null)
    {
        if (is_array($abstract)) {
            foreach ($abstract as $key => $val) {
                if (is_array($val)) {
                    $this->alias[ucfirst($val[0])] = $key;
                    $this->bind($key, $val[1]);
                } else {
                    $this->bind($key, $val);
                }
            }
        } elseif ($concrete instanceof Closure) {
            //回调函数直接放入容器
            $this->instance($abstract, $concrete);
        } elseif (is_object($concrete)) {
            //对象,实例直接放入容器
            $this->instance($abstract, $concrete);
        } else {
            $abstract = $this->getAlias($abstract);
            if ($abstract != $concrete) {
                //当前标识不等于值,直接放入bind当新的标识
                $this->bind[$abstract] = $concrete;
            }
        }

        return $this;
    }

    /**
     * @name: 根据别名获得真实类名
     * @param {*} string
     * @author: ANNG
     * @todo: 
     * @Date: 2021-01-09 15:41:09
     * @return {*}
     */
    public function getAlias(string $abstract): string
    {
        $abstract = ucfirst($abstract);
        //判断当前标识里面是否存在
        if (isset($this->bind[$abstract])) {
            $bind = $this->bind[$abstract];
            if (is_string($bind)) {
                return $this->getAlias($bind);
            }
        }

        return $abstract;
    }

    /**
     * @name: 绑定一个实例到容器
     * @param string $abstract 标识名
     * @param {*} $instance 容器
     * @author: ANNG
     * @todo: 
     * @Date: 2021-01-09 15:22:06
     * @return {*}
     */
    public function instance(string $abstract, $instance)
    {
        $abstract = $this->getAlias($abstract);
        $this->instances[$abstract] = $instance;
        return $this;
    }

    /**
     * @name: 创建实例,存在则返回实例
     * @param {*}
     * @author: ANNG
     * @todo: 
     * @Date: 2021-01-09 14:29:29
     * @return {*}
     */
    public function make(string $abstract, array $vars = [])
    {
        $abstract = $this->getAlias($abstract);
        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }

        $object = $this->invokeClass($abstract, $vars);
        $this->instances[$abstract] = $object;
        return $object;
    }

    /**
     * @name: 实例化类
     * @param {*} $class
     * @param {*} $vars
     * @author: ANNG
     * @todo: 
     * @Date: 2021-01-26 15:27:03
     * @return {*}
     */
    public function invokeClass(string $class, $vars = [])
    {
        $reflation = new ReflectionClass($class);
        $param = [];
        if ($construct = $reflation->getConstructor()) {
            $param = $this->parseData($construct, $vars);
        }

        return !empty($param) ? $reflation->newInstanceArgs($param) : $reflation->newInstance();
        // return (new ReflectionClass($class))->setConstructParam($vars)->instance();
    }

    /**
     * @name: 参数解析
     * @param {*} Type
     * @author: ANNG
     * @todo: 
     * @Date: 2021-02-03 09:29:11
     */
    protected function parseData(ReflectionMethod $refl, array $args = []): array
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
                    if ($this->has($name)) {
                        $data[] = $this->get($name);
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

    public function clear($name)
    {
        $name = $this->getAlias($name);
        if (array_key_exists($name, $this->instances)) {
            unset($this->instances[$name]);
        }
    }

    public function setInstance($instance): static
    {
        static::$instance = $instance;
        return $this;
    }

    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new static;
        }

        return static::$instance;
    }

    public function get($name)
    {
        return $this->make($name);
    }

    public function has($name): bool
    {
        return isset($this->instances[$name]);
    }

    public function __get($name)
    {
        return $this->get($name);
    }
}
