<?php
require_once APPLICATION_DIR . DS . 'common.php';


class HomeIndexPage extends BasePage
{
    public function action()
    {
        $page_length = $this->options['posts_per_page'];
        $this->paged = $this->request->get('paged', 1, 'intval');
        $offset = ($this->paged - 1) * $page_length;
        $length = $page_length + 1;
        $conds = $this->query_articles();
        $stmt = $this->db->exec_query('posts', $conds, 
                        "ORDER BY post_date DESC LIMIT $offset,$length");
        $articles = $this->db->relate_stmt($stmt, array(
            'author' => array('users', 'post_author', 'ID'),
        ));
        if (count($articles) > $page_length) {
            $this->next_page = true;
            unset($articles[$page_length]);
        }
        else {
            $this->next_page = false;
        }
        foreach ($articles as $i => $obj) {
            $articles[$i]->post_date = date_create($obj->post_date);
            $articles[$i]->categories = array();
            $articles[$i]->tags = array();
        }
        /*$articles = array();
        $i = 0;
        while ($obj = $stmt->fetch(PDO::FETCH_OBJ)) {
            if ($i >= $page_length) {
                break;
            }
            $obj->post_date = date_create($obj->post_date);
            $obj->author = $this->db->exec_query('users', array('ID'=>$obj->post_author))->fetch(PDO::FETCH_OBJ);
            $obj->categories = array();
            $obj->tags = array();
            $relships = $obj->related('term_relationships')->order('term_order');
            foreach ($relships as $ship) {
                if ($ship->term_taxonomy->taxonomy == 'category') {
                    $this->articles[$id]->categories[] = $ship->term_taxonomy->term;
                }
                else {
                    $this->articles[$id]->tags[] = $ship->term_taxonomy->term;
                }
            }
            $articles[] = $obj;
            $i ++;
        }*/
        $this->articles = $articles;
    }
}

