<?php
require_once APPLICATION_DIR . DS . 'common.php';


class ArticleShowPage extends BasePage
{
    /*public $models = array(
        'articles' => array(
            'table_name' => 'posts',
            'assigns' => array('post_type'=>'post', 'post_status'=>'publish')
        ),
    );

    public function action()
    {
        $this->id = $this->request->get('id', 0, 'intval');
        $article = $this->articles->get($this->id);
        $article->post_date = date_create($article->post_date);
        $article->categories = array();
        $article->tags = array();
        $this->article = $article;
    }*/

    public function action()
    {
        $this->id = $this->request->get('id', 0, 'intval');
        $article = $this->db->exec_query('posts', array('ID'=>$this->id))->fetch(PDO::FETCH_OBJ);
        $article->post_date = date_create($article->post_date);
        $article->author = $this->db->exec_query('users', array('ID'=>$article->post_author))->fetch(PDO::FETCH_OBJ);
        $article->categories = array();
        $article->tags = array();
        /*$relships = $this->article->related('term_relationships')->order('term_order');
        foreach ($relships as $ship) {
            if ($ship->term_taxonomy->taxonomy == 'category') {
                $this->article->categories[] = $ship->term_taxonomy->term;
            }
            else {
                $this->article->tags[] = $ship->term_taxonomy->term;
            }
        }*/
        $this->article = $article;
    }
}

