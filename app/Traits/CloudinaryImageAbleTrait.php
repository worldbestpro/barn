<?php

namespace App\Traits;


trait CloudinaryImageAbleTrait
{
    public function getImageAttribute($image)
    {
        $temp = true;
        if ($image) {
            $temp = false;
        }

        $imgname = empty($image) ? asset('media/'.$this->imagesFolder."/default.jpg") : \Cloudder::show($image);

        return [
            'name'     => $image,
            'path'     => $imgname,
            'temp'     => $temp,
            'oldImage' => $image,
            'delete'   => false,
        ];
    }

    public function getImagesFolder(){
        return $this->imagesFolder;
    }
}