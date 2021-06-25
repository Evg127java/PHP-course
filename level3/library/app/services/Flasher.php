<?php


namespace app\services;


/**
 * Represents messages on views
 *
 * Class Flasher
 * @package app\services
 */
class Flasher
{
    /**
     * Sets the passed text for the passed session key of the flasher
     *
     * @param $key       'Session key name
     * @param $message   'Message text
     */
    public static function set(string $key, string $message): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['flash'][$key] = $message;
    }

    /**
     * Gets the text of the passed session key of the flasher
     *
     * @param $key     'Session key name
     * @return string  'Message text
     */
    public static function get(string $key): string
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($_SESSION['flash'][$key])) {
            return '';
        }
        $message = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return $message;
    }
}