<?php
include "classes/config.php";
include "classes/functions.php";
include "classes/helpers.php";

$helper = new functions();

pre($GLOBALS['CFG']);

$res = $helper->connect();
pre($res);