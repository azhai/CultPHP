<?php
require_once APPLICATION_DIR . DS . 'common.php';


class ArticleShowPage extends BasePage
{
    public function action()
    {
        $this->id = $this->request->get('id', 0, 'intval');
        $article = $this->query_articles()->get($this->id);
        $article->author_name = $article->author->display_name;
        $article->categories = array();
        $article->tags = array();
        $relships = $article->related('term_relationships')->order('term_order');
        foreach ($relships as $ship) {
            if ($ship->term_taxonomy->taxonomy == 'category') {
                $article->categories[] = $ship->term_taxonomy->term;
            }
            else {
                $article->tags[] = $ship->term_taxonomy->term;
            }
        }
        $this->article = $article;
    }
}

