<?php
defined('LIBRARY_DIR') or die();

return array(
    'site_title' => '',
    'site_url' => '/cultphp/wordpress1/index.php',
    'static_url' => '/cultphp/wordpress1/static',
    'module' => 'default',
    'action' => 'index',
    'module_dir' => APPLICATION_DIR . DS . 'modules',
    'template_dir' => APPLICATION_DIR . DS . 'templates',
    'template_cache_dir' => APPLICATION_DIR . DS . 'tmp' . DS . 'tpl',
    'data_cache_dir' => APPLICATION_DIR . DS . 'tmp' . DS . 'data',
    'logging_dir' => APPLICATION_DIR . DS . 'tmp' . DS . 'log',
    'db' => array(
        'host' => 'localhost',
        'user' => 'dba',
        'password' => 'pass',
        'dbname' => 'wordpress',
        'prefix' => 'wp_',
    ),
);

