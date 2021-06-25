<?php

namespace app\services;

use app\exceptions\UnAllowedValueException;

/**
 * Processes user input data
 *
 * Class UserDataProcessor
 * @package app\services
 */
class UserDataProcessor
{

    /**
     * Filters input data array for not allowed symbols
     *
     * @param array $userData           Input data array
     * @return array                    Sanitized data array
     * @throws UnAllowedValueException
     */
    public static function sanitizeFormData(array $userData): array
    {
        $sanitizedData = [];
        foreach ($userData as $fieldName => $fieldValue) {
            $fieldValue = filter_var(trim($fieldValue), FILTER_SANITIZE_STRING);
            $sanitizedData[$fieldName] = $fieldValue;
            if (preg_match('~(?: <|>|alert|</?script>|<\?php|\?>)~', $fieldValue)) {
                throw new UnallowedValueException('Not allowed symbols: ' . $fieldValue);
            }
        }
        return $sanitizedData;
    }

    /**
     * Checks if the input data array fields have correct value
     *
     * @param array $userData
     * @return bool
     */
    public static function validationFormData(array $userData): bool
    {
        $authors = self::getAuthors($userData);

        if (!preg_match('~[a-zA-Zа-яА-Я0-9 Ёё.,\-+:№#]+~', $userData['title'])) {
            Flasher::set('error', 'Check \'title\' field');
            return false;
        }
        if (!preg_match('~^(?:19|20|21)[0-9]{2}$~', $_POST['year'])) {
            Flasher::set('error', 'Check \'year\' field');
            return false;
        }
        foreach ($authors as $author) {
            if (!preg_match('~[a-zA-Zа-яА-Я0-9 Ёё+.,\-:№#]*~', $author)) {
                Flasher::set('error', 'Check \'author\' fields');
                return false;
            }
        }
        if (!preg_match('~[a-zA-Zа-яА-Я0-9 Ёё+.,\-:№#]*~', $_POST['description'])) {
            Flasher::set('error', 'Check \'description\' field');
            return false;
        }
        return true;
    }

    /**
     * Gets only fields contained "author" from the upload form
     *
     * @param array $userData 'Input data array
     * @return array            Authors array
     */
    public static function getAuthors(array $userData)
    {
        $authors = [];
        foreach ($userData as $name => $value) {
            if (preg_match('~^author~', $name) && $value != '') {
                $authors[$name] = $value;
            }
        }
        return $authors;
    }

}