<?php


namespace app\views;


class View
{

    /**
     * @var string
     */
    private string $templatePath;

    public function __construct(string $templatePath)
    {
        $this->templatePath = $templatePath;
    }

    function renderHtml($templateName, $vars = [], $httpCode = 200)
    {
        http_response_code($httpCode);
        extract($vars);
        ob_start();
        include_once $this->templatePath . '/' . $templateName;
        $buffer = ob_get_contents();
        ob_end_clean();
        echo $buffer;
    }
}