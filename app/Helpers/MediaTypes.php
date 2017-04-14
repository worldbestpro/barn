<?php

function get_media_type($file)
{
    $type = '';
    if (strpos($file->mime_type, 'image') !== false) {
        $type = 'image';
    }
    else{
        $type = $file->mime_type;
    }
    return $type;
}