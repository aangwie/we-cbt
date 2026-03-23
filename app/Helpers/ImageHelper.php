<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageHelper
{
    /**
     * Store an uploaded image as WebP format with high quality.
     * Returns the storage path relative to the public disk.
     */
    public static function storeAsWebp(UploadedFile $file, string $directory = 'images'): string
    {
        $filename = Str::random(40) . '.webp';
        $path = $directory . '/' . $filename;

        // Read the original image
        $imageData = file_get_contents($file->getRealPath());
        $image = @imagecreatefromstring($imageData);

        if (!$image) {
            // Fallback: store as-is if GD can't process it
            return $file->store($directory, 'public');
        }

        // Preserve transparency for PNG images
        imagepalettetotruecolor($image);
        imagealphablending($image, true);
        imagesavealpha($image, true);

        // Create WebP with quality 90 (high quality, no visible loss)
        $storagePath = storage_path('app/public/' . $path);

        // Ensure directory exists
        $dir = dirname($storagePath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        imagewebp($image, $storagePath, 90);
        imagedestroy($image);

        return $path;
    }

    /**
     * Store image as-is (for logos, favicons — no WebP conversion).
     */
    public static function storeOriginal(UploadedFile $file, string $directory = 'settings'): string
    {
        return $file->store($directory, 'public');
    }
}
