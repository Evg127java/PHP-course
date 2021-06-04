<?php

/* Get input data */
$contents = readHttpLikeInput();

$http = parseTcpStringAsHttpRequest($contents);
echo(json_encode($http, JSON_PRETTY_PRINT));

/**
 * The service function from the GitHub for input data reading
 * @return string
 */
function readHttpLikeInput(): string
{
    $f = fopen('php://stdin', 'r');
    $store = "";
    $toread = 0;
    while ($line = fgets($f)) {
        $store .= preg_replace("/\r/", "", $line);
        if (preg_match('/Content-Length: (\d+)/', $line, $m))
            $toread = $m[1] * 1;
        if ($line == "\r\n")
            break;
    }
    if ($toread > 0)
        $store .= fread($f, $toread);
    return $store;
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

    /* Split the main part from above into the start line and headers */
    list($startLine, $headers) = preg_split("#\R#", $mainPart, 2);

    /* Split the start line into the method, URI and protocol parts */
    list($method, $uri,) = preg_split("#\s#", $startLine);

    /* Compose the request array of required format */
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