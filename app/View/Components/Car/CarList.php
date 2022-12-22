<?php

namespace App\View\Components\Car;

use Illuminate\View\Component;

class CarList extends Component
{
    /**
     * The name of component's data
     *
     * @var mixed
     */
    public $cars, $type;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($cars, $type)
    {
        $this->cars = $cars;
        $this->type = $type;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.car.car-list');
    }
}
