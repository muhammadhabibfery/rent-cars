<?php

namespace App\View\Components\Transaction;

use Illuminate\View\Component;

class TransactionList extends Component
{
    /**
     * The name of component's data
     *
     * @var mixed
     */
    public $transactions, $type;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($transactions, $type)
    {
        $this->transactions = $transactions;
        $this->type = $type;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.transaction.transaction-list');
    }
}
