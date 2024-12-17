<?php
namespace App\Html;

/**
 * @author Endrődi Kálmán
 */


use App\Html\AbstractPage;
use App\Html\Request;

class PageCities extends AbstractPage
{
    static function table(array $entities)
    {
        echo '<table id="cities-table">';
        self::tableHead();
        self::tableBody($entities);
        echo '</table>';
    }

    static function searchBar() 
    {
        echo'
            <form name="search-city" method="post">
            <input type="search" name="needed-city" id="needed-city" placeholder="keres">
            <button type="submit" id="btn-search" name="btn-search">Keres</button>
            </form>  
        ';
    }

    static function responseTable($data){
        echo"<table id='response-table'>
            <tr>
                <th class='id-col'>#</th>
                <th>Megye</th>
                <th>Irányítószám</th>
                <th>Megnevezés</th>
                <th>Művelet</th>
            </tr>
            
            <tr id='editor' class='hidden'>";
            self::editor();
        echo"
            </tr>";

        foreach($data as $entity){
        $onClick = sprintf('btnEditOnClick(%d, "%s", %d)', $entity['id'], $entity['city'], $entity['zip_code']);
        echo"
            <tr>
                <td>{$entity['id']}</td>
                <td>";
                $request = new Request();
                $counties = $request->getCounties();
                $countyName = "";
                foreach($counties as $county){
                    if ($county['id'] == $entity['id_county']){
                        $countyName = $county['name'];
                    }
                }
            echo"
                {$countyName}</td>
                <td>{$entity['zip_code']}</td>
                <td>{$entity['city']}</td>
                <td class='flex float-right'>
                    <button type='button' id='btn-edit-{$entity['id']}' onclick='$onClick' title='Módosít'>Módosít</button>
                    <form method='post' action=''>
                        <input type='hidden' id='id-delete' name='id-delete' value='{$entity['id']}'>
                        <input type='hidden' id='deletable' name='deletable' value='{$entity['city']}'>
                        <button type='submit' name='btn-delete' title='Töröl'>Töröl</button>
                    </form>
                </td>
            ";
        }
    }

    static function tableHead(){
        echo'
        <thead>
            <tr>
                <th class="id-col">#</th>
                <th>Irányítószám</th>
                <th>Megnevezés</th>
                <th style="float: right; display: flex">
                    Művelet &nbsp;
                    <button type="button" id="btn-add" title="Új">+</button>
                </th>
            </tr>
            <tr id="editor" class="hidden">';
            self::editor();
        echo'
            </tr>
        </thead>
        ';
    }

    static function editor()
    {
        echo"
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>
                    <form name='city-editor' method='post'>
                        <input type='hidden' id='id' name='id'>
                        <input type='search' id='zip-code' name='zip-code' placeholder='Irányítószám'>
                        <input type='search' id='name' name='name' placeholder='Város'>
                        <select hidden name='counties-dropdown-modify' id='counties-dropdown-modify'>";
                        $request = new Request();
                        $counties = $request->getCounties();
                        foreach($counties as $county){
                            if ($county['id'] == $_SESSION['id-county']){
                                echo"<option value={$county['id']} id=id-dropdown-county-{$county['id']} selected > {$county['name']} </option>";
                            } else {
                                echo"<option value={$county['id']} id=id-dropdown-county-{$county['id']}> {$county['name']} </option>";
                            }
                        }
                        echo"</select>
                        <button type='submit' name='btn-save-county' title='Ment'>Mentés</button>
                        <button type='button' id='btn-cancel' title='Mégese'>Mégse</button>
                    </form>
                </th>

                <th class='felx'>
                &nbsp;
                </th>
        ";
    }

    static function tableBody(array $entities)
    {
        echo'<tbody>';
        $i = 0;
        foreach ($entities as $entity){
            $onClick = sprintf('btnEditOnClick(%d, "%s", %d)', $entity['id'], $entity['city'], $entity['zip_code']);
            echo"
            <tr class='". (++$i % 2 ? "odd" : "even") . "' id='id-entity-{$entity['id']}'>
                <td id='{$entity['id_county']}'>{$entity['id']}</td>
                <td>{$entity['zip_code']}</td>
                <td>{$entity['city']}</td>
                <td class='flex float-right'>
                    <button type='button' id='btn-edit-{$entity['id']}' onclick='$onClick' title='Módosít'>Módosít</button>
                    <form method='post' action=''>
                        <input type='hidden' id='id-delete' name='id-delete' value='{$entity['id']}'>
                        <input type='hidden' id='deletable' name='deletable' value='{$entity['city']}'>
                        <button type='submit' name='btn-delete' title='Töröl'>Töröl</button>
                    </form>
                </td>
            </tr>
            ";
        }
        echo'</tbody>';
    }

    static function select(array $counties)
    {
        echo"<h1>Városok</h1>
            <p>Válassza ki, melyik megye városait akarja látni:</p>
            <form method='post'>";
        echo"<select name='counties-dropdown' id='counties-dropdown'>";
            foreach($counties as $county){
                if ($county['id'] == $_SESSION['id-county']){
                    echo"<option value={$county['id']} id=id-dropdown-county-{$county['id']} selected > {$county['name']} </option>";
                } else {
                    echo"<option value={$county['id']} id=id-dropdown-county-{$county['id']}> {$county['name']} </option>";
                }
            }
        echo"</select>
            <button type='submit' id='btn-show' name='btn-show' title='Mutat'>Mutat</button>
            </form> <br>";
        self::searchBar();
    }

    static function ABCButtons($abc, $selectedCounty){
        echo"<div class='abc-div'>";
        foreach($abc as $ch){
            echo"<form class='abc-form' method='post'>
                <input type='hidden' id='char-city' name='char-city' value={$ch}>
                <input type='hidden' id='counties-dropdown' name='counties-dropdown' value={$selectedCounty}>
                <button type='submit' id='btn-show-city' name='btn-show-city'>{$ch}</button>
                </form>";
        }
        echo"</div>";
    }
}