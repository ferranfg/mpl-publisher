<?php
/**
 * If you use mbstring.func_overload, or the server you are running on has it enabled, you are in trouble.
 * This class will help overcome that, by provide alternative functions to work around it.
 *
 * see readme.markdown for further details.
 *
 * @author A. Grandt <php@grandt.com>
 * @copyright 2014 A. Grandt
 * @license GNU LGPL 2.1
 * @version 0.20
 */
namespace com\grandt;

class BinString {
    const VERSION = 0.20;

    private $has_mbstring = FALSE;
    private $has_mb_shadow = FALSE;
    private $has_mb_mail_overload = FALSE;
    private $has_mb_string_overload = FALSE;
    private $has_mb_regex_overload = FALSE;

    /**
     * mbstring.func_overload has an undocumented feature, to retain access to the original function. 
     * As it is undocumented, it is uncertain if it'll remain, therefore it's being made an optional.
     * 
     * @var boolean 
     */
    public $USE_MB_ORIG = false;

    function __construct() {
        $this->has_mbstring = extension_loaded('mbstring') || @dl(PHP_SHLIB_PREFIX . 'mbstring.' . PHP_SHLIB_SUFFIX);
        $this->has_mb_shadow = (int) ini_get('mbstring.func_overload');
        $this->has_mb_mail_overload = $this->has_mbstring && ($this->has_mb_shadow & 1);
        $this->has_mb_string_overload = $this->has_mbstring && ($this->has_mb_shadow & 2);
        $this->has_mb_regex_overload = $this->has_mbstring && ($this->has_mb_shadow & 4);
    }

    /**
     * @link http://php.net/manual/en/function.mail.php
     */
    public function _mail($to, $subject, $message, $additional_headers = null, $additional_parameters = null) {
        if ($this->has_mb_mail_overload) {
            if ($this->USE_MB_ORIG) {
                return mb_orig_mail($to, $subject, $message, $additional_headers, $additional_parameters);
            }
            $lang = mb_language(); // get current language
            mb_language("en"); // Force encoding to iso8859-1
            $rv = mb_send_mail($to, $subject, $message, $additional_headers, $additional_parameters);
            mb_language($lang);
            return $rv;
        } else {
            return mail($to, $subject, $message, $additional_headers, $additional_parameters);
        }
    }

    /**
     * @link http://php.net/manual/en/function.strlen.php
     */
    public function _strlen($string) {
        if ($this->has_mb_string_overload) {
            if ($this->USE_MB_ORIG) {
                return mb_orig_strlen($string);
            }
            return mb_strlen($string, 'latin1');
        } else {
            return strlen($string);
        }
    }

    /**
     * @link http://php.net/manual/en/function.strpos.php
     */
    public function _strpos($haystack, $needle, $offset = 0) {
        if ($this->has_mb_string_overload) {
            if ($this->USE_MB_ORIG) {
                return mb_orig_strpos($haystack, $needle, $offset);
            }
            return mb_strpos($haystack, $needle, $offset, 'latin1');
        } else {
            return strpos($haystack, $needle, $offset);
        }
    }

    /**
     * @link http://php.net/manual/en/function.strrpos.php
     */
    public function _strrpos($haystack, $needle, $offset = 0) {
        if ($this->has_mb_string_overload) {
            if ($this->USE_MB_ORIG) {
                return mb_orig_strrpos($haystack, $needle, $offset);
            }
            return mb_strrpos($haystack, $needle, $offset, 'latin1');
        } else {
            return strrpos($haystack, $needle, $offset);
        }
    }

    /**
     * @link http://php.net/manual/en/function.substr.php
     */
    public function _substr($string, $start, $length = null) {
        if ($this->has_mb_string_overload) {
            if ($this->USE_MB_ORIG) {
                if (func_num_args() == 2) { // Kludgry hack, as PHP substr is lobotomized.
                    return mb_orig_substr($string, $start);
                }
                return mb_orig_substr($string, $start, $length);
            }
            if (func_num_args() == 2) { // Kludgry hack, as mb_substr is lobotomized, AND broken.
                return mb_substr($string, $start, mb_strlen($mbStr, 'latin1'), 'latin1');
            }
            return mb_substr($string, $start, $length, 'latin1');
        } else {
                if (func_num_args() == 2) { // Kludgry hack, as PHP substr is lobotomized.
                    return substr($string, $start);
                }
            return substr($string, $start, $length);
        }
    }

    /**
     * @link http://php.net/manual/en/function.strtolower.php
     */
    public function _strtolower($string) {
        if ($this->has_mb_string_overload) {
            if ($this->USE_MB_ORIG) {
                return mb_orig_strtolower($string);
            }
            return mb_strtolower($string, 'latin1');
        } else {
            return strtolower($string);
        }
    }

    /**
     * @link http://php.net/manual/en/function.strtoupper.php
     */
    public function _strtoupper($string) {
        if ($this->has_mb_string_overload) {
            if ($this->USE_MB_ORIG) {
                return mb_orig_strtoupper($string);
            }
            return mb_strtoupper($string, 'latin1');
        } else {
            return strtoupper($string);
        }
    }

    /**
     * @link http://php.net/manual/en/function.substr_count.php
     */
    public function _substr_count($haystack, $needle, $offset = 0, $length = null) {
        if ($this->has_mb_string_overload) {
            if ($this->USE_MB_ORIG) {
                if (func_num_args() > 3) { // Kludgry hack, as PHP substr_count is lobotomized.
                    return mb_orig_substr_count($haystack, $needle, $offset, $length);
                }
                return mb_orig_substr_count($haystack, $needle, $offset);
            }
            return mb_substr_count($haystack, $needle, 'latin1');
        } else {
            if (func_num_args() > 3) { // Kludgry hack, as PHP substr_count is lobotomized.
                return substr_count($haystack, $needle, $offset, $length);
            }
            return substr_count($haystack, $needle, $offset);
        }
    }

    /**
     * @link http://php.net/manual/en/function.ereg.php
     */
    public function _ereg($pattern, $string, array &$regs) {
        if ($this->has_mb_regex_overload) {
            if ($this->USE_MB_ORIG) {
                return mb_orig_ereg($pattern, $string, $regs);
            }
            $enc = mb_regex_encoding(); // get current encoding
            mb_regex_encoding("latin1"); // Force encoding to iso8859-1
            $rv = mb_ereg($pattern, $string, $regs);
            mb_regex_encoding($enc);
            return $rv;
        } else {
            return ereg($pattern, $string, $regs);
        }
    }

    /**
     * @link http://php.net/manual/en/function.eregi.php
     */
    public function _eregi($pattern, $string, array &$regs) {
        if ($this->has_mb_regex_overload) {
            if ($this->USE_MB_ORIG) {
                return mb_orig_eregi($pattern, $string, $regs);
            }
            $enc = mb_regex_encoding(); // get current encoding
            mb_regex_encoding("latin1"); // Force encoding to iso8859-1
            $rv = mb_eregi($pattern, $string, $regs);
            mb_regex_encoding($enc);
            return $rv;
        } else {
            return eregi($pattern, $string, $regs);
        }
    }

    /**
     * @link http://php.net/manual/en/function.ereg_replace.php
     */
    public function _ereg_replace($pattern, $replacement, $string, $mb_specific_option = "msr") {
        if ($this->has_mb_regex_overload) {
            if ($this->USE_MB_ORIG) {
                return mb_orig_ereg_replace($pattern, $replacement, $string);
            }
            $enc = mb_regex_encoding(); // get current encoding
            mb_regex_encoding("latin1"); // Force encoding to iso8859-1
            $rv = mb_ereg_replace($pattern, $replacement, $string, $mb_specific_option);
            mb_regex_encoding($enc);
            return $rv;
        } else {
            return ereg_replace($pattern, $replacement, $string);
        }
    }

    /**
     * @link http://php.net/manual/en/function.eregi_replace.php
     */
    public function _eregi_replace($pattern, $replacement, $string, $mb_specific_option = "msri") {
        if ($this->has_mb_regex_overload) {
            if ($this->USE_MB_ORIG) {
                return mb_orig_eregi_replace($pattern, $replacement, $string);
            }
            $enc = mb_regex_encoding(); // get current encoding
            mb_regex_encoding("latin1"); // Force encoding to iso8859-1
            $rv = mb_eregi_replace($pattern, $replacement, $string, $mb_specific_option);
            mb_regex_encoding($enc);
            return $rv;
        } else {
            return eregi_replace($pattern, $replacement, $string);
        }
    }

    /**
     * @link http://php.net/manual/en/function.split.php
     */
    public function _split($pattern, $string, $limit = -1) {
        if ($this->has_mb_regex_overload) {
            if ($this->USE_MB_ORIG) {
                return mb_orig_split($pattern, $string, $limit);
            }
            $enc = mb_regex_encoding(); // get current encoding
            mb_regex_encoding("latin1"); // Force encoding to iso8859-1
            $rv = mb_split($pattern, $string, $limit);
            mb_regex_encoding($enc);
            return $rv;
        } else {
            return split($pattern, $string, $limit);
        }
    }
}
