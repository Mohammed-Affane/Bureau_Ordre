<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Actions extends Component
{
    /**
     * Create a new component instance.
     */
      public $type;

    public function __construct($type='arrive', $courrier = null)
    {
     
        $this->type=$type;
        $this->courrier=$courrier;
   
    }


    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.actions', [
        'courrier' => $this->courrier,
        'type' => $this->type
    ]);
    }
}
