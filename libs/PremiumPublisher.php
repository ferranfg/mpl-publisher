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

    public function getToken()
    {
        if (mpl_is_premium())
        {
            $options = json_decode(file_get_contents(MPL_BASEPATH . '/mpl-publisher.json'), true);

            if (array_key_exists('token', $options)) return $options['token'];
        }

        return null;
    }

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

    public function setAuthor($authorName)
    {
        $this->params['author'] = $authorName;
    }

    public function setPublisher($publisherName)
    {
        $this->params['publisher'] = $publisherName;
    }

    public function setCoverImage($fileName, $imageData)
    {
        $mime_type = mpl_mime_content_type($fileName);
        $encoded64 = base64_encode($imageData);

        $this->params['cover_name'] = $fileName;
        $this->params['cover_image'] = "data:{$mime_type};base64,{$encoded64}";
    }

    public function setTheme($theme, $contentCSS)
    {
        $this->params['theme'] = $theme;
        $this->params['css_content'] = $contentCSS;
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

    public function setRights($rightsText)
    {
        $this->params['rights'] = $rightsText;
    }

    public function request($endpoint, $filename)
    {
        $filepath = $this->tempPath . '/' . $filename;

        try
        {
            (new Client)->post(MPL_ENDPOINT . '/mpl-publisher/' . $endpoint, [
                RequestOptions::HEADERS => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->getToken()
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
            $msg = [
                "This is a premium feature and it is not available on the free version.",
                "Please, visit our homepage and get access to this and more features"
            ];

            if ($e->getResponse()->getStatusCode() == 422)
            {
                $response = json_decode((string) $e->getResponse()->getBody());

                if (property_exists($response, 'message') and property_exists($response, 'errors'))
                {
                    $msg = [$response->message];

                    foreach ($response->errors as $error) array_push($msg, $error[0]);
                }
            }

            throw new Exception("⚠️ " . implode(" ", $msg));
        }
    }
}