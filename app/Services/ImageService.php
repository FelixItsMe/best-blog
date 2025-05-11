<?php

namespace App\Services;

use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ImageService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function storeImage($image, string $path, ?float $ratio = null): string
    {
        $clientExtension = $image->extension();
        $name = strtoupper(Str::random(5)) . '-' . time() . '.' . $clientExtension;

        $imageResize = Image::read($image->getPathName());

        if ($ratio != null) {
           $originalWidth = $imageResize->width();
            $originalHeight = $imageResize->height();

            if ($originalWidth / $originalHeight > $ratio) {
                $height = $originalHeight;
                $width = floor($height * $ratio);
                $x = floor(($originalWidth - $width) / 2);
                $y = 0;
            } else {
                $width = $originalWidth;
                $height = floor($width / $ratio);
                $x = 0;
                $y = floor(($originalHeight - $height) / 2);
            }

            // Crop the image to the desired ratio
            $imageResize = $imageResize->crop($width, $height, $x, $y);
        }

        if (!File::exists(public_path($path))) {
            File::makeDirectory(public_path($path), 0755, true, true);
        }

        $imageResize->save(public_path($path . $name));

        return $path . $name;
    }

    public function deleteImage(?string $path) : bool {
        if(!$path) return false;

        if (!File::exists(public_path($path))) {
            return false;
        }

        File::delete(public_path($path));

        return true;
    }
}
