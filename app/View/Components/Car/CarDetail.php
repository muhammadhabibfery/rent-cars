<?php

namespace App\View\Components\Car;

use Illuminate\View\Component;

class CarDetail extends Component
{
    /**
     * The name of component's data
     *
     * @var mixed
     */
    public $car, $type;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($car, $type)
    {
        $this->car = $car;
        $this->type = $type;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.car.car-detail');
    }
}
