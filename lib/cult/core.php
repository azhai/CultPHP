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
require_once LIBRARY_DIR . DS . 'cult' . DS . 'struct.php';


class ForbiddenException extends Exception {}
class CacheMatchedException extends Exception {}


/**
 * 在指定目录下寻找第一个存在的文件
 */
function get_first_file(array $files, array $subs=array(), $dir='.')
{
    $dir = rtrim($dir, DS);
    $subs = empty($subs) ? array(null) : $subs;
    foreach ($subs as $sub) {
        $sub_dir = is_null($sub) ? '' : trim($sub, DS) . DS;
        foreach ($files as $file) {
            $sub_file = $sub_dir . $file;
            if (file_exists($dir . DS . $sub_file)) {
                return $sub_file;
            }
        }
    }
}


/**
 * 寻找某个类的最终子类
 */
function load_final_class($base_class, array $words=array())
{
    $classes = array($base_class);
    $rev_words = array_reverse($words);
    foreach ($rev_words as $word) {
        $base_class = $word . $base_class;
        array_unshift($classes, $base_class);
    }
    foreach ($classes as $class) {
        if (class_exists($class)) {
            return $class;
        }
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
    $files = array($action . '.php', 'index.php');
    $filename = get_first_file($files, array($module), $configure->module_dir);
    if (! empty($filename)) {
        require_once $configure->module_dir . DS . $filename;
    }
    $words = array(ucfirst($module), ucfirst($action));
    $page_klass = load_final_class('Page', $words);

    //初始化对应页面对象
    $page = init($page_klass, array(
        'request' => $request, 'module' => $module, 'action' => $action,
    ));
    $page->set_configure($configure, array_keys(Configure::$defaults));
    return $page;
}


/**
 * 配置存储器
 */
class Configure extends Storage
{
    public static $defaults = array(
        'site_title' => '', 'site_url' => '', 'static_url' => '',
    );

    public function __construct($config_file, array $options=array())
    {
        self::$defaults['module_dir'] = APPLICATION_DIR . DS . 'modules';
        self::$defaults['template_dir'] = APPLICATION_DIR . DS . 'templates';
        $config = (@include APPLICATION_DIR . DS . $config_file);
        $config = is_array($config) ? $config : array();
        $config = array_merge(self::$defaults, $config, $options);
        parent::__construct($config);
    }
}


/**
 * 请求存储器
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
 * 页面容器
 */
class Page extends Container
{
    protected $configure;
    public $request;
    public $template_file = '';

    public static function redirect($module, $action=null, $args=array())
    {
    }

    public static function error($code, $message='')
    {
    }

    public function get_config($key, $default='')
    {
        return $this->configure->get($key, $default);
    }

    public function set_configure($configure, array $assign_keys=array())
    {
        $this->configure = $configure;
        foreach ($assign_keys as $key) {
            $this->context->$key = $this->get_config($key);
        }
    }

    public function render()
    {
        $context = get_object_vars($this->context);
        if ($this->request->is_ajax()) {
            echo json_encode($context);
        }
        else {
            $template_dir = $this->configure->template_dir;
            if (empty($this->template_file)) {
                $files = array($this->action . '.html', 'default.html');
                $subs = array($this->module, 'public');
                $this->template_file = get_first_file($files, $subs, $template_dir);;
            }
            echo $this->templater->render($this->template_file, $context);
        }
    }

    /*公共操作，如初始化加载器、用户，检查权限等*/
    public function prepare()
    {
        $this->delegate = init('PageDelegate');
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


/**
 * 页面代理
 */
class PageDelegate extends Delegate
{
    public function get_templater($container=null)
    {
        $template_dir = $container->get_config('template_dir');
        if (file_exists($template_dir)) {
            $templater = new Templater($template_dir);
            return $templater;
        }
    }

    public function get_db($container=null)
    {
        $config = $container->get_config('db');        
        if (is_array($config)) {
            $db_class = class_exists('Database') ? 'Database' : 'PDO';
            $dsn = 'mysql:host=' . $config['host'] . ';dbname=' . $config['dbname'];
            $db = new $db_class($dsn, $config['user'], $config['password']);
            if (isset($config['prefix']) && method_exists($db, 'set_table_prefix')) {
                $db->set_table_prefix( trim($config['prefix']) ); //数据表前缀
            }
            return $db;
        }
    }
}


/**
 * PHP原生模板引擎
 */
class Templater
{
    public $template_dir = '';
    public $cache_dir = '';
    private $extend_files = array();
    private $template_blocks = array();
    private $current_block = '';

    public function __construct($template_dir, $cache_dir='')
    {
        $this->template_dir = rtrim($template_dir, DS);
        $this->cache_dir = rtrim($cache_dir, DS);
    }

    public function render($template_file, array $context=array())
    {
        extract($context);
        ob_start();
        include $this->template_dir . DS . $template_file; //入口模板
        if (! empty($this->extend_files)) {
            $layout_file = array_pop($this->extend_files);
            foreach ($this->extend_files as $file) { //中间继承模板
                include $this->template_dir . DS . $file;
            }
            extract($this->template_blocks);
            include $this->template_dir . DS . $layout_file; //布局模板
        }
        return ob_get_clean();
    }

    /* 注意: 必须自己传递context，如果想共享render中的context，请在模板中
       使用 include $this->template_dir . DS . $template_file; 
       代替 $this->include_tpl($template_file); */
    public function include_tpl($template_file, array $context=array())
    {
        extract($context);
        include $this->template_dir . DS . $template_file;
    }

    public function extend_tpl($template_file)
    {
        array_push($this->extend_files, $template_file);
    }

    public function block_start($block_name='content')
    {
        $this->current_block = $block_name;
        ob_start();
    }

    public function block_end()
    {
        $this->template_blocks[ $this->current_block ] = ob_get_clean();
    }
}
