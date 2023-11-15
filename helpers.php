<?php

if ( ! function_exists('mpl_uninstall_hook'))
{
    function mpl_uninstall_hook()
    {
        delete_option(MPL_OPTION_NAME);
        delete_option(MPL_OPTION_LICENSE);
        delete_option(MPL_OPTION_MAX_POSTS);
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
        if ( ! mpl_is_premium()) return (int) MPL_MAX_POSTS;

        return (int) get_option(MPL_OPTION_MAX_POSTS, ceil(MPL_MAX_POSTS * 0x4));
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

if ( ! function_exists('mpl_all_post_types'))
{
    function mpl_all_post_types()
    {
        return array_merge(mpl_default_post_types(), mpl_other_post_types());
    }
}

if ( ! function_exists('mpl_default_post_types'))
{
    function mpl_default_post_types()
    {
        return array('post', 'page', 'mpl_chapter');
    }
}

if ( ! function_exists('mpl_other_post_types'))
{
    function mpl_other_post_types()
    {
        $all_post_types = get_post_types(array('public' => true));
        $other_post_types = array();
        $excluded_post_types = array_merge(mpl_default_post_types(), array(
            'attachment',
            'revision',
            'nav_menu_item'
        ));

        foreach ($all_post_types as $post_type )
        {
            if ( ! in_array($post_type, $excluded_post_types ))
            {
                $other_post_types[] = $post_type;
            }
        }

        return $other_post_types;
    }
}

if ( ! function_exists('mpl_post_type_icon'))
{
    function mpl_post_type_icon($post_type)
    {
        switch ($post_type):
            case 'page':        return 'dashicons dashicons-admin-page';
            case 'post':        return 'dashicons dashicons-admin-post';
            case 'mpl_chapter': return 'dashicons dashicons-book';
            case 'docs':        return 'dashicons dashicons-welcome-learn-more';
            default:            return 'dashicons dashicons-media-default';
        endswitch;
    }
}

if ( ! function_exists('mpl_post_type_label'))
{
    function mpl_post_type_label($post_type)
    {
        $post_type_object = get_post_type_object($post_type);

        if (is_object($post_type_object) and property_exists($post_type_object, 'label'))
        {
            return $post_type_object->label;
        }
        else
        {
            return $post_type;
        }
    }
}

if ( ! function_exists('mpl_starts_with'))
{
    function mpl_starts_with($haystack, $needle)
    {
        $length = strlen($needle);

        return (substr($haystack, 0, $length) === $needle);
    }
}

if ( ! function_exists('mpl_xml_entities'))
{
    function mpl_xml_entities($string)
    {
        $string = sanitize_text_field($string);

        return str_replace(
            array("<", ">", '"'),
            array("﹤", "﹥", "''"),
            $string
        );
    }
}