<?php

namespace App\View\Components\Admin;

use Illuminate\View\Component;

class Navbar extends Component
{
    /**
     * The name active menu.
     *
     * @var string
     */
    public $navbarActive;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($navbarActive)
    {
        $this->navbarActive = $navbarActive;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.admin.navbar');
    }
}
