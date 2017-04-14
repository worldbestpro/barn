<?php

/**
 * Check variable helper. Functions to check if variable exist, and return itself if so, or return NULL
 * @author Anton Iskandiarov
 * @version 0.1
 */
function if_isset(&$var, $fallback = null)
{ // get var by reference
    if (isset($var) && !empty($var) && !is_null($var)) {
        return $var;
    } else if ($fallback) {
        return $fallback;
    } else {
        return false;
    }
}

/**
 * Check if array key exists and if so - return it's value
 * @author Anton Iskandiarov
 * @version 0.1
 */
function if_array_key_exists($key = null, $array = null)
{
    if (is_array($array)) {
        if (array_key_exists($key, $array)) {
            return $array[$key];
        }
    }

    return false;
}

/**
 * Checks if numeric variable is true or false and return boolean equivalent
 * @author Anton Iskandiarov
 * @version 0.1
 */
function get_boolean($var)
{ // get var by reference
    return $var ? 'true' : 'false';
}

/**
 * Return bytes
 * @author
 * @version
 */
function return_bytes($val)
{
    $val = trim($val);
    $last = strtolower($val[strlen($val) - 1]);
    switch ($last) {
        // The 'G' modifier is available since PHP 5.1.0
        case 'g':
            $val *= 1024;
        case 'm':
            $val *= 1024;
        case 'k':
            $val *= 1024;
    }

    return $val;
}

function media_empty($media)
{

    switch ($media['type']) {
        case 'single-image':
            if ($media['name'] == 'main_image') {
                if ($media['image'] == null) {
                    $media['empty'] = true;
                }
            } else {
                if ($media['image'] == null && $media['caption'] == '') {
                    $media['empty'] = true;
                }
            }

            return $media;
            break;
        case 'video':
            if ($media['image'] == null && $media['caption'] == '' && $media['path'] == '' && $media['transcript'] == '') {
                $media['empty'] = true;
            }
            return $media;
            break;
        case 'podcast' :
            if ($media['image'] == null && $media['caption'] == '' && $media['path'] == '' && $media['transcript'] == '') {
                $media['empty'] = true;
            }
            return $media;
            break;
        case 'carousel':
            $c = 0;
            for ($i = 0; $i < count($media['image']); $i++) {

                if ($media['image'][$i]['image'] == null && $media['image'][$i]['caption'] == '') {
                    $media['image'][$i]['empty'] = true;
                    $c++;
                }
            }
            if ($c == count($media['image'])) {
                $media['empty'] = true;
            }
            return $media;
            break;
        default :
            return $media;
    }
}

function checkActive($user, $presentation, $request)
{
    if ($user->activePresentation) {
        if ($request->is('admin/presentations/select') && $presentation->id == $user->activePresentation->id) {
            return 'presentation-active';
        }

    }
}