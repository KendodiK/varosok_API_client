<?php
namespace App\Html;

/**
 * @author Endrődi Kálmán
 */

use App\RestApiClient\Client;

class Request
{
    static function handle()
    {
        switch ($_SERVER["REQUEST_METHOD"]){
            case "POST":
                self::postRequest();
                break;
            case "GET":
            default:
                self::getRequest();
                break;
        }
    }

    private static function getRequest()
    {
        /*$request = $_REQUEST;
        switch ($request) {
            case isset($request['btn-counties']) :
                PageCounties::table(self::getCounties());
                break;
        }*/
    }

    public static function getCounties()
    {
        $client = new Client();
        $response = $client->get('counties');

        return $response['data'];
    }

    private static function postRequest()
    {
        $request = $_REQUEST;
        $client = new Client();
        switch ($request) {
            case isset($request['btn-save-county']):
                if(!empty($_POST['id'])){
                    if(!empty($_POST['zip-code'])){
                        if($_POST['counties-dropdown-modify'] != $_SESSION['id-county']){
                            $_SESSION['id-county'] = $_POST['counties-dropdown-modify'];
                        }
                        self::putRequest("cities/");
                        $newCityCh = substr($_POST['name'], 0, 1);
                        self::showCities($_SESSION['id-county'], $newCityCh);
                        break;
                    }
                    self::putRequest("counties/");
                    PageCounties::table(self::getCounties());
                    break;
                }
                if(isset($_POST['name'])) {
                    if(!empty(($_POST['zip-code']))){
                        $countyId = $_SESSION['id-county'];
                        $data = ['zip_code' => $_POST['zip-code'], 'city' => $_POST['name'], 'id_county' => $countyId];
                        $client->post("counties/" . $countyId . "/cities", $data);
                        $newCityCh = substr($_POST['name'], 0, 1);
                        self::showCities($countyId, $newCityCh);
                        break;
                    }
                    $data = ['id' => $_POST['id'], 'name' => $_POST['name']];
                    $client->post('counties', $data);
                    PageCounties::table(self::getCounties());
                    break;
                }
            case isset($request['btn-delete']):
                if(isset($_POST['deletable'])){
                    self::deleteRequest("cities/");
                    $newCityCh = substr($_POST['deletable'], 0, 1);
                    $countyId = $_SESSION['id-county'];
                    self::showCities($countyId, $newCityCh);
                    break;
                }
                self::deleteRequest("counties/");
                PageCounties::table(self::getCounties());
                break;
            case(isset($_POST['btn-counties'])) :
                PageCounties::table(self::getCounties());
                break;
            
            case(isset($_POST['btn-cities'])) :
                PageCities::select(self::getCounties());
                break;

            case(isset($_POST['btn-show'])):
                $countyId = $_POST['counties-dropdown'];
                $_SESSION['id-county'] = $countyId;
                self::showCities($countyId, "0");
                break;
            case(isset($_POST['btn-show-city'])):
                $countyId = $_POST['counties-dropdown'];
                $ch = $_POST['char-city'];
                self::showCities($countyId, $ch);
                break;
            case(isset($_POST['btn-search'])):
                $cities = $client->get("/cities")['data'];
                $id = 0;
                $needed = $_POST['needed-city'];
                foreach($cities as $city){
                    if(strtolower($needed) == strtolower($city['city'])){
                        $id = $city['id'];
                        $data = $client->get("cities/" . $id)['data'];
                    }
                }
                if ($id == 0){
                    foreach($cities as $city){
                        if($needed == $city['zip_code']){
                            $id = $city['id'];
                            $data = $client->get("cities/" . $id)['data'];
                        }
                    }
                }
                if (is_numeric($id)){
                    foreach($cities as $city){
                        if($needed == $city['id']){
                            if($id != 0){
                                $id = $city['id'];
                                array_push($data,$client->get("cities/" . $id)['data'][0]);
                            }
                            else{
                                $id = $city['id'];
                                $data = $client->get("cities/" . $id)['data'];
                            }
                        }
                    }
                }

                PageCities::responseTable($data);
                break;
            case (isset($_POST['needed'])):
                    $needed = $_POST['needed'];
                    $id = 0;
                    $counties = self::getCounties();
                    $id = 0;      
                    foreach($counties as $couty){
                        if(strtolower($couty['name']) == strtolower($needed)){
                            $id = $couty['id'];
                        }
                    }
                    if($id == 0){
                        foreach($counties as $couty){
                            if($couty['id'] == $needed){
                                $id = $couty['id'];
                            }
                        }                       
                    }
                
                    echo $id; 
                    break;              
        }
    }

    private static function deleteRequest($url){
        $client = new Client();
        $id = $_POST['id-delete'];
        $client->delete($url . $id, $id);     
    }

    private static function putRequest($url) {
        $client = new Client();
        $id = $_POST['id'];
        if($url == 'cities/') {
            $data = ["id" => $id, "name" => $_POST['name'], "zip-code" => $_POST['zip-code'], "county-id" => $_SESSION['id-county']];
            $client->put($url . $id, $data);
        }
        else if ($url == 'counties/') {
            $data = ["id" => $id, "name" => $_POST['name']];
            $client->put($url . $id, $data);
        }
    }

    private static function getCities($countyId) {
        $client = new Client();
        $response = $client->get('counties/' . $countyId . '/cities');

        return $response['data'];
    }

    private static function showCities($countyId, $cityCh){
        PageCities::select(self::getCounties());
        $cities = self::getCities($countyId);
        $abc = [];
        $citiesToWrite = [];
        foreach($cities as $city){
            $ch = substr($city['city'],0,1);
            if(!in_array($ch,$abc)){
                array_push($abc, $ch);
            }
            if($ch == $cityCh){
                $citiesToWrite[] = $city;
            }
        }
        PageCities::ABCButtons($abc, $countyId);
        if(!empty($citiesToWrite)){
            PageCities::table($citiesToWrite);
        }
    }
}
