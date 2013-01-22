<?php
/**
 * This file is part of the CultPHP (https://github.com/azhai/cultphp)
 *
 * Copyright (c) 2012
 * author: 阿债 (Ryan Liu)
 * date: 2013-01-09
 */

defined('APPLICATION_DIR') or die();
defined('DS') or define('DS', DIRECTORY_SEPARATOR);
defined('LIBRARY_DIR') or define('LIBRARY_DIR', realpath(dirname(__DIR__)));
defined('CONFIG_FILENAME') or define('CONFIG_FILENAME', 'config.php');


class ForbiddenException extends Exception {}
class CacheMatchedException extends Exception {}


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
 * 在指定目录下需找子目录和文件名
 */
function get_filename($subdir, $fname, $dir)
{
    $dir = rtrim($dir, DS);
    $subdir = trim($subdir, DS);
    $filename = $subdir . DS . $fname;
    if (file_exists($dir . DS . $filename)) {
        return $filename;
    }
}


/**
 * 加载对应模块的文件，并初始化对应页面
 */
function dispatch(array $options=array())
{
    //加载配置文件
    $configure = new Configure(CONFIG_FILENAME, $options);
    $request = init('Request');
    $module = $request->get('module', $configure->module);
    $action = $request->get('action', $configure->action);

    //按照ModuleActionPage/ActionPage/Page次序寻找页面类
    $page_klass = 'Page';
    $filename = get_filename($module, $action . '.php', $configure->module_dir);
    if (! empty($filename)) {
        require_once $configure->module_dir . DS . $filename;
        $klass = ucfirst($module) . ucfirst($action) . 'Page';
        if (class_exists($klass)) {
            $page_klass = $klass;
        }
        $klass = ucfirst($action) . 'Page';
        if (class_exists($klass)) {
            $page_klass = $klass;
        }
    }
    //初始化对应页面对象
    $page = init($page_klass, array(
        'site_title' => $configure->site_title,
        'site_url' => $configure->site_url,
        'static_url' => $configure->static_url,
        'module' => $module, 'action' => $action, 
        'request' => $request
    ));
    $page->set_configure($configure);
    return $page;
}


/**
 * 存储器，可同时用[]和->访问的关联数组，但其中值不会为null
 */
class Storage extends ArrayObject
{
    public function __construct(array $data=array())
    {
        parent::__construct(array(), ArrayObject::ARRAY_AS_PROPS);
        $this->update($data);
    }

    public function update(array $data=array())
    {
        foreach ($data as $key => $value)
        {
            if (! is_null($value)) {
                $this[$key] = $value;
            }
        }
    }

    /*寻找关联的值*/
    public function get($key, $default=null, $trans=null)
    {
        if (! isset($this[$key])) { //找不到时返回默认值
            return $default;
        }
        $param = $this[$key];
        if (! is_null($trans)) { //使用函数规则化数据，如intval
            $param = call_user_func($trans, $param);
        }
        return $param;
    }

    /*寻找多个关联的值，可选择将对应键值从存储器中清除*/
    public function find(array $keys, $pop=false)
    {
        $params = array();
        foreach ($keys as $key) {
            $value = $this->get($key);
            if (! is_null($value)) { //只返回存在的键值
                $params[$key] = $value;
                if ($pop) {
                    unset($this[$key]);
                }
            }           
        }
        return $params;
    }
}


/**
 * 按需加载属性的容器
 */
class Container
{
    public $context;
    public $loader;

    public function __construct()
    {
        $this->context = new Storage();
    }

    /*当属性不存在时，使用内置加载器代理获取数据*/
    public function __get($key)
    {
        $value = $this->context->get($key);
        if (is_null($value)) {
            $method = 'get_' . $key;
            if (method_exists($this->loader, $method)) {
                $value = $this->loader->$method($this);
                $this->$key = $value;
            }
        }
        return $value;
    }

    public function __set($key, $value)
    {
        $this->context->$key = $value;
    }

    /*检查是否有此属性，可选择是否调用加载器*/
    public function has($key, $load=false)
    {
        $exists = property_exists($this, $key);
        if ($load === false) {
            return $exists || isset($this->context->$key);
        }
        else {
            return $exists || ! is_null($this->$key);
        }
    }

    /*检查此属性中是否存在一个这样的值，此属性为数组*/
    public function has_one($key, $val, $load=false)
    {
        if ($this->has($key, $load)) {
            $value = $this->context->$key;
            if (is_array($value) && in_array($val, $value)) {
                return true;
            }
        }
        return false;
    }
}


/**
 * 配置文件
 */
class Configure extends Storage
{
    public function __construct($config_file, array $options=array())
    {
        $config = (@include APPLICATION_DIR . DS . $config_file);
        $config = is_array($config) ? $config : array();
        $default_config = array(
            'site_title' => '', 'site_url' => '', 'static_url' => '',
            'module' => 'home', 'action' => 'index',
            'module_dir' => APPLICATION_DIR . DS . 'modules', 
            'template_dir' => APPLICATION_DIR . DS . 'templates',
        );
        $config = array_merge($default_config, $config, $options);
        parent::__construct($config);
    }
}


/**
 * 获取环境参数的存储器
 */
class Request extends Storage
{
    public function __construct()
    {
        parent::__construct($_REQUEST);
        $this->method = $_SERVER['REQUEST_METHOD'];
    }

    public function is_ajax()
    {
        return false;
    }

    public function get_client_ip()
    {
        return;
    }
}

/**
 * PHP原生模板引擎
 */
class Templater
{
    public $template_dir = '';
    public $cache_dir = '';

    public function __construct($template_dir, $cache_dir='')
    {
        $this->template_dir = rtrim($template_dir, DS);
        $this->cache_dir = rtrim($cache_dir, DS);
    }

    public function render($template_file, array $context=array())
    {
        extract($context);
        ob_start();
        include $this->template_dir . DS . $template_file;
        return ob_get_clean();
    }
}


/**
 * 简单的数据库连接
 */
class Database extends PDO
{
    public $table_prefix = ''; //表名前缀
    
    /*将字段与值组成查询条件*/
    public static function assign($key, $val)
    {
        if (is_null($val)) {
            $value = array();
            $cond = "`$key` IS NULL";
        }
        else {
            $value =  is_array($val) ? $val : array($val);
            $length = count($value);
            if ($length == 1) {
                $cond = "`$key`=?";
            }
            else {
                $placeholder = rtrim(str_repeat('?,', $length), ',');
                $cond = "`$key` IN ($placeholder)";  
            }
        }
        return array($cond, $value);
    }

    /*将字段与值组成查询条件*/
    public static function to_sql($table, array $assigns=array(), 
                        $addition='', array $params=array(), $columns='*')
    {
    }
    
    /*执行只读查询*/
    public function exec_query($table, array $assigns=array(), 
                        $addition='', array $params=array(), $columns='*')
    {
        $table = trim($table);
        $addition = trim($addition);
        $columns = trim($columns);

        $conds = array();
        $args = array();
        if (! empty($assigns)) {
            foreach ($assigns as $key => $val) {
                list($cond, $value) = self::assign($key, $val);
                array_push($conds, $cond);
                $args = array_merge($args, $value);
            }
        }

        $condition = implode(' AND ', $conds);
        if (! empty($addition)) {
            @list($first, $second, $tail) = explode(' ', strtoupper($addition), 3);
            if ($first === 'OR') {
                $condition = "($condition) " . $addition;
            }
            else if ($second === 'BY' && in_array($first,array('GROUP','ORDER'))
                        || in_array($first,array('LIMIT','AND'))) {
                $condition .= " " . $addition;
            }
            else {
                $condition .= " AND " . $addition;
            }
            $args = array_merge($args, $params);
        }
        $prefix = $this->table_prefix;
        $sql = "SELECT $columns FROM `$prefix$table`";
        if (! empty($condition)) {
            $sql .= " WHERE $condition";
        }
        $stmt = $this->prepare($sql);
        $stmt->execute($args);
        //echo vsprintf(str_replace("?", "'%s'", $sql), $args), "; <br>\n";
        return $stmt;
    }

    /*外键查询*/
    public function relate_stmt(PDOStatement $stmt, array $relations=array())
    {
        $result = array();
        $foreigns = array();
        foreach ($relations as $name => $relation) {
            @list($table, $fkey, $pkey) = $relation;
            $relations[$name][1] = empty($fkey) ? ($name . '_id') : $fkey;
            $relations[$name][2] = empty($pkey) ? 'id' : $pkey;
            $foreigns[$name] = array();
        }            
        while ($obj = $stmt->fetch(PDO::FETCH_OBJ)) {
            $result[] = $obj;
            foreach ($relations as $name => $relation) {
                list($table, $fkey, $pkey) = $relation;
                $foreigns[$name][$obj->$fkey] = null;
            }
        }
        
        foreach ($relations as $relation) {
            list($table, $fkey, $pkey) = $relation;
            $stmt = $this->exec_query($table, array($pkey=>array_keys($foreigns[$name])));
            while ($fobj = $stmt->fetch(PDO::FETCH_OBJ)) {
                $foreigns[$name][$fobj->$pkey] = $fobj;
            }
        }
        foreach ($result as $i => $obj) {
            foreach ($relations as $name => $relation) {
                list($table, $fkey, $pkey) = $relation;
                $result[$i]->$name = $foreigns[$name][$obj->$fkey];
            }
        }
        return $result;
    }
}


/**
 * 加载器
 */
class Loader extends Storage
{
    public function get_templater($master=null)
    {
        $template_dir = $master->get_conf_item('template_dir');
        if (file_exists($template_dir)) {
            $templater = new Templater($template_dir);
            return $templater;
        }
    }

    public function get_db($master=null)
    {
        $config = $master->get_conf_item('db');
        if (is_array($config)) {
            $dsn = 'mysql:host=' . $config['host'] . ';dbname=' . $config['dbname'];
            $db = new Database($dsn, $config['user'], $config['password']);
            $db->table_prefix = isset($config['prefix']) ? trim($config['prefix']) : '';
            return $db;
        }
    }
}


/**
 * 页面
 */
class Page extends Container
{
    protected $configure;
    public $request;
    public $tpl_filename = '';

    public static function redirect($module, $action=null, $args=array())
    {
    }

    public static function error($code, $message='')
    {
    }

    public function set_configure($configure)
    {
        $this->configure = $configure;
    }

    public function get_conf_item($key, $default='')
    {
        return $this->configure->get($key, $default);
    }

    public function get_tpl_file($template_dir)
    {
        $files = array(
            array($this->module, $this->action . '.html'),
            array($this->module, 'default.html'),
            array('public', $this->action . '.html'),
            array('public', 'default.html'),
        );
        foreach ($files as $file) {
            $filename = get_filename($file[0], $file[1], $template_dir);
            if (! empty($filename)) {
                return $filename;
            }
        }
    }

    public function render()
    {
        $context = $this->context->getArrayCopy();
        if ($this->request->is_ajax()) {
            echo json_encode($context);
        }
        else {
            $template_dir = $this->configure->template_dir;
            if (empty($this->tpl_filename)) {
                $this->tpl_filename = $this->get_tpl_file($template_dir);
            }
            echo $this->templater->render($this->tpl_filename, $context);
        }
    }

    /*公共操作，如初始化加载器、用户，检查权限等*/
    public function prepare()
    {
        $this->loader = init('Loader');
    }
    
    /*加载具体数据的操作*/
    public function action()
    {
    }

    public function run()
    {
        try {
            $this->prepare();
            $this->action();
            $this->render();
        }
        catch (ForbiddenException $e) { //权限不足
            self::error(403, $e->getMessage());
        }
        catch (CacheMatchedException $e) { //使用已缓存页面
        }
        return;
    }
}
