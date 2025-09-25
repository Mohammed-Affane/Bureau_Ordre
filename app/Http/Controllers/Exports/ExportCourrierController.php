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
        ->with(['expediteur', 'agent', 'entiteExpediteur', 'courrierDestinatairePivot'])
        ->courrierByUserRole();

    // ✅ apply filters correctly
    $this->applyFilters($query, $request);

    //dd($query->toSql(), $query->getBindings(), $query->count());


    $courriersCount = $query->count();
    if ($courriersCount === 0) {
        return back()->with('error', 'Aucun courrier trouvé pour l\'export PDF.');
    }

    // ✅ prepare header with correct date range label
    $html = view('courriers.exports.partials.pdfHeader', [
        'type' => $type,
        'filters' => $request->all(),
        'dateRangeLabel' => $this->getDateRangeLabel($request),
    ])->render();

    // ✅ chunk already-filtered results
    $query->chunk(300, function ($courriersChunk) use (&$html, $type, $request) {
        $html .= view('courriers.exports.partials.courriersPDFChunk', [
            'courriers' => $courriersChunk,
            'type' => $type,
            'filters' => $request->all(),
        ])->render();

        $html .= '<div style="page-break-after: always;"></div>';
    });

    $html .= '</body></html>';

    $pdfContent = GpdfFacade::generate($html);

    return response($pdfContent, 200, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'attachment; filename="courriers-' . $type . '.pdf"',
    ]);
}

    protected function getDateRangeLabel($request)
    {
        switch ($request->date_range) {
            case 'today':
                return 'Aujourd\'hui';
            case 'week':
                return 'Cette semaine';
            case 'month':
                return 'Ce mois';
            case 'year':
                return 'Cette année';
            case 'custom':
                $from = $request->date_from ? \Carbon\Carbon::parse($request->date_from)->format('d/m/Y') : '';
                $to   = $request->date_to ? \Carbon\Carbon::parse($request->date_to)->format('d/m/Y') : '';
                return "Du $from au $to";
            default:
                return 'Non spécifié';
        }
    }


        
    public function exportExcel(Request $request, $type)
    {
        $query = Courrier::query()
            ->where('type_courrier', $type)
            ->with(['expediteur', 'agent', 'entiteExpediteur', 'courrierDestinatairePivot'])
            ->courrierByUserRole();

        $this->applyFilters($query, $request);

        $courriers = $query->get();

        // ❗ Don't export if nothing is found
        if ($courriers->isEmpty()) {
            return back()->with('error', 'Aucun courrier trouvé pour l\'export Excel.');
        }

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
                        $q->where('nom', 'like', '%'.$request->search.'%')
                        ->orWhere('CIN', 'like', '%'.$request->search.'%');
                    });
            });
        }

       if ($request->filled('statut')) {
        $query->where('statut', $request->statut);
    }

    if ($request->filled('priorite')) {
        $query->where('priorite', $request->priorite);
    }

    if ($request->filled('date_range')) {
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