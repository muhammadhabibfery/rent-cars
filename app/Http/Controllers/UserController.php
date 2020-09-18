<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $admins = User::where('name', '!=', 'administrator')
            ->latest()
            ->paginate(10);
        $search = request()->search;

        if ($search) {
            $admins = User::where('name', '!=', 'administrator')
                ->where(function ($query) use ($search) {
                    $query->where('name', 'LIKE', "%$search%")
                        ->orWhere('email', 'LIKE', "%$search%")
                        ->orWhere('phone', 'LIKE', "%$search%");
                })
                ->latest()
                ->paginate(10);
        }

        return view('pages.admins.index', compact('admins'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', User::class);

        return view('pages.admins.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $this->authorize('create', User::class);

        $data = array_merge(
            $request->validated(),
            [
                'password' => Hash::make($request->phone),
                'avatar' => uploadImage($request, 'admins')
            ]
        );

        User::create($data);

        return redirect()->route('admins.index')
            ->with('status', 'Data admin berhasil dibuat.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $admin
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        $user->update(['deleted_by' => auth()->id()]);

        if ($user->avatar) Storage::disk('public')->delete($user->avatar);

        $user->delete();

        return redirect()->route('admins.index')
            ->with('status', 'Data admin berhasil dihapus.');
    }
}
