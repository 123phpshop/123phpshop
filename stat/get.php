<?php 
/**
 * 123PHPSHOP
 * ============================================================================
 * 版权所有 2015 上海序程信息科技有限公司，并保留所有权利。
 * 网站地址: http://www.123PHPSHOP.com；
 * ----------------------------------------------------------------------------
 * 这是一个免费的软件。您可以在商业目的和非商业目的地前提下对程序除本声明之外的
 * 代码进行修改和使用；您可以对程序代码进行再发布，但一定请保留
 * 本声明和上海序程信息科技有限公司的联系方式！本软件中使用到的第三方代码版权属
 * 于原公司所有。上海序程信息科技有限公司拥有对本声明和123PHPSHOP软件使用的最终
 * 解释权！
 * ============================================================================
 *  作者:	123PHPSHOP团队
 *  手机:	13391334121
 *  邮箱:	service@123phpshop.com
 */
 ?><?php

// location MUST BE either a relative url to a file, relative to this
// script (get.php) OR an absolute url (this should be avoided).

// Usage: replace
// <a href="../pub/fat-recover-0.1.tar.gz">
// by
// <a href="../stats/get.php?location=../pub/fat-recover-0.1.tar.gz">

// DO NOT OUTPUT SOMETHING BEFORE header();

function remove_dotdot($path)
{
    while (is_integer($n = strpos($path,"/..")))
    {
        // extrat left & right part of the search string
        $gauche = substr($path, 0, $n);
        $droit  = substr($path, $n+strlen("/.."));

        // remove last / component, like "/stats"
        $gauche = substr($gauche, 0, - strlen(strrchr($gauche,"/")));

        $path = $gauche . $droit;
    }

    return $path;
}

if (isset($location))
{
    header("Location: $location");

    // Convert $_SERVER['PHP_SELF']
    $url = parse_url($_SERVER['PHP_SELF']);
    $path = dirname($url['path']) . "/" . $location;

    // change $_SERVER['PHP_SELF']
    $_SERVER['PHP_SELF'] = remove_dotdot($path);
}

require "stats.php";
?>