<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;


trait FileUploadTrait
{
    public function uploadToMediaLibrary($model, UploadedFile $file, string $collection = 'default', bool $isPrivate = false): void
    {
        if ($isPrivate) {
            $model->addMedia($file)
                ->toMediaCollection($collection, 'private');
        } else {
            $model->addMedia($file)
                ->toMediaCollection($collection);
        }
    }


    /**
     * Upload multiple files to media library
     */
    public function uploadMultipleToMediaLibrary($model, array $files, string $collection = 'default', bool $isPrivate = false): void
    {
        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                $this->uploadToMediaLibrary($model, $file, $collection, $isPrivate);
            }
        }
    }

    /**
     * Delete media from collection
     */
    public function deleteFromMediaLibrary($model, string $collection): void
    {
        $model->clearMediaCollection($collection);
    }

    /**
     * Validate image file
     */
    protected function validateImageFile(UploadedFile $file, int $maxSize = 2048): bool
    {
        $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

        return in_array($file->getMimeType(), $allowedMimes) &&
            $file->getSize() <= ($maxSize * 1024);
    }

    /**
     * Get optimized image constraints
     */
    protected function getImageConstraints(): array
    {
        return [
            'icon' => ['max_width' => 100, 'max_height' => 100],
            'image' => ['max_width' => 800, 'max_height' => 600],
        ];
    }
}