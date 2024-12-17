<?php

/**
 * @author Endrődi Kálmán
 */

session_start();
include "./vendor/autoload.php";

use App\Html\PageCounties;
use App\Html\Request;

PageCounties::head();
PageCounties::nav();
Request::handle();
PageCounties::footer();