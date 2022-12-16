<?php

use App\User;

// use Illuminate\Support\Facades\Storage;

// function uploadImage($request, $directoryName, $fieldImage = null)
// {
//     $file = $fieldImage;

//     if ($request->hasFile('gambar')) {
//         if ($fieldImage) Storage::disk('public')->delete($fieldImage);

//         $fileName = explode('.', $request->file('gambar')->getClientOriginalName());
//         $fileName = head($fileName) . rand(0, 20) . '.' . last($fileName);
//         $file = Storage::disk('public')
//             ->putFileAs($directoryName, $request->file('gambar'), $fileName);
//     }

//     return $file;
// }


/**
 * query a user who create or update or delete related data
 *
 * @param  int $id
 * @return string
 */
function createdUpdatedDeletedBy(int $id)
{
    return User::find($id)->name;
}
