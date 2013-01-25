<?php

require_once LIBRARY_DIR . DS . 'cult' . DS . 'string.php';
require_once LIBRARY_DIR . DS . 'cult' . DS . 'orm.php';


/*只显示分页符之前的文章内容*/
function less_content($content, $article_id){
    if ( preg_match('/<!--more(.*?)?-->/', $content, $matches) ) {
        list($content, $extended) = explode($matches[0], $content, 2);
        $content .= '<!--more-->';
        $content = wpautop($content);
        $read_more = '<a href="?module=article&action=show&id='. $article_id .'#more-2"'
                    . ' class="more-link">继续阅读 <span class="meta-nav">&rarr;</span></a>';
        $content = str_replace('<!--more-->', $read_more, $content);
    }
    else {
        $content = wpautop($content);
    }
    return $content;
}


class BlogDelegate extends PageDelegate
{
    /*博客配置项*/
    public function get_options($master=null)
    {
        $names = array('siteurl', 'blogname', 'blogdescription', 'posts_per_page');
        $stmt = $master->db->exec_query('options', array('option_name'=>$names), 
                                        '', array(), 'option_name, option_value');
        $options = $stmt->fetchAll(PDO::FETCH_COLUMN | PDO::FETCH_GROUP | PDO::FETCH_UNIQUE);
        return $options;
    }

    /*最近文章*/
    public function get_recent_articles($master=null)
    {
        $conds = $master->query_articles();
        return $master->db->exec_query('posts', $conds, 
                    'ORDER BY post_date DESC LIMIT 5')->fetchAll(PDO::FETCH_OBJ);
    }
    
    /*最近评论*/
    public function get_recent_comments($master=null)
    {
        $stmt = $master->db->exec_query('comments', array('comment_type'=>''), 
                                        'ORDER BY comment_date DESC LIMIT 5');
        $recent_comments = $master->db->relate_stmt($stmt, array(
            'post' => array('posts', 'comment_post_ID', 'ID'),
        ));
        return $recent_comments;
    }
    
    /*文章归档*/
    public function get_archives($master=null)
    {
        $conds = $master->query_articles();
        $stmt = $master->db->exec_query('posts', $conds, 
                                        'GROUP BY YEAR(post_date), MONTH(post_date) DESC LIMIT 5');
        $archives = array();
        while ($obj = $stmt->fetch(PDO::FETCH_OBJ)) {
            $obj->post_date = date_create($obj->post_date);
            $archives[] = $obj;
        }
        return $archives;
    }
    
    /*文章分类*/
    public function get_categories($master=null)
    {
        $stmt = $master->db->exec_query('term_taxonomy', array('taxonomy'=>'category'));
        $categories = $master->db->relate_stmt($stmt, array(
            'term' => array('terms', 'term_id', 'term_id'),
        ));
        return $categories;
    }
}


class BasePage extends Page
{
    //public $models = array();
    
    /*public function __get($key)
    {
        if (array_key_exists($key, $this->models)) {
            $model = $this->models[$key];
            if (! isset($model['query'])) {
                $table_name = isset($model['table_name']) ? $model['table_name'] : $key;
                $assigns = isset($model['assigns']) ? $model['assigns'] : array();
                $this->models[$key]['query'] = new Query($table_name, $assigns, $this->db);
            }
            return $this->models[$key]['query'];
        }
        else {
            return parent::__get($key);
        }
    }*/

    public function prepare()
    {
        $this->delegate = init('BlogDelegate');
        //从数据库中读取全局配置项
        //$this->site_url = $this->options['siteurl'];
        $this->site_title = $this->options['blogname'];
        $this->site_description = $this->options['blogdescription'];
        //加载侧边栏
        $this->sidebar = $this->templater->render('public/sidebar.html', array(
            'recent_articles' => $this->recent_articles,
            'recent_comments' => $this->recent_comments,
            'archives' => $this->archives,
            'categories' => $this->categories,
        ));
    }
    
    public function query_articles()
    {
        return array('post_type'=>'post', 'post_status'=>'publish');
    }
}
