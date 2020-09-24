<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Http\Requests\CustomerRequest;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customers = Customer::latest()
            ->paginate(10);
        $search = request()->search;

        if ($search) {
            $customers = Customer::where('name', 'LIKE', "%$search%")
                ->orWhere('nik', 'LIKE', "%$search%")
                ->orWhere('phone', 'LIKE', "%$search%")
                ->orWhere('email', 'LIKE', "%$search%")
                ->latest()
                ->paginate(10);
        }

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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CustomerRequest $request)
    {

        $data = array_merge(
            $request->validated(),
            [
                'created_by' => auth()->id(),
                'avatar' => uploadImage($request, 'customers'),
            ]
        );

        Customer::create($data);

        return redirect()->route('customers.index')
            ->with('status', 'Data customer berhasil dibuat.');
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
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(CustomerRequest $request, Customer $customer)
    {
        $data = array_merge(
            $request->validated(),
            [
                'updated_by' => auth()->id(),
                'avatar' => uploadImage($request, 'customers', $customer->avatar),
            ]
        );

        $customer->update($data);

        return redirect()->route('customers.index')
            ->with('status', 'Data customer berhasil diubah.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        if ($customer->avatar) Storage::disk('public')->delete($customer->avatar);

        $customer->update(
            [
                'deleted_by' => auth()->id(),
                'avatar' => null
            ]
        );

        $customer->delete();

        return redirect()->route('customers.index')
            ->with('status', 'Data customer berhasil dihapus.');
    }
}
