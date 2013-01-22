<?php
error_reporting(E_ALL & ~E_DEPRECATED);
defined('APPLICATION_DIR') or define('APPLICATION_DIR', realpath(__DIR__));
require_once APPLICATION_DIR . '/../lib/cult/core.php';

dispatch(array('module'=>'home'))->run();
