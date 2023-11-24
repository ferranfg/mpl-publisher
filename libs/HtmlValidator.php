<?php

namespace MPL\Publisher;

use Exception;

class HtmlValidator
{
    /**
     * @param string $html
     *
     * @throws Exception
     *
     * @return string
     */
    public static function validate($html, $lang)
    {
        $html = "<!doctype html><html lang=\"{$lang}\"><head><title>Chapter</title></head><body>{$html}</body></html>";

        $response = wp_remote_post('https://validator.nu/?out=text&parser=html', [
            'headers' => [
                'content-type' => 'text/html; charset=utf-8',
                'content-length' => strlen($html)
            ],
            'body' => $html
        ]);

        if (is_wp_error($response))
        {
            throw new Exception($response->get_error_message());
        }

        $errors = array_key_exists('body', $response) ? $response['body'] : 'Internal Error';

        if (mpl_starts_with($errors, 'The document validates')) return false;

        return $errors;
    }
}