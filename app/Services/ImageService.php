<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use InterventionImage;

class ImageService
{
    /**
     * Upload image
     * @param UploadedFile $file
     *
     * @return string
     */
    public function upload(UploadedFile $file)
    {
        $name = $this->generateName($file);

        [$storagePath, $path] = $this->makeDirectory($name);

        $image = InterventionImage::make($file->getRealPath());

        // Resize Image
        $image->resize(1000, null, function ($constraint) {
            $constraint->aspectRatio();
        })->save($storagePath . '/' . $path);

        return Storage::url($path);
    }

    /**
     * Generate Name
     *
     * @param UploadedFile $file
     * @return string $name
     */
    protected function generateName(UploadedFile $file): string
    {
        return time() . Str::random() . '.' . $file->getClientOriginalExtension();
    }

    /**
     * Make Directory for Image
     *
     * @param string $name
     * @return array
     */
    protected function makeDirectory(string $name): array
    {
        $disk = env('FILESYSTEM_DRIVER', 'public');
        $storagePath = config('filesystems.disks.' . $disk . '.root');
        $dir = 'image';
        Storage::makeDirectory($dir);
        $path = $dir . '/' . $name;
        return [$storagePath, $path];
    }

    public function removeImage(string $url)
    {
        [$url, $path] = explode('storage', $url);
        Storage::delete($path);
    }
}
