<?php


namespace App\Handlers;


use App\Contracts\ImageHandlerContract;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Cloudder;
use Image;
use Storage;

class CloudinaryImageHandler implements ImageHandlerContract
{

    public function uploadImage(UploadedFile $file, $folder, $includePath = false, $options = [], $tags = [])
    {
        $filename     = time() . '-' . clean_string($file->getClientOriginalName());
        $fullPath = public_path('media/temp/' . $filename);
        Image::make($file)->widen(200)->save($fullPath);

        $options['folder'] = $folder;
        $options['crop'] = 'thumb';
        Cloudder::upload($fullPath, $filename, $options, $tags);
        Storage::disk('media')->delete('temp/' . $filename);
        return Cloudder::getPublicId();

    }

    public function getPath($publicId)
    {
        return Cloudder::show($publicId);
    }

    public function replaceImage($oldImage, $newImage, $folder)
    {

        $this->deleteImage($oldImage);

        $filename = $this->uploadImage($newImage, $folder);

        return $filename;
    }

    public function checkForUpdate($object, $image, $folder, $fieldName = 'image')
    {

    }

    public function deleteImage($image)
    {
        Cloudder::delete($image);
    }

    public function remove($oldImage, $folder = null)
    {
        Cloudder::delete($oldImage);
    }

    public function moveFromTemp($publicId, $folder, $options = [])
    {
        $toPublicId = strtr($publicId, ['temp' => $folder]);
        Cloudder::rename($publicId, $toPublicId, $options);
        return $toPublicId;
    }

    public function moveImageToFolder($from, $to, $publicId)
    {
        $toPublicId = strtr($publicId, [$from => $to]);
        Cloudder::rename($publicId, $toPublicId);
    }

    public function createDirIfNotExists($dir)
    {

    }

    public function prepareImageUsingTemp($image, $destinationFolder)
    {
        if ($image['temp'] && $image['name'] && !$image['delete']) {
            if($image['oldImage']){
                $this->remove($image['oldImage']);
            }
            $image['name'] = $this->moveFromTemp($image['name'], $destinationFolder);
        }
        if ($image['delete']) {
            $this->remove($image['name']);
            $image['name'] = null;
        }

        return $image;
    }

}