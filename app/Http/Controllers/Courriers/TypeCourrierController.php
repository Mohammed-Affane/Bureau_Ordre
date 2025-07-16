<?php

namespace App\Http\Controllers\Courriers;

use App\Http\Controllers\Controller;
use App\Models\Courrier;
use App\Http\Traits\CourrierFilterTrait;
use App\Exports\CourriersExport;
use App\Exports\CourriersPdfExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

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
        $courriers = $query->paginate($this->perPage);
        return view('courriers.typesCourriers.arrive', compact('courriers'));
    }

    /**
     * Display outgoing mail courriers
     */
    public function courrierDepart()
    {
        $query = $this->applyCourrierFilters(Courrier::query(), 'depart');
        $courriers = $query->paginate($this->perPage);
        return view('courriers.typesCourriers.depart', compact('courriers'));
    }

    /**
     * Display internal mail courriers
     */
    public function courrierInterne(Request $request)
    {
       $query = $this->applyCourrierFilters(Courrier::query(), 'interne');
        $courriers = $query->paginate($this->perPage);
        return view('courriers.typesCourriers.interne', compact('courriers'));
    }

    /**
     * Display visa mail courriers
     */
    public function courrierVisa(Request $request)
    {
        $query = $this->applyCourrierFilters(Courrier::query(), 'visa');
        $courriers = $query->paginate($this->perPage);
        return view('courriers.typesCourriers.visa', compact('courriers'));
    }

    /**
     * Display decision mail courriers
     */
    public function courrierDecision(Request $request)
    {
        $query = $this->applyCourrierFilters(Courrier::query(), 'decision');
        $courriers = $query->paginate($this->perPage);
        return view('courriers.typesCourriers.decision', compact('courriers'));
    }

     public function exportExcel($type)
    {
        $title = $this->getTypeTitle($type);
        return Excel::download(new CourriersExport($type), "courriers_{$type}.xlsx");
    }

    // /**
    //  * Export to PDF for specific type
    //  */
    // public function exportPdf($type)
    // {
    //     $title = $this->getTypeTitle($type);
    //     return Excel::download(
    //         new CourriersPdfExport($type, $title), 
    //         "courriers_{$type}.pdf", 
    //         \Maatwebsite\Excel\Excel::DOMPDF
    //     );
    // }

    // /**
    //  * Direct PDF export
    //  */
    // public function exportDirectPdf($type)
    // {
    //     $query = $this->applyCourrierFilters(Courrier::query(), $type);
    //     $courriers = $query->with(['expediteur', 'agent', 'entiteExpediteur'])->get();
    //     $title = $this->getTypeTitle($type);
        
    //     $pdf = PDF::loadView('courriers.exports.courriers', compact('courriers', 'title'))
    //         ->setPaper('A4', 'landscape');
            
    //     return $pdf->download("courriers_{$type}.pdf");
    // }

    // /**
    //  * Get translated title for type
    //  */
    // protected function getTypeTitle($type)
    // {
    //     $titles = [
    //         'arrive' => 'Courriers d\'Arrivée',
    //         'depart' => 'Courriers de Départ',
    //         'interne' => 'Courriers Internes',
    //         'visa' => 'Courriers de Visa',
    //         'decision' => 'Courriers de Décision'
    //     ];
        
    //     return $titles[$type] ?? 'Liste des Courriers';
    // }
}