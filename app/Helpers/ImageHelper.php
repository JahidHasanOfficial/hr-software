<?php

namespace App\Helpers;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageHelper
{
    /**
     * Upload and convert image to WebP with target size constraints.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $folder
     * @param string|null $old_image
     * @param int $width
     * @param int $height
     * @param int $quality
     * @return string|null
     */
    public static function upload($file, $folder, $old_image = null, $width = 150, $height = 150, $quality = 70)
    {
        if (!$file) return null;

        // 1. Delete old image if it exists
        if ($old_image) {
            self::delete($old_image);
        }

        // 2. Initialize Intervention Image with GD driver
        $manager = new ImageManager(new Driver());

        // 3. Create image instance and process
        $image = $manager->read($file);

        // Resize and crop to maintain aspect ratio (Fit)
        $image->cover($width, $height);

        // 4. Generate unique name with webp extension
        $filename = Str::random(20) . '.webp';
        $path = $folder . '/' . $filename;

        // 5. Save to public disk as webp
        $encoded = $image->toWebp($quality);
        
        Storage::disk('public')->put($path, (string) $encoded);

        return $path;
    }

    /**
     * Delete image from storage.
     *
     * @param string|null $path
     * @return void
     */
    public static function delete($path)
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    /**
     * Update/Replace image shortcut.
     */
    public static function update($file, $folder, $old_image, $width = 150, $height = 150)
    {
        return self::upload($file, $folder, $old_image, $width, $height);
    }
}
