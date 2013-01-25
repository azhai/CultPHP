<?php
/**
 * This file is part of the CultPHP (https://github.com/azhai/cultphp)
 *
 * Copyright (c) 2012
 * author: 阿债 (Ryan Liu)
 * date: 2013-01-09
 */

/**
 * 初始化一个类，并加上若干属性
 */
function init($klass, array $options=array())
{
    $obj = new $klass();
    foreach ($options as $key => $value) {
        $obj->$key = $value;
    }
    return $obj;
}


/**
 * 判断PHP数组是否索引(is_numeric)/关联(is_string)数组
 */
function array_key_checks($arr, $check='is_numeric')
{
    if (! is_array($arr)) {
        return false;
    }
    else if (empty($arr)) {
        return true;
    }
    else {
        $key_checks = array_map($check, array_keys($arr));
        return array_reduce($key_checks, 'and', true);
    }
}


/**
 * 过程描述，相当于一个function
 */
class Procedure
{
    public $subject = null;
    public $method = '';
    public $args = array();

    public function __construct($subject, $method, array $args=array())
    {
        $this->subject = $subject;
        $this->method = $method;
        if ( ! empty($args) ) {
            $this->args = array_merge($this->args, $args);
        }
    }

    /*执行过程得到结果，相当于PHP5.3的__invoke()*/
    public function emit()
    {
        $args = func_get_args();
        if ( ! empty($args) ) {
            $this->args = array_merge($this->args, $args);
        }
        $func = empty($this->subject) ? $this->method : array($this->subject, $this->method);
        return call_user_func_array($func, $this->args);
    }
}


/**
 * 构造器
 **/
class Constructor extends Procedure
{
    public function __construct($subject, array $args=array())
    {
        parent::__construct($subject, '__construct', $args);
    }

    public function emit()
    {
        $subject = $this->subject;
        if ( empty($this->args) ) {
            return new $this->subject;
        }
        else {
            $ref = new ReflectionClass($this->subject);
            return $ref->newInstanceArgs($this->args);
        }
    }
}


/**
 * 存储器
 */
class Storage
{
    public function __construct(array $data=array())
    {
        $this->update($data);
    }

    public function update(array $data=array())
    {
        foreach ($data as $key => $value)
        {
            if (! is_null($value)) {
                $this->$key = $value;
            }
        }
    }

    public function has($key)
    {
        return property_exists($this, $key) && ! is_null($this->$key);
    }

    public function get($key, $default=null, $trans=null)
    {
        if (! $this->has($key)) { //找不到时返回默认值
            return $default;
        }
        $value = $this->$key;
        if (! is_null($trans)) { //使用函数规则化数据，如intval
            $value = call_user_func($trans, $value);
        }
        return $value;
    }

    /*寻找多个关联的值，可选择将对应键值从存储器中清除*/
    public function find(array $keys, $pop=false)
    {
        $values = array();
        foreach ($keys as $key) {
            $value = $this->get($key);
            if (! is_null($value)) { //只返回存在的键值
                $values[$key] = $value;
            }
            if ($pop && property_exists($this, $key)) {
                unset($this->$key);
            }           
        }
        return $values;
    }
}


/**
 * 代理
 */
class Delegate
{
    public function get($key, $container=null)
    {
        return;
    }

    public function set($key, $value, $container=null)
    {
        return $value;
    }
}


/**
 * 按需加载属性的容器
 */
class Container
{
    public $context;
    public $delegate;

    public function __construct()
    {
        $this->context = new Storage();
    }

    /*当属性不存在时，使用代理获取数据*/
    public function __get($key)
    {
        $value = $this->context->get($key);
        if (is_null($value) && ! is_null($this->delegate)) {
            $method = 'get_' . $key;
            if (method_exists($this->delegate, $method)) {
                $value = $this->delegate->$method($this);
            }
            else {
                $value = $this->delegate->get($key, $this);
            }
            $this->context->$key = $value;
        }
        return $value;
    }

    /*将数据交由代理封装，再给属性赋值*/
    public function __set($key, $value)
    {
        if (! is_null($this->delegate)) {
            $method = 'set_' . $key;
            if (method_exists($this->delegate, $method)) {
                $value = $this->delegate->$method($value, $this);
            }
            else {
                $value = $this->delegate->set($key, $value, $this);
            }
        }
        $this->context->$key = $value;
    }
}