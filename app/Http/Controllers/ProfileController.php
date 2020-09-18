<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfilePasswordRequest;
use App\Http\Requests\UserRequest;
use App\User;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{

    public function editProfile()
    {
        $this->authorize('editProfile', User::class);

        $user = auth()->user();

        return view('pages.profiles.edit', compact('user'));
    }

    public function updateProfile(UserRequest $request)
    {
        $this->authorize('editProfile', User::class);
        $user = auth()->user();

        $data = array_merge(
            $request->validated(),
            ['avatar' => uploadImage($request, 'profiles', $user->avatar)]
        );

        $user->update($data);

        return redirect()->route('dashboard')
            ->with('status', "Profil Admin {$user->name} berhasil diperbarui");
    }

    public function editPassword()
    {
        return view('pages.profiles.password.edit');
    }

    public function updatePassword(ProfilePasswordRequest $request)
    {
        $data = ['password' => Hash::make($request->validated()['new_password'])];

        auth()->user()
            ->update($data);

        return redirect()->route('dashboard')
            ->with(['status' => 'Password berhasil diubah.']);
    }
}
