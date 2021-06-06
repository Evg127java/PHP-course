<?php

define('PASSWORDS_FILE', __DIR__ . '/passwords.txt');

/* Get input data */
$contents = readHttpLikeInput();

$http = parseTcpStringAsHttpRequest($contents);
processHttpRequest($http["method"], $http["uri"], $http["headers"], $http["body"]);

/**
 * Auxiliary method for input data reading
 *
 * @return string input http request
 */
function readHttpLikeInput(): string
{
    $f = fopen( 'php://stdin', 'r' );
    $store = "";
    $toRead = 0;
    while( $line = fgets( $f ) ) {
        $store .= preg_replace("/\r/", "", $line);
        if (preg_match('/Content-Length: (\d+)/',$line,$m))
            $toRead=$m[1]*1;
        if ($line == "\r\n")
            break;
    }
    if ($toRead > 0)
        $store .= fread($f, $toRead);
    return $store;
}

/**
 * Processes input http request and displayed result in the console
 *
 * @param string $method  request's method
 * @param string $uri     request's URI
 * @param array $headers  request's headers
 * @param string $body    request's body
 */
function processHttpRequest(string $method, string $uri, array $headers, string $body): void
{
    $contentType = getContentType($headers);

    /* Check request's content-type */
    if ($method == 'POST' && $contentType != 'application/x-www-form-urlencoded') {
        $body = 'Not Found';
        outputHttpResponse(404, 'Not Found', getHeaders($body), $body);
        return;
    }

    /* Check request's URI */
    if ($method == 'POST' && $uri != '/api/checkLoginAndPassword' || $method != 'POST') {
        $body = 'Bad Request';
        outputHttpResponse(400, 'Bad Request', getHeaders($body), $body);
        return;
    }

    /* Process request's body */
    if ($body != null && $method == 'POST') {
        $loginAndPass = getLoginAndPassFromBody($body);

        /* Check if the passwords file exists */
        if (!file_exists(PASSWORDS_FILE)) {
            $body = 'Internal Server Error';
            outputHttpResponse(500, 'Internal Server Error', getHeaders($body), $body);
            return;
        }

        /* Login and password verifying */
        $passwords = file_get_contents(PASSWORDS_FILE);
        $token = strtok($passwords, "\n");
        $isVerified = false;
        while ($token != false) {
            if ($loginAndPass == $token) {
                $isVerified = true;
                break;
            }
            $token = strtok("\n");
        }

        $statusCode = $isVerified ? 200 : 403;
        $statusMessage = $isVerified ? 'OK' : 'Denied';
        $body = $isVerified ? '<h1 style="color:green">FOUND</h1>' : '<h1 style="color:red">NOT FOUND</h1>';
        outputHttpResponse($statusCode, $statusMessage, getHeaders($body), $body);
    }
}

/**
 * Gets string of login and password from request's body
 *
 * @param string $body  request's body
 * @return string       login:password string
 */
function getLoginAndPassFromBody(string $body): string
{
    $params = explode('&', $body);
    list(, $login) = preg_split("#=#", $params[0]);
    list(, $password) = preg_split("#=#", $params[1]);
    return $login . ':' . $password;
}

/**
 * Builds headers array
 *
 * @param string $body     String of response's body
 * @return array|string[]  Array of headers for http response
 */
function getHeaders(string $body): array
{
    return [
        'Date: ' . date("D, d M Y H:i:s e"),
        'Server: Apache/2.2.14 (Win32)',
        'Content-Length: ' . strlen($body),
        'Connection: Closed',
        'Content-Type: text/html; charset=utf-8',
    ];
}

/**
 * Gets request's content-type from the specified array
 *
 * @param array $headers headers array
 * @return string        request's content-type value
 */
function getContentType(array $headers): string
{
    $contentType = '';
    foreach ($headers as $header) {
        if ($header[0] == 'Content-Type') {
            $contentType = $header[1];
            break;
        }
    }
    return $contentType;
}

/**
 * Displays http response
 *
 * @param int $statusCode        response's status code
 * @param string $statusMessage  response's status message
 * @param array $headers         response's headers
 * @param string $body           response's body
 */
function outputHttpResponse(int $statusCode, string $statusMessage, array $headers, string $body): void
{
    $startLine = 'HTTP/1.1 ' . $statusCode . ' ' . $statusMessage;
    $headersString = implode("\n", $headers);
    echo $startLine . "\n" . $headersString . "\n\n" . $body;
}


/**
 * Gets an array of parsed http request data from the passed string.
 * @param $string "http_request" as string data
 * @return array  The array of parsed string in the required format
 */
function parseTcpStringAsHttpRequest(string $string): array
{
    /* Split the input string into the main part and body */
    list($mainPart, $body) = preg_split("#\R{2}#", $string, 2);

    /* Split the main part into the start line and headers */
    list($startLine, $headers) = preg_split("#\R#", $mainPart, 2);

    /* Split the start line into the method, uri and protocol parts */
    list($method, $uri,) = preg_split("#\s#", $startLine);

    $request = [];
    $request['method'] = $method;
    $request['uri'] = $uri;
    $headersSubArray = explode("\n", $headers);
    foreach ($headersSubArray as $header) {
        list($name, $content) = preg_split("#:\s#", $header);
        $request['headers'][] = [$name, $content];
    }
    $request['body'] = $body;
    return $request;
}