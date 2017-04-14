<?php
use Illuminate\Support\Str;

/**
 * Clean string from special characters and multiple dots, spaces
 * @author Anton Iskandiarov
 * @version 0.1
 */
function clean_string($string,$replacer='') {
   $string = Str::lower($string);
   $string = str_replace(' ', $replacer, $string); // Replaces all spaces with hyphens.
   $string = preg_replace('/\.\.\.+/', '.', $string); //replace multiple dots with single
   return preg_replace('/[^A-Za-z0-9\-.]/', '', $string); // Removes special chars.
}

/**
 * Sanitize string
 * @author http://stackoverflow.com/a/2727693
 */
function sanitize_string($string) {
    return preg_replace(array('/\s/', '/\.[\.]+/', '/[^\w_\.\-]/'), array('_', '.', ''), $string);
}

/**
 * Cutting HTML strings without breaking HTML tags
 * @author code ex machina http://stackoverflow.com/questions/2398725/using-php-substr-and-strip-tags-while-retaining-formatting-and-without-break
 * @version
 */
function html_cut($text, $max_length)
{
    $tags   = array();
    $result = "";

    $is_open   = false;
    $grab_open = false;
    $is_close  = false;
    $in_double_quotes = false;
    $in_single_quotes = false;
    $tag = "";

    $i = 0;
    $stripped = 0;

    $stripped_text = strip_tags($text);

    while ($i < strlen($text) && $stripped < strlen($stripped_text) && $stripped < $max_length)
    {
        $symbol  = $text{$i};
        $result .= $symbol;

        switch ($symbol)
        {
           case '<':
                $is_open   = true;
                $grab_open = true;
                break;

           case '"':
               if ($in_double_quotes)
                   $in_double_quotes = false;
               else
                   $in_double_quotes = true;

            break;

            case "'":
              if ($in_single_quotes)
                  $in_single_quotes = false;
              else
                  $in_single_quotes = true;

            break;

            case '/':
                if ($is_open && !$in_double_quotes && !$in_single_quotes)
                {
                    $is_close  = true;
                    $is_open   = false;
                    $grab_open = false;
                }

                break;

            case ' ':
                if ($is_open)
                    $grab_open = false;
                else
                    $stripped++;

                break;

            case '>':
                if ($is_open)
                {
                    $is_open   = false;
                    $grab_open = false;
                    array_push($tags, $tag);
                    $tag = "";
                }
                else if ($is_close)
                {
                    $is_close = false;
                    array_pop($tags);
                    $tag = "";
                }

                break;

            default:
                if ($grab_open || $is_close)
                    $tag .= $symbol;

                if (!$is_open && !$is_close)
                    $stripped++;
        }

        $i++;
    }

    while ($tags)
        $result .= "</".array_pop($tags).">";

    return $result;
}

/**
 * Clever str limit
 * @author http://www.pjgalbraith.com/truncating-text-html-with-php/
 */
function clever_strlimit($text, $limit, $postfix = '...', $hardcoded_postfix = '') {
    $text = preg_replace('!\s+!', ' ', strip_tags($text)); // strip tags and remove multiple spaces
    if( strlen($text) > $limit ) {
        $hardcoded_postfix = '';
        $endpos = strpos(str_replace(array("\r\n", "\r", "\n", "\t", "."), ' ', $text), ' ', $limit);
        if($endpos !== FALSE)
            $text = trim(substr($text, 0, $endpos)) . $postfix;
    }
    return $text . $hardcoded_postfix;
}

/**
 * Shorten string function. Returns shortened strin without word brak
 * @author http://www.pjgalbraith.com/truncating-text-html-with-php/
 * @version 1.0
 */
function truncate_words($text, $limit, $ellipsis = '...') {
    $words = preg_split("/[\n\r\t ]+/", $text, $limit + 1, PREG_SPLIT_NO_EMPTY|PREG_SPLIT_OFFSET_CAPTURE);
    if (count($words) > $limit) {
        end($words); //ignore last element since it contains the rest of the string
        $last_word = prev($words);
           
        $text =  substr($text, 0, $last_word[1] + strlen($last_word[0])) . $ellipsis;
    }
    return $text;
}

/**
 * Get first paragraph of html text
 */
function getFirstPara($string){
    $ln = strpos($string, "</p>")+4;
    $result = substr($string,0, $ln);
    $result = strip_tags($result);
    if (strlen($result) > 10) {
        echo $result; // don't know why blade template doesn't echo recursive functions second iteration return
        return;
    }
    $string = substr( $string , $ln );
    getFirstPara($string);

}
function replace_media($string){
  $replaced =   preg_replace_callback(
        "/<img[^>]+\>/i",
        function($m) {
            static $id = 0;
            preg_match_all('/[0-9]/', $m[0], $index);
            $id++;
            return "[Media#".$index[0][0]."]";
        },
        $string);
    return $replaced;
}
function upper ($string){
    $upperString  = Str::upper($string);
    return $upperString;
}
function generateRandomString($length) {
    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < $length; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}