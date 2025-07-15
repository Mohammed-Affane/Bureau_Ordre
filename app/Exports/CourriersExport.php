<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class CourriersExport implements FromView
{
    protected $courriers;
    protected $type;
    protected $filters;

    public function __construct($courriers, $type, $filters)
    {
        $this->courriers = $courriers;
        $this->type = $type;
        $this->filters = $filters;
    }

    public function view(): View
    {
        return view('courriers.exports.courriers_excel', [
            'courriers' => $this->courriers,
            'type' => $this->type,
            'filters' => $this->filters,
        ]);
    }
}
