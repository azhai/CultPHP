<?php
/**
 * This file is part of the CultPHP (https://github.com/azhai/cultphp)
 *
 * Copyright (c) 2012
 * author: 阿债 (Ryan Liu)
 * date: 2013-01-09
 */

require_once LIBRARY_DIR . DS . 'cult' . DS . 'cache.php';

/**
 * 模型行为
 */
class Behavior extends Storage
{
}


/**
 * 数据表描述
 */
class Schema extends Storage
{
    public $db;
    public $table_name = '';
    public $pkey = array();
    public $uni_indexes = array();
    public $mul_indexes = array();
    public $fields = array();

    public function __construct($db, $table_name)
    {
        $this->db = $db;
        $this->table_name = $table_name;
        $this->desc();
    }

    public function desc()
    {
        $table_name = trim($this->db->table_prefix) . trim($this->table_name);
        $sql = "SHOW FULL COLUMNS FROM `$table_name`";
        $rows = $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        $this->pkey = array();
        $this->uni_indexes = array();
        $this->mul_indexes = array();
        $this->fields = array();
        foreach ($rows as $row) {
            array_push($this->fields, $row['Field']);
            if ($row['Key'] === 'PRI') {
                array_push($this->pkey, $row['Field']);
            }
            else if ($row['Key'] === 'UNI') {
                array_push($this->uni_indexes, $row['Field']);
            }
            else if ($row['Key'] === 'MUL') {
                array_push($this->mul_indexes, $row['Field']);
            }
        }
    }
}


/**
 * 模型
 */
class Row
{
	const SYNCED = 0;
	const DIRTY = 1;
	const CREATED = 2;
	const DELETED = 3;
	public $rs;
    public $row_state;
    public $behaviors = array();
	public $schema;
    protected $data = array();
    protected $relations = array();
    protected $privates = array();

    public function __construct()
    {
    	$this->data = func_get_args();
    	$this->row_state = self::SYNCED;
    }

    public function __get($key)
    {
    	if (array_key_exists($key, $this->privates)) {
    		return;
    	}
    	$value = null;
    	if (array_key_exists($key, $this->relations)) {
    		$value = $this->relations[$key];
    	}
    	else if (array_key_exists($key, $this->data)) {
    		$value = $this->data[$key];
    	}
    	else if (array_key_exists($key, $this->behaviors)) {
    		$behavior = $this->behaviors[$key];
    		$method = 'apply_' . strtolower(get_class($behavior));
    		if (method_exists($this, $method)) {
    			$value = $this->$method($behavior);
    			$this->relations[$key] = $value;
    		}
    	}
    	return $value;
    }

    public function __set($key, $value)
    {
    	if (array_key_exists($key, $this->privates)) {
    		return;
    	}
    	$changed = false;
    	if (array_key_exists($key, $this->relations)) {
    		if ($this->relations[$key] !== $value) {
    			$this->relations[$key] = $value;
    			$changed = true;
    		}
    	}
    	else if (array_key_exists($key, $this->data)) {
    		if ($this->data[$key] !== $value) {
    			$this->data[$key] = $value;
    			$changed = true;
    		}
    	}
    	else if (array_key_exists($key, $this->behaviors)) {
    		$behavior = $this->behaviors[$key];
    		$method = 'apply_' . strtolower(get_class($behavior));
    		if (method_exists($this, $method)) {
	    		$this->relations[$key] = $value;
    			if ($this->$method($behavior) !== $value) {
	    			$changed = true;
	    		}
    		}
    	}
    	if ($changed === true) {
    		$this->row_state = self::DIRTY;
    	}
    }

    public function save($db=null)
    {
    	$this->row_state = self::SYNCED;
    }

    public function apply_belongsto($db=null)
    {
    }

    public function apply_hasone($db=null)
    {
    }

    public function apply_hasmany($db=null)
    {
    }

    public function apply_manytomany($db=null)
    {
    }
}


/**
 * 模型集合
 */
class RowSet
{
    public $db;
    protected $data = array();

    public function __construct($data)
    {
        $this->data = $data;
    }
}



/**
 * 查询
 */
class Query
{
    public $db;
	public $model = '';
	public $table_name = '';
    public $groups = array();
	public $orders = array();
	public $offset = 0;
	public $length = 0;
    protected $assigns = array();
    protected $wheres = array();
    protected $params = array();

    public function __construct($table_name, array $assigns=array(), $db=null, $model='Row')
    {
        $this->table_name = $table_name;
        $this->assigns = $assigns;
        $this->db = $db;
        $this->model = $model;
    }

    public function filter($where, array $params=array())
    {
        array_push($this->wheres, $where);
        array_push($this->params, $params);
        return $this;
    }

    public function filter_by(array $assigns=array())
    {
        $this->assigns = array_merge($this->assigns, $assigns);
        return $this;
    }

    public function group_by()
    {
        $groups = func_get_arg();
        $this->groups = $groups;
        return $this;
    }

    public function order_by()
    {
        $orders = func_get_arg();
        $this->orders = array_merge($this->orders, $orders);
        return $this;
    }

    public function set_page($page_no=1, $page_len=20)
    {
        $this->offset = ($page_no - 1 ) * $page_len;
        $this->length = $page_len;
        return $this;
    }

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

    /*生成SQL语句*/
	public function to_sql($columns='*', array $excludes=array())
	{
		$columns = ! is_array($columns) || empty($columns) ? '*' : implode(',', $columns);
        $table_name = trim($this->db->table_prefix) . trim($this->table_name);
		$wheres = implode(' AND ', $this->wheres);

        //ASSIGN
        $conds = array();
        $args = array();
        if (! empty($assigns)) {
            foreach ($assigns as $key => $val) {
                list($cond, $value) = self::assign($key, $val);
                array_push($conds, $cond);
                $args = array_merge($args, $value);
            }
        }

        //WHERE
        $condition = implode(' AND ', $conds);
        if (! empty($wheres)) {
            if (strtoupper(substr($wheres, 0, 3)) === 'OR ') {
                $condition = "($condition) " . $wheres;
            }
            else {
                $condition .= ' AND ' . $wheres;
            }
            $args = array_merge($args, $this->params);
        }

        //SQL
        $sql = "SELECT $columns FROM `$table_name`";
        if (! in_array('WHERE', $excludes) && ! empty($condition)) {
            $sql .= ' WHERE ' . $condition;
        }
        if (! in_array('GROUP', $excludes) && ! empty($this->groups)) {
            $sql .= ' GROUP BY ' . implode(', ', $this->groups);
        }
        if (! in_array('ORDER', $excludes) && ! empty($this->orders)) {
            $sql .= ' ORDER BY ' . implode(', ', $this->orders);
        }
        if (! in_array('LIMIT', $excludes) && $this->length > 0) {
            if ($this->offset > 0) {
                $sql .= ' LIMIT ' . $this->offset . ',' . $this->length;
            }
            else {
                $sql .= ' LIMIT ' . $this->length;
            }
        }
        //echo vsprintf(str_replace("?", "'%s'", $sql), $args), "; <br>\n";
        return array($sql, $args);
	}

	public function exec($sql, array $args=array(), Procedure $callback=null)
	{
        $stmt = $this->db->prepare($sql);
        $stmt->execute($args);
        $stmt->setFetchMode(PDO::FETCH_CLASS, $this->model);
        $callback->subject = $stmt;
        $result = $callback->emit();
        $stmt->closeCursor();
        return $result;
	}

    public function all()
    {
        list($sql, $args) = $this->to_sql();
        $callback = new Procedure(null, 'fetchAll');
        $objs = $this->exec($sql, $args, $callback);
        $rs = new RowSet($objs);
        $rs->db = $this->db;
        return $rs;
    }

	public function pair($columns='*', $unique=true)
	{
        list($sql, $args) = $this->to_sql($columns);
        $style = PDO::FETCH_CLASS | PDO::FETCH_GROUP;
        if ($unique === true) {
            $style = $style | PDO::FETCH_UNIQUE;
        }
        $callback = new Procedure(null, 'fetchAll');        
        $objs = $this->exec($sql, $args, $callback);
        $rs = new RowSet($objs);
        $rs->db = $this->db;
        return $rs;
	}

	public function get($id=null)
	{
        $id = func_get_args();
        if (! empty($id)) {
            //$pkey = $this->model->schema->get_pkey();
            $pkey = array('ID');
            $this->filter_by( array_combine($pkey, $id) );
        }
        list($sql, $args) = $this->to_sql();
        $callback = new Procedure(null, 'fetch');
        $obj = $this->exec($sql, $args, $callback);
        $obj->schema = new Schema($this->db, $this->table_name);
        return $obj;
	}

	public function count($field='*')
	{
        list($sql, $args) = $this->to_sql("COUNT($field)", array('LIMIT'));
        $callback = new Procedure(null, 'fetchColumn');
        return $this->exec($sql, $args, $callback);
	}

	public function max($field)
	{
        list($sql, $args) = $this->to_sql("MAX($field)", array('LIMIT'));
        $callback = new Procedure(null, 'fetchColumn');
        return $this->exec($sql, $args, $callback);
	}
}
