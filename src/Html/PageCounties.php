<?php
namespace App\Html;

/**
 * @author Endrődi Kálmán
 */


use App\Html\AbstractPage;

class PageCounties extends AbstractPage
{
    static function table(array $entities)
    {
        echo '<h1>Megyék</h1>';
        self::searchBar();
        echo '<table id="counties-table">';
        self::tableHead();
        self::tableBody($entities);
        echo '</table>';

    }

    static function searchBar() 
    {
        echo'
            <input type="search" name="needed" id="needed" placeholder="keres">
            <button type="submit" id="btn-search" name="btn-search" onclick="search()">Keres</button>  
        ';
    }

    static function tableHead(){
        echo'
        <thead>
            <tr>
                <th class="id-col">#</th>
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
        echo'
                <th>&nbsp;</th>
                <th>
                    <form name="county-editor" method="post" action="">
                        <input type="hidden" id="id" name="id">
                        <input type="search" id="name" name="name" placeholder="Megye">
                        <button type="submit" name="btn-save-county" title="Ment">Mentés</button>
                        <button type="button" id="btn-cancel" title="Mégese">Mégse</button>
                    </form>
                </th>

                <th class="felx">
                &nbsp;
                </th>
        ';
    }

    static function tableBody(array $entities)
    {
        echo'<tbody>';
        $i = 0;
        foreach ($entities as $entity){
            $onClick = sprintf('btnEditOnClick(%d, "%s")', $entity['id'], $entity['name']);
            echo"
            <tr class='". (++$i % 2 ? "odd" : "even") . "' id='id-entity-{$entity['id']}'>
                <td id='{$entity['id']}'>{$entity['id']}</td>
                <td>{$entity['name']}</td>
                <td class='flex float-right'>
                    <button type='button' id='btn-edit-{$entity['id']}' onclick='$onClick' title='Módosít'>Módosít</button>
                    <form method='post' action=''>
                        <input type='hidden' id='id-delete' name='id-delete' value='{$entity['id']}'>
                        <button type='submit' name='btn-delete' title='Töröl'>Töröl</button>
                    </form>
                </td>
            </tr>
            ";
        }
        echo'</tbody>';
    }
}