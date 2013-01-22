<?php
defined('LIBRARY_DIR') or die();

return array(
    'site_title' => '',
    'site_url' => '/cultphp/wordpress2/index.php',
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
    'primary_key' => 'ID', //默认主键名
    'relations' => array(
        'author' => array('table'=>'wp_users', 'foreign'=>'post_author'),
        'post' => array('table'=>'wp_posts', 'foreign'=>'comment_post_ID'),
        'term' => array('table'=>'wp_terms'),
        'term_taxonomy' => array('table'=>'wp_term_taxonomy'),
        'term_relationships' => array('table'=>'wp_%s', 'foreign'=>'object_id'),
    ),
    'primaries' => array(
        'wp_terms' => 'term_id',
        'wp_term_taxonomy' => 'term_taxonomy_id',
    ),
);

