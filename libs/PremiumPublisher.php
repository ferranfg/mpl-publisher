<?php

namespace MPL\Publisher;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use GuzzleHttp\Exception\ClientException;

class PremiumPublisher
{
    protected $tempPath;

    protected $params = array();

    public function setTmpPath($tempPath)
    {
        $this->tempPath = $tempPath;
    }

    public function setEmail($email)
    {
        $this->params['email'] = $email;
    }

    public function setIdentifier($id)
    {
        $this->params['identifier'] = $id;
    }

    public function setTitle($title)
    {
        $this->params['title'] = $title;
    }

    public function setAuthor($author_name)
    {
        $this->params['author'] = $author_name;
    }

    public function setPublisher($publisher_name)
    {
        $this->params['publisher'] = $publisher_name;
    }

    public function setCoverImage($filename, $image_data)
    {
        $mime_type = mpl_mime_content_type($filename);
        $encoded64 = base64_encode($image_data);

        $this->params['cover_name'] = $filename;
        $this->params['cover_image'] = "data:{$mime_type};base64,{$encoded64}";
    }

    public function setTheme($theme, $content_css)
    {
        $this->params['theme'] = $theme;
        $this->params['css_content'] = $content_css;
    }

    public function setDescription($description)
    {
        $this->params['description'] = $description;
    }

    public function setLanguage($language)
    {
        $this->params['language'] = $language;
    }

    public function setDate($date)
    {
        $this->params['date'] = $date;
    }

    public function setRights($rights_text)
    {
        $this->params['rights'] = $rights_text;
    }

    public function request($endpoint, $filename)
    {
        $filepath = $this->tempPath . '/' . $filename;
        $authorization = is_null(mpl_premium_license()) ? mpl_premium_token() : mpl_premium_license();

        try
        {
            (new Client)->post(MPL_ENDPOINT . '/mpl-publisher/' . $endpoint, [
                RequestOptions::HEADERS => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $authorization
                ],
                RequestOptions::JSON => $this->params,
                RequestOptions::SINK => $filepath
            ]);

            header('Content-Description: File Transfer');
            header('Content-Disposition: attachment; filename=' . $filename);
            header('Content-Transfer-Encoding: binary');
            header('Content-Length: ' . filesize($filepath));
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Expires: 0');

            readfile($filepath);

            unlink($filepath);
        }
        catch (ClientException $e)
        {
            $msg = [];

            if ($e->getResponse()->getStatusCode() == 422)
            {
                $response = json_decode((string) $e->getResponse()->getBody());

                if (property_exists($response, 'message') and property_exists($response, 'errors'))
                {
                    $msg = [$response->message];

                    foreach ($response->errors as $error) array_push($msg, $error[0]);
                }
            }

            $this->throwPremiumAlert($msg);
        }
    }

    protected function throwPremiumAlert($msg = [])
    {
        if (empty($msg)) $msg = [
            __('This is a premium feature and it is not available on the free version.', 'publisher'),
            __('Please, visit our homepage and get access to this and more features.', 'publisher')
        ];

        throw new Exception("⚠️ " . implode(" ", $msg));
    }
}