<?php

namespace App\Contracts;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface ImageHandlerContract
{
    public function uploadImage(UploadedFile $file, $folder, $includePath = false, $options = [], $tags = []);

    public function getPath($publicId);

    public function replaceImage($oldImage, $newImage, $folder);

    public function checkForUpdate($object, $image, $folder, $fieldName = 'image');

    public function deleteImage($image);

    public function remove($oldImage, $folder);

    public function moveFromTemp($publicId, $folder, $options = []);

    public function moveImageToFolder($from, $to, $image);

    public function createDirIfNotExists($dir);

    public function prepareImageUsingTemp($image, $destinationFolder);

}