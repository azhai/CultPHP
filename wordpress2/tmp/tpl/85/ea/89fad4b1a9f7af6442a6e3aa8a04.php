<?php

/* public/base.html */
class __TwigTemplate_85ea89fad4b1a9f7af6442a6e3aa8a04 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'content' => array($this, 'block_content'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "
<!DOCTYPE html>
<!--[if IE 7]>
<html class=\"ie ie7\" lang=\"zh-CN\">
<![endif]-->
<!--[if IE 8]>
<html class=\"ie ie8\" lang=\"zh-CN\">
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html lang=\"zh-CN\">
<!--<![endif]-->
<head>
<meta charset=\"UTF-8\" />
<meta name=\"viewport\" content=\"width=device-width\" />
<title>";
        // line 15
        echo twig_escape_filter($this->env, (isset($context["site_title"]) ? $context["site_title"] : null), "html", null, true);
        echo " | ";
        echo twig_escape_filter($this->env, (isset($context["site_description"]) ? $context["site_description"] : null), "html", null, true);
        echo "</title>
<link rel=\"profile\" href=\"http://gmpg.org/xfn/11\" />
<link rel=\"pingback\" href=\"";
        // line 17
        echo twig_escape_filter($this->env, (isset($context["site_url"]) ? $context["site_url"] : null), "html", null, true);
        echo "xmlrpc.php\" />
<!--[if lt IE 9]>
<script src=\"";
        // line 19
        echo twig_escape_filter($this->env, (isset($context["static_url"]) ? $context["static_url"] : null), "html", null, true);
        echo "/js/html5.js\" type=\"text/javascript\"></script>
<![endif]-->
<meta name='robots' content='noindex,nofollow' />
<link rel=\"alternate\" type=\"application/rss+xml\" title=\"";
        // line 22
        echo twig_escape_filter($this->env, (isset($context["site_title"]) ? $context["site_title"] : null), "html", null, true);
        echo " &raquo; Feed\" href=\"";
        echo twig_escape_filter($this->env, (isset($context["site_url"]) ? $context["site_url"] : null), "html", null, true);
        echo "?feed=rss2\" />
<link rel=\"alternate\" type=\"application/rss+xml\" title=\"";
        // line 23
        echo twig_escape_filter($this->env, (isset($context["site_title"]) ? $context["site_title"] : null), "html", null, true);
        echo " &raquo; 评论 Feed\" href=\"";
        echo twig_escape_filter($this->env, (isset($context["site_url"]) ? $context["site_url"] : null), "html", null, true);
        echo "?feed=comments-rss2\" />
<link rel='stylesheet' id='prettify-gc-syntax-highlighter-css'  href='";
        // line 24
        echo twig_escape_filter($this->env, (isset($context["static_url"]) ? $context["static_url"] : null), "html", null, true);
        echo "/css/prettify.css?ver=3.5' type='text/css' media='all' />
<link rel='stylesheet' id='twentytwelve-fonts-css'  href='http://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,400,700&#038;subset=latin,latin-ext' type='text/css' media='all' />
<link rel='stylesheet' id='twentytwelve-style-css'  href='";
        // line 26
        echo twig_escape_filter($this->env, (isset($context["static_url"]) ? $context["static_url"] : null), "html", null, true);
        echo "/css/style.css?ver=3.5' type='text/css' media='all' />
<!--[if lt IE 9]>
<link rel='stylesheet' id='twentytwelve-ie-css'  href='";
        // line 28
        echo twig_escape_filter($this->env, (isset($context["static_url"]) ? $context["static_url"] : null), "html", null, true);
        echo "/css/ie.css?ver=20121010' type='text/css' media='all' />
<![endif]-->
<link rel=\"EditURI\" type=\"application/rsd+xml\" title=\"RSD\" href=\"";
        // line 30
        echo twig_escape_filter($this->env, (isset($context["site_url"]) ? $context["site_url"] : null), "html", null, true);
        echo "xmlrpc.php?rsd\" />
<link rel=\"wlwmanifest\" type=\"application/wlwmanifest+xml\" href=\"";
        // line 31
        echo twig_escape_filter($this->env, (isset($context["site_url"]) ? $context["site_url"] : null), "html", null, true);
        echo "wp-includes/wlwmanifest.xml\" /> 
<meta name=\"generator\" content=\"WordPress 3.5\" />
    <style type=\"text/css\">.recentcomments a{display:inline !important;padding:0 !important;margin:0 !important;}</style>
</head>

<body class=\"home blog custom-font-enabled single-author\">
<div id=\"page\" class=\"hfeed site\">
    <header id=\"masthead\" class=\"site-header\" role=\"banner\">
        <hgroup>
            <h1 class=\"site-title\"><a href=\"?\" title=\"";
        // line 40
        echo twig_escape_filter($this->env, (isset($context["site_title"]) ? $context["site_title"] : null), "html", null, true);
        echo "\" rel=\"home\">";
        echo twig_escape_filter($this->env, (isset($context["site_title"]) ? $context["site_title"] : null), "html", null, true);
        echo "</a></h1>
            <h2 class=\"site-description\">";
        // line 41
        echo twig_escape_filter($this->env, (isset($context["site_description"]) ? $context["site_description"] : null), "html", null, true);
        echo "</h2>
        </hgroup>

        <nav id=\"site-navigation\" class=\"main-navigation\" role=\"navigation\">
            <h3 class=\"menu-toggle\">菜单</h3>
            <a class=\"assistive-text\" href=\"#content\" title=\"跳至内容\">跳至内容</a>
            <div class=\"nav-menu\">
            <ul>
                <li class=\"current_page_item\"><a href=\"?\" title=\"首页\">首页</a></li>
                <li class=\"page_item page-item-2\"><a href=\"";
        // line 50
        echo twig_escape_filter($this->env, (isset($context["site_url"]) ? $context["site_url"] : null), "html", null, true);
        echo "?page_id=2\">About</a></li>
            </ul>
            </div>
        </nav><!-- #site-navigation -->

    </header><!-- #masthead -->

    <div id=\"main\" class=\"wrapper\">
        <div id=\"primary\" class=\"site-content\">
            <div id=\"content\" role=\"main\">
";
        // line 60
        $this->displayBlock('content', $context, $blocks);
        // line 61
        echo "            </div>
            <!-- #content -->
        </div><!-- #primary -->
    \t<div id=\"secondary\" class=\"widget-area\" role=\"complementary\">
";
        // line 65
        echo (isset($context["sidebar"]) ? $context["sidebar"] : null);
        echo "
        </div><!-- #secondary -->
    </div><!-- #main .wrapper -->

    <footer id=\"colophon\" role=\"contentinfo\">
        <div class=\"site-info\">
            <a href=\"http://cn.wordpress.org/\" title=\"优雅的个人发布平台\">自豪地采用 WordPress</a>
        </div><!-- .site-info -->
    </footer><!-- #colophon -->
</div><!-- #page -->

<script type='text/javascript' src='";
        // line 76
        echo twig_escape_filter($this->env, (isset($context["static_url"]) ? $context["static_url"] : null), "html", null, true);
        echo "/js/prettify.js?ver=3.5'></script>
<script type='text/javascript' src='";
        // line 77
        echo twig_escape_filter($this->env, (isset($context["static_url"]) ? $context["static_url"] : null), "html", null, true);
        echo "/js/launch.js?ver=3.5'></script>
<script type='text/javascript' src='";
        // line 78
        echo twig_escape_filter($this->env, (isset($context["static_url"]) ? $context["static_url"] : null), "html", null, true);
        echo "/js/navigation.js?ver=1.0'></script>
</body>
</html>";
    }

    // line 60
    public function block_content($context, array $blocks = array())
    {
    }

    public function getTemplateName()
    {
        return "public/base.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  165 => 60,  158 => 78,  154 => 77,  150 => 76,  136 => 65,  130 => 61,  128 => 60,  115 => 50,  103 => 41,  97 => 40,  85 => 31,  81 => 30,  76 => 28,  71 => 26,  60 => 23,  54 => 22,  48 => 19,  43 => 17,  36 => 15,  20 => 1,  236 => 55,  229 => 53,  227 => 52,  224 => 50,  217 => 48,  215 => 47,  210 => 43,  194 => 39,  191 => 38,  188 => 37,  167 => 35,  163 => 34,  146 => 33,  143 => 32,  139 => 30,  116 => 28,  112 => 27,  95 => 26,  74 => 19,  68 => 15,  58 => 12,  53 => 10,  44 => 8,  35 => 5,  31 => 4,  28 => 3,  149 => 54,  145 => 53,  141 => 31,  132 => 45,  118 => 43,  114 => 42,  107 => 36,  93 => 34,  89 => 33,  82 => 24,  66 => 24,  62 => 24,  55 => 11,  41 => 16,  37 => 15,  23 => 3,  19 => 1,);
    }
}
