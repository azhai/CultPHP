<?php
//使用Nette ORM和Twig模板引擎
require_once LIBRARY_DIR . DS . 'cult' . DS . 'nette_twig.php';


/*只显示分页符之前的文章内容*/
function less_content($content){
    $contents = explode('<!--more-->', $content);
    return is_array($contents) ? array_shift($contents) : $contents;
}


/**
 * 给twig增加扩展和函数
 */
function customize_twig(& $twig)
{
    $twig->addExtension( new Twig_Extension_Escaper(true) );
    $twig->addFilter(new Twig_SimpleFilter('less', 'less_content'));
}


class BlogLoader extends NetteTwigLoader
{
    /*博客配置项*/
    public function get_options($master=null)
    {
        $names = array('siteurl', 'blogname', 'blogdescription', 'posts_per_page');
        $query = $master->db->table('wp_options')->where('option_name', $names);
        return $query->fetchPairs('option_name', 'option_value');
    }

    /*最近文章*/
    public function get_recent_articles($master=null)
    {
        return $master->query_articles()->order('post_date DESC')->limit(5);
    }
    
    /*最近评论*/
    public function get_recent_comments($master=null)
    {
        $query = $master->db->table('wp_comments')->where('comment_type', '');
        $recent_comments = $query->order('comment_date DESC')->limit(5);
        foreach ($recent_comments as $id => $comment) {
            $recent_comments[$id]->post_title = $comment->post->post_title;
        }
        return $recent_comments;
    }
    
    /*文章归档*/
    public function get_archives($master=null)
    {
        return $master->query_articles()->group('YEAR(post_date), MONTH(post_date) DESC')->select('post_date')->limit(5);
    }
    
    /*文章分类*/
    public function get_categories($master=null)
    {
        $categories = $master->db->table('wp_term_taxonomy')->where('taxonomy', 'category');
        foreach ($categories as $id => $category) {
            $categories[$id]->term_name = $category->term->name;
            $categories[$id]->term_slug = $category->term->slug;
        }
        return $categories;
    }
}


class BasePage extends Page
{
    public function prepare()
    {
        $this->loader = init('BlogLoader');
        //从数据库中读取全局配置项
        //$this->site_url = $this->options['siteurl'];
        $this->site_title = $this->options['blogname'];
        $this->site_description = $this->options['blogdescription'];
    }
    
    public function query_articles()
    {
        return $this->db->table('wp_posts')->where('post_type', 'post')->where('post_status', 'publish');
    }
}
