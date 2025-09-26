<?php

namespace App\Http\Controllers\Courriers;

use PDF;
use App\Models\Courrier;
use App\Models\Affectation;
use Illuminate\Http\Request;
use App\Exports\CourriersExport;
use App\Exports\CourriersPdfExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Traits\CourrierFilterTrait;

class TypeCourrierController extends Controller
{
    use CourrierFilterTrait;

    protected $perPage = 5;

    /**
     * Display incoming mail courriers
     */
    public function courrierArrivee(Request $request)
    {
        
        $query = $this->applyCourrierFilters(Courrier::query(), 'arrive');
        $courriers = $query->latest()->paginate($this->perPage)->appends($request->all());

        return view('courriers.typesCourriers.arrive', compact('courriers'));
    }

    /**
     * Display outgoing mail courriers
     */
    public function courrierDepart(Request $request)
    {
        $query = $this->applyCourrierFilters(Courrier::query(), 'depart');
        $courriers = $query->latest()->paginate($this->perPage)->appends($request->all());
        return view('courriers.typesCourriers.depart', compact('courriers'));
    }

    /**
     * Display internal mail courriers
     */
    public function courrierInterne(Request $request)
    {
       $query = $this->applyCourrierFilters(Courrier::query(), 'interne');
        $courriers = $query->latest()->paginate($this->perPage)->appends($request->all());
        return view('courriers.typesCourriers.interne', compact('courriers'));
    }

    /**
     * Display visa mail courriers
     */
    public function courrierVisa(Request $request)
    {
        $query = $this->applyCourrierFilters(Courrier::query(), 'visa');
        $courriers = $query->latest()->paginate($this->perPage)->appends($request->all());
        return view('courriers.typesCourriers.visa', compact('courriers'));
    }

    /**
     * Display decision mail courriers
     */
    public function courrierDecision(Request $request)
    {
        $query = $this->applyCourrierFilters(Courrier::query(), 'decision');
        $courriers = $query->latest()->paginate($this->perPage)->appends($request->all());
        return view('courriers.typesCourriers.decision', compact('courriers'));
    }

     public function exportExcel($type)
    {
        $title = $this->getTypeTitle($type);
        return Excel::download(new CourriersExport($type), "courriers_{$type}.xlsx");
    }

}