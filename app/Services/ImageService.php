<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class ImageService
{
    /**
     * Convert an uploaded image file to WebP and store it on the specified disk and directory.
     *
     * @param UploadedFile|string $file
     * @param string $directory Directory inside storage disk (e.g. 'room_types', 'hotels', 'cities')
     * @param int|null $maxWidth Optional max width to scale down (e.g. 1920)
     * @param string $disk Disk name (default: 'public')
     * @return string Public accessible storage path (e.g. '/storage/room_types/abc123xyz.webp')
     */
    public static function uploadAsWebp($file, string $directory = 'uploads', ?int $maxWidth = 1920, string $disk = 'public'): string
    {
        try {
            // Decode image from uploaded file or path
            if ($file instanceof UploadedFile) {
                $image = Image::decode($file->getRealPath());
            } elseif (is_string($file) && file_exists($file)) {
                $image = Image::decodePath($file);
            } else {
                $image = Image::decode($file);
            }

            // Scale down if larger than maxWidth while preserving aspect ratio
            if ($maxWidth && $image->width() > $maxWidth) {
                $image->scaleDown(width: $maxWidth);
            }

            // Encode to WebP format
            $encoded = $image->encodeUsingFileExtension('webp');

            // Unique filename with .webp extension
            $filename = Str::random(40) . '.webp';
            $relativePath = trim($directory, '/') . '/' . $filename;

            // Save to storage disk
            Storage::disk($disk)->put($relativePath, (string) $encoded);

            return '/storage/' . $relativePath;
        } catch (\Throwable $e) {
            // Fallback: standard file upload if conversion fails
            if ($file instanceof UploadedFile) {
                $path = $file->store($directory, $disk);
                return '/storage/' . $path;
            }
            throw $e;
        }
    }

    /**
     * Delete a stored image if it exists in storage disk.
     */
    public static function deleteFile(?string $path, string $disk = 'public'): void
    {
        if (!$path) {
            return;
        }

        if (str_starts_with($path, '/storage/')) {
            $relativePath = substr($path, strlen('/storage/'));
            Storage::disk($disk)->delete($relativePath);
        } elseif (str_starts_with($path, 'storage/')) {
            $relativePath = substr($path, strlen('storage/'));
            Storage::disk($disk)->delete($relativePath);
        }
    }
}
