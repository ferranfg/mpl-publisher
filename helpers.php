<?php

if ( ! function_exists('mpl_uninstall_hook'))
{
    function mpl_uninstall_hook()
    {
        delete_option(MPL_OPTION_NAME);
        delete_option(MPL_OPTION_LICENSE);
    }
}

if ( ! function_exists('mpl_admin_url'))
{
    function mpl_admin_url($params = array())
    {
        $params = array_merge($params, [
            'page' => 'publisher'
        ]);

        return admin_url('admin.php?' . http_build_query($params));
    }
}

if ( ! function_exists('mpl_is_premium'))
{
    /**
     * https://wordpress.mpl-publisher.com :)
     *
     * We validate the license key on the backend
     */
    function mpl_is_premium()
    {
        return ! is_null(mpl_premium_license()) or ! is_null(mpl_premium_token());
    }
}

if ( ! function_exists('mpl_premium_license'))
{
    function mpl_premium_license()
    {
        $license = get_option(MPL_OPTION_LICENSE, null);

        if ( ! is_null($license) and preg_match('/[A-Z0-9]{8}-[A-Z0-9]{8}-[A-Z0-9]{8}-[A-Z0-9]{8}/', $license))
        {
            return $license;
        }

        return null;
    }
}

if ( ! function_exists('mpl_premium_token'))
{
    function mpl_premium_token()
    {
        if (file_exists(MPL_BASEPATH . '/mpl-publisher.json'))
        {
            $content = json_decode(file_get_contents(MPL_BASEPATH . '/mpl-publisher.json'), true);

            if (array_key_exists('token', $content)) return $content['token'];
        }

        return null;
    }
}

if ( ! function_exists('mpl_max_posts'))
{
    function mpl_max_posts()
    {
        return mpl_is_premium() ? (int) ceil(MPL_MAX_POSTS * 0x5) : MPL_MAX_POSTS;
    }
}

if ( ! function_exists('mpl_mime_content_type'))
{
    function mpl_mime_content_type($filename)
    {
        $mime_types = array(
            'txt' => 'text/plain',
            'htm' => 'text/html',
            'html' => 'text/html',
            'php' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'swf' => 'application/x-shockwave-flash',
            'flv' => 'video/x-flv',

            // images
            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml',

            // archives
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'exe' => 'application/x-msdownload',
            'msi' => 'application/x-msdownload',
            'cab' => 'application/vnd.ms-cab-compressed',

            // audio/video
            'mp3' => 'audio/mpeg',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',

            // adobe
            'pdf' => 'application/pdf',
            'psd' => 'image/vnd.adobe.photoshop',
            'ai' => 'application/postscript',
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',

            // ms office
            'doc' => 'application/msword',
            'rtf' => 'application/rtf',
            'xls' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint',

            // open office
            'odt' => 'application/vnd.oasis.opendocument.text',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        );

        $parts = explode('.', $filename);
        // Only variables should be passed by reference
        $ext = strtolower(array_pop($parts));

        if (array_key_exists($ext, $mime_types))
        {
            return $mime_types[$ext];
        }
        elseif (function_exists('finfo_open'))
        {
            $finfo = finfo_open(FILEINFO_MIME);
            $mimetype = finfo_file($finfo, $filename);

            finfo_close($finfo);

            return $mimetype;
        }
        else
        {
            return 'application/octet-stream';
        }
    }
}

if ( ! function_exists('mpl_sanitize_array'))
{
    function mpl_sanitize_array($array)
    {
        if ( ! is_array($array)) return $array;

        foreach ($array as $key => $item)
        {
            if (is_string($item))
            {
                $array[$key] = sanitize_text_field($item);
            }
            else
            {
                $array[$key] = mpl_sanitize_array($item);
            }
        }

        return $array;
    }
}