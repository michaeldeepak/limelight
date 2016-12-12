<?php

/**
 * @author Michael Deepak <michaeldeepak@yahoo.com>
 * Sample php file to purge limelight content 
 */

include 'credentials.php';
include 'constants.php';


$pattern[] = array(
    'pattern'   => "http://*.example.com/images/*",
    'evict'     => false,
    'exact'     => false,
    'incqs'     => false
);

$email = array(
    'subject'   => EMAIL_SUBJECT,
    'to'        => EMAIL_TO
);

$callback = array(
    "url" => CALLBACK_URL
);

$data = array(
    'patterns' => $pattern,
    'email'    => $email,
    'callback' => $callback,
    'notes'    => "my first purge request"
);


$dataJson = json_encode($data);
$dataJson = str_replace('\/', '/', $dataJson);

$apiPath    = '/account/' . LIMELIGHT_SHORT_NAME . "/requests";
$uri        = LIMELIGHT_API_URL . $apiPath;

$queryString = '';
$httpHeaders = getHeaders('POST', $apiPath, $queryString, $dataJson);

$headers = array();
foreach($httpHeaders as $k=>$v)
{
    $headers[] = "$k:$v";
}

/**
 * use curl to executePurgeRequest limelight purge request
 */
executePurgeRequest($uri, $headers, $dataJson);

/**
 * 
 * @param string $uri       Limelight purge request url
 * @param array  $headers   Client request header with security token
 * @param string $data      json encoded request body
 * 
 * example response
 * {
 * "id":"8c1a86546c3611e49c633a03000021e9", "states":[
 * {
 * "ts":1415994226253, "state":"queued"
 * } ],
 * "username":"exampleuser", "shortname":"example", "patterns":[
 * {
 * "pattern":"http://*.example.com/images/*", "evict":false,
 * "exact":false,
 * "incqs":false
 * } ],
 * "email":{
 * "subject":"purge results", "to":"user@example.com"
 * }, "callback":{
 * "url":"http://test.example.com/my_callback.php" },
 * "notes":"my first purge request" }
 * 
 */
function executePurgeRequest($uri, $headers, $data)
{
    $ch = curl_init($uri);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
    $result = curl_exec($ch);
    
    if ($result === false) {
        echo 'Curl error: ' . curl_error($ch);
    } else {
        echo "<PRE>";
        print_r($result);
    }
}

/**
 * 
 * @param string $method    Http Method. (GET | POST)
 * @param string $path      Api request path
 * @param string $qs    Query string (if any) 
 * @param sting  $data      Request body
 * @return array request    header for client request
 */
function getHeaders($method, $path, $qs, $data) 
{
     $timestamp = number_format(time() * 1000, 0, '.', ''); //Get unix time in milliseconds
     
     $token = getHash($method, $path, $qs, $timestamp, $data);
     
     $headers = array(
         'Accept'                       => 'application/json',
         'Content-Type'                 => 'application/json',
         'X-LLNW-Security-Token'        => $token,
         'X-LLNW-Security-Principal'    => LIMELIGHT_USERNAME,
         'X-LLNW-Security-Timestamp'    => $timestamp,
         'Content-Length'               => strlen($data)
     );

    return $headers;
}

/**
 * Prepare data to create MAC hash digest to be used as a request security token
 *
 * @param string $method     Http Method. (GET | POST)
 * @param string $path       Api request path
 * @param string $timestamp  Unix time in milliseconds
 * @param string $data       Request body
 *
 * @return string Security token
 */
function getHash($method, $path, $qs, $timestamp, $data = '')
{
     //string containing, REQUEST_METHOD + QUERY_STRING (if present) + TIMESTAMP + REQUEST_BODY (if present)
    $datastring = $method . LIMELIGHT_API_URL . $path . $qs . $timestamp . $data;
    
    //Shared keys is stored in HEX format and should be decoded to ASCII 
    $dataKey = pack('H*', LIMELIGHT_SHARED_KEY);
    
    // Generate sha256  digest for the given data string
    return hash_hmac('sha256', $datastring, "$dataKey");
}