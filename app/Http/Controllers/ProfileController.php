<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Traits\ImageHandler;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{

    use ImageHandler;

    /**
     * constant of route name
     *
     * @var string
     */
    private const ROUTE_DASHBOARD = 'dashboard';

    /**
     * Show the form edit user's profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function editProfile()
    {
        $user = auth()->user();

        return view('pages.profiles.edit', compact('user'));
    }

    /**
     * Update the user's profile.
     *
     * @param  \App\Http\Requests\UserRequest  $request
     * @return mixed
     */
    public function updateProfile(UserRequest $request)
    {
        $user = auth()->user();

        $data = array_merge(
            $this->checkAdministratorData($request->validated()),
            ['avatar' => $this->createImage($request, 'avatars', $user->avatar)]
        );

        return $this->checkProcess(
            self::ROUTE_DASHBOARD,
            'Profil anda berhasil diperbarui',
            function () use ($user, $data) {
                if (!$user->update($data)) throw new \Exception("Profil anda gagal diperbarui");
            }
        );
    }

    /**
     * Show the form edit user's password.
     *
     * @return \Illuminate\Http\Response
     */
    public function editPassword()
    {
        return view('pages.profiles.password.edit');
    }

    /**
     * Update the user's password.
     *
     * @param  \App\Http\Requests\UserRequest  $request
     * @return mixed
     */
    public function updatePassword(UserRequest $request)
    {
        $data = ['password' => Hash::make($request->validated()['new_password'])];

        return $this->checkProcess(
            self::ROUTE_DASHBOARD,
            'Password berhasil diubah',
            function () use ($data) {
                if (!auth()->user()->update($data)) throw new \Exception('Password gagal diubah');
            }
        );
    }

    /**
     * check Administrator's Data
     *
     * @param  array $validatedData
     * @return array
     */
    private function checkAdministratorData(array $validatedData)
    {
        if (auth()->user()->name === 'Administrator') return array_filter($validatedData, fn ($vd) => $vd !== 'Administrator');

        return $validatedData;
    }

    /**
     * Check one or more processes and catch them if fail
     *
     * @param  string $routeName
     * @param  string $successMessage
     * @param  callable $action
     * @return \Illuminate\Http\Response
     */
    private function checkProcess(string $routeName, string $successMessage, callable $action)
    {
        try {
            $action();
        } catch (\Exception $e) {
            return redirect()->route($routeName)
                ->with('failed', $e->getMessage());
        }

        return redirect()->route($routeName)
            ->with('success', $successMessage);
    }
}
