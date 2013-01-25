<?php
/**
 * This file is part of the CultPHP (https://github.com/azhai/cultphp)
 *
 * Copyright (c) 2012
 * author: 阿债 (Ryan Liu)
 * date: 2013-01-09
 */

/**
 * 将单词转为复数形式
 * COPY FROM http://paulosman.me/2007/03/03/php-pluralize-method.html
 */
function pluralize( $string )
{
    $plural = array(
        array( '/(quiz)$/i',               "$1zes"   ),
    array( '/^(ox)$/i',                "$1en"    ),
    array( '/([m|l])ouse$/i',          "$1ice"   ),
    array( '/(matr|vert|ind)ix|ex$/i', "$1ices"  ),
    array( '/(x|ch|ss|sh)$/i',         "$1es"    ),
    array( '/([^aeiouy]|qu)y$/i',      "$1ies"   ),
    array( '/([^aeiouy]|qu)ies$/i',    "$1y"     ),
        array( '/(hive)$/i',               "$1s"     ),
        array( '/(?:([^f])fe|([lr])f)$/i', "$1$2ves" ),
        array( '/sis$/i',                  "ses"     ),
        array( '/([ti])um$/i',             "$1a"     ),
        array( '/(buffal|tomat)o$/i',      "$1oes"   ),
        array( '/(bu)s$/i',                "$1ses"   ),
        array( '/(alias|status)$/i',       "$1es"    ),
        array( '/(octop|vir)us$/i',        "$1i"     ),
        array( '/(ax|test)is$/i',          "$1es"    ),
        array( '/s$/i',                    "s"       ),
        array( '/$/',                      "s"       )
    );

    $irregular = array(
    array( 'move',   'moves'    ),
    array( 'sex',    'sexes'    ),
    array( 'child',  'children' ),
    array( 'man',    'men'      ),
    array( 'person', 'people'   )
    );

    $uncountable = array( 
    'sheep', 
    'fish',
    'series',
    'species',
    'money',
    'rice',
    'information',
    'equipment'
    );

    // save some time in the case that singular and plural are the same
    if ( in_array( strtolower( $string ), $uncountable ) )
    return $string;

    // check for irregular singular forms
    foreach ( $irregular as $noun )
    {
    if ( strtolower( $string ) == $noun[0] )
        return $noun[1];
    }

    // check for matches using regular expressions
    foreach ( $plural as $pattern )
    {
    if ( preg_match( $pattern[0], $string ) )
        return preg_replace( $pattern[0], $pattern[1], $string );
    }

    return $string;
}


/**
 * 将下划线分隔的单词转为驼峰，可选最后一个单词复数化
 */
function to_caml($uname, $pluralize_last=false)
{
    $words = explode('_', $uname);
    if ($pluralize_last) {
        $last = count($words) - 1;
        $words[$last] = pluralize( $words[$last] );
    }
    return implode('', array_map('ucfirst', $words));
}


/**
 * 产生指定长度的随机字符串
 */
function rand_string($length=8)
{
    //字符池，去掉了难以分辨的0,1,o,O,l,I
    $good_letters = 'abcdefghijkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789';
    //每次可以产生的字符串最大长度，不能超出字符池的长度
    $max_gen_length = floor( strlen($good_letters) / 2);
    $buffers = '';
    srand( (float)microtime() * 1000000 );
    while ($length > 0) {
        $gen_length = min($length, $max_gen_length);
        $good_letters = str_shuffle($good_letters);
        $buffers .= substr($good_letters, 0, $gen_length);
        $length -= $gen_length;
    }
    return $buffers;
}


/**
 * Newline preservation help function for wpautop
 *
 * @since 3.1.0
 * @access private
 * @param array $matches preg_replace_callback matches array
 * @return string
 */
function _autop_newline_preservation_helper( $matches ) {
    return str_replace("\n", "<WPPreserveNewline />", $matches[0]);
}


/**
 * Replaces double line-breaks with paragraph elements.
 *
 * A group of regex replaces used to identify text formatted with newlines and
 * replace double line-breaks with HTML paragraph tags. The remaining
 * line-breaks after conversion become <<br />> tags, unless $br is set to '0'
 * or 'false'.
 *
 * @since 0.71
 *
 * @param string $pee The text which has to be formatted.
 * @param bool $br Optional. If set, this will convert all remaining line-breaks after paragraphing. Default true.
 * @return string Text which has been converted into correct paragraph tags.
 */
function wpautop($pee, $br = true) {
    $pre_tags = array();

    if ( trim($pee) === '' )
        return '';

    $pee = $pee . "\n"; // just to make things a little easier, pad the end

    if ( strpos($pee, '<pre') !== false ) {
        $pee_parts = explode( '</pre>', $pee );
        $last_pee = array_pop($pee_parts);
        $pee = '';
        $i = 0;

        foreach ( $pee_parts as $pee_part ) {
            $start = strpos($pee_part, '<pre');

            // Malformed html?
            if ( $start === false ) {
                $pee .= $pee_part;
                continue;
            }

            $name = "<pre wp-pre-tag-$i></pre>";
            $pre_tags[$name] = substr( $pee_part, $start ) . '</pre>';

            $pee .= substr( $pee_part, 0, $start ) . $name;
            $i++;
        }

        $pee .= $last_pee;
    }

    $pee = preg_replace('|<br />\s*<br />|', "\n\n", $pee);
    // Space things out a little
    $allblocks = '(?:table|thead|tfoot|caption|col|colgroup|tbody|tr|td|th|div|dl|dd|dt|ul|ol|li|pre|select|option|form|map|area|blockquote|address|math|style|p|h[1-6]|hr|fieldset|noscript|samp|legend|section|article|aside|hgroup|header|footer|nav|figure|figcaption|details|menu|summary)';
    $pee = preg_replace('!(<' . $allblocks . '[^>]*>)!', "\n$1", $pee);
    $pee = preg_replace('!(</' . $allblocks . '>)!', "$1\n\n", $pee);
    $pee = str_replace(array("\r\n", "\r"), "\n", $pee); // cross-platform newlines
    if ( strpos($pee, '<object') !== false ) {
        $pee = preg_replace('|\s*<param([^>]*)>\s*|', "<param$1>", $pee); // no pee inside object/embed
        $pee = preg_replace('|\s*</embed>\s*|', '</embed>', $pee);
    }
    $pee = preg_replace("/\n\n+/", "\n\n", $pee); // take care of duplicates
    // make paragraphs, including one at the end
    $pees = preg_split('/\n\s*\n/', $pee, -1, PREG_SPLIT_NO_EMPTY);
    $pee = '';
    foreach ( $pees as $tinkle )
        $pee .= '<p>' . trim($tinkle, "\n") . "</p>\n";
    $pee = preg_replace('|<p>\s*</p>|', '', $pee); // under certain strange conditions it could create a P of entirely whitespace
    $pee = preg_replace('!<p>([^<]+)</(div|address|form)>!', "<p>$1</p></$2>", $pee);
    $pee = preg_replace('!<p>\s*(</?' . $allblocks . '[^>]*>)\s*</p>!', "$1", $pee); // don't pee all over a tag
    $pee = preg_replace("|<p>(<li.+?)</p>|", "$1", $pee); // problem with nested lists
    $pee = preg_replace('|<p><blockquote([^>]*)>|i', "<blockquote$1><p>", $pee);
    $pee = str_replace('</blockquote></p>', '</p></blockquote>', $pee);
    $pee = preg_replace('!<p>\s*(</?' . $allblocks . '[^>]*>)!', "$1", $pee);
    $pee = preg_replace('!(</?' . $allblocks . '[^>]*>)\s*</p>!', "$1", $pee);
    if ( $br ) {
        $pee = preg_replace_callback('/<(script|style).*?<\/\\1>/s', '_autop_newline_preservation_helper', $pee);
        $pee = preg_replace('|(?<!<br />)\s*\n|', "<br />\n", $pee); // optionally make line breaks
        $pee = str_replace('<WPPreserveNewline />', "\n", $pee);
    }
    $pee = preg_replace('!(</?' . $allblocks . '[^>]*>)\s*<br />!', "$1", $pee);
    $pee = preg_replace('!<br />(\s*</?(?:p|li|div|dl|dd|dt|th|pre|td|ul|ol)[^>]*>)!', '$1', $pee);
    $pee = preg_replace( "|\n</p>$|", '</p>', $pee );

    if ( !empty($pre_tags) )
        $pee = str_replace(array_keys($pre_tags), array_values($pre_tags), $pee);

    return $pee;
}