<?php
/**
 * This file is part of the CultPHP (https://github.com/azhai/cultphp)
 *
 * Copyright (c) 2012
 * author: 阿债 (Ryan Liu)
 * date: 2013-01-09
 */

require_once LIBRARY_DIR . DS . 'Twig' . DS . 'Autoloader.php';
require_once LIBRARY_DIR . DS . 'nette.min.php';
require_once LIBRARY_DIR . DS . 'cult' . DS . 'string.php';
use Nette\Database;


/**
 * 使用特殊的外键与表名的对应关系
 */
class MyReflection extends Database\Reflection\ConventionalReflection
{
    const BELONGS_TO = 1;
    const HAS_MANY = 2;
    public static $relations = array();
    public static $primaries = array();
    public $table_prefix = '';

    public static function set_user_defines(array $relations, array $primaries)
    {
        self::$relations = $relations;
        self::$primaries = $primaries;
    }

    public function alias($key, $table, $rel_type)
    {
        $alias = array(
            'table' => $this->table, 
            'foreign' => $this->foreign
        );
        if ( isset(self::$relations[$key]) ) {
            return array_merge($alias, self::$relations[$key]);
        }
        if ($key == 'parent') {
            $alias['table'] = $table;
        }
        else {
            $alias['table'] = $this->table_prefix . pluralize($key);
        }
        return $alias;
    }

    public function getPrimary($table)
    {
        if ( isset(self::$primaries[$table]) ) {
            $primary =self::$primaries[$table];
        }
        else {
            $primary = $this->primary;        
        }
        return sprintf($primary, $this->getColumnFromTable($table));
    }

    public function getBelongsToReference($table, $key)
    {
        $table = $this->getColumnFromTable($table);
        $alias = $this->alias($key, $table, self::BELONGS_TO);
        return array(
            sprintf($alias['table'], $key, $table),
            sprintf($alias['foreign'], $key, $table),
        );
    }

    public function getHasManyReference($table, $key)
    {
        $table = $this->getColumnFromTable($table);
        $alias = $this->alias($key, $table, self::HAS_MANY);
        return array(
            sprintf($alias['table'], $key, $table),
            sprintf($alias['foreign'], $table, $key),
        );
    }
}


class NetteTwigDelegate extends Delegate
{
    /*Twig模板引擎*/
    public function get_templater($container=null)
    {
        $template_dir = $container->get_config('template_dir');
        $template_cache_dir = $container->get_config('template_cache_dir', '');
        if (file_exists($template_dir)) {
            $env_config = array('debug'=>true);
            if (! empty($template_cache_dir)) {
                $env_config['cache'] = $template_cache_dir;
            }
            Twig_Autoloader::register();
            $loader = new Twig_Loader_Filesystem($template_dir);
            $twig = new Twig_Environment($loader, $env_config);
            if (function_exists('customize_twig')) {
                customize_twig($twig);
            }
            return $twig;
        }
    }
    
    /*Nette ORM*/
    public function get_db($container=null)
    {
        $config = $container->get_config('db');
        $relations = $container->get_config('relations', array());
        $primaries = $container->get_config('primaries', array());
        $primary_key = $container->get_config('primary_key', 'id');
        if (is_array($config)) {
            $dsn = 'mysql:host=' . $config['host'] . ';dbname=' . $config['dbname'];
            $db = new Database\Connection($dsn, $config['user'], $config['password']);
            $ref = new MyReflection($primary_key);
            $ref->set_user_defines($relations, $primaries);
            $ref->table_prefix = isset($config['prefix']) ? $config['prefix'] : '';
            $db->setDatabaseReflection($ref);
            return $db;
        }
    }
}

