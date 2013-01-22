<?php
require_once APPLICATION_DIR . DS . 'common.php';


class HomeIndexPage extends BasePage
{
    public function action()
    {
        $page_length = $this->options['posts_per_page'];
        $this->paged = $this->request->get('paged', 1, 'intval');
        $offset = ($this->paged - 1) * $page_length;
        $this->articles = $this->query_articles()->order('post_date DESC')->limit($page_length + 1, $offset);
        $this->next_page = count($this->articles) > $page_length;
        $i = 0;
        foreach ($this->articles as $id => $article) {
            $i ++;
            if ($i > $page_length) {
                unset($this->articles[$id]);
                break;
            }
            $this->articles[$id]->author_name = $article->author->display_name;
            $this->articles[$id]->categories = array();
            $this->articles[$id]->tags = array();
            $relships = $article->related('term_relationships')->order('term_order');
            foreach ($relships as $ship) {
                if ($ship->term_taxonomy->taxonomy == 'category') {
                    $this->articles[$id]->categories[] = $ship->term_taxonomy->term;
                }
                else {
                    $this->articles[$id]->tags[] = $ship->term_taxonomy->term;
                }
            }
        }
    }
}

