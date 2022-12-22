<?php

namespace App\Http\Controllers;

use App\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\CustomerRequest;
use App\Traits\ImageHandler;

class CustomerController extends Controller
{

    use ImageHandler;

    /**
     * constant of route name
     *
     * @var string
     */
    private const ROUTE_INDEX = 'customers.index', ROUTE_TRASH = 'customers.trash';

    /**
     * authorizing the customer controller
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            return auth()->user()->name === 'Administrator' ? $next($request) : abort(403);
        })->only(['indexTrash', 'showTrash', 'restore', 'forceDelete']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $customers = $this->getAllCustomersByKeyword($request->search, 10);

        return view('pages.customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.customers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CustomerRequest  $request
     * @return mixed
     */
    public function store(CustomerRequest $request)
    {
        $data = $this->mergeData(
            $request->validated(),
            ['created_by' => auth()->id(), 'avatar' => $this->createImage($request, 'avatars')]
        );

        return $this->checkProcess(
            self::ROUTE_INDEX,
            'Data customer berhasil dibuat',
            function () use ($data) {
                if (!Customer::create($data)) {
                    $this->deleteImage($data['avatar']);
                    throw new \Exception('Data customer gagal dibuat');
                }
            }
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        return view('pages.customers.detail', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        return view('pages.customers.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\CustomerRequest  $request
     * @param  \App\Customer  $customer
     * @return mixed
     */
    public function update(CustomerRequest $request, Customer $customer)
    {
        $data = $this->mergeData(
            $request->validated(),
            ['updated_by' => auth()->id(), 'avatar' => $this->createImage($request, 'avatars', $customer->avatar)]
        );

        return $this->checkProcess(
            self::ROUTE_INDEX,
            'Data customer berhasil diubah',
            function () use ($customer, $data) {
                if (!$customer->update($data)) throw new \Exception('Data customer gagal diubah');
            }
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Customer  $customer
     * @return mixed
     */
    public function destroy(Customer $customer)
    {
        $failedMessage = 'Data customer gagal dihapus';

        return $this->checkProcess(
            self::ROUTE_INDEX,
            'Data customer berhasil dihapus',
            function () use ($customer, $failedMessage) {
                if (!$customer->update(['deleted_by' => auth()->id()])) throw new \Exception($failedMessage);
                if (!$customer->delete()) throw new \Exception($failedMessage);
            },
            true
        );
    }

    /**
     * Display a listing of the deleted resource.
     *
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function indexTrash(Request $request)
    {
        $customers = $this->getAllCustomersByKeyword($request->search, 10, true);

        return view('pages.customers.trash.index-trash', compact('customers'));
    }

    /**
     * Display the specified deleted resource.
     *
     * @param  string  $phone
     * @return \Illuminate\Http\Response
     */
    public function showTrash(string $phone)
    {
        $customer = $this->getOneDeletedCustomer($phone);

        return view('pages.customers.trash.detail-trash', compact('customer'));
    }

    /**
     * restore the specified deleted resource.
     *
     * @param  string  $phone
     * @return mixed
     */
    public function restore(string $phone)
    {
        $customer = $this->getOneDeletedCustomer($phone);
        $failedMessage = 'Data customer gagal dikembalikan';

        return $this->checkProcess(
            self::ROUTE_TRASH,
            'Data customer berhasil dikembalikan',
            function () use ($customer, $failedMessage) {
                if (!$customer->update(['deleted_by' => null])) throw new \Exception($failedMessage);
                if (!$customer->restore()) throw new \Exception($failedMessage);
            },
            true
        );
    }

    /**
     * remove the specified deleted resource
     *
     * @param  string $phone
     * @return mixed
     */
    public function forceDelete(string $phone)
    {
        $customer = $this->getOneDeletedCustomer($phone);
        $avatar = $customer->avatar;

        return $this->checkProcess(
            self::ROUTE_TRASH,
            'Data customer berhasil dihapus secara permanen',
            function () use ($customer, $avatar) {
                if ($customer->forceDelete()) {
                    $this->deleteImage($avatar);
                } else {
                    throw new \Exception('Data customer gagal dihapus secara permanen');
                }
            }
        );
    }

    /**
     * query all customers by keyword
     *
     * @param  string|null $keyword
     * @param  int $number (define paginate data per page)
     * @param  bool $onlyDeleted (only trashed when delete using soft delete)
     * @return \illuminate\Pagination\LengthAwarePaginator
     */
    private function getAllCustomersByKeyword(?string $keyword, int $number, ?bool $onlyDeleted = false)
    {
        $customers = Customer::query();

        if ($onlyDeleted) $customers->onlyTrashed();

        if ($keyword) $customers->where('name', 'LIKE', "%$keyword%");

        return $customers->latest()
            ->paginate($number);
    }

    /**
     * query a deleted customer by phone field
     *
     * @param  string $phone
     * @return \illuminate\Database\Eloquent\Model
     */
    private function getOneDeletedCustomer(string $phone)
    {
        return Customer::onlyTrashed()
            ->where('phone', $phone)
            ->firstOrFail();
    }

    /**
     * merge data into an array
     *
     * @param  array $validatedData
     * @param  array $additonalData
     * @return array
     */
    private function mergeData(array $validatedData, array $additonalData)
    {
        return array_merge($validatedData, $additonalData);
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
            if ($dbTransaction) DB::rollBack();

            return redirect()->route($routeName)
                ->with('failed', $e->getMessage());
        }

        return redirect()->route($routeName)
            ->with('success', $successMessage);
    }
}
