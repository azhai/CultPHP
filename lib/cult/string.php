<?php
/**
 * This file is part of the CultPHP (https://github.com/azhai/cultphp)
 *
 * Copyright (c) 2012
 * author: 阿债 (Ryan Liu)
 * date: 2013-01-09
 */

/*
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


/*
 * 将下划线分隔的单词转为驼峰复数形式(最后一个单词复数化)
 */
function tocamls($uname)
{
    $words = explode('_', $uname);
    $last = count($words) - 1;
    $words[$last] = pluralize( $words[$last] );
    $camls = implode('', array_map('ucfirst', $words));
    return $camls;
}
