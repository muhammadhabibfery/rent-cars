<?php

namespace App\View\Components\Customer;

use Illuminate\View\Component;

class CustomerDetail extends Component
{

    /**
     * The name of component's data
     *
     * @var mixed
     */
    public $customer, $type;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($customer, $type)
    {
        $this->customer = $customer;
        $this->type = $type;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.customer.customer-detail');
    }
}
