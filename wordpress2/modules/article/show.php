<?php
require_once APPLICATION_DIR . DS . 'common.php';


class ArticleShowPage extends BasePage
{
    public function action()
    {
        $this->id = $this->request->get('id', 0, 'intval');
        $this->article = $this->query_articles()->get($this->id);
        $this->article->author_name = $this->article->author->display_name;
        $this->article->categories = array();
        $this->article->tags = array();
        $relships = $this->article->related('term_relationships')->order('term_order');
        foreach ($relships as $ship) {
            if ($ship->term_taxonomy->taxonomy == 'category') {
                $this->article->categories[] = $ship->term_taxonomy->term;
            }
            else {
                $this->article->tags[] = $ship->term_taxonomy->term;
            }
        }
    }
}

