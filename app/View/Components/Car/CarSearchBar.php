<?php

namespace App\View\Components\Car;

use Illuminate\View\Component;

class CarSearchBar extends Component
{
    /**
     * The name of component's data
     *
     * @var mixed
     */
    public $route, $type;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($route, $type)
    {
        $this->route = $route;
        $this->type = $type;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.car.car-search-bar');
    }
}
