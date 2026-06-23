<?php

$version = "yy.mm.vv";

include "contact.php";

$temp = "include/template/";
$lang = "include/lang/";
$func = "include/func/";



$css = "layout/css/";
$js = "layout/js/";
$img = "layout/images/";

// هذا الملف به دالة خاصة بي جلب عنوان الصفحة
include $func . "getTitle.php";

include $temp . "header.php";

if (!isset($noNavbar)) {
    include $temp . "navbar.php";
}