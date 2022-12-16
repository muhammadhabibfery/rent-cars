<?php

namespace App\View\Components\Customer;

use Illuminate\View\Component;

class CustomerForm extends Component
{

    /**
     * The name of component's data
     *
     * @var mixed
     */
    public $route, $customer, $type;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($route, $customer, $type)
    {
        $this->route = $route;
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
        return view('components.customer.customer-form');
    }
}
