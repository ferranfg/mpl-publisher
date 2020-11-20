<?php

namespace MPL\Publisher;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use GuzzleHttp\Exception\ClientException;

class AudiobookPublisher extends PremiumPublisher implements IPublisher
{
    private $email;

    private $title;

    private $language;

    private $content;

    private $tempPath;

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setTmpPath($tempPath)
    {
        $this->tempPath = $tempPath;
    }

    public function setIdentifier($id)
    {
        //
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function setAuthor($authorName)
    {
        //
    }

    public function setPublisher($publisherName)
    {
        //
    }

    public function setCoverImage($fileName, $imageData)
    {
        //
    }

    public function setTheme($theme, $contentCSS)
    {
        //
    }

    public function setDescription($description)
    {
        //
    }

    public function setLanguage($language)
    {
        $this->language = $language;
    }

    public function setDate($date)
    {
        //
    }

    public function setRights($rightsText)
    {
        //
    }

    public function addChapter($id, $title, $content)
    {
        $this->content .= "{$id}. {$title}. {$content}. ";
    }

    public function send($filename)
    {
        try
        {
            $filepath = $this->tempPath . '/' . $filename . '.mp3';

            (new Client)->post(MPL_ENDPOINT . '/mpl-publisher/audiobook', [
                RequestOptions::HEADERS => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->getToken()
                ],
                RequestOptions::JSON => [
                    'email' => $this->email,
                    'language' => $this->language,
                    'title' => $this->title,
                    'content' => $this->content
                ],
                RequestOptions::SINK => $filepath
            ]);

            header('Content-Description: File Transfer');
            header('Content-Disposition: attachment; filename=' . $filename . '.mp3');
            header('Content-Type: audio/mpeg');
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

            throw new Exception(implode(" ", $msg));
        }
    }

}