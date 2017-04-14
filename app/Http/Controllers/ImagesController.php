<?php

namespace App\Http\Controllers;

use App\Handlers\CloudinaryImageHandler;
use App\Http\Requests\UploadImageRequest;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ImagesController extends Controller
{
    public function uploadImage(UploadImageRequest $request, CloudinaryImageHandler $handler)
    {
        $image = $handler->uploadImage($request->file('image'), 'temp');

        return \Response::json([
            'image' => [
                'name'     => $image,
                'path'     => $handler->getPath($image),
                'temp'     => true,
            ],

        ], 200);
    }
}
