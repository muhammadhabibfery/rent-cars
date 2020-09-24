<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfilePasswordRequest;
use App\Http\Requests\UserRequest;
use App\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Gate::allows('edit-profile')) return $next($request);
            abort(404);
        })
            ->except(['editPassword', 'updatePassword']);
    }

    public function editProfile()
    {
        $user = auth()->user();

        return view('pages.profiles.edit', compact('user'));
    }

    public function updateProfile(UserRequest $request)
    {
        $user = auth()->user();

        $data = array_merge(
            $request->validated(),
            ['avatar' => uploadImage($request, 'users', $user->avatar)]
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
