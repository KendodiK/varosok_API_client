<?php
namespace App\Html;

/**
 * @author Endrődi Kálmán
 */


use App\Interfaces\PageInterface;

abstract class AbstractPage implements PageInterface 
{
    static function head() 
    {
        echo '
        <!doctype html>
        <html land="hu-hu">
        <head>
            <meta carset="utf-8">
            <meta name="viewport" content="width=device-width", initial-scale=1>

            <script src="src/js/jquery-3_7_1.js" type="text/javascript"></script>
            <script src="src/js/app.js" type="text/javascript"></script>
            <!-- <script src="js/bootstrap.js" type="text/javascript"></script> -->

            <link rel="stylesheet" href="css/style.css">

            <title>REST API ügyfél</title>

        </head>
        ';
    }

    static function nav() 
    {
        echo '
        <nav>
            <form name="nav" method="post" action="index.php">
                <button type="submit" name="btn-home">Kezdőlap</button>
                <button type="submit" id="btn-counties" name="btn-counties">Megyék</button>
                <button type="submit" name="btn-cities">Városok</button>
            </form>
        </nav>
        ';
    }

    static function footer() 
    {
        echo'
        <footer>
            Endrődi Kálmán
        </footer>
        </html>
        ';
    }

    abstract static function tableHead();

    abstract static function tableBody(array $entities);

    abstract static function table(array $entities);

    abstract static function searchBar();
}