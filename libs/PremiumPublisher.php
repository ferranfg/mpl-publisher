<?php

namespace MPL\Publisher;

class PremiumPublisher
{
    public function getToken()
    {
        if (file_exists(MPL_BASEPATH . '/mpl-publisher.json'))
        {
            $options = json_decode(file_get_contents(MPL_BASEPATH . '/mpl-publisher.json'), true);

            if (array_key_exists('token', $options)) return $options['token'];
        }

        return null;
    }
}