<?php

namespace App\Http\Controllers;

use App\User;
use App\Traits\ImageHandler;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    use ImageHandler;

    /**
     * constant of route name
     *
     * @var string
     */
    private const ROUTE_NAME = 'admins.index';

    /**
     * authorizing the user controller
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $admins = $this->getAllAdminsByKeyword($request->search, 10);

        return view('pages.admins.index', compact('admins'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.admins.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\UserRequest $request
     * @return mixed
     */
    public function store(UserRequest $request)
    {
        $data = array_merge(
            $request->validated(),
            [
                'password' => Hash::make($request->validated()['nik']),
                'avatar' => $this->createImage($request, 'avatars')
            ]
        );

        return $this->checkProcess(
            self::ROUTE_NAME,
            'Data admin berhasil dibuat',
            function () use ($data) {
                if (!User::create($data)) {
                    $this->deleteImage($data['avatar']);
                    throw new \Exception('Data admin gagal dibuat');
                }
            }
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function destroy(User $user)
    {
        $avatar = $user->avatar;
        $failedMessage = 'Data admin gagal dihapus';

        return $this->checkProcess(
            self::ROUTE_NAME,
            'Data admin berhasil dihapus',
            function () use ($user, $avatar, $failedMessage) {
                if (!$user->update(['deleted_by' => auth()->id()])) throw new \Exception($failedMessage);

                if ($user->delete()) {
                    $this->deleteImage($avatar);
                } else {
                    throw new \Exception($failedMessage);
                }
            },
            true
        );
    }

    /**
     * query get all admins (users) by keyword
     *
     * @param  string|null $keyword
     * @param  int $number (define paginate data per page)
     * @return \illuminate\Pagination\LengthAwarePaginator
     */
    private function getAllAdminsByKeyword(?string $keyword = null, int $number)
    {
        $admins = User::where('name', '!=', 'administrator');

        if ($keyword) {
            $admins->where(function ($query) use ($keyword) {
                $query->where('name', 'LIKE', "%$keyword%")
                    ->orWhere('email', 'LIKE', "%$keyword%");
            });
        }

        return $admins->latest()
            ->paginate($number);
    }

    /**
     * Check one or more processes and catch them if fail
     *
     * @param  string $routeName
     * @param  string $successMessage
     * @param  callable $action
     * @param  bool $dbTransaction (use database transaction for multiple queries)
     * @return \Illuminate\Http\Response
     */
    private function checkProcess(string $routeName, string $successMessage, callable $action, ?bool $dbTransaction = false)
    {
        try {
            if ($dbTransaction) DB::beginTransaction();

            $action();

            if ($dbTransaction) DB::commit();
        } catch (\Exception $e) {
            if ($dbTransaction) DB::rollback();

            return redirect()->route($routeName)
                ->with('failed', $e->getMessage());
        }

        return redirect()->route($routeName)
            ->with('success', $successMessage);
    }
}
