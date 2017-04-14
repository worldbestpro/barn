<?php

namespace App\Traits;


trait ImageAbleTrait
{
    public function getImageAttribute($image)
    {
        $temp = true;
        if ($image) {
            $temp = false;
        }

        $imgname = empty($image) ? "default.jpg" : $image;

        return [
            'name'     => $image,
            'path'     => asset('media/'.$this->imagesFolder.'/'. $imgname),
            'temp'     => $temp,
            'oldImage' => $image,
            'delete'   => false,
        ];
    }
}