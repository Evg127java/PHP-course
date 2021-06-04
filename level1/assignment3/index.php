<?php

/* Get input data */
$contents = readHttpLikeInput();

$http = parseTcpStringAsHttpRequest($contents);
processHttpRequest($http["method"], $http["uri"], $http["body"]);

/**
 * The service function from the GitHub for input data reading
 *
 * @return string input string
 */
function readHttpLikeInput(): string
{
    $f = fopen('php://stdin', 'r');
    $store = "";
    $toRead = 0;
    while ($line = fgets($f)) {
        $store .= preg_replace("/\r/", "", $line);
        if (preg_match('/Content-Length: (\d+)/', $line, $m))
            $toRead = $m[1] * 1;
        if ($line == "\r\n")
            break;
    }
    if ($toRead > 0)
        $store .= fread($f, $toRead);
    return $store;
}

/**
 * Processes http request passed as a set of preprocessed data
 *
 * @param string $method http request method
 * @param string $uri    http request URI
 * @param array $body    http request body
 */
function processHttpRequest(string $method, string $uri, array $body): void
{
    /* Set initial default values */
    $statusCode = 200;
    $contentLength = 0;
    $statusMessage = '';

    if ($method == 'GET' && !preg_match("#^/sum#", $uri)) {
        /* request method is GET AND URI is not equal to '/sum' */
        $statusCode = 404;
        $statusMessage = $body = 'Not Found';
        $contentLength = strlen($statusMessage);
    } elseif ($method == 'GET' && !preg_match("#\?nums=#", $uri) || $method != 'GET') {
        /* request method is GET AND URI is not contain ?nums=' OR request method is not GET */
        $statusCode = 400;
        $statusMessage = $body = 'Bad Request';
        $contentLength = strlen($statusMessage);
    } elseif (preg_match("#^/sum\?nums=(?:\d+,?)+#", $uri) && $method == 'GET') {
        /* request method is GET and URI is equal to '/sum/?nums=' */
        /* Process capturing parameters from URI */
        $varString = substr($uri, strpos($uri, '=') + 1);
        $vars = explode(',', $varString);
        $body = array_sum($vars);
        $statusMessage = 'OK';
        $contentLength = strlen($body);
    }

    /* Form headers for the http response */
    $headers = [
        'Date: ' . date("D, d M Y H:i:s e"),
        'Server: Apache/2.2.14 (Win32)',
        'Content-Length: ' . $contentLength,
        'Connection: Closed',
        'Content-Type: text/html; charset=utf-8',
    ];
    outputHttpResponse($statusCode, $statusMessage, $headers, $body);
}

/**
 * Outputs the passed http response to the console
 *
 * @param int $statusCode        http response status code
 * @param string $statusMessage  http response message
 * @param array $headers         http response headers' set
 * @param array $body            http response body
 */
function outputHttpResponse(int $statusCode, string $statusMessage, array $headers, array $body): void
{
    /* Build http response start line */
    $startLine = 'HTTP/1.1 ' . $statusCode . ' ' . $statusMessage;

    /* Build headers' set as a string */
    $headersString = '';
    foreach ($headers as $header) {
        $headersString .= ($header . "\n");
    }

    /* Display built data in the console */
    echo $startLine . "\n" . $headersString . "\n" . $body;
}


/**
 * Gets an array of parsed http request data from the passed string.
 *
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