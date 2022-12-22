<?php

namespace App\Http\Controllers;

use App\Car;
use App\Customer;
use App\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\TransactionRequest;
use Illuminate\Database\Eloquent\Builder;

class TransactionController extends Controller
{

    /**
     * authorizing the transaction controller
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(Transaction::class, 'transaction');
    }

    /**
     * constant of route name
     *
     * @var string
     */
    private const ROUTE_INDEX = 'transactions.index', ROUTE_TRASH = 'transactions.trash';

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $transactions = $this->getAllTransactionsBySearch($request->keyword, $request->status, 10);

        return view('pages.transactions.index', compact('transactions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $customers = $this->getAllCustomers();
        $cars = $this->getAllAvailableCars();

        return view('pages.transactions.create', compact('customers', 'cars'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\TransactionRequest  $request
     * @return mixed
     */
    public function store(TransactionRequest $request)
    {
        $data = collect($this->mergeData($request->validated()))->except('cars')->toArray();
        $cars = collect($this->mergeData($request->validated()))->only('cars')->toArray()['cars'];
        $failedMessage = 'Data transaksi gagal dibuat';

        return $this->checkProcess(
            self::ROUTE_INDEX,
            'Data transaksi berhasil dibuat',
            function () use ($data, $cars, $failedMessage) {
                if ($transaction = Transaction::create($data)) {
                    if (!$this->updateCarStatus($cars, 'NOT AVAILABLE')) throw new \Exception($failedMessage);
                    $transaction->cars()->attach($cars);
                } else {
                    throw new \Exception($failedMessage);
                }
            }
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        return view('pages.transactions.detail', compact('transaction'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function edit(Transaction $transaction)
    {
        $this->updateTotalLateTransaction($transaction);

        return view('pages.transactions.edit', compact('transaction'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\TransactionRequest  $request
     * @param  \App\Transaction  $transaction
     * @return mixed
     */
    public function update(TransactionRequest $request, Transaction $transaction)
    {
        $validatedData = [
            'payment_amount' => (int) str_replace('.', '', $request->validated()['payment_amount']) + $transaction->payment_amount
        ];
        $failedMessage = 'Data transaksi gagal diubah';

        return $this->checkProcess(
            self::ROUTE_INDEX,
            'Data transaksi berhasil diubah',
            function () use ($validatedData, $transaction, $failedMessage) {
                if ($transaction->update($this->mergeData($validatedData, false))) {
                    $carsId = $this->getCarsId($transaction->cars);
                    if (!$this->updateCarStatus($carsId, 'AVAILABLE')) throw new \Exception($failedMessage);
                } else {
                    throw new \Exception($failedMessage);
                }
            }
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Transaction  $transaction
     * @return mixed
     */
    public function destroy(Transaction $transaction)
    {
        $failedMessage = 'Data transaksi gagal dihapus';

        return $this->checkProcess(
            self::ROUTE_INDEX,
            'Data transaksi berhasil dihapus',
            function () use ($transaction, $failedMessage) {
                if ($transaction->status === 'DP') {
                    $carsId = $this->getCarsId($transaction->cars);
                    if (!$this->updateCarStatus($carsId, 'AVAILABLE')) throw new \Exception($failedMessage);
                }

                if (!$transaction->update(['deleted_by' => auth()->id()])) throw new \Exception($failedMessage);
                if (!$transaction->delete()) throw new \Exception($failedMessage);
            }
        );
    }

    /**
     * print transaction after created or updated
     *
     * @param  \App\Transaction $transaction
     * @param  bool $update (print transaction after updated)
     * @return mixed
     */
    public function printTransaction(Transaction $transaction, ?bool $update = false)
    {
        $status = 'created';
        $pdfFile = 'pages.transactions.pdf.transaction-created';

        if ($update) {
            $status = 'updated';
            $pdfFile = 'pages.transactions.pdf.transaction-updated';
        }

        $fileName = 'invoice-' . $status . '-'  . $transaction->customer->name . '-' . Str::of($transaction->invoice_number)->limit(15, '') . '.pdf';

        return PDF::loadview($pdfFile, compact('transaction'))
            ->stream($fileName);
    }

    /**
     * generate invoice number for a transaction
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function generateInvoiceNumber()
    {
        if (request()->wantsJson())
            return response()->json([
                'status' => 200,
                'data' => [
                    'invoice_number' => "KBVTRANS-" . date('dmy') . Str::random(16)
                ]
            ]);
    }

    /**
     * generate return amount for a transaction
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function generateReturnAmount(Request $request)
    {
        if (request()->wantsJson()) {
            $paymentAmount = integerFormat($request->payment_amount);
            $totalPrice = integerFormat($request->total_price);

            if ($paymentAmount < $totalPrice) return false;

            $returnAmount = $paymentAmount - $totalPrice;
            return response()->json([
                'status' => 200,
                'data' => [
                    'return_amount' => number_format($returnAmount, 0, '.', '.')
                ]
            ]);
        }
    }

    /**
     * query all transactions by search
     *
     * @param  string $keyword
     * @param  string $status
     * @param  int $number (define paginate data per page)
     * @param  bool $onlyDeleted (only trashed when delete using soft delete)
     * @return \illuminate\Pagination\LengthAwarePaginator
     */
    private function getAllTransactionsBySearch(?string $keyword, ?string $status, int $number, ?bool $onlyDeleted = false)
    {
        $transactions = Transaction::with(['customer' => fn ($query) => $query->select('id', 'name', 'phone')->where('name', 'LIKE', "%$keyword%")]);

        if ($onlyDeleted) $transactions->onlyTrashed();

        if ($keyword) $transactions->whereHas('customer', fn (Builder $query) => $query->where('name', 'LIKE', "%$keyword%"));

        if (in_array($status, ['>', '<', 'COMPLETED'])) $transactions->transactionStatus($status);

        return  $transactions->latest()
            ->paginate($number);
    }

    /**
     * query all customers
     *
     * @return \illuminate\Database\Eloquent\Collection
     */
    private function getAllCustomers()
    {
        return Customer::select('id', 'name', 'phone')
            ->latest()
            ->get();
    }

    /**
     * query all available cars
     *
     * @return \illuminate\Database\Eloquent\Collection
     */
    private function getAllAvailableCars()
    {
        return Car::select('id', 'name', 'years', 'plat_number', 'color', 'price')
            ->where('status', 'AVAILABLE')
            ->latest()
            ->get();
    }

    /**
     * query one available car
     *
     * @param int $id
     * @return \illuminate\Database\Eloquent\Model
     */
    private function getOneCar(int $id)
    {
        return Car::findOrFail($id);
    }

    /**
     * get the cars id
     *
     * @param  object $cars
     * @return array
     */
    private function getCarsId(object $cars)
    {
        $carsId = [];
        foreach ($cars as $car) {
            $carsId[] = $car->id;
        }

        return $carsId;
    }

    /**
     * update the car's status
     *
     * @param  array $ids
     * @param  string $status
     * @return bool
     */
    private function updateCarStatus(array $ids, string $status)
    {
        foreach ($ids as $id) {
            if (!$this->getOneCar($id)->update(['status' => $status])) return false;
        }

        return true;
    }

    /**
     * update the transaction's total late
     *
     * @param  object $transaction
     * @return mixed
     */
    private function updateTotalLateTransaction(object $transaction)
    {
        if (now() > $transaction->finish_date && empty($transaction->total_late)) {
            $totalLate = date_diff(transformDateFormat($transaction->finish_date), transformDateFormat(now()->modify('+ 1 days')))
                ->format('%a');

            $transaction->total_late = (int) $totalLate;
            $transaction->save();

            return $this->updatePenaltyAmountTransaction($transaction);
        }
    }

    /**
     * update the transaction's penalty amount
     *
     * @param  object $transaction
     * @return mixed
     */
    private function updatePenaltyAmountTransaction(object $transaction)
    {
        if ($transaction->total_late) {
            $totalPriceCars = 0;

            foreach ($transaction->cars as $car) {
                $totalPriceCars += (int) $car->price;
            }

            $transaction->penalty_amount = $totalPriceCars * $transaction->total_late;
            $transaction->save();

            return $this->updateTotalPriceTransaction($transaction);
        }
    }

    /**
     * update the transaction's total price
     *
     * @param  object $transaction
     * @return mixed
     */
    private function updateTotalPriceTransaction(object $transaction)
    {
        if ($transaction->penalty_amount) {
            $transaction->total_price += $transaction->penalty_amount;
            $transaction->save();
        }
    }

    /**
     * merge data into an array
     *
     * @param  array $validatedData
     * @param  bool $update (merge data in update method)
     * @return array
     */
    private function mergeData(array $validatedData, ?bool $create = true)
    {
        $additonalData = [
            'updated_by' => auth()->id(),
            'return_date' => transformDateFormat(now(), 'Y-m-d H:i:s'),
            'status' => 'COMPLETED'
        ];

        if ($create) {
            $additonalData = [
                'created_by' => auth()->id(),
                'finish_date' => transformDateFormat($validatedData['start_date'])->addDays($validatedData['duration']),
                'status' => 'DP'
            ];
        }

        return array_merge($validatedData, $additonalData);
    }

    /**
     * Check one or more processes and catch them if fail
     *
     * @param  string $routeName
     * @param  string $successMessage
     * @param  callable $action
     * @return \Illuminate\Http\RedirectResponse
     */
    private function checkProcess(string $routeName, string $successMessage, callable $action)
    {
        try {
            DB::beginTransaction();

            $action();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->route($routeName)
                ->with('failed', $e->getMessage());
        }

        return redirect()->route($routeName)
            ->with('success', $successMessage);
    }
}
