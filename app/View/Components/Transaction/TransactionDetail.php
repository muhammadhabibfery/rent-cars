<?php

namespace App\View\Components\Transaction;

use Illuminate\View\Component;

class TransactionDetail extends Component
{
    /**
     * The name of component's data
     *
     * @var mixed
     */
    public $transaction, $type;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($transaction, $type)
    {
        $this->transaction = $transaction;
        $this->type = $type;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.transaction.transaction-detail');
    }
}
