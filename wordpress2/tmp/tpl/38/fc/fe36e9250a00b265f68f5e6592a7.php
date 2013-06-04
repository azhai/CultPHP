<?php

/* home/index.html */
class __TwigTemplate_38fcfe36e9250a00b265f68f5e6592a7 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("public/base.html");

        $this->blocks = array(
            'content' => array($this, 'block_content'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "public/base.html";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_content($context, array $blocks = array())
    {
        // line 4
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["articles"]) ? $context["articles"] : null));
        foreach ($context['_seq'] as $context["_key"] => $context["article"]) {
            // line 5
            echo "<article id=\"post-";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["article"]) ? $context["article"] : null), "ID"), "html", null, true);
            echo "\" class=\"post-";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["article"]) ? $context["article"] : null), "ID"), "html", null, true);
            echo " post type-post status-publish format-standard hentry category-design tag-css tag-font-size tag-83\">
    <header class=\"entry-header\">
        <h1 class=\"entry-title\">
            <a href=\"?module=article&action=show&id=";
            // line 8
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["article"]) ? $context["article"] : null), "ID"), "html", null, true);
            echo "\" title=\"链向 ";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["article"]) ? $context["article"] : null), "post_title"), "html", null, true);
            echo " 的固定链接\" rel=\"bookmark\">";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["article"]) ? $context["article"] : null), "post_title"), "html", null, true);
            echo "</a>
        </h1>
        ";
            // line 10
            if (($this->getAttribute((isset($context["article"]) ? $context["article"] : null), "comment_count") > 0)) {
                // line 11
                echo "        <div class=\"comments-link\">
            <a href=\"?module=article&action=show&id=";
                // line 12
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["article"]) ? $context["article"] : null), "ID"), "html", null, true);
                echo "#comments\" title=\"《";
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["article"]) ? $context["article"] : null), "post_title"), "html", null, true);
                echo "》上的评论\">";
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["article"]) ? $context["article"] : null), "comment_count"), "html", null, true);
                echo " 条回复</a>
        </div><!-- .comments-link -->
        ";
            }
            // line 15
            echo "    </header><!-- .entry-header -->

    <div class=\"entry-content\">
        <p>
";
            // line 19
            echo less_content($this->getAttribute((isset($context["article"]) ? $context["article"] : null), "post_content"), $this->getAttribute((isset($context["article"]) ? $context["article"] : null), "ID"));
            echo "
        </p>
    </div><!-- .entry-content -->
    
    <footer class=\"entry-meta\">
        本条目发布于 <a href=\"";
            // line 24
            echo twig_escape_filter($this->env, (isset($context["site_url"]) ? $context["site_url"] : null), "html", null, true);
            echo "?p=";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["article"]) ? $context["article"] : null), "ID"), "html", null, true);
            echo "\" title=\"";
            echo twig_escape_filter($this->env, twig_date_format_filter($this->env, $this->getAttribute((isset($context["article"]) ? $context["article"] : null), "post_date"), "H:i"), "html", null, true);
            echo "\" rel=\"bookmark\"><time class=\"entry-date\" datetime=\"";
            echo twig_escape_filter($this->env, twig_date_format_filter($this->env, $this->getAttribute((isset($context["article"]) ? $context["article"] : null), "post_date"), "Y-m-d\\TH:i:sP"), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, twig_date_format_filter($this->env, $this->getAttribute((isset($context["article"]) ? $context["article"] : null), "post_date"), "Y-m-d"), "html", null, true);
            echo "</time></a>。
        属于
        ";
            // line 26
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["article"]) ? $context["article"] : null), "categories"));
            $context['loop'] = array(
              'parent' => $context['_parent'],
              'index0' => 0,
              'index'  => 1,
              'first'  => true,
            );
            if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof Countable)) {
                $length = count($context['_seq']);
                $context['loop']['revindex0'] = $length - 1;
                $context['loop']['revindex'] = $length;
                $context['loop']['length'] = $length;
                $context['loop']['last'] = 1 === $length;
            }
            foreach ($context['_seq'] as $context["_key"] => $context["category"]) {
                // line 27
                if ((!$this->getAttribute((isset($context["loop"]) ? $context["loop"] : null), "first"))) {
                    echo "、";
                }
                // line 28
                echo "<a href=\"";
                echo twig_escape_filter($this->env, (isset($context["site_url"]) ? $context["site_url"] : null), "html", null, true);
                echo "?cat=";
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["category"]) ? $context["category"] : null), "term_id"), "html", null, true);
                echo "\" title=\"查看 ";
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["category"]) ? $context["category"] : null), "name"), "html", null, true);
                echo " 中的全部文章\" rel=\"category\">";
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["category"]) ? $context["category"] : null), "name"), "html", null, true);
                echo "</a>
        ";
                ++$context['loop']['index0'];
                ++$context['loop']['index'];
                $context['loop']['first'] = false;
                if (isset($context['loop']['length'])) {
                    --$context['loop']['revindex0'];
                    --$context['loop']['revindex'];
                    $context['loop']['last'] = 0 === $context['loop']['revindex0'];
                }
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['category'], $context['_parent'], $context['loop']);
            $context = array_merge($_parent, array_intersect_key($context, $_parent));
            // line 30
            echo "        分类";
            // line 31
            if ($this->getAttribute((isset($context["article"]) ? $context["article"] : null), "tags")) {
                // line 32
                echo "，被贴了
            ";
                // line 33
                $context['_parent'] = (array) $context;
                $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["article"]) ? $context["article"] : null), "tags"));
                $context['loop'] = array(
                  'parent' => $context['_parent'],
                  'index0' => 0,
                  'index'  => 1,
                  'first'  => true,
                );
                if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof Countable)) {
                    $length = count($context['_seq']);
                    $context['loop']['revindex0'] = $length - 1;
                    $context['loop']['revindex'] = $length;
                    $context['loop']['length'] = $length;
                    $context['loop']['last'] = 1 === $length;
                }
                foreach ($context['_seq'] as $context["_key"] => $context["tag"]) {
                    // line 34
                    if ((!$this->getAttribute((isset($context["loop"]) ? $context["loop"] : null), "first"))) {
                        echo "、";
                    }
                    // line 35
                    echo "<a href=\"";
                    echo twig_escape_filter($this->env, (isset($context["site_url"]) ? $context["site_url"] : null), "html", null, true);
                    echo "?tag=";
                    echo twig_escape_filter($this->env, $this->getAttribute((isset($context["tag"]) ? $context["tag"] : null), "slug"), "html", null, true);
                    echo "\" rel=\"tag\">";
                    echo twig_escape_filter($this->env, $this->getAttribute((isset($context["tag"]) ? $context["tag"] : null), "name"), "html", null, true);
                    echo "</a>
            ";
                    ++$context['loop']['index0'];
                    ++$context['loop']['index'];
                    $context['loop']['first'] = false;
                    if (isset($context['loop']['length'])) {
                        --$context['loop']['revindex0'];
                        --$context['loop']['revindex'];
                        $context['loop']['last'] = 0 === $context['loop']['revindex0'];
                    }
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['tag'], $context['_parent'], $context['loop']);
                $context = array_merge($_parent, array_intersect_key($context, $_parent));
                // line 37
                echo "            标签";
            }
            // line 38
            echo "。
        <span class=\"by-author\">作者是 <span class=\"author vcard\"><a class=\"url fn n\" href=\"";
            // line 39
            echo twig_escape_filter($this->env, (isset($context["site_url"]) ? $context["site_url"] : null), "html", null, true);
            echo "?author=";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["article"]) ? $context["article"] : null), "post_author"), "html", null, true);
            echo "\" title=\"查看所有由 ";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["article"]) ? $context["article"] : null), "author_name"), "html", null, true);
            echo " 发布的文章\" rel=\"author\">";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["article"]) ? $context["article"] : null), "author_name"), "html", null, true);
            echo "</a></span>。</span>
    </footer><!-- .entry-meta -->
</article><!-- #post -->
";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['article'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
        // line 43
        echo "            
<nav id=\"nav-below\" class=\"navigation\" role=\"navigation\">
    <h3 class=\"assistive-text\">文章导航</h3>
    <div class=\"nav-previous alignleft\">";
        // line 47
        if ((isset($context["next_page"]) ? $context["next_page"] : null)) {
            // line 48
            echo "<a href=\"";
            echo twig_escape_filter($this->env, (isset($context["site_url"]) ? $context["site_url"] : null), "html", null, true);
            echo "?paged=";
            echo twig_escape_filter($this->env, ((isset($context["paged"]) ? $context["paged"] : null) + 1), "html", null, true);
            echo "\" ><span class=\"meta-nav\">&larr;</span> 早期文章</a>";
        }
        // line 50
        echo "</div>
    <div class=\"nav-next alignright\">";
        // line 52
        if (((isset($context["paged"]) ? $context["paged"] : null) > 1)) {
            // line 53
            echo "<a href=\"";
            echo twig_escape_filter($this->env, (isset($context["site_url"]) ? $context["site_url"] : null), "html", null, true);
            echo "?paged=";
            echo twig_escape_filter($this->env, ((isset($context["paged"]) ? $context["paged"] : null) - 1), "html", null, true);
            echo "\" >较晚文章 <span class=\"meta-nav\">&rarr;</span></a>";
        }
        // line 55
        echo "</div>
</nav><!-- #nav-below .navigation -->
";
    }

    public function getTemplateName()
    {
        return "home/index.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  236 => 55,  229 => 53,  227 => 52,  224 => 50,  217 => 48,  215 => 47,  210 => 43,  194 => 39,  191 => 38,  188 => 37,  167 => 35,  163 => 34,  146 => 33,  143 => 32,  139 => 30,  116 => 28,  112 => 27,  95 => 26,  74 => 19,  68 => 15,  58 => 12,  53 => 10,  44 => 8,  35 => 5,  31 => 4,  28 => 3,  149 => 54,  145 => 53,  141 => 31,  132 => 45,  118 => 43,  114 => 42,  107 => 36,  93 => 34,  89 => 33,  82 => 24,  66 => 25,  62 => 24,  55 => 11,  41 => 16,  37 => 15,  23 => 3,  19 => 1,);
    }
}
