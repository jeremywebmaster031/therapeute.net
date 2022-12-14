<?php
defined('ABSPATH') || die('Cheatin\' uh?');

class SQ_Classes_Helpers_Sanitize
{

    /**
     * Escape the parameter for html
     *
     * @param  $key
     * @return string|void
     */
    public static function escapeGetValue($key)
    {
        return esc_attr(SQ_Classes_Helpers_Tools::getValue($key));
    }

    /**
     * Clear the title string
     *
     * @param  $title
     * @return mixed|null|string|string[]
     */
    public static function clearTitle($title)
    {
        if ($title <> '') {

            $seps = json_decode(SQ_ALL_SEP, true);
            $title = str_replace(
                array_values($seps),
                array_map(function($value){return  html_entity_decode($value);},$seps),
                $title);

            if (function_exists('preg_replace')) {
                $search = array(
                    "/[\n\r]/si",
                    "/[\n]/si",
                    "/&nbsp;/si",
                    "/\[[^\]]+\]/si",
                    "/\s{2,}/",
                );
                $title = preg_replace($search, " ", $title);
            }

            $title = SQ_Classes_Helpers_Sanitize::i18n(trim(esc_html(ent2ncr(strip_tags($title)))));

        }

        return $title;
    }

    /**
     * Clear description
     *
     * @param  $description
     * @return null|string|string[]
     */
    public static function clearDescription($description)
    {
        if ($description <> '') {

            $seps = json_decode(SQ_ALL_SEP, true);
            $description = str_replace(
                array_values($seps),
                array_map(function($value){return  html_entity_decode($value);},$seps),
                $description);

            if (function_exists('preg_replace')) {
                $search = array(
                    "'<!--(.*?)-->'is",
                    "'<script[^>]*?>.*?<\/script>'si", // strip out javascript
                    "'<style[^>]*?>.*?<\/style>'si", // strip out styles
                    "'<form.*?<\/form>'si",
                    "'<iframe.*?<\/iframe>'si",
                    "'&lt;!--(.*?)--&gt;'is",
                    "'&lt;script&gt;.*?&lt;\/script&gt;'si", // strip out javascript
                    "'&lt;style&gt;.*?&lt;\/style&gt;'si", // strip out styles
                );
                $description = preg_replace($search, "", $description);
                $search = array(
                    "/[\n\r]/si",
                    "/[\n]/si",
                    "/&nbsp;/si",
                    "/\[[^\]]+\]/si",
                    "/\s{2,}/",
                );
                $description = preg_replace($search, " ", $description);
            }

            $description = SQ_Classes_Helpers_Sanitize::i18n(trim(esc_html(ent2ncr(strip_tags($description)))));
        }

        return $description;
    }

    /**
     * Clear the keywords
     *
     * @param  $keywords
     * @return mixed|null|string|string[]
     */
    public static function clearKeywords($keywords)
    {
        return self::clearTitle($keywords);
    }



    /**
     * Escape the keyword for tags and urls
     *
     * @param  $keyword
     * @param  string $for
     * @return string|void
     */
    public static function escapeKeyword($keyword, $for = 'all')
    {
        switch ($for){
        case 'url':
            $keyword = urlencode(esc_attr($keyword));
            break;
        case 'attr':
            $keyword = htmlspecialchars(str_replace('"', '\"', $keyword));
            break;
        default:
            $keyword = esc_attr($keyword);
        }
        return $keyword;
    }

    /**
     * Truncate the text
     *
     * @param  $text
     * @param  int $min
     * @param  int $max
     * @return bool|mixed|null|string|string[]
     */
    public static function truncate($text, $min = 100, $max = 110)
    {
        //make sure they are values
        $max = (int)$max;
        $min = (int)$min;

        if ($max > 0 && $text <> '' && strlen($text) > $max) {
            if (function_exists('strip_tags')) {
                $text = strip_tags($text);
            }

            $text = str_replace(']]>', ']]&gt;', $text);
            $text = preg_replace('/\[(.+?)\]/is', '', $text);

            if ($max < strlen($text)) {
                while ($text[$max] != ' ' && $max > $min) {
                    $max--;
                }
            }

            //Use internation truncate
            if (function_exists('mb_substr')) {
                $text = mb_substr($text, 0, $max);
            } else {
                $text = substr($text, 0, $max);
            }

            return trim(stripcslashes($text));
        }

        return $text;
    }

    /**
     * Replace language-specific characters by ASCII-equivalents.
     *
     * @param  string $s
     * @return string
     */
    public static function normalizeChars($s)
    {
        $replace = array(
            '??'=>'-', '??'=>'-', '??'=>'-', '??'=>'-',
            '??'=>'A', '??'=>'A', '??'=>'A', '??'=>'A', '??'=>'A', '??'=>'A', '??'=>'A', '??'=>'A', '??'=>'Ae',
            '??'=>'B',
            '??'=>'C', '??'=>'C', '??'=>'C',
            '??'=>'E', '??'=>'E', '??'=>'E', '??'=>'E', '??'=>'E',
            '??'=>'G',
            '??'=>'I', '??'=>'I', '??'=>'I', '??'=>'I', '??'=>'I',
            '??'=>'L',
            '??'=>'N', '??'=>'N',
            '??'=>'O', '??'=>'O', '??'=>'O', '??'=>'O', '??'=>'O', '??'=>'Oe',
            '??'=>'S', '??'=>'S', '??'=>'S', '??'=>'S',
            '??'=>'T',
            '??'=>'U', '??'=>'U', '??'=>'U', '??'=>'Ue',
            '??'=>'Y',
            '??'=>'Z', '??'=>'Z', '??'=>'Z',
            '??'=>'a', '??'=>'a', '??'=>'a', '??'=>'a', '??'=>'a', '??'=>'a', '??'=>'a', '??'=>'a', '??'=>'a', '??'=>'a', '??'=>'a', '??'=>'a', '??'=>'a', '??'=>'a', '??'=>'a', '??'=>'a', '??'=>'ae', '??'=>'ae', '??'=>'ae', '??'=>'ae',
            '??'=>'b', '??'=>'b', '??'=>'b', '??'=>'b',
            '??'=>'c', '??'=>'c', '??'=>'c', '??'=>'c', '??'=>'c', '??'=>'c', '??'=>'c', '??'=>'c', '??'=>'c', '??'=>'c', '??'=>'c', '??'=>'ch', '??'=>'ch',
            '??'=>'d', '??'=>'d', '??'=>'d', '??'=>'d', '??'=>'d', '??'=>'d', '??'=>'D', '??'=>'d',
            '??'=>'e', '??'=>'e', '??'=>'e', '??'=>'e', '??'=>'e', '??'=>'e', '??'=>'e', '??'=>'e', '??'=>'e', '??'=>'e', '??'=>'e', '??'=>'e', '??'=>'e', '??'=>'e', '??'=>'e', '??'=>'e', '??'=>'e', '??'=>'e', '??'=>'e', '??'=>'e',
            '??'=>'f', '??'=>'f', '??'=>'f',
            '??'=>'g', '??'=>'g', '??'=>'g', '??'=>'g', '??'=>'g', '??'=>'g', '??'=>'g', '??'=>'g', '??'=>'g', '??'=>'g', '??'=>'g', '??'=>'g',
            '??'=>'h', '??'=>'h', '??'=>'h', '??'=>'h', '??'=>'h', '??'=>'h', '??'=>'h', '??'=>'h',
            '??'=>'i', '??'=>'i', '??'=>'i', '??'=>'i', '??'=>'i', '??'=>'i', '??'=>'i', '??'=>'i', '??'=>'i', '??'=>'i', '??'=>'i', '??'=>'i', '??'=>'i', '??'=>'i', '??'=>'i', '??'=>'i', '??'=>'i', '??'=>'i', '??'=>'i', '??'=>'i', '??'=>'i', '??'=>'i', '??'=>'ij', '??'=>'ij',
            '??'=>'j', '??'=>'j', '??'=>'j', '??'=>'j', '??'=>'ja', '??'=>'ja', '??'=>'je', '??'=>'je', '??'=>'jo', '??'=>'jo', '??'=>'ju', '??'=>'ju',
            '??'=>'k', '??'=>'k', '??'=>'k', '??'=>'k', '??'=>'k', '??'=>'k', '??'=>'k',
            '??'=>'l', '??'=>'l', '??'=>'l', '??'=>'l', '??'=>'l', '??'=>'l', '??'=>'l', '??'=>'l', '??'=>'l', '??'=>'l', '??'=>'l', '??'=>'l',
            '??'=>'m', '??'=>'m', '??'=>'m', '??'=>'m',
            '??'=>'n', '??'=>'n', '??'=>'n', '??'=>'n', '??'=>'n', '??'=>'n', '??'=>'n', '??'=>'n', '??'=>'n', '??'=>'n', '??'=>'n', '??'=>'n', '??'=>'n',
            '??'=>'o', '??'=>'o', '??'=>'o', '??'=>'o', '??'=>'o', '??'=>'o', '??'=>'o', '??'=>'o', '??'=>'o', '??'=>'o', '??'=>'o', '??'=>'o', '??'=>'o', '??'=>'o', '??'=>'o', '??'=>'o', '??'=>'o', '??'=>'o', '??'=>'o', '??'=>'oe', '??'=>'oe', '??'=>'oe',
            '??'=>'p', '??'=>'p', '??'=>'p', '??'=>'p',
            '??'=>'q',
            '??'=>'r', '??'=>'r', '??'=>'r', '??'=>'r', '??'=>'r', '??'=>'r', '??'=>'r', '??'=>'r', '??'=>'r',
            '??'=>'s', '??'=>'s', '??'=>'s', '??'=>'s', '??'=>'s', '??'=>'s', '??'=>'s', '??'=>'s', '??'=>'s', '??'=>'sch', '??'=>'sch', '??'=>'sh', '??'=>'sh', '??'=>'ss',
            '??'=>'t', '??'=>'t', '??'=>'t', '??'=>'t', '??'=>'t', '??'=>'t', '??'=>'t', '??'=>'t', '??'=>'t', '??'=>'t', '??'=>'t', '???'=>'tm',
            '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'ue',
            '??'=>'v', '??'=>'v', '??'=>'v',
            '??'=>'w', '??'=>'w', '??'=>'w',
            '??'=>'y', '??'=>'y', '??'=>'y', '??'=>'y', '??'=>'y', '??'=>'y',
            '??'=>'y', '??'=>'z', '??'=>'z', '??'=>'z', '??'=>'z', '??'=>'z', '??'=>'z', '??'=>'z', '??'=>'zh', '??'=>'zh'
        );
        return strtr($s, $replace);
    }

    public static function checkTelephone($phone)
    {
        if ($phone <> '') {
            $phone = '+' . ltrim($phone, '+');
        }

        return $phone;
    }

    /**
     * Check the google code saved at settings
     *
     * @param  string $code
     * @return string
     */
    public static function checkGoogleWTCode($code)
    {
        if ($code <> '') {
            $code = stripslashes($code);
            if (strpos($code, 'content') !== false) {
                preg_match('/content\\s*=\\s*[\"]([^\"]+)[\"]/i', $code, $result);
                if (isset($result[1]) && !empty($result[1])) $code = $result[1];
            }
            if (strpos($code, '"') !== false) {
                preg_match('/[\"]([^\"]+)[\"]/i', $code, $result);
                if (isset($result[1]) && !empty($result[1])) $code = $result[1];
            }

            if ($code == '') SQ_Classes_Error::setError(esc_html__("The code for Google Webmaster Tool is incorrect.", 'squirrly-seo'));
        }
        return $code;
    }

    /**
     * Check the google code saved at settings
     *
     * @param  string $code
     * @return string
     */
    public static function checkGoogleAnalyticsCode($code)
    {
        if ($code <> '') {
            $code = stripslashes($code);

            if (strpos($code, 'GoogleAnalyticsObject') !== false) {
                preg_match('/ga\(\'create\',[^\'"]*[\'"]([^\'"]+)[\'"],/i', $code, $result);
                if (isset($result[1]) && !empty($result[1])) $code = $result[1];
            }

            if (strpos($code, '"') !== false) {
                preg_match('/[\"]([^\"]+)[\"]/i', $code, $result);
                if (isset($result[1]) && !empty($result[1])) $code = $result[1];
            }

            if (strpos($code, 'UA-') === false && strpos($code, 'G-') === false) {
                $code = '';
                SQ_Classes_Error::setError(esc_html__("The code for Google Analytics is incorrect.", 'squirrly-seo'));
            }
        }
        return trim($code);
    }

    /**
     * Check the Facebook code saved at settings
     *
     * @param  string $code
     * @return string
     */
    public static function checkFacebookAdminCode($code)
    {
        if ($code <> '') {
            $code = trim($code);

            if (strpos($code, 'facebook.com/') !== false) {
                preg_match('/facebook.com\/([^\/]+)/i', $code, $result);
                if (isset($result[1]) && !empty($result[1])) {
                    if (is_string($result[1])) {
                        $response = SQ_Classes_RemoteController::getFacebookApi(array('profile' => $result[1]));
                        if (!is_wp_error($response) && isset($response->code)) {
                            return $response->code;
                        }
                    } elseif (is_numeric($result[1])) {
                        return $result[1];
                    }
                }
            } elseif ($code <> (int)$code) {
                $response = SQ_Classes_RemoteController::getFacebookApi(array('profile' => $code));
                if (!is_wp_error($response) && isset($response->code)) {
                    return $response->code;
                }
            } else {
                return $code;
            }

            SQ_Classes_Error::setError(esc_html__("The code for Facebook is incorrect.", 'squirrly-seo'));

        }
        return false;
    }

    /**
     * Check the Pinterest code saved at settings
     *
     * @param  string $code
     * @return string
     */
    public static function checkPinterestCode($code)
    {
        if ($code <> '') {
            $code = stripslashes($code);

            if (strpos($code, 'content') !== false) {
                preg_match('/content\\s*=\\s*[\"]([^\"]+)[\"]/i', $code, $result);
                if (isset($result[1]) && !empty($result[1])) $code = $result[1];
            }

            if (strpos($code, '"') !== false) {
                preg_match('/[\"]([^\"]+)[\"]/i', $code, $result);
                if (isset($result[1]) && !empty($result[1])) $code = $result[1];
            }

            if ($code == '') SQ_Classes_Error::setError(esc_html__("The code for Pinterest is incorrect.", 'squirrly-seo'));
        }
        return $code;
    }

    /**
     * Check the Bing code saved at settings
     *
     * @return string
     */
    public static function checkBingWTCode($code)
    {
        if ($code <> '') {
            $code = stripslashes($code);


            if (strpos($code, 'content') !== false) {
                preg_match('/content\\s*=\\s*[\"]([^\"]+)[\"]/i', $code, $result);
                if (isset($result[1]) && !empty($result[1])) $code = $result[1];
            }

            if (strpos($code, '"') !== false) {
                preg_match('/[\"]([^\"]+)[\"]/i', $code, $result);
                if (isset($result[1]) && !empty($result[1])) $code = $result[1];
            }

            if ($code == '') SQ_Classes_Error::setError(esc_html__("The code for Bing is incorrect.", 'squirrly-seo'));
        }
        return $code;
    }

    /**
     * Check the Baidu code saved at settings
     *
     * @return string
     */
    public static function checkBaiduWTCode($code)
    {
        if ($code <> '') {
            $code = stripslashes($code);


            if (strpos($code, 'content') !== false) {
                preg_match('/content\\s*=\\s*[\"]([^\"]+)[\"]/i', $code, $result);
                if (isset($result[1]) && !empty($result[1])) $code = $result[1];
            }

            if (strpos($code, '"') !== false) {
                preg_match('/[\"]([^\"]+)[\"]/i', $code, $result);
                if (isset($result[1]) && !empty($result[1])) $code = $result[1];
            }

            if ($code == '') SQ_Classes_Error::setError(esc_html__("The code for Baidu is incorrect.", 'squirrly-seo'));
        }
        return $code;
    }

    /**
     * Check the Yandex code saved at settings
     *
     * @return string
     */
    public static function checkYandexWTCode($code)
    {
        if ($code <> '') {
            $code = stripslashes($code);


            if (strpos($code, 'content') !== false) {
                preg_match('/content\\s*=\\s*[\"]([^\"]+)[\"]/i', $code, $result);
                if (isset($result[1]) && !empty($result[1])) $code = $result[1];
            }

            if (strpos($code, '"') !== false) {
                preg_match('/[\"]([^\"]+)[\"]/i', $code, $result);
                if (isset($result[1]) && !empty($result[1])) $code = $result[1];
            }

            if ($code == '') SQ_Classes_Error::setError(esc_html__("The code for Yandex is incorrect.", 'squirrly-seo'));
        }
        return $code;
    }

    /**
     * Check the twitter account
     *
     * @param  string $account
     * @return string
     */
    public static function checkTwitterAccount($account)
    {
        if ($account <> '' && strpos($account, '//') === false) {
            $account = 'https://twitter.com/' . $account;
        }

        return $account;
    }

    /**
     * Check the twitter account
     *
     * @param  string $account
     * @return string
     */
    public static function checkTwitterAccountName($account)
    {
        if ($account <> '' && strpos($account, '//') !== false) {
            $account = parse_url($account, PHP_URL_PATH);
            if ($account <> '') {
                $account = str_replace('/', '', $account);
            }
            if (strpos($account, '@') == false) {
                $account = '@' . $account;
            }
        }

        return $account;
    }

    /**
     * Check the google + account
     *
     * @param  string $account
     * @return string
     */
    public static function checkGoogleAccount($account)
    {
        if ($account <> '' && strpos($account, '//') === false) {
            $account = 'https://plus.google.com/' . $account;
        }
        return str_replace(" ", "+", $account);
    }

    /**
     * Check the google + account
     *
     * @param  string $account
     * @return string
     */
    public static function checkLinkeinAccount($account)
    {
        if ($account <> '' && strpos($account, '//') === false) {
            $account = 'https://www.linkedin.com/in/' . $account;
        }
        return $account;
    }

    /**
     * Check the facebook account
     *
     * @param  string $account
     * @return string
     */
    public static function checkFacebookAccount($account)
    {
        if ($account <> '' && strpos($account, '//') === false) {
            $account = 'https://www.facebook.com/' . $account;
        }
        return $account;
    }

    /**
     * Check the Pinterest account
     *
     * @param  $account
     * @return string
     */
    public static function checkPinterestAccount($account)
    {
        if ($account <> '' && strpos($account, '//') === false) {
            $account = 'https://www.pinterest.com/' . $account;
        }
        return $account;
    }

    /**
     * Check the Instagram
     *
     * @param  $account
     * @return string
     */
    public static function checkInstagramAccount($account)
    {
        if ($account <> '' && strpos($account, '//') === false) {
            $account = 'https://www.instagram.com/' . $account;
        }
        return $account;
    }

    /**
     * Check the Youtube account
     *
     * @param  $account
     * @return string
     */
    public static function checkYoutubeAccount($account)
    {
        if ($account <> '' && strpos($account, '//') === false) {
            if (strpos($account, 'user/') === false && strpos($account, 'channel/') === false) {
                $account = 'https://www.youtube.com/channel/' . $account;
            }
        }
        return $account;
    }

    /**
     * Check the Facebook Pixel code
     *
     * @return string
     */
    public static function checkFacebookPixel($code)
    {
        if ($code <> '') {
            if ((int)$code == 0) {
                SQ_Classes_Error::setError(esc_html__("The code for Facebook Pixel must only contain numbers.", 'squirrly-seo'));
                $code = '';
            }
        }
        return $code;
    }

    /**
     * Check the Facebook App code
     *
     * @return string
     */
    public static function checkFacebookApp($code)
    {
        if ($code <> '') {
            if ((int)$code == 0) {
                SQ_Classes_Error::setError(esc_html__("The code for Facebook App must only contain numbers.", 'squirrly-seo'));
                $code = '';
            }
        }
        return $code;
    }

    /**
     * Support for i18n with wpml, polyglot or qtrans
     *
     * @param  string $in
     * @return string $in localized
     */
    public static function i18n($in)
    {
        if (function_exists('langswitch_filter_langs_with_message')) {
            $in = langswitch_filter_langs_with_message($in);
        }
        if (function_exists('polyglot_filter')) {
            $in = polyglot_filter($in);
        }
        if (function_exists('qtrans_useCurrentLanguageIfNotFoundUseDefaultLanguage')) {
            $in = qtrans_useCurrentLanguageIfNotFoundUseDefaultLanguage($in);
        }
        $in = apply_filters('localization', $in);
        return $in;
    }
}
