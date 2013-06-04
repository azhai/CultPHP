<?php

/* public/sidebar.html */
class __TwigTemplate_ed844bec809db3e6f459e96820e1af47 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "
<aside id=\"search-2\" class=\"widget widget_search\">
    <form role=\"search\" method=\"get\" id=\"searchform\" action=\"";
        // line 3
        echo twig_escape_filter($this->env, (isset($context["site_url"]) ? $context["site_url"] : null), "html", null, true);
        echo "\" >
    <div>
        <label class=\"screen-reader-text\" for=\"s\">搜索：</label>
        <input type=\"text\" value=\"\" name=\"s\" id=\"s\" />
        <input type=\"submit\" id=\"searchsubmit\" value=\"搜索\" />
    </div>
    </form>
</aside>     
    
<aside id=\"recent-posts-2\" class=\"widget widget_recent_entries\">        
    <h3 class=\"widget-title\">近期文章</h3>      
    <ul>";
        // line 15
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["recent_articles"]) ? $context["recent_articles"] : null));
        foreach ($context['_seq'] as $context["_key"] => $context["article"]) {
            // line 16
            echo "<li><a href=\"";
            echo twig_escape_filter($this->env, (isset($context["site_url"]) ? $context["site_url"] : null), "html", null, true);
            echo "?p=";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["article"]) ? $context["article"] : null), "ID"), "html", null, true);
            echo "\" title=\"";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["article"]) ? $context["article"] : null), "post_title"), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["article"]) ? $context["article"] : null), "post_title"), "html", null, true);
            echo "</a></li>";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['article'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
        // line 18
        echo "</ul>
</aside>
        
<aside id=\"recent-comments-2\" class=\"widget widget_recent_comments\">
    <h3 class=\"widget-title\">近期评论</h3>
    <ul id=\"recentcomments\">";
        // line 24
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["recent_comments"]) ? $context["recent_comments"] : null));
        foreach ($context['_seq'] as $context["_key"] => $context["comment"]) {
            // line 25
            echo "<li class=\"recentcomments\">";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["comment"]) ? $context["comment"] : null), "comment_author"), "html", null, true);
            echo " 发表在《<a href=\"";
            echo twig_escape_filter($this->env, (isset($context["site_url"]) ? $context["site_url"] : null), "html", null, true);
            echo "?p=";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["comment"]) ? $context["comment"] : null), "comment_post_ID"), "html", null, true);
            echo "#comment-";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["comment"]) ? $context["comment"] : null), "comment_ID"), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["comment"]) ? $context["comment"] : null), "post_title"), "html", null, true);
            echo "</a>》</li>";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['comment'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
        // line 27
        echo "</ul>
</aside>
        
<aside id=\"archives-2\" class=\"widget widget_archive\">
    <h3 class=\"widget-title\">文章归档</h3>    
    <ul>";
        // line 33
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["archives"]) ? $context["archives"] : null));
        foreach ($context['_seq'] as $context["_key"] => $context["archive"]) {
            // line 34
            echo "<li><a href=\"";
            echo twig_escape_filter($this->env, (isset($context["site_url"]) ? $context["site_url"] : null), "html", null, true);
            echo "?m=";
            echo twig_escape_filter($this->env, twig_date_format_filter($this->env, $this->getAttribute((isset($context["archive"]) ? $context["archive"] : null), "post_date"), "Ym"), "html", null, true);
            echo "\" title=\"";
            echo twig_escape_filter($this->env, twig_date_format_filter($this->env, $this->getAttribute((isset($context["archive"]) ? $context["archive"] : null), "post_date"), "Y年n月"), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, twig_date_format_filter($this->env, $this->getAttribute((isset($context["archive"]) ? $context["archive"] : null), "post_date"), "Y年n月"), "html", null, true);
            echo "</a></li>";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['archive'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
        // line 36
        echo "</ul>
</aside>

<aside id=\"categories-2\" class=\"widget widget_categories\">
    <h3 class=\"widget-title\">分类目录</h3>        
    <ul>";
        // line 42
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["categories"]) ? $context["categories"] : null));
        foreach ($context['_seq'] as $context["_key"] => $context["category"]) {
            // line 43
            echo "<li class=\"cat-item cat-item-";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["category"]) ? $context["category"] : null), "term_id"), "html", null, true);
            echo "\"><a href=\"";
            echo twig_escape_filter($this->env, (isset($context["site_url"]) ? $context["site_url"] : null), "html", null, true);
            echo "?cat=";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["category"]) ? $context["category"] : null), "term_id"), "html", null, true);
            echo "\" title=\"category.term_slug\">";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["category"]) ? $context["category"] : null), "term_name"), "html", null, true);
            echo "</a>";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['category'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
        // line 45
        echo "</li>
    </ul>
</aside>

<aside id=\"meta-2\" class=\"widget widget_meta\">
    <h3 class=\"widget-title\">功能</h3>          
    <ul>
        <li><a href=\"";
        // line 52
        echo twig_escape_filter($this->env, (isset($context["site_url"]) ? $context["site_url"] : null), "html", null, true);
        echo "wp-login.php\">登录</a></li>
        <li><a href=\"";
        // line 53
        echo twig_escape_filter($this->env, (isset($context["site_url"]) ? $context["site_url"] : null), "html", null, true);
        echo "?feed=rss2\" title=\"使用 RSS 2.0 订阅本站点内容\">文章 <abbr title=\"Really Simple Syndication\">RSS</abbr></a></li>
        <li><a href=\"";
        // line 54
        echo twig_escape_filter($this->env, (isset($context["site_url"]) ? $context["site_url"] : null), "html", null, true);
        echo "?feed=comments-rss2\" title=\"使用 RSS 订阅本站点的所有文章的近期评论\">评论 <abbr title=\"Really Simple Syndication\">RSS</abbr></a></li>
        <li><a href=\"http://cn.wordpress.org/\" title=\"基于 WordPress，一个优美、先进的个人信息发布平台。\">WordPress.org</a></li>
    </ul>
</aside>
";
    }

    public function getTemplateName()
    {
        return "public/sidebar.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  149 => 54,  145 => 53,  141 => 52,  132 => 45,  118 => 43,  114 => 42,  107 => 36,  93 => 34,  89 => 33,  82 => 27,  66 => 25,  62 => 24,  55 => 18,  41 => 16,  37 => 15,  23 => 3,  19 => 1,);
    }
}
