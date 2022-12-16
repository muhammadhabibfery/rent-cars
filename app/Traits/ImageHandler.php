<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait ImageHandler
{

    /**
     * create image file and save to application directory
     *
     * @param  object $request
     * @param  string $directory
     * @param  string|null $oldImage
     * @return string
     */
    private function createImage(object $request, string $directory, ?string $oldImage = null)
    {
        $paths = $oldImage;

        if ($request->hasFile('image')) {
            if ($oldImage) $this->deleteImage($paths);

            $fileName = explode('.', $request->file('image')->getClientOriginalName());
            $fileName = head($fileName) . rand(1, 100) . '.' . last($fileName);
            $paths = Storage::disk('public')
                ->putFileAs($directory, $request->file('image'), $fileName);
        }

        return $paths;
    }

    /**
     * delete the image file(s) from application directory
     *
     * @param  string|null $paths
     * @return void
     */
    private function deleteImage(?string $paths = null)
    {
        if ($paths) Storage::disk('public')
            ->delete($paths);
    }
}
