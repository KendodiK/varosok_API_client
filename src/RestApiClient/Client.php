<?php
namespace App\RestApiClient;

/**
 * @author Endrődi Kálmán
 */

class Client //implements ClientInterface 
{
    const API_URL = "http://localhost:8000/";
    /**
     * The whole url including host, api uri ang jql query.
     * @var string
     */
    protected $url;

    function __construct($url = self::API_URL) 
    {
        $this->url = $url;
    }

    public function getUrl() {
        return $this->url;
    }

    function get($route, array $query = [])
    {
        $url = $this->getUrl() . $route;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl,
            CURLOPT_HTTPHEADER,
            array('Contetn-Type: applicaton/json')
        );
        $response = curl_exec($curl);
        if(!$response) {
            trigger_error(curl_error($curl));
        }
        curl_close($curl);

        return json_decode($response, TRUE);
    }

    function post($url, array $data = [])
    {
        $json = json_encode($data);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_URL, $this->url . $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Content-Length: ' . strlen($json)]);
        curl_setopt($curl, CURLOPT_POST, TRUE);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
        $response = curl_exec($curl);

        if (!$response) {
            trigger_error(curl_error($curl));
        }
        curl_close($curl);

        return json_encode($response, TRUE);
    }

    function delete($url, $id)
    {
        $json = json_encode(['id' => $id]);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_URL, $this->url . $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Content-Length: ' . strlen($json)]);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
        $response = curl_exec($curl);
        
        if(!$response) {
            $error = curl_error($curl);
            if ($error) {
                trigger_error($error);
            }
        }
        curl_close($curl);

        return json_decode($response, TRUE);
    }

    function put($url, $data) 
    {
        $json = json_encode($data);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_URL, $this->url . $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Content-Length: ' . strlen($json)]);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
        $response = curl_exec($curl);

        if (!$response) {
            trigger_error(curl_error($curl));
        }
        curl_close($curl);

        return json_encode($response, TRUE);        
    }
}