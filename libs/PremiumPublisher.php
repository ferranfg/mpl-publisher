<?php

namespace MPL\Publisher;

use Exception;

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

    public function setSubtitle($subtitle)
    {
        if ($subtitle == '') return;

        $this->setTitle($this->params['title'] . ': ' . $subtitle);
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

    public function addChapter($id, $title, $content, $image = null)
    {
        if ( ! array_key_exists('chapters', $this->params)) $this->params['chapters'] = array();

        array_push($this->params['chapters'], array(
            'id' => $id,
            'title' => $title,
            'content' => $content,
            'image' => $image,
        ));
    }

    public function addFile($id, $name, $data, $mime_type)
    {
        // NOTHING TO IMPLEMENT
    }

    public function request($endpoint, $filename)
    {
        $filepath = $this->tempPath . '/' . $filename;
        $authorization = is_null(mpl_premium_license()) ? mpl_premium_token() : mpl_premium_license();

        $request = wp_remote_post(MPL_ENDPOINT . '/mpl-publisher/' . $endpoint, [
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $authorization
            ],
            'body' => json_encode($this->params),
            'data_format' => 'body',
            'stream' => true,
            'filename' => $filepath
        ]);

        if (is_wp_error($request)) $this->throwAlert([$request->get_error_message()]);

        if ($request['http_response']->get_status() == 200)
        {
            header('Content-Description: File Transfer');
            header('Content-Disposition: attachment; filename=' . $filename);
            header('Content-Transfer-Encoding: binary');
            header('Content-Length: ' . filesize($filepath));
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Expires: 0');

            readfile($filepath);

            unlink($filepath);
        }
        else if ($request['http_response']->get_status() == 201)
        {
            $response = json_decode((string) file_get_contents($filepath));

            unlink($filepath);

            throw new Exception("✅ " . implode(" ", [
                __('Your book is available at the following link:', 'publisher'),
                $response->canonical_url
            ]));
        }
        else
        {
            $msg = [];

            if ($request['http_response']->get_status() == 422)
            {
                $response = json_decode((string) file_get_contents($filepath));

                if (property_exists($response, 'message') and property_exists($response, 'errors'))
                {
                    $msg = [$response->message];

                    foreach ($response->errors as $error) array_push($msg, $error[0]);
                }
            }

            $this->throwAlert($msg);
        }
    }

    protected function throwAlert($msg = [])
    {
        if (empty($msg)) $msg = [
            __('This is a premium feature and it is not available on the free version.', 'publisher'),
            __('Please, visit our homepage and get access to this and more features.', 'publisher')
        ];

        throw new Exception("⚠️ " . implode(" ", $msg));
    }
}