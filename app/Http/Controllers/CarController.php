<?php

namespace App\Http\Controllers;

use App\Car;
use App\Traits\ImageHandler;
use Illuminate\Http\Request;
use App\Http\Requests\CarRequest;
use Illuminate\Support\Facades\DB;

class CarController extends Controller
{

    use ImageHandler;

    /**
     * constant of route name
     *
     * @var string
     */
    private const ROUTE_INDEX = 'cars.index', ROUTE_TRASH = 'cars.trash';

    /**
     * authorizing the car controller
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
        $cars = $this->getAllCarsBySearch($request->status, $request->keyword, 10);

        return view('pages.cars.index', compact('cars'));
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
     * @param  \App\Http\Requests\CarRequest  $request
     * @return mixed
     */
    public function store(CarRequest $request)
    {
        $data = $this->mergeData(
            $request->validated(),
            ['created_by' => auth()->id(), 'status' => 'AVAILABLE', 'image' => $this->createImage($request, 'cars')]
        );

        return $this->checkProcess(
            self::ROUTE_INDEX,
            'Data mobil berhasil dibuat',
            function () use ($data) {
                if (!Car::create($data)) {
                    $this->deleteImage($data['image']);
                    throw new \Exception('Data mobil gagal dibuat');
                }

                return ['status' => 'AVAILABLE'];
            }
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function show(Car $car)
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
     * @param  \App\Http\Requests\CarRequest  $request
     * @param  \App\Car  $car
     * @return mixed
     */
    public function update(CarRequest $request, Car $car)
    {
        $data = $this->mergeData(
            $request->validated(),
            ['updated_by' => auth()->id(), 'image' => $this->createImage($request, 'cars', $car->image)]
        );

        return $this->checkProcess(
            self::ROUTE_INDEX,
            'Data mobil berhasil diubah',
            function () use ($car, $data) {
                if (!$car->update($data)) throw new \Exception('Data mobil gagal diubah');

                return ['status' => 'AVAILABLE'];
            }
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Car  $car
     * @return mixed
     */
    public function destroy(Car $car)
    {
        $failedMessage = "Data mobil gagal dihapus";

        return $this->checkProcess(
            self::ROUTE_INDEX,
            'Data mobil berhasil dihapus',
            function () use ($car, $failedMessage) {
                if (!$car->update(['deleted_by' => auth()->id()])) throw new \Exception($failedMessage);
                if (!$car->delete()) throw new \Exception($failedMessage);
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
        $cars = $this->getAllCarsBySearch($request->status, $request->keyword, 10, true);

        return view('pages.cars.trash.index-trash', compact('cars'));
    }

    /**
     * Display the specified deleted resource.
     *
     * @param  string  $platNumber
     * @return \Illuminate\Http\Response
     */
    public function showTrash(string $platNumber)
    {
        $car = $this->getOneDeletedCars($platNumber);

        return view('pages.cars.trash.detail-trash', compact('car'));
    }

    /**
     * restore the specified deleted resource.
     *
     * @param  string  $platNumber
     * @return mixed
     */
    public function restore(string $platNumber)
    {
        $car = $this->getOneDeletedCars($platNumber);
        $failedMessage = 'Data mobil gagal dikembalikan';

        return $this->checkProcess(
            self::ROUTE_TRASH,
            'Data mobil berhasil dikembalikan',
            function () use ($car, $failedMessage) {
                if (!$car->update(['deleted_by' => null])) throw new \Exception($failedMessage);
                if (!$car->restore()) throw new \Exception($failedMessage);

                return null;
            },
            true
        );
    }

    /**
     * remove the specified deleted resource
     *
     * @param  string $platNumber
     * @return mixed
     */
    public function forceDelete(string $platNumber)
    {
        $car = $this->getOneDeletedCars($platNumber);
        $image = $car->image;

        return $this->checkProcess(
            self::ROUTE_TRASH,
            'Data mobil berhasil dihapus secara permanen',
            function () use ($car, $image) {
                if ($car->forceDelete()) {
                    $this->deleteImage($image);
                } else {
                    throw new \Exception('Data mobil gagal dihapus secara permanen');
                }

                return  null;
            }
        );
    }

    /**
     * query all cars by search
     *
     * @param  string $keyword
     * @param  string $status
     * @param  int $number (define paginate data per page)
     * @param  bool $onlyDeleted (only trashed when delete using soft delete)
     * @return \illuminate\Pagination\LengthAwarePaginator
     */
    private function getAllCarsBySearch(?string $status, ?string $keyword, int $number, ?bool $onlyDeleted = false)
    {
        $cars = Car::query();

        if ($onlyDeleted) $cars->onlyTrashed();

        if (in_array($status, ['AVAILABLE', 'NOT AVAILABLE'])) $cars->where('status', $status);

        if ($keyword)
            $cars->where(function ($query) use ($keyword) {
                $query->where('name', 'LIKE', "%$keyword%")
                    ->orWhere('years', 'LIKE', "%$keyword%")
                    ->orWhere('plat_number', 'LIKE', "%$keyword%");
            });

        return $cars->latest()
            ->paginate($number);
    }

    /**
     * query a deleted car by plat_number field
     *
     * @param  string $platNumber
     * @return \illuminate\Database\Eloquent\Model
     */
    private function getOneDeletedCars(string $platNumber)
    {
        return Car::onlyTrashed()
            ->where('plat_number', $platNumber)
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
        $result = array_merge($validatedData, $additonalData);
        $result['price'] = str_replace('.', '', $result['price']);
        return $result;
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

            $params = $action();

            if ($dbTransaction) DB::commit();
        } catch (\Exception $e) {
            if ($dbTransaction) DB::rollBack();

            return redirect()->route($routeName, $params)
                ->with('failed', $e->getMessage());
        }

        return redirect()->route($routeName, $params)
            ->with('success', $successMessage);
    }
}
