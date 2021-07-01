<?php

namespace app\views;

/**
 * View model represents
 * Forms data for representing in a browser
 *
 * Class View
 * @package app\views
 */
class View
{
    /**
     * @var string Path to templates
     */
    private string $templatePath;

    /**
     * View constructor.
     * @param string $templatePath
     */
    public function __construct(string $templatePath)
    {
        $this->templatePath = $templatePath;
    }

    /**
     * Forms html data format for representing in a browser
     *
     * @param $templateName
     * @param array $vars    Variables which must be passed to a client
     * @param int $httpCode
     */
    function renderHtml($templateName, $vars = [], $httpCode = 200)
    {
        /* Set response's status code */
        http_response_code($httpCode);

        /* Import vars */
        extract($vars);

        /* Include a template using buffer */
        ob_start();
        include_once $this->templatePath . '/' . $templateName;
        $buffer = ob_get_contents();
        ob_end_clean();
        echo $buffer;
    }
}