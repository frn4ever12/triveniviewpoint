<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Breadcrumb extends Component
{
    public string $title;
    public ?string $route;
    public ?string $button;
    public ?string $icon;

    public function __construct(
        string $title,
        ?string $route = null,
        ?string $button = 'Add New',
        ?string $icon = 'bi-plus-circle'
    ) {
        $this->title = $title;
        $this->route = $route;
        $this->button = $button;
        $this->icon = $icon;
    }



    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.breadcrumb');
    }
}
