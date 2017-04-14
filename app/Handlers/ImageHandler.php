<?php


namespace App\Handlers;


use App\Contracts\ImageHandlerContract;
use File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageHandler implements ImageHandlerContract
{

    public function uploadImage(UploadedFile $file, $folder, $includePath = false, $options = [], $tags = [])
    {
        $relativePath = 'media/' . $folder . '/';
        $filename     = time() . '-' . clean_string($file->getClientOriginalName());

        if ( !File::exists(public_path($relativePath))) {
            $this->createDirIfNotExists(public_path('media'));
            File::makeDirectory(public_path($relativePath));
        }
        $path = public_path($relativePath);
        $file->move($path, $filename);

        if ($includePath)
            return asset($relativePath . $filename);

        return $filename;
    }

    public function replaceImage($oldImage, $newImage, $folder)
    {

        $this->deleteImage($oldImage);

        $filename = $this->uploadImage($newImage, $folder);

        return $filename;
    }

    public function checkForUpdate($object, $image, $folder, $fieldName = 'image')
    {

        if ($image instanceof UploadedFile) {

            if ( !$object->{$fieldName}) {
                $checkedImage = $this->uploadImage($image, $folder);
            } else {
                $checkedImage = $this->replaceImage($object->{$fieldName}, $image, $folder);
            }
        } else {
            $checkedImage = explode('/', $object->{$fieldName});
            $checkedImage = end($checkedImage);
        }

        return $checkedImage;

    }

    public function deleteImage($image)
    {
        if (File::exists(public_path($image))) {
            File::delete(public_path($image));
        }
    }

    public function remove($oldImage, $folder)
    {
        $relativePath = 'media/' . $folder . '/' . $oldImage;
        if (File::exists(public_path($relativePath))) {
            File::delete(public_path($relativePath));
        }
    }

    public function moveFromTemp($image, $destinationFolder, $options = [])
    {
        $this->moveImageToFolder('temp', $destinationFolder, $image);
    }

    public function moveImageToFolder($from, $to, $image)
    {
        $initialPath = public_path('media/' . $from . '/' . $image);

        $destinationPath = public_path('media/' . $to . '/' . $image);

        $this->createDirIfNotExists(public_path('media/' . $to . '/'));
        File::move($initialPath, $destinationPath);
    }

    public function createDirIfNotExists($dir)
    {
        if ( !File::exists($dir)) {
            File::makeDirectory($dir);
        }
    }

    public function prepareImageUsingTemp($image, $destinationFolder)
    {
        if ($image['temp'] && $image['name'] && !$image['delete']) {
            $this->remove($image['oldImage'], $destinationFolder);
            $this->moveFromTemp($image['name'], $destinationFolder);
        }
        if ($image['delete']) {
            if ($image['temp']) {
                $this->remove($image['name'], 'temp');
            } else {
                $this->remove($image['name'], $destinationFolder);
            }
            $image['name'] = null;
        }

        return $image;
    }

    public function getPath($name)
    {
        return asset('/media/temp/' . $name);
    }

}