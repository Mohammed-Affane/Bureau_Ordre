<?php

namespace App\Http\Controllers\Exports;


use App\Http\Controllers\Controller;
use App\Models\Courrier;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;    
use PDF; 
use Illuminate\Contracts\View\View;
use Omaralalwi\Gpdf\Facade\Gpdf as GpdfFacade;
use App\Exports\CourriersExport;

class ExportCourrierController extends Controller
{
   public function exportPdf(Request $request, $type)
{
    set_time_limit(0);
    ini_set('memory_limit', '512M');

    $query = Courrier::where('type_courrier', $type)
        ->with(['expediteur', 'agent', 'entiteExpediteur', 'courrierDestinatairePivot']);

    $this->applyFilters($query, $request);

    $html = '';

    // Add the PDF header layout
    $html .= view('courriers.exports.partials.pdfHeader', [
        'type' => $type,
        'filters' => $request->all(),
    ])->render();

    // Chunk and render each block
    $query->chunk(300, function ($courriersChunk) use (&$html, $type, $request) {
        $html .= view('courriers.exports.partials.courriersPDFChunk', [
            'courriers' => $courriersChunk,
            'type' => $type,
            'filters' => $request->all(),
        ])->render();

        // Optional page break between chunks
        $html .= '<div style="page-break-after: always;"></div>';
    });

    $pdfContent = GpdfFacade::generate($html);

    return response($pdfContent, 200, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'attachment; filename="courriers-' . $type . '.pdf"',
    ]);
}

    
    public function exportExcel(Request $request, $type)
    {
            // Start with a fresh query
        $query = Courrier::query()->where('type_courrier', $type)
            ->with([
                'expediteur',
                'agent',
                'entiteExpediteur',
                'courrierDestinatairePivot'
            ]);

        // Apply filters BEFORE getting the results
        $this->applyFilters($query, $request);

        // Get the filtered results
        $courriers = $query->get();
           
        // Generate filename with timestamp
        $filename = "courriers-{$type}-" . now()->format('Ymd-His') . ".xlsx";


        return Excel::download(
            new CourriersExport($courriers, $type, $request->all()),
            $filename
        );
    }

    protected function applyFilters($query, $request)
    {
        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('reference_arrive', 'like', '%'.$request->search.'%')
                    ->orWhere('reference_depart', 'like', '%'.$request->search.'%')
                    ->orWhere('reference_dec', 'like', '%'.$request->search.'%')
                    ->orWhere('reference_visa', 'like', '%'.$request->search.'%')
                    ->orWhere('reference_bo', 'like', '%'.$request->search.'%')
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