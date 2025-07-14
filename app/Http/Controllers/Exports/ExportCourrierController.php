<?php

namespace App\Http\Controllers\Exports;


use App\Http\Controllers\Controller;
use App\Models\Courrier;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;    
use PDF;
use App\Exports\CourriersExport;
use App\Exports\CourriersPdfExport; 
use Illuminate\Contracts\View\View;

use Omaralalwi\Gpdf\Facade\Gpdf as GpdfFacade;





class ExportCourrierController extends Controller
{
    public function exportPdf(Request $request, $type)
    {
        // Get filtered courriers based on request parameters
         $query = Courrier::where('type_courrier', $type)
             ->with(['expediteur', 'agent', 'entiteExpediteur','courrierDestinatairePivot' 
         ]);

          $courriers = $query->get();
        
        // Generate PDF
        // $pdf = PDF::loadView('courriers.exports.courriersPDF', [
        //     'courriers' => $courriers,
        //     'type' => $type,
        //     'filters' => $request->all(),
        // ])->setPaper('a4', 'landscape');

        $html = view('courriers.exports.courriersPDF',[
             'courriers' => $courriers,
             'type' => $type,
             'filters' => $request->all(),
         ])->render();
    $pdfContent = GpdfFacade::generate($html);
    return response($pdfContent, 200, ['Content-Type' => 'application/pdf']);
        
        // Apply filters from request
        // $this->applyFilters($query, $request);
        
        
        
        // Download PDF
        return $pdf->download("courriers-{$type}.pdf");
    }

    protected function applyFilters($query, $request)
    {
        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('reference_arrive', 'like', '%'.$request->search.'%')
                  ->orWhere('objet', 'like', '%'.$request->search.'%')
                  ->orWhereHas('expediteur', function($q) use ($request) {
                      $q->where('nom', 'like', '%'.$request->search.'%');
                  });
            });
        }

        if ($request->has('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->has('priorite')) {
            $query->where('priorite', $request->priorite);
        }

        if ($request->has('date_range')) {
            $this->applyDateRangeFilter($query, $request);
        }
    }

    protected function applyDateRangeFilter($query, $request)
    {
        $dateField = 'date_enregistrement';
        
        if ($request->date_range === 'today') {
            $query->whereDate($dateField, today());
        } elseif ($request->date_range === 'week') {
            $query->whereBetween($dateField, [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($request->date_range === 'month') {
            $query->whereBetween($dateField, [now()->startOfMonth(), now()->endOfMonth()]);
        } elseif ($request->date_range === 'year') {
            $query->whereBetween($dateField, [now()->startOfYear(), now()->endOfYear()]);
        } elseif ($request->date_range === 'custom' && ($request->date_from || $request->date_to)) {
            if ($request->date_from) {
                $query->whereDate($dateField, '>=', $request->date_from);
            }
            if ($request->date_to) {
                $query->whereDate($dateField, '<=', $request->date_to);
            }
        }
    }
}