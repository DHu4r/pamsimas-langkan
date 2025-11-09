<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Request;
use Illuminate\View\Component;

class SidebarLink extends Component
{
    /**
     * Create a new component instance.
     */
    public $href;
    public $active;
    public function __construct($href)
    {
        $this->href = $href;
        $this->active = Request::is(ltrim($href, '/')); // cek jika URL aktif
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.sidebar-link');
    }
}
