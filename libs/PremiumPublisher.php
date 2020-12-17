<?php

namespace MPL\Publisher;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use GuzzleHttp\Exception\ClientException;

class PremiumPublisher
{
    private $email;

    private $tempPath;

    public function getToken()
    {
        if (mpl_is_premium())
        {
            $options = json_decode(file_get_contents(MPL_BASEPATH . '/mpl-publisher.json'), true);

            if (array_key_exists('token', $options)) return $options['token'];
        }

        return null;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setTmpPath($tempPath)
    {
        $this->tempPath = $tempPath;
    }

    public function request($endpoint, $filename, $params = [])
    {
        $filepath = $this->tempPath . '/' . $filename;

        $params = array_merge($params, [
            'email' => $this->email
        ]);

        try
        {
            (new Client)->post(MPL_ENDPOINT . '/mpl-publisher/' . $endpoint, [
                RequestOptions::HEADERS => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->getToken()
                ],
                RequestOptions::JSON => $params,
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

            throw new Exception(implode(" ", $msg));
        }
    }
}