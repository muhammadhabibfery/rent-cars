<?php

namespace App\Http\Controllers;

use App\Car;
use Illuminate\Support\Str;
use App\Http\Requests\CarRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class CarController extends Controller
{
    /**
     * Display a listing of the resource (Car Available).
     *
     * @return \Illuminate\Http\Response
     */
    public function available(Request $request)
    {
        // $cars = Car::where('status', 'AVAILABLE')
        //     ->latest()
        //     ->paginate(10);
        // $search = request()->search;

        // if ($search) {
        //     $cars = Car::where('status', 'AVAILABLE')
        //         ->where(function ($query) use ($search) {
        //             $query->where('name', 'LIKE', "%$search%")
        //                 ->orWhere('merk', 'LIKE', "%$search%")
        //                 ->orWhere('years', 'LIKE', "%$search%")
        //                 ->orWhere('plat_number', 'LIKE', "%$search%");
        //         })
        //         ->latest()
        //         ->paginate();
        // }

        $cars = $this->getAllCarWithQueryString('AVAILABLE', $request->search);

        return view('pages.cars.index-available', compact('cars'));
    }

    /**
     * Display a listing of the resource (Car Available).
     *
     * @return \Illuminate\Http\Response
     */
    public function notAvailable(Request $request)
    {
        // $cars = Car::where('status', 'NOT AVAILABLE')
        //     ->latest()
        //     ->paginate(10);
        // $search = request()->search;

        // if ($search) {
        //     $cars = Car::where('status', 'NOT AVAILABLE')
        //     ->orWhere('name', 'LIKE', "%$search%")
        //     ->orWhere('merk', 'LIKE', "%$search%")
        //     ->orWhere('years', 'LIKE', "%$search%")
        //     ->orWhere('plat_number', 'LIKE', "%$search%")
        //     ->latest()
        //     ->paginate();
        // }

        $cars = $this->getAllCarWithQueryString('NOT AVAILABLE', $request->search);

        return view('pages.cars.index-not-available', compact('cars'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.cars.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CarRequest $request)
    {
        Car::create($this->mergingData($request));

        return redirect()->route('cars.available')
            ->with('status', 'Data mobil berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function show($status, Car $car)
    {
        return view('pages.cars.detail', compact('car'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function edit(Car $car)
    {
        return view('pages.cars.edit', compact('car'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function update(CarRequest $request, Car $car)
    {
        $car->update($this->mergingData($request, $car));

        return redirect()->route('cars.available')
            ->with('status', 'Data mobil berhasil diubah.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function destroy(Car $car)
    {
        if ($car->car_image) Storage::disk('public')->delete($car->car_image);

        $car->update(
            [
                'deleted_by' => auth()->id(),
                'car_image' => null
            ]
        );

        $car->delete();

        return redirect()->route('cars.available')
            ->with('status', 'Data mobil berhasil dihapus');
    }

    private function mergingData($request, $model = null)
    {
        $data = array_merge(
            $request->validated(),
            [
                'price' => (int)Str::replaceArray('.', [''], $request->validated()['price']),
                'status' => 'AVAILABLE',
                'created_by' => auth()->id()
            ]
        );

        if ($model) {
            $data = collect($data)->except(['created_by'])
                ->merge(
                    [
                        'car_image' => uploadImage($request, 'cars', $model->car_image),
                        'updated_by' => auth()->id()
                    ]
                )
                ->toArray();
        } else {
            $data['car_image'] = uploadImage($request, 'cars');
        }

        return $data;
    }

    private function getAllCarWithQueryString($status, $keyword)
    {
        $data = Car::where('status', $status)
            ->latest()
            ->paginate(10);

        $keyword = request()->search;

        if ($keyword) {
            $data = Car::where('status', $status)
                ->where(function ($query) use ($keyword) {
                    $query->where('name', 'LIKE', "%$keyword%")
                        ->orWhere('merk', 'LIKE', "%$keyword%")
                        ->orWhere('years', 'LIKE', "%$keyword%")
                        ->orWhere('plat_number', 'LIKE', "%$keyword%");
                })
                ->latest()
                ->paginate(10);
        }

        return $data;
    }
}
